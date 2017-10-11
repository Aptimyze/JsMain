<?php

/************************************************bms_bannerdetails.php****************************************************
	*	Created By		:	Abhinav Katiyar
	*	Last Modified By   	:	Abhinav Katiyar
	*	Description        	:	Displays the populated banner details page
	*	Includes/Libraries	:	./includes/bms_connect.php
****************************************************************************************************************************/
include("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
$site = $data["SITE"];

/*********************************************************************************
	returns an array of banner class to which a particular 
	banner class is allowed to be changed to
	input	:	bannerclass
	output	:	array of banner classes
*********************************************************************************/
function getBannerClass($bannerclass)
{
	//wmv,flv,html added by lavesh
/*
	$bannerclassarr=array("Image"=>array("Image","Flash","html","textlink","flv","wmv"),
						"textlink"=>array("Image","Flash","html","textlink","flv","wmv"),
						"Flash"=>array("Image","Flash","html","flv","wmv"),
						"html"=>array("Image","Flash","html","flv","wmv"),
						"PopUp"=>array("PopUp"),
						"PopUnder"=>array("PopUnder"),
						"MailerFlash"=>array("MailerImage","MailerFlash"),
						"MailerImage"=>array("MailerImage","MailerFlash"),
						"flv"=>array("Image","Flash","html","flv","wmv"),
						"wmv"=>array("Image","Flash","html","flv","wmv")
						);
*/
	
        $bannerclassarr=array("Image"=>array("Image","Flash","html","textlink","flv","wmv","Flash-shoshkelle","Flash-shoshkelle-slug"),
                                                "textlink"=>array("Image","Flash","html","textlink","flv","wmv","Flash-shoshkelle"),
                                                "Flash"=>array("Image","Flash","html","flv","wmv","Flash-shoshkelle"),
                                                "html"=>array("Image","Flash","html","flv","wmv","Flash-shoshkelle"),
                                                "PopUp"=>array("PopUp","Flash-shoshkelle"),
                                                "PopUnder"=>array("PopUnder","Flash-shoshkelle"),
                                                "MailerFlash"=>array("MailerImage","MailerFlash","Flash-shoshkelle"),
                                                "MailerImage"=>array("MailerImage","MailerFlash","Flash-shoshkelle"),
                                                "flv"=>array("Image","Flash","html","flv","wmv","Flash-shoshkelle"),
                                                "Flash"=>array("Image","Flash","html","flv","wmv","Flash-shoshkelle"),
                                                "Flash-shoshkelle"=>array("Image","Flash-shoshkelle","Flash-shoshkelle-slug"),
                                                "Flash-shoshkelle-slug"=>array("Image","Flash-shoshkelle","Flash-shoshkelle-slug"),
                                                "wmv"=>array("Image","Flash","html","flv","wmv","Flash-shoshkelle")
				        	);
	return $bannerclassarr["$bannerclass"];
}

/**********************************************************************************
	returns an array of allowed values of BannerDefault field in banner table
	input:none
	output:array of banner default values
**********************************************************************************/
function getBannerDefault()
{
	$bannerdefaultarr=array("Y","N");
	return $bannerdefaultarr;
}

/*********************************************************************************
	returns an array of allowed values of BannerStatic field in banner table
	input:none
	output:array of banner static values
*********************************************************************************/ 
function getBannerStatic()
{
	$bannerstaticarr=array("Y","N");
	return $bannerstaticarr;
}

/*********************************************************************************
	returns an array of allowed values of BannerFreeOrPaid field in banner table
	input:none
	output:array of banner free or paid values
*********************************************************************************/ 
function getBannerFreePaid()
{
	$bannerfreepaidarr=array("Free","Paid");
	return $bannerfreepaidarr;
}

/*********************************************************************************
	returns an array of allowed values of BannerIntExt(Internal or External) field in banner table
	input:none
	output:array of banner internal/external values
*********************************************************************************/
function getBannerIntExt()
{
	$bannerintextarr=array("0"=>array("name"=>"Internal","value"=>"I"),
							"1"=>array("name"=>"External","value"=>"E")
						);
	return  $bannerintextarr;
}

/**********************************************************************************
	assigns all the required details (like data to populate dropdowns) to the template
	input:bannerclass, selected zoneid 
	output:all dropdowns and data assigned to the template
**********************************************************************************/ 
function showBannerDetailsForm($bannerdetails,$campaigndatearr)
{
	global $smarty,$bannerstatusarr,$_TPLPATH,$site;
	assignRegionZoneDropDowns($bannerdetails["bannerzoneid"],"",$bannerdetails["bannerclass"],$site);
	$smarty->assign("bannerclassarr",getBannerClass($bannerdetails["bannerclass"]));
	$smarty->assign("bannerdefaultarr",getBannerDefault());
	$smarty->assign("bannerstaticarr",getBannerStatic());
	//$smarty->assign("bannerfreepaidarr",getBannerFreePaid());
	$smarty->assign("bannerintextarr",getBannerIntExt());
	$smarty->assign("daysarr",getDaysBms());
	$smarty->assign("monthsarr",getMonthsBms());
	$smarty->assign("yearsarr",getYearsBms());
	$smarty->assign("campaignstartdate",$campaigndatearr["startdate"]);
	$smarty->assign("campaignenddate",$campaigndatearr["enddate"]);
	if($bannerdetails["bannerstatus"]=="newrequest"||$bannerdetails["bannerstatus"]=="cancel")
	{
		$campaignstart=explode("-",$campaigndatearr["startdate"]);
		$bannerdetails["bannerstartday"]=$campaignstart[2];
		$bannerdetails["bannerstartmonth"]=$campaignstart[1];
		$bannerdetails["bannerstartyear"]=$campaignstart[0];
		$campaignend=explode("-",$campaigndatearr["enddate"]);
		$bannerdetails["bannerendday"]=$campaignend[2];
		$bannerdetails["bannerendmonth"]=$campaignend[1];
		$bannerdetails["bannerendyear"]=$campaignend[0];
	}
	$smarty->assign("bannerdetails",$bannerdetails);
}


if($data)
{
	// bannerid passed from link or form

	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);

	$id=$data["ID"];
	$site = $data["SITE"];
	$smarty->assign("id",$id);
	$smarty->assign("site",$site);
	$smarty->assign("bannerid",$bannerid);
	$bannerdetails=getBannerDetails($bannerid);
	$smarty->assign("id",$id);
	$smarty->assign("campaignid",$campaignid);
	$smarty->assign("bannerid",$bannerid);
	$smarty->assign("bannerstatus",$bannerdetails["bannerstatus"]);
	$campaigndatearr=getCampaignDate($campaignid);
	showBannerDetailsForm($bannerdetails,$campaigndatearr);
	$smarty->display("./$_TPLPATH/bms_bannerdetails.htm");
}
else
{
	TimedOutBms();
}
?>
