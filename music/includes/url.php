<?php



class url_data {
	
	public $controller;
	public $get;
	public $current_page;
		
	function __construct($string) {
	
		$this->current_page = $string;
			
		if ($string != "")
		{
			
		
			$string .= "/";
			$string_length = strlen($string); 
			
			$pos = 0;
			$pos2 = 0;
			$counter = 0;
			
			while ( $pos2 != $string_length )
			{
				
				if ( $string[$pos] == "/" ) 
				{ 
					$cut = $pos;
					
					if ( $counter == 0  ) { $this->controller = str_replace("/","",substr($string, 0, $cut)); }
					if ( $counter > 0 ) { $this->get[] = str_replace("/","",substr($string, 0, $cut)); }
					//if ( $counter == 2  ) { $this->b = str_replace("/","",substr($string, 0, $cut)); }
					//if ( $counter == 3  ) { $this->c = str_replace("/","",substr($string, 0, $cut)); }
					//if ( $counter == 4  ) { $this->d = str_replace("/","",substr($string, 0, $cut)); }
					//if ( $counter == 5  ) { $this->e = str_replace("/","",substr($string, 0, $cut)); }
					
					$string = substr($string, $cut);
					$pos = 0;
					$counter ++;
				}
															
				$pos ++;
				$pos2 ++;
			}
			
			
			$pos = 0;
			
			
			if(is_array($this->get)){
			
				foreach($this->get as $g)
				{
					//echo $pos."-".$g.'<br/>';
					
					if ($pos % 2) { 	
						
					}
					elseif(isset($this->get[($pos+1)])) {
						
						$this->get_array[$g] = $this->get[($pos+1)]; 
						
					} 
					
					$pos ++;			
				}
			
			}
			
			//$this->display_array("",$this->get_array,"");
			
			/*
			echo "controller:".$this->controller."<br/>";

		
			foreach ( $this->get as $get )
			{
				echo $get."<br/>";
			}
			
			*/
		
		}
	}
	
	
	function display_array($title,$array,$bold) {

		foreach(array_keys($array) as $key0)
		{
			if($bold==1){ echo '<b>display_array:'.$title.' : '.$key0.'</b> -'.$array[$key0].'<br/>'; }
			else { echo 'display_array:'.$key0.':'.$array[$key0].'<br/>'; }
			
			$array2 = $array[$key0];
			$this->display_array("",$array2);
		}

	//-e display_array
	}
	
}


?>