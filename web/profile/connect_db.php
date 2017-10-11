<?php
	if(!$active_db)
		$active_db = "";
	if(!$previous_db)
		$previous_db = "";
	if(!$db_211)
		$db_211="";
        if(!$db_ser4)
                $previous_db = "";
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");

if(!function_exists('connect_db')){
	function connect_db($nohtml="")
	{
		if($nohtml==1)
			$db=db_set_active("master",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or die();
		else
			$db=db_set_active("master",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES");
		@mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

		//function will check the status and assign it to a varible named STATUS
		if(function_exists('check_livestatus'))
			check_livestatus();

		return $db;
	}
}

if(!function_exists('connect_ddl')){
	function connect_ddl($nohtml="")
	{
		if($nohtml==1)
			$db=db_set_active("master",MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT],MysqlDbConstants::$masterDDL[USER],MysqlDbConstants::$masterDDL[PASS]) or die();
		else
			$db=db_set_active("master",MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT],MysqlDbConstants::$masterDDL[USER],MysqlDbConstants::$masterDDL[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES");
		@mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

		//function will check the status and assign it to a varible named STATUS
		if(function_exists('check_livestatus'))
			check_livestatus();

		return $db;
	}
}


	function connect_db_ro($nohtml="")
	{
		if($nohtml==1)
			$db=db_set_active("master_ro",MysqlDbConstants::$masterRO[HOST].":".MysqlDbConstants::$masterRO[PORT],MysqlDbConstants::$masterRO[USER],MysqlDbConstants::$masterRO[PASS]) or die();
		else
			$db=db_set_active("master_ro",MysqlDbConstants::$masterRO[HOST].":".MysqlDbConstants::$masterRO[PORT],MysqlDbConstants::$masterRO[USER],MysqlDbConstants::$masterRO[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES");

		@mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

		return $db;
	}
if(!function_exists('connect_slave')){
	function connect_slave()
        {
                $db=db_set_active("slave",MysqlDbConstants::$misSlave[HOST].":".MysqlDbConstants::$misSlave[PORT],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","81");
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
		
        }
}
if(!function_exists('connect_slave81')){
        function connect_slave81()
        {
                $db=db_set_active("slave81",MysqlDbConstants::$alertsSlave[HOST].":".MysqlDbConstants::$alertsSlave[PORT],MysqlDbConstants::$alertsSlave[USER],MysqlDbConstants::$alertsSlave[PASS],MYSQL_CLIENT_COMPRESS);
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}

if(!function_exists('connect_slave_ro')){
	function connect_slave_ro()
        {
		return connect_slave81();
               
                $db=db_set_active("slave_ro",MysqlDbConstants::$alertsSlave[HOST].":".MysqlDbConstants::$alertsSlave[PORT],MysqlDbConstants::$alertsSlave[USER],MysqlDbConstants::$alertsSlave[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","81");
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_db4')){
	function connect_db4()
        {
                $db=db_set_active("db4",MysqlDbConstants::$viewSimilar[HOST].":".MysqlDbConstants::$viewSimilar[PORT],MysqlDbConstants::$viewSimilar[USER],MysqlDbConstants::$viewSimilar[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","db4");
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_db4_ddl')){
	function connect_db4_ddl()
        {
                $db=db_set_active("db4",MysqlDbConstants::$viewSimilarDDL[HOST].":".MysqlDbConstants::$viewSimilarDDL[PORT],MysqlDbConstants::$viewSimilarDDL[USER],MysqlDbConstants::$viewSimilarDDL[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","db4_ddl");
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_737')){
	function connect_737()
        {
		//return connect_db();
                $db=db_set_active("737",MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","737");
		
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_737_ro')){
	function connect_737_ro()
        {
		//return connect_db();
                $db=db_set_active("737_ro",MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","737");
		
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_db2')){
	function connect_db2()
        {
                $db=db_set_active("db2","localhost:/tmp/mysql2.sock","user","CLDLRTa9",MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES");
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_737_lan')){
	function connect_737_lan()
        {
		return connect_db();
                $db=db_set_active("737_lan",MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT],MysqlDbConstants::$bmsSlave[USER],MysqlDbConstants::$bmsSlave[PASS],MYSQL_CLIENT_COMPRESS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","737");
		
                @mysql_select_db_js("newjs",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
}
if(!function_exists('connect_211'))
{
	function connect_211()
	{
		$db2 = db_set_active("211",MysqlDbConstants::$viewLog[HOST].":".MysqlDbConstants::$viewLog[PORT],MysqlDbConstants::$viewLog[USER],MysqlDbConstants::$viewLog[PASS],MYSQL_CLIENT_COMPRESS) or die("Can't connect to Database".mysql_error());
		mysql_select_db_js("newjs",$db2);               // connection string

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db2);

		return $db2;
	}
}
if(!function_exists('connect_303')){
	function connect_303()
	{	
		$db2 = db_set_active("303",MysqlDbConstants::$misSlave[HOST],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS],MYSQL_CLIENT_COMPRESS) or die("Can't connect to Database".mysql_error());
                mysql_select_db_js("newjs",$db2);               // connection string

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db2);

                return $db2;
	}
}
	function connect_openfire()
        {
                $db=db_set_active("openfire",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or die();

                @mysql_select_db_js("openfire",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }

        function connect_userplane()
        {
                $db=db_set_active("userplane",MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or die();

                @mysql_select_db_js("userplane",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);

                return $db;
        }
if(!function_exists('connect_dnc')){
	function connect_dnc()
        {
                $db_dnc =mysql_connect(MysqlDbConstants::$dnc[HOST].":".MysqlDbConstants::$dnc[PORT],MysqlDbConstants::$dnc[USER],MysqlDbConstants::$dnc[PASS]) or die("");
                @mysql_select_db("DNC",$db);

		if(php_sapi_name()=="cli")
                        mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db_dnc);

                return $db_dnc;
        }
}

?>
