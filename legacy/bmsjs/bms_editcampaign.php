<?php

/***********************************************************bms_editcampaign.php*****************************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : used for editing a particular campaign
   *  Includes/Libraries : ./includes/bms_connect.php
*************************************************************************************************************************/

include("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");

/*********************************************************************************
	checks if the details entered are valid or not
	input: transaction id, campaign start date, campaign end date, 
	       campaign type, campaign impressions
	output: true - if details correct
	      : false - if details incorrect
**********************************************************************************/
function checkForm($campaignstartdt,$campaignenddt,$campaigntype,$campaignimpression)
{
	global $smarty,$_TPLPATH;
	global $dbbms,$dbsums;
	$check=1;
	
	/*$campaignname=trim(addslashes($campaignname));
	$campaignimpression=trim(addslashes($campaignimpression));
	if($campaignname=="")
	{
		$errormsg="To continue, please enter name of the campaign.";
		$check= 0;
	}
	else
	*/
	if($campaignenddt<$campaignstartdt)
	{
		$errormsg="To continue, please enter the duration correctly.";
		$check=0;
	}
	elseif($campaigntype=="impression"&&($campaignimpression==""||$campaignimpression=="0"))
	{
		$errormsg="Please enter no of imp, compulsory with impression type campaign";
		$check=0;
	}
	elseif($campaigntype=="duration"&&($campaignimpression!=""))
	{
		$errormsg="You cannot enter impressions with duration type banners.";
		$check=0;
	}
	else
	{
	
	}
	
	if($check==0)
	{
		$smarty->assign("errormsg",$errormsg);
	 	return 0;
	}
	else 
		return 1;

}

/************************************************************************************************************************
	fetches the array for the type of campaigns
	input: none
	output: array of campaign types
*************************************************************************************************************************/
function getCampaignTypes()
{
	$campaigntype=array("0"=>array("campaigntype"=>"Duration","campaignvalue"=>"duration"),
					"1"=>array("campaigntype"=>"Impression","campaignvalue"=>"impression")
);
return $campaigntype;

}
function updateCampaignDetails($campaignenddate,$campaignimpression,$campaignid,$showmis="",$misoptions="")
{
        global $dbbms;
        $sql="update bms2.CAMPAIGN set CampaignEndDt='$campaignenddate', CampaignImpressions='$campaignimpression'";
        if ($showmis == 'Y' && trim($misoptions)!= "")
                $sql.=", Showmis='$showmis',Misoption='$misoptions'";
        $sql.=" where CampaignId='$campaignid'";
        $res=mysql_query($sql,$dbbms) or die(mysql_error());
                                                                                                                            
}
/***********************************************************************************
	displays populated booing form 
	input: transaction  id
	output: none
***********************************************************************************/
function showForm($campaignid,$startdate,$enddate,$campaignenddate,$campaigntype,$campaignname,$campaignimpression,$showmis,$misdetails)
{
	global $smarty,$_TPLPATH;
	$days=getDaysBms();
	$months=getMonthsBms();
	$years=getYearsBms();
	$mis=create_dd($misdetails,"misopt");
	$campaigntypearr=getCampaignTypes();
	$smarty->assign("campaigntypearr",$campaigntypearr);
	$smarty->assign("days",$days);
	$smarty->assign("months",$months);
	$smarty->assign("years",$years);
	
	$startdatearr=explode("-",$startdate);
	$enddatearr=explode("-",$enddate);
	$smarty->assign("startday",$startdatearr[2]);
	$smarty->assign("startyear",$startdatearr[1]);
	$smarty->assign("startmonth",$startdatearr[0]);
	$smarty->assign("endday",$enddatearr[2]);
	$smarty->assign("endmonth",$enddatearr[1]);
	$smarty->assign("endyear",$enddatearr[0]);
	$smarty->assign("campaigntype",$campaigntype);
	$smarty->assign("campaignname",$campaignname);
	$smarty->assign("campaignimpression",$campaignimpression);
	$smarty->assign("campaignenddate",$campaignenddate);
	$smarty->assign("showmis",$showmis);
	$smarty->assign("mis",$mis);
	$smarty->display("./$_TPLPATH/bms_editcampaign.htm");
}

if($data)
{
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("id",$id);
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("campaignid",$campaignid);
	
	if($resetform_x)
	{
		$campaignarr=getCampaigns("","",$campaignid);
		$campaigndetails=$campaignarr[0];
		$misdetails=explode(',',$campaigndetails["misdetails"]);
		showForm($campaignid,$campaigndetails["campaignstartdate"],$campaigndetails["campaignenddate"],$campaigndetails["campaignenddate"],$campaigndetails["campaigntype"],$campaigndetails["campaignname"],$campaigndetails["campaignimpression"],$misdetails);
	}
	elseif($campaignname||$savedetails_x)
	{
		$days=getDaysBms();
		$months=getMonthsBms();
		$years=getYearsBms();
		$campaigntypearr=getCampaignTypes();
		if(count($misdetails > 1))
                        $misoptions = implode(',',$misdetails);
                else
                        $misoptions = $misdetails[0];
		$smarty->assign("campaigntypearr",$campaigntypearr);
		$smarty->assign("days",$days);
		$smarty->assign("months",$months);
		$smarty->assign("years",$years);
		$campaignstartdt=$startyear."-".$startmonth."-".$startday;
		$campaignenddt=$endyear."-".$endmonth."-".$endday;
		if(checkForm($campaignstartdt,$campaignenddt,$campaigntype,$campaignimpression))
		{
			updateCampaignDetails($campaignenddt,$campaignimpression,$campaignid,$showmis,$misoptions);
			$smarty->assign("cnfrmmsg","This Campaign  has been edited.Please <a href=\"bms_campaign.php?id=$id\" >click</a> on this link  to continue.");
			$smarty->display("./$_TPLPATH/bms_confirmation.htm");

		}
		else
		{
			showForm($campaignid,$campaignstartdt,$campaignenddt,$campaignenddate,$campaigntype,$campaignname,$campaignimpression);
		}

	}
	else
	{
		$campaignarr=getCampaigns("","",$campaignid);
		$campaigndetails=$campaignarr[0];
		showForm($campaignid,$campaigndetails["campaignstartdate"],$campaigndetails["campaignenddate"],$campaigndetails["campaignenddate"],$campaigndetails["campaigntype"],$campaigndetails["campaignname"],$campaigndetails["campaignimpression"],$campaigndetails["showmis"],$campaigndetails["misdetails"]);
	}
}
else
	TimedOutBms();
?>
