<?php



class html{


	function __construct() {
			
	
	  
	//-e construct
	}
	
	
	
	static function gallery_thumbs($array,$page){
	
		$num = 1;
	
		foreach($array as $img)
		{
			/* top right bottom left */
			
			if($num==8 || $num==16){ echo '<div class="thumb" style="margin: 0px 0px 20px 0px;float:left;">'; }
			else{ echo '<div class="thumb" style="margin: 0px 20px 20px 0px;float:left;">'; }
			
			echo '<a alt="'.$img['link'].'" class="thumb_link" href="#"><img src="'.GV::ROOT.'images/'.$img['imagename'].'" width="100" height="60"/></a>';
			echo '</div>';
			
			$num ++;
		}
	
	//-e gallery_thumbs
	}
	
	
	static function box1($title,$content,$type,$width,$height,$padding,$p_top,$p_right,$p_bottom,$p_left,$justify){
	
		/* top right bottom left */
	
		if(isset($type)){
		
			if($type=="left"){ $stub = "_left"; }
			if($type=="right"){ $stub = "_right"; }
		
		}
		else{
		
			$stub = "_left";
		
		}
	
		if(is_numeric($width)){ 
			echo '<div style="width:'.$width.'px" class="box1_container'.$stub.'">';
		}
		else{
			echo '<div class="box1_container'.$stub.'">';
		}
		
		if(is_numeric($height)){ $hstub = 'style="height:'.$height.'px"'; }
		else{ $hstub = ""; }
		
		if(strlen($justify)>0){
			echo '<div style="text-align:'.$justify.'">';
		}
		
		if($padding==true){ 
			echo '<div style="padding:'.$p_top.'px '.$p_right.'px '.$p_bottom.'px '.$p_left.'px">';
		}
		
		
		
		echo $content;
		
		if($padding==true){ 
			echo '</div>';
		}
		
		if(strlen($justify)>0){
			echo '</div>';
		}
		
		echo '</div>';
	
	//-e box1
	}
	
	
	static function ddarrow(){
	
		return '<img class="ddarrow" src="'.GV::ROOT.'images/dropdown_arrow.png">';
	
	}
	
	
	static function slide_thumb($filename,$name){
	
		$html = '<a name="'.$name.'" class="slide_link" href="#"><img src="'.GV::ROOT.'images/'.$filename.'"></a>';
		
		return $html;
	
	//-e slide_thumb
	}
	
	static function slide_text($id,$title,$text){
	
		$html = '<div id="slidetext'.$id.'" class="slideshow_text">';
		$html .= $title;
		$html .= '<label class="smalltext">'.$text.'</label>';
		$html .= '</div>';
		return $html;
		
	//-e slide text
	}
	
	
	static function slideshow(){
	
		echo '<div id="slideshow_container">';
		echo '<div id="slideshow_text_bg"></div>';
		
		echo html::slide_text(1,'Battlefield 3','the latest shooter from DICE and Electronic Arts, coming to Xbox 360');
		echo html::slide_text(2,'Alice goes mad','Mad as a Hatta ye are!');
		echo html::slide_text(3,'Deux Ex 3','Could this be the best one yet?');
		echo html::slide_text(4,'Batman: Arkham City Riddler Trailer HD','The new Batman game coming soon');
		echo html::slide_text(5,'Unchartered 3','Drakes back, and this time hes piseed!');
		
		/*
		echo '<div id="slidetext1" class="slideshow_text">Battlefield 3 - makes me skip<label class="smalltext">Edd smells of fleas</label></div>';
		echo '<div id="slidetext2" class="slideshow_text">Alice goes mad!!!</div>';
		echo '<div id="slidetext3" class="slideshow_text">Deus Ex 3 - not again!!</div>';
		echo '<div id="slidetext4" class="slideshow_text">Batman: Arkham City Riddler Trailer HD</div>';
		echo '<div id="slidetext5" class="slideshow_text">Unchartered 3</div>';
		*/
		
		echo '<div id="slideshow_links_bg"></div>';
		echo '<div id="slideshow_links">';
		
		echo html::slide_thumb('thumb1.jpg',1);
		echo html::slide_thumb('thumb2.jpg',2);
		echo html::slide_thumb('thumb3.jpg',3);
		echo html::slide_thumb('thumb4.jpg',4);
		echo html::slide_thumb('thumb5.jpg',5);
		
		echo '</div>';
		
		echo '<div id="slideshow">
		  <div id="slidesContainer">
			<div id="slide1" class="slides">
			 <img src="'.GV::ROOT.'images/slide1.jpg">
			</div>
			<div id="slide2" class="slides">
			 <img src="'.GV::ROOT.'images/slide2.jpg">
			</div>
			<div id="slide3" class="slides">
			 <img src="'.GV::ROOT.'images/slide3.jpg">
			</div>
			<div id="slide4" class="slides">
			 <img src="'.GV::ROOT.'images/slide4.jpg">
			</div>
			<div id="slide5" class="slides">
			 <img src="'.GV::ROOT.'images/slide5.jpg">
			</div>
		  </div>
		</div>';
		
		echo '</div>';
	
	//-e slideshow
	}
	
	
	
	static function ddmenu($array){
		
		
			
		echo '<ul id="jsddm">';
		
		
		$menu_count = count($array);
		
		$num = 1;
		
		foreach (array_keys($array) as $key)
		{
			
			if($num==1){ echo '<li><a id="menu_link_start" href="'.GV::ROOT.$array[$key]['link'].'">'.$key; }
			elseif($num == $menu_count){ echo '<li><a id="menu_link_end" href="'.GV::ROOT.$array[$key]['link'].'">'.$key; }
			else{ echo '<li><a id="menu_link" href="'.GV::ROOT.$array[$key]['link'].'">'.$key; }
			
			//if(isset($array[$key]['sub'])){ echo html::ddarrow(); }
			
			echo '</a>';
			
			if(isset($array[$key]['sub'])){
			
				echo '	<ul id="js_wrap">';
				
				foreach($array[$key]['sub'] as $a)
				{
				
					//print_r($a);
				
					echo '<li><a id="menu_link" href="'.$a['link'].'">'.$a['name'].'</a></li>';
				}
				
				echo '	</ul>';
			
			//-e is array
			}
			
			echo '</li>';
			
			$num ++;
			
		}
		echo '</ul>';
		
		
	//-e ddmenu
	}
	
	
	static function menu(){
	
		echo '
		<div id="menu_container">
		<div id="menu_left">';
		
		$array['Web']['link'] = "web";
		//$array['Web']['sub'][] = array("name" => "Latest Games", "link" => "#");

		$array['Flash']['link'] = "flash";
		//$array['Flash']['sub'][] = array("name" => "Latest News", "link" => "#");
		
		$array['CV']['link'] = "cv";
		//$array['CV']['sub'][] = array("name" => "Forums", "link" => "#");
		
		$array['Contact']['link'] = "contact";
		//$array['CV']['sub'][] = array("name" => "Forums", "link" => "#");
		
		html::ddmenu($array);
		
		echo '</div>';
		
		/*
		echo '<div id="menu_right">';
		
		
		unset($array);
		$array['LOGIN']['link'] = "#";
		$array['REGISTER']['link'] = "#";
		html::ddmenu($array);
		
		echo '</div>';	
		*/
		
		echo '</div>';
		
	
	}
	
	
	
	static function banner() {
	
		echo '<div id="banner_container">';
		echo '<div id="banner_title">';
		echo '<h1>James Roberts | Web Designer</h1>';
		echo '<b>James Roberts</b> | Designer';
		//echo '<a href="'.GV::ROOT.'"><img src="'.GV::ROOT.'images/banner_title.png"/></a>';
		echo '</div>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
		
	//-e footer
	}
	
	
	static function footer() {
		
		echo '<div id="footer_container">';
		echo '</div>';
		// close container div
		echo '</div>';
		
		echo '</body>';
		echo '</html>';
		
	//-e footer
	}
	
	static function header($javascript_name) {
	
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">'; 
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<meta name="keywords" content="Leighton, recruitment, careers, web develper, web designer" />';
		echo '<meta name="description" content="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent in magna." />';
		echo '<meta name="language" content="en-uk" />';
		echo '<meta name="author" content="Games Shroom" />';
		echo '<meta name="copyright" content="Leighton 2007" />';
		echo '<script src="'.GV::ROOT.'javascript/jquery-1.6.1.js" type="text/javascript"></script>';
		
		if(strlen($javascript_name)>0){
			echo '<script src="'.GV::ROOT.'javascript/'.$javascript_name.'" type="text/javascript"></script>';
		}
		
		echo '<link type="text/css" rel="stylesheet" media="screen" href="'.GV::ROOT.'css/style.css" />';
		echo '<title></title>';
		echo '</head>';
		echo '<body>';
		echo '<div id="container">';
	}

//-e class
}

?>