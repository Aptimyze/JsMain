<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


$db=mysql_connect("10.240.14.48","user","CLDLRTa9") or die("no conn".mysql_error());
mysql_select_db("newjs",$db);

$sql="SELECT MAX(ID) AS ID FROM MESSAGE_LOG";
$res=mysql_query($sql) or die("$sql".mysql_error());
$row=mysql_fetch_array($res);
$maxid=$row['ID'];

for($i=1;$i<=$maxid;$i++)
{
	$sql="SELECT ID,SENDER, RECEIVER FROM MESSAGE_LOG WHERE ID='$i'";
	$res=mysql_query($sql) or die("$sql".mysql_error());
	$row=mysql_fetch_array($res);
	mysql_free_result($res);

	$sql="SELECT COUNT(*) AS CNT FROM CONTACTS WHERE SENDER='".$row['SENDER']."' AND RECEIVER='".$row['RECEIVER']."'";
	$res=mysql_query($sql) or die("$sql".mysql_error());
	$row1=mysql_fetch_array($res);
	if($row1['CNT']==0)
	{
		mysql_free_result($res);
		$sql="SELECT COUNT(*) AS CNT FROM CONTACTS WHERE RECEIVER='".$row['SENDER']."' AND SENDER='".$row['RECEIVER']."'";
		$res=mysql_query($sql) or die("$sql".mysql_error());
		$row1=mysql_fetch_array($res);
		if($row1['CNT']==0)
		{
			$sql="INSERT INTO MESSAGE_LOG_EXPIRE SELECT * FROM MESSAGE_LOG WHERE ID='$row[ID]'";
			mysql_query($sql) or die("$sql".mysql_error());

			$sql="DELETE FROM MESSAGE_LOG WHERE ID='$row[ID]'";
			mysql_query($sql) or die("$sql".mysql_error());
		}
		mysql_free_result($res);
	}
}
?>
