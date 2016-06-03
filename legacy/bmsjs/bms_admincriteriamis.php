<?php
/********************************************************bms_admincriteriamis.php*******************************************/
  /*
   *  Created By         : Shobha Kumari 
   *  Last Modified By   : Shobha Kumari
   *  Description        : used for displaying criteria wise mis of a banner
   *  Includes/Libraries : bms_connect.php
			 : bms_mis.php
****************************************************************************************************************************/
include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");

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
	
	if($action=="viewdatewise")
	{
		// bannerid, criteria , criteria value passed from link
		include("bms_datewisemis.php");
		exit;
	
	}
	else
	{
		$sqlcriteria=getCriteriaQuery($criteria,$bannerid);
		$returnarr=getCriteriaResult($sqlcriteria,$criteria);
		$resultarr=$returnarr["resultarray"];
		$smarty->assign("criteria",$criteria);
		$smarty->assign("resultarr",$resultarr);
		$smarty->assign("overallimpressions",$returnarr["overallimpressions"]);
		if($downloadcriteriamis_x)
		{
			$echostr=getExcelCriteria($resultarr);
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition:attachment; filename=criteriamis.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $echostr;
		}
		else
		{	
			$smarty->display("./$_TPLPATH/bms_admincriteriamis.htm");
		}
	}
}
else
{
	TimedOutBms();
}
?>
