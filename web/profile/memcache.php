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
echo $keys_removed;
?>
