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
		echo '<div id="cv">';
		
		$this->cv();
		
		echo '</div>';
		echo '</div>';
		
		html::footer();
		
	//-e construct	
  }
  
	function cv(){
	
		echo '
		

<h2>PERSONAL INFORMATION</h2>
<p>
Date of birth: 03-11-1978<br/>
Nationality: British
</p>
<br/><br/>	
<h2>EMPLOYMENT</h2>

<h3>July 2011 | PHP Developer Scoot.co.uk</h3>
<p>
In this position I worked with PHP to further develop existing code. I used OO PHP to create APIs for website to website communication using JSON. I further developed my AJAX skills using Javascript Prototype. I was introduced to Agile Development methods and version control using GIT. 
</p>


<h3>Dec 2008 - Nov 2010 | Database Manager / Web Developer, Talent Recruitment</h3>
<p>
Design, development and maintenance of Job Search Website. Good understanding of system development methods. Experience of using Object-orientated PHP / Mysql. Working with PHP code and Mysql on a daily basis. Flash/Actionscript with XML integration. Projects include: development of Content-Management-System, Job search engine with screen-scraping capabilities. Developing XHTML and CSS with accessibility best-practices. Code layout using Model View Controller concept. Development of Stored Procedures using MYSQL. 
</p>
		
<br/><br/>	
<h2>EDUCATION</h2>

<h3>Oct 06 - Dec 07 University of Teesside</h3>
<p>Msc Web Services Development (Bursary support provided)</p>

<h3>Sep 1998-July 2001 University of Lincolnshire and Humberside</h3>
<p>BA (Hons) Graphic Design (2:1)</p>


<h3>Sep 1996-May 1998 Cleveland College of Art and Design</h3>
<p>BTEC National Diploma in Foundation Studies (Merit)</p>

<h3>1992-1996 Conyers School</h3>
<p>9 GCSE (A+ Art and Design, B Maths, C English)
2 A-LEVEL (B Art and Design, D Business Studies)
</p>


<br/><br/>	
<h2>IT EXPERIENCE</h2>

<h3>Graphics</h3>
<p>
Adobe Photoshop, Illustrator,
BA Hons Graphic Design,
10 years experience,
Photo-manipulation,
Line art.
</p>

<h3>Animation</h3>
<p>
Flash  / Actionscript,
Actionscript 3,
Flixel / Flashpunk,
Interface design.
</p>

<h3>Scripting / Database</h3>
<p>
PHP / MYSQL,
2 years experience,
Object-orientated,
MVC design pattern,
Apache,
MYSQL 4/5,
PHP 5,
Linux / OSX command line,
Version Control - Git,
Encryption, Injection hack prevention,
Agile Software Development methods,
Windows and OSX development platforms.
</p>

<h3>HTML</h3>
<p>
XHTML / CSS / XML,
Cross browser compatibility,
W3C standard,
CSS2.
</p>

<h3>Javascript</h3>
<p>
JQUERY / PROTOTYPE,
DOM manipulation,
AJAX,
Visual Effects,
Slideshow,
JSON.
</p>

<h3>CMS</h3>
<p>
JOOMLA,
Moodle,
Wiki.
</p>

<h3>Audio</h3>
<p>
Ableton Live,
Cool Edit,
Reason,
MaxMsp.
</p>
		
		';
	
	}
	

//-e class
}












?>