<?php
/************************************************************************************************************************
Filename    : offline_billing.php
Description : To restrict offline operator to perform billing only for offline customer [Offline Product 2586]
Created On  : 29 January 2008
Created By  : Sadaf Alam
**************************************************************************************************************************/
include_once("../jsadmin/connect.inc");
include_once("comfunc_sums.php");

$db=connect_db();

if(authenticated($cid))
{
        if($search)
	{
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$phrase' AND SOURCE='ofl_prof'";
		$res=mysql_query_decide($sql) or logError($sql);
		if(mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			$profileid = $row['PROFILEID'];

			$sql_purch = "SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE PROFILEID='$profileid'";
			$res_purch = mysql_query_decide($sql_purch) or logError($sql_purch);
			$row_purch = mysql_fetch_array($res_purch);
			if($row_purch['COUNT'] > 0)
				$entry_in_purch = 1;

			$row=mysql_fetch_assoc($res);

			if($entry_in_purch)
				$smarty->assign("PROCEED_SEARCH",1);
			else
				$smarty->assign("PROCEED_NEW_ENTRY",1);

			if(check_marked_for_deletion($profileid))
				$smarty->assign("MARKED_FOR_DELETION",1);

			$smarty->assign("phrase",$phrase);
			$smarty->assign("pid",$profileid);
		}
		else
		$smarty->assign("NOUSER",1);		

	}
	$smarty->assign("cid",$cid);
        $smarty->display("offline_billing.htm");
}
else
{
        $smarty->assign("CID",$cid);
        $smarty->display("jsconnectError.tpl");
}
?>
