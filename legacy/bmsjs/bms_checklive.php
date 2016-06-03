<?php
/****************************************************bms_checklive.php************************************************
        *       Created By              :       Abhinav Katiyar
        *       Last Modified By        :       Abhinav Katiyar
        *       Description             :       Checks whether a banner is live or not 
        *       Includes/Libraries      :       ./includes/bms_connect.php
****************************************************************************************************************************/
include_once("./includes/bms_connect.php");
function checkAdvBooking($bannerstartdate,$zoneid)
{
	//echo "in chek ad booking<br />";
	return 1;

}
function checkValidMailerId($mailerid)
{
	//echo "in mailer id check";
	if($mailerid==""||$mailerid=="0")
		return 	0;
	else
		return 1;
	
}
function checkValidGif($bannergif)
{
	if(strstr(trim($bannergif), '.gif'))
		return 1;
	elseif(strstr(trim($bannergif), '.jpeg'))
		return 1;
	elseif(strstr(trim($bannergif), '.jpg'))
		return 1;
	elseif(strstr(trim($bannergif), '.bmp'))
		return 1;
	elseif(strstr(trim($bannergif), '.htm'))
		return 1;
	elseif(strstr(trim($bannergif), '.php'))
		return 1;
	elseif(strstr(trim($bannergif), '.swf'))
		return 1;
	elseif(strstr(trim($bannergif), '.flv'))
		return 1;
	elseif(strstr(trim($bannergif), '.wmv'))
		return 1;
	elseif(strstr(trim($bannergif), '.html'))
		return 1;
	else
		return 0;
}
function checkBannerCriteria($bannerid)
{
	$bannercriterias=showcriterias($bannerid);
	if($bannercriterias["criteria"])
	{
		return 1;
	}
	else
	{
		return 0;
	}
	
}
function CheckToBeLive($bannerdetails,$checklive="",$showmsg="")
{
	//echo $bannerdetails["bannerclass"];
	global $dbbms,$smarty;
	$bannerclass=FormatBannerClass($bannerdetails["bannerclass"]);
	if($bannerdetails["bannerzoneid"]==""||$bannerdetails["bannerzoneid"]=="0")
	{
		$errormsg.="The zone you have selected is incorrect .Please select a valid zone to process the request..<BR />";
	}
	elseif(!checkAdvBooking($bannerdetails["bannerstartdate"],$bannerdetails["bannerzoneid"]))
	{
		$errormsg.="Please select correct startdate of banner as it clashes with the advance booking period of the zone.<BR />";
	}
	elseif($bannerdetails["bannerweightage"]==""||$bannerdetails["bannerpriority"]=="")
	{
		$errormsg.="Please fill in a valid weightage and priority for the banner.<BR />";
	}
	elseif($bannerdetails["bannerstartdt"]>$bannerdetails["bannerenddt"])
	{
		$errormsg.="Please fill in valid start and end dates for the banner to continue<BR />";
	}
	elseif($bannerclass=="Mailer")
	{
		if(!checkValidMailerId($bannerdetails["mailerid"]))
			$errormsg.="You have entered an invalid mailer id for this banner.Please put in a correct mailer id to process the request.<BR />";
	}
	elseif($bannerclass=="PopUp"||$bannerclass=="PopUnder")
	{		
		if($bannerdetails["bannerfeatures"]=="")
			$errormsg.="You have entered invalid banner features for this banner.Please correct the banner features to process the request";
	}
	elseif($bannerdetails["bannerstatic"]!='Y' && $bannerdetails["bannerurl"]=="")
	{
		$errormsg.="Banner url Field cannot be left empty as you have selected a non-static banner.Please enter a valid banner url to process the request  .<BR />";
	}
	elseif($bannerdetails["bannerclass"]!="textlink" && ($bannerdetails["bannergif"]==""||!checkValidGif($bannerdetails["bannergif"])))
	{
		$errormsg.="You have entered an invalid gif for this banner.Please put in a correct gif to process the request.<BR />";
	}
	elseif($bannerdetails["bannerdefault"]!='Y' && $bannerdetails["bannerfixed"]!='Y' && !checkBannerCriteria($bannerdetails["bannerid"]))
	{	 
		$errormsg.="You have entered invalid banner criterias for this banner.Please correct the banner criteria to process the request .<BR />";
	}
	elseif($bannerdetails["bannerfixed"]!='Y' )
	{
		$sql_default = "SELECT COUNT(*) AS CNT FROM bms2.BANNER WHERE ZoneId='$bannerdetails[bannerzoneid]' AND (BannerStatus='live' or BannerStatus='booked'  or BannerStatus='ready') and BannerFixed = 'Y'";
                if($result_default=mysql_query($sql_default,$dbbms))
		{
                	$row_default = mysql_fetch_array($result_default);
                	if ($row_default['CNT'] == 0)
			{
				$errormsg.="This banner cannot be made live.To book a banner on a particular criterion a default banner need to be booked first.Please go back and do the same.<BR />";
				$smarty->assign("nodefaultbanner","Y");
			}
		}
		else
			die(mysql_error($dbbms));
	}
	if($checklive!=" ")//=="checklive")
	{	
		$curdate=date("Y-m-d");
		if($bannerdetails["bannerstartdt"]!=$curdate)
			$errormsg.=" This banner cannot be made live as its start date is ' $bannerdetails[bannerstartdt] '.<BR />";
	}
	if($errormsg)
	{
		if($showmsg!="no")
			$smarty->assign("errormsg",$errormsg);
		return 0;
	}
	else
	{
		return 1;
	}
	
}

$bannerdetails=getBannerDetails($bannerid);
if($changestatus=="ready")
{
	if($bannerdetails["bannerstatus"]=="booked"||$bannerdetails["bannerstatus"]=="ready")
	{
		if(CheckToBeLive($bannerdetails,""))
		{
		
			updateBannerStatus($bannerid,$changestatus);
			ChangeCampaignStatus($campaignid,"active");
			$smarty->assign("cnfrmmsg","The banner status has been changed to 'ready' succesfully.");
		}
		
	}
	else
		$smarty->assign("errormsg","The banner has to be booked first before changing its status.");
	
}
elseif($changestatus=="live")
{
	if($bannerdetails["bannerstatus"]=="booked"||$bannerdetails["bannerstatus"]=="ready")
	{
		if(CheckToBeLive($bannerdetails,"checklive"))
		{
			updateBannerStatus($bannerid,$changestatus);
			ChangeCampaignStatus($campaignid,"active");
			$smarty->assign("cnfrmmsg","This banner has been made live succesfully");
		}
		
	}
	else
		$smarty->assign("errormsg","The banner has to be booked first before changing its status.");
	
}
elseif($changestatus=="cancel")
{
	updateBannerStatus($bannerid,$changestatus);
	$smarty->assign("cnfrmmsg","This banner has been cancelled on your request.");
}
elseif($changestatus=="deactive")
{
	updateBannerStatus($bannerid,$changestatus);
	$smarty->assign("cnfrmmsg","This banner has been deactivated on your request.");
}
elseif($checkstatus=="checkstatus")
{
	if(CheckToBeLive($bannerdetails,"checklive","yes"))
		$newstatus="live";
	elseif(CheckToBeLive($bannerdetails,"","yes"))
		$newstatus="ready";
	else
		$newstatus="booked";
}

?>
