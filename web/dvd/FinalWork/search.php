<?

function search($profile_data,$s0,$s1,$s2,$s3,$s4,$s5,$s6)
{
	
	global $profileid_costumer; 
	
	//$photo = imagecreatefromjpeg("template/2_photo_browse2.jpg");
	if (count($profile_data)>0)
	{
			for($i=0;$i<7;$i++)
			{
					$var="s".$i;
				/*	foreach($$var as $key=>$val)
						echo " search s$i i $key value $val username ".$profile_data[$val][1]." \n";
				*/

				create_search($$var,$profile_data,$var);
			}
		include("dvd_xml.php");
		dvd_xml($profile_data,$s0,$s1,$s2,$s3,$s4,$s5,$s6);
		create_dvd($profileid_costumer);
			
	}

}
function create_search($search,$profile_data,$type_of_search)
{
	
//	print_r($profile_data);
	global $profileid_costumer; 
	global $font_path;
	global $font_size;
	global $fps;
	global $audio_name;
	global $mpeg_params;
	global  $jpeg_params;
	global $temp_image;
	global $gender;
	global $pass;
		$x=56;
		$y=127;
		$count=0;
		$image_no=0;
//		$photo = imagecreatefromjpeg("template/2_photo_browse2.jpg");
		$img_path="search";
		if(count($search))
		{
			for($i=0;$i<count($search);$i++)
			{
				$profileid=$search[$i];
				if($i%6==0)
				{
					if($i!=0)
					{
						imagejpeg($photo,"$img_path/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles.".jpeg",100);
						$jpeg2yuv="jpeg2yuv  $jpeg_params -j \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles.".jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.m2v\"";
						
						$mplex="mplex -f 8   \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.m2v\" $audio_name -o \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.mpg\"";
						$spumux="spumux search_spumux_6.xml < \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.mpg\" > \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles.".mpg\"";	
						shell_exec($jpeg2yuv);
						shell_exec($mplex);
						shell_exec($spumux);
					}
					$y=127;
					$image_no=intval($i/6);
					if((count($search)-$i)>5)
					{
						$no_of_profiles=6;
						$photo = imagecreatefromjpeg("template/2_photo_browse_6.jpg");
						$text_color = imagecolorallocate ($temp_image, 0, 0,0);
					}	
					else
					{
						$no_of_profiles=count($search)-$i;
					//	echo "No. of profiles".$no_of_profiles."\n";
						//$photo_used="template/2_photo_browse_".$no_of_profiles.".jpg";
						$photo_used="template/2_photo_browse_6.jpg";
						$photo = imagecreatefromjpeg($photo_used);
						$text_color = imagecolorallocate ($temp_image, 0, 0,0);
					}	
				}
				$k=($i-3)%6;
				
				if(($i-3)%6==0 && ($i-3)>=0)
				$y=309;
				$file_temp="profile/".$profileid.".jpeg";
				//file_put_contents($filename,$file);
				$filename="profile/".$profileid."_small.jpeg";
				$re_montage="montage -geometry 96x121 -background white -quality 100 $file_temp $filename";
				shell_exec($re_montage);
				
				
				$im=imagecreatefromjpeg($filename);
				imagecopymerge($photo,$im,$x,$y,0,0,96,121,100);
			///add age	
				$age=$profile_data[$profileid][2];
				
				imagettftext($photo, $font_size, 0,$x+106,$y+19, $text_color, "$font_path", $age);
				
				$height=$profile_data[$profileid][3];
				imagettftext($photo, $font_size, 0,$x+106,$y+46, $text_color, "$font_path", $height);
				
			///add degree
				$degree=$profile_data[$profileid][11];
				$degree= strtolower($degree);
				$degree = wordwrap($degree, 10, "\n",true);
				imagettftext($photo, $font_size, 0,$x+106,$y+70, $text_color, "$font_path", $degree);	
					
				$x=($x+217)%651;
			}
			$search_image="search/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles.".jpeg";
			if(!file_exists($search_image) || $pass)
			{
				imagejpeg($photo,$search_image,100);
				$jpeg2yuv="jpeg2yuv  $jpeg_params -j \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles.".jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.m2v\"";
				
				$mplex="mplex -f 8  \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.m2v\" $audio_name -o \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.mpg\"";
				
				$spumux="spumux search_spumux_6.xml < \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_".$no_of_profiles."_without.mpg\" > \"".$img_path."/".$profileid_costumer."_search_".$type_of_search."_".$image_no."_6.mpg\"";	
				
				
				shell_exec($jpeg2yuv);
				shell_exec($mplex);
				shell_exec($spumux);	
			}
		}
}
?>
