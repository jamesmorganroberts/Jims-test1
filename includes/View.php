<?php

class View{

  private $scripts;
	private $css;
	private $page;

	function __construct() {
			
		$this->scripts == "";
		$this->css == "";
		$this->page =="";
	  
	//-e construct
	}
	
	
	function html($html){
	
		 $this->html = $html;
	
	}
	
	function hidden($html){
	
		 $this->html = $html;
	
	}
	
	
	function open_table(){
	
		$this->page .= '<table>';
	
	}
	
	
	function close_table(){
	
		$this->page .= '</table>';
	
	}
	
	function table_row_input($a,$b){
	
		$this->page .=  '<tr>';
		$this->page .=  '<td>'.$a.'</td>';
		$this->page .=  '<td>';
		$this->page .= '<input class="input" type="text" name="name" id="name" value="'.$b.'" />';
		$this->page .=  '</td>';
		$this->page .=  '</tr>';
	
	}
	
	
	
	function table_row_password($a,$b){
	
		$this->page .=  '<tr>';
		$this->page .=  '<td>'.$a.'</td>';
		$this->page .=  '<td>';
		$this->page .= '<input class="input" type="password" name="name" id="name" value="'.$b.'" />';
		$this->page .=  '</td>';
		$this->page .=  '</tr>';
	
	}
	
	
	
	
	function table_row_button($value){
	
		$this->page .= '<tr>';
		$this->page .= '<td>&nbsp;</td>';
		$this->page .= '<td><input type="button" value="'.$value.'" id="submit_button" /></td>';
		$this->page .= '</tr>';
	
	}
	
	
	
	
	
	function open_form(){
	
		$this->page .= '<form id="form" class="user_form" name="form" method="post">';
	
	}
	
	
	function close_form(){
	
		$this->page .= '</form>';
	
	}
	
	
	
		
	function add_javascript($script){
	  
	  $this->scripts .= '<script src="{ROOT}javascript/'.$script.'" type="text/javascript"></script>';
	  
	}
	
	function add_css($css){
		$this->css .= '<link type="text/css" rel="stylesheet" media="screen" href="{ROOT}css/'.$css.'" /> ';
	}
			
	function page($template) {
	    
		if (file_exists(CrispConstants::TEMPLATE_ROOT.$template)) {
		
		  $this->page .= join("",file(CrispConstants::TEMPLATE_ROOT.$template));
		
		}
	  
		else {
		
		 $this->page = "Template file $template not found.";
	 
		}

  }


	function add_tag($tag,$value) {
		
		$this->page = str_replace("{".$tag."}",$value,$this->page);	
		
	}
	
	function clear_tag($tag) {
		
		$this->page = str_replace("{".$tag."}","",$this->page);	
		
	}
	
	function process_tags($array) {
		
		foreach($array as $a) {
			$pieces = explode("=", $a);
			$this->page = str_replace("{".$pieces[0]."}",$pieces[1],$this->page);	
		}
		
	//-e process_tags
	}

 	function output() {
 	  
		$this->page = str_replace("{css}",$this->css,$this->page);
		$this->page = str_replace("{scripts}",$this->scripts,$this->page);
		$this->page = str_replace("{ROOT}",CrispConstants::ROOT,$this->page);
				    
		echo $this->page;
		
  }

//-e class
}

?>