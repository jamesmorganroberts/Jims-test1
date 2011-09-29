<?php



class common_model{


	function common_test(){
			
		echo 'common test';
	
	//-e common_test
	}
	
	
	function get_html($controller) {
		
		$dbh = new PDO($this->connection_string, $this->username, $this->password);	
		
		$q = "SELECT * FROM jmr_menu WHERE name='$controller'";	

		foreach ($dbh->query($q) as $row)
		{
			$html = $row['html'];
		}
	
		$html = str_replace("#ROOT#", ROOT, $html);
	
		return $html;
	
	//-e get_html	
	}
	
	
	function get_page_links() {
		
		$dbh = new PDO($this->connection_string, $this->username, $this->password);	
		
		$q = "SELECT * FROM jmr_menu ORDER BY id ASC";

		foreach ($dbh->query($q) as $row)
		{
			$this->menu_links[]=array( 	
									id => $row['id'], 
								  	name => $row['name'],
									link => $row['link']
								); 
		}
		
	//-e get_page_links
	}

	
//-e class
}


?>