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
include("updatescore.php");
/**
*       Function        :       upload
*       Input           :       filename(string), accept_type(string), extention(string)
*       Output          :       Boolean
*       Description     :       cheaks for the validity of file to be inserted
**/
function upload($filename='', $accept_type='', $extention='') 
{
//will be called only for horoscope upload and not for photo upload
	global $_FILES;
	global $max_filesize;
	global $file;
	global $errors;
	global $accepted;
	global $max_image_width;
	global $max_image_height;
	$max_filesize=1048576*4;//4 MB
	if (!is_array($_FILES[$filename]) || !$_FILES[$filename]['name']){
			$errors[0] = "No file was uploaded";
		$accepted  = FALSE;
		return FALSE;
	}
	// Copy PHP's global $_FILES array to a local array
	$file = $_FILES[$filename];
	$file['file'] = $filename;
	$fp=fopen($file["tmp_name"],"rb");
	if($fp)
	{
		// test max size
		if($max_filesize && ($file["size"] > $max_filesize)){
			$errors[2] = "Maximum file size exceeded. File may be no larger than " . $max_filesize/1000 . "KB (" . $max_filesize . " bytes).";
			$accepted  = FALSE;
			return FALSE;
		}
		if(ereg("image", $file["type"])){
			/* IMAGES */
			$image = @getimagesize($file["tmp_name"]);
			$file["width"]  = $image[0];
			$file["height"] = $image[1];
			// test max image size
			if(($max_image_width || $max_image_height) && (($file["width"] > $max_image_width) || ($file["height"] > $max_image_height))){
				$errors[3] = "Maximum image size exceeded. Image may be no more than " . $max_image_width . " x " . $max_image_height . " pixels";
				$accepted  = FALSE;
				return FALSE;
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
				$accepted = TRUE;
				else
				{ 
					$accepted = FALSE;
					$errors[4] = "Only " . ereg_replace("\|", " or ", $accept_type) . " files may be uploaded";
				}
			else
			$accepted = TRUE;
	}
	else
	{
		$errors[1]="Error in file being uploaded!";
		$accepted = FALSE;
		return FALSE;	
	}
	return $accepted;
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
function photo_save($profileid, $picarr, $photograde="",$watermark,$val="",$name="",$del="")
{
	global $_SERVER;
	$http_msg=print_r($_SERVER,true);
	$str="echo \"$http_msg\" >> /tmp/test/log_photoModule.txt";
	passthru($str);

}
?>
