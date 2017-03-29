<?php 
include_once("/usr/local/scripts/DocRoot.php");
$flag_using_php5=1;
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/classes/Memcache.class.php");
$mysqlObj=new Mysql;
$db=connect_db();
mysql_query("set session wait_timeout=1000",$db);
$dt = date("d",strtotime("-1 day"));
$memcache = JsMemcache::getInstance();
$counter = $memcache->get("TOTAL_SEARCH_COUNT_".$dt,NULL,0,0);
if($counter != 0 && $counter != ''){
        $sql = "REPLACE INTO search.DAILY_SEARCH_COUNT VALUES ('".date("Y-m-d")."',$counter,NULL)";
        mysql_query($sql,$db) or die("DAILY_SEARCH_COUNT".mysql_error1($db));
}
$memcache->delete("TOTAL_SEARCH_COUNT_".date("d",strtotime("-20 day")));