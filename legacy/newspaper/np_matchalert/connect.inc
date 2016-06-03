<?php
//include("/usr/local/apache/sites/jeevansathi.com/htdocs/live/promotions/np_matchalert/logerror.php");
include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
$smarty=new Smarty;
$smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/templates/live/promotions/np_matchalert";

//$SITE_URL="http://203.92.39.199/jeevansathi/";
$SITE_URL="http://192.168.2.220/";
$smarty->assign("SITE_URL",$SITE_URL);
                                                                                                                            
$db=@mysql_connect("localhost","root","Km7Iv80l") or logerror1("In matchalerts at connecting db","");
@mysql_select_db("alerts",$db);

function send_email($email,$msg,$subject,$from)
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $email\n");
        fputs($fd, "From: $from \n");
        fputs($fd, "Subject: $subject \n");
        fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "$msg\r\n");
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}
function logerror1($message,$query="", $sendmailto="NO",$flag="N")
{
        global $_SERVER['HTTP_REFERER'],$_SERVER,$_SERVER['HTTP_USER_AGENT'], $db, $smarty, $checksum;
        if(mysql_error())
        {
//              $sendmailto="shiv.narayan@naukri.com";
                $sendmailto="shobha.kumari@jeevansathi.com";
//              $sendmailto = "rahul.tara@jeevansathi.com";
        }
                                                                                                                            
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();
                                                                                                                            
        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        // for 120
//        $errorstring.="\" >> /home/ops/np_matchalert/logerror.txt";
	$errorstring.="\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/live/promotions/logerror.txt";
                                                                                                                            
        passthru($errorstring);
        $errorstring.="\n#Details : $ret_val";
        if($sendmailto=="NO")
                $b=mail($sendmailto,"ERROR in matchalert", $errorstring);
        if($flag=="Y")
                die($message);
}

?>
