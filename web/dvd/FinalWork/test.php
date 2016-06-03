<?php
include("album.php");
include("all_func.php");
$profileid_costumer=$argv[1];
//$profileid_costumer=660;
//$profileid=$argv[0];

$row = 1;
//$font_path="/home/nikhil/download/svn-live/realsvn/branches/sms_sep23/dvd/FinalWork/FreeSansBold.ttf";
//$font_path_sms="/home/nikhil/download/svn-live/realsvn/branches/sms_sep23/dvd/FinalWork/FreeSans.ttf";
$font_path="FreeSansBold.ttf";
$font_path_sms="FreeSans.ttf";
$font_path=$font_path_sms;
$img_path="profile";
$font_size=12;
$word_wrap=55;
$audio_name="silence.mp2";
$fps="";
$mpeg_params=" -n p -f 8 -a 2 -v 0 -q 1 ";
$jpeg_params=" -I p -f 25 -n 25 -v 0 ";
$pass=false;
$temp_image = imagecreatefromjpeg("tm_dir/temp.jpg");
if (($handle = fopen("csvs/".$profileid_costumer.".csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ",",'"')) !== FALSE) {
      
        $row++;
        
        if($row==3)
        {
			$gender=create_home_page($data);
		}

        //Used to skip the first line of csv
        if($row==2 || $row==3)
			continue;
		
		if(!file_exists("$img_path/$data[0].jpeg") || $pass )//Getting the profile image of user..
		{
			if($data[32]!='N')//Symfony Photo Modification
			{
				$img_url="$data[32]"; //Symfony Photo Modification
			}
			else
			{
					$img_url="Main/male_no_photo.jpg";
					if($gender=='F')
						$img_url="Main/female_no_photo.jpg";
			}
			$file = file_get_contents($img_url);
			//Creating image by the name of profileid(to make it unique)
			$filename="$img_path/$data[0].jpeg";
			file_put_contents($filename,$file);
			
			//Resetting the profile image.
			$re_montage="montage -geometry 141x188 -background white -quality 100 $filename $filename";
			shell_exec($re_montage);
			//Fetching out first profile page.
			$r_hindu=array();
			if($data[35]=="Caste" && $data[4]=="Hindu")
			{
					$basic_details = imagecreatefromjpeg("template/4_1_detailed.jpg");
					$r_hindu=array();
			}
			if($data[35]=="Caste" && $data[4]!="Hindu")
			{
					$basic_details = imagecreatefromjpeg("template/4_1_detailed_nh.jpg");
					$r_hindu=array(7,8);
			}
			if($data[35]=="Sect")
			{
					$basic_details = imagecreatefromjpeg("template/4_1_detailed_s.jpg");
					$r_hindu=array(7,8);
			}
			if($data[35]=="")
			{
				$basic_details = imagecreatefromjpeg("template/4_1_detailed_n.jpg");
				$r_hindu=array(6,7,8);
			}
				
			
			
			$red_color = imagecolorallocate ($temp_image, 0, 105,185); 
			$text_color = imagecolorallocate ($temp_image, 0, 0,0);
			$Profile_ID="Profile ID : ".$data[1]."\n";
			//$str="";
			
			//Putting profile information in image.
			imagettftext($basic_details, $font_size, 0, 260,130, $red_color, "$font_path", $Profile_ID);
			$k=0;
			for ($c=2; $c < 14; $c++) {
				
				if(!in_array($c,$r_hindu))
				{
					//$k++;								
					if($data[$c]=="" || $data[$c]=="0")
						$data[$c]="-";
					$str=" : ".htmlspecialchars_decode($data[$c],ENT_QUOTES)."\n";
					imagettftext($basic_details, $font_size, 0,394,150+(22*($k)), $text_color, "$font_path", $str);
					$k++;
				}
				
			}
			
			//Putting profile image in actual image(to be used for dvd)
			$more_detail="More details of the profile SMS info ".$data[0]." to 09870803838";
			imagettftext($basic_details, 11, 0,265,460, $text_color, "$font_path_sms", $more_detail);
			
			$im=imagecreatefromjpeg($filename);
			imagecopymerge  ($basic_details ,$im,88,108,0,0,141,188,100);
			imagejpeg($basic_details,"$img_path/$data[0]_4_1.jpeg",100);
			/*`jpeg2yuv -I p -f $fps -j "$img_path/$data[0]_4_1.jpeg" | mpeg2enc -n p -f 8 --aspect 2 -o "$img_path/$data[0]_4_1.m2v"`;
			 `mplex -f 8 "$img_path/$data[0]_4_1.m2v" $audio_name -o "$img_path/$data[0]_4_1_without.mpg"`;
			`spumux profile_spumux.xml < "$img_path/$data[0]_4_1_without.mpg" > "$img_path/$data[0]_4_1.mpg"`;			*/
			$jpeg2yuv="jpeg2yuv $jpeg_params -j \"".$img_path."/".$data[0]."_4_1.jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$data[0]."_4_1_without.m2v\"";
				
			$mplex="mplex -f 8  -v 0 \"".$img_path."/".$data[0]."_4_1_without.m2v\" $audio_name -o \"".$img_path."/".$data[0]."_4_1_without.mpg\"";
			$spumux="spumux profile_spumux_1.xml < \"".$img_path."/".$data[0]."_4_1_without.mpg\" > \"".$img_path."/".$data[0]."_4_1.mpg\"";	
			shell_exec($jpeg2yuv);
			shell_exec($mplex);
			shell_exec($spumux);
			
			
			
			// For Second page
			$photo = imagecreatefromjpeg("template/4_2_aboutme.jpg"); //Getting second profile page.
			$about="About ".$data[1]."\n";		
			imagettftext($photo, $font_size, 0, 256,130, $red_color, "$font_path", $about);
			//$str="";
			$str=htmlspecialchars_decode($data[16],ENT_QUOTES);;
			$str= str_replace("\n", " ", $str);
			$len=strlen($str);
			//echo $len;
			$substr=substr($str, 0, 360);
			if($len >= 360)
			{
				$end_str="...";
				$substr1=$substr.$end_str;
				$substr=$substr1;
			}
			//Wrapping word so that word won't go out of image.
			$newtext = wordwrap($substr, $word_wrap, "\n",true);
			$newtext = strtolower($newtext);
				
			imagettftext($photo, $font_size, 0,256,150, $text_color, "$font_path", $newtext);
			$about="About ".$data[1]." family \n";
			$about="Other Info \n";
			imagettftext($photo, $font_size, 0, 256,330, $red_color, "$font_path", $about);
			$str=htmlspecialchars_decode($data[15],ENT_QUOTES);
			$str= str_replace("\n", " ", $str);
			$len=strlen($str);
			//echo $len;
			$substr=substr($str, 0, 150);
			if($len >= 150)
			{
				$end_str="...";
				$substr1=$substr.$end_str;
				$substr=$substr1;
			}
			//echo $substr;
			$newtext = wordwrap($substr, $word_wrap, "\n",true);
			$newtext= strtolower($newtext);
			imagettftext($photo, $font_size, 0,256,355, $text_color, "$font_path", $newtext);
			
			
			$more_detail="More details of the profile SMS info ".$data[0]." to 09870803838";
			imagettftext($photo, 11, 0,265,460, $text_color, "$font_path_sms", $more_detail);
			
			imagecopymerge($photo ,$im,88,108,0,0,141,188,100);
			imagejpeg($photo,"$img_path/$data[0]_4_2.jpeg",100);
			/*`jpeg2yuv -I p -f 25 -j "$img_path/$data[0]_4_2.jpeg" | mpeg2enc -n p -f 8 --aspect 2 -o "$img_path/$data[0]_4_2.m2v"`;
			 `mplex -f 8 "$img_path/$data[0]_4_2.m2v" $audio_name -o "$img_path/$data[0]_4_2_without.mpg"`;
			`spumux profile_spumux.xml < "$img_path/$data[0]_4_2_without.mpg" > "$img_path/$data[0]_4_2.mpg"`;			*/
			$jpeg2yuv="jpeg2yuv $jpeg_params -j \"".$img_path."/".$data[0]."_4_2.jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$data[0]."_4_2_without.m2v\"";
				
			$mplex="mplex -f 8  -v 0 \"".$img_path."/".$data[0]."_4_2_without.m2v\" $audio_name -o \"".$img_path."/".$data[0]."_4_2_without.mpg\"";
			$spumux="spumux profile_spumux_2.xml < \"".$img_path."/".$data[0]."_4_2_without.mpg\" > \"".$img_path."/".$data[0]."_4_2.mpg\"";	
			shell_exec($jpeg2yuv);
			shell_exec($mplex);
			shell_exec($spumux);
	
		
			// third page
			if($data[35]=="Sect")
				$photo = imagecreatefromjpeg("template/4_3_dpp_sect.jpg");
			else if($data[4]=="Hindu")
				$photo = imagecreatefromjpeg("template/4_3_dpp.jpg");
			else
				$photo = imagecreatefromjpeg("template/4_3_dpp_nm.jpg");
				
			$about="About ".$data[1]." Partner profile \n";		
			imagettftext($photo, $font_size, 0, 256,130, $red_color, "$font_path", $about);
			$str=htmlspecialchars_decode($data[17],ENT_QUOTES);
			$str= str_replace("\n", " ", $str);
			$len=strlen($str);
			//echo $len;
			$substr=substr($str, 0, 180);
			if($len >= 180)
			{
				$end_str="...";
				$substr1=$substr.$end_str;
				$substr=$substr1;
			}
			//echo $substr;
			$newtext = wordwrap($substr, 50, "\n",true);	
			$newtext= strtolower($newtext);
			imagettftext($photo, $font_size, 0,256,150, $text_color, "$font_path", $newtext);
			
				$k=18;
				$dnt_allow=0;
				if($data[4]!="Hindu")
					$dnt_allow=24;
				for ($c=18; $c < 26; $c++) 
				{
					if($c!=$dnt_allow)
					{ 
						if($data[$c]==""  || $data[$c]=="0")
							$data[$c]="-";
						$str=" : ".htmlspecialchars_decode($data[$c],ENT_QUOTES)."\n";
						imagettftext($photo, $font_size, 0,397,276+(22*($k-18)), $text_color, "$font_path", $str);
						$k++;
					}
				}
			
			
			imagettftext($photo, 11, 0,265,460, $text_color, "$font_path_sms", $more_detail);
			
			imagecopymerge  ($photo ,$im,88,108,0,0,141,188,100);
			imagejpeg($photo,"$img_path/$data[0]_4_3.jpeg",100);
			
			$jpeg2yuv="jpeg2yuv  $jpeg_params -j \"".$img_path."/".$data[0]."_4_3.jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$data[0]."_4_3_without.m2v\"";
				
			$mplex="mplex -f 8  -v 0 \"".$img_path."/".$data[0]."_4_3_without.m2v\" $audio_name -o \"".$img_path."/".$data[0]."_4_3_without.mpg\"";
			$spumux="spumux profile_spumux_3.xml < \"".$img_path."/".$data[0]."_4_3_without.mpg\" > \"".$img_path."/".$data[0]."_4_3.mpg\"";	
			shell_exec($jpeg2yuv);
			shell_exec($mplex);
			shell_exec($spumux);
		//fourth image
			album_create($data);
			
			//$photo = imagecreatefromjpeg("template/4_4_morephots.jpg");
			
			$jpeg2yuv="jpeg2yuv  $jpeg_params -j \"".$img_path."/".$data[0]."_4_4.jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$data[0]."_4_4_without.m2v\"";	
			$mplex="mplex -f 8 \"".$img_path."/".$data[0]."_4_4_without.m2v\" $audio_name -o \"".$img_path."/".$data[0]."_4_4_without.mpg\"";
			$spumux="spumux profile_spumux_4.xml < \"".$img_path."/".$data[0]."_4_4_without.mpg\" > \"".$img_path."/".$data[0]."_4_4.mpg\"";	
			shell_exec($jpeg2yuv);
			shell_exec($mplex);
			shell_exec($spumux);
			
			//Fifth image
			$photo = imagecreatefromjpeg("template/4_5_contact.jpg"); //Getting second profile page.
			
			imagettftext($photo, 11, 0,265,460, $text_color, "$font_path_sms", $more_detail);
			imagecopymerge  ($photo ,$im,88,108,0,0,141,188,100);
			imagejpeg($photo,"$img_path/$data[0]_4_5.jpeg",100);
			
			$jpeg2yuv="jpeg2yuv $jpeg_params -j \"".$img_path."/".$data[0]."_4_5.jpeg\" | mpeg2enc $mpeg_params -o \"".$img_path."/".$data[0]."_4_5_without.m2v\"";
				
			$mplex="mplex -f 8  -v 0 \"".$img_path."/".$data[0]."_4_5_without.m2v\" $audio_name -o \"".$img_path."/".$data[0]."_4_5_without.mpg\"";
			$spumux="spumux profile_spumux_5.xml < \"".$img_path."/".$data[0]."_4_5_without.mpg\" > \"".$img_path."/".$data[0]."_4_5.mpg\"";	
			shell_exec($jpeg2yuv);
			shell_exec($mplex);
			shell_exec($spumux);
			
		
		}
		//To make profile in search use below.. 
		if($data[26])
			$s0[]=$data[0];
		if($data[27])
			$s1[]=$data[0];
		if($data[28])
			$s2[]=$data[0];
		if($data[29])
			$s3[]=$data[0];
		if($data[30])
			$s4[]=$data[0];
		if($data[31])
			$s5[]=$data[0];
			
			$s6[]=$data[0];
		$profile_infr[$data[0]]=$data;
		
    }
    include("search.php");
    search($profile_infr,$s0,$s1,$s2,$s3,$s4,$s5,$s6);
    fclose($handle);
}
?>
