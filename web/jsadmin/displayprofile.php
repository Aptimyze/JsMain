<?php
/****************************************************************************************************************************
Filename    : displayprofile.php
Description : To display the detailed view of a profile on clicking on Read More in separate window and then redirect to appropriate list (pool/sl/acc/rej) in offline module
Created On  : 29 January 2008
Created By  : Sadaf Alam
****************************************************************************************************************************/
include("connect.inc");
include("../crm/func_sky.php");
//include("view_contact.php");
include("matches_display_results.inc");
//include("view_contact.php");
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com"," web/jsadmin/displayprofile.php in USE",$msg);
$db=connect_db();
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if(authenticated($cid))
{
	if(proceed($profileid))
	{
		$sql="SELECT BILLID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$billid=$row["BILLID"];
		if($shortlist)
		{
			$note=addslashes(stripslashes($note));
			$sql="UPDATE jsadmin.OFFLINE_MATCHES SET NOTE='$note',STATUS='SL',MOD_DATE=now() WHERE PROFILEID='$profileid' AND MATCH_ID='$searched_profileid'";
			mysql_query_decide($sql) or logError($sql);
			$smarty->assign("SHORTLISTED",1);
			$searchid=$searched_profileid;
		}
		if($accept)
		{
			 $note=addslashes(stripslashes($note));
			 accept_profile($profileid,$searched_profileid,$oc_id);
                        $sql= "SELECT ACC_MADE,ACC_ALLOWED,ACC_UPGRADED FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
                        $res= mysql_query_decide($sql) or die(mysql_error_js());
                        $row= mysql_fetch_array($res);
                        $acc_made= $row['ACC_MADE'];
                        $acc_allowed= $row['ACC_ALLOWED'];
                        $acc_upgraded=$row["ACC_UPGRADED"];
                        $acc_made= $acc_made+1;
                        $acc_remain= $acc_allowed+$acc_upgraded-$acc_made;
                        if($acc_remain<=0)
                        {
                                $sql= "UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N',ACC_MADE='$acc_made' WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
                                mysql_query_decide($sql) or logError($sql);
                                $sql= "UPDATE newjs.JPROFILE SET ACTIVATED= 'D',activatedKey=0 WHERE PROFILEID= '$profileid'";
                                mysql_query_decide($sql) or logError($sql);
                                comp_info($profileid);
								JProfileUpdateLib::getInstance()->removeCache($profileid);
                        }
                        else
                        {
                                $sql="UPDATE jsadmin.OFFLINE_BILLING SET ACC_MADE='$acc_made' WHERE PROFILEID='$profileid' AND BILLID='$billid'";
                                mysql_query_decide($sql) or logError($sql);
                                $smarty->assign("acc_remain",$acc_remain);
                        }
                        $smarty->assign("ACCEPTED",1);
			$searchid= $searched_profileid;
		}	
		if($reject)
		{
			$note=addslashes(stripslashes($note));
			$sql="UPDATE jsadmin.OFFLINE_MATCHES SET NOTE='$note',STATUS='REJ',MOD_DATE=now() WHERE PROFILEID='$profileid' AND MATCH_ID='$searched_profileid'";
			mysql_query_decide($sql) or logError($sql);
			$sql_up="delete from newjs.CONTACTS_STATUS  where PROFILEID='$searched_profileid'";
			mysql_query_decide($sql_up) or logError($sql_up);
			$smarty->assign("REJECTED",1);
			$searchid=$searched_profileid;
		}	
		if($mvta)
		{
			$sql="UPDATE jsadmin.OFFLINE_MATCHES SET COURIERED='N' WHERE PROFILEID= '$profileid' AND MATCH_ID= '$searched_profileid'";
			mysql_query_decide($sql) or die(mysql_error_js());
			$smarty->assign("COURIERED","N");
			$searchid=$searched_profileid;
		}
		if($mvtc)
		{
			$sql="UPDATE jsadmin.OFFLINE_MATCHES SET COURIERED='Y' WHERE PROFILEID= '$profileid' AND MATCH_ID= '$searched_profileid'";
			mysql_query_decide($sql) or die(mysql_error_js());
                        $smarty->assign("COURIERED","Y");
			$searchid=$searched_profileid;
                }
	}
	else
	{
		//$searchid=$searched_profileid;
		$smarty->assign("p_expire",1);
	}
	assigndetails($profileid,$searchid);
	$viewprofile=$smarty->fetch("displayprofile.htm");
	$smarty->assign("cid",$cid);
	$smarty->assign("profileid",$profileid);
	$smarty->assign("viewprofile",$viewprofile);
	$smarty->assign("detailview",1);
	$smarty->assign("page",$page);
	//Added by Vibhor for Astro Service in Offline Module
	$smarty->assign("COMPATIBILITY_SUBSCRIPTION",$compatibility_subscription);
	//end
	if(!$print)
	{
		if($page=="pool")
		{
			$smarty->display("pool_matches.htm");
		}
		if($page=="shortlist")
		{
			contact($searchid,1);
			$smarty->display("shortlisted_matches.htm");
		}
		if($page=="reject")
		{
			$smarty->display("rejected_matches.htm");
		}
		if($page=="accept")
		{
			if($crd_status)
				$smarty->assign("crd_status",$crd_status);
			contact($searchid,1);
			$smarty->display("accept_matches.htm");
		}
		if($page=="search")
	        {
	                $smarty->display("search_profile.htm");
	        }
	}
	else
	{
		if($page == "print")
	        {       
			contact($searchid,1);
			$smarty->assign("single",1);
			$smarty->display("print_profile.htm");
	        }
		else
		{
			$smarty->display("print_profile.htm");
		}
	}	
}
else
{
	$msg="Your session has been timed out";
	$msg.="<br><br>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
function proceed($profileid)
{
	$sql="SELECT ACTIVE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	if($row["ACTIVE"]=="Y")
	return 1;
	else
	return 0;
}
?>
