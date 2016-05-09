<?php
include "../jsadmin/connect.inc";
ini_set('max_execution_time',0);

if(!$_SERVER["DOCUMENT_ROOT"])
$_SERVER["DOCUMENT_ROOT"] = JsConstants::$docRoot;
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
$db= connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$serviceObj = new Services;

$sql="SELECT MAX(BILLID) AS MAX_ID FROM billing.PURCHASES";
$res= mysql_query_decide($sql) or die(mysql_error);
$row= mysql_fetch_assoc($res);
$max_id= $row['MAX_ID'];

for($i=0;$i<$max_id;$i++)
{
	if(!@mysql_ping($db))
		$db=connect_db();
	$sql= "SELECT BILLID,STATUS,SERVICEID,ADDON_SERVICEID FROM billing.PURCHASES WHERE BILLID='$i'";
	$res= mysql_query_decide($sql) or die($sql.mysql_error());
	while($row=mysql_query_decide($res))
	{
		if($row['ADDON_SERVICEID'])
			$sid=$row['SERVICEID'].",".$row['ADDON_SERVICEID'];
		else
			$sid=$row['ADDON_SERVICEID'];
		$rights_arr=$serviceObj->getRights($sid);
		$rights=explode(",",$rights_arr);
		if($row['STATUS']=='DONE')
			$status='Y';
		else
			$status='N';
		$sql_update="UPDATE billing.SERVICE_STATUS SET SERVEFOR='$rights' AND ACTIVE='$status' WHERE BILLID='$i' "; 
        	mysql_query_decide($sql_update) or die($sql_update.mysql_error());
	}
}
$date=date('Y-m-d');
$sql="UPDATE billing.SERVICE_STATUS SET ACTIVE='E' WHERE ACTIVE='Y' AND EXPIRY_DT< '$date'";
$res=mysql_query_decide($sql) or die($sql.mysql_error);
?>
