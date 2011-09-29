<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

//require_once("includes/WebTools.php");
//require_once("includes/User.php");
//require_once("includes/UserToBusiness.php");
//require_once('includes/logged_in.php');
//require_once("includes/ActivityLog.php");


class controller {
	
	
  public function __construct()  
  {
		$this->image_dir = 'uploads/';
		$this->show_back = false;
	
		$this->alexid = WebTools::getOrPost('alexid');
		$this->imageid = WebTools::getOrPost('imageid');
		$this->page = WebTools::getOrPost('page');	

		$this->header();
		$this->display_menu();
		
		if($this->page==""){ $this->page = "default"; }
		if($this->page=="default"){ $this->bulk_upload_form(); }
		if($this->page=="upload"){ $this->upload(); }
		if($this->page=="receive"){ $this->receive(); }
		if($this->page=="calculate_dir"){ $this->calculate_dir(); }
		if($this->page=="delete"){ $this->delete();}
		if($this->page=="delete_confirm"){ $this->delete_confirm();}
		if($this->page=="receive_bulk"){ $this->receive_bulk();}
		
		
		$this->footer();
		
	//-e construct	
  }
	
	public function receive_bulk(){
	
		$this->show_back = true;
		if($_FILES['file1']['tmp_name'] != "") { $this->send_image(1,'file1'); }
		if($_FILES['file2']['tmp_name'] != "") { $this->send_image(2,'file2'); }
		if($_FILES['file3']['tmp_name'] != "") { $this->send_image(3,'file3'); }
		if($_FILES['file4']['tmp_name'] != "") { $this->send_image(4,'file4'); }
		if($_FILES['file5']['tmp_name'] != "") { $this->send_image(5,'file5'); }
		if($_FILES['file6']['tmp_name'] != "") { $this->send_image(6,'file6'); }
		if($_FILES['file7']['tmp_name'] != "") { $this->send_image(7,'file7'); }
		if($_FILES['file8']['tmp_name'] != "") { $this->send_image(8,'file8'); }
		if($_FILES['file9']['tmp_name'] != "") { $this->send_image(9,'file9'); }
		if($_FILES['file10']['tmp_name'] != "") { $this->send_image(10,'file10'); }
		
		echo 'images uploaded.<br/><br/>';
		
		$this->bulk_upload_form();
		
	
	//-e receive_bulk
	}
	
	
	public function send_image($id,$file){
	
		$filename = $this->alexid.'_'.$id;
		$image_dir = ImageTools::generate_image_path($this->alexid,CrispConstants::CLOUD_ROOT,true);		
		ImageTools::resize_and_upload($file,$filename,$image_dir,200);
	
	//-e send image
	}
	
	
	
	public function bulk_upload_form(){
	
		$this->show_back = true;
		
		echo '<h2>Upload Images</h2>';
		echo '<form  action="'.$_SERVER["PHP_SELF"].'?page=receive_bulk&imageid='.$this->imageid.'&alexid='.$this->alexid.'" method="post" enctype="multipart/form-data">';
		
		echo '<input name="file1" class="login" type="file"><br/>';
		echo '<input name="file2" class="login" type="file"><br/>';
		echo '<input name="file3" class="login" type="file"><br/>';
		echo '<input name="file4" class="login" type="file"><br/>';
		echo '<input name="file5" class="login" type="file"><br/>';
		echo '<input name="file6" class="login" type="file"><br/>';
		echo '<input name="file7" class="login" type="file"><br/>';
		echo '<input name="file8" class="login" type="file"><br/>';
		echo '<input name="file9" class="login" type="file"><br/>';
		echo '<input name="file10" class="login" type="file"><br/>';

		echo '<br/>';
		echo '<input type="submit" value="Upload Images" name="upload_image"/>';
		echo '</form>';
	
	//-e bulk_form
	}
	
	
	
	
	public function delete_confirm(){
	
		if(isset($_POST['delete_yes'])){
		
			$filename = $this->alexid.'_'.$this->imageid;
			$image_dir = ImageTools::generate_image_path($this->alexid,CrispConstants::CLOUD_ROOT,true);
			ImageTools::delete_images($image_dir,$filename);
		
		}	
		
		$this->gallery();
		
	//-e delete_confirm
	}
	
	
	
	
	public function delete(){
	
		$this->show_back = true;
		$img = ImageTools::get_image($this->imageid,$this->alexid,CrispConstants::CLOUD_ROOT);
	
		echo '<h2>Delete Image?</h2>';
		echo '<img src="'.$img.'">';
		echo '<form  action="'.$_SERVER["PHP_SELF"].'?page=delete_confirm&alexid='.$this->alexid.'" method="post" enctype="multipart/form-data">';
		echo '<input name="imageid" type="hidden" id="imageid" value="'.$this->imageid.'" />';
		echo '<input type="submit" value="Yes" name="delete_yes"/>';
		echo '<input type="submit" value="No" name="delete_no"/>';
		echo '</form>';
	
	//-e delete
	}
	

	
	
	
	
	public function calculate_dir(){
				
		$this->show_back = true;
		echo ImageTools::generate_image_path($this->alexid,CrispConstants::CLOUD_ROOT,false);
	
	//-e calculate_dir
	}
	
	
	
	public function receive(){
	
		// receive image post
		if (isset($_POST['upload_image']) ) { 
				
			// if file is not empty
			if($_FILES['file']['tmp_name'] != "") {

				$filename = $this->alexid.'_'.$this->imageid;
				$image_dir = ImageTools::generate_image_path($this->alexid,CrispConstants::CLOUD_ROOT,true);
				
				ImageTools::delete_images($image_dir,$filename);
				
				ImageTools::resize_and_upload('file',$filename,$image_dir,200);
				
				//display gallery page
				$this->gallery();
				
			}else{
				// if empty....
				echo 'please select file';
				$this->upload();
			}
				
		//-e if post exists
		}
	
	//-e receive
	}
	
	
	public function upload(){
		
		$this->show_back = true;
		
		echo '<h2>Upload Image</h2>';
		echo '<form  action="'.$_SERVER["PHP_SELF"].'?page=receive&imageid='.$this->imageid.'&alexid='.$this->alexid.'" method="post" enctype="multipart/form-data">';
		echo '<input name="file" class="login" type="file">';
		echo '<input type="submit" value="Upload Image" name="upload_image"/>';
		echo '</form>';
	
	//-e upload form
	}
	
	
	public function gallery(){
	
		$this->get_business();
					
		echo '<h2>'.$this->business['records'][0]['companyname'].' - Images</h2>';
			
		echo '<div class="image_group">';
		$this->image(1);
		$this->image(2);
		$this->image(3);
		$this->image(4);
		$this->image(5);
		echo '</div>';
		
		echo '<div class="image_group">';
		$this->image(6);
		$this->image(7);
		$this->image(8);
		$this->image(9);
		$this->image(10);
		echo '</div>';		

	//-e gallery
	}
	
	
	public function image($id){
	
		// ".gif",".jpg",".png",".jpeg"
		$file_exists = false;
		
		$image_dir = ImageTools::generate_image_path($this->alexid,CrispConstants::CLOUD_ROOT,false);
	
		$gif = $image_dir.$this->alexid.'_'.$id.'.gif';
		$jpg = $image_dir.$this->alexid.'_'.$id.'.jpg';
		$png = $image_dir.$this->alexid.'_'.$id.'.png';
		$jpeg = $image_dir.$this->alexid.'_'.$id.'.jpeg';
		
		if (file_exists($gif)) { $img = $gif;}
		if (file_exists($jpg)) { $img = $jpg;}
		if (file_exists($png)) { $img = $png;}
		if (file_exists($jpeg)){$img = $jpeg;}

		echo '<div class="image_container">';
		
		echo '<a href="'.CrispConstants::ROOT.'adminviews/business_images.php?page=upload&alexid='.$this->alexid.'&imageid='.$id.'">upload</a>';
		echo '&nbsp;|&nbsp;';
		echo '<a href="'.CrispConstants::ROOT.'adminviews/business_images.php?page=delete&alexid='.$this->alexid.'&imageid='.$id.'">delete</a>';
	
		if(isset($img)){ echo '<img src="'.$img.'" width="120" height="120">'; }
	
		echo '</a>';
		echo '</div>';
					
	//-e image
	}
	
	
	
	public function header(){
	
		$view = new View();
		$view->page("header.html");
		$view->add_tag("title","Admin");
		$view->add_javascript("business_images.js");
		$view->add_css("user.css");
		$view->output();
	//-header
	}
	
	
	public function footer(){
	
		if($this->show_back==true){
			echo '<br/><br/><a href="'.CrispConstants::ROOT.'loadview.php" class="load_gallery">back</a><br/>';
	  }
		echo '<br/><a href="logout.php">LOGOUT</a>';
		$view = new View();
		$view->page("footer.html");
		$view->output();
	
	//-e footer
	}

	
	public function display_menu(){
	
		echo '<input name="alexid" type="hidden" id="alexid" value="'.$this->alexid.'" />';
		echo '<ul>';
		echo '<li><a href="'.CrispConstants::ROOT.'loadview.php" id="business_products">Business Products</a></li>';	
		echo '</ul>';	
		
		
		
	//-e display_menu
	}
	
	
	
	public function get_business(){
		
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_URL, CrispConstants::AKME_API_ROOT.'business.php');
		curl_setopt($c, CURLOPT_POSTFIELDS,'alexids[]='.$this->alexid);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($c, CURLOPT_TIMEOUT, 20);
			
		$content = curl_exec($c);
		$info = curl_getinfo($c);
			
		curl_close($c);
		$this->business = json_decode($content,true);
				
	//-e get_business
	}
	
//-e class
}




$controller = new controller();  


?>