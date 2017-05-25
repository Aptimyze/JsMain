<?
$type=0;
$cnt = xcache_count($type);
for($i=0;$i<$cnt;$i++)
{
        $cacheid=$i;
        $arr = xcache_info($type,$i);
        xcache_clear_cache($type, $cacheid);
        $arr1 = xcache_info($type,$i);
	echo $arr["cached"]."-----".$arr1["cached"].'<br>';
}
