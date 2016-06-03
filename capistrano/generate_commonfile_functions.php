<?php
$realPath=dirname(__FILE__);
$path= dirname($realPath);
$filecontent= file_get_contents("/tmp/jsCssFilelog.txt");
$splitFileContent = explode("\n",$filecontent);
$tmpString=implode(" ",$splitFileContent);
unset($fileContent);
$files=Array();
$GLOBALS["css"] = array();
$GLOBALS["js"] = array();
$tmpfiles = explode("/web/js/",$tmpString);
foreach($tmpfiles as $k=>$v)
{
	if(strpos($v,".js ")!=FALSE)
        {
                $tmpexplode= explode(".",$v);
		if(strpos($tmpexplode[0]," ")==FALSE)
		{
                	$files["js"][]="\"".$tmpexplode[0]."\"";
		}
                	$tmpString= str_replace($tmpexplode[0].".js","",$tmpString);
        }

}
$tmpfiles = explode("/web/profile/js/",$tmpString);
foreach($tmpfiles as $k=>$v)
{
	if(strpos($v,".js ")!=FALSE)
        {
		$tmpexplode= explode(".js",$v);
		if(strpos($tmpexplode[0]," ")==FALSE)
                {

                	$files["js"][]="\"".$tmpexplode[0]."\"";
		}
        }

}
$tmpfiles = explode("/web/css/",$tmpString);
foreach($tmpfiles as $k=>$v)
{
	if(strpos($v,".css")!=FALSE)
	{
		$tmpexplode= explode(".",$v);
		if(strpos($tmpexplode[0]," ")==FALSE)
                {
			$files["css"][]="\"".$tmpexplode[0]."\"";
		}
		$tmpString= str_replace($tmpexplode[0].".css","",$tmpString);
	}
}
$tmpfiles = explode("/web/profile/css/",$tmpString);
foreach($tmpfiles as $k=>$v)
{
	if(strpos($v,".css")!=FALSE)
	{
		$tmpexplode= explode(".",$v);
		if(strpos($tmpexplode[0]," ")==FALSE)
                {
			$files["css"][]="\"".$tmpexplode[0]."\"";
		}
	}
}
unset($tmpfiles);
if(sizeof($files)>0)
{
	if(sizeof($files["css"])>0)
		$files["css"]= array_unique($files["css"]);
	if(sizeof($files["js"])>0)
		$files["js"]= array_unique($files["js"]);
	$file = fopen($path."/web/profile/commonfile_functions.php","r");
	$filew = fopen($path."/web/profile/commonfile_functions_new.php","w+");
	while(!feof($file))
	{
		$x = fgets($file);
		if(sizeof($files["css"])>0)
		{
			if(strpos($x,"css_arr[]=array")!=FALSE)
			{
				$newx= getIncrementLine($x,$files["css"],"css");
				if($newx)
				{
					$x=$newx["newline"];
					unset($files["css"][$newx["unset"]]);
				}
			}
			elseif(strpos($x,"css_arr;")!=FALSE)
			{
				foreach($files["css"] as $k=>$v)
                        	{
					$GLOBALS["css"][]=preg_replace('/\s+/', '', $v);
					$x1="\$css_arr[]=array($v   => \"1\");\n";
					$x=$x1.$x;
					unset($files["css"][$k]);
                        	}
			}
		}
		elseif(sizeof($files["js"])>0)
        	{
                	if(strpos($x,"js_arr[]=array")!=FALSE)
                	{
                        	$newx= getIncrementLine($x,$files["js"],"js");
                        	if($newx)
                        	{
                                	$x=$newx["newline"];
                                	unset($files["js"][$newx["unset"]]);
                        	}
                	}
                	elseif(strpos($x,"js_arr;")!=FALSE)
                	{
                        	foreach($files["js"] as $k=>$v)
                        	{
					$GLOBALS["js"][]=preg_replace('/\s+/', '', $v);
                                	$x1="\$js_arr[]=array($v   => \"1\");\n";
                                	$x=$x1.$x;
                                	unset($files["js"][$k]);
                        	}
                	}
	        }
		fwrite($filew,$x);
	}
	unset($files);
	fclose($file);
	fclose($filew);
	unlink($path."/web/profile/commonfile_functions.php");
	rename($path."/web/profile/commonfile_functions_new.php",$path."/web/profile/commonfile_functions.php");
	if(sizeof($GLOBALS["js"]) != sizeof(array_unique($GLOBALS["js"])) || sizeof($GLOBALS["css"]) != sizeof(array_unique($GLOBALS["css"])))
		print_r("Some Error");
	else
		print_r("Commit");
}
else
	print_r("NA");
function getIncrementLine($x,$files,$type)
{
	$flag=0;
        $fileName="";
        $tempArr = explode("=>",$x);
        foreach($tempArr as $k=>$v)
        trim($tempArr[$k]);
	$getNameArray = explode("array(",$tempArr[0]);
	$GLOBALS[$type][] = preg_replace('/\s+/', '', $getNameArray[1]);
        foreach($files as $k=>$v)
        {
        	if(strpos($tempArr[0],$v)!=FALSE)
                {
                	$flag=1;
                        $fileName=$k;
                }
	}
	if($flag)
        {
        	$result["unset"]=$fileName;
                $tempArr[1] = ltrim($tempArr[1],"  ");
                $tempArr[1] = ltrim($tempArr[1]," ");
                $tempArr[1] = rtrim($tempArr[1],"\n");
                $tempArr[1] = rtrim($tempArr[1],";");
                $tempArr[1] = rtrim($tempArr[1],")");
                $tempArr[1] = trim($tempArr[1],"\"");
                $tempArr[1] = intval($tempArr[1])+1;
                $tempArr[1] = "\"".$tempArr[1]."\");";
                $result["newline"] = implode(" => ",$tempArr)."\n";
		return $result;
	}
	return null;
}
?>
