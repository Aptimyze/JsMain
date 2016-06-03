
<?php
/*************************************************bms_adminmis.php**********************************************************/
  /*
   *  Created By         : Shobha Kumari
   *  Last Modified By   : Shobha Kumari
   *  Description        : used for displaying mis of campaigns for banner admin
   *  Includes/Libraries : bms_connect.php , bms_mis.php
*************************************************************************************************************************/
include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
if ($site != '99acres')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");
//$data=authenticatedBms($id,$ip,"banadmin");
$smarty->assign("site",$site);

function getCampaigns1($campaignstatus="",$campaignname="",$startdate="",$enddate="",$show="",$transactionid="")
{
	global $dbbms;

	if ($show == '99acresmis')
	{
		$sql="select * from bms2.CAMPAIGN where SITE = '99acres' order by CampaignEntryDate desc";
	}
	else
	{
		if(!$campaignstatus)
			$campaignstatus="all";

		if($campaignname != "")
			$addquery=" where CampaignName like '%$campaignname%'";

		elseif ($startdate && $enddate)
		{
			$addquery = " where CampaignStartDt >='$startdate' and CampaignEndDt <= '$enddate' ";
		}
                if ($transactionid)
                        $sql = "select * from bms2.CAMPAIGN where REF_ID = '$transactionid'";
		elseif($campaignstatus=="all")
			$sql="select * from bms2.CAMPAIGN ".$addquery." order by CampaignEntryDate desc";
		else
		{
			if($addquery)
				$sql="select * from bms2.CAMPAIGN ".$addquery." and CampaignStatus='$campaignstatus'  order by CampaignEntryDate desc";
			else
				$sql="select * from bms2.CAMPAIGN where CampaignStatus='$campaignstatus'  order by CampaignEntryDate desc";
		}
	}
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_campaign.php:getCampaignDetailsStatusWise:3: Could not get status wise campaign details. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i=0;
	while($myrow=mysql_fetch_array($res))
	{
		$campaigndetails[$i]["campaignid"]		= $myrow["CampaignId"];
		$campaigndetails[$i]["campaignname"]		= $myrow["CampaignName"];
		$campaigndetails[$i]["campaigntype"]		= $myrow["CampaignType"];
		$campaigndetails[$i]["campaignstatus"]		= $myrow["CampaignStatus"];
		$campaigndetails[$i]["campaignentrydate"]	= $myrow["CampaignEntryDate"];
		$campaigndetails[$i]["companyid"]		= $myrow["CompanyId"];
		$campaigndetails[$i]["campaignimpression"]	= $myrow["CampaignImpressions"];
		$campaigndetails[$i]["transactionid"]		= $myrow["TransactionId"];
		$campaigndetails[$i]["executiveid"]		= $myrow["CampaignExecutiveId"];
		$campaigndetails[$i]["campaignstartdate"]	= $myrow["CampaignStartDt"];
		$campaigndetails[$i]["campaignenddate"]		= $myrow["CampaignEndDt"];
		$campaigndetails[$i]["site"]                    = $myrow["SITE"];
		$i++;
	}
	return $campaigndetails;
}

if($data)
{
	$id		= $data["ID"];
	$startdate	= $startyear."-".$startmonth."-".$startday;
	$enddate	= $endyear."-".$endmonth."-".$endday;
	$bmsheader	= fetchHeaderBms($data);
	$bmsfooter	= fetchFooterBms();

	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("id",$id);

	$campaignarr=getCampaigns1($campaignstatusselected,$campaignname,$startdate,$enddate,$show,$transactionid);
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
		if($showcampaign)
		{	
			$campaignarr=getCampaigns1($campaignstatusselected,"","","");
        		formatCampaignDetails($campaignarr);
	
		}
	
		$smarty->assign("campaignarr",$campaignarr);
		$smarty->assign("campaignstatus",$campaignstatusselected);
		$smarty->assign("campaignstatusarr",getCampaignStatusArr());
		$smarty->assign("show",$show);
		$smarty->display("./$_TPLPATH/bms_adminmis.htm");
	}

}
else
{
	TimedOutBms();
}

