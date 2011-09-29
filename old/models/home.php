<?php

class model extends common_model{

			
	function __construct($url) {
		
		$this->connection_string = CONNECTION_STRING;
		$this->username = USERNAME;
		$this->password = PASSWORD;
		$this->url = $url;
		
		$this->get_page_links();
		$this->html = $this->get_html($url->controller);

	//-e constuct	
	}

//-e class
}


?>