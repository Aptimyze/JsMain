<?php

$TOUT = 9000;   // the timeout value in seconds

if(!$active_db)
	$active_db = "";
if(!$previous_db)
	$previous_db = "";
if(!$db_211)
        $db_211="";

$domain=".jeevansathi.com";

include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");

include (JsConstants::$smartyDir);

$db = connect_db();

function connect_db()
{
	$db = db_set_active("master",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or die("Can't connect to Database".mysql_error());
	mysql_select_db_js("jsadmin",$db);         // connection string
	return $db;
}
/*
function connect_ddl()
{
        $db = db_set_active("masterDDL",MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT],MysqlDbConstants::$masterDDL[USER],MysqlDbConstants::$masterDDL[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("jsadmin",$db);         // connection string
        return $db;
}*/

function connect_rep()
{
        $db = db_set_active("masterRep",MysqlDbConstants::$masterRep[HOST].":".MysqlDbConstants::$masterRep[PORT],MysqlDbConstants::$masterRep[USER],MysqlDbConstants::$masterRep[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("jsadmin",$db);               // connection string
        return $db;
}

function connect_slave81()
{
	$db = db_set_active("slave",MysqlDbConstants::$alertsSlave[HOST].":".MysqlDbConstants::$alertsSlave[PORT],MysqlDbConstants::$alertsSlave[USER],MysqlDbConstants::$alerts[PASS]) or die("Can't connect to Database".mysql_error());
	mysql_select_db_js("jsadmin",$db);         // connection string
	return $db;
}
function connect_slave()
{
	
	$db=db_set_active("slave",MysqlDbConstants::$misSlave[HOST].":".MysqlDbConstants::$misSlave[PORT],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS]) or die("Cudnt connect to slave".mysql_error());
	mysql_select_db_js("jsadmin",$db);         // connection string
	return $db;
}

function connect_737()
{
	$db = db_set_active("737",MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS]) or die("Can't connect to Database".mysql_error());
	mysql_select_db_js("jsadmin",$db);         // connection string
	return $db;
}

function connect_211()
{
        $db2 = db_set_active("211",MysqlDbConstants::$viewLog[HOST].":".MysqlDbConstants::$viewLog[PORT],MysqlDbConstants::$viewLog[USER],MysqlDbConstants::$viewLog[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("newjs",$db2);               // connection string
        return $db2;
}

$smarty = new Smarty;
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/jsadmin/");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");

$SITE_URL=JsConstants::$siteUrl;
$smarty->assign("SITE_URL",$SITE_URL);
$PHOTO_URL =JsConstants::$screenedPhotosUrl;
$smarty->assign("PHOTO_URL",$PHOTO_URL);
$IMG_URL = $SITE_URL;
$smarty->assign("IMG_URL",$IMG_URL);
$REG_IMG="$SITE_URL/profile/images/reg"; //When online change this IMAGE path to ser4.jeevansathi.com rather than SITE_URL
$smarty->assign("REG_IMG",$REG_IMG);
$smarty->assign("JQUERY_JS",JsConstants::$jquery);
?>
