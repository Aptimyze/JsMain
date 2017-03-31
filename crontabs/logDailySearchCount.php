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
$memcache = new UserMemcache;
$counter = $memcache->get("TOTAL_SEARCH_COUNT_".$dt,NULL,0,0);

$dtZero = str_pad($dt, 2, "0", STR_PAD_LEFT); 
$javaKeys = array("time_l500","time_l1000","time_l2000","time_l3000","time_g3000");
$javaPrefixKey = "listingDppPerformance";
$javacount = 0;
for($i=0;$i<=23;$i++){
     $hr = str_pad($i, 2, "0", STR_PAD_LEFT);
     $key= $javaPrefixKey.$dtZero."_".$hr;
     foreach($javaKeys as $jKeys){
             $counter = $memcache->get($key.$jKeys,NULL,0,0);
             $javacount += $counter;
     }
}
if($counter != 0 && $counter != ''){
        $sql = "REPLACE INTO search.DAILY_SEARCH_COUNT VALUES ('".date("Y-m-d",strtotime("-1 day"))."',$counter,$javacount,NULL)";
        mysql_query($sql,$db) or die("DAILY_SEARCH_COUNT".mysql_error1($db));
}
$memcache->delete("TOTAL_SEARCH_COUNT_".date("d",strtotime("-20 day")));