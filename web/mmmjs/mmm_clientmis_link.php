<?
include("connect.inc");
/**** THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE**********************/
                                                                                                 
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
                                                                                                 
$smarty->assign("cid",$cid);
                                                                                                 
/****************************AUTHENTICATION ROUTINE ENDS HERE*********************************/

if($submit)
{
	$unique_id=md5($mailer_id)."i".$mailer_id;
	$unique_url="http://ser2.jeevansathi.com/mmmjs/mmm_client_mis.php?mailer_id=$mailer_id&unique_id=$unique_id";
	$smarty->assign("flag","1");
	$smarty->assign("unique_url",$unique_url);
	$smarty->assign("mailer_id",$mailer_id);
	$smarty->display("mmm_clientmis_link.htm");
	$smarty->assign("cid",$cid);
}
else
{
	$smarty->display("mmm_clientmis_link.htm");
}



?>
