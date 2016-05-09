<?php
function login4mb($username,$password)
{
	global $TOUT1,$TOUT2,$smarty;

	setcookie("JS_AUTOLOGIN","",0,"/");
	left_panel_search();	
	$username=addslashes(stripslashes($username));
	$password=addslashes(stripslashes($password));
	
	$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED from JPROFILE where USERNAME='$username' and PASSWORD='$password' and ACTIVATED<>'D'";
	$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) <= 0)
	{
		$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED from JPROFILE where EMAIL='$username' and PASSWORD='$password' and ACTIVATED<>'D'";
		$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		if(mysql_num_rows($result) <= 0)
			return NULL;
	}
		
	$myrow=mysql_fetch_array($result);
	
	$data["PROFILEID"]=$myrow["PROFILEID"];
	$data["USERNAME"]=$myrow["USERNAME"];
	$data["GENDER"]=$myrow["GENDER"];
	$data["ACTIVATED"]=$myrow["ACTIVATED"];
	
	$userID=$data["PROFILEID"];
	customisedusername($data["PROFILEID"]);
	
	//$sql="replace into userplane.users(userID,LastTimeOnline,displayName) values ('$userID',now(),'" . addslashes($data["USERNAME"]) . "')";
	//mysql_query_decide($sql);
	
	$today=date("Y-m-d");
	
	$data["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
		
	$tm=time();
	
	$sql="delete from CONNECT where TIME1 < ($tm - $TOUT2)";
	mysql_query_decide($sql);
	
	$sql="insert into CONNECT(ID,USERNAME,PASSWORD,PROFILEID,SUBSCRIPTION,TIME1,GENDER,ACTIVATED) values ('','".addslashes(stripslashes($data["USERNAME"]))."','$password','" . $data["PROFILEID"] . "','" . $data["SUBSCRIPTION"] . "','$tm','" . $data["GENDER"] . "','" . $data["ACTIVATED"] . "')";
	
	@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	$id=mysql_insert_id_js();
	$checksum=md5($id) . "i" . $id;
	
	$data["CHECKSUM"]=$checksum;

	$sql="insert ignore into LOGIN_HISTORY(PROFILEID,LOGIN_DT) values ('" . $data["PROFILEID"] . "',now())";
	@mysql_query_decide($sql);
	
	$sql="update JPROFILE set LAST_LOGIN_DT=now(),SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>SORT_DT,DATE_SUB(NOW(),INTERVAL 7 DAY),SORT_DT) where PROFILEID='" . $data["PROFILEID"] . "'";
	@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	// set a cookie with the name JSLOGIN that stores the checksum and expires when the session ends and is available on the entire domain
	
	setcookie("JSLOGIN",$checksum,0,"/","");
	setcookie("CHATUSERNAME",$data["USERNAME"],time()+300,"/");
	setcookie("CHATPROFILEID",$data["PROFILEID"],time()+300,"/");

	login_relogin_auth($data);
	
	$smarty->assign("CURRENTUSERNAME",$data["USERNAME"]);
	$smarty->assign("CURRENTGENDER",$data["GENDER"]);
	$smarty->assign("CURRENTACTIVATED",$data["ACTIVATED"]);
	$smarty->assign("MYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);
	
	return $data;
}
