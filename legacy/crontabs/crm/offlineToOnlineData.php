<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
chdir(dirname(__FILE__));
include ("$docRoot/crontabs/connect.inc");
$db=connect_db();
$db_slave=connect_737();

$check_day=date(time()-7*86400);

unset($val_array);
unset($pid_array);
unset($fin_array);

//findng cities which fall under Maharashtra state.
$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'MH%'";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
	$val_array[]=$row["VALUE"];

$val_str=@implode("','",$val_array);
if($val_str!='')
{
	$sqlA="(SELECT PROFILEID FROM newjs.JPROFILE WHERE MTONGUE IN ('20','34') AND ACTIVATED!='D' AND CRM_TEAM='offline' AND ENTRY_DT>='$check_day') UNION (SELECT PROFILEID FROM newjs.JPROFILE WHERE CITY_RES IN ('$val_str') AND ACTIVATED!='D' AND CRM_TEAM='offline' AND ENTRY_DT>='$check_day')";
	$resA=mysql_query($sqlA,$db_slave) or die(mysql_error());
	while($rowA=mysql_fetch_array($resA))
	{
        	$pid=$rowA["PROFILEID"];
	        if(!in_array($pid,$pid_array))
        	        $pid_array[]=$pid;
	}
}

$pid_str=@implode(",",$pid_array);
if($pid_str!='')
{
	$sqlB="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID IN ($pid_str)";
	$resB=mysql_query($sqlB,$db_slave) or die(mysql_error());
	while($rowB=mysql_fetch_array($resB))
		$fin_array[]=$rowB["PROFILEID"];
}

for($i=0;$i<count($pid_array);$i++)
{
	$pid1=$pid_array[$i];
	if(!in_array($pid1,$fin_array))
	{
		$sql1="UPDATE newjs.JPROFILE SET CRM_TEAM='online' WHERE PROFILEID='$pid1'";
		mysql_query($sql1,$db) or die(mysql_error());
	}
}
?>
