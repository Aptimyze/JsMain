<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
include(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
/*
hourly tracking of photo
*/
include_once("connect.inc");
//date_default_timezone_set('UTC');
$dbM=connect_db();

//--------------------------3rd chk------------------------------
$sql="SELECT PICTUREID,ORDERING,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,SearchPicUrl FROM PICTURE_NEW ORDER BY PICTUREID DESC LIMIT 20";
$res=mysql_query($sql,$dbM) or die(mysql_error());
while($row=mysql_fetch_assoc($res))
{
	$pic3='SKIP';
	$pic4='SKIP';
	$pic5='SKIP';
	$picId=PictureFunctions::getCloudOrApplicationCompleteUrl($row["PICTUREID"]);
	$pic1=PictureFunctions::getCloudOrApplicationCompleteUrl($row["MainPicUrl"]);
	$pic2=PictureFunctions::getCloudOrApplicationCompleteUrl($row["Thumbail96Url"]);
	if($row["ORDERING"]==0)
	{
		$pic3=PictureFunctions::getCloudOrApplicationCompleteUrl($row["ProfilePicUrl"]);
		$pic4=PictureFunctions::getCloudOrApplicationCompleteUrl($row["ThumbailUrl"]);
		$pic5=PictureFunctions::getCloudOrApplicationCompleteUrl($row["SearchPicUrl"]);
	}
	if(checkForError($pic1,'M'))
		$mainPicError[]=$picId;
	if(checkForError($pic2))
		$thumbail96Error[]=$picId;
	if(checkForError($pic3))
		$profilePicError[]=$picId;
	if(checkForError($pic4))
		$thumbailError[]=$picId;
	if(checkForError($pic5))
		$searchPicError[]=$picId;
}

if($mainPicError)
{
	$mail_msg.="\n\n MAIN URLS - ";
	$mail_msg.=print_r($mainPicError,true);
}
if($thumbail96Error)
{
	$mail_msg.="\n\n T96 URLS - ";
	$mail_msg.=print_r($thumbail96Error,true);
}

if($profilePicError)
{
	$mail_msg.="\n\n Profile PIC URLS - ";
	$mail_msg.=print_r($profilePicError,true);
}
if($thumbailError)
{
	$mail_msg.="\n\n Thumbnail PIC URLS - ";
	$mail_msg.=print_r($thumbailError,true);
}

if($searchPicError)
{
	$mail_msg.="\n\n Search PIC URLS - ";
	$mail_msg.=print_r($searchPicError,true);
}
if($mail_msg)
	mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","Picture Proper funtioning Report ","$mail_msg");

function checkForError($pic,$picType="")
{
	$noError=0;
	if($pic!='SKIP')
	{
		$noError=1;
		if($pic)
		{
			ini_set('user_agent','JsInternal');	
			header('Content-Type: image/jpeg'); 
			$size= getimagesize($pic);
			if(is_array($size))
			{
				if($size[2]>0) 
					$noError=0;
				if($picType=='M')
					if($size[0]<200 || $size[1]<200)	
						$noError=1;
			}
		}
	}
	return $noError;
}
?>
