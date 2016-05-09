<?php
/***************************************************************************************************************************
FILE NAME		: search_matri_profile.php
DESCRIPTION		: This file is used to search a matri-profile.
DATE			: July 11th 2007.
CREATEDED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("../billing/comfunc_sums.php");
if(authenticated($checksum))
{
	if($submit)
	{
		//finding the profileid from cheque number.
		if($criteria=="CDNUM")
		{
			$sql_search = "SELECT PROFILEID FROM billing.PAYMENT_DETAIL WHERE CD_NUM='$keywordy'";
			$res_search = mysql_query_decide($sql_search) or die($sql_search.mysql_error_js());
			$row_search = mysql_fetch_array($res_search);
			$criteria = "PROFILEID";
			$keywordy = $row_search['PROFILEID'];
		}

		//search keywordy based on the selected critera.
		if($criteria=="USERNAME")
		{
			$sql= "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$keywordy'";
			$result=mysql_query_decide($sql) or die($sql.mysql_error_js());
			$row = mysql_fetch_array($result);
			$profileid=$row['PROFILEID'];
			$qwe=1;	
		}
		elseif($criteria=="EMAILID")
		{
			$sql= "SELECT PROFILEID FROM newjs.JPROFILE WHERE EMAIL='$keywordy'";
			$result=mysql_query_decide($sql) or die($sql.mysql_error_js());
			$row = mysql_fetch_array($result);
			$profileid=$row['PROFILEID'];
			$qwe=1;	
		}
		elseif($criteria=="PROFILEID")
		{
			$profileid=$criteria;
			$qwe=1;	
		}
		if($qwe==1)
		{
			$sql_search = "SELECT p.USERNAME,p.SERVICEID, p.ADDON_SERVICEID FROM billing.MATRI_PURCHASES m join billing.PURCHASES p on p.BILLID=m.BILLID WHERE m.PROFILEID='$profileid' and p.STATUS='DONE'order by m.ID DESC LIMIT 1"; 
		}
		elseif($criteria=="BILLID")
		{			
			$sql_search ="SELECT m.PROFILEID,p.USERNAME,p.SERVICEID, p.ADDON_SERVICEID FROM billing.MATRI_PURCHASES m join billing.PURCHASES p on p.BILLID=m.BILLID WHERE m.BILLID='$keywordy' and p.STATUS='DONE'order by m.ID DESC LIMIT 1";
		}
		elseif($criteria=="ORDERID")
                {
                        $order_id = explode("-",$keywordy);
			$sql_search ="SELECT m.PROFILEID,p.USERNAME,p.SERVICEID, p.ADDON_SERVICEID FROM billing.MATRI_PURCHASES m join billing.PURCHASES p on p.BILLID=m.BILLID WHERE p.ORDERID='$order_id[1]' and p.STATUS='DONE'order by m.ID DESC LIMIT 1";
		}
		$res_search = mysql_query_decide($sql_search) or die($sql_search.mysql_error_js());
		if($row_search = mysql_fetch_array($res_search))
		{
			if(!$profileid)
				$profileid = $row_search['PROFILEID'];
			$details['SERVICE'] = get_service_name(substr($row_search['SERVICEID'],0,1));
			$details['DURATION'] = substr($row_search['SERVICEID'],1)." Month(s)";
			$addons = explode(",",$row_search['ADDON_SERVICEID']);
			for($i=0;$i<count($addons);$i++)
				$addons_arr = substr($addons[$i],0,1);
			$details['ADDON_SERVICE'] = get_addon_services($addons_arr);

			//finding whether the profile is completed.
			$sql_comp = "SELECT * FROM billing.MATRI_COMPLETED WHERE PROFILEID = '$profileid'";
			$res_comp = mysql_query_decide($sql_comp) or die($sql_comp.mysql_error_js());
			if($row_comp = mysql_fetch_array($res_comp))
			{
				$details["PROFILEID"] = $row_comp["PROFILEID"];
				$details["USERNAME"] = $row_comp["USERNAME"];
				$details["ALLOTTED_TO"] = $row_comp["ALLOTTED_TO"];
				$details["ALLOT_TIME"] = $row_comp["ALLOT_TIME"];
				$details["VERIFIED_BY"] = $row_comp["VERIFIED_BY"];
				$details["VERIFIED_DATE"] = $row_comp["VERIFY_DATE"];
				$details["ENTRY_DT"] = $row_comp["ENTRY_DT"];
				$details["CUTS"] = $row_comp["CUTS"];
				$details["ONHOLD_TIME"] = $row_comp["ONHOLD_TIME"];
				$details["ONHOLD_REASON"] = $row_comp["REASON_IFHOLD"];
				$details["STATUS_MESSAGE"] = "This profile has been completed/closed.";
			}
			//finding whether the profile is under progress.
			else
			{
				$sql_progress = "SELECT * FROM billing.MATRI_PROFILE WHERE PROFILEID = '$profileid'";
				$res_progress = mysql_query_decide($sql_progress) or  die($sql_progress.mysql_error_js());
				if($row_progress = mysql_fetch_array($res_progress))
				{
					$details["PROFILEID"] = $row_progress["PROFILEID"];
					$details["USERNAME"] = $row_progress["USERNAME"];
					$details["ALLOTTED_TO"] = $row_progress["ALLOTTED_TO"];
					$details["ALLOT_TIME"] = $row_progress["ALLOT_TIME"];
					$details["COMPLETION_TIME"] = $row_progress["COMPLETION_TIME"];
					$details["ENTRY_DT"] = $row_progress["ENTRY_DT"];
					$details["VERIFIED_BY"] = $row_progress["VERIFIED_BY"];
					$details["STATUS"] = $row_progress["STATUS"];

					//if profile is under progress then finding the hold details.
					if($details["STATUS"] ==  "H")
					{
						$sql_hold = "SELECT HOLD_TIME, HOLD_REASON FROM billing.MATRI_ONHOLD WHERE PROFILEID='$profileid' ORDER BY HOLD_TIME DESC LIMIT 1";
						$res_hold = mysql_query_decide($sql_hold) or die($sql_hold.mysql_error_js());
						$row_hold = mysql_fetch_array($res_hold);
						$details["ONHOLD_TIME"] = $row_hold["HOLD_TIME"];
						$details["ONHOLD_REASON"] = $row_hold["HOLD_REASON"];
						$details["STATUS_MESSAGE"] = "This profile is currently on hold.";
					}
					elseif($details["STATUS"] ==  "Y")
						$details["STATUS_MESSAGE"] = "This profile is yet to be verified by team leader.";
					elseif($details["STATUS"] ==  "N")
						$details["STATUS_MESSAGE"] = "This profile is currently under progress.";
					elseif($details["STATUS"] ==  "NY")
						$details["STATUS_MESSAGE"] = "This profile has been verified and is ready to send first draft.";
					elseif($details["STATUS"] ==  "F")
						$details["STATUS_MESSAGE"] = "This profile has been sent to the user.";
				}
				else
				{
					$details["PROFILEID"] = $profileid;
                                        $details["USERNAME"] = $row_search["USERNAME"];
					$details["STATUS_MESSAGE"] = "This profile is unallotted.";
				}
			}
		}
		else
		{
			$details["STATUS_MESSAGE"] = "This profile does not exists or does not avail matri-profile service.";
		}
		$smarty->assign("details",$details);
		$smarty->assign("RESULT",1);
	}
	$smarty->assign("show_image",$show_image);
	$smarty->assign("checksum",$checksum);
	$smarty->display("search_matri_profile.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
