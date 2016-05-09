<?php
/***************************************************************************************************************************
FILE NAME		: show_matriprofile.php
DESCRIPTION		: This file shows the list of unalloted profiles which have matri-profile
			: as their main / addon service.
MODIFICATION DATE	: July 14th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
if(authenticated($checksum))
{
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {
		//complete button is clicked
		if($submit)
		{
			//finding details from MATRI_PROFILE table.
			$sql_mp = "SELECT ALLOTTED_TO, ALLOT_TIME, VERIFIED_BY, COMPLETION_TIME, ENTRY_DT FROM billing.MATRI_PROFILE WHERE PROFILEID='$profileid'";
			$res_mp = mysql_query_decide($sql_mp) or die("$sql".mysql_error_js());
			while($row_mp = mysql_fetch_array($res_mp))
			{
				$allotted_to = $row_mp['ALLOTTED_TO'];
				$allot_time = $row_mp['ALLOT_TIME'];
				$verified_by = $row_mp['VERIFIED_BY'];
				$completion_time = $row_mp['COMPLETION_TIME'];
				$entry_dt = $row_mp['ENTRY_DT'];
			}

			//finding max cuts.
			$sql_mc = "SELECT MAX(CUTS) CUTS FROM billing.MATRI_CUTS WHERE PROFILEID='$profileid'";
			$res_mc = mysql_query_decide($sql_mc) or die($sql_mc.mysql_error_js());
			$row_mc = mysql_fetch_array($res_mc);
			
			$cuts = $row_mc['CUTS'];

			//finding hold details (if any).
			$sql_mh = "SELECT HOLD_TIME,HOLD_REASON FROM billing.MATRI_ONHOLD WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
			$res_mh = mysql_query_decide($sql_mh) or die($sql_mh.mysql_error_js());
                        $row_mh = mysql_fetch_array($res_mh);

			$onhold_time = $row_mh['HOLD_TIME'];
			$onhold_reason = $row_mh['HOLD_REASON'];

			//insert into MATRI_COMPLETE.
			$sql_ins = "INSERT INTO billing.MATRI_COMPLETED(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,VERIFIED_BY,VERIFY_DATE,ENTRY_DT,CUTS,ONHOLD_TIME,REASON_IFHOLD,COMPLETION_TIME) VALUES('$profileid','$username','$allotted_to','$allot_time','$verified_by','$completion_time','$entry_dt','$cuts','$onhold_time','$onhold_reason',now())";
			mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());

			$sql_del = "DELETE FROM billing.MATRI_PROFILE WHERE PROFILEID = '$profileid'";
			mysql_query_decide($sql_del) or die($sql_del.mysql_error_js());

			$smarty->assign("COMPLETED",1);
		}
		else
		{
			//finding followup details of particular profile
			$sql_followup = "SELECT mc.PROFILEID, mc.ENTRY_DT, mc.CUTS, mc.UPLOADED_BY, mc.COMMENTS, mp.USERNAME FROM billing.MATRI_CUTS mc, billing.MATRI_PROFILE mp WHERE mc.PROFILEID=mp.PROFILEID AND mc.PROFILEID='$profileid' AND mp.STATUS = 'F' ORDER BY mc.ENTRY_DT DESC";
			$res_followup = mysql_query_decide($sql_followup) or die($sql_followup.mysql_error_js());
			$x=0;
			while($row_followup = mysql_fetch_array($res_followup))
			{
				$sql_con = "SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE where PROFILEID='$profileid'";
				$result_con = mysql_query_decide($sql_con) or die(mysql_error_js());
				$myrow_con = mysql_fetch_array($result_con);

				$details['PROFILEID'] = $profileid;
				$details["USERNAME"] = $row_followup['USERNAME'];
				$details["EMAIL"] = $myrow_con['EMAIL'];
				$details['PHONE_MOB'] = $myrow_con['PHONE_MOB'];
				$details['PHONE_RES'] = $myrow_con['PHONE_RES'];

				$followup[$x]['SNO']=$x+1;
				$followup[$x]['DATE'] = $row_followup['ENTRY_DT'];
				$followup[$x]['CUTS'] = $row_followup['CUTS'];
				$followup[$x]['UPLOADED_BY'] = $row_followup['UPLOADED_BY'];
				$followup[$x]['COMMENTS'] = str_replace("<a href","<u hre",$row_followup['COMMENTS']);
				$followup[$x]['COMMENTS'] = str_replace("</a>","</u>",$followup[$x]['COMMENTS']);
				$x++;
			}
			$smarty->assign("details",$details);
			$smarty->assign("followup",$followup);
		}
		$smarty->assign("checksum",$checksum);
		$smarty->display("matri_followup.htm");
	}
	else
	{
		echo "You don't have permission to view this mis";
		die();
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}

?>
