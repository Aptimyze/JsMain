<?php
/* updating the values of nakshatra field in jprofile table and new values of nakshatra are taken from temporary_values table*/ 
 include("../profile/connect.inc");
 $db=connect_db();

ini_set("max_execution_time","0");
 // create object

$sql_mapp = "SELECT * FROM newjs.Temporary_values";
$res_mapp = mysql_query_decide($sql_mapp) or die($sql_mapp.mysql_error_js());
$i=0;
while($row_mapp = mysql_fetch_array($res_mapp))
{
	$nak_old[$i] = strtolower($row_mapp['NAKSHTRA_OLD']);
	$nak_new[$i] = $row_mapp['NAKSHTRA_NEW'];
	$i++;
}

$sql_max="SELECT MAX(PROFILEID) from newjs.JPROFILE" ;
$res=mysql_query_decide($sql_max);
$res_fetch=mysql_fetch_row($res);
$maxi=$res_fetch[0];
for($i=1;$i<=$maxi;$i++)
{
	$sql_l="SELECT LOWER(TRIM(NAKSHATRA)) from newjs.JPROFILE where PROFILEID=$i";
	$res=mysql_query_decide($sql_l) or die(mysql_error_js());
	$result=mysql_fetch_row($res);
	$p= $result[0];
	if($position = array_search($p,$nak_old))
	{
		$sql_upd = "UPDATE newjs.JPROFILE jp SET jp.NAKSHATRA='$nak_new[$position]' WHERE PROFILEID='$i'";
		mysql_query_decide($sql_upd) or die($sql_upd.mysql_query_decide());
	}
}

?>
