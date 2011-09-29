<?php

require_once('./includes/WebTools.php');
require_once('./includes/Users.php');


class controller {
	
	
  function __construct($url)  
  {
		$this->image_dir = 'uploads/';
		$this->show_back = false;
		
		
		
		html::header();
		html::banner();
		html::menu();


		
		$this->default_page(); 
		
		html::footer();
		
	//-e construct	
  }
  
  
  function top_games(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Todays Top Games',$content,"left");
  
  }
  
  
	
	function default_page(){
	
		echo 'GAME';
	
	//-e default_page
	}
	
	function display_menu(){
	
		
	
	//-e header
	}
	
	function header(){
	
		
	
	//-e header
	}
	
	function footer(){
	
		
	
	//-e footer
	}
	
	
	public function user(){
	
		global $mysqlread;
		global $mysqlwrite;
		
		$user = new Users(1);
		
		//echo $user->getSurname();
	
	
	}
	
	
	public function add_to_db(){
		
		global $mysqlwrite;
		
		//$id = WebTools::getOrPost('id');
		//$alexid = WebTools::getOrPost('alexid');
	
			
		$q = "DELETE FROM user_to_business WHERE user_id='".mysql_escape_string($id)."' AND alexid='".mysql_escape_string($alexid)."'";
		$delete = $mysqlwrite->doQuery($q);


	
	
	//-e delete_business
	}
	
	
	

//-e class
}












?>