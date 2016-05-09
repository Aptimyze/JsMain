<?php

/* To zip the file before sending it */
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now)
{
	$dont_zip_more=1;
		ob_start("ob_gzhandler");
}
/* end of it */

$_SERVER['DOCUMENT_ROOT']=str_replace("/seopages","",$_SERVER['DOCUMENT_ROOT']);

include_once("../profile/connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
$db_master=connect_db();
$db_slave=connect_slave();

$smarty->relative_dir="seopages/";
$smarty->assign("SER6_URL",JsConstants::$ser6Url);
$serUrl=$_SERVER["SERVER_NAME"];
$subDomain_array=explode(".",$serUrl);
$subDomain=$subDomain_array[0];

$serUrl_Assign="http://$serUrl";
$smarty->assign('SUB_URL',$serUrl_Assign);

$sql="SELECT SQL_CACHE * FROM newjs.SEO WHERE URL='$subDomain.jeevansathi.com'";
$res=mysql_query($sql,$db_slave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
$row=mysql_fetch_array($res);
if(!$row)
{
	header('HTTP/1.1 404 Not Found');
	exit;
}
while($row)
{
	$name=$row['NAME'];
	$val=$row['VALUE'];
	$desc=$row['DESCRIPTION'];
	$url=$row['URL'];
	$source=$row['SOURCE'];
	$field=$row['FIELD'];
	$photo_url=$row['PHOTO_URL'];
	$url1=$row['URL1'];
	$url2=$row['URL2'];

	$finalurl1=$SITE_URL."$url1";
	$finalurl2=$SITE_URL."$url2";

	if($field=='CASTE')
		$searchfield='casteArr';
	elseif($field=='MTONGUE')
		$searchfield='mtongueArr';
	elseif($field=='CITY_RES')
		$searchfield='city_resArr';
	elseif($field=='RELIGION')
		$searchfield='religionArr';

	$ss=$row['MAP_SS'];
	if($ss)
	{
		$array=explode("-",$ss);
		$numbers=$array;
		$rand = rand(0, count($array)-1);

		/*for($i=0; $i<=count($array)-1; $i++)
		{
			$n = getrand($rand, $numbers, $array);
			array_push($numbers, $n);
			$final_ss= $array[$n];
		}*/

		$final_ss=$numbers[$rand];
	}

	$row=mysql_fetch_array($res);
	
	$smarty->assign("NAME",$name);
	$smarty->assign("DESC",$desc);
	$smarty->assign("SOURCE",$source);
	$smarty->assign("PHOTO_URL",$photo_url);

	$smarty->assign("SEOPAGES","1");
	$smarty->assign("FIELD","$field");
	$smarty->assign("SEARCHFIELD","$searchfield");
	$smarty->assign("URL1",$finalurl1);
	$smarty->assign("URL2",$finalurl2);
	$smarty->assign("VALUE",$val);
}

/* Success Story Section  */

if($final_ss)
{
	$sql_1="SELECT * FROM newjs.INDIVIDUAL_STORIES WHERE STORYID='$final_ss'";
	$res_1=mysql_query($sql_1,$db_slave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_1,"ShowErrTemplate");
	while($row_1=mysql_fetch_array($res_1))
	{
		$name_hus=$row_1['NAME1'];
		$name_wife=$row_1['NAME2'];
		$heading=$row_1['HEADING'];
		$story=$row_1['STORY'];
		$picture=$row_1['PICTURE'];
		$year=$row_1['YEAR'];
		$sid=$row_1['SID'];

		if(strlen($story)>155)
		{
			 $story=substr($story,0,155);
			 $story.=".....";
		}
	}

	$smarty->assign('NAME_HUS',$name_hus);
	$smarty->assign('NAME_WIFE',$name_wife);
	$smarty->assign('HEADING',$heading);
	$smarty->assign('STORY',$story);
	$smarty->assign('FINALSS',$final_ss);
	$smarty->assign('YEAR',$year);
	$smarty->assign('SID',$sid);
}
else
{
	$smarty->assign('HIDE_SS','1');
}

/* End of the Success Story Section */

/* Featured Profile CUM Profile display Section Male - Grooms + Female - Brides */

for($i=1;$i<=2;$i++)
{
	if($i==1)
	{
		$table="SEARCH_MALE";
		$g="male";
	}
	else
	{
		$table="SEARCH_FEMALE";
		$g="female";
	}

	$count_down=0;
	$count_row=0;
	$second_loop_count=0;
	$second_loop=0;

	$sql_2="SELECT SQL_CACHE PROFILEID FROM newjs.$table WHERE SUBSCRIPTION !='' AND HAVEPHOTO='Y' AND PHOTO_DISPLAY='A' AND PRIVACY IN('','A') AND $field='$val' ORDER BY ENTRY_DT DESC LIMIT 25";
	$res_2=mysql_query($sql_2,$db_slave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_2,"ShowErrTemplate");
	$count=mysql_num_rows($res_2);
	while($row_2=mysql_fetch_array($res_2))
	{
		if($count<25)
			$count_down=0;
		else
			$count_down=1;
		$pid[]=$row_2['PROFILEID'];	
	}

	$lim=25-sizeof($pid);	
	if($count_down==0)
	{
		$sql_3="SELECT SQL_CACHE PROFILEID FROM newjs.$table WHERE HAVEPHOTO='Y' AND PHOTO_DISPLAY='A' AND PRIVACY IN('','A') AND $field='$val' ORDER BY ENTRY_DT DESC LIMIT $lim";
		$res_3=mysql_query($sql_3,$db_slave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_3,"ShowErrTemplate");
		while($row_3=mysql_fetch_array($res_3))
		{
			if(sizeof($pid)<25)
				$count_down=0;
			else
				$count_down=1;
			$pid[]=$row_3['PROFILEID'];	
		}

		$lim1=25-sizeof($pid);

		if($count_down==0)
		{
			$sql_4="SELECT SQL_CACHE PROFILEID FROM newjs.$table WHERE SUBSCRIPTION !='' AND $field='$val' AND PRIVACY IN('','A') ORDER BY ENTRY_DT DESC LIMIT $lim1";
			$res_4=mysql_query($sql_4,$db_slave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_4,"ShowErrTemplate");
			while($row_4=mysql_fetch_array($res_4))
			{
				$pid[]=$row_4['PROFILEID'];	
			}
		} 
	}
	
	if($pid)
	{
		$profileids=implode(",",$pid);
		$sql_5="SELECT PROFILEID,USERNAME,GENDER,AGE,HEIGHT,CASTE,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,SUBCASTE,RELIGION FROM newjs.JPROFILE WHERE PROFILEID IN($profileids) ORDER BY PROFILEID";
		$res_5=mysql_query($sql_5,$db_slave) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_4,"ShowErrTemplate");
		$count_row=mysql_num_rows($res_5);
		if($count_row>5)
		{
			$second_loop_count=$count_row-5;
			$second_loop=$second_loop_count;
		}
		else
		{
			$second_loop_count=$count_row;
			$second_loop=0;
		}
		while($row_5=mysql_fetch_array($res_5))
		{
			$profileid=$row_5['PROFILEID'];
			$user=$row_5['USERNAME'];
			$gender=$row_5['GENDER'];
			$username[]=$row_5['USERNAME'];

			$caste=$row_5['CASTE'];	
			$caste_val_new=$CASTE_DROP_SMALL["$caste"];
			$caste_val[]=end(explode("-",$caste_val_new));

			$heightArr=explode("(",$HEIGHT_DROP[$row_5["HEIGHT"]]);
			$height_val[]=trim($heightArr[0]);

			$religion=$row_5['RELIGION'];	
			$religion_val[]=$RELIGIONS["$religion"];

			$mtng=$row_5["MTONGUE"];	
			$mtng_val[]=$MTONGUE_DROP_SMALL["$mtng"];

			$age[]=$row_5['AGE'];	

			$country=$row_5['COUNTRY_RES'];	
			$country_val[]=$COUNTRY_DROP["$country"];

			$city=$row_5['CITY_RES'];
			if($city=='')
				$city_val[]=$COUNTRY_DROP["$country"];
			else
				$city_val[]=$CITY_DROP["$city"];

			$occ=$row_5['OCCUPATION'];
			$occ_val[]=$OCCUPATION_DROP["$occ"];
			
			$PHOTOCHECKSUM=intval(intval($profileid)/1000) . "/" . md5($profileid+5);
			$myphoto[]='http://ser4.jeevansathi.com/thumbnails/'.$PHOTOCHECKSUM.'.jpg';

			$stat_uname=stat_name($profileid,$user);
	                $profile_url[]="$SITE_URL/profile/matrimonial-$stat_uname.htm";
		}
	}
	
	if($i=="1")
		$gender1='M';
	elseif($i=="2")
		$gender1='F';
	
	if($gender1=='M')
	{
		$smarty->assign("USERNAME_M",$username);
		$smarty->assign("CASTE_M",$caste_val);
		$smarty->assign("RELIGION_M",$religion_val);
		$smarty->assign("MSTATUS_M",$mtng_val);
		$smarty->assign("AGE_M",$age);
		$smarty->assign("HEIGHT_M",$height_val);
		$smarty->assign("CITY_M",$city_val);
		$smarty->assign("COUNTRY_M",$country_val);
		$smarty->assign("OCC_M",$occ_val);
		$smarty->assign("PHOTO_M",$myphoto);
		$smarty->assign("PROFILE_URL_M",$profile_url);
		$smarty->assign("COUNT_M",$second_loop_count);
		$smarty->assign("SECOND_LOOP_M",$second_loop);

		if($count_row<5)
		{
			$smarty->assign("STOP_LOOP_M","Y");
			$smarty->assign("LOOP_M",$count_row);
		}
		
	}
	elseif($gender1=='F')
	{
		if($count_row<5)
		{
			$smarty->assign("STOP_LOOP_F","Y");
			$smarty->assign("LOOP_F",$count_row);
		}
		$smarty->assign("USERNAME_F",$username);
		$smarty->assign("CASTE_F",$caste_val);
		$smarty->assign("RELIGION_F",$religion_val);
		$smarty->assign("MSTATUS_F",$mtng_val);
		$smarty->assign("AGE_F",$age);
		$smarty->assign("HEIGHT_F",$height_val);
		$smarty->assign("CITY_F",$city_val);
		$smarty->assign("COUNTRY_F",$country_val);
		$smarty->assign("OCC_F",$occ_val);
		$smarty->assign("PHOTO_F",$myphoto);
		$smarty->assign("PROFILE_URL_F",$profile_url);
		$smarty->assign("COUNT_F",$second_loop_count);
		$smarty->assign("SECOND_LOOP_F",$second_loop);
	}
	unset($pid);
	unset($username);
	unset($profile_url);
	unset($myphoto);
	unset($age);
	unset($city_val);
	unset($occ_val);
	unset($country_val);
	unset($height_val);
	unset($mtng_val);
	unset($religion_val);
	unset($caste_val);
}

/* Ends of the Section */

$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->display("index.htm");

/*
function stat_name($PROFILEID,$username)
{
	$sumProfileid=0;

	for($tempcnt=0;$tempcnt<strlen($PROFILEID);$tempcnt++)
	{
		$sumProfileid=$sumProfileid+$PROFILEID{$tempcnt};      //sum of profileid digits
	}
	$rotator=$sumProfileid%(strlen($PROFILEID));           //sum mod length of profileid rotator

	for($tempcnt=0;$tempcnt<strlen($PROFILEID);$tempcnt++)
	{
		$newpos=($tempcnt+$rotator)%strlen($PROFILEID);
		$rotatedProfileidArr[$newpos]=$PROFILEID{$tempcnt};    //rotated profileid
	}

	ksort($rotatedProfileidArr);

	if(count($rotatedProfileidArr)>1)
		$rotatedProfileid=implode("",$rotatedProfileidArr);
	else
		$rotatedProfileid=$rotatedProfileidArr[0];

	unset($rotatedProfileidArr);
	unset($sumProfileid);
	for($tempcnt=0;$tempcnt<strlen($username);$tempcnt++)
	{
		$asciiChr=ord($username{$tempcnt});

		if($asciiChr>=33 && $asciiChr<=126)
		{
			$stat_uname=$rotatedProfileid.$username{$tempcnt}.$rotator;
			break;
		}
	}
	unset($rotatedProfileid);
	unset($rotator);
	return $stat_uname;
}*/

function getrand($rand, $numbers, $array)
{
	while (in_array($rand, $numbers))
		$rand = rand(0,count($array)-1);
	return $rand;
}

/* flush the buffer */
if($zipIt && !$dont_zip_now)
ob_end_flush();

?>
