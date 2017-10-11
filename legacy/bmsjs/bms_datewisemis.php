<?php
/*************************bms_datewisemis.php***********************************/
  /*
   *  Created By         : Shobha Kumari
   *  Last Modified By   : Shobha Kumari
   *  Description        : used for displaying date wise mis of a banner
   *  Includes/Libraries : bms_connect.php
   						:	bms_mis.php
*/
include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"client");
$mapcriteriasql=array("Experience"=>"Exp",
						"Keywords"=>"Keyword",
						"Age"=>"Age",
						"Functional Area"=>"Farea",
						"Industry Type"=>"Indtype",
						"IndustryLoggedIn"=>"ResmanIndustry",
						"Location"=>"Location",
						"Category"=>"Categories",
						"IP"=>"City",
						"CTC"=>"CTC",
						"Age"=>"Age",
						"Gender"=>"Gender",
						"ExperienceLoggedIn"=>"ResmanExp",
						"FareaLoggedIn"=>"ResmanFarea"
					);
						

function assignDates($startdate,$enddate)
{
		global $smarty;
		$smarty->assign("daysarr",getDaysBms());
		$smarty->assign("monthsarr",getMonthsBms());
		$smarty->assign("yearsarr",getYearsBms());
		$selstartarr=explode("-",$startdate);
		$selendarr=explode("-",$enddate);
		$smarty->assign("selendday",$selendarr[2]);
		$smarty->assign("selendmonth",$selendarr[1]);
		$smarty->assign("selendyear",$selendarr[0]);
		$smarty->assign("selstartday",$selstartarr[2]);
		$smarty->assign("selstartmonth",$selstartarr[1]);
		$smarty->assign("selstartyear",$selstartarr[0]);

}
function checkDates($startdate,$enddate,$criteria)
{
	global $smarty;
	$check=1;
	if($enddate<$startdate)
	{	
		$check=0;
		$smarty->assign("errormsg","Please select correct duration of dates .<BR /> Please <a href=\"javascript:history.go(-1)\">click here</a> to go back and select the dates again."); 
	}
	elseif($criteria&&($startdate<"2005-04-18"))
	{
		
		$check=0;
		$smarty->assign("errormsg","Impression-wise break up is available only after 18 apr . <BR /> Please <a href=\"javascript:history.go(-1)\">click here</a> to go back and select the dates again."); 
	}
	else
	{
		$startdatearr=explode("-",$startdate);
		$enddatearr=explode("-",$enddate);
		$modval=$enddatearr[1]-$startdatearr[1];
		$months=12;
		$mod=$modval % $months;
		if($mod<0)
		{
			$mod=$months+$mod;
		}
		
		if($mod>=4)
		{
			$check=0;
			$smarty->assign("errormsg","Please duration of dates within an interval of 2 months. <BR /> Please <a href=\"javascript:history.go(-1)\">click here</a> to go back and select the dates again."); 
		}
	}
	return $check;

}

function formatCriteriaValue($criteria,$criteriavalue)
{
	if($criteria=="Functional Area"||$criteria=="FareaLoggedIn")
			return get_farea_bms($criteriavalue,"farea");
	elseif($criteria=="Industry Type"||$criteria=="IndustryLoggedIn")
			return get_farea_bms($criteriavalue,"indtype");
	elseif($criteria=="Category")
			return get_farea_bms($criteriavalue,"category");
	elseif($criteria=="IP")
			return get_farea_bms($criteriavalue,"city");
	else
			return $criteriavalue;

}
if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$smarty->assign("id",$id);
	$smarty->assign("bannerid",$bannerid);
	$smarty->assign("campaignid",$campaignid);
	$smarty->assign("bannerstartdate",$bannerstartdate);
	$smarty->assign("bannerenddate",$bannerenddate);
	$smarty->assign("criteria",$criteria);
	$smarty->assign("criteriavalue",$criteriavalue);

	if($showresults_x||$downloaddatecriteriamis_x||$downloaddatemis_x)
	{
		$startdate=$startyear."-".$startmonth."-".$startday;
		$enddate=$endyear."-".$endmonth."-".$endday;
		if(checkDates($startdate,$enddate,$criteria))
		{
			$smarty->assign("startday",$startday);
			$smarty->assign("startmonth",$startmonth);
			$smarty->assign("startyear",$startyear);
			$smarty->assign("endday",$endday);
			$smarty->assign("endmonth",$endmonth);
			$smarty->assign("endyear",$endyear);
			if($criteria)
			{

				$criteriasql=getCriteriaDatewiseQuery($criteria,$criteriavalue,$bannerid,$startdate,$enddate);
				$resultarr=getCriteriaDatewiseResult($criteriasql);
				$criterialabel=formatCriteriaValue($criteria,$criteriavalue);
				$smarty->assign("criterialabel",$criterialabel);
				$smarty->assign("resultarr",$resultarr);
				if($downloaddatecriteriamis_x)
				{
					$echostr=getExcelDateCriteria($resultarr);
					header("Content-Type: application/vnd.ms-excel");
					header("Content-Disposition:attachment; filename=criteriadatewise.xls");
					header("Pragma: no-cache");
					header("Expires: 0");
					echo $echostr;
				}
				else
				{

					$smarty->display("./$_TPLPATH/bms_criteriadatewise.htm");
				}
			}
			else
			{
				$resultarr=viewDateWiseResults($bannerid,$startdate,$enddate);
				if($downloaddatemis_x)
				{
					$echostr=getExcelDate($resultarr);
					header("Content-Type: application/vnd.ms-excel");
					header("Content-Disposition:attachment; filename=datewisemis.xls");
					header("Pragma: no-cache");
					header("Expires: 0");
					echo $echostr;
				}
				else
				{
					$smarty->assign("resultarr",$resultarr);
					$smarty->display("./$_TPLPATH/bms_datewisemis.htm");
				}
			}
		}
		else
			$smarty->display("./$_TPLPATH/bms_error.htm");
	}
	else
	{
		$currentdate=date("Y-m-d");
		if($bannerenddate>$currentdate)
			$selenddate=$currentdate;
		else
			$selenddate=$bannerenddate;
		
		$selstartdate=$bannerstartdate;
		assignDates($selstartdate,$selenddate);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("criteriavalue",$criteriavalue);
		$smarty->display("./$_TPLPATH/bms_selectdate.htm");
	}
}
else
	TimedOutBms();

