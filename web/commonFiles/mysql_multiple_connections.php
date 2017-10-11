<?php
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
if(!function_exists('db_set_active'))
{
	function db_set_active($name = 'master', $dburl,$dbuser,$dbpassword)
	{
		global $active_db;
		global $previous_db;
		static $db_conns;

		if (!isset($db_conns[$name]))
		{
			if($name=='slave81')
				$db_conns[$name] = mysql_connect($dburl,$dbuser,$dbpassword);
			else
				$db_conns[$name] =@mysql_connect($dburl,$dbuser,$dbpassword) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES");

			if(php_sapi_name()=="cli")
                                mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db_conns[$name]);
		}
		$previous_db = $active_db;
		//Set the active connection.
		$active_db = $db_conns[$name];

		return $active_db;
		//return array_search($previous_db, $db_conns);
	}
}

if(!function_exists('mysql_query_decide'))
{
	function mysql_query_decide($sql,$db="",$divert="")
	{
		global $active_db,$previous_db,$db_211,$db_ser4;

                if($divert)
                {
                        if($divert==2)
                        {
                                if($db_ser4 == $active_db)
                                        $db_ser4 = $active_db;
                                else
                                        $db_ser4=connect_737_ro();
                                $active_db = $previous_db;
                                return mysql_query($sql,$db_ser4);
                        }
                        else
                        {
                                if($db_211 == $active_db)
                                        $db_211 = $active_db;
                                else
                                        $db_211 = connect_211();

                                $active_db = $previous_db;
                                return mysql_query($sql,$db_211);
                        }
                }
		else
		{
			if($db != "")
				return mysql_query($sql,$db);
			else
				return mysql_query($sql,$active_db);
		}
	}
}

if(!function_exists('mysql_insert_id_js'))
{
	function mysql_insert_id_js()
	{
		global $active_db;
		return @mysql_insert_id($active_db);
	}
}

if(!function_exists('mysql_affected_rows_js'))
{
	function mysql_affected_rows_js()
	{
		global $active_db;
		return @mysql_affected_rows($active_db);
	}
}

if(!function_exists('mysql_error_js'))
{
	function mysql_error_js()
	{
		global $active_db;
		LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception(@mysql_error($active_db)));
		return @mysql_error($active_db);
	}
}

	function mysql_ping_js()
	{
		global $active_db;
		return @mysql_ping($active_db);
	}

	function mysql_errno_js()
	{
		global $active_db;
		return @mysql_errno($active_db);
	}

	function mysql_select_db_js($database,$db="")
	{
		global $active_db;
		if($db)
			return @mysql_select_db($database,$db);
		else
			return @mysql_select_db($database,$active_db);
	}
?>
