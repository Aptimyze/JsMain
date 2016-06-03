<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("$_SERVER[DOCUMENT_ROOT]/profile/connect_db.php");
include_once("$_SERVER[DOCUMENT_ROOT]/profile/login_intermediate_pages.php");
$db=connect_db();
$db_slave = connect_slave();

//day1A
$from_date=date("Y-m-d",time()-86400*155);
$sql_screen = "SELECT PROFILEID FROM newjs.JPROFILE WHERE INCOMPLETE='Y' AND ENTRY_DT>'$from_date'";
$res_screen = mysql_query_decide($sql_screen,$db_slave) or die(mysql_error($db_slave));
while($row_screen = mysql_fetch_array($res_screen))
{
	if(!is_incomplete($row_screen["PROFILEID"]))
	{
		$sql_in="INSERT INTO test.INCOMP_TO_COMP (PROFILEID) VALUES ($row_screen[PROFILEID])";
                mysql_query_decide($sql_in,$db) or die(mysql_error($db));
	}
}

//day1 to 11
/*
$sql_screen = "SELECT PROFILEID FROM test.INCOMP_TO_COMP WHERE HANDLED='N' LIMIT 1000";
$res_screen = mysql_query_decide($sql_screen,$db_slave) or die(mysql_error($db_slave));
while($row_screen = mysql_fetch_array($res_screen))
{       
        if(!is_incomplete($row_screen["PROFILEID"]))
        {
                $sql_incomp="UPDATE newjs.JPROFILE SET MOD_DT=now(),INCOMPLETE='N',ENTRY_DT=now() WHERE PROFILEID=$row_screen[PROFILEID]";
                mysql_query_decide($sql_incomp,$db) or die(mysql_error($db));
                $sql_in="UPDATE test.INCOMP_TO_COMP SET HANDLED='Y' WHERE PROFILEID=$row_screen[PROFILEID]";
                mysql_query_decide($sql_in,$db) or die(mysql_error($db));
        }
}
*/
?>
