<?php
function logerror1($message,$query="", $sendmailto="NO",$flag="N")
{
        global $db, $smarty, $checksum;
        if(mysql_error())
	{
//		$sendmailto="shiv.narayan@naukri.com";
                $sendmailto="abhinav.katiyar@jeevansathi.com";
//		$sendmailto = "rahul.tara@jeevansathi.com";
	}

        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();
                                                                                                 
        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        // for 120
        $errorstring.="\" >> /home/ops/np_matchalert/logerror.txt";
                                                                                                 
        passthru($errorstring);
        $errorstring.="\n#Details : $ret_val";
    	if($sendmailto=="NO")
                $b=mail($sendmailto,"ERROR in matchalert", $errorstring);
	if($flag=="Y")
		die($message);
}
?>
