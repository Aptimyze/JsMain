<?php
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

$db = connect_slave();
$db2 = connect_db();
$mysql=new Mysql;

if(authenticated($cid))
{
	$name = getname($cid);
        $privilage = explode("+",getprivilage($cid));
	$flag =false;
        if(in_array("AdSlEx",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage))
		$flag =true;

	if($username && $flag){
		$sql = "select PROFILEID, PHONE_MOB, PHONE_WITH_STD, EMAIL, ENTRY_DT, MOD_DT, DATE(LAST_LOGIN_DT) LAST_LOGIN_DT from newjs.JPROFILE where USERNAME='$username'";
		$result = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($result))
		{
			$profileid	=$row['PROFILEID'];
			$mobile1	=$row['PHONE_MOB'];
			$landline 	=$row['PHONE_WITH_STD'];
			$email 		=$row['EMAIL'];
			$reg_dt 	=date("d-M-Y H:i:s",JSstrToTime("$row[ENTRY_DT]")); 
			$mod_dt 	=date("d-M-Y H:i:s",JSstrToTime("$row[MOD_DT]"));
			$lastLogin_dt 	=date("d-M-Y",JSstrToTime("$row[LAST_LOGIN_DT]"));

			$sql1 ="select ALT_MOBILE,ALT_MOBILE_ISD from newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid'";
			$result1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());	
			$row1=mysql_fetch_array($result1);
			$mobile2 =$row1['ALT_MOBILE'];
			if($mobile2 && $row1['ALT_MOBILE_ISD'])
				$mobile2 =$row1['ALT_MOBILE_ISD']."-".$mobile2;

	                // query to find the number of times user has logged on to the site in past 90 days
        	        $myDbName=getProfileDatabaseConnectionName($profileid);
        	        $myDb=$mysql->connect("$myDbName");
        	       	$sql2 = "select IPADDR from newjs.LOG_LOGIN_HISTORY where PROFILEID='$profileid' ORDER BY TIME DESC LIMIT 1";
        	        $res2=$mysql->executeQuery($sql2,$myDb);
        	        $row2=$mysql->fetchArray($res2);
        	        $ipAddress =$row2['IPADDR'];
		}
	}
	else
		$smarty->assign('NO_RECORD','Y');
	
	$smarty->assign("username","$username");	
	$smarty->assign("mobile1","$mobile1");

	$smarty->assign("landline","$landline");
	$smarty->assign("mobile2","$mobile2");		
	$smarty->assign("email","$email");
	$smarty->assign("reg_dt","$reg_dt");
	$smarty->assign("mod_dt","$mod_dt");
	$smarty->assign("lastLogin_dt","$lastLogin_dt");
	$smarty->assign("ipAddress","$ipAddress");	

	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->display('mobUserInfo.htm');
}
else
{
	$msg="Your session has been timed out  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}
?>
