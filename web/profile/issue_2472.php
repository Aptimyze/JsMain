<?php
include("connect.inc");
connect_db();
if($profileid == "")
{
	$data=authenticated($checksum);
        if($data)
	{
        	$profileid=$data['PROFILEID'];	//getting the profileid
		$gender=$data["GENDER"];
	}	
}

// if person is not logged in, exit
if(!$profileid)
	exit;

/** white listing **/
if(!is_numeric($profileid) || ( $other_profileid && !is_numeric($other_profileid)) )
{
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	ValidationHandler::getValidationHandler("","white listing handling : issue_2472.php");
	exit;
}
/** white listing **/


connect_737_ro();

if(!$caste || !$gender)
{
	$sql="SELECT CASTE,GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID = '$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
		$caste=$myrow["CASTE"];
		$gender=$myrow["GENDER"];
		if($gender == 'M')
			$gender_value = 1;
		else
			$gender_value = 2;
	}
}
if($caste)
{
	$sql1="SELECT SQL_CACHE PARENT FROM newjs.CASTE WHERE VALUE=$caste";
	$result1=mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
	if(mysql_num_rows($result1) > 0)
	{
		$myrow1=mysql_fetch_array($result1);
		$parent=$myrow1["PARENT"];
	}
}                       
if((($parent == 1)||($parent == 9)) && !$logged_astro_details)
{
	if($other_profileid)
		$in_state="$profileid,$other_profileid";
	else
		$in_state="$profileid";
	$in_state = implode(",",array_diff(explode(",",$in_state), [0]));
	$sql_astro="SELECT PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN($in_state)";
	$result_astro=mysql_query_decide($sql_astro) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_astro,"ShowErrTemplate");
	if(mysql_num_rows($result_astro) > 0)
	{
		while($myrow_astro=mysql_fetch_array($result_astro))
		{
			$astro_pid1=$myrow_astro["PROFILEID"];
			if($astro_pid1)
			{
				$lagna=$myrow_astro['LAGNA_DEGREES_FULL'];
				$sun=$myrow_astro['SUN_DEGREES_FULL'];
				$mo=$myrow_astro['MOON_DEGREES_FULL'];
				$ma=$myrow_astro['MARS_DEGREES_FULL'];
				$me=$myrow_astro['MERCURY_DEGREES_FULL'];
				$ju=$myrow_astro['JUPITER_DEGREES_FULL'];
				$ve=$myrow_astro['VENUS_DEGREES_FULL'];
				$sa=$myrow_astro['SATURN_DEGREES_FULL'];
				$gender_val=1;
				if($gender_value==1)
					$gender_val=2;
				if($astro_pid1==$profileid)
					$logged_astro_details="$astro_pid1:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
				else
					$compstring="$astro_pid1:$gender_val:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa@";
			}
		}
	}
}
if($logged_astro_details)
{
	$url = "https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull?".$logged_astro_details."&".$compstring;
	//$fp = @fsockopen("vendors.vedic-astrology.net", 80, &$errno, &$errstr);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
	curl_setopt($ch, CURLOPT_TIMEOUT, 4);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

	$fresult = curl_exec($ch);
	if ($fresult)
	{
		$fresult = substr($fresult,(strpos($fresult,"<br/>")+5));
	}
	else
	{
		die;
		//logError(curl_error($ch),"","pass");
	}
	curl_close ($ch);
        echo $fresult;
	die;
}
?>
