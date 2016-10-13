<?php
function connect_db()
{
        $db=@mysql_connect(MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or die("master connection failed");//changed
        @mysql_select_db("newjs",$db);
        return $db;
}

function connect_ddl()
{
        $db=@mysql_connect(MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT],MysqlDbConstants::$masterDDL[USER],MysqlDbConstants::$masterDDL[PASS]) or die("master connection failed");//changed
        @mysql_select_db("newjs",$db);
        return $db;
}

function connect_slave()
{
       
        $db=@mysql_connect(MysqlDbConstants::$misSlave[HOST].":".MysqlDbConstants::$misSlave[PORT],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS]) or die("slave connection failed");
        @mysql_select_db("newjs",$db);
        return $db;
}

function connect_737()
{
        $db=@mysql_connect(MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS]) or die("737 connection failed");
        @mysql_select_db("newjs",$db);
        return $db;
}

function connect_737_lan()
{
        $db=@mysql_connect(MysqlDbConstants::$bms[HOST].":".MysqlDbConstants::$bms[PORT],MysqlDbConstants::$bms[USER],MysqlDbConstants::$bms[PASS]) or die("737 connection failed");
        @mysql_select_db("newjs",$db);
        return $db;
}

function connect_211()
{
	$db = mysql_connect(MysqlDbConstants::$viewLog[HOST].":".MysqlDbConstants::$viewLog[PORT],MysqlDbConstants::$viewLog[USER],MysqlDbConstants::$viewLog[PASS]) or die("211 connection failed");
	mysql_select_db("newjs",$db);               // connection string
	return $db;
}

function connect_viewLogDDL()
{
        $db = mysql_connect(MysqlDbConstants::$viewLogDDL[HOST].":".MysqlDbConstants::$viewLogDDL[PORT],MysqlDbConstants::$viewLogDDL[USER],MysqlDbConstants::$viewLogDDL[PASS]) or die("211 DDL connection failed");
        mysql_select_db("newjs",$db);               // connection string
        return $db;
}

//function added by Vibhor for tracking bounce emails
function connect_bouncelog()
{
        $db=@mysql_connect(MysqlDbConstants::$bounceLog[HOST].":".MysqlDbConstants::$bounceLog[PORT],MysqlDbConstants::$bounceLog[USER],MysqlDbConstants::$bounceLog[PASS]);
        @mysql_select_db("bouncelog",$db);
        return $db;
}


function connect_slave81()
{
        $db=@mysql_connect(MysqlDbConstants::$alertsSlave[HOST].":".MysqlDbConstants::$alertsSlave[PORT],MysqlDbConstants::$alertsSlave[USER],MysqlDbConstants::$alertsSlave[PASS]) or die("slave connection failed");
        @mysql_select_db("newjs",$db);
        return $db;
}

function connect_dialer()
{
	$db = mssql_connect(MysqlDbConstants::$dialer[HOST].":".MysqlDbConstants::$dialer[PORT],MysqlDbConstants::$dialer[USER],MysqlDbConstants::$dialer[PASS]);
        return $db;
}

function connect_dnc()
{
	$db_dnc = mysql_connect(MysqlDbConstants::$dnc[HOST].":".MysqlDbConstants::$dnc[PORT],MysqlDbConstants::$dnc[USER],MysqlDbConstants::$dnc[PASS]) or die("Unable to connect to dnc server");
        return $db_dnc;
}

function connect_db4()
{
	$db_viewSimilar = mysql_connect(MysqlDbConstants::$viewSimilar[HOST].":".MysqlDbConstants::$viewSimilar[PORT],MysqlDbConstants::$viewSimilar[USER],MysqlDbConstants::$viewSimilar[PASS]) or die("Unable to connect to viewSimilar server");
        return $db_viewSimilar;
}
function connect_db4_ddl()
{
        $db_viewSimilar = mysql_connect(MysqlDbConstants::$viewSimilarDDL[HOST].":".MysqlDbConstants::$viewSimilarDDL[PORT],MysqlDbConstants::$viewSimilarDDL[USER],MysqlDbConstants::$viewSimilarDDL[PASS]) or die("Unable to connect to viewSimilar server");
        return $db_viewSimilar;
}


// product Slave for master 
function connect_slave111()
{
        $db=@mysql_connect(MysqlDbConstants::$productSlave[HOST].":".MysqlDbConstants::$productSlave[PORT],MysqlDbConstants::$productSlave[USER],MysqlDbConstants::$productSlave[PASS]) or die("111 connection failed");
        @mysql_select_db("newjs",$db);
        return $db;
}

?>
