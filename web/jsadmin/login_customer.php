<?php
include("connect.inc");

function login_customer($oc_uid,$oc_passwd,$cid)
{

	$profile_sql= "SELECT PROFILEID,PASSWORD FROM newjs.JPROFILE WHERE USERNAME= '$oc_uid' AND SOURCE LIKE 'ofl_prof' AND ACTIVATED='Y'";
	$profile_res= mysql_query_decide($profile_sql) or die(mysql_error_js());
	$profile_row= mysql_fetch_array($profile_res);
	$profile= $profile_row['PROFILEID'];
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	if($profile && PasswordHashFunctions::validatePassword($oc_passwd, $profile_row['PASSWORD']))
	{
		$sql= "Select ACTIVE from jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profile' ORDER BY ENTRY_DATE DESC LIMIT 1";
		$result= mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if(mysql_num_rows($result)>0)
		{
			$row=mysql_fetch_assoc($result);
			$active= $row["ACTIVE"];
			if($active== 'N')
			{
				header("Location:".$SITE_URL."/jsadmin/accept_matches.php?profileid=$profile&cid=$cid&p_expire=Y");
			}
			else
			{
				$sql="UPDATE jsadmin.OFFLINE_ASSIGNED SET LAST_LOGIN_DATE = now() WHERE PROFILEID='".$profile."'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				return($profile);
			}
		}
		else
			return(-1);
	}
	else
		return(0);
	
}
$data= authenticated($cid);
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($submit)
	{
		
		$profile= login_customer($oc_uid,$oc_passwd,$cid);
		$profile;
		if($profile == '-1')
                {
                        $msg= "Billing has to be done for logging Offline Customer";
                        $smarty->assign("msg",$msg);
                        $smarty->display('login_customer.tpl');
                }
		elseif($profile)
		{		
			comp_info($profile);
			$smarty->assign("profileid",$profile);
			$smarty->display("logged_in_home.html");	
		}
		else
		{
			$msg= "Either UserId or Password is incorrect!!";
			$smarty->assign("msg",$msg);
			$smarty->display('login_customer.tpl');
		}
	}
	elseif($flag==1)
	{
		comp_info($profileid);
		$flag=0;	
		$smarty->assign("profileid",$profileid);
		$smarty->display("logged_in_home.html");	
	}
	else
	{
		$smarty->display('login_customer.tpl');
	}

}
else
{
	 $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>

