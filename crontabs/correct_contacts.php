<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//CREATED BY PUNEET MAKKAR ON 27 OCT 2005 TO CLEAR THE INCOSISTENCY IN DATABASE TABLE CONTACTS HAVING MULTIPLE ENTRIES FOR 2 PEOPLE

include("connect.inc");

$db2=connect_slave();

$sql="SELECT SENDER,RECEIVER FROM CONTACTS GROUP BY SENDER,RECEIVER HAVING COUNT(*)>1";
$res=mysql_query($sql,$db2) or logError($sql,$db2);//die("$sql".mysql_error($db2));

mysql_close($db2);
$db=connect_db();

while($row=mysql_fetch_array($res))
{
	$sql="SELECT * FROM CONTACTS WHERE SENDER='".$row['SENDER']."' AND RECEIVER='".$row['RECEIVER']."' ORDER BY TIME desc" ;
	$res2=mysql_query($sql,$db) or logError($sql,$db);//die("$sql".mysql_error($db));
	$i=0;
	while($row2=mysql_fetch_array($res2))
	{
		if($i>0)
		{
			$sql_insert="INSERT into DUP_CONTACTS(CONTACTID,SENDER,RECEIVER,TYPE,TIME,COUNT,MSG_DEL) values ('".$row2['CONTACTID']."','".$row2['SENDER']."','".$row2['RECEIVER']."','".$row2['TYPE']."','".$row2['TIME']."','".$row2['COUNT']."','".$row2['MSG_DEL']."')";
			mysql_query($sql_insert,$db) or logError($sql,$db);//die("$sql_insert".mysql_error($db));

			$sql_delete="DELETE FROM CONTACTS WHERE CONTACTID='".$row2['CONTACTID']."'";
			mysql_query($sql_delete,$db) or logError($sql,$db);//die("$sql_delete".mysql_error($db));
		}
		$i++;
	}
}
?>
