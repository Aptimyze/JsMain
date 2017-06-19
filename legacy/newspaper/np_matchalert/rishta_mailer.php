<?php
                                                                                                                             
/*********************************************************************************************
* FILE NAME     : rishta_mailer.php 
* DESCRIPTION   : Selects email of Rishta.com user and sends mail
* CREATION DATE : 24 june, 2005
* CREATED BY    : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include "../matchalert/logerror.php";
include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
$smarty=new Smarty;
$smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/templates/rishta";
$SITE_URL="http://www.jeevansathi.com";
$smarty->assign("SITE_URL",$SITE_URL);
                                                                                
                                                                                
$db=@mysql_connect("localhost","user","CLDLRTa9") or logerror1("In rishta at connecting db","");
@mysql_select_db("MMM",$db);


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
	$sql_res="select EMAIL from MMM.RISHTA_EMAILS";
        $result= mysql_query($sql_res) or logerror1("In rishta at selecting email","");
//        $msg=$smarty->fetch("rishta_mailer.htm");
	$subject="Who will you marry ? Find out now";
	$from="info@jeevansathi.com";
	while($row=mysql_fetch_array($result))
        {
                $email=addslashes($row["EMAIL"]);
		$smarty->assign("email",$email);
		$msg=$smarty->fetch("rishta_mailer.htm");
		send_email($email,$msg,$subject,$from);
		$sql="update MMM.RISHTA_EMAILS set SENT='Y' where EMAIL='$email'";
		 mysql_query($sql) or logerror1("In rishta at setting SENT var","");
	}
		

?>




