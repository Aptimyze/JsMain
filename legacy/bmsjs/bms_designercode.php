<?php

/****************************************************bms_designercode.php**************************************************/
/*
	*	Created By		:	Shobha Kumari
	*	Description        	:	This file is used to generate code (to be embedded in the script)
						for displaying the banners
	*	Includes/Libraries	:	bms_connect.php
****************************************************************************************************************************/

include("./includes/bms_connect.php");

$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"designer");

/*************************************************************************************
	returns an array of banner class to which a particular banner 
	class is allowed to be changed to
	input: bannerclass
	output: array of banner classes
**************************************************************************************/
function getBannerClass($bannerclass)
{
	$bannerclassarr=array("Image"=>array("Image","Flash"),
						"Flash"=>array("Image","Flash"),
						"Popup"=>array("Popup"),
						"PopUnder"=>array("PopUnder"),
						"Mailer"=>array("Mailer")
						);
	return $bannerclassarr["$bannerclass"];
}

/**************************************************************************************
	returns an array of allowed values of BannerDefault field in banner table
	input:none
	output:array of banner default values
**************************************************************************************/ 
function getBannerDefault()
{
	$bannerdefaultarr=array("Y","N");
	return $bannerdefaultarr;
}

if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$id=$data["ID"];
	$site = $data["SITE"];
	$smarty->assign("site",$site);
	$smarty->assign("id",$id);
	$smarty->assign("bannerid",$bannerid);


	if($saveadvbannerdetails_x||$saveadvbannerdetails||$savebasicdetails_x)
	{
		$check=true;
		$sql =  "SELECT * FROM ZONE WHERE ZoneId='$zoneid'";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logerror("Error",$sql);
		$row = mysql_fetch_array($res);
		$site = $row["SITE"];
		$popup  = $row["ZonePopup"];
		if ($popup == 'Y' || $bannerclass=='PopUnder/PopUp/Banner-NewWindow')
		{
			$frameheight = '0';
			$framewidth = '0';
		}
		if ($bannerdefault == 'N')
			$datastr = "&data=";
		else
			$datastr ='';
		if ($bannerclass != 'MailerBanner')
		{
			$echostring="<IFRAME MARGINWIDTH=0 MARGINHEIGHT=0 HSPACE=0 VSPACE=0 FRAMEBORDER=0 SCROLLING=no BORDERCOLOR=#000000 FRAMEBORDER=0 HEIGHT=$frameheight WIDTH=$framewidth";
			if ($bannerpriority != 'ALL')
				$url= JsConstants::$bmsUrl."/bmsjs/bms_display_final.php?zonestr=$zoneid&subzone=$bannerpriority".$datastr;
			else
				$url= JsConstants::$bmsUrl."/bmsjs/bms_display_final.php?zonestr=$zoneid&showall=1".$datastr;
			$echostring.=" SRC = $url"."></IFRAME>";
		}
		else
		{
			$echostring="<a href=\"".JsConstants::$bmsUrl."/bmsjs/bms_display_final.php?zonestr=$zoneid&mailer=1&subzone=$bannerpriority&hit=1&data=\"><img src=\"".JsConstants::$bmsUrl."/bmsjs/bms_display_final.php?zonestr=$zoneid&mailer=1&subzone=$bannerpriority&data=\" border=\"0\" ></a>";
		}
		$smarty->assign("zonestr",$echostring);
		$smarty->assign("id",$id);
		$smarty->display("./$_TPLPATH/bms_getcode.htm");
	}
	else
	{
		$zonearr=explode("|ZID|",$zone);
		$zoneid=$zonearr[0];
		$zonecriteriastr=$zonearr[1];
		if($bannerdefault!='Y')
			$bannerdefault='N';
		if ($bannerclass=='MailerBanner' || $bannerclass == 'PopUnder/PopUp/Banner-NewWindow')
			$showframe = 'N';
		else
			$showframe = 'Y';
		$smarty->assign("showframe",$showframe);
		$smarty->assign("zoneid",$zoneid);
		$smarty->assign("bannerclass",$bannerclass);
		$smarty->assign("bannerdefault",$bannerdefault);
		$smarty->assign("bannerdetails",$bannerdetails);
		$smarty->assign("id",$id);
		$smarty->display("./$_TPLPATH/bms_designercode.htm");
	}
}
else
{
	TimedOutBms();
}
?>
