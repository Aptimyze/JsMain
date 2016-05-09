<?php

include_once "connect.inc";
//$data=authenticated($checksum);
//if($data["PROFILEID"]==144111)
{
$db=connect_slave81();
mysql_select_db("matchalerts",$db) or die(mysql_error());

$localtime = localtime();
$today=$localtime[2].":".$localtime[1].":".$localtime[0];

$sql="SELECT ((SELECT COUNT(*) FROM JPARTNER)-(SELECT SUM(RESULTS0 + RESULTS1 + RESULTS2 + RESULTS3 + RESULTS4 + RESULTS5 + RESULTS6 + RESULTS7 + RESULTS8 + RESULTS9 + RESULTS10) FROM TEMP_RESULT_COUNT)) AS LAVESH";
$result=mysql_query($sql) or die(mysql_error().$sql);
$myrow=mysql_fetch_row($result);
$n_count=$myrow[0];

if($lavesh)
{
	$sql=" SELECT COUNT(*),(RECEIVER%8) AS A FROM MAILER GROUP BY A";
	$result=mysql_query($sql) or die(mysql_error().$sql);
	while($myrow=mysql_fetch_row($result))
	{
		$t2=$myrow[0];
		$t1=$myrow[1];
		$arr[$t1]=$t2;
	}
}
$Data = $today."--".$n_count."\n";
echo $Data;
if($lavesh)
	print_r($arr);
}
?>

