<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
connect_db();

$sql="select PROFILEID from SEARCH_MALE where LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
//$sql="select PROFILEID from SEARCH_MALE where SORT_DT < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
while($myrow=mysql_fetch_array($result))
{
        $deletePidArr[]=$myrow["PROFILEID"];
}
if(is_array($deletePidArr))
{
        $deletePidArrStr=implode(",",$deletePidArr);
        $sql = "delete from SEARCH_MALE where PROFILEID IN ($deletePidArrStr)";
        mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);

        $sql = "delete from SEARCH_MALE_TEXT where PROFILEID IN ($deletePidArrStr)";
        mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
        
	$sql = "delete from SEARCH_MALE_REV where PROFILEID IN ($deletePidArrStr)";
        mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
}

unset($deletePidArr);
mysql_free_result($result);

$sql="select PROFILEID from SEARCH_FEMALE where LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
//$sql="select PROFILEID from SEARCH_FEMALE where SORT_DT < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
while($myrow=mysql_fetch_array($result))
{
        $deletePidArr[]=$myrow["PROFILEID"];
}
if(is_array($deletePidArr))
{
        $deletePidArrStr=implode(",",$deletePidArr);
        $sql = "delete from SEARCH_FEMALE where PROFILEID IN ($deletePidArrStr)";
        mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);

        $sql = "delete from SEARCH_FEMALE_TEXT where PROFILEID IN ($deletePidArrStr)";
        mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
	
	$sql = "delete from SEARCH_FEMALE_REV where PROFILEID IN ($deletePidArrStr)";
        mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
}

unset($deletePidArr);
mysql_free_result($result);
        
$sql2="UPDATE SEARCH_MALE SET FRESHNESS_POINTS=150 , TOTAL_POINTS=if(PROFILE_SCORE<=150,100,if(PROFILE_SCORE<326,300,450)) ,SCORE_POINTS=IF(PROFILE_SCORE<=150,-50,IF(PROFILE_SCORE<326,150,300)) where DATE_SUB(CURDATE(),INTERVAL 16 DAY) = if(HAVEPHOTO='Y',left(PHOTODATE,10),left(ENTRY_DT,10)) AND TOTAL_POINTS>49";
$result2=mysql_query($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2);


$sql2="UPDATE SEARCH_MALE SET FRESHNESS_POINTS=100 , TOTAL_POINTS=if(PROFILE_SCORE<=150,50,if(PROFILE_SCORE<326,250,400)) ,SCORE_POINTS=IF(PROFILE_SCORE<=150,-50,IF(PROFILE_SCORE<326,150,300)) where DATE_SUB(CURDATE(),INTERVAL 46 DAY) = if(HAVEPHOTO='Y',left(PHOTODATE,10),left(ENTRY_DT,10)) AND TOTAL_POINTS>49";
$result2=mysql_query($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2);


$sql2="UPDATE SEARCH_FEMALE SET FRESHNESS_POINTS=150 , TOTAL_POINTS=if(PROFILE_SCORE<=150,100,if(PROFILE_SCORE<326,300,450)) ,SCORE_POINTS=IF(PROFILE_SCORE<=150,-50,IF(PROFILE_SCORE<326,150,300)) where DATE_SUB(CURDATE(),INTERVAL 16 DAY) = if(HAVEPHOTO='Y',left(PHOTODATE,10),left(ENTRY_DT,10)) AND TOTAL_POINTS>49";
$result2=mysql_query($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2);


$sql2="UPDATE SEARCH_FEMALE SET FRESHNESS_POINTS=100 , TOTAL_POINTS=if(PROFILE_SCORE<=150,50,if(PROFILE_SCORE<326,250,400)) ,SCORE_POINTS=IF(PROFILE_SCORE<=150,-50,IF(PROFILE_SCORE<326,150,300)) where DATE_SUB(CURDATE(),INTERVAL 46 DAY) = if(HAVEPHOTO='Y',left(PHOTODATE,10),left(ENTRY_DT,10)) AND TOTAL_POINTS>49";
$result2=mysql_query($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2);

?>
