<?php
header("Location:$SITE_URL/crm");
//it starts zipping
/*$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)   
	ob_start("ob_gzhandler");*/

//end of it
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
//include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
/*include ("../jsadmin/time.php");
$ip = FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}
$connection = login($username, $password);
if($connection && $connection!="-1")//successful login
{
		setLoginCookies($connection,$username);

		if($from_dialer=='Y')
			header("Location: $SITE_URL/crm/dialer_outbound_handler.php?profileid=$profileid&agent_name=$agent_name&campaign_name=$campaign_name&from_dialer=$from_dialer");
		elseif($from_dialer_inbound=='Y')
			header("Location: $SITE_URL/crm/dialer_inbound_handler.php?phone=$phone&agent_name=$agent_name&campaign_name=$campaign_name&from_dialer_inbound=$from_dialer_inbound");
		elseif($from_dialer_phone=='Y')
			header("Location: $SITE_URL/crm/phoneVerifyDialer.php?from_dialer_phone=Y&profileid=$profileid");
		else
			header("Location: $SITE_URL/crm/mainpage.php?name=$user&cid=$connection");		
}
else//login failed
{
	if($connection=="-1")
		$smarty->assign("EXPIRE","Y");
	$smarty->assign("username","$username");
	$profileid = $_REQUEST["profileid"];
	$agent_name = $_REQUEST["agent_name"];
	$campaign_name = $_REQUEST["campaign_name"];
	$from_dialer = $_REQUEST["from_dialer"];
	$from_dialer_phone = $_REQUEST["from_dialer_phone"];
	$from_dialer_inbound = $_REQUEST["from_dialer_inbound"];
	$phone = $_REQUEST["phone"];
	$smarty->assign("profileid","$profileid");
	$smarty->assign("agent_name","$agent_name");
	$smarty->assign("campaign_name","$campaign_name");
	$smarty->assign("from_dialer","$from_dialer");
	$smarty->assign("from_dialer_phone","$from_dialer_phone");
	$smarty->assign("from_dialer_inbound",$from_dialer_inbound);
	$smarty->assign("phone",$phone);
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();*/
?>
