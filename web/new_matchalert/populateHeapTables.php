<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);

//for preventing timeout to maximum possible
chdir(dirname(__FILE__));
include_once("connect.inc");
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/configVariables.php");

$mysqlObj=new Mysql;

$localdb=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$localdb);
mysql_select_db("matchalerts",$localdb) or die(mysql_error());

setTrendsInfoOneTime($localdb);
setMatchesInfoOneTime($localdb,'M',1);
setMatchesInfoOneTime($localdb,'F',1);
setMatchesInfoOneTime($localdb,'M');
setMatchesInfoOneTime($localdb,'F');

function setTrendsInfoOneTime($db)
{
	$ctable="FEMALE_TRENDS_HEAP";
	$table="matchalerts.TRENDS_SEARCH_FEMALE";

	$sql1="TRUNCATE TABLE $ctable";
	mysql_query($sql1,$db) or die(mysql_error($db).$sql1);

	$sql1="INSERT INTO $ctable select A.PROFILEID,W_CASTE,CASTE_VALUE_PERCENTILE,W_MTONGUE,MTONGUE_VALUE_PERCENTILE,W_AGE,AGE_VALUE_PERCENTILE,W_INCOME,INCOME_VALUE_PERCENTILE,W_HEIGHT,HEIGHT_VALUE_PERCENTILE,W_EDUCATION,EDUCATION_VALUE_PERCENTILE,W_OCCUPATION,OCCUPATION_VALUE_PERCENTILE,W_CITY,CITY_VALUE_PERCENTILE,W_NRI,NRI_N_P,NRI_M_P,W_MSTATUS,MSTATUS_M_P,MSTATUS_N_P,W_MANGLIK,MANGLIK_M_P,MANGLIK_N_P,GENDER,MAX_SCORE from matchalerts.TRENDS A  JOIN $table B WHERE A.PROFILEID=B.PROFILEID";
	mysql_query($sql1,$db) or die(mysql_error($db).$sql1);


	$ctable="MALE_TRENDS_HEAP";
	$table="matchalerts.TRENDS_SEARCH_MALE";

	$sql1="TRUNCATE TABLE $ctable";
	mysql_query($sql1,$db) or die(mysql_error($db).$sql1);

	$sql1="INSERT INTO $ctable select A.PROFILEID,W_CASTE,CASTE_VALUE_PERCENTILE,W_MTONGUE,MTONGUE_VALUE_PERCENTILE,W_AGE,AGE_VALUE_PERCENTILE,W_INCOME,INCOME_VALUE_PERCENTILE,W_HEIGHT,HEIGHT_VALUE_PERCENTILE,W_EDUCATION,EDUCATION_VALUE_PERCENTILE,W_OCCUPATION,OCCUPATION_VALUE_PERCENTILE,W_CITY,CITY_VALUE_PERCENTILE,W_NRI,NRI_N_P,NRI_M_P,W_MSTATUS,MSTATUS_M_P,MSTATUS_N_P,W_MANGLIK,MANGLIK_M_P,MANGLIK_N_P,GENDER,MAX_SCORE from matchalerts.TRENDS A  JOIN $table B WHERE A.PROFILEID=B.PROFILEID";
	$res1=mysql_query($sql1,$db) or die(mysql_error($db));
}

function setMatchesInfoOneTime($db,$gender,$trendsmatches='')
{	
	/*
	global $matchesGlobalInfoF;
	global $matchesGlobalInfoM;
	*/

	if($trendsmatches)
	{
		if($gender=='F')
		{
			$table="matchalerts.TRENDS_SEARCH_FEMALE";
			$ctable="matchalerts.HEAP_TRENDS_FEMALE";
		}
		else
		{
			$table="matchalerts.TRENDS_SEARCH_MALE";
			$ctable="matchalerts.HEAP_TRENDS_MALE";
		}
	}
	else
	{
		if($gender=='F')
		{
			$table="matchalerts.NOTRENDS_SEARCH_FEMALE";
			$ctable="matchalerts.HEAP_NOTRENDS_FEMALE";
		}
		else
		{
			$table="matchalerts.NOTRENDS_SEARCH_MALE";
			$ctable="matchalerts.HEAP_NOTRENDS_MALE";
		}
	}

	$sql1="TRUNCATE TABLE $ctable";
	$res1=mysql_query($sql1,$db) or die(mysql_error($db));

	$sql1="INSERT INTO $ctable select A.PROFILEID,A.AGE,A.CASTE,A.MTONGUE,A.HEIGHT,A.EDU_LEVEL_NEW,A.OCCUPATION,A.CITY_RES,A.COUNTRY_RES,A.MSTATUS,A.MANGLIK,A.INCOME,A.ENTRY_DT,AGE_FILTER,RELIGION_FILTER,CASTE_FILTER,MTONGUE_FILTER,COUNTRY_RES_FILTER,MSTATUS_FILTER,INCOME_FILTER,CITY_RES_FILTER from $table B JOIN newjs.JPROFILE A WHERE A.PROFILEID=B.PROFILEID";
	$res1=mysql_query($sql1,$db) or die(mysql_error($db).$sql1);
/*
	$sql1="select A.PROFILEID,A.AGE,A.CASTE,A.MTONGUE,A.HEIGHT,A.EDU_LEVEL_NEW,A.OCCUPATION,A.CITY_RES,A.COUNTRY_RES,A.MSTATUS,A.MANGLIK,A.ENTRY_DT from $table B JOIN newjs.JPROFILE A WHERE A.PROFILEID=B.PROFILEID";
	$res1=mysql_query($sql1,$db) or die(mysql_error($db));
        while($row=mysql_fetch_array($res1))
	{
		$profileId=$row["PROFILEID"];
		$age=$row["AGE"];
		$caste=$row["CASTE"];
		$mtongue=$row["MTONGUE"];
		$height=$row["HEIGHT"];
		$education=$row["EDU_LEVEL_NEW"];
		$occupation=$row["OCCUPATION"];
		$cityres=$row["CITY_RES"];
		$country=$row["COUNTRY_RES"];
		$mstatus=$row["MSTATUS"];
		$manglik=$row["MANGLIK"];
		$entry_dt=$row["ENTRY_DT"];

		$send=new Sender($profileId,0);
		$send->setAge($age);
		$send->setCaste($caste);
		$send->setMtongue($mtongue);
		$send->setHeight($height);
		$send->setEdu_level($education);
		$send->setOccupation($occupation);
		$send->setCity_res($cityres);
		$send->setCountry_res($country);
		$send->setMstatus($mstatus);
		$send->setManglik($manglik);
		$send->setEntry_dt($row["ENTRY_DT"]);
		${"matchesGlobalInfo".$gender}[$profileId]=$send;
	}
*/
}
?>
