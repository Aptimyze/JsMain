<?php
chdir(dirname(__FILE__));
include("../profile/config.php");
include("../profile/connect.inc");
include("include/utils/systemProcessUsersConfig.php");
global $process_user_mapping;

$processUserId=$process_user_mapping["unsubscribe"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

$db=connect_db();
	//$smarty->template_dir="../smarty/templates/jeevansathi";
if($id && $source=='lma')
{
	$sql="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET do_not_email_c=1,modified_user_id='$processUserId',date_modified='$updateTime' WHERE id_c='$id' AND id=id_c";
	$res=mysql_query($sql) or die(mysql_error());
	$msg="You have been successfully unsubscribed from jeevansathi.com";
}else{ 
	$msg="Invalid inputs provided";
	$smarty->assign("error",1);
}
	$smarty->assign("msg",$msg);
include_once("../profile/commonfile.php");
	if(!$action_url)
		$action_url=$_SERVER['PHP_SELF'];

	$smarty->assign("ACTION",$action_url);
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",4);
	$smarty->assign("bms_left",5);
	$smarty->assign("bms_bottom",6);
	$smarty->assign("bms_right",27);
	$smarty->assign("bms_new_win",33);

	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	//$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

	//$smarty->assign("msg_error", $message);
	$smarty->display("lma_unsubscribe.htm");
	//$smarty->display("site_down.htm");
?>
