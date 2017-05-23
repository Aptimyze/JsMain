<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	/***************
	Cron for cleaning up unused dial codes for call now functionality
	Created by Tanu Gupta 
	Created at 30 Sep 11
	***************/

	include "connect.inc";
	$db = connect_db();
	$yTimestamp=mktime(0,0,0,date("m"),date("d")-7,date("Y"));
	$yesterday = date("Y-m-d",$yTimestamp);
	$sql = "DELETE FROM newjs.DIALCODE_GENERATE WHERE ADD_TIME <= '$yesterday 00:00:00'";
	mysql_query($sql,$db) or trackIvrError($sql, $db, "DIALCODE_GENERATE");

        function trackIvrError($sql, $db, $type)
        {
                $msg="TYPE:".$type;
                if($db)
                        echo $msg = $msg."\nSQL:".$sql."\nDB:".$db."\nERROR:".mysql_error($db);
                mail("tanu.gupta@jeevansathi.com","Remove dialcodes Cron: $type",$msg);
        }
?>
