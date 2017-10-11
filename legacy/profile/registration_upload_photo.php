<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

$path = $_SERVER['DOCUMENT_ROOT'];
	
/**
*       Included        :       connect.inc
*       Description     :       contains database connect functions and other common functions
**/
include_once($path."/profile/connect.inc");
$db = connect_db();

/**
*       Included        :       uploadphoto_inc.php
*       Description     :       contains all functions related to photo save and update
**/
include_once($path.'/profile/uploadphoto_inc.php');

/**
*       Included        :       flag.php
*       Description     :       contains all functions related to photo screening status flag
**/
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
/**
*       Included        :       functions.inc
*       Description     :       contain function to calculate profile percentage
**/
include_once($path.'/profile/functions.inc');

include_once("registration_functions.inc");
$xml = new DomDocument;
$proc = new xsltprocessor;
$IMG_URL = $SITE_URL."/profile/images/registration_new";
$proc->setParameter("","IMG_URL",$IMG_URL);

global $max_filesize;
global $file;

if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
	$proc->setParameter("","class","hand");
else
	$proc->setParameter("","class","pointer");	

$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;
$errorMsg = "Due to a temporary problem your request could not be processed. Please try after a couple of minutes";
$data=authenticated($checksum);
if($data["BUREAU"]==1 && ($mbureau=="bureau" || $_COOKIE['JSMBLOGIN']))
{
        $fromprofilepage=1;
        mysql_select_db_js('marriage_bureau');
        include('../marriage_bureau/connectmb.inc');
        $mbdata = authenticatedmb($mbchecksum);
        if(!$mbdata)timeoutmb();
        $smarty->assign('mbchecksum',$mbdata["CHECKSUM"]);
        $smarty->assign('source',$mbdata["SOURCE"]);
        mysql_select_db_js('newjs');
        $mbureau="bureau1";
}

if($data)
{
	loadMyXml($path."/profile/registration_upload_photo_eng.xml");
	$profileid=$data["PROFILEID"];

	if($submit_photo)// After the form is submitted
	{ 
		$proc->setParameter("","CHECKSUM",$checksum);
		$proc->setParameter("","PROFILEID", $profileid);
		$proc->setParameter("","WHICH_PHOTO",$which_photo);

		$filename = $which_photo;

		$max_filesize = 1048576 * 2; //2MB
		$flag_error = 0;
		$flag_upload = 0;

		$proc->setParameter("","CHECKSUM",$data["CHECKSUM"]);

		if($mbureau=="bureau1")
		{
			$proc->setParameter("","MB_USERNAME_PROFILE",$data["USERNAME"]);
			$proc->setParameter("","CHECKSUM",$data["CHECKSUM"]);
		}
		
		//upload main photo
		if(upload($filename, $acceptable_file_types, $default_extension)==0)
		{
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$photo_content_name = $which_photo."_content";
			$$photo_content_name = addslashes($fcontent);
			//$main_photo_content = addslashes($fcontent);
			$flag_upload = 1;
		}
		elseif($filename)
		{
			$flag_error = 1;
			$flag_upload=0;
		}

		if($flag_error)//if any of the photos could not be uploaded to temp location
		{
			createXmlTag("uploadPhoto","submitted","uploadError","1");
		}
		elseif($flag_upload)
		{	
			//successful upload of photos to a temporary location
			$picarr = array("main_photo"=>$mainphoto_content, "album_photo1"=>$albumphoto1_content, "album_photo2"=>$albumphoto2_content);				
			photo_save($profileid, $picarr);//Save the photos in the database
			createXmlTag("uploadPhoto","submitted","uploadSuccessful","1");
		}
		else
		{
			createXmlTag("uploadPhoto","submitted","uploadError","1");
		}
	}
	else
	{
		$proc->setParameter("","CHECKSUM",$checksum);
		$proc->setParameter("","PROFILEID", $profileid);
		$proc->setParameter("","WHICH_PHOTO",$which_photo);
	}
	$xml->saveXML();

	$xsl = new DomDocument;
        $file_string = file_get_contents($path."/profile/registration_upload_photo.xsl");
        $file_string = str_replace("\n","",$file_string);
        $file_string = str_replace("\t","",$file_string);
        $xsl->loadXML("$file_string");

        $proc->importStyleSheet($xsl);
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
        echo $proc->transformToXML($xml);
}
else 
{
	TimedOut();	
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
