<?php

/*************************bms_adminmis.php***********************************/
  /*
   *  Created By         : Ruchi Chawla
   *  Last Modified By   : Ruchi Chawla
   *  Description        : used for displaying mis of campaigns for banner admin
   *  Includes/Libraries : bms_connect.php
   						  bms_mis.php
*/
include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");



if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$smarty->assign("id",$id);
	$campaignarr=getCampaigns($campaignstatusselected,"");
	formatCampaignDetails($campaignarr);
	//print_r($campaignarr);
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
		$smarty->display("./$_TPLPATH/bms_adminmis.htm");
	}
}
else
{
	TimedOutBms();
}
