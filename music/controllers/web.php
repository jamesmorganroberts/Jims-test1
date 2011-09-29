<?php

require_once('./includes/WebTools.php');
require_once('./includes/Users.php');


class controller {
	
	
  function __construct($url)  
  {
   
		$this->url = $url;
		$this->image_dir = 'uploads/';
		$this->show_back = false;
		html::header("gallery_light.js");
		html::banner();
		html::menu();
		
		echo '<div id="page_container">';
		
		$this->selected_design();
		$this->web_thumbs();
		
		echo '</div>';
		
		html::footer();
		
	//-e construct	
  }
  
  
  function web_thumbs(){

	$img['imagename']='web_thumb_gvsg.png';
	$img['link']='gvsg';
	$array[] = $img;

	$img['imagename']='web_thumb_book_review.png';
	$img['link']='the-book-review';
	$array[] = $img;
	
	$img['imagename']='web_thumb_CJH.png';
	$img['link']='cjh';
	$array[] = $img;

	$img['imagename']='web_thumb_etree.png';
	$img['link']='etree';
	$array[] = $img;

	$img['imagename']='web_thumb_ranj.png';
	$img['link']='ranj';
	$array[] = $img;
	
	$img['imagename']='web_thumb_northern_electronica.png';
	$img['link']='northern_electronica';
	$array[] = $img;
	
	$img['imagename']='web_thumb_osd.png';
	$img['link']='osd';
	$array[] = $img;
	
	$img['imagename']='web_thumb_jmr.png';
	$img['link']='jmr';
	$array[] = $img;
	
	/*
	
	$img['imagename']='web_thumb_gvsg.png';
	$img['link']='gvsg';
	$array[] = $img;

	$img['imagename']='web_thumb_book_review.png';
	$img['link']='book_review';
	$array[] = $img;
	
	$img['imagename']='web_thumb_CJH.png';
	$img['link']='cjh';
	$array[] = $img;

	$img['imagename']='web_thumb_etree.png';
	$img['link']='etree';
	$array[] = $img;

	$img['imagename']='web_thumb_ranj.png';
	$img['link']='ranj';
	$array[] = $img;
	
	$img['imagename']='web_thumb_northern_electronica.png';
	$img['link']='northern_electronica';
	$array[] = $img;
	
	$img['imagename']='web_thumb_zoes.png';
	$img['link']='zoes';
	$array[] = $img;
	
	*/
	
	html::gallery_thumbs($array,'web');
  
  //-e web_thumbs
  }
  
  
  
  function selected_design(){
  
	echo '<div id="selected_design_container">';
	echo '<div id="selected_design">';
	
	$content = '<img id="image_load" src="'.GV::ROOT.'images/game_vs_gamer.png"/>';
	
	html::box1('Todays Top Games',$content,"left","","",false,"","","","","");
	
	$text= '<h2>Game Vs Gamer</h2>';

	html::box1('Todays Top Games',$text,"right","362","360",true,120,0,0,40,"justify");
	
	echo '</div>';
	echo '</div>';
	
  //-e selected_design
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
				
	html::box1('Todays Top Games',$content,"left","326","267");
  
  }
  
  function coming_soon(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Coming Soon',$content,"left","326","");
	
  //-e coming_soon
  }
  
  
  function news(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('News',$content,"left","652","");
	
  //-e news
  }
   
  function featured(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Featured',$content,"left","326","");
	
  //-e featured
  }
  
  
  function screenshots(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Latest Screenshots',$content,"left","652","");
	
  //-e screenshots
  }
  
  
  function quizzes(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Quizzes',$content,"left","456","");
	
  //-e quizzes
  }
  
  function hitlist(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Hit List',$content,"left","252","");
	
  //-e hitlist
  }
  
  
  function comments(){
  
	$content = '<ul>
					<li><a href="#">Cannibal Chef 2</a></li>
					<li><a href="#">Duck Hunt:The Return</a></li>
					<li><a href="#">Kick a Ball 8</a></li>
					<li><a href="#">Resident Evil Flamer</a></li>
					<li><a href="#">Duke Nukem:Effort Edition</a></li>
				</ul>';
				
				
	$content.= '<hr/>';
	$content.= '<a href="#">See More</a>';
				
	html::box1('Comments',$content,"left","256","");
	
  //-e comments
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