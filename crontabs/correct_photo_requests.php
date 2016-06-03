<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//CREATED BY PUNEET MAKKAR ON 27 OCT 2005 TO CLEAR THE INCOSISTENCY IN DATABASE TABLE CONTACTS HAVING MULTIPLE ENTRIES FOR 2 PEOPLE

include("connect.inc");

$db2=connect_slave();

$sql="SELECT COUNT(*) AS CNT,PROFILEID,PROFILEID_REQ_BY FROM PHOTO_REQUEST GROUP BY PROFILEID,PROFILEID_REQ_BY HAVING CNT>1";
$res=mysql_query($sql,$db2) or logError($sql,$db2);//die("$sql".mysql_error($db2));

mysql_close($db2);
$db=connect_db();

while($row=mysql_fetch_array($res))
{
	$sql_delete="DELETE FROM PHOTO_REQUEST WHERE PROFILEID='".$row['PROFILEID']."' AND PROFILEID_REQ_BY='" . $row['PROFILEID_REQ_BY'] . "' LIMIT " . ($row['CNT']-1);
	mysql_query($sql_delete,$db) or logError($sql,$db);//die("$sql_delete".mysql_error($db));
}
?>
