<?php

/**************************************************bms_clientmis.php********************************************************/
  /*
   *  Created By         :	Abhinav Katiyar
   *  Last Modified By   : 	Abhinav Katiyar
   *  Description        : 	used for displaying mis of campaigns for a client
   *  Includes/Libraries : 	./includes/bms_connect.php
   			 : 	bms_mis.php
****************************************************************************************************************************/

include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"client");

if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$smarty->assign("id",$id);
	
	$userid=$data["USERID"];
	$companyid=getCompanyId($userid);
	$campaignarr=getCampaignsMis($companyid);
	formatCampaignDetails($campaignarr);
	if($downloadcampaign_x)
	{
		$echostr=getExcelStringCampaigns($campaignarr);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition:attachment; filename=campaigns.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $echostr;
	}
	else
	{
		$smarty->assign("campaignarr",$campaignarr);
		$smarty->assign("campaignstatus",$campaignstatusselected);
		$smarty->assign("campaignstatusarr",getCampaignStatusArr());
		$smarty->display("./$_TPLPATH/bms_clientmis.htm");
	}
}
else
{
	TimedOutBms();
}
