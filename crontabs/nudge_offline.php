<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");
$db= connect_db();

//$sql= "SELECT DATEDIFF(now(),MATCH_DATE) AS dayleft,ID,MATCH_ID,PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE STATUS IN('N','NNOW') AND DATEDIFF(now(),MATCH_DATE)>=47";
$sql= "SELECT ID,MATCH_ID,PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE STATUS IN('N','NNOW') AND SHOW_ONLINE !='N' AND DATEDIFF(now(),MATCH_DATE)>=47 ";
$res= mysql_query_decide($sql) or die(mysql_error_js());
while($row= mysql_fetch_array($res))
{
	//$dy_lft= $row['dayleft'];
	$id= $row['ID'];
	//if($dy_lft>=15)
	//{
		$sql1= "UPDATE jsadmin.OFFLINE_MATCHES SET SHOW_ONLINE= 'N' WHERE ID= '$id'";
		mysql_query_decide($sql1) or logError("$sql1");
		$sql1="INSERT INTO jsadmin.DELETED_OFFLINE_NUDGE_LOG(ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON) SELECT ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER='$row[PROFILEID]' AND RECEIVER='$row[MATCH_ID]'";
		$res1=mysql_query_decide($sql1) or logError("$sql1");
		$sql1="DELETE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER='$row[PROFILEID]' AND RECEIVER='$row[MATCH_ID]'";
		mysql_query_decide($sql1) or logError("$sql1");
		
	//}
}
mysql_close($db);
?>
