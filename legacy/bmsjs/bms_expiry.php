<?php

include("includes/bms_connect.php");
$db99 = getConnection99acres();

$ip=FetchClientIP();

$site           = $data["SITE"];
$smarty->assign("site",$site);

if ($site != '99acres')
	$data=authenticatedBms($id,$ip,"banadmin");
else
	$data=authenticatedBms($id,$ip,"99acresadmin");

$bmsheader      = fetchHeaderBms($data);
$bmsfooter      = fetchFooterBms();
$smarty->assign("bmsheader",$bmsheader);
$smarty->assign("bmsfooter",$bmsfooter);

if(!$id)
	$id=$data["ID"];

$smarty->assign("id",$id);

if($campaignname || $startday)
{

        $i=0;
	$now=time();

	if($campaignname)
	{
		$start_dt=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
		$end_date=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+$campaignname,date("Y")));
	}
	else
	{
		$start_dt=$startyear.'-'.$startmonth.'-'.$startday;
		$end_date=$endyear.'-'.$endmonth.'-'.$endday;
	}

	$sql="Select *  from bms2.CAMPAIGN where  CampaignEndDt>'$start_dt' and CampaignEndDt<'$end_date' and CampaignEndDt!='0000-00-00'";
	$result=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_expiry",mysql_error($dbbms).$sql);
        while($myrow=mysql_fetch_array($result))
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
                $campaigndetails[$i]["site"]                    = $myrow["SITE"];

		$companyid=$myrow["CompanyId"];
		$executiveid=$myrow["CampaignExecutiveId"];

		$sql1 = "select company_name from clientprofile.company where company_id='$companyid'";
		$res1 = mysql_query($sql1,$dbbms) or logErrorBms("bms_campaign.php:getcompanyName:1: Could not get name of company <br>  <!--$sql1<br>". mysql_error()."-->: ". mysql_errno(), $sql1, "ShowErrTemplate");
		$myrow1 = mysql_fetch_array($res1);
		$companyname = $myrow1["company_name"];
		$campaigndetails[$i]["companyname"]=$companyname;


		$sql1="Select sum(BannerServed) as tot from bms2.BANNER b WHERE CampaignId=$myrow[CampaignId]";
		$res1 = mysql_query($sql1,$dbbms) or logErrorBms("bms_campaign.php:getcompanyName:1: Could not get name of company <br>  <!--$sql1<br>". mysql_error()."-->: ". mysql_errno(), $sql1, "ShowErrTemplate");
                $myrow1 = mysql_fetch_array($res1);
		$campaigndetails[$i]["ImpressionServed"]=$myrow1["tot"];


		if($myrow["CampaignExecutiveId"])
		{
			if ($myrow["SITE"] == 'JS')
			{
				$sql_exec = "SELECT EMAIL,USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID = $myrow[CampaignExecutiveId]";
				$res_exec=mysql_query($sql_exec,$dbbms) or logErrorBms("bms_expiryalert.php: Could not retrieve email-id of the sales executive. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql_exec, "ShowErrTemplate");
			}
			else
			{
				$sql_exec = "SELECT EMAIL,USERNAME FROM sums.PSWRDS WHERE EMP_ID = $myrow[CampaignExecutiveId]";
				$res_exec=mysql_query($sql_exec,$db99) or logErrorBms("bms_expiryalert.php: Could not retrieve email-id of the sales executive.<br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql_exec, "ShowErrTemplate");
			}
			
			if($row_exec=mysql_fetch_array($res_exec))
			{
				if($row_exec['EMAIL'])
					$campaigndetails[$i]["email_of_sales_executive"] = $row_exec['EMAIL'];
				else
					$campaigndetails[$i]["email_of_sales_executive"] = 'Not Mentioned';
				
				if($row_exec['USERNAME'])
					$campaigndetails[$i]["executivename"] = $row_exec['USERNAME'];
				else
					$campaigndetails[$i]["executivename"] = 'Not Mentioned';
			}
			else
			{
				$campaigndetails[$i]["executivename"] = 'Not Mentioned';
				$campaigndetails[$i]["email_of_sales_executive"] = 'Not Mentioned';
			}
		}
		else
		{
			$campaigndetails[$i]["executivename"] = 'Not Mentioned';
                        $campaigndetails[$i]["email_of_sales_executive"] = 'Not Mentioned';
		}
                                                                                                                             

		$i=$i+1;
	}
	$smarty->assign("waitcampaigndetails",$campaigndetails);
	$smarty->assign("expiring_soon",1);
        $smarty->display("./$_TPLPATH/bms_campaign.htm");
}
else
{
	$curr_dt=date("Y-m-d");
	$start=explode("-",$curr_dt);
	$startyear=$start[0];
	$startmonth=$start[1];
	$startday=$start[2];
	$smarty->assign("startday",$startday);
	$smarty->assign("startmonth",$startmonth);
	$smarty->assign("startyear",$startyear);

	$ts = time();
	$ts+=7*24*60*60;
	$curr_dt7=date("Y-m-d",$ts);
        $end=explode("-",$curr_dt7);
        $endyear=$end[0];
        $endmonth=$end[1];
        $endday=$end[2];
        $smarty->assign("endday",$endday);
        $smarty->assign("endmonth",$endmonth);
        $smarty->assign("endyear",$endyear);


	$smarty->assign("daysarr",getDaysBms());
	$smarty->assign("monthsarr",getMonthsBms());
	$smarty->assign("yearsarr",getYearsBms());
	$smarty->display("./$_TPLPATH/bms_expiry.htm");
}

?>

