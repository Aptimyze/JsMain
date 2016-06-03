<?php

ini_set("max_execution_time","0");
                                                                                                                             
/************************************************************************************************************************
*    FILENAME           : skipped_caste_mtongue.php 
*    INCLUDED           : connect.inc,contact.inc,payment_array.php
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();
                                                                                                                             
$sql="SELECT MTONGUE,CITY_RES,TOTAL_POINTS,SUBSCRIPTION,E_RISHTA FROM newjs.SEARCH_FEMALE WHERE TOTAL_POINTS>49";
$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_array($res))
{
	$mtongue=$row['MTONGUE'];
	$city=$row['CITY_RES'];

	$sql1="SELECT MAPPING FROM newjs.SCORE_MTON_CITY_MAP WHERE CITY='$city' AND COMMUNITY='$mtongue'";
	$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
	if($row1=mysql_fetch_array($res1))
	{
	}
	else
	{
		$sqlU="UPDATE MIS.SKIIPPED_CITY_MTONGUE set COUNT=COUNT+1 WHERE CITY='$city' AND MTONGUE='$mtongue'";
		mysql_query_decide($sqlU,$db2) or die("$sql1".mysql_error_js($db2));
		if(mysql_affected_rows_js()==0)
		{
			$sqli="INSERT INTO MIS.SKIIPPED_CITY_MTONGUE VALUES ('','$city','$mtongue','1')";
			mysql_query_decide($sqli,$db2);
		}
	}
}

$sql="SELECT MTONGUE,CITY_RES,TOTAL_POINTS,SUBSCRIPTION,E_RISHTA FROM newjs.SEARCH_MALE WHERE TOTAL_POINTS>49";
$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_array($res))
{
	$mtongue=$row['MTONGUE'];
	$city=$row['CITY_RES'];

	$sql1="SELECT MAPPING FROM newjs.SCORE_MTON_CITY_MAP WHERE CITY='$city' AND COMMUNITY='$mtongue'";
	$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
	if($row1=mysql_fetch_array($res1))
	{
	}
	else
	{
		$final_array[$score][$subs][4]+=1;
		$sqlU="UPDATE MIS.SKIIPPED_CITY_MTONGUE set COUNT=COUNT+1 WHERE CITY='$city' AND MTONGUE='$mtongue'";
		mysql_query_decide($sqlU,$db2) or die("$sql1".mysql_error_js($db2));
		if(mysql_affected_rows_js()==0)
		{
			$sqli="INSERT INTO MIS.SKIIPPED_CITY_MTONGUE VALUES ('','$city','$mtongue','1')";
			mysql_query_decide($sqli,$db2);
		}
	}
}

?>
