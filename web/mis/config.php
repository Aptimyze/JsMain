<?php

$TOUT = 9000;   // the timeout value in seconds

$active_db = "";
$previous_db = "";
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");

include(JsConstants::$smartyDir);

$smarty=new Smarty;
//$smarty->relative_dir="mis/";
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/mis");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");


function connect_misdb()
{
	$db=db_set_active("slave",MysqlDbConstants::$misSlave[HOST].":".MysqlDbConstants::$misSlave[PORT],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS]) or die("Cudnt connect to slave".mysql_error());
        @mysql_select_db("MIS",$db);
        return $db;
}
/*
function connect_ddl()
{
        $db = db_set_active("masterDDL",MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT],MysqlDbConstants::$masterDDL[USER],MysqlDbConstants::$masterDDL[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("MIS",$db);         // connection string
        return $db;
}*/

function connect_rep()
{
        $db = db_set_active("masterRep",MysqlDbConstants::$masterRep[HOST].":".MysqlDbConstants::$masterRep[PORT],MysqlDbConstants::$masterRep[USER],MysqlDbConstants::$masterRep[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db_js("MIS",$db);               // connection string
        return $db;
}

function connect_master()
{
        $db=db_set_active("master",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or die("Coudnt connect to master".mysql_error());
        @mysql_select_db("jsadmin",$db);
        return $db;
}

function connect_737()
{
        $db=db_set_active("737",MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS]) or die("Coudnt connect to 737".mysql_error());
        @mysql_select_db("jsadmin",$db);
        return $db;
}
function connect_737m()
{
        $db=db_set_active("737",MysqlDbConstants::$bms[HOST].":".MysqlDbConstants::$bms[PORT],MysqlDbConstants::$bms[USER],MysqlDbConstants::$bms[PASS]) or die("Coudnt connect to 737".mysql_error());
        @mysql_select_db("MIS",$db);
        return $db;
}

function connect_211()
{
        $db2 = db_set_active("211",MysqlDbConstants::$viewLog[HOST].":".MysqlDbConstants::$viewLog[PORT],MysqlDbConstants::$viewLog[USER],MysqlDbConstants::$viewLog[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db("newjs",$db2);               // connection string
        return $db2;
}

function connect_211_slave()
{
	$db2 = db_set_active("211_slave",MysqlDbConstants::$viewLogSlave[HOST].":".MysqlDbConstants::$viewLogSlave[PORT],MysqlDbConstants::$viewLogSlave[USER],MysqlDbConstants::$viewLogSlave[PASS]) or die("Can't connect to Database".mysql_error());
        mysql_select_db("newjs",$db2);               // connection string
        return $db2;
}

if(!function_exists('connect_slave81'))
{
	function connect_slave81()
        {
                $db=db_set_active("slave",MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","81");
                @mysql_select_db_js("MIS",$db);
                return $db;
        }
}

function connect_dnc()
{
        $db_dnc = mysql_connect(MysqlDbConstants::$dnc[HOST].":".MysqlDbConstants::$dnc[PORT],MysqlDbConstants::$dnc[USER],MysqlDbConstants::$dnc[PASS]) or die("Unable to connect to dnc server");
        return $db_dnc;
}

// crmSlave server
function connect_crmSlave()
{
	$crm_slave = mysql_connect(MysqlDbConstants::$crmSlave[HOST].":".MysqlDbConstants::$crmSlave[PORT],MysqlDbConstants::$crmSlave[USER],MysqlDbConstants::$crmSlave[PASS]) or die("Unable to connect to crmSlave server");
        return $crm_slave;
}

?>
