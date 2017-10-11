<?php

/****************************************************bms_campaigndetails.php************************************************
	*	Created By		:	Abhinav Katiyar
	*	Last Modified By	:	Abhinav Katiyar
	*	Description		:	Displays the campaigndetails page, and used 
						to change the status of a banner
	*	Includes/Libraries 	:	./includes/bms_connect.php
****************************************************************************************************************************/

include_once("./includes/bms_connect.php");
$ip = FetchClientIP();
$data = authenticatedBms($id,$ip,"banadmin");

/***************************************************************************
	fetches the various possible banner status
	input:  void
	ouput:	array of banner status
****************************************************************************/
function getBannerStatusArr()
{
	$bannerstatusarr = array("all","new","ready","booked","cancel","live","served","deactive","expired");
	return $bannerstatusarr;
}


if ($data)
{
	$id	   = $data["ID"];
	$site	   = $data["SITE"];

	$smarty->assign("site",$site);
	$bmsheader = fetchHeaderBms($data);          	// displays the common header
	$bmsfooter = fetchFooterBms();			// displays the common footer
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);

	if ($changestatus)				// if the status has to be changed (deactivate)
	{
		include("bms_checklive.php");
	}
	
	// fetches  the details of campaigns based on some crieria viz live , served. 
 	$campaigndetails = getCampaignDetails($campaignid,$bannerstatusselected);

	$smarty->assign("campaigndetails",$campaigndetails);
	$smarty->assign("campaignid",$campaignid);
	$campaign_comm_arr=getCampaigns("","",$campaignid);
	$smarty->assign("comments",$campaign_comm_arr[0]['comments']);
	$smarty->assign("id",$id);
	$smarty->assign("site",$site);
	$smarty->assign("bannerstatus",$bannerstatusselected);
	$smarty->assign("bannerstatusarr",getBannerStatusArr());
	if($lavesh)
		$smarty->display("./$_TPLPATH/bms_campaigndetails_lavesh.htm");
	else
		$smarty->display("./$_TPLPATH/bms_campaigndetails.htm");

}
else
{
	TimedOutBms();
}
?>
