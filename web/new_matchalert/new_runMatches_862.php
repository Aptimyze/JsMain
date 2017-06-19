<?php
/***************************************************************************************************************
* Created By    : Lavesh Rawat
*****************************************************************************************************************/
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

chdir(dirname(__FILE__));
include_once(JsConstants::$alertDocRoot."/new_matchalert/connect.inc");
include_once(JsConstants::$alertDocRoot."/new_matchalert/configVariables.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Receiver.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyNTvsNT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyNTvsT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyTvsNT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyTvsT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SendMatchAlert.php");
include_once(JsConstants::$alertDocRoot."/classes/shardingRelated.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$mysqlObj = new Mysql;	
$db=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

while(1)
{
        //@mysql_ping($db);
        $sql="SELECT VALUE FROM matchalerts.STARTCRON";
        $result=mysql_query($sql) or die(mysql_error().$sql);
        $myrow=mysql_fetch_row($result);
        $maxId=$myrow[0];

        $sql="SELECT COUNT(*) FROM matchalerts.MAILER WHERE SENT=''";
        $result=mysql_query($sql) or die(mysql_error().$sql);
        $myrow=mysql_fetch_row($result);
        $n_count=$myrow[0];

        $sql="TRUNCATE TABLE matchalerts.MAILER_TEMP";
        $result=mysql_query($sql) or die(mysql_error().$sql);

        $sql="SELECT COUNT(*) FROM matchalerts.MAILER_TEMP";
        $result=mysql_query($sql) or die(mysql_error().$sql);
        $myrow=mysql_fetch_row($result);
        $n_count1=$myrow[0];

        if($maxId>630 && !$n_count && !$n_count1)
        {
		mysql_select_db("matchalerts",$db);
		$arr=array("TRENDS_SEARCH_MALE","NOTRENDS_SEARCH_MALE","TRENDS_SEARCH_FEMALE","NOTRENDS_SEARCH_FEMALE","FEMALE_TRENDS_HEAP","MALE_TRENDS_HEAP","HEAP_TRENDS_MALE","HEAP_TRENDS_FEMALE","HEAP_NOTRENDS_MALE","HEAP_NOTRENDS_FEMALE");
		for($i=0;$i<count($arr);$i++)
		{
			$str=$arr[$i];
			$sql="SELECT COUNT(*) FROM $str";
			$result=mysql_query($sql,$db) or die(mysql_error().$sql);
			$myrow=mysql_fetch_row($result);
			$n_count=$myrow[0];
			if($n_count<50000)
			{
				 mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com',"TABLE EMPTY".$str,$n_count."records");
				die;
			}
		}
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 0 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 1 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 2 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 3 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 4 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 5 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 6 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 7 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
                passthru("nohup ".JsConstants::$php5path." -q check_trends.php 9 8 $maxId >>".JsConstants::$alertDocRoot."/new_matchalert/logerror.txt &");
		die;
	}
        else
        {
		mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','new_runMatches_862.php','new_runMatches_862.php');
                // wait for 5 minutes
		usleep(300000000);
        }

}
?>
