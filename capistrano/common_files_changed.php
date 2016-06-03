<?php
$realPath=dirname(__FILE__);
$path= dirname($realPath);
$filecontent = file_get_contents("/tmp/capistranochangelog.txt");
$splitFileContent = explode("\n",$filecontent);
$DirectoryModifyTest = Array("commonConfig","crontabs","lib/task","web/sql_builds","web/uploads");
foreach($splitFileContent as $k=>$v)
{
	foreach($DirectoryModifyTest as $i=>$dir)
	{
        	if(strpos($v,$dir)!==false)
        	{
        		$tmpexplode= explode($dir,$v);
        		$filesChanged[$dir][]=$tmpexplode[1];
			break;
        	}
        }


}
if(is_array($filesChanged))
{
	foreach($filesChanged as $dir=>$files)
	{
		$filesChanged[$dir] = array_unique($filesChanged[$dir]);
		echo "\n".$dir." files changed/Added :";
		print_r($filesChanged[$dir]);
	}
}
else
	echo "NA";
unset($filecontent);
unset($splitFileContent);
?>
