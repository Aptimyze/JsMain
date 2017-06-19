<?php
/*function dbsql2_connect()
{
	$db = @mysql_connect("localhost:/tmp/mysql2.sock","root","Km7Iv80l") or die("Can't connect to Database".mysql_error());
        mysql_select_db("jsadmin",$db) or die(mysql_error());
        return $db;
}

/*function db_connect()
{
                                                                                                                             
        $db = @mysql_connect("localhost","root","Km7Iv80l") or die("Can't connect to Database".mysql_error());
        mysql_select_db("jsadmin",$db) or die(mysql_error());
        return $db;
}*/
                                                                                                                           

/**************************************************************************************************************************** 	This function is to check the uniqueness of the emailid provided.It checks table OLD_EMAIL,JPROFILE and MAILER_TEST for
   the existence of the emailid.
****************************************************************************************************************************/

function checkoldemail($email)     
{
	
        $flag=0;
        if (trim($email)!='')
        {
		$db = dbsql2_connect();
		$result = mysql_query("SELECT EMAIL FROM jsadmin.AFFILIATE_MAIN where EMAIL='$email'",$db) or logError("error",$sql);
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


function checkphone($phone)
{
        $flag=0;
        if (trim($phone)!='' && is_numeric($phone))
        {
                $db = dbsql2_connect();
                $result = mysql_query("SELECT PHONE_MOB FROM jsadmin.AFFILIATE_DATA where PHONE_MOB='$phone'",$db) or logError("error",$sql);                 
		$myrow = mysql_fetch_array($result);
                $mailerphone=$myrow["PHONE_MOB"];
                if($mailerphone==$phone)
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
function assign_rand_value($num)
{
	// accepts 1 - 36
	switch($num)
	{
		case "1":
		$rand_value = "a";
		break;
		case "2":
		$rand_value = "b";
		break;
		case "3":
		$rand_value = "c";
		break;
		case "4":
		$rand_value = "d";
		break;
		case "5":
		$rand_value = "e";
		break;
		case "6":
		$rand_value = "f";
		break;
		case "7":
		$rand_value = "g";
		break;
		case "8":
		$rand_value = "h";
		break;
		case "9":
		$rand_value = "i";
		break;
		case "10":
		$rand_value = "j";
		break;
		case "11":
		$rand_value = "k";
		break;
		       case "12":
		$rand_value = "l";
		break;
		case "13":
		$rand_value = "m";
		break;
		case "14":
		$rand_value = "n";
		break;
		case "15":
		$rand_value = "o";
		break;
		case "16":
		$rand_value = "p";
		break;
		case "17":
		$rand_value = "q";
		break;
		case "18":
		$rand_value = "r";
		break;
		case "19":
		$rand_value = "s";
		break;
		case "20":
		$rand_value = "t";
		break;
		case "21":
		$rand_value = "u";
		break;
		case "22":
		$rand_value = "v";
		break;
		case "23":
		$rand_value = "w";
		break;
		case "24":
		$rand_value = "x";
		break;
		case "25":
		$rand_value = "y";
		break;
		case "26":
		$rand_value = "z";
		break;
		case "27":
		$rand_value = "0";
		break;
		case "28":
		$rand_value = "1";
		break;
		case "29":
		$rand_value = "2";
		break;
		case "30":
		$rand_value = "3";
		break;
		case "31":
		$rand_value = "4";
		break;
		case "32":
		$rand_value = "5";
		break;
		case "33":
		$rand_value = "6";
		break;
		case "34":
		$rand_value = "7";
		break;
		case "35":
		$rand_value = "8";
		break;
		case "36":
		$rand_value = "9";
		break;
	}
	return $rand_value;
}
function email_gen($length)
{
  	if($length>0)
  	{
  		$rand_id="";
   		for($i=1; $i<=$length; $i++)
   		{
   			mt_srand((double)microtime() * 1000000);
   			$num = mt_rand(1,36);
   			$rand_id .= assign_rand_value($num);
   		}
	}
	return $rand_id;
}
?>
