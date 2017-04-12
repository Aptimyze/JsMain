<?
include("connect.inc");
$memCacheObject = JsMemcache::getInstance();
$membershipKeyArray = VariableParams::$membershipKeyArray;
$keys_removed = "";
foreach ($membershipKeyArray as $key => $keyVal) {
	//echo "\n";
	//echo ($keyVal."--");
	//print_r(unserialize($memCacheObject->get($keyVal)));
    $output1 = $memCacheObject->deleteKeysWithMatchedSuffix($keyVal,"prefix");
    $keys_removed .= $keyVal.",\n"; 
}

//flush membership subscription if this extra param is set
if($_GET["memSub@nkit@"] == '1'){
    $output = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_SUBSTATUS_ARRAY","suffix");
    $keys_removed .= "\n".",KEYS WITH SUFFIX as _MEM_SUBSTATUS_ARRAY";
}
//flush hamburger membership keys if this extra param is set
if($_GET["memOcb@nkit@"] == '1'){
	$output1 = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_HAMB_MESSAGE","suffix");
	$output2 = $memCacheObject->deleteKeysWithMatchedSuffix("_MEM_OBC_MESSAGE_API*","suffix");
	$keys_removed .= "\n".",KEYS WITH SUFFIX as _MEM_HAMB_MESSAGE,_MEM_OBC_MESSAGE_API*";
}
if($_GET["memVisible"] == '1'){
	$memCacheObject->remove("MAIN_MEM_DURATION");
	$memCacheObject->remove('NO_MEM_FILTER_MTONGUE');
	$keys_removed .= "\n".",MAIN_MEM_DURATION,NO_MEM_FILTER_MTONGUE";
}
echo $keys_removed;
?>
