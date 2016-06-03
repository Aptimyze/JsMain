<?php
/*********************************************bms_admindatewisemis.php******************************************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : used for displaying date wise mis of a banner for admin
   *  Includes/Libraries : bms_connect.php
			 : bms_mis.php
****************************************************************************************************************************/
include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$month_label=array("1" =>"January",
                   "2" => "February",
                   "3" => "March",
                   "4" => "April",
                   "5" => "May",
                   "6" => "June",
                   "7" => "July",
                   "8" => "August",
                   "9" => "September",
                   "10" => "October",
                   "11" => "November",
                   "12" => "December");
$ip=FetchClientIP();
if ($site != '99acres')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");
$smarty->assign("site",$site);
//$data=authenticatedBms($id,$ip,"banadmin");
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

function checkDates($startdate,$enddate)
{
	if($enddate<$startdate)
		return 0;
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
			return 0;
		else
			return 1;	
	}
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

	$sql="SELECT CampaignName FROM bms2.CAMPAIGN WHERE CampaignId='$campaignid'";
        $res=mysql_query($sql);
        $row=mysql_fetch_array($res);
        $campaignname=$row["CampaignName"];

	$smarty->assign("id",$id);
	$smarty->assign("bannerid",$bannerid);
	$smarty->assign("campaignid",$campaignid);
	$smarty->assign("campaignname",$campaignname);
	$smarty->assign("bannerstartdate",$bannerstartdate);
	$smarty->assign("bannerenddate",$bannerenddate);
	$smarty->assign("criteria",$criteria);
	$smarty->assign("criteriavalue",$criteriavalue);

	if($showresults_x||$downloaddatecriteriamis_x||$downloaddatemis_x)
	{
		$startdate=$startyear."-".$startmonth."-".$startday;
		$enddate=$endyear."-".$endmonth."-".$endday;

		if(checkDates($startdate,$enddate) || $flag_year)
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
				$smarty->assign("criteria",$criteria);
				//$criterialabel=formatCriteriaValue($criteria,$criteriavalue);
				//$smarty->assign("criterialabel",$criterialabel);
				if($downloaddatecriteriamis_x)
				{
					$eichostr=getExcelDateCriteria($resultarr);
					header("Content-Type: application/vnd.ms-excel");
					header("Content-Disposition:attachment; filename=criteriadatewise.xls");
					header("Pragma: no-cache");
					header("Expires: 0");
					echo $echostr;
				}
				else
				{
					$smarty->assign("resultarr",$resultarr);
					$smarty->display("./$_TPLPATH/bms_admincriteriadatewise.htm");
				}
			}
			else
			{	
				if (!$camp)	
					$resultarr=viewDateWiseResults($bannerid,$startdate,$enddate);
				else
				{
					$i = 0;
					$campaigndetailarr=getCampaignDetails($campaignid);
					if(!$flag_year)
					{
						if($invenory_mis)
							$sql = "select Date, sum(BANNERMIS.Clicks) as Clicks , sum(BANNERMIS.Impressions) as Impressions from bms2.BANNERMIS WHERE BannerId='$bannerid' and  Date >='$startdate' and Date <='$enddate' group by Date";	
						else
							$sql = "select BANNERMIS.Date as Date, sum(BANNERMIS.Clicks) as Clicks , sum(BANNERMIS.Impressions) as Impressions from bms2.BANNERMIS , BANNER where BANNER.CampaignID='$campaignid' and BANNERMIS.BannerId=BANNER.BannerId and BANNERMIS.Date >='$startdate' and BANNERMIS.Date <='$enddate' group by BANNERMIS.Date";
						$res = mysql_query($sql);
						while($myrow = mysql_fetch_array($res))
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
					}
					else
					{
						$smarty->assign("monthwise",1);
						$smarty->assign("endyear",$endyear);
						$sql = "Select sum(Clicks) as Clicks , sum(Impressions) as Impressions , MONTH(Date) as mm FROM bms2.BANNERMIS  WHERE BannerId='$bannerid' AND Date >='$startdate' and Date <='$enddate' GROUP BY mm";
        	                                if($res = mysql_query($sql))
						{
	                	                        while($myrow = mysql_fetch_array($res))
        	                	                {
	        	                                        $resultarr[$i]["sno"] = $i+1;
								$resultarr[$i]["date"] = $month_label[$myrow["mm"]];
								$resultarr[$i]["impressions"] = $myrow["Impressions"];
								$resultarr[$i]["clicks"] = $myrow["Clicks"];		
                                	        	        if ($myrow["Impressions"])
                                        	        		$resultarr[$i]["ctr"] = round((($myrow["Clicks"]*100)/$myrow["Impressions"]),2);
	                                                	else
		                                                        $resultarr[$i]["ctr"] = 0;
        		                                        $i++;
							}
						}
					}
				}
				//print_r($resultarr);
				$smarty->assign("resultarr",$resultarr);
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

					$smarty->display("./$_TPLPATH/bms_admindatewisemis.htm");
				}
			}
		}
		else
		{
			$smarty->assign("errormsg","Please select correct duration of dates and within an interval of 2 months. <BR />Please <a href=\"javascript:history.go(-1)\">click here</a> to go back and select the dates again."); 
			$smarty->display("./$_TPLPATH/bms_error.tpl");
		}
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
		$smarty->assign("camp",$camp);
		$smarty->display("./$_TPLPATH/bms_adminselectdate.htm");
	}
}
else
	TimedOutBms();
