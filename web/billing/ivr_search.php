<?php
/***************************************************************************************************************************
FILE NAME       : easy_bill.php
DESCRIPTION     : This script finds and displays the reference id's and their corresponding receipt id's for a particular
		  user.
CREATED BY      : Sriram Viswanathan
***************************************************************************************************************************/
include("../jsadmin/connect.inc");
include("comfunc_sums.php");
$data=authenticated($cid);

if($data)
{
	if($submit)
	{
		if($criteria=="ivr_ref")
		{
			$sql_uname = "SELECT USERNAME FROM billing.IVR_DETAILS WHERE ID='$phrase'";
			$res_uname = mysql_query_decide($sql_uname) or logError_sums($sql_uname,0);
			$row_uname = mysql_fetch_array($res_uname);
			$phrase = $row_uname['USERNAME'];
		}
		$sql = "SELECT * FROM billing.IVR_DETAILS WHERE USERNAME='$phrase'";
		$res = mysql_query_decide($sql) or logError_sums($sql,0);
		if(mysql_num_rows($res) > 0)
		{
			$i=0;
			while($row = mysql_fetch_array($res))
			{
				$profileid = $row["PROFILEID"];
				if(is_profile_offline($profileid) && !$offline_billing)
					$smarty->assign("ONLINE_TRYING_OFFLINE",1);
				elseif(!is_profile_offline($profileid) && $offline_billing)
					$smarty->assign("OFFLINE_TRYING_ONLINE",1);

				$details[$i]['IVR_NUM'] = $row['ID'];
				$details[$i]['USERNAME'] = $row['USERNAME'];

				$sql_ser = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$row[SERVICEID]'";
				$res_ser = mysql_query_decide($sql_ser) or logError_sums($sql_ser,0);
				$row_ser = mysql_fetch_array($res_ser);

				$details[$i]['MAIN_SERVICE'] = $row_ser['NAME'];

				if(strstr($row['ADDON_SERVICEID'],'B'))
					$details[$i]['ADDON_SERVICE'] .= "Profile Highlighting";
				if(strstr($row['ADDON_SERVICEID'],'M'))
					$details[$i]['ADDON_SERVICE'] .= ", Matri Profile";
				if(strstr($row['ADDON_SERVICEID'],'K'))
					$details[$i]['ADDON_SERVICE'] .= ", Kundali";
				if(strstr($row['ADDON_SERVICEID'],'H'))
					$details[$i]['ADDON_SERVICE'] .= ", Horoscope";
				if(strstr($row['ADDON_SERVICEID'],'V'))
					$details[$i]['ADDON_SERVICE'] .= ", Voicemail";

				$details[$i]['CURTYPE'] = $row['TYPE'];
				$details[$i]['AMOUNT'] = $row['AMOUNT'];
				$details[$i]['DISCOUNT'] = $row['DISCOUNT'];
				list($yy,$mm,$dd) = explode("-", substr($row['ENTRY_DT'],0,10));
				$details[$i]['ENTRY_DT'] = $dd."-".$mm."-".$yy;
				$details[$i]['GENERATED_BY'] = $row['GENERATED_BY'];
				$i++;
			}
		}
		else
			$smarty->assign("NO_RESULT",1);
	}
	else if($edit_ivr_details)
	{
		$sql = "SELECT * FROM billing.IVR_DETAILS WHERE ID='$ivr_ref'";
		$res = mysql_query_decide($sql) or logError_sums($sql,0);
		$i=0;
		while($row = mysql_fetch_array($res))
		{
			$profileid = $row["PROFILEID"];

			$details[$i]['IVR_NUM'] = $row['ID'];
			$details[$i]['USERNAME'] = $row['USERNAME'];

			$sql_ser = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$row[SERVICEID]'";
			$res_ser = mysql_query_decide($sql_ser) or logError_sums($sql_ser,0);
			$row_ser = mysql_fetch_array($res_ser);

			$details[$i]['MAIN_SERVICE'] = $row_ser['NAME'];

			if(strstr($row['ADDON_SERVICEID'],'B'))
				$details[$i]['ADDON_SERVICE'] .= "Profile Highlighting";
			if(strstr($row['ADDON_SERVICEID'],'M'))
				$details[$i]['ADDON_SERVICE'] .= ", Matri Profile";
			if(strstr($row['ADDON_SERVICEID'],'K'))
				$details[$i]['ADDON_SERVICE'] .= ", Kundali";
			if(strstr($row['ADDON_SERVICEID'],'H'))
				$details[$i]['ADDON_SERVICE'] .= ", Horoscope";
			if(strstr($row['ADDON_SERVICEID'],'V'))
				$details[$i]['ADDON_SERVICE'] .= ", Voicemail";

			$details[$i]['CURTYPE'] = $row['TYPE'];
			$details[$i]['AMOUNT'] = $row['AMOUNT'];
			$details[$i]['DISCOUNT'] = $row['DISCOUNT'];
			list($yy,$mm,$dd) = explode("-", substr($row['ENTRY_DT'],0,10));
			$details[$i]['ENTRY_DT'] = $dd."-".$mm."-".$yy;
			$details[$i]['GENERATED_BY'] = $row['GENERATED_BY'];
			$i++;
		}
		$smarty->assign("EDIT_IVR_DETAILS",1);
	}
	else if($update_ivr_details)
	{
		maStripVARS_sums('stripslashes');
		$now = date("Y-m-d G:i:s");
		$entryby = getname($cid);

		$sql_old = "SELECT PROFILEID,AMOUNT FROM billing.IVR_DETAILS WHERE ID='$ivr_ref'";
		$res_old = mysql_query_decide($sql_old) or die("$sql".mysql_error_js());
		$row_old = mysql_fetch_array($res_old);

		$profileid = $row_old["PROFILEID"];
		$old_amount = $row_old['AMOUNT'];

		$sql_pd = "SELECT BILLID, RECEIPTID FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profileid' AND TRANS_NUM='$ivr_ref'";
		$res_pd = mysql_query_decide($sql_pd) or die("$sql".mysql_error_js());
		$row_pd = mysql_fetch_array($res_pd);

		$billid = $row_pd['BILLID'];
		$receiptid = $row_pd['RECEIPTID'];
                if($chk_amount)
                {
                        $mod_str = "AMOUNT changed from $old_amount to $amount in IVR_DETAILS table";

			$sql_log = "INSERT INTO billing.EDIT_DETAILS_LOG(PROFILEID,BILLID,RECEIPTID,CHANGES,ENTRYBY,ENTRY_DT) VALUES('$profileid','$billid','$receiptid','$mod_str','$entryby','$now')";
			mysql_query_decide($sql_log) or die("$sql_log".mysql_error_js());

			$sql_upd_ivr = "UPDATE billing.IVR_DETAILS SET AMOUNT='$amount' WHERE ID='$ivr_ref'";
			mysql_query_decide($sql_upd_ivr) or die("$sql_upd_ivr".mysql_error_js());
                }
		$smarty->assign("ivr_ref",$ivr_ref);
		$smarty->assign("IVR_DETAILS_UPDATED",1);
	}

	$smarty->assign("details",$details);
	$smarty->assign("USER",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("offline_billing",$offline_billing);
	$smarty->display('ivr_search.htm');
}
else
{
	$smarty->assign("cid",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
