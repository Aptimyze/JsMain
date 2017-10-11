<?php

function connect_newspromlocaldb()
{
        $db=@mysql_connect("198.168.2.220","root","Km7Iv80l") or die("Couldnt connect to the local database");
        @mysql_select_db("jsadmin",$db);
        return $db;
}
                                                                                                                             
function connect_newspromservdb()
{
        $db=@mysql_connect("192.168.2.206","user","Km7Iv80l") or die("Can't connect to the server database.".mysql_error());
        @mysql_select_db("jsadmin",$db);
        return $db;
}


/**************************************************************************************************************************** 	This function is to check the uniqueness of the emailid provided.It checks table OLD_EMAIL,JPROFILE and MAILER_TEST for
   the existence of the emailid.
****************************************************************************************************************************/

function checkoldemail($email)     
{
        $flag=0;
        if (trim($email)!='')
        {
                $result = mysql_query("SELECT OLD_EMAIL FROM newjs.OLDEMAIL where OLD_EMAIL='$email'") or logError("error",$sql);
                $myrow = mysql_fetch_array($result);
                $email1=$myrow["OLD_EMAIL"];
                if($email1==$email)
                        $flag=1;

		$result = mysql_query("SELECT EMAIL FROM newjs.JPROFILE where EMAIL='$email'") or logError("error",$sql);
                $myrow = mysql_fetch_array($result);
                $profemail=$myrow["EMAIL"];
                if($profemail==$email)
                        $flag=2;
	
		$result = mysql_query("SELECT EMAIL FROM jsadmin.MAILER_TEST where EMAIL='$email'") or logError("error",$sql);
                $myrow = mysql_fetch_array($result);
                $maileremail=$myrow["EMAIL"];
                if($maileremail==$email)
                        $flag=3;

        }
        else
        {
                $flag=0;
        }
        return $flag;
}


/***************************************************************************************************************************
			This function sends an email to a particular recipient
****************************************************************************************************************************/

function send_email($to,$msg="")
{
        $boundry = "b".md5(uniqid(time()));
        $announce_subject = "WE ARE INTERESTED !!! ";
        $announce_from_email = "info@jeevansathi.com";
        $announce_to_email = $to;
        $MP = "/usr/sbin/sendmail -t";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -f $announce_from_email";
        }
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $announce_to_email\n");
        fputs($fd, "From: $announce_from_email \n");
        fputs($fd, "Subject: $announce_subject \n");
        fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "$msg\r\n");
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}


/****************************************************************************************************************************
	This functions reports any error encountered while sending the email to a recipient
****************************************************************************************************************************/

function sendlogerror($message,$query="", $sendmailto="NO")
{
        global $_SERVER['HTTP_REFERER'],$_SERVER,$_SERVER['HTTP_USER_AGENT'], $db, $smarty, $checksum;

        if(mysql_error())
        {
                $sendmailto="shobha.kumari@jeevansathi.com";
        }
                                                                                                                             
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();
                                                                                                                             
        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        
        $errorstring.="\" >> logerror.txt";
                                                                                                                             
        passthru($errorstring);
        $errorstring.="\n#Details : $ret_val";
        if($sendmailto!="NO")
                $b=mail($sendmailto,"ERROR in sendemail", $errorstring);
                                                                                                                             
}

?>





