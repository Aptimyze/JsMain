<?php 

/*
Script to send notification to photo developers abt improper functioning of photos storage
1. Check if ordering are continious
*/
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($docRoot."/crontabs/connect.inc");
//date_default_timezone_set('UTC');

$dbS=connect_slave();

$end_dt=date("Y-m-d");

$today=date("Y-m-d");
$ts = time();
$ts-=1*24*60*60;
$start_dt=date("Y-m-d",$ts);

$ts = time();
$ts-=5*24*60*60; // changed 3 to 5 days
$threeDaysOld=date("Y-m-d",$ts);


//--------------------------1st chk------------------------------
$sql="SELECT distinct(PROFILEID) AS PID FROM PICTURE_NEW WHERE UPDATED_TIMESTAMP BETWEEN '$start_dt' AND '$end_dt'";
//$sql="SELECT distinct(PROFILEID) AS PID FROM PICTURE_NEW WHERE 1";//TEMP
$res=mysql_query($sql,$dbS) or die(mysql_error());
$dbM=connect_db();
while($row=mysql_fetch_assoc($res))
{
	$pid=$row["PID"];
	unset($order);
	$sql1="SELECT ORDERING FROM PICTURE_NEW WHERE PROFILEID='$pid' ORDER BY ORDERING ASC";
	$res1=mysql_query($sql1,$dbM) or die(mysql_error());
	while($row1=mysql_fetch_assoc($res1))
	{
		$order[]=$row1["ORDERING"];
	}
	for($i=0;$i<count($order)-1;$i++)
	{
		if($order[$i+1]-$order[$i]>1)
			$PICTURE_NEW_ORDER_ERROR[]=$pid;
	}
}
//--------------------------1st chk------------------------------


//--------------------------2nd chk------------------------------
$dbM=connect_db();
$sql="SELECT distinct(PROFILEID) as PID FROM PICTURE_FOR_SCREEN_NEW WHERE UPDATED_TIMESTAMP<'$threeDaysOld' AND  UPDATED_TIMESTAMP > '2017-01-01 00:00:00'";
$res=mysql_query($sql,$dbM) or die(mysql_error());
while($row=mysql_fetch_assoc($res))
{
	$pid=$row["PID"];
	$PICTURE_NOT_SCREENED_IN_3_DAYS[]=$pid;
}

if(is_array($PICTURE_NOT_SCREENED_IN_3_DAYS))
{
	$sql = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN (".implode(",",$PICTURE_NOT_SCREENED_IN_3_DAYS).") AND ACTIVATED!='D'";
	$res=mysql_query($sql,$dbS) or die(mysql_error());

	while($row=mysql_fetch_assoc($res))
	{
		$USERNAME_NOT_SCREENED_IN_3_DAYS[] = $row["USERNAME"];
	}
}
//--------------------------2nd chk------------------------------

//Check added by Reshu  for Image from Mail screened 

$sql="SELECT distinct(jsadmin.PHOTOS_FROM_MAIL.ID) FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON jsadmin.PHOTOS_FROM_MAIL.ID = jsadmin.SCREEN_PHOTOS_FROM_MAIL.MAILID WHERE (SCREEN_PHOTOS_FROM_MAIL.MAILID IS NULL) AND ATTACHMENT='Y' AND DATE <'$threeDaysOld'";

$res=mysql_query($sql,$dbM) or die(mysql_error());
while($row=mysql_fetch_assoc($res))
{
        $mid=$row["ID"];
        $MAIL_PICTURE_NOT_SCREENED_IN_3_DAYS[]=$mid;
}
if(is_array($USERNAME_NOT_SCREENED_IN_3_DAYS))
        $notScreenedMsg=" Non Screened pictures :".implode(",",$USERNAME_NOT_SCREENED_IN_3_DAYS);

if(is_array($MAIL_PICTURE_NOT_SCREENED_IN_3_DAYS))
	$notScreenedMsg=$notScreenedMsg." Images from mail not screened :".implode(",",$MAIL_PICTURE_NOT_SCREENED_IN_3_DAYS);

if(isset($notScreenedMsg))
{  
	mail("reshu.rajput@jeevansathi.com,photos@jeevansathi.com,sandeep@naukri.com,amuda.ruby@jeevansathi.com","Pictures Not Screened in 5 Days ","$notScreenedMsg","Reply-To: sandeep@naukri.com,anu@jeevansathi.com,anant.gupta@naukri.com");
}

// Check for Image from mail ended

//--------------------------3rd chk------------------------------
$sql="SELECT PICTUREID,ORDERING,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,SearchPicUrl FROM PICTURE_NEW WHERE UPDATED_TIMESTAMP BETWEEN '$start_dt' AND '$end_dt'";
//echo $sql="SELECT PICTUREID,ORDERING,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,SearchPicUrl FROM PICTURE_NEW LIMIT 5";//TEMP
$res=mysql_query($sql,$dbS) or die(mysql_error());
while($row=mysql_fetch_assoc($res))
{
	$pic3='SKIP';
	$pic4='SKIP';
	$pic5='SKIP';
	$picId=$row["PICTUREID"];
	$pic1=$row["MainPicUrl"];
	$pic2=$row["Thumbail96Url"];
	if($row["ORDERING"]==0)
	{
		$pic3=$row["ProfilePicUrl"];
		$pic4=$row["ThumbailUrl"];
		$pic5=$row["SearchPicUrl"];
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

$mail_msg="ORDERING PROBLEM - ";
if($PICTURE_NEW_ORDER_ERROR)
	$mail_msg.=print_r($PICTURE_NEW_ORDER_ERROR,true);

$mail_msg.="\n\n  PICTURES NOT GETTING SCREENED PROBLEM - ";
if($PICTURE_NOT_SCREENED_IN_3_DAYS)
	$mail_msg.=print_r($PICTURE_NOT_SCREENED_IN_3_DAYS,true);

$mail_msg.="\n\n MAIN URLS - ";
if($mainPicError)
	$mail_msg.=print_r($mainPicError,true);

$mail_msg.="\n\n T96 URLS - ";
if($thumbail96Error)
	$mail_msg.=print_r($thumbail96Error,true);

$mail_msg.="\n\n Profile PIC URLS - ";
if($profilePicError)
	$mail_msg.=print_r($profilePicError,true);

$mail_msg.="\n\n Thumbnail PIC URLS - ";
if($thumbailError)
	$mail_msg.=print_r($thumbailError,true);

$mail_msg.="\n\n Search PIC URLS - ";
if($searchPicError)
	$mail_msg.=print_r($searchPicError,true);
//echo $mail_msg;
// mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","Picture Proper funtioning Report ","$mail_msg");
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
			PictureFunctions::getCloudOrApplicationCompleteUrl($pic); 
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
