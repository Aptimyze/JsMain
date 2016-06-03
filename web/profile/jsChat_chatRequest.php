<?php
require_once("connect.inc");
$senderId=$_GET[senderId];
$receiverId=$_GET[receiverId];
$type=$_GET[type];
//echo "senderid is >>>>".$senderId;
//echo "receiverId is >>>>".$receiverId;
$db=connect_db();

/*if(!$profileId){
	$data=authenticated($checksum);
	$profileId=$data["PROFILEID"];
}*/
//echo "profileId is >>>".$profileId;
//$sql="DELETE FROM userplane.users WHERE userID='$profileId'";
//mysql_query_decide($sql);
$date=time();
$date_format=date("Y-m-d H:i:s",$date);
if($type == "chat_request"){

$insert_sql="insert into userplane.CHAT_REQUESTS (`SENDER`,`RECEIVER`,`TIMEOFINSERTION`) values('$senderId','$receiverId','$date_format')";
mysql_query_decide($insert_sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$insert_sql,"ShowErrTemplate");
}else if($type == "log_ad"){

$insert_sql="insert into userplane.LOG_AD (`SENDER`,`RECEIVER`,`STATUS`,`TIMEOFINSERTION`) values('$senderId','$receiverId','$status','$date_format')";
mysql_query_decide($insert_sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$insert_sql,"ShowErrTemplate");

$date_format1=date("Y-m-d",$date);
//echo "2nd date format is >>>>".$date_format1;
$insert_sql="insert into userplane.USERS_AD (`PROFILEID`,`DAYZ`) values('$receiverId','$date_format1')";
mysql_query_decide($insert_sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$insert_sql,"ShowErrTemplate");

}
//echo "done";
?>
