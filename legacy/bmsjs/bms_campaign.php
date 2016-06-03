<?php
ini_set('max_execution_time',0);
/*************************************************bms_campaign.php**********************************************************/
/*
	*	Created By         :	Abhinav Katiyar
	*	Last Modified By   :	Abhinav Katiyar
	*	Description        :	used for displaying the campaigns in 
					the system and changing their status.
	*	Includes/Libraries :	./includes/bms_connect.php
*/
/***************************************************************************************************************************/

include("./includes/bms_connect.php");
$ip	= FetchClientIP();
$data	= authenticatedBms($id,$ip,"banadmin");

function getCampaigns1($campaignid,$campaignstatus="",$campaignname="",$startdate="",$enddate="",$show="",$transactionid="")
{
        global $dbbms;
	

        if ($show == '99acresmis')
        {
                $sql="select * from bms2.CAMPAIGN where SITE = '99acres' order by CampaignEntryDate desc";
        }
        else
        {
		if ($transactionid)
			$sql = "select * from bms2.CAMPAIGN where REF_ID = '$transactionid'";
		elseif ($campaignid)
		{
			$sql = "select * from bms2.CAMPAIGN where  CampaignId = '$campaignid'";
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
			if($campaignstatus=="all")
				$sql="select * from bms2.CAMPAIGN ".$addquery." order by CampaignEntryDate desc";
			else
			{
				if($addquery)
				$sql="select * from bms2.CAMPAIGN ".$addquery." and CampaignStatus='$campaignstatus'  order by CampaignEntryDate desc";
				else    
					$sql="select * from bms2.CAMPAIGN where CampaignStatus='$campaignstatus'  order by CampaignEntryDate desc";
			}
		}
	}
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_campaign.php:getCampaignDetailsStatusWise:3: Could not get status wise campaign details. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $i=0;
        while($myrow=mysql_fetch_array($res))
        {
                $campaigndetails[$i]["campaignid"]              = $myrow["CampaignId"];
		$campaigndetails[$i]["campaignname"]            = $myrow["CampaignName"];
                $campaigndetails[$i]["campaigntype"]            = $myrow["CampaignType"];
                $campaigndetails[$i]["campaignstatus"]          = $myrow["CampaignStatus"];
                $campaigndetails[$i]["campaignentrydate"]       = $myrow["CampaignEntryDate"];
                $campaigndetails[$i]["companyid"]               = $myrow["CompanyId"];
                $campaigndetails[$i]["campaignimpression"]      = $myrow["CampaignImpressions"];
                $campaigndetails[$i]["transactionid"]           = $myrow["TransactionId"];
                $campaigndetails[$i]["executiveid"]             = $myrow["CampaignExecutiveId"];
                $campaigndetails[$i]["campaignstartdate"]       = $myrow["CampaignStartDt"];
                $campaigndetails[$i]["campaignenddate"]         = $myrow["CampaignEndDt"];
		$campaigndetails[$i]["site"]		        = $myrow["SITE"];
                $i++;
        }
        return $campaigndetails;
}
/***********************************************************************************
	fetches the name of the company corresponding to a campaign 
	input:	company id
	output: company name  									
************************************************************************************/
function getCompanyName($companyid)
{
 	$sql = "select company_name from clientprofile.company where company_id='$companyid'";
	$res = mysql_query($sql) or logErrorBms("bms_campaign.php:getcompanyName:1: Could not get name of company <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow = mysql_fetch_array($res);
	$companyname = $myrow["company_name"];
	return $companyname;
}

/************************************************************************************
	fetches the name of the salesexecutive from corresponding to a campaign
	input:	salesexecutive id
	output: salesexecutive name  
************************************************************************************/
function getSaleExectiveName($executiveid)
{
	$sql = "select name from clientprofile.sales_exec where emp_id='$executiveid'";
	$res = mysql_query($sql) or logErrorBms("bms_campaign.php:getSaleExectiveName:2: Could not get name of salesexecutive <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow = mysql_fetch_array($res);
	$salesexecutivename = $myrow["name"];
	return $salesexecutivename;
}

/************************************************************************************
	fetches the details of all campaigns corresponding to a status
	input:	campaignstatus
	output:	array of all campaigns with above status
*************************************************************************************/
function FormatCampaignDetails(&$campaignarr)
{
	global $dbbms;
	for ($i = 0;$i < count($campaignarr);$i++)
	{
		$campaignarr[$i]["companyname"]		= getCompanyName($campaignarr[$i]["companyid"]);
 		$campaignarr[$i]["executivename"]	= getSaleExectiveName($campaignarr[$i]["executiveid"]);
	}
}

/************************************************************************************
	checks if the campaign can be made active or not
	input : campaign id 
	output: true  - if campaign can be made active
		false - if campaign cannot  be made active
*************************************************************************************/
function checkMakeCampaignActive($campaignid)
{
	global $dbbms;
	$sql = "select BannerId from bms2.BANNER  where CampaignId='$campaignid' and (BannerStatus='booked' or BannerStatus='live' or BannerStatus='ready')";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_campaign.php:checkMakeCampaignActive:5: Could not check if the campaign is allowed to be made active or not <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	if (mysql_num_rows($res))
		return 1;
	else 
		return 0;
}

if($data)
{
	//campaign status passed from form else default status(hold) is assumed.

	global $dbbms,$smarty;
	$id		= $data["ID"];
	$site		= $data["SITE"];
	$bmsheader	= fetchHeaderBms($data);
	$bmsfooter	= fetchFooterBms();

	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("site",$site);
	$smarty->assign("id",$id);

	if ($startyear && $startmonth && $startday && $endyear && $endmonth && $endday)
	{
		$startdate      = $startyear."-".$startmonth."-".$startday;
 	        $enddate        = $endyear."-".$endmonth."-".$endday;
	}

	// if any campaign has to be made live

	if ($action == "activate")
	{	
		if (checkMakeCampaignActive($campaignid))  // if the campaign can be made active 
		{
			ChangeCampaignStatus($campaignid,"active"); //changes the status of a campaign to active
			$cnfrmmsg = "This campaign has been made active.";
			$smarty->assign("cnfrmmsg",$cnfrmmsg);
		}
		else
		{
			$errormsg = "This campaign could not be made active. Please make live at least one component of this campaign.";
			$smarty->assign("errormsg",$errormsg);
		}
	}
	elseif ($action=="deactivate")  
	{
		ChangeCampaignStatus($campaignid,"deactive");  //changes the status of a campaign to de-active
	}

	$smarty->assign("campaignstatus",$campaignstatusselected);
	$smarty->assign("campaignstatusarr",getCampaignStatusArr()); // array of different campaign status viz. live , served
	$campaignarr=getCampaigns1($campaignid,$campaignstatusselected,$campaignname,$startdate,$enddate,$show,$transactionid);
	//$campaignarr = getCampaigns($campaignstatusselected,"");     // gets campaign based on criteria viz. live campaigns 
	//print_r($campaignarr);
	FormatCampaignDetails($campaignarr);			    // Formats the different campaign details
	$smarty->assign("waitcampaigndetails",$campaignarr);
	$smarty->display("./$_TPLPATH/bms_campaign.htm");

}
else
{
	TimedOutBms();
}

