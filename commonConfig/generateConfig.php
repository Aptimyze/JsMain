<?php
include "JsConstantsConfig.class.php";
$env = $argv[1];
/*$urlInput = $argv[2];
$branch = $argv[3];
$rootDir = $argv[4];*/

$urlInput = $argv[2];
$rootDir = realpath(dirname(__FILE__)."/..");
$branchStrArr = explode("/",$rootDir);
$branch = end($branchStrArr);

//echo $branch." ".$rootDir;die;
switch ($env){
	case "dev":
	{
		$allConfigArr = JsConstantsConfig::$all;
		$testConfigArr = JsConstantsConfig::$dev;
		$configArr = array_merge($allConfigArr,$testConfigArr);
		break;
	}
	case "test":
	{
		$allConfigArr = JsConstantsConfig::$all;
		$testConfigArr = JsConstantsConfig::$test;
		$configArr = array_merge($allConfigArr,$testConfigArr);
		break;
	}
}

if (!file_exists("/usr/local/scripts/config/".$branch)) {
    mkdir("/usr/local/scripts/config/".$branch, 0777, true);
}
$file=fopen("/usr/local/scripts/config/".$branch."/JsConstants.class.php","w");
$now=date("Y-m-d");
fwrite($file,"<?php\n /*
This class lists all the configuration except mysql configuration.\n
Created on $now\n
 */
class JsConstants{\n");
foreach ($configArr as $k=>$v){
	/*if(is_array($v)){

	}
	else{*/
		//echo $k." ".$v."\n\n";
		if(strstr($v,'%SSL_URL_INPUT%')){
			fwrite($file, "\tpublic static $".$k." = '".str_replace("%SSL_URL_INPUT%","https://".$urlInput,$v)."';\n");
		}
		elseif(strstr($v,'%URL_INPUT%')){
			fwrite($file, "\tpublic static $".$k." = '".str_replace("%URL_INPUT%","http://".$urlInput,$v)."';\n");
		}
		elseif(strstr($v,'%ROOT_DIR%')){
			fwrite($file, "\tpublic static $".$k." = '".str_replace("%ROOT_DIR%",$rootDir,$v)."';\n");
		}
		else{
			if(strtolower(substr($v,0,5))=="array" || strtolower(substr($v,0,1)) == "[")
				fwrite($file, "\tpublic static $".$k." = ".$v.";\n");
			else{
				fwrite($file, "\tpublic static $".$k." = '".$v."';\n");
			}
		}
	//}
}
fwrite ($file, "}");
	


