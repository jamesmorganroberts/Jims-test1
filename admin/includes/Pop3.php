<?php

/*------------------------------------------------------------------------------------------------*/	

class Pop3 {

	var $hostname="";
	var $port=110;
	var $tls=0;
	var $quit_handshake=1;
	var $error="";
	var $authentication_mechanism="USER";
	var $realm="";
	var $workstation="";
	var $join_continuation_header_lines=1;

	var $connection=0;
	var $state="DISCONNECTED";
	var $greeting="";
	var $must_update=0;
	var $debug=0;
	var $html_debug=0;
	var $next_token="";
	var $message_buffer="";
	var $connection_name = '';

	function Tokenize($string,$separator="") {
		if(!strcmp($separator,"")) {
			$separator=$string;
			$string=$this->next_token;
		}
		for($character=0;$character<strlen($separator);$character++) {
			if(GetType($position=strpos($string,$separator[$character]))=="integer") {
				$found=(IsSet($found) ? min($found,$position) : $position);
			}
		}
		if(IsSet($found)) {
			$this->next_token=substr($string,$found+1);
			return(substr($string,0,$found));
		} else {
			$this->next_token="";
			return($string);
		}
	}
	
	function SetError($error) {
		return($this->error=$error);
	}
	
	function OutputDebug($message) {
		$message.="\n";
		if($this->html_debug) {
			$message=str_replace("\n","<br />\n",HtmlSpecialChars($message));
		}
		echo $message;
		flush();
	}
	
	function GetLine() {
		for($line="";;) {
			if(feof($this->connection)) {
				return(0);
			}
			$line.=fgets($this->connection,100);
			$length=strlen($line);
			if($length>=2 && substr($line,$length-2,2)=="\r\n") {
				$line=substr($line,0,$length-2);
				if($this->debug) {
					$this->OutputDebug("S $line");
				}
				return($line);
			}
		}
	}
	
	function PutLine($line) {
		if($this->debug) {
			$this->OutputDebug("C $line");
		}
		return(fputs($this->connection,"$line\r\n"));
	}
	
	function OpenConnection() {
		if($this->tls) {
			$version=explode(".",function_exists("phpversion") ? phpversion() : "3.0.7");
			$php_version=intval($version[0])*1000000+intval($version[1])*1000+intval($version[2]);
			if($php_version<4003000) {
				return("establishing TLS connections requires at least PHP version 4.3.0");
			}
			if(!function_exists("extension_loaded")
			|| !extension_loaded("openssl")) {
				return("establishing TLS connections requires the OpenSSL extension enabled");
			}
		}
		if($this->hostname=="") {
			return($this->SetError("2 it was not specified a valid hostname"));
		}
		if($this->debug) {
			$this->OutputDebug("Connecting to ".$this->hostname." ...");
		}
		if(($this->connection=@fsockopen(($this->tls ? "tls://" : "").$this->hostname, $this->port, $error, $error_message))==0) {
			switch($error)
			{
				case -3:
					return($this->SetError("-3 socket could not be created"));
				case -4:
					return($this->SetError("-4 dns lookup on hostname \"$hostname\" failed"));
				case -5:
					return($this->SetError("-5 connection refused or timed out"));
				case -6:
					return($this->SetError("-6 fdopen() call failed"));
				case -7:
					return($this->SetError("-7 setvbuf() call failed"));
				default:
					return($this->SetError($error." could not connect to the host \"".$this->hostname."\": ".$error_message));
			}
		}
		return("");
	}
	
	function CloseConnection() {
		if($this->debug) {
			$this->OutputDebug("Closing connection.");
		}
		if($this->connection!=0) {
			fclose($this->connection);
			$this->connection=0;
		}
	}
	
	/* Open method - set the object variable $hostname to the POP3 server address. */
	
	function Open() {
		if($this->state!="DISCONNECTED") {
			return($this->SetError("1 a connection is already opened"));
		}
		if(($error=$this->OpenConnection())!="") {
			return($error);
		}
		$greeting=$this->GetLine();
		if(GetType($greeting)!="string"
		|| $this->Tokenize($greeting," ")!="+OK") {
			$this->CloseConnection();
			return($this->SetError("3 POP3 server greeting was not found"));
		}
		$this->Tokenize("<");
		$this->greeting = $this->Tokenize(">");
		$this->must_update=0;
		$this->state="AUTHORIZATION";
		return("");
	}
	
	/* Close method - this method must be called at least if there are any
	messages to be deleted */
	
	function Close() {
		if($this->state=="DISCONNECTED") {
			return($this->SetError("no connection was opened"));
		}
		while($this->state=='GETMESSAGE')
		{
			if(strlen($error=$this->GetMessage(8000, $message, $end_of_message))) {
				return($error);
			}
		}
		if($this->must_update
		|| $this->quit_handshake) {
			if($this->PutLine("QUIT")==0) {
				return($this->SetError("Could not send the QUIT command"));
			}
			$response=$this->GetLine();
			if(GetType($response)!="string") {
				return($this->SetError("Could not get quit command response"));
			}
			if($this->Tokenize($response," ")!="+OK") {
				return($this->SetError("Could not quit the connection: ".$this->Tokenize("\r\n")));
			}
		}
		$this->CloseConnection();
		$this->state="DISCONNECTED";
		Pop3::SetConnection(-1, $this->connection_name, $this);
		return("");
	}
	
	/* Login method - pass the user name and password of POP account.  Set
	$apop to 1 or 0 wether you want to login using APOP method or not.  */
	
	function Login($user,$password,$apop=0) {

		if($this->state!="AUTHORIZATION") {
			return($this->SetError("connection is not in AUTHORIZATION state"));
		}
		if($apop) {
			if(!strcmp($this->greeting,"")) {
				return($this->SetError("Server does not seem to support APOP authentication"));
			}
			if($this->PutLine("APOP $user ".md5("<".$this->greeting.">".$password))==0) {
				return($this->SetError("Could not send the APOP command"));
			}
			$response=$this->GetLine();
			if(GetType($response)!="string") {
				return($this->SetError("Could not get APOP login command response"));
			}
			if($this->Tokenize($response," ")!="+OK") {
				return($this->SetError("APOP login failed: ".$this->Tokenize("\r\n")));
			}
		} else {
			$authenticated=0;
			if(strcmp($this->authentication_mechanism,"USER")
			&& function_exists("class_exists")
			&& class_exists("sasl_client_class"))
			{
				if(strlen($this->authentication_mechanism)) {
					$mechanisms=array($this->authentication_mechanism);
				}	else {
					$mechanisms=array();
					if($this->PutLine("CAPA")==0) {
						return($this->SetError("Could not send the CAPA command"));
					}
					$response=$this->GetLine();
					if(GetType($response)!="string") {
						return($this->SetError("Could not get CAPA command response"));
					}
					if(!strcmp($this->Tokenize($response," "),"+OK"))
					{
						for(;;)
						{
							$response=$this->GetLine();
							if(GetType($response)!="string") {
								return($this->SetError("Could not retrieve the supported authentication methods"));
							}
							switch($this->Tokenize($response," "))
							{
								case ".":
									break 2;
								case "SASL":
									for($method=1;strlen($mechanism=$this->Tokenize(" "));$method++)
										$mechanisms[]=$mechanism;
									break;
							}
						}
					}
				}

				$sasl=new sasl_client_class;
				$sasl->SetCredential("user",$user);
				$sasl->SetCredential("password",$password);
				if(strlen($this->realm)) {
					$sasl->SetCredential("realm",$this->realm);
				}
				if(strlen($this->workstation)) {
					$sasl->SetCredential("workstation",$this->workstation);
				}
				do {
					$status=$sasl->Start($mechanisms,$message,$interactions);
				}
				while($status==SASL_INTERACT);
				switch($status) {
					case SASL_CONTINUE:
						break;
					case SASL_NOMECH:
						if(strlen($this->authentication_mechanism)) {
							return($this->SetError("authenticated mechanism ".$this->authentication_mechanism." may not be used: ".$sasl->error));
						}
						break;
					default:
						return($this->SetError("Could not start the SASL authentication client: ".$sasl->error));
				}

				if(strlen($sasl->mechanism)) {
					if($this->PutLine("AUTH ".$sasl->mechanism.(IsSet($message) ? " ".base64_encode($message) : ""))==0) {
						return("Could not send the AUTH command");
					}
					$response=$this->GetLine();
					if(GetType($response)!="string") {
						return("Could not get AUTH command response");
					}
					switch($this->Tokenize($response," "))
					{
						case "+OK":
							$response="";
							break;
						case "+":
							$response=base64_decode($this->Tokenize("\r\n"));
							break;
						default:
							return($this->SetError("Authentication error: ".$this->Tokenize("\r\n")));
					}
					for(;!$authenticated;)
					{
						do
						{
							$status=$sasl->Step($response,$message,$interactions);
						}
						while($status==SASL_INTERACT);
						switch($status)
						{
							case SASL_CONTINUE:
								if($this->PutLine(base64_encode($message))==0)
									return("Could not send message authentication step message");
								$response=$this->GetLine();
								if(GetType($response)!="string")
									return("Could not get authentication step message response");
								switch($this->Tokenize($response," "))
								{
									case "+OK":
										$authenticated=1;
										break;
									case "+":
										$response=base64_decode($this->Tokenize("\r\n"));
										break;
									default:
										return($this->SetError("Authentication error: ".$this->Tokenize("\r\n")));
								}
							break;
							default:
								return($this->SetError("Could not process the SASL authentication step: ".$sasl->error));
						}
					}
				}
			}
			if(!$authenticated) {
				if($this->PutLine("USER $user")==0) {
					return($this->SetError("Could not send the USER command"));
				}
				$response=$this->GetLine();
				if(GetType($response)!="string") {
					return($this->SetError("Could not get user login entry response"));
				}
				if($this->Tokenize($response," ")!="+OK") {
					return($this->SetError("User error: ".$this->Tokenize("\r\n")));
				}
				if($this->PutLine("PASS $password")==0) {
					return($this->SetError("Could not send the PASS command"));
				}
				$response=$this->GetLine();
				if(GetType($response)!="string") {
 					return($this->SetError("Could not get login password entry response"));
 				}
				if($this->Tokenize($response," ")!="+OK") {
					return($this->SetError("Password error: ".$this->Tokenize("\r\n")));
				}
			}
		}
		$this->state="TRANSACTION";
		return("");
	}
	
	/* Statistics method - pass references to variables to hold the number of
	messages in the mail box and the size that they take in bytes.  */
	
	function Statistics(&$messages,&$size) {
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($this->PutLine("STAT")==0) {
			return($this->SetError("Could not send the STAT command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not get the statistics command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not get the statistics: ".$this->Tokenize("\r\n")));
		}
		$messages=$this->Tokenize(" ");
		$size=$this->Tokenize(" ");
		return("");
	}
	
	/* ListMessages method - the $message argument indicates the number of a
	message to be listed.  If you specify an empty string it will list all
	messages in the mail box.  The $unique_id flag indicates if you want
	to list the each message unique identifier, otherwise it will
	return the size of each message listed.  If you list all messages the
	result will be returned in an array. */
	
	function ListMessages($message,$unique_id) {
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($unique_id) {
			$list_command="UIDL";
		}
		else {
			$list_command="LIST";
		}
		if($this->PutLine("$list_command".($message ? " ".$message : ""))==0) {
			return($this->SetError("Could not send the $list_command command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not get message list command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not get the message listing: ".$this->Tokenize("\r\n")));
		}
		if($message=="")
		{
			for($messages=array();;)
			{
				$response=$this->GetLine();
				if(GetType($response)!="string") {
					return($this->SetError("Could not get message list response"));
				}
				if($response==".") {
					break;
				}
				$message=intval($this->Tokenize($response," "));
				if($unique_id) {
					$messages[$message]=$this->Tokenize(" ");
				} else {
					$messages[$message]=intval($this->Tokenize(" "));
				}
			}
			return($messages);
		}
		else
		{
			$message=intval($this->Tokenize(" "));
			$value=$this->Tokenize(" ");
			return($unique_id ? $value : intval($value));
		}
	}

	/* RetrieveMessage method - the $message argument indicates the number of
	a message to be listed.  Pass a reference variables that will hold the
	arrays of the $header and $body lines.  The $lines argument tells how
	many lines of the message are to be retrieved.  Pass a negative number
	if you want to retrieve the whole message. */
	
	function RetrieveMessage($message,&$headers,&$body,$lines) {
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($lines<0) {
			$command="RETR";
			$arguments="$message";
		} else {
			$command="TOP";
			$arguments="$message $lines";
		}
		if($this->PutLine("$command $arguments")==0) {
			return($this->SetError("Could not send the $command command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not get message retrieval command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not retrieve the message: ".$this->Tokenize("\r\n")));
		}
		for($headers=$body=array(),$line=0;;) {
			$response=$this->GetLine();
			if(GetType($response)!="string") {
				return($this->SetError("Could not retrieve the message"));
			}
			switch($response)
			{
				case ".":
					return("");
				case "":
					break 2;
				default:
					if(substr($response,0,1)==".") {
						$response=substr($response,1,strlen($response)-1);
					}
				break;
			}
			if($this->join_continuation_header_lines
			&& $line>0
			&& ($response[0]=="\t"
			|| $response[0]==" ")) {
				$headers[$line-1].=$response;
			} else {
				$headers[$line]=$response;
				$line++;
			}
		}
		for($line=0;;$line++) {
			$response=$this->GetLine();
			if(GetType($response)!="string") {
				return($this->SetError("Could not retrieve the message"));
			}
			switch($response)
			{
				case ".":
					return("");
				default:
					if(substr($response,0,1)==".") {
						$response=substr($response,1,strlen($response)-1);
					}
				break;
			}
			$body[$line]=$response;
		}
		return("");
	}
	
	/* OpenMessage method - the $message argument indicates the number of
	a message to be opened. The $lines argument tells how many lines of
	the message are to be retrieved.  Pass a negative number if you want
	to retrieve the whole message. */
	
	function OpenMessage($message, $lines=-1)
	{
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($lines<0) {
			$command="RETR";
			$arguments="$message";
		} else {
			$command="TOP";
			$arguments="$message $lines";
		}
		if($this->PutLine("$command $arguments")==0) {
			return($this->SetError("Could not send the $command command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not get message retrieval command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not retrieve the message: ".$this->Tokenize("\r\n")));
		}
		$this->state="GETMESSAGE";
		$this->message_buffer="";
		return("");
	}
	
	/* GetMessage method - the $count argument indicates the number of bytes
	to be read from an opened message. The $message returns by reference
	the data read from the message. The $end_of_message argument returns
	by reference a boolean value indicated whether it was reached the end
	of the message. */
	
	function GetMessage($count, &$message, &$end_of_message)
	{
		if($this->state!="GETMESSAGE") {
			return($this->SetError("connection is not in GETMESSAGE state"));
		}
		$message="";
		$end_of_message=0;
		while($count>strlen($this->message_buffer)
		&& !$end_of_message) {
			$response=$this->GetLine();
			if(GetType($response)!="string") {
				return($this->SetError("Could not retrieve the message headers"));
			}
			if(!strcmp($response,".")) {
				$end_of_message=1;
				$this->state="TRANSACTION";
				break;
			}	else {
				if(substr($response,0,1)==".") {
					$response=substr($response,1,strlen($response)-1);
				}
				$this->message_buffer.=$response."\r\n";
			}
		}
		if($end_of_message
		|| $count>=strlen($this->message_buffer)) {
			$message=$this->message_buffer;
			$this->message_buffer="";
		}	else {
			$message=substr($this->message_buffer, 0, $count);
			$this->message_buffer=substr($this->message_buffer, $count);
		}
		return("");
	}
	
	/* DeleteMessage method - the $message argument indicates the number of
	a message to be marked as deleted.  Messages will only be effectively
	deleted upon a successful call to the Close method. */
	
	function DeleteMessage($message)
	{
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($this->PutLine("DELE $message")==0) {
			return($this->SetError("Could not send the DELE command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not get message delete command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not delete the message: ".$this->Tokenize("\r\n")));
		}
		$this->must_update=1;
		return("");
	}
	
	/* ResetDeletedMessages method - Reset the list of marked to be deleted
	messages.  No messages will be marked to be deleted upon a successful
	call to this method.  */
	
	function ResetDeletedMessages()
	{
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($this->PutLine("RSET")==0) {
			return($this->SetError("Could not send the RSET command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not get reset deleted messages command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not reset deleted messages: ".$this->Tokenize("\r\n")));
		}
		$this->must_update=0;
		return("");
	}
	
	/* IssueNOOP method - Just pings the server to prevent it auto-close the
	connection after an idle timeout (tipically 10 minutes).  Not very
	useful for most likely uses of this class.  It's just here for
	protocol support completeness.  */
	
	function IssueNOOP()
	{
		if($this->state!="TRANSACTION") {
			return($this->SetError("connection is not in TRANSACTION state"));
		}
		if($this->PutLine("NOOP")==0) {
			return($this->SetError("Could not send the NOOP command"));
		}
		$response=$this->GetLine();
		if(GetType($response)!="string") {
			return($this->SetError("Could not NOOP command response"));
		}
		if($this->Tokenize($response," ")!="+OK") {
			return($this->SetError("Could not issue the NOOP command: ".$this->Tokenize("\r\n")));
		}
		return("");
	}
	
	function &SetConnection($set, &$current_name, &$pop3)
	{
		static $connections = array();
		if($set>0) {
			$current_name = strval(count($connections));
			$connections[$current_name] = &$pop3;
		}	elseif($set<0) {
			$connections[$current_name] = '';
			$current_name = '';
		}	elseif(IsSet($connections[$current_name])
					&& GetType($connections[$current_name])!='string') {
			$connection = &$connections[$current_name];
			return($connection);
		}
		return($pop3);
	}
	
	/* GetConnectionName method - Retrieve the name associated to an
	established POP3 server connection to use as virtual host name for
	use in POP3 stream wrapper URLs.  */
	function GetConnectionName(&$connection_name)
	{
		if($this->state!="TRANSACTION") {
			return($this->SetError("cannot get the name of a POP3 connection that was not established and the user has logged in"));
		}
		if(strlen($this->connection_name) == 0) {
			Pop3::SetConnection(1, $this->connection_name, $this);
		}
		$connection_name = $this->connection_name;
		return('');
	}
		
	/*----------------------------------------------------------------------------------------------*/	
	
	// Get a count of the number of unread messages in the POP box...
	public function getUnreadMessageCount(&$messagecount,$host,$port,$username,$password,$tls=0,$apop=0,$debug=0) {
	
		$pop3 = new Pop3();
		$pop3->hostname=$host;
		$pop3->port=$port;
		$pop3->tls=$tls;
		
		$pop3->realm="";                         /* Authentication realm or domain              */
		$pop3->workstation="";                   /* Workstation for NTLM authentication         */
		$pop3->authentication_mechanism="USER";  /* SASL authentication mechanism               */
		$pop3->debug=$debug;                     /* Output debug information                    */
		$pop3->html_debug=0;                     /* Debug information is in HTML                */
		$pop3->join_continuation_header_lines=1; /* Concatenate headers split in multiple lines */
		
		// Open connection
		$error = $pop3->Open();	
		if(!$error) {
			// Login
			$error = $pop3->Login($username,$password,$apop);
			if(!$error) {
				// Get message stats (i.e check if there is any unread mail)
				$error = $pop3->Statistics($messages,$size);
				if(!$error) {				
					$messagecount = $messages;		
				}		
			} 
		}	
		if(!$error) {
			// Close connection
			$error = $pop3->Close();
		}
		return $error;
		
	}

	// Get an unread message from the POP box...
	// Retrieving this message should mark it as read, but we'll reset the mailbox after pulling out the message data so you'll need to manually kill the 
	// mail if you want it gone... NOTE : GMail is slow between updating it's POP status and the web front end, so expect discrepancies!
	public function getUnreadMessage(&$uniqueid,&$headers,&$body,$host,$port,$username,$password,$tls=0,$apop=0,$debug=0) {
	
		$pop3 = new Pop3();
		$pop3->hostname=$host;
		$pop3->port=$port;
		$pop3->tls=$tls;
		
		$pop3->realm="";                         /* Authentication realm or domain              */
		$pop3->workstation="";                   /* Workstation for NTLM authentication         */
		$pop3->authentication_mechanism="USER";  /* SASL authentication mechanism               */
		$pop3->debug=$debug;                     /* Output debug information                    */
		$pop3->html_debug=0;                     /* Debug information is in HTML                */
		$pop3->join_continuation_header_lines=1; /* Concatenate headers split in multiple lines */
		
		// Open connection
		$error = $pop3->Open();	
		if(!$error) {
			// Login
			$error = $pop3->Login($username,$password,$apop);
			if(!$error) {
				// Get message stats (i.e check if there is any unread mail)
				$error = $pop3->Statistics($messages,$size);
				if(!$error) {
					if($messages > 0) {
						// Get the unique identifier for this first message 
						$uniqueid = $pop3->ListMessages(1,1);
						if($uniqueid) {
							// Pull the first message
							$error = $pop3->RetrieveMessage(1,$headers,$body,-1);							
							if(!$error) {
								// Mark this message deleted, then trigger a reset to put it back to normal so we can pull again if required (deosn't seem to quite cut it with GMail - but it archives the mail anyway, so we can always get it back)...
								$error = $pop3->DeleteMessage(1);
								if(!$error) {
									$error = $pop3->ResetDeletedMessages();							
								}
							}							
						} else {
							$error = "Message ID not retrieved";
						}					
					}			
				}		
			} 
		}	
		if(!$error) {
			// Close connection
			$error = $pop3->Close();
		}
		return $error;
		
	}
	
	// Given a unique message identifier, find and delete this message...
	public function deleteSpecificMessage($uniqueid,$host,$port,$username,$password,$tls=0,$apop=0,$debug=0) {
	
		$pop3 = new Pop3();
		$pop3->hostname=$host;
		$pop3->port=$port;
		$pop3->tls=$tls;
		
		$pop3->realm="";                         /* Authentication realm or domain              */
		$pop3->workstation="";                   /* Workstation for NTLM authentication         */
		$pop3->authentication_mechanism="USER";  /* SASL authentication mechanism               */
		$pop3->debug=$debug;                     /* Output debug information                    */
		$pop3->html_debug=0;                     /* Debug information is in HTML                */
		$pop3->join_continuation_header_lines=1; /* Concatenate headers split in multiple lines */
		
		// Open connection
		$error = $pop3->Open();	
		if(!$error) {
			// Login
			$error = $pop3->Login($username,$password,$apop);
			if(!$error) {
				// Get message stats (i.e check if there is any unread mail)
				$error = $pop3->Statistics($messages,$size);
				if(!$error) {
					if($messages > 0) {
						// Get all the ID's for the available messages... this will give us an index value we can then use to kill the mail with 
						$results = $pop3->ListMessages("",1);
						if(is_array($results)) {
							$key = NULL;
							foreach($results AS $k => $id) {
								if($id == $uniqueid) {
									$key = $k;
									break;
								}
							}						
							if($key) {
								// Delete this message
								$error = $pop3->DeleteMessage($key);
							} else {
								$error = "Message ID not matched";
							}						
						} else {
							$error = "Message ID not retrieved";
						}					
					}			
				}		
			} 
		}	
		if(!$error) {
			// Close connection
			$error = $pop3->Close();
		}
		return $error;
		
	}

}
	
/*------------------------------------------------------------------------------------------------*/	
	
// This class is not yet used, but was part of the original source provided with the above class.
// You can grab the full class by Googling for 'Manuel Lemos's PHP POP3 class'
/*
class pop3_stream {

	var $opened = 0;
	var $report_errors = 1;
	var $read = 0;
	var $buffer = "";
	var $end_of_message=1;
	var $previous_connection = 0;
	var $pop3;
	
	function SetError($error) {
		if($this->report_errors) {
			trigger_error($error);
		}
		return(FALSE);
	}
	
	function ParsePath($path, &$url) {
		if(!$this->previous_connection) {
			if(IsSet($url["host"])) {
				$this->pop3->hostname=$url["host"];
			}
			if(IsSet($url["port"])) {
				$this->pop3->port=intval($url["port"]);
			}
			if(IsSet($url["scheme"])
			&& !strcmp($url["scheme"],"pop3s")) {
				$this->pop3->tls=1;
			}
			if(!IsSet($url["user"])) {
				return($this->SetError("it was not specified a valid POP3 user"));
			}
			if(!IsSet($url["pass"])) {
				return($this->SetError("it was not specified a valid POP3 password"));
			}
			if(!IsSet($url["path"])) {
				return($this->SetError("it was not specified a valid mailbox path"));
			}
		}
		
		if(IsSet($url["query"])) {
			parse_str($url["query"],$query);
			if(IsSet($query["debug"])) {
				$this->pop3->debug = intval($query["debug"]);
			}
			if(IsSet($query["html_debug"])) {
				$this->pop3->html_debug = intval($query["html_debug"]);
			}
			if(!$this->previous_connection) {
				if(IsSet($query["tls"])) {
					$this->pop3->tls = intval($query["tls"]);
				}
				if(IsSet($query["realm"])) {
					$this->pop3->realm = UrlDecode($query["realm"]);
				}
				if(IsSet($query["workstation"])) {
					$this->pop3->workstation = UrlDecode($query["workstation"]);
				}
				if(IsSet($query["authentication_mechanism"])) {
					$this->pop3->realm = UrlDecode($query["authentication_mechanism"]);
				}
			}
			if(IsSet($query["quit_handshake"])) {
				$this->pop3->quit_handshake = intval($query["quit_handshake"]);
			}
		}
		return(TRUE);
	}
	
	function stream_open($path, $mode, $options, &$opened_path)
	{
		$this->report_errors = (($options & STREAM_REPORT_ERRORS) !=0);
		if(strcmp($mode, "r")) {
			return($this->SetError("the message can only be opened for reading"));
		}
		$url=parse_url($path);
		$host = $url['host'];
		$pop3 = &Pop3::SetConnection(0, $host, $this->pop3);
		if(IsSet($pop3)) {
			$this->pop3 = &$pop3;
			$this->previous_connection = 1;
		}	else {
			$this->pop3 = new Pop3();
		}
		if(!$this->ParsePath($path, $url)) {
			return(FALSE);
		}
		$message=substr($url["path"],1);
		if(strcmp(intval($message), $message)
		|| $message<=0) {
			return($this->SetError("it was not specified a valid message to retrieve"));
		}
		if(!$this->previous_connection) {
			if(strlen($error=$this->pop3->Open())) {
				return($this->SetError($error));
			}
			$this->opened = 1;
			$apop = (IsSet($url["query"]["apop"]) ? intval($url["query"]["apop"]) : 0);
			if(strlen($error=$this->pop3->Login(UrlDecode($url["user"]), UrlDecode($url["pass"]),$apop))) {
				$this->stream_close();
				return($this->SetError($error));
			}
		}
		if(strlen($error=$this->pop3->OpenMessage($message,-1))) {
			$this->stream_close();
			return($this->SetError($error));
		}
		$this->end_of_message=FALSE;
		if($options & STREAM_USE_PATH) {
			$opened_path=$path;
		}
		$this->read = 0;
		$this->buffer = "";
		return(TRUE);
	}
	
	function stream_eof() {
		if($this->read==0) {
			return(FALSE);
		}
		return($this->end_of_message);
	}
	
	function stream_read($count) {
		if($count<=0) {
			return($this->SetError("it was not specified a valid length of the message to read"));
		}
		if($this->end_of_message) {
			return("");
		}
		if(strlen($error=$this->pop3->GetMessage($count, $read, $this->end_of_message))) {
			return($this->SetError($error));
		}
		$this->read += strlen($read);
		return($read);
	}
	
	function stream_close() {
		while(!$this->end_of_message) {
			$this->stream_read(8000);
		}
		if($this->opened) {
			$this->pop3->Close();
			$this->opened = 0;
		}
	}

}
*/

/*------------------------------------------------------------------------------------------------*/	

?>