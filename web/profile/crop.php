<?php
function maintain_aspect_ratio($profileid,$flag,$whichfile,$which_photo)
{
	$folder_path="photo_buffer/";
	if($whichfile)
	{
		if($which_photo == "mainphoto")
			$filename = $folder_path.$profileid."_main.gif";
		elseif($which_photo == "albumphoto1")
			$filename = $folder_path.$profileid."_a1.gif";
		elseif($which_photo == "albumphoto2")
			$filename = $folder_path.$profileid."_a2.gif";
	}
	else
	{
		if($which_photo == "mainphoto")
                        $filename = $folder_path.$profileid."_main.jpg";
                elseif($which_photo == "albumphoto1")
                        $filename = $folder_path.$profileid."_a1.jpg";
                elseif($which_photo == "albumphoto2")
                        $filename = $folder_path.$profileid."_a2.jpg";
	}
        // Get the size of the original image into an array
	if(!file_exists($filename))
		return 0;
        $size = getimagesize( $filename );
	// Set the new width of the image as the Canvas Size
	if($flag)
	{
		if(($size[0]>340)||($size[1]>310))
		{
			$a1 = $size[0]/340;
			$a2 = $size[1]/310;
			if($a1>=$a2)
			{
				$thumb_width = 340;
				$w = $thumb_width;
				$thumb_height = $size[1]/$a1;
				$h = $thumb_height;
			}
			else
			{
				$thumb_width = $size[0]/$a2;
				$w = $thumb_width;
                        	$thumb_height = 310;
				$h = $thumb_height;
			}
		}
		else
		{
			$thumb_width = $size[0];
                        $w = $thumb_width;
                        $thumb_height = $size[1];
                        $h = $thumb_height;
		}
	}
	else
	{
		if(($size[0]>150)||($size[1]>200))
	        {
			$a1 = $size[0]/150;
        	        $a2 = $size[1]/200;
                	if($a1>=$a2)
	                {
        	                $thumb_width = 150;
				$w = $thumb_width;
                        	$thumb_height = $size[1]/$a1;	
				$h = $thumb_height;
	                }
        	        else
                	{
                        	$thumb_width = $size[0]/$a2;
				$w = $thumb_width;
        	                $thumb_height = 200;
				$h = $thumb_height;
                	}
		}
		else
                {
                        $thumb_width = $size[0];
                        $w = $thumb_width;
                        $thumb_height = $size[1];
                        $h = $thumb_height;
                }
	}	

        // Create a new true color image in the memory
        $thumbnail = ImageCreateTrueColor( $thumb_width, $thumb_height );
	//echo getMemoryRequiredToEdit($filename);
	//	echo "*";
        // Create a new image from file 
	if($whichfile)
		$src_img = ImageCreateFromGIF( $filename );
	else
        	$src_img = ImageCreateFromJPEG( $filename );
	//echo memory_get_usage();exit;
	unset($filename);
        // Create the resized image
        imagecopyresampled( $thumbnail, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1] );
	if($whichfile)
	{
		$filename2 = $folder_path.$profileid."_ready.gif";
		ImageGIF( $thumbnail, $filename2,100 );
	}
	else
	{
		$filename2 = $folder_path.$profileid."_ready.jpg";
		ImageJPEG( $thumbnail, $filename2,100 );
	}
	unset( $thumbnail);
	unset( $src_img);
	gen_img_for_canvas($profileid,$whichfile);
}
/*function getMemoryRequiredToEdit($sImagePath)
{
    $aImageInfo = getimagesize($sImagePath);
    return round((($aImageInfo[0] * $aImageInfo[1] * $aImageInfo['bits'] * $aImageInfo['channels'] / 8 + Pow(2, 16)) * 1.65));
}*/

function gen_img_for_canvas($profileid,$whichfile)
{
	$folder_path="photo_buffer/";
	$hmargin=0;
        $wmargin=0;
	if($whichfile)
		$filename1 = $folder_path.$profileid."_ready.gif";
	else
		$filename1 = $folder_path.$profileid."_ready.jpg";
        $size = getimagesize( $filename1 );
        $w=$size[0];
        $h=$size[1];
        if($h<310)
                $hmargin=(310-$h)/2;
        if($w<340)
                $wmargin=(340-$w)/2;
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
        if($whichfile)
        {
                $filename = "images/white340.gif";
                $des_img = ImageCreateFromGIF($filename);
        }
	else
        {
                $filename = "images/white340.jpg";
                $des_img = ImageCreateFromJPEG($filename);
        }
        if($filename1)
        {
                if($whichfile)
                        $src_img = ImageCreateFromGIF($filename1);
                else
                        $src_img = ImageCreateFromJPEG($filename1);
                imagecopymerge($des_img, $src_img, $x, $y, 0, 0, $w, $h , 100);
        }
	unset($filename1);
	unset($src_img);
        if($whichfile)
        {
                $filename2 = $folder_path.$profileid."_readymade.gif";
                ImageGIF($des_img,$filename2,100 );
        }
        else
        {
                $filename2 = $folder_path.$profileid."_readymade.jpg";
                ImageJPEG($des_img,$filename2,100 );
        }
        unset($des_img);
	if(file_exists($folder_path.$profileid."_main.gif"))
		unlink($folder_path.$profileid."_main.gif");
	if(file_exists($folder_path.$profileid."_a1.gif"))
		unlink($folder_path.$profileid."_a1.gif");
	if(file_exists($folder_path.$profileid."_a2.gif"))
		unlink($folder_path.$profileid."_a2.gif");
	if(file_exists($folder_path.$profileid."_ready.gif"))
		unlink($folder_path.$profileid."_ready.gif");
	if(file_exists($folder_path.$profileid."_main.jpg"))
		unlink($folder_path.$profileid."_main.jpg");
	if(file_exists($folder_path.$profileid."_a1.jpg"))
		unlink($folder_path.$profileid."_a1.jpg");
	if(file_exists($folder_path.$profileid."_a2.jpg"))
		unlink($folder_path.$profileid."_a2.jpg");
	if(file_exists($folder_path.$profileid."_ready.jpg"))
		unlink($folder_path.$profileid."_ready.jpg");
}
	
function crop_image($profileid,$x1,$x2,$y1,$y2,$width,$height,$thumb,$whichfile)
{
	$folder_path="photo_buffer/";
	if($whichfile)
		$input_image = $folder_path.$profileid."_readymade.gif";
	else
		$input_image = $folder_path.$profileid."_readymade.jpg";  
	
	if(!file_exists($input_image))
                return 0;	

	$size = getimagesize( $input_image );
	
	// Prepare canvas
	$canvas = imagecreatetruecolor($width,$height);
	if($whichfile)
		$cropped = imagecreatefromgif( $input_image );
	else
		$cropped = imagecreatefromjpeg( $input_image );
	
	// Generate the cropped image */

	imagecopyresized($canvas,$cropped,0,0,$x1,$y1,$width,$height,$width,$height);

	// Save the cropped image as cropped.jpg
	
	if($whichfile)
	{
		$filename1 = $folder_path.$profileid."_crop.gif";
		imagegif( $canvas,$filename1,100 );
	}
	else
	{
		$filename1 = $folder_path.$profileid."_crop.jpg";
		imagejpeg( $canvas,$filename1,100 );
	}
	// Clear the memory of the tempory images

	unset( $canvas );
	unset( $cropped );
	
						/* Second Resizing of Image for Displaying Cropped Image infront of User  */

	/* Converted Cropped Image into the Preview Canvas Size,so that user can see same image as he was able to see in the Preview Canvas */

	// Get the size of the original image into an array
	$size = getimagesize( $filename1 );

	// Set the new width of the image
	if(!$thumb)
		$thumb_width = 150;
	else
		$thumb_width = 60;

	// Set the new Height of the image
	if(!$thumb)
                $thumb_height = 200;
        else
		$thumb_height = 60;

	// Create a new true color image in the memory
	$thumbnail = ImageCreateTrueColor( $thumb_width, $thumb_height );

	// Create a new image from file 
	if($whichfile)
		$src_img = ImageCreateFromGIF( $filename1 );
	else
		$src_img = ImageCreateFromJPEG( $filename1 );
	
	unset($filename1);
	
	// Create the resized image
	imagecopyresampled( $thumbnail, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1] );
	
	unset($src_img);

	if($whichfile)
	{
		if($thumb)
		{
                        $filename2 = $folder_path.$profileid."_thumb.gif";
			ImageGIF( $thumbnail, $filename2,100 );
		}
                else
		{
                        $filename2 = $folder_path.$profileid."_pro.gif";
			ImageGIF( $thumbnail, $filename2,100 );
		}
	}
	else
	{
		if($thumb)
		{
			$filename2 = $folder_path.$profileid."_thumb.jpg";
			ImageJPEG( $thumbnail, $filename2,100 );
		}
		else
		{
			$filename2 = $folder_path.$profileid."_pro.jpg";
			ImageJPEG( $thumbnail, $filename2,100 );
		}
	}

	// Clear the memory of the tempory image 
	unset( $thumbnail );
	if($whichfile)
                unlink($folder_path.$profileid."_crop.gif");
        else
                unlink($folder_path.$profileid."_crop.jpg");
}
?>
