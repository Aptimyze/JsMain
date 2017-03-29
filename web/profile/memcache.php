<?
include("connect.inc");
$memCacheObject = JsMemcache::getInstance();
$membershipKeyArray = VariableParams::$membershipKeyArray;
$keys_removed = "";
foreach ($membershipKeyArray as $key => $keyVal) {
	//echo "\n";
	//echo ($keyVal."--");
	//print_r(unserialize($memCacheObject->get($keyVal)));
    $memCacheObject->remove($keyVal);
    $keys_removed .= $keyVal.",\n"; 
}
//flush membership subscription if this extra param is set
if($_GET["memSub"] == '1'){
    $output = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_SUBSTATUS_ARRAY","suffix");
    $keys_removed .= $keys_removed."\n"."KEYS WITH SUFFIX as _MEM_SUBSTATUS_ARRAY";
}
//flush hamburger membership keys if this extra param is set
if($_GET["memHam"] == '1'){
	$output = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_HAMB_MESSAGE","suffix");
	$keys_removed .= $keys_removed."\n"."KEYS WITH SUFFIX as _MEM_HAMB_MESSAGE";
}
echo $keys_removed;
?>
