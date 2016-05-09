<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path."/profile/connect.inc");
$db = connect_db();
$db_slave = connect_737();
$da=authenticated();
if($_GET && $da)
{
	$definedGrpArr =array("SHP","SMP","BMP","SH","SM","BM"); // defined array for discount pages
	$location = $_GET['yes'];	
	$location=stripslashes("\"".$location."\"");
	$sql_1="SELECT ID,GRP FROM MIS.ATS_URL WHERE URL IN ($location) ORDER BY URL ASC";
	$res_1=mysql_query($sql_1,$db_slave) or die(mysql_error1($db_slave));
	while($row=mysql_fetch_array($res_1))
	{
		$loc[]=$row['ID'];
		$grpVal =$row['GRP'];
		if(in_array("$grpVal",$definedGrpArr))
			$grpArr[]=$grpVal;
	}
	if($loc)
		$loc=implode("-",$loc);
	if(count($grpArr)>0)
		$grp =implode("-",$grpArr);

	$ip= FetchClientIP();
	$date=date("Y-m-d G:i:s");
	$username=$da['USERNAME'];
	$profileid=$da['PROFILEID'];
	if($loc){
		$sql="INSERT INTO MIS.ATS (`IP`,`ENTRY_DATE`,`USERNAME`,`VISITED_URL`,`PROFILEID`,`VISITED_SITE`) VALUES ('$ip','$date','$username','$loc','$profileid','$grp')";
		mysql_query($sql,$db) or die(mysql_error1($db));
	}
}

if($da)
{
	$sql_ats="SELECT SQL_CACHE URL FROM MIS.ATS_URL";
	$res_ats=mysql_query($sql_ats,$db_slave) or die(mysql_error1($db_slave));
	while($row_ats=mysql_fetch_array($res_ats))
	{
		 $url=$row_ats['URL'];
	echo	 $url=$url.",";
	}
}

?>
