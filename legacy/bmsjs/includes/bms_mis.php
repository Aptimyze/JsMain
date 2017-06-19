<?php
/*********************************./includes/bms_mis.php*********************************************************************
        *       Created By         :    Abhinav Katiyar
        *       Last Modified By   :    Abhinav Katiyar
        *       Description        :    This file includes all the functions common to
                                        the mis scripts
        *       Includes/Libraries :    none
***************************************************************************************************************************/

/**********************************************************************************
	Fetch an array of date wise results of a banner in a duration
	input: bannerid, start date of query, end date of query
	output: array of clicks, impressions and ctr
***********************************************************************************/
function viewDateWiseResults($bannerid,$startdate,$enddate)
{
	//echo "here"	;
	global $dbbms;
 	$sql = "select Date,Clicks,Impressions from bms2.BANNERMIS where BannerId ='$bannerid' and Date >='$startdate' and Date <='$enddate' order by Date desc";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_mis.php: viewDateWiseResults :1: Could not select date wise result for mis. <br>	<!--$sql--<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i = 0;
	$curdate = date("Y-m-d");
	while ($myrow = mysql_fetch_array($res))
	{
		$resultarr[$i]["sno"] = $i+1;
		$resultarr[$i]["date"] = $myrow["Date"];
		$resultarr[$i]["impressions"] = $myrow["Impressions"];

		if (($myrow["Date"] == $curdate) && ($myrow["Impressions"] == "0"))
			$resultarr[$i]["impressions"] = "N/A";
		$resultarr[$i]["clicks"] = $myrow["Clicks"];

		if ($myrow["Impressions"])
			$resultarr[$i]["ctr"] = round((($myrow["Clicks"]*100)/$myrow["Impressions"]),2);
		else
			$resultarr[$i]["ctr"] = 0;
		$i++;
	}
	return $resultarr;
}

/**********************************************************************************
        Fetch an array of date wise results of a banner in a duration and converts
	them in excel sheet format
        input: array of results
        output: array of clicks, impressions and ctr in .xls format
***********************************************************************************/
function getExcelDate($resultarr)
{
	$excelstr = "S NO\tDate\tClicks\tImpressions\tCTR\n";
	//$excelstr="S NO\tDate\tImpressions\n";
	if ($resultarr)
	{
		foreach($resultarr as $result)
		{
			$excelstr.="$result[sno]\t";
			$excelstr.="$result[date]\t";
			//$excelstr.="$result[clicks]\t";
			$excelstr.="$result[clicks]\t";
			$excelstr.="$result[impressions]\t";
			$excelstr.="$result[ctr]\n";
			//$excelstr.="$result[ctr]\n";
		}
	}
	return $excelstr;
}

/**********************************************************************************
        Fetch an array of date wise results of a banner in a duration based on 
	different criterion and converts them in excel sheet format
        input: array of results
        output: array of clicks, impressions and ctr in .xls format
***********************************************************************************/

function getExcelCriteria($resultarr)
{
	$excelstr = "S NO\tCriteria\tTotalImpressions\n";
	if ($resultarr)
	{
		foreach ($resultarr as $result)
		{
			$excelstr.="$result[sno]\t";
			$excelstr.="$result[criterialabel]\t";
			$excelstr.="$result[totalimpressions]\n";
		}
	}
	return $excelstr;
}
/************************************************************************************
	Fetches the query for criteria wise and date wise results of a banner in a duration
	input: Criteria, value of criteria ,bannerid, start date of query, end date of query
	output: query required to fetch the information
*************************************************************************************/
function getCriteriaDatewiseQuery($criteria,$criteriavalue,$bannerid,$startdate,$enddate)
{
	global $mapcriteriasql;
	$criteriasql = $mapcriteriasql["$criteria"];
	$sql = "select  Date , Impressions from bms2.IMPRESSIONMIS where BannerId = '$bannerid' and $criteriasql = '$criteriavalue' and Date >= '$startdate' and Date <= '$enddate'";
	return $sql;
}


/**************************************************************************************
	Fetch an array of date wise results of a banner of a particular criteria given the sql query
	input: sql query
	output: array of date and respective impressions
****************************************************************************************/
function getCriteriaDatewiseResult($criteriasql)
{
	global $dbbms;
	$res = mysql_query($criteriasql,$dbbms) or logErrorBms("bms_mis.php: getCriteriaDatewiseResult :1: Could not select criteria wise result for mis. <br>	<!--$criteriasql<br>". mysql_error()."-->: ". mysql_errno(), $criteriasql, "ShowErrTemplate");
	$i = 0;
	$curdate = date("Y-m-d");
	while ($myrow = mysql_fetch_array($res))
	{
		$returnarr[$i]["sno"] = $i+1;
		$returnarr[$i]["date"] = $myrow["Date"];
		$returnarr[$i]["impressions"] = $myrow["Impressions"];
		if (($myrow["Date"] == $curdate) && ($myrow["Impressions"] == "0"))
			$returnarr[$i]["impressions"] = "N/A";
		$i++;
	}
	return $returnarr;
}

/**************************************************************************************
	returns a sql query required for criteria results of a banner 
	input: bannerid, criteria
	output: sql query
**************************************************************************************/
function  getCriteriaQuery($criteria,$bannerid)
{
	global $mapcriteriasql;
	$criteriasql = $mapcriteriasql["$criteria"];
	$sql = "select ".$criteriasql." as criteria , sum(Impressions) as totalimpressions from bms2.IMPRESSIONMIS where BannerId='$bannerid' and  $criteriasql !='' group by $criteriasql";
	return $sql;
}

/***************************************************************************************
	Fetch an array of criteria wise results of a banner 
	input: sql query, criteria
	output: array of criteria,total impressions,criteria label,overall impressions
***************************************************************************************/
function getCriteriaResult($sqlcriteria,$criteria)
{
	global $dbbms;
	$rescriteria = mysql_query($sqlcriteria,$dbbms) or die(mysql_error());
	$i = 0;
	$total = 0;
	while($myrow = mysql_fetch_array($rescriteria))
	{
		$resultarr[$i]["sno"] = $i+1;
		$resultarr[$i]["criteria"] = urlencode($myrow["criteria"]);
		$resultarr[$i]["totalimpressions"] = $myrow["totalimpressions"];
		$total+=$myrow["totalimpressions"];
		if ($criteria == "CTC")
			$resultarr[$i]["criterialabel"] = get_farea_bms($myrow["criteria"],"ctc");
		elseif ($criteria == "Industry Type" || $criteria == "IndustryLoggedIn")
			$resultarr[$i]["criterialabel"] = get_farea_bms($myrow["criteria"],"indtype");
		elseif ($criteria == "Category")
			$resultarr[$i]["criterialabel"] = get_farea_bms($myrow["criteria"],"category");
		elseif ($criteria == "IP")
			$resultarr[$i]["criterialabel"] = get_farea_bms($myrow["criteria"],"city");
		else
			$resultarr[$i]["criterialabel"] = $myrow["criteria"];
		$i++;	
	}
	$returnarr["resultarray"] = $resultarr;
	$returnarr["overallimpressions"] = $total;
	return $returnarr;
}

/***************************************************************************************
	Fetch the company id corresponding to a user id
	input: userid
	output: companyid
****************************************************************************************/
function getCompanyId($userid)
{
	global $dbsums;
	$i =0;
	$sql = "select company_id from clientprofile.client_reg where user_id = '$userid'";
	$res = mysql_query($sql) or logErrorBms ("bms_mis.php :getCompanyId:1: Could not select company id<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	while($myrow = mysql_fetch_array($res))
	{
		$companyidarr[$i] = $myrow["company_id"];
		$i++;
	}
	$companyid = implode("','",$companyidarr);
	return $companyid;
}

/***************************************************************************************
	Fetch an array of campaign constituents in a campaign
	input: campaignid
	output: string of campaign entities(e.g. 3 banners, 4 popups)
***************************************************************************************/
function getCampaignEntities($campaignid)
{
	global $dbbms,$bannerclassarr;

	$campaignentities = array();
	$sql = "select BannerClass, count(*) as BannerCount from bms2.BANNER where CampaignId='$campaignid' group by BannerClass";
	$res=mysql_query($sql,$dbbms) or logErrorBms ("bms_mis.php :getCampaignEntities :2: Could not get banner details<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);

	$campaignentitiesarr = array();
	$campaignentities = array();

	while ($myrow = mysql_fetch_array($res))
	{
		$bannerclass = $myrow["BannerClass"];
		$bannercount = $myrow["BannerCount"];

		foreach ($bannerclassarr as $key=>$value)
		{
			if (in_array("$bannerclass",$value))
				$campaignentitiesarr["$key"] = $campaignentitiesarr["$key"]+$bannercount;
		}
	}
	foreach($campaignentitiesarr as $key=>$value)
	{
		$campaignentities[]="$value $key";
	}
	return $campaignentities;		
}

/**************************************************************************************
	Fetchtotal impressions of a campaign
	input: campaignid
	output: total impressions of campaign
**************************************************************************************/
function getCampaignImpressions($campaignid)
{
	global $dbbms;

	$sql = "select sum(BannerServed) as totalimp from bms2.BANNER where CampaignId = '$campaignid'";
	$res = mysql_query($sql,$dbbms) or logErrorBms ("mis.php :getCampaignImpressions :3: Could not get campaign impressions<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	$myrow = mysql_fetch_array($res);
	$totalimp = $myrow["totalimp"];

	if ($totalimp)
		return $totalimp;
	else
		return 0;
}

/**************************************************************************************
	Fetch an array with the campaign entities and impressions added to the array
	input: array of campaign details
	output: array formatted according to requirements
**************************************************************************************/
function formatCampaignDetails(&$campaignarr)
{
	for ($i = 0;$i < count($campaignarr);$i++)
	{
		$campaignarr[$i]["sno"]=$i+1;
		$campaignarr[$i]["campaignentities"]  = getCampaignEntities($campaignarr[$i]["campaignid"]);
		$campaignarr[$i]["campaignimpserved"] = getCampaignImpressions($campaignarr[$i]["campaignid"]);

	}
}

/**************************************************************************************
	Fetch an array of campaign details of a company
	input: companyid
	output: array of campaign details
***************************************************************************************/
function getCampaignsMis($companyid)
{
	global $dbbms;
	$sql = "select * from bms2.CAMPAIGN  where  CampaignStatus in ('active','deactive','served')  and CompanyId IN ('$companyid') order by CampaignEntryDate desc";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_mis.php:getCampaignsMis:3: Could not get status wise campaign details. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$i = 0;
	while ($myrow = mysql_fetch_array($res))
	{
		$campaigndetails[$i]["campaignid"] = $myrow["CampaignId"];
		$campaigndetails[$i]["campaignname"] = $myrow["CampaignName"];
		$campaigndetails[$i]["campaigntype"] = $myrow["CampaignType"];
		$campaigndetails[$i]["campaignstatus"] = $myrow["CampaignStatus"];
		$campaigndetails[$i]["campaignentrydate"] = $myrow["CampaignEntryDate"];
		$campaigndetails[$i]["companyid"] = $myrow["CompanyId"];
		$campaigndetails[$i]["campaignimpression"] = $myrow["CampaignImpressions"];
		$campaigndetails[$i]["transactionid"] = $myrow["TransactionId"];
 		$campaigndetails[$i]["executiveid"] = $myrow["CampaignExecutiveId"];
		$campaigndetails[$i]["campaignstartdate"] = $myrow["CampaignStartDt"];
		$campaigndetails[$i]["campaignenddate"] = $myrow["CampaignEndDt"];
		$i++;
	}
	return $campaigndetails;
}

/****************************************************************************************
	Fetch total impressions served by a banner
	input: bannerid
	output: banner impressions
****************************************************************************************/
function getBannerImpressions($bannerid)
{
	global $dbbms;
	$sql = "select BannerServed as totalimp from bms2.BANNER where BannerId='$bannerid'";
	//echo $sql="select BannerServed as totalimp from bms2.BANNERHEAP where BannerId='$bannerid'";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_mis.php :getBannerImpressions:1: Could not select total impressions <br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	$myrow = mysql_fetch_array($res);

	$totalimp = $myrow["totalimp"];
	if ($totalimp)
		return $totalimp;
	else
		return 0;
}

/****************************************************************************************
	Fetch total clicks served by a banner
	input: bannerid
	output: banner clicks

****************************************************************************************/
function getBannerClicks($bannerid)
{
	global $dbbms;
	$sql = "select sum(Clicks) as totalclicks from bms2.BANNERMIS where BannerId='$bannerid'";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_mis.php :getBannerClicks:1: Could not select total clicks <br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	$myrow = mysql_fetch_array($res);

	$totalimp = $myrow["totalclicks"];
	if ($totalimp)
		return $totalimp;
	else
		return 0;
}

/****************************************************************************************
	Fetch ctr of a banner
	input: bannerimpressions, banner clicks
	output: banner ctr
****************************************************************************************/
function getBannerCtr($bannerimpressions,$bannerclicks)
{
	global $dbbms;
	if ($bannerimpressions)
		return round((($bannerclicks*100)/$bannerimpressions),2);
	else
		return 0;
}

/****************************************************************************************
        Fetches criteria of a particular banner
        input: bannerid , campaignid , banner start and end date 
        output: banner criteria string
****************************************************************************************/
function getBannerCriteria($bannerid,$campaignid,$bannerstartdate,$bannerenddate)
{
	global $dbbms,$mapcriteria,$id;
	$bannercriteriaarr = showcriterias($bannerid);
	$bannercriteriastr = $bannercriteriaarr["criteria"];
	if ($bannercriteriastr == "Default")
		return $bannercriteriastr;
	else
		$bannercriteria=$bannercriteriaarr["selected"];
	
	$bannerstr="";
	if ($bannercriteria)
	{
		foreach ($bannercriteria as $criteria=>$criteriavalue)
		{	
			//$bannerstr.="<a href=\"bms_criteriamis.php?id=$id&criteria=$mapcriteria[$criteria]&bannerid=$bannerid&campaignid=$campaignid&bannerstartdate=$bannerstartdate&bannerenddate=$bannerenddate\">".$mapcriteria["$criteria"]."</a><BR />";
			$bannerstr.=$mapcriteria["$criteria"]."<BR />";
		}
	}
	else
		$bannerstr="";
	return $bannerstr;
}

/****************************************************************************************
	Fetches array of details of a banner to be sent in a mailer
	input: mailerid
	output: array of mails sent and response corresponding to that mailer
****************************************************************************************/
function getMailerData($mailerid)
{
	$dbmmm=getConnectionMMM();
	global $mailtypearr;
	$sql="select TOTAL_RESPONSE as response , MAIL_TYPE as type ,SENT as sent from mmmnew.MAIN_MAILER where MAILER_ID='$mailerid'";
	$res=mysql_query($sql,$dbmmm) or logErrorBms("bms_mis.php :getMailerData:1: Could not select data from mailer <br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	if(mysql_num_rows($res))
	{
		$myrow=mysql_fetch_array($res);
		$mailerarr["response"]=$myrow["response"];
		$mailerarr["sent"]=$myrow["sent"];
		$mailerarr["type"]=$mailtypearr["$myrow[type]"];
		
	}
	mysql_close($dbmmm);
	if($mailerarr)
		return $mailerarr;
	else 
		return NULL;
		
}
/****************************************************************************************
        Fetches array of details of a campaign 
        input: campaignid
        output: array of details
****************************************************************************************/
function getCampaignNameType($campaignid)
{
	global $dbbms;
	$campaignarr = getCampaigns("","",$campaignid);
	$campaigndetailsarr = $campaignarr[0];
	return $campaigndetailsarr;
}

/****************************************************************************************
        Fetches array of details of a campaign in .xls format viz impression etc.
        input: campaign detail array
        output: string of details in .xls format
****************************************************************************************/
function getExcelStringCampaigns($campaignarr)
{
	$excelstr.="S.NO\tCampaign Name\tDate Range\tConstituents\tCampaign Type\tImpressions Desired\tImpressions Served\t Date Wise Result\n";

	if($campaignarr)
	{
		foreach($campaignarr as $arr)
		{
			$excelstr.="$arr[sno]\t";
			$excelstr.="$arr[campaignname]\t";
			$excelstr.="$arr[campaignstartdate]-$arr[campaignenddate]\t";
			if($arr["campaignentities"])
			{
				$entitystr="";
				foreach($arr["campaignentities"] as $campaignentities)
				{
					$entitystr.=$campaignentities." , ";
				}
			}
			$entitystr=substr($entitystr,0,-2);
			$excelstr.="$entitystr\t";
			$excelstr.="$arr[campaigntype]-based\t";
			if($arr["campaigntype"]=="impression")
			{
				$excelstr.="$arr[campaignimpression]\t";
			}
			else 
				$excelstr.="\t";
			$excelstr.="$arr[campaignimpserved]\t";
			$excelstr.="\t";
			$excelstr.="\n";
			
		}
	}
	return $excelstr;
}
/****************************************************************************************
        Fetches array of details of a campaign in .xls format 
        input: campaign detail array
        output: string of details in .xls format
****************************************************************************************/
function getExcelCampaignDetails($campaignarr)
{
	$excelstr="";
	$mailerstr="";
	$excelstr.="S.NO\tType\tBanner Preview\tSize (pixels)\tLocation (Region/Zone)\tImpressions\tClicks\tCTR\tCriteria\n";
	//$excelstr.="S.NO\tType\tBanner Preview\tSize (pixels)\tLocation (Region/Zone)\tImpressions\tCriteria\t\n";
	if($campaignarr)
	{
 	foreach($campaignarr as $arr)
	{
		if ($arr["bannertype"]!="Mailer" && $arr["show"]=="true")
		{
			$excelstr.="$arr[sno]\t";
			$excelstr.="$arr[bannertype]\t";
			$excelstr.="$arr[bannergif]\t";
			$excelstr.="$arr[heightwidth]\t";
			$excelstr.="$arr[regionname]-$arr[zonename]\t";
			$excelstr.="$arr[bannerimpressions]\t";
			$excelstr.="$arr[bannerclicks]\t";
			$excelstr.="$arr[bannerctr]\t";
			//$excelstr.="$arr[bannerclicks]\t";
			//$excelstr.="$arr[bannerctr]\t";
			$excelstr.=strip_tags($arr["bannercriteria"])."\t";
			$excelstr.="\n";
		}
	}
	
	$excelstr.="\t\t\t\t\t\t\t\n";
	$excelstr.="\t\t\t\t\t\t\t\n";
	//$excelstr.="&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\n";
	//$excelstr.="&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\t&nbsp;\n";
	foreach($campaignarr as $arr)
	{
		if ($arr["bannertype"]=="Mailer" && $arr["show"]=="true")
		{
			$mailerstr.="$arr[sno]\t";
			$mailerstr.="$arr[bannertype]\t";
			$mailerstr.="$arr[bannergif]\t";
			$mailerstr.="$arr[heightwidth]\t";
			$mailerstr.="$arr[regionname]-$arr[zonename]\t";
			$mailerstr.="$arr[mailertype]\t";
			$mailerstr.="$arr[mailersent]\t";
			$mailerstr.="$arr[maileropenrate]%\t";
			$mailerstr.="$arr[bannerclicks]\n";
			//$mailerstr.="$arr[bannerclicks]\n";
			
		}
	}
	if($mailerstr)
	{
		$excelstr.="\t\t\t\t\t\t\t\n";
		$excelstr.="S.NO\tType\tBanner Preview\tSize (pixels)\tLocation (Region/Zone)\tMailerType\tMailerSent\tMailerOpenRate\tClicks\n";
		$excelstr.=$mailerstr;
	}
	}
	return $excelstr;

}
/**********************************************************************************************
        Fetches array of details of a campaign in .xls format viz impression etc based on date
        input: campaign detail array
        output: string of details in .xls format
*********************************************************************************************/
function getExcelDateCriteria($resultarr)
{
	$excelstr="S NO\tDate\tImpressions\n";
	if($resultarr)
	{
		foreach($resultarr as $result)
		{
			$excelstr.="$result[sno]\t";
			$excelstr.="$result[date]\t";
			$excelstr.="$result[impressions]\n";
		}
	}
	return $excelstr;
}
?>
