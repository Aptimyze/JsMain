<?php
include ("./includes/bms_connect.php");
include ("bms_checkavail.php");
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
$data=authenticatedBms($id,$ip,"banadmin");
global $smarty,$_TPLPATH;
$id=$data["ID"];
if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("id",$id);
	assignRegionZoneDropDowns("","showcriteria");
	$smarty->assign("days",$days);

	if($calculate_x && $region!="select" && $zone!="select")
	{
		$regionval = explode('|',$region);
		$zoneval = explode('|',$zone);

		//Added By lavesh on 20july 2007.
		$sql="SELECT  ZoneBanWidth , ZoneBanHeight FROM bms2.ZONE WHERE ZoneId='$zoneval[0]'";
		$res= mysql_query($sql);
		if($row=mysql_fetch_array($res))
		{
			$smarty->assign("size",$row["ZoneBanWidth"]." x ".$row["ZoneBanHeight"]);
		}

		if($VIEW_MIS=='D')
		{
			$smarty->assign("flag_mis",1);
			$endmonth=$month;
			$smarty->assign("Criteria",'Daily View For '.$month.'/'.$year);
		}
		elseif($VIEW_MIS=='M')
		{
			$month=01;
			$endmonth=12;
			$year=$myear;
			$smarty->assign("Criteria",'Monthly View For '.$year);
		}
		$smarty->assign("startday",01);
		$smarty->assign("startmonth",$month);
		$smarty->assign("startyear",$year);
		$smarty->assign("endday",31);
		$smarty->assign("endmonth",$endmonth);
		$smarty->assign("endyear",$year);

		$start_dt=$year.'-'.$month.'-01';
		$end_dt=$year.'-'.$endmonth.'-31';
		list_banner($zoneval[0],$start_dt,$end_dt);	
		$smarty->assign("banners_details",$smarty->fetch("./$_TPLPATH/banner_preview_calc.htm"));

		//$sql="SELECT sum( r.Impressions ) as IMPRESSIONS , sum( r.Clicks ) as  CLICKS FROM bms2.BANNER b,bms2.BANNERMIS r WHERE b.ZoneId = '$zoneval[0]' AND b.BannerPriority = '1' AND  r.Date BETWEEN '$start_dt' and '$end_dt' AND r.BannerId=b.BannerId";
		$sql="SELECT sum( r.Impressions ) as IMPRESSIONS , sum( r.Clicks ) as  CLICKS FROM bms2.BANNER b,bms2.BANNERMIS r WHERE b.ZoneId = '$zoneval[0]' AND r.Date BETWEEN '$start_dt' and '$end_dt' AND r.BannerId=b.BannerId";
		if($res= mysql_query($sql))
		{
			if($row=mysql_fetch_array($res))
			{	
				$resultarr["Count"]= $row["IMPRESSIONS"];
				$resultarr["Clicks"]= $row["CLICKS"];
			}
		
			$zone_sql = "SELECT ZoneName FROM bms2.ZONE WHERE ZoneId = '$zoneval[0]'";
			$zone_res = mysql_query($zone_sql);
			$zone_row = mysql_fetch_array($zone_res);
			$resultarr["Zone"] = $zone_row["ZoneName"];

			$reg_sql = "SELECT RegName FROM bms2.REGION WHERE RegId = '$regionval[0]'";
			$reg_res = mysql_query($reg_sql);
			$reg_row = mysql_fetch_array($reg_res);
			$resultarr["Region"]    = $reg_row["RegName"];
			$smarty->assign("days",$days);

			$smarty->assign("resultarr",$resultarr);
			$smarty->display("./$_TPLPATH/bms_inventory.htm");
		}
		else
		{
			logErrorBms("bms_inventory.php:Inventory:1:Could not get inventory result. <br><!--$sql(".mysql_error().")-->:".mysql_errno(),$sql,"exit","NO");
		}
	}
	else
	{	
		//Added By lavesh
		$curr_dt=date("Y-m-d");
		$start=explode("-",$curr_dt);
		$startyear=$start[0];
		$startmonth=$start[1];

		for($i=0;$i<12;$i++)
		{
			if($i<9)
				$mmarr[$i]='0'.($i+1);			
			else
				$mmarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
		{
			$yyarr[$i]=$i+2005;
			if($yyarr[$i]==$startyear)
				break;
		}
		$smarty->assign("startmonth",$startmonth);
	        $smarty->assign("startyear",$startyear);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("fill_function",1);
		//Ends Here.
		$smarty->display("./$_TPLPATH/bms_inventory.htm");
	}
}
else
{
        TimedOutBms();
}

//added by lavesh
function list_banner($zoneval,$start_dt,$end_dt)
{
	global $smarty,$dbbms;
	$j=1;
	$sql="SELECT c.CampaignName,c.CampaignId,b.BannerStatus,r.BannerId,BannerGif,BannerClass,BannerStartDate,BannerEndDate,sum(r.Impressions ) as IMPRESSIONS , sum( r.Clicks ) as  CLICKS FROM bms2.BANNER b,bms2.BANNERMIS r,bms2.CAMPAIGN c WHERE b.ZoneId = '$zoneval' AND  r.Date BETWEEN '$start_dt' and '$end_dt' AND r.BannerId=b.BannerId AND c.CampaignId=b.CampaignId GROUP BY r.BannerId HAVING (IMPRESSIONS >0 OR CLICKS >0) ORDER BY IMPRESSIONS DESC";	
	$res=mysql_query($sql,$dbbms) or die(mysql_error());
        while($row=mysql_fetch_array($res))
	{
		
		$banner=$row["BannerId"];
		if(is_array($bannarr))
		{
			if(!in_array($banner,$bannarr))
			{
				$cnt=count($bannarr);
				$bannarr[$cnt]=$banner;
			}
		}
		else
		{
			$bannarr[0]=$banner;
		}

		$rowcrits=showcriterias($banner);
		$resarr[$banner]["bannerid"]=$banner;
		$criteria=$rowcrits["criteria"];
		$resarr[$banner]["criteria"]=$criteria;	
		$resarr[$banner]["sno"]=$j++;
		$resarr[$banner]["startdt"]=$row["BannerStartDate"];
                $resarr[$banner]["enddt"]=$row["BannerEndDate"];
		$resarr[$banner]["campaign"]=$row["CampaignName"];
		$resarr[$banner]["campaignid"]=$row["CampaignId"];
		$resarr[$banner]["status"]=$row["BannerStatus"];
                $gif=$row["BannerGif"];
		$class=$row["BannerClass"];
		if($class=="Image")
		{
			$resarr[$banner]["banner"]="<a href=$gif target=_blank><Img src=\"$gif\" border=0 width=100 height=25></a>";
		}
		elseif ($class=="Flash")
		{        
			$resarr[$banner]["banner"]="<object><embed src=\"$gif\" width=100 height=25></embed></object>";
		}
		elseif ($class=="Popup" || $class=="Popunder")
		{
			$resarr[$banner]["banner"]="<a href=\"$gif\">View Popup/Popunder</a>";
		}

		$resarr[$banner]["impressions"]=$row["IMPRESSIONS"];
		//$a=$a+$row["IMPRESSIONS"];
		//$b=$b+$row["CLICKS"];
		$resarr[$banner]["clicks"]=$row["CLICKS"];
		if($row["IMPRESSIONS"])
		{
			$resarr[$banner]["ctr"]=round( ($row["CLICKS"]/$row["IMPRESSIONS"])*100 ,2);
		}
		else
			$resarr[$banner]["ctr"]='N/A';
		//$resarr[$banner][""]=$row[];
	}
	//echo $a.'--'.$b;
	if($bannarr)
		$banners=implode(",",$bannarr);
	$smarty->assign('banners',$banners);
	$smarty->assign('resarr',$resarr);
        $smarty->assign('bannarr',$bannarr);
	$smarty->assign("banners_details",$smarty->fetch("./$_TPLPATH/banner_preview_calc.htm"));
}
?>
