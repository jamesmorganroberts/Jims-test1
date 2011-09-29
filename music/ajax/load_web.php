<?php
require_once("../includes/config.php");
require_once("../includes/html.php");


class ajax {
	
	
  function __construct()  
  {
   
		echo '
		
			<script>
			$("#image_load").load(function() {
	
				
				$("#selected_design").fadeIn();
	
			});
			</script>
		
		';
		
		$this->selected_design();
		
		
	//-e construct	
  }
  
  function selected_design(){
  
	switch ($_GET['image']) {
    case 'gvsg':
	
        $image = 'game_vs_gamer.png';
		$text= '<h2>Game Vs Gamer</h2>';
		//$text.= 'I have created an offline version of my Storyboard Online (SBO) dissertaion project. ';
		//$text.= 'The original version of SBO utilised Flash Media Server meaning i could not showcase';
		//$text.= 'the project on this website. ';
        
		break;
    case 'the-book-review':
	
        $image = 'the-book-review.png';
		$text= '<h2>Book Review</h2>';
		$text.= 'The-book-review.co.uk is an online book club. The book review provides information for and facilitates the discussion of books, authors and associated topics. ';
		$text.= 'User registration enables users to store their favourite books, participate in discussions and review books.';
		
        break;
		
    case 'cjh':
	
		$image = 'cjh.png';
		$text= '<h2>Central Jobs Hub</h2>';
		$text.= 'The Central Jobs Hub is a jobs website which brings all jobs in the tees valley area together in a single place. ';
		$text.= 'The Central Jobs Hub uses screen-scaping technologies to routinely gather jobs from multiple ';
		$text.= 'sources across the web.';
	
        break;
	
	case 'etree':
	
		$image = 'etree.png';
		$text= '<h2>Etree</h2>';
		$text.= 'The ETREE website uses the JOOMLA content management system. ';
	
	
        break;
		
	case 'northern_electronica':
	
		$image = 'northern_electronica.jpg';
		$text= '<h2>Northern Electronica</h2>';
	
        break;
		
	case 'ranj':
	
		$image = 'ranj.png';
		$text= '<h2>Ranjit Doroszkiewicz</h2>';
		$text.= 'This site showcases the photographic talents of artist Ranjit Doroszkiewicz. ';

	
        break;
		
	case 'osd':
	
		$image = 'osd.png';
		$text= '<h2>Office Seating & Desking</h2>';
		$text.= 'The OSD website utilises a content management system. ';

	
        break;
		
	case 'jmr':
	
		$image = 'jmr.jpg';
		$text= '<h2>Portfolio V4</h2>';

	
        break;
		
		
	}
	
	$content = '<img id="image_load" src="'.GV::ROOT.'images/'.$image.'"/>';
	
	html::box1('',$content,"left","","",false,"","","","","");
	


	html::box1('',$text,"right","362","360",true,120,0,0,40,"justify");
	
	
  
  //-e selected_design
  }
  
}

$ajax = new ajax();  

?>