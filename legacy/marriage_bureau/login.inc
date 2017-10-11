<?php
function check_username_mb($username)
{
	$username_flag=0;
	if( trim($username)=="" || strlen($username)<2 || strlen($username)>20)
	{
		$username_flag=1; //return 1 if Username is blank or not in 2-20 range
		return $username_flag;
	}
	else
        {
                $result = mysql_query_decide("SELECT USERNAME FROM BUREAU_PROFILE where USERNAME='$username'") or logError("error",$sql);
                if(mysql_num_rows($result) > 0)
                        return 2;// return 2 if username already exists
        }
}

function loginmb($username,$password)
{
	global $TOUT1,$TOUT2,$smarty;

//	setcookie("JS_AUTOLOGIN","",0,"/");
	$username=addslashes(stripslashes($username));
	$password=addslashes(stripslashes($password));
	
	$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,ACTIVATED from BUREAU_PROFILE where USERNAME='$username' and PASSWORD='$password' and ACTIVATED<>'D'";
	echo $sql;
	$result=@mysql_query_decide($sql) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result) <= 0)
	{
		$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,ACTIVATED from BUREAU_PROFILE where EMAIL='$username' and PASSWORD='$password' and ACTIVATED<>'D'";
		$result=@mysql_query_decide($sql) or die(mysql_error_js());//or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result) <= 0)
			return NULL;
	}
	$myrow=mysql_fetch_array($result);
	$data["PROFILEID"]=$myrow["PROFILEID"];
	$data["USERNAME"]=$myrow["USERNAME"];
	$data["ACTIVATED"]=$myrow["ACTIVATED"];
	$userID=$data["PROFILEID"];
	$today=date("Y-m-d");
	$data["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
	$tm=time();
	$sql="delete from MB_CONNECT where TIME1 < ($tm - $TOUT2)";
	mysql_query_decide($sql);
	$sql="insert into MB_CONNECT(ID,USERNAME,PASSWORD,PROFILEID,SUBSCRIPTION,TIME1,ACTIVATED) values('','".addslashes(stripslashes($data["USERNAME"]))."','$password','" . $data["PROFILEID"] . "','" . $data["SUBSCRIPTION"] . "','$tm','" . $data["ACTIVATED"] . "')";
	@mysql_query_decide($sql) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$id=mysql_insert_id_js();
	$checksum=md5($id) . "i" . $id;
	$data["CHECKSUM"]=$checksum;
	$sql="insert ignore into MBLOGIN_HISTORY(PROFILEID,LOGIN_DT) values ('" . $data["PROFILEID"] . "',now())";
	@mysql_query_decide($sql);
	
	$sql="update BUREAU_PROFILE set LAST_LOGIN_DT=now(),SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>SORT_DT,DATE_SUB(NOW(),INTERVAL 7 DAY),SORT_DT) where PROFILEID='" . $data["PROFILEID"] . "'";
	@mysql_query_decide($sql) or die(mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	// set a cookie with the name JSLOGIN that stores the checksum and expires when the session ends and is available on the entire domain
	
	setcookie("JSLOGIN",$checksum,0,"/","");
	$smarty->assign("MBUSERNAME",$data["USERNAME"]);
	$smarty->assign("MBCURRENTACTIVATED",$data["ACTIVATED"]);
	$smarty->assign("MBMYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);
	return $data;
}
?>
