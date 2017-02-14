<?php
include("connect.inc");
include("sms_service.inc");
$db = connect_db();
$data = authenticated($checksum);
/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);

//$regionstr=8;
//include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************/
//$db=connect_db();
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if($data)
{
	$profileid=$data["PROFILEID"];
	if($submit)
	{
		if(trim($mobile) == "")  // No mobile number entered
		{
			$smarty->assign("MOBILE_NUM_NULL",'1');
			$smarty->assign("ERROR_MSG","1");
		}
		else   // Update mobile number in newjs.JPROFILE and check service availability
		{
      $objUpdate = JProfileUpdateLib::getInstance();
      $result = $objUpdate->editJPROFILE(array('PHONE_MOB'=>$mobile),$profileid,'PROFILEID',"activatedKey=1");
      if(false === $result) {
        $msg = print_r($_SERVER,true);
        mail("kunal.test02@gmail.com","Error in web/profile/sms_info.php while updating",$msg);

        $sql = "Update newjs.JPROFILE set PHONE_MOB = '$mobile' where profileid = '$profileid'  and  activatedKey=1 ";
        logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
      }
//			$sql = "Update newjs.JPROFILE set PHONE_MOB = '$mobile' where profileid = '$profileid'  and  activatedKey=1 ";
//			$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(check_sms_service($mobile))
				$smarty->assign("SERVICE_AVAILABLE",'1');
			else
				$smarty->assign("SERVICE_AVAILABLE",'0');
			$smarty->assign("MOBILE_NUM",$mobile);
		}
		$smarty->assign("checksum",$checksum);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));                                              $smarty->assign("HEAD",$smarty->fetch("head.htm"));                                              $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));                                    $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));                                    $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));                                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));                                    $smarty->display("jssms1.htm");
	}
	else
	{
		$sql_mobile = "Select PHONE_MOB from newjs.JPROFILE where  activatedKey=1 and PROFILEID = '$profileid'";
		$result = mysql_query_decide($sql_mobile) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mobile,"ShowErrTemplate");
	
		$myrow = mysql_fetch_array($result);
		$mobile_num = $myrow["PHONE_MOB"];
		if( $mobile_num != "")   // mobile number specified
		{
			if(check_sms_service($mobile_num))
				$smarty->assign("SERVICE_AVAILABLE",'1');
			else
				$smarty->assign("SERVICE_AVAILABLE",'0');
			$smarty->assign("MOBILE_NUM",$mobile_num);		
		}
		else
			$smarty->assign("MOBILE_NUM_NULL",'1');

		$smarty->assign("checksum",$checksum);
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));                                              $smarty->assign("HEAD",$smarty->fetch("head.htm"));                                              $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));                                    $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));                                    $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));                                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));                                    $smarty->display("jssms1.htm");
	}
}
else
{
	$smarty->assign("MAKE_LOGIN",'1');	
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	$smarty->display("jssms1.htm");
}

/*
function check_sms_service($mobile_num)
{
	$sql_series = "Select SQL_CACHE * from newjs.SMS_SERIES ";
	$result_series = mysql_query_decide($sql_series) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_series,"ShowErrTemplate");

	while($myrow = mysql_fetch_array($result_series))
		$sms_series[] = $myrow["SERIES"];
	$mobile_num_prefix = substr($mobile_num,0,4);
	if(in_array($mobile_num_prefix,$sms_series))
		return 1;
	else
		return 0;	
}
*/
?>
