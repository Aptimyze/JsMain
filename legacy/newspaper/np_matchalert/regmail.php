<?php
                                                                                                                            
/*********************************************************************************************
* FILE NAME     : regmail.php
* DESCRIPTION   : Selects users from PROFILE_BRIEF and sends them mail
* CREATION DATE : 25  May, 2005
* CREATEDED BY  : Shakti Srivastava * Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
/*include("../../jeevansathi/alerts/logerror.php");
include("connect.inc");
$db=@mysql_connect("localhost","root","Km7Iv80l");
$smarty=new Smarty;
*/

include("/home/ops/matchalert/logerror.php");
include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
$smarty=new Smarty;
$smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/templates/np_matchalert";
$SITE_URL="http://www.jeevansathi.com";
$smarty->assign("SITE_URL",$SITE_URL);
                                                                                                                            
$db=@mysql_connect("localhost","user","CLDLRTa9") or logerror1("In matchalerts at connecting db","");
@mysql_select_db("alerts",$db);

$trunc="TRUNCATE TABLE mailer.GGL_MAILER";
$res_trunc=mysql_query($trunc) or logerror1("Error in truncating mailer.GGL_MAILER.".mysql_error(),$trunc,"","");

$sql_unsub="SELECT ID FROM jsadmin.AFFILIATE_MAIN WHERE UNSUBSCRIBE='Y'";
$res_sql_unsub=mysql_query($sql_unsub) or logerror1("Error in selecting ProfileID from affiliate_main. ".mysql_error(),$sql_unsub,"","");
while($row_unsub=mysql_fetch_array($res_sql_unsub))
{
	$unsub[]=$row_unsub['ID'];
}
                                                                                                                            
if(count($unsub)>=1)
{
	$unsub_arr=implode("','",$unsub);
}
else
{
	$unsub_arr="";
}

$sql_pop="INSERT INTO mailer.GGL_MAILER SELECT newjs.PROFILE_BRIEF.ID,newjs.PROFILE_BRIEF.EMAIL,'N' FROM newjs.PROFILE_BRIEF LEFT JOIN newjs.JPROFILE ON (newjs.PROFILE_BRIEF.EMAIL = newjs.JPROFILE.EMAIL) WHERE newjs.JPROFILE.EMAIL IS NULL";
if($unsub_arr!="")
{
	$sql_pop.=" AND newjs.PROFILE_BRIEF.ID NOT IN ('$unsub_arr')";
}
$res_pop=mysql_query($sql_pop) or logerror1("Error in inserting into mailer.GGL_MAILER. ".mysql_error(),$sql_pop,"","");

$sql_sel="SELECT EMAIL FROM mailer.GGL_MAILER WHERE SENT<>'Y'";
$res_sql_sel=mysql_query($sql_sel) or logerror1("Error in selecting and mailing. ".mysql_error(),$sql_sel,"","");
while($row_sql=mysql_fetch_array($res_sql_sel))
{
	$email=$row_sql['EMAIL'];
	$smarty->assign("email",$email);
	$msg1 = $smarty->fetch("google_mailer.htm");
	$srch="href=\"";
        $repl="href=\"http://www.jeevansathi.com/g_redirect.php?source=afl_google&url=";
        $msg=str_replace($srch,$repl,$msg1);
	$subject="Your perfect Match at Jeevansathi.com";
	$from="info@jeevansathi.com";
	if($email)
	{
               	send_email($email,$msg,$subject,$from);
		$updt="UPDATE mailer.GGL_MAILER SET SENT='Y' WHERE EMAIL='$email'";
		$res_updt=mysql_query($updt) or logerror1("Error in updating mailer.GGL_MAILER. ".mysql_error(),$updt,"","");
	}
}
unset($unsub);
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
                                                                                                                            
?>
