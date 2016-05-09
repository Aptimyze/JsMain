<?php
/**************************************************bms_clientbannermis.php**************************************************/
  /*
   *  Created By         : Abhinav Katityar
   *  Last Modified By   : Abhinav Katityar
   *  Description        : used for displaying mis of banners in a campaign
   *  Includes/Libraries : ./includes/bms_connect.php
   			 : bms_mis.php
****************************************************************************************************************************/
include_once("./includes/bms_connect.php");
include_once("./includes/bms_mis.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"client");

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
					"CTC"=>"CTC",
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


function formatReturnCampaignDetails(&$campaigndetailsarr,$campaignid)
{
	global $dbbms,$bannerclassarr,$bannerstoshow;

	$arrcount=count($campaigndetailsarr);

	for($i=0,$j=1,$k=1;$i<$arrcount;$i++)
	{
 		$campaigndetailsarr[$i]["bannertype"]=FormatBannerClass($campaigndetailsarr[$i]["bannerclass"]);
		if(in_array($campaigndetailsarr[$i]["bannerstatus"],$bannerstoshow))
		{
			$bannerimpressions=getBannerImpressions($campaigndetailsarr[$i]["bannerid"]);
			//$bannerimpressions=1;
			if($bannerimpressions)
			{
				//changed by shiv on Apr 06, 2006 - no t yet integrated with MMM
				if(0)//$campaigndetailsarr[$i]["bannertype"]=="Mailer")
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
					//$campaigndetailsarr[$i]["bannerclicks"]=0;
					getBannerClicks($campaigndetailsarr[$i]["bannerid"]);
					$campaigndetailsarr[$i]["show"]=true;
					$returnarr["totalmailer"]+=1;
				}
				else
				{
					$campaigndetailsarr[$i]["show"]=true;
					$campaigndetailsarr[$i]["sno"]=$k++;
					$campaigndetailsarr[$i]["bannerimpressions"]=$bannerimpressions;
					$campaigndetailsarr[$i]["bannercriteria"]=getBannerCriteria($campaigndetailsarr[$i]["bannerid"],$campaignid,$campaigndetailsarr[$i]["bannerstartdate"],$campaigndetailsarr[$i]["bannerenddate"]);
					//$campaigndetailsarr[$i]["bannerclicks"]=0;
					//$campaigndetailsarr[$i]["bannerctr"]=0;
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


if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$smarty->assign("id",$id);
	$smarty->assign("campaignid",$campaignid);
	
	$sql = "SELECT Misoption FROM  bms2.CAMPAIGN WHERE CampaignId='$campaignid'";
        $res = mysql_query($sql) or logErrorBms("bms_bannermis.php: viewMisOption :1: Could not select Client MisOption for mis. <br>    <!--$sql--<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $row = mysql_fetch_array($res);
        $misoption = $row["Misoption"];
        $misoptionarr = explode(",",$misoption);

	$campaigndetailsarr=getCampaignDetails($campaignid,"all");
	$clicksimpshow=formatReturnCampaignDetails($campaigndetailsarr,$campaignid);
	$campaignarr=getCampaignNameType($campaignid);
	$smarty->assign("campaignname",$campaignarr["campaignname"]);
	$smarty->assign("campaigntype",$campaignarr["campaigntype"]);
	if (in_array(2,$misoptionarr) || in_array(1,$misoptionarr))
        {
                $smarty->assign("showimpressions",1);
        }
        if (in_array(3,$misoptionarr) || in_array(1,$misoptionarr))
        {
                $smarty->assign("showclicks",1);
        }
        if (in_array(2,$misoptionarr) || in_array(1,$misoptionarr))
        {
                $smarty->assign("showctr",1);
        }

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
		$smarty->display("./$_TPLPATH/bms_clientbannermis.htm");
}
else
{
	TimedOutBms();
}
