<?php


class ImageTools {


	static function delete_images($path,$filename){
	
		$file[] = $path.$filename.'.jpg';
		$file[] = $path.$filename.'.jpeg';
		$file[] = $path.$filename.'.gif';
		$file[] = $path.$filename.'.png';
		
		foreach($file as $f){
		
			if(file_exists($f)){
			
				unlink($f);
			
			}
		
		}
		
	}
	
	
	
	public function get_image($id,$alexid,$path){
	
		//returns full image path
		//carries out checks to see which file externsion exists
		//then returns the one that exists
	
		$image_dir = ImageTools::generate_image_path($this->alexid,$path,false);
	
		$file[] = $image_dir.$alexid.'_'.$id.'.gif';
		$file[] = $image_dir.$alexid.'_'.$id.'.jpg';
		$file[] = $image_dir.$alexid.'_'.$id.'.png';
		$file[] = $image_dir.$alexid.'_'.$id.'.jpeg';
	
		
		foreach($file as $f){
		
			if(file_exists($f)){
			
				return $f;
			
			}
		
		}
			
	//-e get_image
	}
	



	static function generate_image_path($alexid,$path,$createdir) {
	
		// generates file path for cloud
		// does not return filename itself
		// if createdir is true then directories will be created if dont exist
	
  	$charArray = str_split($alexid);

    $ID="";
		
    foreach ($charArray as $char) {
        $ID += ord($char);
		}

    $LetterDirectory = chr(fmod($ID,26)+65);
		
		if($createdir == true) {
			if (!is_dir($path."/".$LetterDirectory)) {
					@mkdir($path."/".$LetterDirectory);
			} 
		}

    $Subdirectory =fmod($ID,100);
    
		if($createdir == true) {
			if (!is_dir($path."/".$LetterDirectory."/".$Subdirectory)) {
				 @mkdir($path."/".$LetterDirectory."/".$Subdirectory);
			}
		}

    $filename = sprintf("%s%s/%s/",$path,$LetterDirectory,$Subdirectory);

    return $filename;
		
	//-generate_image_path
	}

	
	
 	static function resize_and_upload ($file,$image_name,$path,$width) {
	
		// example
		// -------
		// image_name = 'mypic'
		// path = 'uploads/'
		// width = 100
		// file extension is dynamically created
		
		//the new width of the resized image, in pixels.
		
		$img_thumb_width = $width; // 
		
		$extlimit = "yes"; //Limit allowed extensions? (no for all extensions allowed)
		
		//List of allowed extensions if extlimit = yes
		
		$limitedext = array(".gif",".jpg",".png",".jpeg");		
		
		//the image -> variables
		
		$file_type = $_FILES[$file]['type'];
		
		$file_name = $_FILES[$file]['name'];
		
		$file_size = $_FILES[$file]['size'];
		
		$file_tmp = $_FILES[$file]['tmp_name'];
		
		//check the file's extension

		$ext = strrchr($file_name,'.');
		
		$ext = strtolower($ext);
		
		//uh-oh! the file extension is not allowed!
		
		if (($extlimit == "yes") && (!in_array($ext,$limitedext))) {
		
			echo "Wrong file extension.  <br>--<a href=\"$_SERVER[PHP_SELF]\">back</a>";
			
			exit();

		}
		
		//so, whats the file's extension?

		$getExt = explode ('.', $file_name);
		
		$file_ext = $getExt[count($getExt)-1];
		
		//create a random file name
		
		$rand_name = md5(time());
		
		$rand_name= rand(0,999999999);
		
		//the new width variable
		
		$ThumbWidth = $img_thumb_width;
		
		/////////////////////////////////
		
		// CREATE THE THUMBNAIL //
		
		////////////////////////////////
		
		
		//keep image type
		
		if($file_size){
		
		if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
		
		$new_img = imagecreatefromjpeg($file_tmp);
		
		}elseif($file_type == "image/x-png" || $file_type == "image/png"){
		
		$new_img = imagecreatefrompng($file_tmp);
		
		}elseif($file_type == "image/gif"){
		
		$new_img = imagecreatefromgif($file_tmp);
		
		}
		
		//list the width and height and keep the height ratio.

		list($width, $height) = getimagesize($file_tmp);
		
		//calculate the image ratio
		
		$imgratio=$width/$height;
		
		if ($imgratio>1){
		
		$newwidth = $ThumbWidth;
		
		$newheight = $ThumbWidth/$imgratio;
		
		}else{
		
		$newheight = $ThumbWidth;
		
		$newwidth = $ThumbWidth*$imgratio;
		
		}
		
		//function for resize image.

	
		
		$resized_img = imagecreatetruecolor($newwidth,$newheight);		
		
		//the resizing is going on here!
		
		imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
		//finally, save the image
		
		ImageJpeg ($resized_img,$path.$image_name.'.'.$file_ext);
				
		ImageDestroy ($resized_img);
		
		ImageDestroy ($new_img);
		
		}
	
	}

	

//-e class
}







?>