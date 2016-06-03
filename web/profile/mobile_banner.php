<?php

include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once("connect.inc");
$db=connect_db();

$mobile = $_GET["mobile"];
$resHandle ="";
//$resHandle = file_get_contents("http://m.mobisoc.com/link?app=JS&phonenum=$mobile&opt=2&source=jeevansathi","r");
$result = fopen("http://d.centralappserver.com/app/Share?source_type=shortcode&source=jshome&option=2&data=R2c3M77UMD5LBgIE&phonenum=$mobile","r");
//$result = fopen("http://m.mobisoc.com/link?app=JS&phonenum=$mobile&opt=2&source=jeevansathi","r");
$resHandle = fgets($result);
$resHandle = trim($resHandle);
if($resHandle =='' )
	echo "FETCH_ERR";
else{
	$message = "Please go to link " .$resHandle. " to download the JS application and be on top of your partner search - whenever, wherever";
	$profileid = "1";
	$table="";
	$smsState = send_sms($message,'',$mobile,$profileid,$table,'Y');
	if($smsState =='1'){
		echo "SUCCESS";
		log_mobisocReport($mobile,$profileid);
	}
	else
		echo "SMS_ERR";		
}

function log_mobisocReport($mobileNo,$profileid)
{
        $date = date("Y-m-d");
        $sql="INSERT INTO MOBISOC_LOG_REPORT(`MOBILE`,`PROFILEID`,`DATE`) VALUES ('$mobileNo','$profileid','$date')";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}

?>
