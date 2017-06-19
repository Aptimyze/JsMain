<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_common.php");

$db=connect_db();

$today=date("Y-m-d");

$sql="SELECT PROFILEID,NEXT_SERVICE_DATE FROM Assisted_Product.AP_SERVICE_TABLE WHERE SERVICED IN('','S') AND NEXT_SERVICE_DATE='$today'";
$res=mysql_query_decide($sql) or die("Error while selecting profiles  ".$sql."   ".mysql_error($db));
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
		$insertString.="('$row[PROFILEID]','$row[NEXT_SERVICE_DATE]'),";
	if($insertString)
	{
		$insertString=trim($insertString,",");
		$sqlInsert="INSERT INTO Assisted_Product.AP_MISSED_SERVICE_LOG(PROFILEID,MISSED_SERVICE_DATE) VALUES$insertString";
		mysql_query_decide($sqlInsert) or die("Error while inserting into missed service log   ".$sqlDelete."  ".mysql_error($db));
	}
}
$sqlUpdate="UPDATE Assisted_Product.AP_SERVICE_TABLE SET SERVICED='',NEXT_SERVICE_DATE=DATE_ADD(NEXT_SERVICE_DATE,INTERVAL 15 DAY) WHERE NEXT_SERVICE_DATE='$today'";
mysql_query_decide($sqlUpdate) or die("Error while updating service dates   ".$sqlUpdate."   ".mysql_error($db));

$sqlDelete="DELETE FROM Assisted_Product.AP_QUEUE WHERE ASSIGNED_FOR='DIS'";
mysql_query_decide($sqlDelete) or die("Error while deleting entry from queue table   ".$sqlDelete."   ".mysql_error($db));
mail('nikhil.dhiman@jeevansathi.com','ap service cron',date("y-m-d"));
?>
