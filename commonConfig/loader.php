<?php
//$inTimeApi=microtime("TRUE");
$branch=getBranch();
$configPath='/usr/local/scripts/config/';
if(file_exists($configPath.$branch.'/JsConstants.class.php'))
        include_once($configPath.$branch.'/JsConstants.class.php');
include_once('/usr/local/scripts/config/MysqlDbConstants.class.php');
include_once('/usr/local/scripts/config/MongoDbConstants.class.php');
include_once('/usr/local/scripts/config/environmentFunctions.php');
if(file_exists($configPath.$branch.'/JsConstants.class.php'))
	include_once(JsConstants::$docRoot."/commonFiles/FetchIP.php");


if(array_key_exists("useHeaderCaching",$_GET) && $_GET["useHeaderCaching"]==1)
{
       $offset=60*60*1;//time to be cached:1 hrs
       header("Cache-Control: public,max-age=$offset,s-maxage=$offset");
}

/** cron azkaban **/
if(php_sapi_name() === 'cli')
        register_shutdown_function('shutdown');
function successfullDie($msg='')
{
        if($msg)
                echo $msg;
        define('SCRIPT_END_REACHED', TRUE);
        exit(0);
}
function shutdown() 
{
        if(defined('SCRIPT_END_REACHED'))
                exit(0);
        else
                exit(1);
}
/** cron azkaban **/


function getBranch()
{

	 $docroot=$_SERVER['DOCUMENT_ROOT'];
 	if($docroot)
       		$branch=explodeSingle($docroot,"web");
 	else
	{	
		$realPath=realpath($_SERVER['PHP_SELF']);
		$mainDirectory= getJsDirectory($realPath); 
		if($mainDirectory)
                        $branch=explodeSingle($realPath,$mainDirectory);

	}
	return $branch;
}

function explodeSingle($path='',$word)
{
       if($path!='')
        {
                $split=explode($word,$path);
                $split1=explode("/",$split[0]);
                return $split1[sizeof($split1)-2];
        }
}

function getJsDirectory($path)
{
	$webPos=strrpos($path,"web"); 
        $crontabPos=strrpos($path,"crontabs");
	$libUtilPos=strrpos($path,"lib/utils");
	$symfonyPos=strrpos($path,"symfony");
	
	if($webPos > $crontabPos && $webPos > $libUtilPos && $webPos > $symfonyPos)
		return "web";
	elseif($webPos < $crontabPos && $crontabPos > $libUtilPos && $crontabPos > $symfonyPos)
                return "crontabs";
	elseif($libUtilPos > $symfonyPos)
		return "lib/utils";
	else
		return "symfony";

}
//register_shutdown_function('shutdownApi');
/*
function shutdownApi()
{

	global $inTimeApi;
	
	$page=explode('?',$_SERVER["REQUEST_URI"]);
	$page=$page[0];
	
	
	$outTime=microtime("TRUE");
	
	$differenceInSeconds = $outTime - $inTimeApi;
	
	$db=mysql_connect('172.16.3.185','localuser','Km7Iv80l');
	$sql= "Insert into MOBILE_API.API_TIME_TRACKING Values ('".$page."',".$differenceInSeconds.")";
	$res = mysql_query($sql) or  die("errorInAPiTracking");
	//$myrow= mysql_fetch_row($res);
	
}
*/
?>
