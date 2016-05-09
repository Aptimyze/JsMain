<?php

/**
*       Filename        :       uploadphoto_inc.php
*       Included        :
*       Description     :       Contains all functions related to picture display and add
*       Called From     :       uploadphoto.php
*       Created by      :       Alok
*       Changed by      :       
*       Changed on      :       16-10-2004
*       Changes         :       
**/

/**
*       Function        :       check_all_submission

*       Description     :       checks for the validity of submission done for Success Story
**/



function check_all_submission()
{
	global $Day,$Month,$Year,$check_date,$check_email_h,$check_email_w,$invalidh,$invalidw,$name_h,$name_w,$username_h,$username_h_db,$username_w_db,$email_h_db,$email_w_db;
	global $username_h_error,$username_w,$username_w_error,$email_h_error,$email_w_error,$Subscription_error,$smarty;
	global $email_h,$is_error;
	global $email_w;

		if($Year=="")
			$Year='2007';
		if($Month=="")
			$Month="00";
		if($Day=="")
			$Day="00";
			
		$check_date=1;
	
	if($email_h!="")
		$check_email_h=checkemail1($email_h);
	else
		$check_email_h=1;
		
	if($email_w!="")
		$check_email_w=checkemail1($email_w);	
	else
		$check_email_w=1;
	
	
	if($username_h!="")
		$invalidh=isvalid_username($username_h);

	if($username_w!="'")
		$invalidw=isvalid_username($username_w);
	
	if($name_h=="")
		$name_h_error="Mention husband name";

	if($name_w=="")
		$name_w_error="Mention wife name";

	if($invalidh)
			$username_h_error="Husband's User ID is invalid";

	if($invalidw)
		$username_w_error="Wife's User ID is invalid";

	if(!$check_email_h)
		$email_h_error="Husband's Email ID is invalid";

	if(!$check_email_w)
		$email_w_error="Wife's Email ID is invalid";

	if($username_h=="" && $email_h=="")
	{
			$username_h_error="Provide Husband's Email ID or User ID";
			$email_h_error="&nbsp;";
			$invalidh=1;
	}
	if($username_w=="" && $email_w=="")
	{
			$username_w_error="Provide Wife's Email ID or User ID";
			$email_w_error="&nbsp;";
			$invalidw=1;
	}
		

	if(!$invalidh && !$invalidw && $check_email_h &&$check_email_w)
	{
		if($username_h=="")
			$sql_m="select PROFILEID,EMAIL,USERNAME from newjs.JPROFILE where EMAIL='$email_h' and GENDER='M'";
		else
			$sql_m="select PROFILEID,EMAIL,USERNAME from newjs.JPROFILE where USERNAME='$username_h' and GENDER='M' ";
			
		$res=mysql_query_decide($sql_m);
		if($row=mysql_fetch_row($res))
		{
			$sub_profileid=$row[0];
			if($username_h=="")
			{
					
					$username_h_db=$row[2];
					if($row[1]!=$email_h)
							$mix_h_error="Husband's User ID and Email ID provided do not match.";
			}
			else
			{
					
					if($row[2]!=$username_h)
						$usename_h_error="Husband's User ID provided, does not exist in our database.";
					if($email_h!="")
					{
						if($row[1]!=$email_h)
							$mix_h_error="Husband's User ID and Email ID provided do not match.";
					}
					else 
						$email_h_db=$row[1];
			}
						
		}
		else
		{
			if($username_h=="")
			{
				$email_h_error="Husband's Email ID provided, does not exist in our database.";
			}
			else 
			{
				$username_h_error="Husband's User ID provided, does not exist in our database.";
			}
		}
			
		
		if($username_w=="")
			$sql_m="select PROFILEID,EMAIL,USERNAME from newjs.JPROFILE where EMAIL='$email_w' and GENDER='F'";
		else
			$sql_m="select PROFILEID,EMAIL,USERNAME from newjs.JPROFILE where USERNAME='$username_w' and GENDER='F' ";
			
		$res=mysql_query_decide($sql_m);
		if($row=mysql_fetch_row($res))
		{
			if($sub_profileid)
						$sub_profileid.=",".$row[0];
			else
				$sub_profileid=$row[0];
			if($username_w=="")
			{
					
					$username_w_db=$row[2];
					if($row[1]!=$email_w)
							$mix_w_error="Wife's User ID and Email ID provided do not match.";
			}
			else
			{
					
					if($row[2]!=$username_w)
						$usename_w_error="Wife's User ID provided, does not exist in our database.";
					if($email_w!="")
					{
						if($row[1]!=$email_w)
							$mix_w_error="Wife's User ID and Email ID provided do not match.";
					}
					else 
						$email_w_db=$row[1];
			}
						
		}
		else
		{
			if($username_w=="")
			{
				$email_w_error="Wife's Email ID provided, does not exist in our database.";
			}
			else 
			{
				$username_w_error="Wife's User ID provided, does not exist in our database.";
			}
			
		}

		if($username_h_error=="" && $username_w_error=="" && $email_w_error=="" && $email_h_error=="")
		{
			$sql="SELECT PROFILEID FROM billing.`PURCHASES` WHERE PROFILEID IN ($sub_profileid) AND SERVICEID != 'M'";
			$res=mysql_query_decide($sql) or die (mysql_error_js());
			if(mysql_num_rows($res)<=0)
				$Subscription_error="The user IDs are not compatible according to our records.";
		}
	}
	if($mix_h_error!="" || $mix_w_error!="")
		$is_error=1;
		
	$smarty->assign("mix_h_error",$mix_h_error);
	$smarty->assign("mix_w_error",$mix_w_error);
	
}
/**
*       Function        :       upload
*       Input           :       filename(string), accept_type(string), extention(string)
*       Output          :       Boolean
*       Description     :       cheaks for the validity of file to be inserted
**/


function upload($filename='', $accept_type='', $extention='',$profileid='') 
{
//wont be used for photo module but still in use for horoscope uploading
	global $_FILES;
	global $max_filesize;
	global $file;
	global $errors;
	global $accepted;
	global $max_image_width;
	global $max_image_height;
	$max_filesize=1048576*4;//4 MB
	$error = 0 ;
	if (!is_array($_FILES[$filename]) || !$_FILES[$filename]['name']){
			$errors[0] = "No file was uploaded";
		$accepted  = FALSE;
		return FALSE;
	}
	// Copy PHP's global $_FILES array to a local array
	$file = $_FILES[$filename];
	$file['file'] = $filename;
	if($file['type']=='image/pjpeg')
		$file['type'] = 'image/jpeg';
	elseif($file['type']=='')
	{
		if($file['error'])
		{
			$ext = explode(".",$file['name']);
			$file["type"]="image/".$ext[1];
			if(!ereg(strtolower($accept_type), strtolower($file["type"])))
                        	$error = 2;
			else
				$error = 1;
			return $error;
		}
	}
	// test max size
	if($max_filesize && ($file["size"] > $max_filesize)){
		$errors[1] = "Maximum file size exceeded. File may be no larger than " . $max_filesize/1000 . "KB (" . $max_filesize . " bytes).";
		$error = 1;
		$accepted  = FALSE;
		//return FALSE;
	}
 	if(ereg("image", $file["type"])){
 		/* IMAGES */
 		$image = getimagesize($file["tmp_name"]);
 		$file["width"]  = $image[0];
 		$file["height"] = $image[1];
		// test max image size
		if(($max_image_width || $max_image_height) && (($file["width"] > $max_image_width) || ($file["height"] > $max_image_height))){
			$errors[2] = "Maximum image size exceeded. Image may be no more than " . $max_image_width . " x " . $max_image_height . " pixels";
			$error = 1;
			$accepted  = FALSE;
			//return FALSE;
		}
		// Image Type is returned from getimagesize() function
 		switch($image[2])
		{
 			case 1:
 				$file["extention"] = ".gif"; break;
 			case 2:
 				$file["extention"] = ".jpg"; break;
 			case 3:
 				$file["extention"] = ".png"; break;
 			case 4:
 				$file["extention"] = ".swf"; break;
 			case 5:
 				$file["extention"] = ".psd"; break;
 			case 6:
 				$file["extention"] = ".bmp"; break;
 			case 7:
 				$file["extention"] = ".tif"; break;
 			case 8:
 				$file["extention"] = ".tif"; break;
 			default:
				$file["extention"] = $extention; break;
 		}
	}
	elseif(!ereg("(\.)([a-z0-9]{3,5})$", $file["name"]) && !$extention)
		switch($file["type"]) 
		{
			case "text/plain":
				$file["extention"] = ".txt"; break;
			case "text/rtf":
				$file["extention"] = ".rtf"; break;
			case "text/doc":
			     	$file["extention"] = ".doc"; break;
			
			default:
				break;
		}
 	else
		$file["extention"] = $extention;
	if($accept_type)
		if(ereg(strtolower($accept_type), strtolower($file["type"])))
		{
			$accepted = TRUE;
		}
		else{ 
			$error = 2;
			$accepted = FALSE;
			$errors[3] = "Only " . ereg_replace("\|", " or ", $accept_type) . " files may be uploaded";
		}
	else
		$accepted = TRUE;
	return $error;
}

/**
*       Function        :       save_file
*       Input           :       path(string), overwrite_mode(int)
*       Output          :       Boolean
*       Description     :       Cleans up the filename, copies the file from PHP's temp location to $path, and checks the overwrite_mode
**/

function save_file($path, $overwrite_mode="3")
{
	global $file;
	global $errors;
	global $accepted;
	$path = $path;	
	if($accepted){
		// Clean up file name (only lowercase letters, numbers and underscores)
		$file["name"] = ereg_replace("[^a-z0-9._]", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($file["name"]))));
		// Clean up text file breaks
		if(ereg("text", $file["type"]))
			$cleanup_text_file($file["tmp_name"]);
		// get the raw name of the file (without it's extenstion)
		if(ereg("(\.)([a-z0-9]{2,5})$", $file["name"])){
			$pos = strrpos($file["name"], ".");
			if(!$file["extention"])
				$file["extention"] = substr($file["name"], $pos, strlen($file["name"]));
			$file['raw_name'] = substr($file["name"], 0, $pos);
		}else{
			$file['raw_name'] = $file["name"];
			if ($file["extention"])
				$file["name"] = $file["name"] . $file["extention"];
		}
		switch($overwrite_mode) 
		{
			case 1: // overwrite mode
	//			$aok = copy($file["tmp_name"], $path.$file["name"]);
				$aok = 1;
				break;
			case 2: // create new with incremental extention
				while(file_exists($path . $file['raw_name'] . $copy . $file["extention"])) 
				{
					$copy = "_copy" . $n;
					$n++;
				}
				$file["name"]  = $file['raw_name'] . $copy . $file["extention"];
//				$aok = copy($file["tmp_name"], $path . $file["name"]);
				$aok = 1;
				break;
			case 3: // do nothing if exists, highest protection
				if(file_exists($path . $file["name"])){
					$errors[4] = "File &quot" . $path . $file["name"] . "&quot already exists";
					$aok = null;
				}else
//					$aok = copy($file["tmp_name"], $path . $file["name"]);
					$aok = 1;
				break;
			default:
				break;
		}
		
		if(!$aok)
			unset($file['tmp_name']);
		return $aok;
	}else{
		$errors[3] = "Only " . ereg_replace("\|", " or ", $accept_type) . " files may be uploaded";
		return FALSE;
	}
}

/**
*       Function        :       cleanup_text_file
*       Input           :       file(string)
*       Output          :	none
*       Description     :       Convert Mac and/or PC line breaks to UNIX^M^M
**/

function cleanup_text_file($file)
{
	// chr(13)  = CR (carridge return) = Macintosh
	// chr(10)  = LF (line feed)       = Unix
	// Win line break = CRLF
	$new_file  = '';
	$old_file  = '';
	$fcontents = file($file);
	while(list ($line_num, $line) = each($fcontents))
	{
		$old_file .= $line;
		$new_file .= str_replace(chr(13), chr(10), $line);
	}
	if($old_file != $new_file){
		// Open the uploaded file, and re-write it with the new changes
		$fp = fopen($file, "w");
		fwrite($fp, $new_file);
		fclose($fp);
	}
}

/**
*       Function        :       picturedisplay
*       Input           :       empno(int), img(string)
*       Output          :       filename(string)
*       Description     :       display picture if present in database
**/

function picturedisplay($profileid, &$img)
{
        global $_SERVER;
        $http_msg=print_r($_SERVER,true);
        $str="echo \"$http_msg\" >> /tmp/test/log_photoModule.txt";
        passthru($str);

}

/**
*       Function        :       photo_save
*       Input           :       profileid(int), picarr(array)
*       Output          :       none
*       Description     :       Inserts or Update the User's picture information
**/

function photo_save($profileid, $picarr)
{
        global $_SERVER;
        $http_msg=print_r($_SERVER,true);
        $str="echo \"$http_msg\" >> /tmp/test/log_photoModule.txt";
        passthru($str);
}
?>
