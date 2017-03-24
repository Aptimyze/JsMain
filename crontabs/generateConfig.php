<?php
/*
First argument: env
Second argument: Domain name of the branch
Command: php crontabs/generateConfig.php test xmpp.jeevansathi.com
*/
$env = $argv[1];//Env
$input = $argv[2];//Branch domain

//$inputArr = get_object_vars(json_decode($input));

//Dynamic variables
//$urlInput = $inputArr["url_input"];
$urlInput = $input;
//$devIp = $inputArr["dev_ip"];
//$testIp = $inputArr["test_ip"];
//Ends

$rootDir = realpath(dirname(__FILE__)."/..");
$branchStrArr = explode("/",$rootDir);
$branch = end($branchStrArr);
include $rootDir."/commonConfig/JsConstants.class.php";

//echo $branch." ".$rootDir;die;

$allConfigArr = JsConstantsConfig::$all;
$devConfigArr = JsConstantsConfig::$dev;
$allDevConfigArr = array_merge($allConfigArr,$devConfigArr);
$testConfigArr = JsConstantsConfig::$test;
$allTestConfigArr = array_merge($allConfigArr,$testConfigArr);

/****Capture Error****/
$error = "";
$diffDev = array_diff_key($allDevConfigArr, $allTestConfigArr);
if($diffDev)
	$error.="Please add ".implode(",",array_keys($diffDev))." in test environment. ";
$diffTest = array_diff_key($allTestConfigArr, $allDevConfigArr);
if($diffTest)
	$error.="Please add ".implode(",",array_keys($diffTest))." in dev environment";
if($error)
	Throw new Exception ($error);
/****Ends here****/

switch ($env){
	case "dev":
	{
		$configArr = $allDevConfigArr;
		break;
	}
	case "test":
	{
		$configArr = $allTestConfigArr;
		break;
	}
}

if (!file_exists("/usr/local/scripts/config/".$branch)) {
    mkdir("/usr/local/scripts/config/".$branch, 0777, true);
}
$file=fopen("/usr/local/scripts/config/".$branch."/JsConstants.class.php","w");
$now=date("Y-m-d");
fwrite($file,"<?php\n /*
This class lists all the Jeevansathi dev and test configurations except mysql.\n
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
		elseif(strstr($str,'%STATIC_URL_INPUT%')){
			$str = str_replace("%STATIC_URL_INPUT%","http://static.".$urlInput,$str);
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
	


