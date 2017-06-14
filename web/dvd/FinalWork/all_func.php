<?php
function create_home_page($data)
{
	global $font_path,$font_path_sms;
	global $font_size;
	global $fps;
	global $audio_name;
	global $mpeg_params;
	global  $jpeg_params;
	global $temp_image;
	$gender=$data[2];
	$data[3]=htmlspecialchars_decode($data[3],ENT_QUOTES);
	$prof_data=str_replace("BREAK, ",",\n",$data[3]);
	$profileid=$data[0];
	$prof_cnt=$data[4];
	$png="1_home_boy_Selected.png";
	$image="1_home_boy.jpg";
	if($gender=='F')
	{
		$png="1_home_girl_Selected.png";
		$image="1_home_girl.jpg";
	}
	$home = imagecreatefromjpeg("template/$image");
	$text_color = imagecolorallocate ($temp_image, 0, 0,0);
	$cnt_color = imagecolorallocate ($temp_image, 0, 105,185);
	$prof_data=htmlspecialchars_decode($prof_data,ENT_QUOTES);
	imagettftext($home, $font_size, 0, 46,154, $text_color, "$font_path", $prof_data);
	imagettftext($home, 14, 0, 217,133, $cnt_color, "$font_path_sms", $prof_cnt);
	imagejpeg($home,"Main/".$profileid."_main.jpeg",100);
	
	$jpeg2yuv="jpeg2yuv  $jpeg_params -j \"Main/".$profileid."_main.jpeg\" | mpeg2enc $mpeg_params -o \"Main/".$profileid."_main_without.m2v\"";
	$mplex="mplex -f 8 -v 0 \"Main/".$profileid."_main_without.m2v\" $audio_name -o \"Main/".$profileid."_main_without.mpg\"";
	$spumux="spumux  home_spumux.xml < \"Main/".$profileid."_main_without.mpg\" > \"Main/".$profileid."_main.mpg\"";	
	shell_exec($jpeg2yuv);
	shell_exec($mplex);
	shell_exec($spumux);
	return $gender;
}
function create_dvd($profileid)
{
	$command=" rm -rf ../dvd_content_".$profileid." ; dvdauthor -x dvdauthor_".$profileid.".xml; mkisofs -dvd-video -o iso/dvd_".$profileid."_".date("Y-m-d").".iso ../dvd_content_".$profileid."/ ;";
	shell_exec($command);
}
?>
