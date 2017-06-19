<?php
/***************************************************************************************************************
* FILE NAME     : matchalert.php
* DESCRIPTION   : Connects to the database, creates an object of Smarty class and then calls the functions one-by-one
*		: which eventually sends the mails
* CREATION DATE : 11 July, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

ini_set("max_execution_time","0");

$matchalertServer = 1;

include_once(JsConstants::$smartyDir);
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$alertDocRoot."/commonFiles/incomeCommonFunctions.inc");
include_once(JsConstants::$alertDocRoot."/classes/authentication.class.php");
include_once(JsConstants::$alertDocRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$alertDocRoot."/classes/Jpartner.class.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$alertDocRoot."/classes/shardingRelated.php");

$smarty=new Smarty;
$smarty->assign("SITE_URL",JsConstants::$siteUrl);
$smarty->assign("SITE_SER2_URL",JsConstants::$ser2Url);
$smarty->assign("IMG_URL",JsConstants::$imgUrl);

$protect_obj=new protect;
$mysqlObj = new Mysql;
$smarty->setTemplateDir(JsConstants::$alertDocRoot."/jsmail/templates");
$smarty->setCompileDir(JsConstants::$alertDocRoot."/jsmail/templates_c");
$jpartnerObj=new Jpartner('newjs.JPARTNER');

$db=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
//mysql_select_db("matchalerts",$db) or die(mysql_error());

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObj);
        $myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$myDbArr[$myDbName]);
}

$db_211=$mysqlObj->connect("viewLogSlave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);
//mysql_select_db("newjs",$db_211) or die(mysql_error());

include_once(JsConstants::$alertDocRoot."/jsmail/mail_new_inc.php");

//new logic portion
new_mails();
?>
