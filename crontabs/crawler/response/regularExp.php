<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//$zip_pattern = "\([0-9]{1,} results found\)";
$fp=fopen("search_1_response.htm","rb");
$content=fread($fp,filesize("search_1_response.htm"));
fclose($fp);
//$zip_pattern = "/\(\d{0,}\s{0,}\+?\s{0,}results found\)/";
$zip_pattern = "/profileid=\w{1,}/";
//$str = "Mission Viejo, CA (926+ results found)";
preg_match_all($zip_pattern,$content,$regs);
$profileArr=array();
//var_dump($regs);
if(is_array($regs))
{
	foreach($regs as $matchArr)
	{
		foreach($matchArr as $value)
		{
			$arr=explode("=",$value);
			if(!in_array($arr[1],$profileArr))		
				$profileArr[]=$arr[1];
		}
	}
}
var_dump($profileArr);
?>
