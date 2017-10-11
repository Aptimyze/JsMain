<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

include("$docRoot/crontabs/connect.inc");

//$dbSlave=connect_slave();
//mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);

$dbM=connect_db(); //can be slave as well.
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql="SELECT PROFILEID,MATCH_ID from Assisted_Product.AP_CALL_HISTORY WHERE CALL_STATUS!='C'";
$result=mysql_query($sql,$dbM) or die(mysql_error($dbM).$sql);
while($myrow=mysql_fetch_array($result))
{
	$pid		=$myrow["PROFILEID"];
	$matchid 	=$myrow["MATCH_ID"];

	if($pid && $matchid)
		$str ="$pid".","."$matchid";

	$sql="SELECT PROFILEID,ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID IN ($str)";
	$res=mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
	while($row=mysql_fetch_array($res))
	{
		$activated=$row["ACTIVATED"];
		$profileid=$row['PROFILEID'];
		if($activated=='D')
		{
			$sql1 ="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE (PROFILEID='$profileid' OR MATCH_ID='$profileid') AND CALL_STATUS='N'";
			mysql_query($sql1,$dbM) or mysql_error1(mysql_error($dbM).$sql1);
			$sql2 ="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE (PROFILEID='$profileid' OR MATCH_ID='$profileid') AND CALL_STATUS='Y'";
			mysql_query($sql2,$dbM) or mysql_error1(mysql_error($dbM).$sql2);
		}
	}
}

?>	

