<?php
/*************************bms_adminbannermis.php****************************************************************************/
  /*
   *  Created By         : Shobha Kumari
   *  Last Modified By   : Shobha Kumari
   *  Description        : used for displaying mis of banners in a campaign for admin
   *  Includes/Libraries : bms_connect.php
			 : bms_mis.php
/***************************************************************************************************************************/

include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
//$data=authenticatedBms($id,$ip,"banadmin");
if ($site != '99acres')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");
$smarty->assign("site",$site);

$bannerstoshow=array("live","deactive","deactivesums","served");
$bannersnottoshow=array("newrequest","booked","ready","cancel","expired");
$mailtypearr=array("hrm"=>"Hr Headlines Mailer",
				   "hcm"=>"Hard Code Mailer",
				   "tpm"=>"Template Mailer",
				   "urm"=>"URL Mailer",
				   "crm"=>"Career Headlines Mailer",
				   "bm"=>"Business Mailer",
				   "ucm"=>"Update CV Mailer",
				   "nja"=>"new Job alert Mailer",
				   "erm"=>"Easy Register Mailer",
				   );

$mapcriteria=array("Exp"=>"Experience",
					"Farea"=>"Functional Area",
					"Industry"=>"Industry Type",
					"Ctc"=>"CTC",
					"Age"=>"Age",
					"IP"=>"IP",
					"Keywords"=>"Keywords",
					"IndustryResman"=>"IndustryLoggedIn",
					"Categories"=>"Category",
					"Location"=>"Location",
					"Gender"=>"Gender",
					"FareaResman"=>"FareaLoggedIn",
					"ExpResman"=>"ExperienceLoggedIn",
					
					
				);

function FormattCampaignDetails(&$campaigndetailsarr,$campaignid)
{
	global $dbbms,$bannerclassarr,$bannerstoshow;
	for($i=0,$j=1,$k=1;$i<count($campaigndetailsarr);$i++)
	{
 		$campaigndetailsarr[$i]["bannertype"]=FormatBannerClass($campaigndetailsarr[$i]["bannerclass"]);
		if(in_array($campaigndetailsarr[$i]["bannerstatus"],$bannerstoshow))
		{
			$bannerimpressions=getBannerImpressions($campaigndetailsarr[$i]["bannerid"]);
			if($bannerimpressions)
			{	
				/*if($campaigndetailsarr[$i]["bannertype"]=="Mailer")
				{
					$campaigndetailsarr[$i]["sno"]=$j++;
					$mailerarr=getMailerData($campaigndetailsarr[$i]["mailerid"]);
					$campaigndetailsarr[$i]["mailersent"]=$mailerarr["sent"];
					$campaigndetailsarr[$i]["mailertype"]=$mailerarr["type"];
					if($mailerarr["sent"])
						$campaigndetailsarr[$i]["maileropenrate"]=round(($mailerarr["response"]*100/$mailerarr["sent"]),2);
					else
					{
							
						$campaigndetailsarr[$i]["maileropenrate"]=0;
					}
					$campaigndetailsarr[$i]["bannerclicks"]=getBannerClicks($campaigndetailsarr[$i]["bannerid"]);
					$campaigndetailsarr[$i]["show"]=true;
					$returnarr["totalmailer"]+=1;
				}
				else*/
				{
					$campaigndetailsarr[$i]["show"]=true;
					$campaigndetailsarr[$i]["sno"]=$k++;
					$campaigndetailsarr[$i]["bannerimpressions"]=$bannerimpressions;
					$campaigndetailsarr[$i]["bannercriteria"]=getBannersCriteria($campaigndetailsarr[$i]["bannerid"],$campaignid,$campaigndetailsarr[$i]["bannerstartdate"],$campaigndetailsarr[$i]["bannerenddate"]);
					$campaigndetailsarr[$i]["bannerclicks"]=getBannerClicks($campaigndetailsarr[$i]["bannerid"]);
					$campaigndetailsarr[$i]["bannerctr"]=getBannerCtr($campaigndetailsarr[$i]["bannerimpressions"],$campaigndetailsarr[$i]["bannerclicks"]);
					$returnarr["totalclicks"]+=$campaigndetailsarr[$i]["bannerclicks"];
					$returnarr["totalimpressions"]+=$campaigndetailsarr[$i]["bannerimpressions"];
					$returnarr["totalbanner"]+=1;

				}
			}
			else
				$campaigndetailsarr[$i]["show"]=false;
			$zoneparam=getZoneParam($campaigndetailsarr[$i]["zoneid"]);
			$campaigndetailsarr[$i]["heightwidth"]=$zoneparam["heightwidth"];
			$campaigndetailsarr[$i]["regionname"]=$zoneparam["regionname"];
			$campaigndetailsarr[$i]["zonename"]=$zoneparam["zonename"];
		}
		else
			$campaigndetailsarr[$i]["show"]=false;
	}
	return $returnarr;
	
}

function getBannersCriteria($bannerid,$campaignid,$bannerstartdate,$bannerenddate)
{
	global $dbbms,$mapcriteria,$id,$campaignname;
	$bannercriteriaarr=showcriterias($bannerid);
	$bannercriteriastr=$bannercriteriaarr["criteria"];
	if($bannercriteriastr=="Default")
		return $bannercriteriastr;
	else
		$bannercriteria=$bannercriteriaarr["selected"];
	$bannerstr="";
	if($bannercriteria)
	{
		foreach($bannercriteria as $criteria=>$criteriavalue)
		{
			$bannerstr.=$mapcriteria["$criteria"]."<BR />";
			//$bannerstr.="<a href=\"bms_admincriteriamis.php?id=$id&criteria=$mapcriteria[$criteria]&bannerid=$bannerid&campaignid=$campaignid&campaignname=$campaignname&bannerstartdate=$bannerstartdate&bannerenddate=$bannerenddate\">".$mapcriteria["$criteria"]."</a><BR />";
			$bannerstr.="";
		}
	}
	else
		$bannerstr="";
	return $bannerstr;
}
if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$smarty->assign("id",$id);
	$smarty->assign("campaignid",$campaignid);	
	$campaigndetailsarr=getCampaignDetails($campaignid,"all");
	$clicksimpshow=FormattCampaignDetails($campaigndetailsarr,$campaignid);
	$campaignarr=getCampaignNameType($campaignid);
	$smarty->assign("campaignname",$campaignarr["campaignname"]);
	$smarty->assign("campaigntype",$campaignarr["campaigntype"]);
	$smarty->assign("totalclicks",$clicksimpshow["totalclicks"]);
	$smarty->assign("totalimpressions",$clicksimpshow["totalimpressions"]);
	$smarty->assign("totalbanner",$clicksimpshow["totalbanner"]);
	$smarty->assign("totalmailer",$clicksimpshow["totalmailer"]);
	$smarty->assign("campaigndetailsarr",$campaigndetailsarr);
	if($downloadcampaigndetails_x)
	{
		$echostr=getExcelCampaignDetails($campaigndetailsarr);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition:attachment; filename=campaigndetails.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $echostr;
		
	}
	else
	{	
		$smarty->assign("campaignname",$campaignname);
		$smarty->display("./$_TPLPATH/bms_adminbannersmis.htm");
	}
}
else
{
	TimedOutBms();
}
