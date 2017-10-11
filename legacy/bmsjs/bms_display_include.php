<?php
/*********************************************./includes/bms_display_include.php.php****************************************/
  /*
	*	Created By		:	Abhinav Katiyar
	*	Last Modified By   	:	Abhinav Katiyar
	*	Description	        :	This file includes various functions
						used for display
	*	Includes/Libraries 	:	bms_connections.php
***************************************************************************************************************************/

global $_SVNENABLE;
$_SVNENABLE="false";//value set to "true", will work only after authentication of user
global $_SVN,$_LOGPATHS,$_SMARTYPATHS;
$_SMARTYPATHS["220"] ="/usr/local/apache/htdocs/smarty/Smarty.class.php";
$_SMARTYPATHS["205"] ="/var/www/html1/smarty/Smarty.class.php";
$_SMARTYPATHS["corp"] ="/usr/local/apache/htdocs/smarty/Smarty.class.php";
$_SMARTYPATHS["jobs"] ="/usr/local/apache/sites/site1/smarty/Smarty.class.php";
$_SMARTYPATHS["resman"] ="/usr/local/smarty/Smarty.class.php";
$_LOGPATHS["220"]="/usr/local/apache/htdocs";
$_LOGPATHS["205"]="/var/www/html";
$_LOGPATHS["corp"]="/usr/local/apache/htdocs";
$_LOGPATHS["jobs"]="/usr/local/apache/sites/site1";
$_LOGPATHS["resman"]="/usr/local/apache/sites/site2";
global $_LOGPATH,$_HITSFILE,$_LOGIMPS,$_ADDLOGPATH;
$_HITSFILE="http://www.jeevansathi.com/bmsjs/bms_hits.php"; 
$_LOGIMPS="http://www.jeevansathi.com/bmsjs/bms_logimpressions.php";
$_TPLPATH="bms";   // name of the directory in which bms tpl's will be kept
$_ADDLOGPATH="/bmsjs/log";// the name of the directory in which the log directory for bms will be kept

getLogPathBms();

/*******************************Including bms_connections.php for taking connections**************************************/
//include_once("bms_connections.php");
/************************************************************************************************************************/	
$smarty=getSmartyBms();
global $dbbms,$dbsums;
$validcriteriaarray=array("Keywords","Farea","Industry","Location","Exp","Categories","IP","Ctc","Age","Gender","ExpResman","IndustryResman","FareaResman");
$bannerclassarr=array("Banner"=>array("Image","Flash"),
				"Mailer"=>array("MailerFlash","MailerImage"),
				"PopUp"=>array("PopUp"),
				"PopUnder"=>array("PopUnder")
				);


/*   
	Fetches smarty paths for use at different servers
	Includes the smarty file for use at the 
	input: username,password,ip
	output: array of user info/ null
*/
	  
function getSmartyBms()
{
	global $_SMARTYPATHS,$_SERVER,$smarty;
	
	//get host name
	$server=$_SERVER["HTTP_HOST"];
	
	//get smarty path

	if($server=="198.65.112.205" || $server=="www.jeevansathi.com")
		$smartypath =$_SMARTYPATHS["205"];

	elseif($server=="192.168.2.220")
                $smartypath =$_SMARTYPATHS["220"];
	else 
	{
		$server1=explode(".",$server);
		$server=$server1[0];
		if($server=="corp")
			$smartypath =$_SMARTYPATHS["corp"];
		elseif ($server=="resman")
			$smartypath =$_SMARTYPATHS["resman"];
		elseif(trim($server)=="jobs")
			$smartypath =$_SMARTYPATHS["jobs"];	
	}

	//include file containing smarty class
	include_once("$smartypath");

	//create a new object for smarty
	if(!isset($smarty))   
		$smarty = new Smarty;
    return $smarty;

}

/*   
	sets the global variable $_LOGPATH to the value of the path of the errorlog of that server.
	input: none
	output: none
*/
function getLogPathBms()
{
	global $_LOGPATHS,$_SERVER,$_LOGPATH,$_SVN,$_ADDLOGPATH;
	//get host name
	$server=$_SERVER["HTTP_HOST"];
	
	//get log path
	if($server=="198.65.112.205" || $server=="www.jeevansathi.com" || $server=="www.jeevansaathi.com" || $server=="www.jeevansathi.net" || $server=="www.jeevansaathi.net" || $server=="www.jeevansathi.org" || $server=="www.jeevansaathi.org")
		$_LOGPATH =$_LOGPATHS["205"].$_SVN.$_ADDLOGPATH;
	elseif($server=="192.168.2.220")
		$_LOGPATH =$_LOGPATHS["220"].$_SVN.$_ADDLOGPATH;
	else 
	{
		$server1=explode(".",$server);
		$server=$server1[0];
		//echo "server:$server";
		if($server=="corp")
			$_LOGPATH =$_LOGPATHS["corp"].$_ADDLOGPATH;
		elseif ($server=="resman")
			$_LOGPATH =$_LOGPATHS["resman"].$_ADDLOGPATH;
		elseif(trim($server)=="jobs")
			$_LOGPATH =$_LOGPATHS["jobs"].$_ADDLOGPATH;	
	}

    
}

/*   
	logs the error and defines the output to be seen by the user when a sql query dies
	input: message to be logged, query, whether to continue or exit, send a mail or not
	output: error template shown , or exit or coninued with error msg displayed
*/
function logErrorBms($message,$query="",$critical="exit", $sendmailto="NO")
{
	/* this function creates a log file mn_error.txt in bms/log directory*/
	global $dbbms, $smarty,$_LOGPATH,$_TPLPATH;
	getLogPathBms();
	ob_start();
 	var_dump($_SERVER);
 	$ret_val = ob_get_contents();
 	ob_end_clean();
	$errorstring="\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
	
	error_log($errorstring,3,JsConstants::$docRoot . "$_LOGPATH/log/bms_error.txt");	

	/* if critical option is set to exit then exit from the script after displaying the error message*/
	if($critical=="exit")
	{
		echo $message;
		exit;
	}
	
	/* if critical option is set to ShowErrTemplate then display error template*/
	elseif($critical=="ShowErrTemplate")
	{
		$smarty->assign("msg_error", $message);
		$smarty->display("./$_TPLPATH/bms_error.htm");
		exit;
	}
	
	/* if critical option is set to continue then display message and continue */
	elseif($critical!="continue")
	{
		echo $message;
	}
}
?>
