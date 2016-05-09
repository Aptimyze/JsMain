<?php

function album_create($data)
{
	global $img_path;
	global $alb1,$alb2;
	global $alb_path;
	global $im;
	global $font_path_sms;
	global $fps;
	global $temp_image;
	$alb1=$data[33];
	$alb2=$data[34];
	$alb_path="album";
	$k=0;
	$photo = imagecreatefromjpeg("template/4_4_morephots.jpg");
	imagecopymerge  ($photo ,$im,88,108,0,0,141,188,100);
	$no_ph=1;
	while($k<2)
	{
		$k++;
		$var="alb".$k;
		if($$var!='N') //Symfony Photo Modification
		{
			$no_ph=0;
			$img_url=$$var; //Symfony Photo Modification

			$file = file_get_contents($img_url);
			//Creating image by the name of profileid(to make it unique)
			$filename="$alb_path/alb_".$k."_".$data[0].".jpeg";
			
			file_put_contents($filename,$file);
			
			
			//$im=imagecreatefromjpeg($filename);
			resizeimage($filename,$photo,$data,$k);
			
		}
	}
	
	if($no_ph)
	{
		
		$file_temp="profile/".$data[0].".jpeg";
		//file_put_contents($filename,$file);
		$filename="$alb_path/".$data[0]."_album.jpeg";
		$re_montage="montage -geometry 180X276 -background white -quality 100 $file_temp $filename";
		shell_exec($re_montage);
		resizeimage($filename,$photo,$data,1);
		
	}
	$text_color = imagecolorallocate ($temp_image, 0, 0,0);
        $cnt_color = imagecolorallocate ($temp_image, 0, 105,185);
	//Putting profile image in actual image(to be used for dvd)
	$more_detail="More details of the profile SMS info ".$data[0]." to 09870803838";
	imagettftext($photo, 11, 0,265,460, $text_color, "$font_path_sms", $more_detail);
	
	imagejpeg($photo,"$img_path/$data[0]_4_4.jpeg",100);
}
function resizeimage($filename,$photo,$data,$k)
{
	global $img_path;
	global $alb_path;
	global $fps;
	$new_width = 180;
	$new_height = 276;
	//Resetting the profile image.
	
	list($width, $height) = getimagesize($filename);
	$re_montage="montage -geometry ".$new_width."x".$new_height." -background white -quality 100 $filename $filename;";
	shell_exec($re_montage);
	// Resample
	//$image_p = imagecreatetruecolor($new_width, $new_height);
	//$image = imagecreatefromjpeg($filename);
	
	//imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	//$filename="$alb_path/alb_".$k."_check_".$data[0].".jpeg";
	//imagejpeg($image_p,$filename);

	$im=imagecreatefromjpeg($filename);
	
	//Merge with blank image first..
	$blank_img=imagecreatefromjpeg("template/blank.jpg");
	imagecopymerge($blank_img,$im,7,7,0,0,180,276,100);
	$variable_x=256+($k-1)*226;
	imagecopymerge($photo,$blank_img,$variable_x,122,0,0,194,287,100);
	//imagejpeg($photo,"$img_path/$data[0]_4_4.jpeg");

}
?>
