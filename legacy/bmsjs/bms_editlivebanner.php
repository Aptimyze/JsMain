<?php

/*******************************************************bms_editlivebanner.php**********************************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : This file is used to modify the details of a live banner
   *  Includes/Libraries : ./includes/bms_connect.php , bms_functions.php
**************************************************************************************************************************/

include("./includes/bms_connect.php");
include("bms_functions.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");

/*******************************************************************************
	populates the form containing the details of a live banner
	input: bannerenddate,banner gif, banner url , banner static, banner default values
	output: above details assigned to form
********************************************************************************/
function showEditLiveBannerForm($bannerzoneid,$banneroldenddate,$bannerendday,$bannerendmonth,$bannerendyear,$bannerstartday,$bannerstartmonth,$bannerstartyear,$bannergif,$bannerurl,$bannerstatic,$bannerpriority,$bannerweightage,$bannerfeatures,$bannerclass,$campaignenddate)
{
	global $smarty,$_TPLPATH;
	$smarty->assign("banneroldenddate",$banneroldenddate);
	$smarty->assign("bannergif",$bannergif);
	$smarty->assign("bannerurl",$bannerurl);
	$smarty->assign("bannerfeatures",$bannerfeatures);
	$smarty->assign("bannerstatic",$bannerstatic);
	$smarty->assign("bannerweightage",$bannerweightage);
	$smarty->assign("bannerendday",$bannerendday);
	$smarty->assign("bannerendmonth",$bannerendmonth);
	$smarty->assign("bannerendyear",$bannerendyear);
	$smarty->assign("bannerstartday",$bannerstartday);
	$smarty->assign("bannerstartmonth",$bannerstartmonth);
	$smarty->assign("bannerstartyear",$bannerstartyear);

$smarty->assign("bannerpriorityarr",getBannerPriority($bannerzoneid,$bannerpriority));
	$smarty->assign("daysarr",getDaysBms());
	$smarty->assign("monthsarr",getMonthsBms());
	$smarty->assign("yearsarr",getYearsBms());
	$smarty->assign("bannerclass",FormatBannerClass($bannerclass));
	$smarty->assign("campaignenddate",$campaignenddate);
	$smarty->assign("currentdate",date("Y-m-d"));
		
}
function getCriteriaArray($bannerid,$bannerenddate,$bannergif,$bannerurl,$bannerstatic,$bannerpriority,$bannerweightage,$bannerfeatures)
{
	$bannerdetails=getBannerDetails($bannerid);
	$criteriavaluesarr["zoneid"]=$bannerdetails["bannerzoneid"];
	$criteriavaluesarr["bannerid"]=$bannerdetails["bannerid"];
	$criteriavaluesarr["bannerstartdate"]=$bannerdetails["bannerstartdt"];
	$criteriavaluesarr["bannerenddate"]=$bannerenddate;
	$criteriavaluesarr["bannerdefault"]=$bannerdetails["bannerdefault"];
	$criteriavaluesarr["bannerstatic"]=$bannerstatic;
	$criteriavaluesarr["bannerurl"]=$bannerurl;
	$criteriavaluesarr["bannergif"]=$bannergif;
	$criteriavaluesarr["mailerid"]=$bannerdetails["mailerid"];
	$criteriavaluesarr["bannerclass"]=$bannerdetails["bannerclass"];
	$criteriavaluesarr["bannerintext"]=$bannerdetails["bannerintext"];
	$criteriavaluesarr["bannerfeaturelist"]=$bannerfeatures;
	$criteriavaluesarr["campaignid"]=$bannerdetails["campaignid"];
	$criteriavaluesarr["bannerweightage"]=$bannerweightage;
	$criteriavaluesarr["bannerpriority"]=$bannerpriority;
	
	if($bannerdetails["bannerkeyword"]!="")
		$criteriavaluesarr["criteriaarr"][]="Keywords";
	$criteriavaluesarr["bannerkeyword"]=$bannerdetails["bannerkeyword"];
	$criteriavaluesarr["bannerkeystype"]=$bannerdetails["bannerkeystype"];
	
	if($bannerdetails["bannerlocation"]!="")
		$criteriavaluesarr["criteriaarr"][]="Location";
	$criteriavaluesarr["bannerlocation"]=$bannerdetails["bannerlocation"];
	
	if($bannerdetails["bannerindtype"]!="")
	{
		$criteriavaluesarr["criteriaarr"][]="Industry";
		$criteriavaluesarr["bannerindtype"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerindtype"]))));
	}
	else
		$criteriavaluesarr["bannerindtype"]="";
		
	if($bannerdetails["bannerfarea"]!="")
	{
		$criteriavaluesarr["criteriaarr"][]="Farea";
		$criteriavaluesarr["bannerfarea"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerfarea"]))));
	}
	else
		$criteriavaluesarr["bannerfarea"]="";
	
	if($bannerdetails["bannerexpmin"]>=0&&$bannerdetails["bannerexpmax"]>=0)
		$criteriavaluesarr["criteriaarr"][]="Exp";
		
	$criteriavaluesarr["bannerexpmin"]=$bannerdetails["bannerexpmin"];
	$criteriavaluesarr["bannerexpmax"]=$bannerdetails["bannerexpmax"];
	
	if($bannerdetails["bannercategories"]!="")
	{
		$criteriavaluesarr["criteriaarr"][]="Categories";
		$criteriavaluesarr["bannercategories"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannercategories"]))));
	}
	else
		$criteriavaluesarr["bannercategories"]="";
		
	if($bannerdetails["bannerip"]!="")
	{
		$criteriavaluesarr["criteriaarr"][]="IP";
		$criteriavaluesarr["bannerip"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerip"]))));
	}
	else
		$criteriavaluesarr["bannerip"]="";

	if($bannerdetails["bannerctcmin"]>=0&&$bannerdetails["bannerctcmax"]>=0)
		$criteriavaluesarr["criteriaarr"][]="Ctc";
		
	$criteriavaluesarr["bannerctcmin"]=$bannerdetails["bannerctcmin"];
	$criteriavaluesarr["bannerctcmax"]=$bannerdetails["bannerctcmax"];
	
	if($bannerdetails["banneragemin"]>=0&&$bannerdetails["banneragemax"]>=0)
		$criteriavaluesarr["criteriaarr"][]="Age";
		
	$criteriavaluesarr["banneragemin"]=$bannerdetails["banneragemin"];
	$criteriavaluesarr["banneragemax"]=$bannerdetails["banneragemax"];
	
	if($bannerdetails["bannergender"]!="")
		$criteriavaluesarr["criteriaarr"][]="Gender";
		
	$criteriavaluesarr["bannergender"]=$bannerdetails["bannergender"];
	
	if($bannerdetails["bannerresmanexpmin"]>=0&&$bannerdetails["bannerresmanexpmax"]>=0)
		$criteriavaluesarr["criteriaarr"][]="ExpResman";
		
	$criteriavaluesarr["bannerresmanexpmin"]=$bannerdetails["bannerresmanexpmin"];
	$criteriavaluesarr["bannerresmanexpmax"]=$bannerdetails["bannerresmanexpmax"];
	
	if($bannerdetails["bannerresmanindustry"]!="")
	{
		$criteriavaluesarr["criteriaarr"][]="IndustryResman";
		$criteriavaluesarr["bannerresmanindtype"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerresmanindustry"]))));
	}
	else
		$criteriavaluesarr["bannerresmanindtype"]="";
		
	if($bannerdetails["bannerresmanfarea"]!="")
	{
		$criteriavaluesarr["criteriaarr"][]="FareaResman";
		$criteriavaluesarr["bannerresmanfarea"]=explode(",",trim(str_replace(" , ",",",str_replace("#"," ",$bannerdetails["bannerresmanfarea"]))));
	}
	else
		$criteriavaluesarr["bannerresmanfarea"]="";
		return $criteriavaluesarr;

}

function checkDates($banneroldenddate,$bannerenddate)
{
	if($banneroldenddate==$bannerenddate)
		return false;
	else
		return true;
}

function saveLiveDetails($bannerid,$bannerenddate,$bannergif,$bannerurl,$bannerstatic,$bannerpriority,$bannerweightage,$bannerfeaturelist)
{
	global $dbbms;
	$sql="update bms2.BANNER set BannerEndDate='$bannerenddate',BannerGif='$bannergif',BannerUrl='$bannerurl',BannerStatic='$bannerstatic',BannerPriority='$bannerpriority',BannerFeatures='$bannerfeaturelist',BannerWeightage='$bannerweightage' where BannerId='$bannerid'";
	echo "<!--$sql-->";
	$res=mysql_query($sql,$dbbms);


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
	
	if($savebannerdetails_x||$savedetails)
	{
		$check=true;
		$bannerenddate=$bannerendyear."-".$bannerendmonth."-".$bannerendday;
		if(checkDates($banneroldenddate,$bannerenddate))
		{
	$criteriavaluesarr=getCriteriaArray($bannerid,$bannerenddate,$bannergif,$bannerurl,$bannerstatic,$bannerpriority,$bannerweightage,$bannerfeatures);

			if(checkIfAvail($criteriavaluesarr))
				$check=true;
			else
				$check=false;
		}
		
		else
			$bannerenddate=$banneroldenddate;
		
		if($check)
		{
		
			saveLiveDetails($bannerid,$bannerenddate,$bannergif,$bannerurl,$bannerstatic,$bannerpriority,$bannerweightage,$bannerfeaturelist);		
			$smarty->assign("cnfrmmsg","The banner details have been changed succesfully on your request.");
		}
		else
			$smarty->assign("errormsg","Due to non-availability of the banner in this duration your request to book the banner.");
		$smarty->assign("campaignid",$campaignid);
		$smarty->display("./$_TPLPATH/bms_editliveconfirm1.htm");
	}
	else
	{
		$bannerdetails=getBannerDetails($bannerid);
		$campaignduration=getCampaignDate($bannerdetails["campaignid"])	;

showEditLiveBannerForm($bannerdetails["bannerzoneid"],$bannerdetails["bannerenddt"],$bannerdetails["bannerendday"],$bannerdetails["bannerendmonth"],$bannerdetails["bannerendyear"],$bannerdetails["bannerstartday"],$bannerdetails["bannerstartmonth"],$bannerdetails["bannerstartyear"],$bannerdetails["bannergif"],$bannerdetails["bannerurl"],$bannerdetails["bannerstatic"],$bannerdetails["bannerpriority"],$bannerdetails["bannerweightage"],$bannerdetails["bannerfeatures"],$bannerdetails["bannerclass"],$campaignduration["enddate"]);
		getCriteriaArray($bannerid,$bannerenddate,$bannergif,$bannerurl,$bannerstatic,$bannerpriority,$bannerweightage,$bannerfeatures);
		$smarty->assign("campaignid",$bannerdetails["campaignid"]);
		$smarty->display("./$_TPLPATH/bms_editlivebanner.htm");
	}
}
else
{
	TimedOutBms();
}

?>
