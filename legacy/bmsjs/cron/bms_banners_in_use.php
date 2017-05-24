<?php
if(!$skipphp5)
chdir(dirname(__FILE__));
if($bannerStatusChanged==1 && $bannerid) 
{
	global $dbbms;

	$sql="SELECT ZoneId FROM BANNER WHERE BANNERID=$bannerid";
	$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","error in bms_banner_in_use1",mysql_error($dbbms).$sql);
	$row=mysql_fetch_array($res);
	$zoneId=$row["ZoneId"];

	if($zoneId)
		updateCrieriaInuse($zoneId);
}
else
{
	$dirname=dirname(__FILE__);
	chdir($dirname);

	include_once("../includes/bms_connect.php");
	$dbbms = getConnectionBms();
	global $dbbms;

	$sql="SELECT ZoneId,ZoneCriterias FROM ZONE WHERE ZoneStatus='active'";
	$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","error in bms_banner_in_use2",mysql_error($dbbms).$sql);
	while($row=mysql_fetch_array($res))
	{
		//$zoneCriteria=$row["ZoneCriterias"];
		//$zoneMaxCriteria=explode(",",$zoneCriteria);	
		$zoneId=$row["ZoneId"];
		updateCrieriaInuse($zoneId);
	}
}

function updateCrieriaInuse($zoneId)
{
	global $dbbms;
	$sql1="SELECT BannerCountry,BannerIP,BannerAgeMin,BannerGender,BannerCTC,BannerMEM,BannerMARITALSTATUS,BannerEDU,BannerOCC,BannerCOM,BannerREL,BannerPROPCITY,BannerPROPINR,BannerPROPTYPE,BannerPROPCAT,BannerJsVd,BannerJsProfileStatus,BannerJsMailID,BannerJsEoiStatus,BannerJsRegistrationStatus,BannerJsFtoStatus,BannerJsFtoExpiry,BannerJsProfileCompletionState  FROM BANNER WHERE ZoneId=$zoneId AND BannerStatus='live'";
	$res1=mysql_query($sql1,$dbbms) or mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","error in bms_banner_in_use3",mysql_error($dbbms).$sql1);

	while($row1=mysql_fetch_array($res1))
	{
		if($row1["BannerCountry"])
			$usedCriteria[]="LOCATION";
		if($row1["BannerIP"])		
			$usedCriteria[]="IP";
		if($row1["BannerAgeMin"] && $row1["BannerAgeMin"]!='-1')
			$usedCriteria[]="AGE";
		if($row1["BannerGender"])
			$usedCriteria[]="GENDER";
		if($row1["BannerCTC"])
			$usedCriteria[]="INCOME";
		if($row1["BannerMEM"])
			$usedCriteria[]="SUBSCRIPTION";
		if($row1["BannerMARITALSTATUS"])
			$usedCriteria[]="MARITALSTATUS";
		if($row1["BannerEDU"])
			$usedCriteria[]="EDUCATION";
		if($row1["BannerOCC"])
			$usedCriteria[]="OCCUPATION";
		if($row1["BannerCOM"])
			$usedCriteria[]="COMMUNITY";
		if($row1["BannerREL"])
			$usedCriteria[]="RELIGION";
		if($row1["BannerPROPCITY"])
			$usedCriteria[]="PROPCITY";
		if($row1["BannerPROPINR"])
			$usedCriteria[]="PROPINR";
		if($row1["BannerPROPTYPE"])
			$usedCriteria[]="PROPTYPE";
		if($row1["BannerPROPCAT"])
			$usedCriteria[]="PROPCAT";
		if($row1["BannerJsVd"])
			$usedCriteria[]="VARIABLE_DISCOUNT";
		if($row1["BannerJsProfileStatus"])
			$usedCriteria[]="PROFILE_STATUS";
		if($row1["BannerJsMailID"])
			$usedCriteria[]="GMAIL_ID";
		if($row1["BannerJsEoiStatus"])
			$usedCriteria[]="EOI_STATUS";
		if($row1["BannerJsRegistrationStatus"])
			$usedCriteria[]="REGISTRATION_STATUS";
		if($row1["BannerJsFtoStatus"])
			$usedCriteria[]="FTO_STATE";
		if($row1["BannerJsFtoExpiry"])
			$usedCriteria[]="FTO_EXPIRY";
		if($row1["BannerJsProfileCompletionState"])
			$usedCriteria[]="PROFILE_COMPLETE_STATE";
		//if($row1[""])
		//if($row1[""])
	}

	if(is_array($usedCriteria))
	{
		//print_r($usedCriteria);
		$usedCriteria=array_unique($usedCriteria);
		//print_r($usedCriteria);
		$usedCriteriaStr=implode(",",$usedCriteria);
		//echo $usedCriteriaStr;
	}
	else
		$usedCriteriaStr='';

	$sql_u="UPDATE ZONE SET CriteriaInUse='$usedCriteriaStr' WHERE ZONEID='$zoneId'";
	mysql_query($sql_u,$dbbms) or mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","error in bms_banner_in_use4",mysql_error($dbbms).$sql_u);
	
	unset($usedCriteriaStr);
	unset($usedCriteria);
}
?>
