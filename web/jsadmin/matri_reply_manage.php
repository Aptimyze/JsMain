<?php
/***************************************************************************************************************************
FILE NAME		: matri_reply_manage.php
DESCRIPTION		: This file shows the responses received from user, and has not been looked into.
MODIFICATION DATE	: July 26th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
if(authenticated($checksum))
{
	//finding the max cut for each profileid where UPLOADED_BY is USER.
	$sql = "SELECT MAX( CUTS ) CUTS, PROFILEID FROM billing.MATRI_CUTS WHERE UPLOADED_BY='USER' GROUP BY PROFILEID";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		$profileid_arr[] = $row['PROFILEID'];
		$user_upload[$row['PROFILEID']] = $row['CUTS'];
	}

	//finding the max cut for each profileid where UPLOADED_BY is not USER (by an executive).
	$sql = "SELECT MAX( CUTS ) CUTS, PROFILEID FROM billing.MATRI_CUTS WHERE UPLOADED_BY<>'USER' GROUP BY PROFILEID";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		if(!@in_array($row['PROFILEID'], $profileid_arr))
			$profileid_arr[] = $row['PROFILEID'];

		$upload[$row['PROFILEID']] = $row['CUTS'];
	}

	//comparing cuts value for each user.
	for($i=0;$i<count($profileid_arr);$i++)
	{
		//if cuts value of user_upload is >= that of uploaded by some executive.
		if($user_upload[$profileid_arr[$i]] >= $upload[$profileid_arr[$i]])
			$final_profileid_string .= "'".$profileid_arr[$i]."',";
	}

	$final_profileid_string = rtrim($final_profileid_string,",");

	if($final_profileid_string)
	{
		unset($profileid_arr);
		//finding the details.
		$sql_details = "SELECT mc.PROFILEID, mc.COMMENTS, mc.ENTRY_DT, mc.CUTS, mp.USERNAME, mp.ALLOTTED_TO, mp.STATUS FROM billing.MATRI_CUTS mc, billing.MATRI_PROFILE mp WHERE mc.UPLOADED_BY='USER' AND mc.PROFILEID IN ($final_profileid_string) AND mc.PROFILEID = mp.PROFILEID ORDER BY mc.ENTRY_DT DESC";
		$res_details = mysql_query_decide($sql_details) or die($sql_details.mysql_error_js());
		$i=0;
		while($row_details = mysql_fetch_array($res_details))
		{
			if(!@in_array($row_details["PROFILEID"],$profileid_arr))
			{
				$profileid_arr[] = $row_details["PROFILEID"]; 
				$entry_dt_arr[] = $row_details["ENTRY_DT"];

				$details[$i]["PROFILEID"] = $row_details["PROFILEID"];
				$details[$i]["USERNAME"] = $row_details["USERNAME"];
				$details[$i]["ENTRY_DT"] = $row_details["ENTRY_DT"];
				$details[$i]["COMMENTS"] = $row_details["COMMENTS"];
				$details[$i]["CUTS"] = $row_details["CUTS"];
				$details[$i]["ALLOTTED_TO"] = $row_details["ALLOTTED_TO"];
				$details[$i]["STATUS"] = $row_details["STATUS"];
				$i++;
			}

			$smarty->assign("FOUND",1);
			array_multisort($details,SORT_ASC,$entry_dt_arr);
		}
	}

	$smarty->assign("details",$details);
	$smarty->assign("checksum",$checksum);
	$smarty->display("matri_reply_manage.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
