<?php
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$mem = JsMemcache::getInstance();
$keys = $mem->getSetsAllValue("COUNTER_SEARCH_TYPE_KEYS");
$data = array();
foreach($keys as $key){
        $keyValue = $mem->get($key,NULL,0,0);
        $data[$key] = $keyValue;
}
arsort($data,SORT_REGULAR);
foreach ($data as $d){
        
}
$a = print_r($data,true);
$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/COUNTER_SEARCH_TYPE_KEYS.txt";
file_put_contents($fileName, $a."\n", FILE_APPEND);
die("Go to.".  $fileName);
?>
