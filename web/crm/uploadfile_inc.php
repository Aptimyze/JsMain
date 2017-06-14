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
*       Included        :       flag.php
*       Description     :       contains functions for setting and retrieving flags for 
								managing photos
**/
include(JsConstants::$docRoot."/commonFiles/flag.php");

/**
*       Function        :       upload
*       Input           :       filename(string), accept_type(string), extention(string)
*       Output          :       Boolean
*       Description     :       cheaks for the validity of file to be inserted
**/
function upload($filename='', $accept_type='', $extention='') 
{
	global $_FILES;
	global $file;
	global $errors;
	global $accepted;

	if (!is_array($_FILES[$filename]) || !$_FILES[$filename]['name']){
			$errors[0] = "No file was uploaded";
		$accepted  = FALSE;
		return FALSE;
	}
	// Copy PHP's global $_FILES array to a local array
	$file = $_FILES[$filename];
	$file['file'] = $filename;
//print_r($file);

	if(!preg_match("/(\.)([a-z0-9]{3,5})$/", $file["name"]) && !$extention)
	{
		switch($file["type"]) 
		{
			case "text/plain":
				$file["extention"] = ".txt"; break;
			case "text/rtf":
				$file["extention"] = ".rtf"; break;
			case "text/doc":
			     	$file["extention"] = ".doc"; break;
			case "text/csv":
				$file["extention"] = ".csv"; break;
			
			default:
				break;
		}
	}
 	else
	{
		$file["extention"] = $extention;
	}
	if($accept_type)
	{
		if(preg_match(strtolower($accept_type), strtolower($file["type"])))
		{
			$accepted = TRUE;
		}
		else{
			$accepted = FALSE;
			$errors[3] = "Only " . preg_replace("\|", " or ", $accept_type) . " files may be uploaded";
print_r($errors);
		}
	}
	else
	{
		$accepted = TRUE;
	}
	return $accepted;
}

/**
*       Function        :       save_file
*       Input           :       path(string), overwrite_mode(int)
*       Output          :       Boolean
*       Description     :       Cleans up the filename, copies the file from PHP's temp location to $path, and checks the overwrite_mode
**/

function save_file($path,$fname,$overwrite_mode="3")
{
	global $file;
	global $errors;
	global $accepted;
	$path = $path;	
	if($accepted){
		// Clean up file name (only lowercase letters, numbers and underscores)
		$file["name"] = preg_replace("[^a-z0-9._]", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($file["name"]))));
		// Clean up text file breaks
		if(preg_match("text", $file["type"]))
			cleanup_text_file($file["tmp_name"]);
		// get the raw name of the file (without it's extenstion)
		if(preg_match("(\.)([a-z0-9]{2,5})$", $file["name"])){
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
				$fold = $file["name"];
				$file["name"] = $fname.$file["extention"];
				$aok = copy($file["tmp_name"], $path.$file["name"]);
//				$aok = 1;
				break;
			case 2: // create new with incremental extention
				while(file_exists($path . $file['raw_name'] . $copy . $file["extention"])) 
				{
					$copy = "_copy" . $n;
					$n++;
				}
				$file["name"]  = $file['raw_name'] . $copy . $file["extention"];
				$aok = copy($file["tmp_name"], $path . $file["name"]);
//				$aok = 1;
				break;
			case 3: // do nothing if exists, highest protection
				if(file_exists($path . $file["name"])){
					$errors[4] = "File &quot" . $path . $file["name"] . "&quot already exists";
					$aok = null;
				}else
					$aok = copy($file["tmp_name"], $path . $file["name"]);
//					$aok = 1;
				break;
			default:
				break;
		}
		
		if(!$aok)
			unset($file['tmp_name']);
		return $aok;
	}else{
		$errors[3] = "Only " . preg_replace("\|", " or ", $accept_type) . " files may be uploaded";
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

function insert_claim($maillist,$mailto)
{       
	$msg="";
	for($i=0;$i<count($maillist);$i++)
	{
		list($operator[],$userid[],$start[],$duration[])=explode("','",$maillist[$i]);
	}
	for($i=0;$i<count($operator);$i++)
	{
		if(preg_match('/\@/i',$userid[$i]))
		{
			$useremail_arr[]=$userid[$i];
		}
		else
		{
			$username_arr[]=$userid[$i];
		}
		$check_arr[$userid[$i]]["userid"]=$userid[$i];
		$check_arr[$userid[$i]]["operator"]=strtolower($operator[$i]);
		$check_arr[$userid[$i]]["start"]=$start[$i];
		$check_arr[$userid[$i]]["duration"]=$duration[$i];
	}
	if(count($useremail_arr)>0)
		$useremail_str=implode("','",$useremail_arr);
	if(count($username_arr)>0)
		$username_str=implode("','",$username_arr);

//$msg.=$myrow['USERNAME']."&nbsp;&nbsp;&nbsp;&nbsp;:-->valid";
	
	if(count($username_arr)>0)
	{
		$sql="SELECT PROFILEID,USERNAME,PHONE_RES,PHONE_MOB,EMAIL from newjs.JPROFILE where USERNAME in ('$username_str')";
		$result=mysql_query_decide($sql);
		$i=0;
		if(mysql_num_rows($result)>0)
		{
			while($myrow=mysql_fetch_array($result))
			{
				$user_name[$i]["proid"]=$myrow['PROFILEID'];
				$user_name[$i]["username"]=$myrow['USERNAME'];
				$user_name[$i]["phone_res"]=$myrow['PHONE_RES'];
				$user_name[$i]["phone_mob"]=$myrow['PHONE_MOB'];
				$user_name[$i]["email"]=$myrow['EMAIL'];
				$userid_valid[]=$myrow['USERNAME'];
				$i++;
			}
		}
	}
	if(count($useremail_arr)>0)
	{
		$i=0;
		$sql="SELECT PROFILEID,USERNAME,PHONE_RES,PHONE_MOB,EMAIL from newjs.JPROFILE where EMAIL in ('$useremail_str')";
		$result=mysql_query_decide($sql);
		if(mysql_num_rows($result)>0)
		{
			while($myrow=mysql_fetch_array($result))
			{
				$user_email[$i]["proid"]=$myrow['PROFILEID'];
				$user_email[$i]["username"]=$myrow['USERNAME'];
				$user_email[$i]["phone_res"]=$myrow['PHONE_RES'];
				$user_email[$i]["phone_mob"]=$myrow['PHONE_MOB'];
				$user_email[$i]["email"]=$myrow['EMAIL'];
				$userid_valid[]=$myrow['EMAIL'];
				$i++;
			}
		}
	}
//	print_r($user);
	for($i=0;$i<count($user_name);$i++)
	{
		$sql="INSERT into incentive.CLAIM (PROFILEID,USERNAME,CONVINCE_TIME,ENTRY_TIME,ENTRYBY,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) values ('".$user_name[$i]["proid"]."','".addslashes($user_name[$i]["username"])."','".$check_arr[$user_name[$i]["username"]]["start"]."',now(),'".$check_arr[$user_name[$i]["username"]]["operator"]."','C','".$user_name[$i]["phone_res"]."','".$user_name[$i]["phone_mob"]."','".$user_name[$i]["email"]."','Y')";
		mysql_query_decide($sql);
	}	
	for($i=0;$i<count($user_email);$i++)
	{
		$sql="INSERT into incentive.CLAIM (PROFILEID,USERNAME,CONVINCE_TIME,ENTRY_TIME,ENTRYBY,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) values ('".$user_email[$i]["proid"]."','".addslashes($user_email[$i]["username"])."','".$check_arr[$user_email[$i]["email"]]["start"]."',now(),'".$check_arr[$user_email[$i]["email"]]["operator"]."','C','".$user_email[$i]["phone_res"]."','".$user_email[$i]["phone_mob"]."','".$user_email[$i]["email"]."','Y')";
		mysql_query_decide($sql);
	}
	
	//for sending mail
	$userid_invalid=array_values(array_diff($userid,$userid_valid));
	for($i=0;$i<count($userid_valid);$i++)
	{
		$msg.=$userid_valid[$i]."&nbsp;&nbsp;&nbsp;&nbsp;:-->valid<br>\n";
	}
	for($i=0;$i<count($userid_invalid);$i++)
	{
		$msg.=$userid_invalid[$i]."&nbsp;&nbsp;&nbsp;&nbsp;:-->invalid<br>\n";
	}
	send_email($mailto,$msg,"Chat File uploaded");
		
}
?>
