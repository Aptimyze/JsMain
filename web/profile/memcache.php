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
if($_GET["memSub@nkit@"] == '1'){
    $output = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_SUBSTATUS_ARRAY","suffix");
    $keys_removed .= $keys_removed."\n"."KEYS WITH SUFFIX as _MEM_SUBSTATUS_ARRAY";
}
//flush hamburger membership keys if this extra param is set
if($_GET["memOcb@nkit@"] == '1'){
	$output1 = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_HAMB_MESSAGE","suffix");
	$output2 = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_OBC_MESSAGE_API","suffix");
	$keys_removed .= $keys_removed."\n"."KEYS WITH SUFFIX as _MEM_HAMB_MESSAGE,_MEM_OBC_MESSAGE_API";
}
echo $keys_removed;
?>
