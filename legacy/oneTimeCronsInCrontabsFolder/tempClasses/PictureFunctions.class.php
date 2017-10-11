<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

class PictureFunctions
{
	public function maintain_ratio_canvas($pic_name,$final_pic_name,$x1,$y1,$x2,$y2,$width,$height,$type_of_image)
	{
		$filename = $pic_name;
		$new_filename = $final_pic_name;
		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			$image = imagecreatefromgif($filename);
		else
			$image = imagecreatefromjpeg($filename);
			

		$width_orig = imagesx($image);
		$height_orig = imagesy($image);

		$ratio_orig = ($width_orig/$height_orig);
	
		if ($width_orig<$width && $height_orig<$height)
		{
			$width = $width_orig;
			$height = $height_orig;
		}	
		else
		{
			if ($width/$height > $ratio_orig) 
			{
				 $width = $height*$ratio_orig; 
			} 
			else 
			{
				$height = $width/$ratio_orig;
			}
		}
		
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		imagecopyresampled($image_p, $image, $x2, $y2, $x1, $y1, $width, $height, $width_orig, $height_orig);

		// Output
		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			imagegif($image_p, $new_filename, 100);
		else
			imagejpeg($image_p, $new_filename, 100);
		//$command = "chmod -R 777 ".$new_filename;
		//shell_exec($command);
		chmod($new_filename, 0777);
	}

	public function maintain_ratio_profile_thumb($pic_name,$final_pic_name,$x1,$y1,$x2,$y2,$width,$height,$final_width,$final_height,$type_of_image)
	{
		$filename = $pic_name;
		$new_filename = $final_pic_name;

		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			$image = imagecreatefromgif($filename);
		else
			$image = imagecreatefromjpeg($filename);
			
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		imagecopyresampled($image_p, $image, $x2, $y2, $x1, $y1, $width, $height, $width, $height);

		$width_orig = $width;
		$height_orig = $height;

		$image_p1 = imagecreatetruecolor($final_width, $final_height);
		imagecopyresampled($image_p1, $image_p, 0, 0, 0, 0, $final_width, $final_height, $width_orig, $height_orig);

		// Output
		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			imagegif($image_p1, $new_filename, 100);
		else
			imagejpeg($image_p1, $new_filename, 100);

		//$command = "chmod -R 777 ".$new_filename;
		//shell_exec($command);
		chmod($new_filename, 0777);
	}

	public function generate_image_for_canvas($new_filename,$max_height,$max_width,$type_of_image)
	{
		$filename1 = $new_filename;
		
		$hmargin=0;
	        $wmargin=0;
	        
	      	if($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
	          	$src_img = imagecreatefromgif($filename1);
	    	else
	            	$src_img = imagecreatefromjpeg($filename1);
	
	        $w=imagesx($src_img);
	        $h=imagesy($src_img);
		
	        if($h<$max_height)
	                $hmargin=($max_height-$h)/2;
	        if($w<$max_width)
	                $wmargin=($max_width-$w)/2;
		
	        if($hmargin && $wmargin)
	        {
	                $x = $wmargin;
	                $y = $hmargin;
	        }
	        elseif($wmargin)
	        {
	                $x = $wmargin;
	                $y = 0;
	        }
	        elseif($hmargin)
	        {
	                $x = 0;
	                $y = $hmargin;
	        }
	        else
	        {
	                $x = 0;
	                $y = 0;
	        }

                if($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
                {
                        if ($max_width == 340)
                                $filename = $_SERVER["DOCUMENT_ROOT"]."/images/white340.gif";
                        else
                                $filename = $_SERVER["DOCUMENT_ROOT"]."/images/white96.gif";
                        $des_img = imagecreatefromgif($filename);
                }
                else
                {
                        if ($max_width == 340)
                                $filename = $_SERVER["DOCUMENT_ROOT"]."/images/white340.jpg";
                        else
                                $filename = $_SERVER["DOCUMENT_ROOT"]."/images/white96.jpg";
                        $des_img = imagecreatefromjpeg($filename);
                }

	       	imagecopymerge($des_img, $src_img, $x, $y, 0, 0, $w, $h , 100);
		unset($src_img);

	        if($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
	        {
	                imagegif($des_img,$filename1,100 );
	        }
	        else
	        {
	                imagejpeg($des_img,$filename1,100 );
	        }

		//$command = "chmod -R 777 ".$filename1;
		//shell_exec($command);
		chmod($filename1, 0777);
	
	        unset($des_img);
		unset($filename1);
	}

	public function photo_resize($width, $height, $req_width, $req_height)
	{
		if( $width > $height)
		{
			$hei=round($req_width*$height/$width);
			$wid=$req_width;
			if($hei>$req_height)
			{
				$hei=$req_height;
				$wid=round($req_height*$width/$height);
			}
			$height=$hei;
			$width=$wid;
		}
		elseif( $height > $width)
		{
			$wid=round($req_height*$width/$height);
			$hei=$req_height;
			if($wid>$req_width)
			{
				$wid=$req_width;
				$hei=round($req_width*$height/$width);
			}
			$height=$hei;
			$width=$wid;
		}
		else
		{
			if($req_height < $req_width)
				$x=$req_height;
			else
				$x=$req_width;
			$height=$x;
			$width=$x;
		}
		$hh=0;
		$ww=0;

		if($height<$req_height)
		{
			$hh=($req_height-$height)/2;
			$hh.="px";
		}
		if($width<$req_width)
		{
			$ww=($req_width-$width)/2;
			$ww.="px";
		}
		$size[0]=$ww; //left right margin
		$size[1]=$hh; //top bottom margin
		$size[2]=$width; //final width
		$size[3]=$height; //final height

		return $size;
	}

	public function createWatermark($filename_path,$type_of_pic,$format)
	{
		if ($type_of_pic == "main")
			$watermark_path = sfConfig::get('sf_web_dir')."/images/watermark_big_1.gif";
		else
			$watermark_path = sfConfig::get('sf_web_dir')."/images/watermark_small.gif";
			
		$destination_path = $filename_path;

		if($format == "image/gif" || $format == "image/GIF")
                        $src_handle = imagecreatefromgif($filename_path);
                else
                        $src_handle = imagecreatefromjpeg($filename_path);

		$width = imagesx($src_handle);
		$height = imagesy($src_handle);

		$watermark_handle = imagecreatefromgif($watermark_path);
		$w = imagesx($watermark_handle);
		$h = imagesy($watermark_handle);

		$x = $width-$w;
		$y = ($height-$h)/2;

		imagecopymerge($src_handle,$watermark_handle,$x,$y,0,0,$w,$h,30);

		if ($format == "image/gif" || $format == "image/GIF")
        		imagegif($src_handle,$destination_path);
		else
        		imagejpeg($src_handle,$destination_path);
		

		chmod($destination_path,0777);
		unset($src_handle);
		unset($watermark_handle);
	}
}
?>
