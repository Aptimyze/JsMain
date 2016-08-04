<?php

if(!$_SERVER['DOCUMENT_ROOT'])
                $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
$path=$_SERVER['DOCUMENT_ROOT'];
include("../profile/connect_functions.inc");
include("../profile/connect_db.php");

include ("$path/smarty/Smarty.class.php");

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
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
                        else
                                return mysql_query($sql,$active_db);
                }
        }
}

function logError($sql="",$db="")
{
        LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception(mysql_error()));
        if(!$db)
                global $db;

        $today=date("Y-m-d h:m:s");

	error_log("\n\nQuery : $sql \t Error : " .mysql_error(). " \t $today",3,JsConstants::$docRoot."/sugarcrm/logerror.txt");
}

function logTime()
{
        global $pagename;
        $pagename=$_SERVER['PHP_SELF'];
        $errorstring="\n" . date("Y-m-d G:i:s",time()).",$pagename\n";
	error_log($errorstring,3,JsConstants::$docRoot."/sugarcrm/logtime.txt");
}

$smarty = new Smarty;
$smarty->template_dir=JsConstants::$docRoot."/sugarcrm/templates";

$SITE_URL=JsConstants::$siteUrl;
$smarty->assign("SITE_URL",$SITE_URL);

?>
