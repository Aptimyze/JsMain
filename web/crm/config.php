<?php
$TOUT = 9000;   // the timeout value in seconds

if(!$active_db)
	$active_db = "";
if(!$previous_db)
	$previous_db = "";
if(!$db_211)
        $db_211="";
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$db=connect_db();

function connect_db()
{
	$db = db_set_active("master",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Can't connect to Database".mysql_error()));
        mysql_select_db_js("incentive",$db);               // connection string
        return $db;
}
/*
function connect_ddl()
{
        $db = db_set_active("masterDDL",MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT],MysqlDbConstants::$masterDDL[USER],MysqlDbConstants::$masterDDL[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("incentive",$db);         // connection string
        return $db;
}*/

function connect_rep()
{
        $db = db_set_active("masterRep",MysqlDbConstants::$masterRep[HOST].":".MysqlDbConstants::$masterRep[PORT],MysqlDbConstants::$masterRep[USER],MysqlDbConstants::$masterRep[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("incentive",$db);               // connection string
        return $db;
}

function connect_db2()
{
	$db2 = db_set_active("slave",MysqlDbConstants::$misSlave[HOST],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS],MYSQL_CLIENT_COMPRESS) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Cudnt connect to slave".mysql_error()));
        mysql_select_db_js("incentive",$db2);               // connection string
        return $db2;
}

function connect_81()
{
	$db2 = db_set_active("slave81",MysqlDbConstants::$alertsSlave[HOST].":".MysqlDbConstants::$alertsSlave[PORT],MysqlDbConstants::$alertsSlave[USER],MysqlDbConstants::$alerts[PASS]) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Can't connect to Database".mysql_error()));
        mysql_select_db_js("incentive",$db2);               // connection string
        return $db2;
}

function connect_737()
{
	$db2 = db_set_active("737",MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS]) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Can't connect to Database".mysql_error()));
        mysql_select_db_js("incentive",$db2);               // connection string
        return $db2;
}

function connect_211()
{
        $db2 = db_set_active("211",MysqlDbConstants::$viewLog[HOST].":".MysqlDbConstants::$viewLog[PORT],MysqlDbConstants::$viewLog[USER],MysqlDbConstants::$viewLog[PASS]) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Can't connect to Database".mysql_error()));
        mysql_select_db_js("newjs",$db2);               // connection string
        return $db2;
}

function connect_dnc()
{
	$db_dnc =mysql_connect(MysqlDbConstants::$dnc[HOST].":".MysqlDbConstants::$dnc[PORT],MysqlDbConstants::$dnc[USER],MysqlDbConstants::$dnc[PASS]) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Unable to connect to dnc server"));
        return $db_dnc;
}
function connect_crmSlave()
{
        $crm_slave =mysql_connect(MysqlDbConstants::$crmSlave[HOST].":".MysqlDbConstants::$crmSlave[PORT],MysqlDbConstants::$crmSlave[USER],MysqlDbConstants::$crmSlave[PASS]) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Unable to connect to crmSlave server"));
        return $crm_slave;
}

if(!$symfonyVar){
	include(JsConstants::$smartyDir);
	$smarty=new Smarty;
	$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/crm");
	$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");

	$SITE_URL=JsConstants::$siteUrl;
	$smarty->assign("SITE_URL",$SITE_URL);
	$PHOTO_URL =JsConstants::$screenedPhotosUrl;
	$smarty->assign("PHOTO_URL",$PHOTO_URL);
	$IMG_URL = $SITE_URL;
	$smarty->assign("IMG_URL",$IMG_URL);
}
?>
