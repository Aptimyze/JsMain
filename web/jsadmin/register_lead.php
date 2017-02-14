<?php

include("connect.inc");
$path2=dirname(__FILE__);
$path2=realpath($path2."/..");
include_once($path2."/sugarcrm/include/utils/Jsutils.php");
include_once($path2."/sugarcrm/custom/crons/housekeepingConfig.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($submit){
		if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'http://crm.jeevansathi.com'){
			$machineURL = 'http://www.jeevansathi.com';
		} else {
			$machineURL = JsConstants::$siteUrl;
		}
		$dbSlave = connect_slave();
		$db = connect_db();
		$smarty->assign('machineURL',$machineURL);
		$id=mysql_escape_string(trim($lead_id));
		$user_name=getuser($cid);
		$sql="select id from sugarcrm.users where user_name='$user_name'";
		$res=mysql_query_decide($sql,$dbSlave) or die("Problem");
		if(mysql_num_rows($res)<1)
			$err_code=1;
		$row1=mysql_fetch_assoc($res);
		$user_id=$row1['id'];
		mysql_free_result($res);
		if(!$err_code){
		$sql="select status,js_source_c,source_c,campaign_id from sugarcrm.leads,sugarcrm.leads_cstm where id=id_c and id='$id'";
		$res=mysql_query_decide($sql,$dbSlave) or die("Problem somewhere");
		$row=mysql_fetch_assoc($res);
		if(mysql_num_rows($res)<1)
			$err_code=2;
		if($err_code==2){
			$sql="select status,js_source_c,source_c,campaign_id from sugarcrm_housekeeping.inactive_leads,sugarcrm_housekeeping.inactive_leads_cstm where id=id_c and id='$id'";
			$res=mysql_query_decide($sql,$dbSlave) or die("Problem somewhere");
			$row=mysql_fetch_assoc($res);
			if(mysql_num_rows($res)<1)
				$err_code=2;
			else{
				moveLead($id,'inactive','active',$db);
				$err_code=0;
			}
			}
		$status=$row['status'];
		if($status==26)
			$err_code=3;
		}
		if($err_code)
			$smarty->assign("err_code",$err_code);
		else{
			$now=date('Y-m-d H:i:s');
			$sql="update sugarcrm.leads set assigned_user_id='$user_id',date_modified='$now' where id='$id'";
			mysql_query_decide($sql,$db) or die("Problem somewhere");
			if(!$row['js_source_c'])
				$js_source=fetchLeadSource($row['source_c'],$row['campaign_id'],$dbSlave);
			else
				$js_source=$row['js_source_c'];
			if($status==24)
				$reg_url="/P/sugarcrm_registration/registration_page1.php?record_id=$id&from_sugar_exec=Y&sugar_incomplete=Y&source=$js_source&secondary_source=C";
			else
				$reg_url="/P/sugarcrm_registration/registration_page1.php?record_id=$id&from_sugar_exec=Y&source=$js_source&secondary_source=C";
      
    //New logout
    $authWeb = new WebAuthentication();
    $authWeb->removeLoginCookies();
    
			$smarty->assign("reg_url",$reg_url);
			$smarty->display("register_lead_final.htm");die;
		}
	}
			$smarty->display("register_lead.htm");
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->assign("user",$user);
	$smarty->display("jsadmin_msg.tpl");
}
