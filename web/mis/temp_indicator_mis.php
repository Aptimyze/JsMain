<?php
/************************************************************************************************************************
*       FILE NAME               : indicator_mis.php
*       DESCRIPTION             : JeevanSathi Indicator MIS
*       CREATION DATE           : 30 November, 2005
*       CREATED BY              : Shakti Srivastava
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

$outside="Y";
$JSIndicator=1;
$today=date("Y-m-d");
$today="2006-01-31";

$source_array=array("yahoo","google","NONE","jeevansathi","Sify","samachar");
$month_array=array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");

if($checksum)
	$cid=$checksum;
else if($cid)
	$checksum=$cid;


/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate View Similar Count
**************************************************************************************************************************/
include("view_similar_count.php");
$smarty->assign("view_sim",$cnt);

if(is_array($cnt))
	$smarty->assign("sum_view_sim",array_sum($cnt));

unset($cnt);
/**************************************************************************************************************************/

/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps are for calculating Daily Login Count
**************************************************************************************************************************/
include("daily_login_count.php");
$smarty->assign("daily_login",$cnt);

if(is_array($cnt))
	$smarty->assign("sum_daily_login",array_sum($cnt));

unset($cnt);
/**************************************************************************************************************************/

/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps are for calculating Daily Contact Count
**************************************************************************************************************************/
include("daily_contact_count.php");
$smarty->assign("total_contacts",$tot1);

if(is_array($tot1))
	$smarty->assign("sum_total_contacts",array_sum($tot1));
unset($tot1);

$smarty->assign("initial_contacts",$icnt);

if(is_array($icnt))
	$smarty->assign("sum_initial_contacts",array_sum($icnt));
unset($icnt);

$smarty->assign("accepted_contacts",$acnt);

if(is_array($acnt))
	$smarty->assign("sum_accepted_contacts",array_sum($acnt));
unset($acnt);

$smarty->assign("declined_contacts",$dcnt);

if(is_array($dcnt))
	$smarty->assign("sum_declined_contacts",array_sum($dcnt));
unset($dcnt);

$smarty->assign("cancelled_contacts",$ccnt);

if(is_array($ccnt))
	$smarty->assign("sum_cancelled_contacts",array_sum($ccnt));
unset($ccnt);
/**************************************************************************************************************************/


/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate Total Paid Members, Indian Members & 
					:	NRI Members
**************************************************************************************************************************/
unset($outside);
list($year,$month,$d)=explode("-",$today);
$sum_totamt=0;
$sum_totamti=0;
$sum_totamtn=0;
for($day=1;$day<=31;$day++)
{
	$CMDGo="Y";
	include("paid_n_percentages.php");
	$ss_data[$day]['totamt']=$totamt;
	$sum_totamt=$sum_totamt+$totamt;
	$ss_data[$day]['totamti']=$totamti;
	$sum_totamti=$sum_totamti+$totamti;
	$ss_data[$day]['totamtn']=$totamtn;
	$sum_totamtn=$sum_totamtn+$totamtn;
	unset($totamt);
	unset($totamti);
	unset($totamtn);
}
$smarty->assign("sum_total",$sum_totamt);
$smarty->assign("sum_totali",$sum_totamti);
$smarty->assign("sum_totaln",$sum_totamtn);
$smarty->assign("paidNPer",$ss_data);
unset($ss_data);
/**************************************************************************************************************************/



/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate Online Paid Members
**************************************************************************************************************************/
$CMDGo="Go";
$vtype="D";
$branch="HO";
list($dyear,$dmonth,$d)=explode("-",$today);
include("collectionmis.php");
$sumonline=0;
for($day=0;$day<32;$day++)
{
	$sumonline=$sumonline+$amt[$day]['ol'];
}
$smarty->assign("online",$amt);
$smarty->assign("sum_online",$sumonline);
unset($amt);
/**************************************************************************************************************************/


/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate All Complete Profiles
**************************************************************************************************************************/

$CMDGo="Y";

//For all complete profiles
$sourcegp="";
$profile_type="E";
$community="";
$gender="";
$mstatus="";
$country="";
$subs="";
$incomplete="N";
$activated="";
$dt_type="day";
list($ddate_yyyy,$ddate_mon,$d)=explode("-",$today);
include("sourcehits.php");
$sum_complete_pro=0;
for($cnt=0;$cnt<32;$cnt++)
{
	$sum_complete_pro=$sum_complete_pro+$totb[$cnt]['m'];
}
$smarty->assign("comp_profiles",$totb);
$smarty->assign("sum_complete_pro",$sum_complete_pro);
unset($totb);
/**************************************************************************************************************************/



/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate All Incomplete Profiles
**************************************************************************************************************************/
//For all incomplete profiles
unset($cnt);
unset($b_count);
unset($tota);
unset($totb);
unset($totallh);
unset($totallm);
unset($totallp);
unset($avgtota);
unset($avgtotallm);
unset($b_tota);
unset($gender);
unset($flag_b);
unset($flag_bs);
unset($srcarr);
unset($mmarr);
unset($ddarr);
unset($hharr);

unset($totb);
$sourcegp="";
$profile_type="E";
$community="";
$gender="";
$mstatus="";
$country="";
$subs="";
$incomplete="Y";
$activated="";
$dt_type="day";
list($ddate_yyyy,$ddate_mon,$d)=explode("-",$today);
include("sourcehits.php");
$sum_incom_pro=0;
for($cnt=0;$cnt<30;$cnt++)
{
	$sum_incom_pro=$sum_incom_pro+$totb[$cnt]['m'];
}
$smarty->assign("incomp_profiles",$totb);
$smarty->assign("sum_incom_pro",$sum_incom_pro);
unset($totb);
/**************************************************************************************************************************/


/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate Total Number of Profiles
**************************************************************************************************************************/
//Total number of profiles
unset($cnt);
unset($b_count);
unset($tota);
unset($totb);
unset($totallh);
unset($totallm);
unset($totallp);
unset($avgtota);
unset($avgtotallm);
unset($b_tota);
unset($gender);
unset($flag_b);
unset($flag_bs);
unset($srcarr);
unset($mmarr);
unset($ddarr);
unset($hharr);

unset($totb);
$sourcegp="";
$profile_type="E";
$community="";
$gender="";
$mstatus="";
$country="";
$subs="";
$incomplete="";
$activated="";
$dt_type="day";
list($ddate_yyyy,$ddate_mon,$d)=explode("-",$today);
include("sourcehits.php");
for($tempvar=0;$tempvar<=count($srcarr);$tempvar++)
{
	$tempsum=0;

	if(in_array($srcarr[$tempvar],$source_array))
	{
		$src_req_val[]=$cnt[$tempvar];
		$src_req_name[]=$srcarr[$tempvar];
		for($sscnt=0;$sscnt<31;$sscnt++)
		{
			$tempsum=$tempsum+$cnt[$tempvar][$sscnt]['m'];
		}
		$sum_source[]=$tempsum;
	}
}
$sum_total_pro=0;
$sum_conversion=0;
$sum_hits=0;
for($cnt=0;$cnt<31;$cnt++)
{
	$sum_total_pro=$sum_total_pro+$totb[$cnt]['m'];
	$sum_conversion=$sum_conversion+$totb[$cnt]['p'];
	$sum_hits=$sum_hits+$totb[$cnt]['h'];
}
$smarty->assign("all_profiles",$totb);
$smarty->assign("sum_total_pro",$sum_total_pro);			//summation of all profiles
$smarty->assign("sum_hits",$sum_hits);					//summation of total hits

if(count($totb))
	$smarty->assign("avg_conver",round($sum_conversion/count($totb),3));	//average of conversion

$smarty->assign("breakup_name",$src_req_name);				//break up of
$smarty->assign("breakup_val",$src_req_val);				//major sources
$smarty->assign("sum_source",$sum_source);				//major sources
unset($totb);
unset($cnt);
unset($srcarr);
unset($src_req_name);
unset($src_req_val);
/**************************************************************************************************************************/


/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate Profiles Live on Site
**************************************************************************************************************************/
//For all Live profiles
unset($cnt);
unset($b_count);
unset($tota);
unset($totb);
unset($totallh);
unset($totallm);
unset($totallp);
unset($avgtota);
unset($avgtotallm);
unset($b_tota);
unset($gender);
unset($flag_b);
unset($flag_bs);
unset($srcarr);
unset($mmarr);
unset($ddarr);
unset($hharr);

unset($totb);
unset($srcarr);
$sourcegp="";
$profile_type="E";
$community="";
$gender="";
$mstatus="";
$country="";
$subs="";
$incomplete="";
$activated="Y";
$dt_type="day";
list($ddate_yyyy,$ddate_mon,$d)=explode("-",$today);
include("sourcehits.php");
$sum_live=0;
for($cnt=0;$cnt<31;$cnt++)
{
	$sum_live=$sum_live+$totb[$cnt]['m'];
}
$smarty->assign("live_profiles",$totb);
$smarty->assign("sum_live",$sum_live);
unset($totb);
/**************************************************************************************************************************/



/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps calculate Total Searches
**************************************************************************************************************************/
list($year,$month,$d)=explode("-",$today);
list($eyear,$emonth,$ed)=explode("-",$today);
for($day=1;$day<=31;$day++)
{
        $CMDGo="Y";
	$eday=$day;
	include("search_query.php");
	$search[$day]=$total;
	unset($total);
	unset($count);
}
$smarty->assign("search",$search);

if(is_array($search))
	$smarty->assign("sum_search",array_sum($search));

/**************************************************************************************************************************/



/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps are for computing Server Load
**************************************************************************************************************************/
list($year,$month,$day)=explode("-",$today);

for($loopday=01;$loopday<=$day;$loopday++)
{
	$min=0;
	$max=0;
	$avg=0;
	$flag=0;

	if($loopday<10)
		$loopday="0".$loopday;

	$filename=JsConstants::$docRoot."/loadchkr/".$month.$loopday;
//	$filename="/usr/local/apache/sites/jeevansathi.com/htdocs/shakti/load/".$month.$loopday;
	$hnd=fopen("$filename","r");
	$sumload=0;

	if($hnd)
	{
		while($info=fscanf($hnd,"%s %s           %s"))
		{
			if($flag==0)
			{
				$min=$info[2];
			}

			$tempmin=$info[2];
			$tempmax=$info[2];
			$sumload=$sumload+$info[2];

			if($tempmin<$min)
				$min=$tempmin;

			if($tempmax>$max)
				$max=$tempmax;

			$flag++;
		}

		$minima[]=$min;
		$maxima[]=$max;
		$average[]=round(($sumload)/$flag,3);
		fclose($hnd);
	}
}

$smarty->assign("minima",$minima);
$smarty->assign("maxima",$maxima);
$smarty->assign("average",$average);
/**************************************************************************************************************************/


list($file_yr,$file_mth,$file_day)=explode("-",$today);
$smarty->assign("mm",$file_mth);
$smarty->assign("yy",$file_yr);
$smarty->assign("mth",$month_array);
//$smarty->display("indicator_mis.htm");

$mishtm=$smarty->fetch("indicator_mis.htm");

$file_name=JsConstants::$docRoot."/mis/indicators/".$file_mth."_".$file_yr.".htm";
//$file_name="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/sstemp.htm";

$fd=fopen($file_name,"w");
fwrite($fd,$mishtm);
fclose($fd);

passthru("chmod 664 ".$file_name);
?>
