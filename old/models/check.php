<?php

class model extends common_model{

			
	function __construct($url) {
		
		$this->connection_string = 'mysql:host=talent.co.uk;dbname=JG';
		$this->username = 'alex';
		$this->password = 'ax212';
		
		$this->url = $url;
		$this->get_tables();

	//-e constuct	
	}
	
	
	function get_tables() {
		
		$dbh = new PDO($this->connection_string, $this->username, $this->password);	
		
		$q = "SELECT * FROM users_cjh";	

		foreach ($dbh->query($q) as $row)
		{
			echo $row['first_name'].'<br/>';
		}
	
			
	//-e get_html	
	}

	

//-e class
}


?>