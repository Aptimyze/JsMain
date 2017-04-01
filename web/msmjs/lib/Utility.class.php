<?php
//class Utility
//{
	include_once "config/config.php";
        // include wrapper for logging
        include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");

	/***********log error function**********************/
        function logError($message,$query="",$critical="exit", $sendmailto="NO")
        {
                global $db, $smarty, $checksum;
                ob_start();
                var_dump($_SERVER);
                $ret_val = ob_get_contents();
                ob_end_clean();

                $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
                // for 120                
		$errorstring.="\" >> ".JsConstants::$alertDocRoot."/jsadmin/temp/logerror.txt";
        
                //for resman
                //$errorstring.="\" >> /usr/local/apache/sites/site2/manager/logs/resmgr.err
                passthru($errorstring);
                $errorstring.="\n#Details : $ret_val";
                LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorstring));
                if($sendmailto!="NO")
                        $b=mail($sendmailto,"ERROR in new resman connect", $errorstring);

                if($critical=="exit")
                {
                        echo $message;
                        exit;
                }
                elseif($critical=="ShowErrTemplate")
                {
                        $smarty->assign("CHECKSUM",$checksum);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

                        $smarty->assign("msg_error", $message);
                        $smarty->display("error_template.htm");
                        exit;
                }
                elseif($critical!="continue")
                {
                        echo $message;
                }
        }
	/**************************Ends here***********************/
//}
?>
