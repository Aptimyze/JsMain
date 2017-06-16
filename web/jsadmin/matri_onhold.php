<?php
/**************************************************************************************************************************
FILE		: matri_onhold.php
DESCRIPTION	: This file is used to put a profile on hold or to remove the profile from hold.
CREATED BY	: Sriram Viswanathan.
CREATION DATE	: July 13th 2007.
**************************************************************************************************************************/
include("connect.inc");
if(authenticated($checksum))
{
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {
		//when submit button is clicked from hold page.
		if($hold)
		{
			if(trim($reason) == "")
				$smarty->assign("error",1);
			else
			{
				$sql = "SELECT mp.ALLOTTED_TO, mp.ALLOT_TIME, p.ENTRY_DT FROM billing.MATRI_PROFILE mp, billing.PURCHASES p WHERE mp.PROFILEID=p.PROFILEID AND mp.PROFILEID='$profileid' AND mp.STATUS='N' ORDER BY ID DESC LIMIT 1";
				$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
				$row = mysql_fetch_array($res);

				$sql_ins = "REPLACE INTO billing.MATRI_ONHOLD(PROFILEID,USERNAME,ENTRY_DT,ALLOTTED_TO, ALLOTTED_TIME,HOLD_TIME,HOLD_REASON,STATUS,ENTRYBY) VALUES('$profileid','$username','$row[ENTRY_DT]','$row[ALLOTTED_TO]','$row[ALLOT_TIME]',now(),'".addslashes($reason)."','H','$user')";
				mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());

				$sql_upd = "UPDATE billing.MATRI_PROFILE SET STATUS = 'H' WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());

				$smarty->assign("hold_done",1);
			}
		}
		//when submit button is clicked from hold page.
		elseif($unhold)
		{
			if(trim($reason) == "")
				$smarty->assign("error",1);
			else
			{
				$sql_upd = "UPDATE billing.MATRI_PROFILE SET STATUS='N' WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());

				$sql_upd = "UPDATE billing.MATRI_ONHOLD SET STATUS='N', UNHOLD_REASON='".addslashes(stripslashes($reason))."', UNHOLD_BY='$user', UNHOLD_TIME=now() WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());

				$smarty->assign("unhold_done",1);
			}
		}

		if($from_unhold==1)
			$smarty->assign("submit_name","unhold");
		else
			$smarty->assign("submit_name","hold");
			
		$smarty->assign("profileid",$profileid);
		$smarty->assign("script_name",$script_name);
		$smarty->assign("allotted_to",$allotted_to);
		$smarty->assign("username",$username);
		$smarty->assign("checksum",$checksum);
		$smarty->assign("from_unhold",$from_unhold);
		$smarty->display("matri_onhold.htm");
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
