<?php 

$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include_once("connect.inc");
$dbS=connect_slave();

$today=date("Y-m-d");
$ts = time();
$ts-=1*24*60*60;
$ts2=time() - 7*24*60*60;
$start_dt=date("Y-m-d",$ts);
$start_dt1=date("Y-m-d",$ts2);

//picasa,flickr
$sourceArr = array('facebook','computer_noFlash','mobPicsGallery','appPicsCamera','appPicsGallery','iOSPicsGallery');
$frequency800 = array('appPicsGallery','computer_noFlash','mobPicsGallery');
$frequency5 = array('appPicsCamera');

foreach($sourceArr as $k=>$v)
{
	$sql="SELECT COUNT(*) AS CNT FROM MIS.IMPORT_UPLOAD_TRACKING WHERE DATE='$start_dt' AND PHOTO_SOURCE='$v'";
	$res=mysql_query($sql,$dbS) or die(mysql_error());
	$row=mysql_fetch_assoc($res);

	
	if(in_array($v,$frequency800))
	{
		if($row["CNT"]<800)
			$prob[] =  $v."--".$row['CNT']."  ";
	}
	elseif(in_array($v,$frequency5))
	{
		if($row["CNT"]<5)
			$prob[] =  $v."--".$row['CNT']."  ";
	}
	else
	{
		if($row["CNT"]<100)
			$prob[] =  $v."--".$row['CNT']."  ";
	}
}
if($prob)
{
	$prob = implode(",",$prob);
	$problem.="Check Source : ".$prob;
	mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com,esha.jain@jeevansathi.com,eshajain88@gmail.com','problem in upload/import',$problem);
        include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
        $mobile         = "9818424749";
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached picture $date within 5 minutes";
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

        $mobile         = "9953457479";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');

        $mobile         = "9711304800";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
}
