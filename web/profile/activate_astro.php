<?php
/*********************************************************************************************
* FILE NAME   : activate_astro.php
* DESCRIPTION : Called through a link given to astro users in mail and also after online payment 
		is done for astro service. This script updates the astro information.
* DATE        : May 23, 2005
* MADE BY     :	Kush Asthana
*********************************************************************************************/


include("connect.inc");
//include("pg/functions.php");
$db=connect_db();
$data=authenticated($checksum);
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if($data)
{
	$profileid=$data['PROFILEID'];
	if($Submit)
	{
		$sql="SELECT VALUE from newjs.COUNTRY where LABEL = '$birth_country'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");;
		$myrow=mysql_fetch_array($result);
		$count_birth=$myrow['VALUE'];
		$btime=$Hour_Birth.":".$Min_Birth;
		$sql="UPDATE newjs.JPROFILE set CITY_BIRTH='$birth_place', COUNTRY_BIRTH='$count_birth', BTIME='$btime' where PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");;
		JProfileUpdateLib::getInstance()->removeCache($profileid);
		$smarty->assign("astro",$astro);
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                //$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

		$smarty->display("astro_thanks.htm");
	}
	else
	{
		$servefor=explode(",",$servefor);
		if(in_array("H",$servefor) && in_array("K",$servefor))
		{
			$smarty->assign("ASTRO","H,K");
		}
		elseif(in_array("H",$servefor))
			$smarty->assign("ASTRO","H");
		elseif(in_array("K",$servefor))
			$smarty->assign("ASTRO","K");
			
		$sql="SELECT DTOFBIRTH,BTIME from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");;
		$myrow=mysql_fetch_array($result);
		list($year,$month,$day)=explode("-",$myrow['DTOFBIRTH']);	
		$dt_birth=my_format_date($day,$month,$year);
		list($hour_birth,$min_birth)=explode(":",$myrow['BTIME']);
		$smarty->assign("DTOFBIRTH",$dt_birth);
		$smarty->assign("HOUR_BIRTH",$hour_birth);
		$smarty->assign("MIN_BIRTH",$min_birth);
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
		//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->display("horoscope_form.htm");

	}
}
else
{
	TimedOut();
}
?>
