<?php
/*
First argument env
Second argument json of dynamic variables
php crontabs/generateConfig.php test '{"url_input":"con.jeevansathi.com"}'
*/
$env = $argv[1];//Env
$input = $argv[2];//json of dynamic variables

$inputArr = get_object_vars(json_decode($input));

//Dynamic variables
$urlInput = $inputArr["url_input"];
//$devIp = $inputArr["dev_ip"];
//$testIp = $inputArr["test_ip"];
//Ends

$rootDir = realpath(dirname(__FILE__)."/..");
$branchStrArr = explode("/",$rootDir);
$branch = end($branchStrArr);
include $rootDir."/commonConfig/JsConstantsConfig.class.php";

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
		{
			if(strtolower(substr($v,0,5))=="array" || strtolower(substr($v,0,1)) == "["){
				$str =  "$".$k." = ".$v;
			}
			else{
				$str = "$".$k." = '".$v."'";
			}
		}
//echo $str."";
		if(strstr($str,'%SSL_URL_INPUT%')){
			$str = str_replace("%SSL_URL_INPUT%","https://".$urlInput,$str);
		}
		elseif(strstr($str,'%URL_INPUT%')){
			$str = str_replace("%URL_INPUT%","http://".$urlInput,$str);
		}
		/*if(strstr($str,'%DEV_IP%')){
			$str = str_replace("%DEV_IP%","http://".$devIp,$str);
		}*/
		/*if(strstr($str,'%TEST_IP%')){
			$str = str_replace("%TEST_IP%","http://".$testIp,$str);
		}*/
		if(strstr($str,'%ROOT_DIR%')){
			$str = str_replace("%ROOT_DIR%",$rootDir,$str);
		}
//		echo $str;
		fwrite($file, "\tpublic static $str;\n");
	//}
}
fwrite ($file, "}");
	


