<?php
	ini_set('max_execution_time','0');
	include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

	$db=connect_737();
	
	$sql="select PROFILEID from SEARCH_MALE_FULL1";
	$result=mysql_query_decide($sql) or die(mysql_error_js());

	while($myrow=mysql_fetch_array($result))
	{
		$sql="select CASTE,MTONGUE,AGE,EDU_LEVEL_NEW,OCCUPATION,CITY_RES,COUNTRY_RES from SEARCH_MALE_FULL1 where PROFILEID=" . $myrow['PROFILEID'];
		$res=mysql_query_decide($sql) or die(mysql_error_js());

		$myrow1=mysql_fetch_array($res);

		$caste=$CASTE_DROP[$myrow1['CASTE']];
		$mtongue1=label_select("MTONGUE",$myrow1['MTONGUE']);
		$mtongue=$mtongue1[0];

		$age=$myrow1['AGE'];
		$edu_level1=label_select("EDUCATION_LEVEL_NEW",$myrow1['EDU_LEVEL_NEW']);
		$edu_level=$edu_level1[0];

		$occupation=$OCCUPATION_DROP[$myrow1['OCCUPATION']];
		
		if($myrow1['COUNTRY_RES']==51)
		{
			$city_res=$CITY_INDIA_DROP[$myrow1['CITY_RES']];
			$country_res=$COUNTRY_DROP[$myrow1['COUNTRY_RES']];
		}
		elseif($myrow1['COUNTRY_RES']==128)
		{
			$city_res=$CITY_USA_DROP[$myrow1['CITY_RES']];
			$country_res=$COUNTRY_DROP[$myrow1['COUNTRY_RES']];
		}
		else
		{
			$city_res="";
			$country_res=$COUNTRY_DROP[$myrow1['COUNTRY_RES']];
		}

		//$height1=explode("&quot;",$HEIGHT_DROP[$myrow1['HEIGHT']]);
		//$height=$height1[0];

		$totstring="$age, Male, $caste, $mtongue, $occupation, $edu_level, $city_res, $country_res";
		
		$sql="update SEARCH_MALE_FULL1 set KEYWORDS='" . addslashes($totstring) . "' where PROFILEID=" . $myrow['PROFILEID'];
		mysql_query_decide($sql) or die(mysql_error_js());
		//echo $totstring . "\n";
	}
?>
