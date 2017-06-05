<?php
//INCLUDE FILES HERE
include_once(JsConstants::$smartyDir);
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$alertDocRoot."/classes/authentication.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/configVariables.php");
include_once(JsConstants::$alertDocRoot."/kundli/SendKundliMailers.class.php");
include_once(JsConstants::$alertDocRoot."/kundli/TrackingFunctions.class.php");
//INCLUDE FILES HERE

$smarty=new Smarty;
$smarty->assign("SITE_URL",JsConstants::$siteUrl);
$smarty->assign("SITE_SER2_URL",JsConstants::$ser2Url);
$smarty->assign("IMG_URL",JsConstants::$imgUrl);

$mysqlObj = new Mysql;
$smarty->setTemplateDir(JsConstants::$alertDocRoot."/kundli/templates");
$smarty->setCompileDir(JsConstants::$alertDocRoot."/kundli/templates_c");

$mailerLimit = configVariables::$kundliMailLimit;

$localdb=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$localdb);
?>
