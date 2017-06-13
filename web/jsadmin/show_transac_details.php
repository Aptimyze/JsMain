<?php
include("connect.inc");
include("../billing/comfunc_sums.php");

if(authenticated($checksum))
{
	//finding transaction details
	$sql_purch = "SELECT p.USERNAME,p.SERVICEID,p.ADDON_SERVICEID,p.DUEAMOUNT, CUR_TYPE, IF(p.CUR_TYPE='RS',s.desktop_RS,s.desktop_DOL) AS PRICE FROM billing.PURCHASES p, billing.SERVICES s WHERE p.SERVICEID=s.SERVICEID AND p.BILLID='$billid'";
	$res_purch = mysql_query_decide($sql_purch) or die($sql_purch.mysql_error_js());
	$row_purch = mysql_fetch_array($res_purch);
	$transac_details['PROFILEID'] = $profileid;
	$transac_details['BILLID'] = $billid;
	$transac_details['USERNAME'] = $row_purch['USERNAME'];
	$transac_details['SERVICE'] = get_service_name(substr($row_purch['SERVICEID'],0,1));
	$transac_details['DURATION'] = substr($row_purch['SERVICEID'],1)." Month(s)";
	$addons = explode(",",$row_purch['ADDON_SERVICEID']);
	$transac_details['ADDON_SERVICE'] = get_addon_services($addons);
	$transac_details['DUEAMOUNT'] = $row_purch['DUEAMOUNT'];
	for($i=0;$i<count($i);$i++)
	{
		$sid = $addons[$i].$transac_details['DURATION'];
		$sql_pr = "SELECT desktop_RS, desktop_DOL FROM billing.SERVICES WHERE SERVICEID='$sid'";
		$res_pr = mysql_query_decide($sql_pr) or die($sql_pr.mysql_error_js());
		$row_pr = mysql_fetch_array($res_pr);
		if($row_purch['CUR_TYPE']=="DOL")
			$addon_price += $row_pr['desktop_DOL']*(1-(billingVariables::TAX_RATE/100));
		elseif($row_purch['CUR_TYPE']=="RS")
			$addon_price += $row_pr['desktop_RS']*(1-(billingVariables::TAX_RATE/100));
	}
	$transac_details['TOTAL_AMOUNT'] = $row_purch['CUR_TYPE']." ".($row_purch['PRICE'] + $addon_price);

	$sql_pd = "SELECT * FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid' AND STATUS='DONE'";
	$res_pd = mysql_query_decide($sql_pd) or die($sql_pd.mysql_error_js());
	$i=0;
	while($row_pd = mysql_fetch_array($res_pd))
	{
		$pay_det[$i]['RECEIPTID'] = $row_pd['RECEIPTID'];
		$pay_det[$i]['MODE'] = $row_pd['MODE'];
		$pay_det[$i]['TYPE'] = $row_pd['TYPE'];
		$pay_det[$i]['AMOUNT'] = $row_pd['AMOUNT'];
		$pay_det[$i]['CD_NUM'] = $row_pd['CD_NUM'];
		$pay_det[$i]['CD_CITY'] = $row_pd['CD_CITY'];
		$pay_det[$i]['CD_DT'] = $row_pd['CD_DT'];
		$i++;
	}
	$i++;

	$sql_comments = "SELECT * FROM billing.MATRI_COMMENTS WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
	$res_comments = mysql_query_decide($sql_comments) or die($sql_comments.mysql_error_js());
	$i=0;
	while($row_comments = mysql_fetch_array($res_comments))
	{
		$comments[$i]["SNO"] = $i+1;
		$comments[$i]["COMMENT"] = $row_comments['COMMENT'];
		$comments[$i]["COMMENT_BY"] = $row_comments['ENTRY_BY'];
		$comments[$i]["COMMENT_DATE"] = $row_comments['ENTRY_DT'];
		$i++;
	}
	
	$smarty->assign("transac_details",$transac_details);
	$smarty->assign("comments",$comments);
	$smarty->assign("pay_det",$pay_det);
	$smarty->assign("checksum",$checksum);
	$smarty->display("show_transac_details.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
