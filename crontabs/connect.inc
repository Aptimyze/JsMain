<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once("/usr/local/scripts/connect_crontabsDb.php");
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
//Added By lavesh
if($flag_using_php5)
{
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
}
//Added By lavesh

if(!function_exists('mysql_query_decide'))
{
        function mysql_query_decide($sql,$db="",$divert="")
        {
                global $active_db,$previous_db;
                if($divert)
                {
                        if($db_211 == $active_db)
                                $db_211 = $active_db;
                        else
                                $db_211 = connect_211();

                        $active_db = $previous_db;
                        return mysql_query($sql,$db_211);
                }
                else
                {
                        if($db != "")
                                return mysql_query($sql,$db);
                        elseif($active_db!="")
                                return mysql_query($sql,$active_db);
			else
			{
				global $db;
				if($db)
					return mysql_query($sql,$db);
				else
					return mysql_query($sql);
			}
                }
        }
}
if(!function_exists('mysql_affected_rows_js'))
{
	function mysql_affected_rows_js()
	{
		global $db,$active_db;
		if($active_db!="")
			return mysql_affected_rows($active_db);
		elseif($db)
			return mysql_affected_rows($db);
		else
			return mysql_affected_rows();
	}
}
if(!function_exists('mysql_error_js'))
{
        function mysql_error_js()
        {
                global $active_db,$db;
		if($active_db!="")
			$errorMessage = mysql_error($active_db);
                elseif($db)
                        $errorMessage = mysql_error($db);
                else    
                        $errorMessage = mysql_error();
        LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorMessage));
        return $errorMessage;
        }
}



function logError($sql="",$db="")
{
	if(!$db)
		global $db;
	global $doc_root;
	$today=date("Y-m-d h:m:s");
	//mail("vikas@jeevansathi.com","Error occurred in Database : $sql",mysql_error($db));
	$filename=$doc_root."/crontabs/logerror.txt";
	if(is_writable($filename))
	{
		if (!$handle = fopen($filename, 'a'))
                {
                        echo "Cannot open file ($filename)";
                        exit;
                }
		fwrite($handle,"\n\nQuery : $sql \t Error : " .mysql_error(). " \t $today");
		fclose($handle);
	}
        else
        {
                echo "The file $filename is not writable";
        }
}

function logTime()
{
	global $pagename,$docRoot;
	$pagename=$_SERVER['PHP_SELF'];
	$errorstring="echo \"" . date("Y-m-d G:i:s",time()).",$pagename";
	$filename=$docRoot."/crontabs/logtime.txt";
	$errorstring.="\" >> $filename";
	passthru($errorstring);
}

?>
