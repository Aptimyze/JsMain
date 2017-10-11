<?php
//it starts zipping
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)   
	ob_start("ob_gzhandler");

//end of it
include ("connect.inc");
include ("time.php");
$ip = FetchClientIP();

// GET parameters: username, profileid, auto
if($username =='' || $profileid =='' || $auto =='')
	$autoLoginErr='1';

// get the password
$sql = "select `PASSWORD` from jsadmin.PSWRDS where USERNAME= binary '$username' AND ACTIVE='Y'";
$res = mysql_query_decide($sql) or die("Could not Execute query pwdError");
$count = mysql_num_rows($res);
if ($count > 0){
	$myrow = mysql_fetch_array($res);
	$password= $myrow["PASSWORD"];
}
else
	$autoLoginErr ='1';

$connection = login($username, $password);
if( ($connection && $connection!="-1") || $autoLoginErr)//successful login
{
	if($auto =='shortlist')	
		header("Location: $SITE_URL/jsadmin/ap_list.php?name=$username&cid=$connection&profileid=$profileid&list=SL");		
	else if($auto =='dpp')
		header("Location: $SITE_URL/jsadmin/ap_dpp.php?cid=$connection&profile=$profileid");
	else if($auto =='profile')
		header("Location: $SITE_URL/jsadmin/ap_viewprofile.php?cid=$connection&profileid=$profileid&list=MYPROFILE");
        else if($auto =='contact_center')
                header("Location: $SITE_URL/jsadmin/ap_profile_extra_form.php?cid=$connection&profileid=$profileid&list=CALLERS");
}
else//login failed
{
	if($connection=="-1")
		$smarty->assign("EXPIRE","Y");
	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();
?>
