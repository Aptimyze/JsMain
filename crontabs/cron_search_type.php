<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/************************************************************************************************************************
*    FILENAME           : cron_search_type.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : Store searchtype + results for analysing purpose.
*    CREATED BY         : lavesh
***********************************************************************************************************************/


ini_set("max_execution_time","0");
include("connect.inc");
                                                                                                                             
$dbS = connect_slave();
$today=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
$sql="SELECT PROFILEID,SEARCH_TYPE,RECORDCOUNT FROM newjs.SEARCHQUERY WHERE DATE BETWEEN '$today 00:00:00' AND '$today 23:59:59'";
$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
while($row=mysql_fetch_array($res))
{
	$pid=$row["PROFILEID"];
	$stype=$row["SEARCH_TYPE"];
	$rcount=$row["RECORDCOUNT"];

	if(!$stype)
		$stype='U';//unknown entry

	if($rcount>100)
		$type=1;
	elseif($rcount>50)
		$type=2;
	elseif($rcount)
		$type=3;
	else
		$type=4;

	$myarray[$stype][$type]++;		

	if($pid)
		$loggedin[$stype]++;	
	else
		$loggedout[$stype]++;	
}

$keys1=array_keys($myarray);
$keys2=array('1','2','3','4');

$dbM = connect_db();
for($j=0;$j<count($keys1);$j++)
{
	$k1=$keys1[$j];
	$logged_in=$loggedin[$k1];
	$logged_out=$loggedout[$k1];

	for($k=1;$k<5;$k++)
	{
		$result[$k]=$myarray[$k1][$k];		
		if(!$result[$k])
			$result[$k]=0;
	}

	$sql="INSERT INTO MIS.SEARCH_TYPES VALUES('$today','$k1','$logged_in','$logged_out','$result[1]','$result[2]','$result[3]','$result[4]')";
	mysql_query($sql,$dbM) or die(mysql_error($dbM).$sql);
}
?>
