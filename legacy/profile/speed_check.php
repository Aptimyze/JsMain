<?php
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);


//ini_set('max_execution_time','0');
include_once("connect.inc");
$db=connect_db4();

$sen=rand(1,10)*4000;


$sql="CREATE TEMPORARY TABLE SEN1(SEN mediumint(11))";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

$sql="CREATE TEMPORARY TABLE REC1(REC mediumint(11) , INDEX (REC) )";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

for($i=$sen;$i<$sen+4000;$i++)
{
	$sen_str.="('".$i."'),";
	$j=$i+9000;
	$rec_str.="('".$j."'),";
}

$sen_str=rtrim($sen_str,',');
$sql="INSERT INTO SEN1 VALUES $sen_str";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

$rec_str=rtrim($rec_str,',');
$sql="INSERT INTO REC1 VALUES $rec_str";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);


$sql="CREATE TEMPORARY TABLE COMMON_HISTORY(DISPLAY_PID mediumint(11) unsigned, CNT smallint(2) unsigned , INDEX (DISPLAY_PID) )";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

disable_keys("COMMON_HISTORY",$db);

if($check)
{
	$sql= "SELECT SQL_NO_CACHE COUNT(*) AS CNT, SENDER FROM newjs.CONTACTS_SEARCH_NEW JOIN SEN1 ON SEN = SENDER JOIN REC1 ON REC = RECEIVER GROUP BY SENDER";
	$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
}
else
{
	$sql="INSERT INTO newjs.COMMON_HISTORY(CNT,DISPLAY_PID)(SELECT SQL_NO_CACHE COUNT(*) AS CNT, SENDER FROM newjs.CONTACTS_SEARCH_NEW JOIN SEN1 ON SEN = SENDER JOIN REC1 ON REC = RECEIVER GROUP BY SENDER ORDER BY NULL)";
	$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
}

function disable_keys($tablename,$db)
{
	$sql="alter table $tablename disable keys";
	mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
}

