<?php
/************************************************************************************************************************
*       FILE NAME               : indicator_mis.php
*       DESCRIPTION             : JeevanSathi Indicator MIS
*       CREATION DATE           : 30 November, 2005
*       CREATED BY              : Shakti Srivastava
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/
ini_set("max_execution_time","0");
$dirname=dirname(__FILE__);
chdir($dirname);
$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$outside="Y";
$JSIndicator=1;
$today=date("Y-m-d");
//$today="2007-03-31";

$source_array=array("yahoo","google","NONE","jeevansathi","Google_NRI","Rediff06","google_coop","yahoosrch","Yahoo Group","Yahoo_Tgt","yahoo_Del_Mum","overture","marriage_bureau","infovision","rediff_affiliate","Rediff NRI","rediff","Rediff_tgt");
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
	//include("search_query.php");

	//Added By lavesh
	$total=0;

	if($d>$day)
	{
		include_once("connect.inc");
		$db=connect_misdb();
		$db2=connect_master();

		$st_date_l=$year."-".$month."-".$day;
		$sqlL="SELECT COUNT  FROM MIS.DAILYSEARCHCOUNT WHERE S_DATE='$st_date_l'";
		$resL=mysql_query_decide($sqlL,$db) or die("$sql".mysql_error_js($db));
		$rowL=mysql_fetch_array($resL);
		$total=$rowL["COUNT"];

		if($total<1)
		{		
			$st_date=$year."-".$month."-".$day." 00:00:00";
			$end_date=$eyear."-".$emonth."-".$eday." 23:59:59";

			$sqlL="SELECT COUNT(*) as CNT FROM MIS.SEARCHQUERY  WHERE DATE BETWEEN '$st_date' AND '$end_date'";
			$resL=mysql_query_decide($sqlL,$db) or die("$sql".mysql_error_js($db));
			$rowL=mysql_fetch_array($resL);
			$total=$rowL["CNT"];
			
			if($total>0)
			{
				$sqlL="INSERT INTO MIS.DAILYSEARCHCOUNT(COUNT,S_DATE) VALUES($total,'$st_date_l')";
				mysql_query_decide($sqlL,$db2) or die("$sql".mysql_error_js($db2));
			}
		}
		
	}

	$search[$day]=$total;
	unset($total);
	unset($count);
}
$smarty->assign("search",$search);

if(is_array($search))
	$smarty->assign("sum_search",array_sum($search));

/**************************************************************************************************************************/



/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps are for computing Cluster Search count
**************************************************************************************************************************/
list($year,$month,$day)=explode("-",$today);
$sum_cluster=0;

mysql_ping_js();

$sql="SELECT COUNT,DAYOFMONTH(DATE) AS DAY FROM MIS.SEARCH_CLUSTERING WHERE DATE BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31'";
$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
while($row=mysql_fetch_array($res))
{
	$cluster[$row['DAY']]['COUNT']=$row['COUNT'];
	$sum_cluster=$sum_cluster+$row['COUNT'];
}
$smarty->assign("cluster",$cluster);
$smarty->assign("sum_cluster",$sum_cluster);
unset($sum_cluster);
unset($cluster);
/**************************************************************************************************************************/


/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps are for computing Registered people who have utilsed
					:	"Save-As-Matchalert" feature.
**************************************************************************************************************************/
list($year,$month,$day)=explode("-",$today);
$sum_matchalert=0;

mysql_ping_js();

$sql="SELECT COUNT,DAYOFMONTH(DATE) AS DAY FROM MIS.TOP_SAVE_MATCHALERT WHERE DATE BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31'";
$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
while($row=mysql_fetch_array($res))
{
	$saveMatchalert[$row['DAY']]['COUNT']=$row['COUNT'];
	$sum_matchalert=$sum_matchalert+$row['COUNT'];
}
$smarty->assign("saveMatchalert",$saveMatchalert);
$smarty->assign("sum_matchalert",$sum_matchalert);
unset($saveMatchalert);
unset($sum_matchalert);
/**************************************************************************************************************************/

/**************************************************************************************************************************
                DATA DESCRIPTION        :       The following steps are for computing Total Number of search agents (saved)
                                                and Total number of unique users who are saving search agents
**************************************************************************************************************************/

list($year,$month,$thisday)=explode("-",$today);
for($loopday=1;$loopday<=$thisday;$loopday++)
{
        if($loopday<10)
                $day="0".$loopday;
        else
                $day=$loopday;

        $sql="SELECT COUNT(DISTINCT PROFILEID) savesearch_user_count,COUNT(SEARCH_NAME) savesearch_agent_count FROM newjs.SEARCH_AGENT WHERE DATE BETWEEN '".$year."-".$month."-".$day." 00:00:00' AND '".$year."-".$month."-".$day." 23:59:59'";
        $res=mysql_query_decide($sql,$db2) or die(mysql_error_js()."<BR>".$sql);
        $row=mysql_fetch_array($res);
        $savesearch_user_count[$loopday]=$row['savesearch_user_count'];
        $savesearch_agent_count[$loopday]=$row['savesearch_agent_count'];
}
$smarty->assign("savesearch_agent_count",$savesearch_agent_count);
$smarty->assign("savesearch_user_count",$savesearch_user_count);
$smarty->assign("sum_savesearch_agent_count",array_sum($savesearch_agent_count));
$smarty->assign("sum_savesearch_user_count",array_sum($savesearch_user_count));
unset($savesearch_user_count);
unset($savesearch_agent_count);
/**************************************************************************************************************************

/**************************************************************************************************************************
                DATA DESCRIPTION        :       The following steps are for computing Number of photo requests generated
                                                daily.
**************************************************************************************************************************/
list($year,$month,$thisday)=explode("-",$today);

//Sharding Concept added by Vibhor Garg on table JPARTNER
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
$mysqlObj=new Mysql;
$jpartnerObj=new Jpartner;
for($i=0;$i<$noOfActiveServers;$i++)
{
        $myDbName=$activeServers[$i];
        $myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
}
//Sharding Concept added by Vibhor Garg on table JPARTNER

for($loopday=1;$loopday<=$thisday;$loopday++)
{
        if($loopday<10)
                $day="0".$loopday;
        else
                $day=$loopday;

        for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
        {
                //Sharding
		$myDbName=$activeServers[$activeServerId];
                $mysqlObj->ping($myDbArray[$myDbName]);
		$myDb=$myDbArray[$myDbName];
                //Sharding

                $sql="SELECT A.PROFILEID FROM newjs.PHOTO_REQUEST A , newjs.PROFILEID_SERVER_MAPPING B WHERE DATE ='".$year."-".$month."-".$day."' AND A.PROFILEID = B.PROFILEID AND B.SERVERID=$activeServerId";
                $res=$mysqlObj->executeQuery($sql,$myDb);
                while($row=$mysqlObj->fetchArray($res))
                {
			$sql_photo="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
			$res_photo=mysql_query_decide($sql_photo);
			$row_photo=mysql_fetch_array($res_photo);
			if($row_photo['GENDER']=='M')
				$i++;
			elseif($row_photo['GENDER']=='F')
				$j++;
                }
        }


        $photoreq_by_male[$loopday]=$i;
        $photoreq_by_female[$loopday]=$j;
        $photoreq_total[$loopday]=$i+$j;
}
$smarty->assign("photoreq_by_male",$photoreq_by_male);
$smarty->assign("photoreq_by_female",$photoreq_by_female);
$smarty->assign("photoreq_total",$photoreq_total);
$smarty->assign("sum_photoreq_by_male",array_sum($photoreq_by_male));
$smarty->assign("sum_photoreq_by_female",array_sum($photoreq_by_female));
$smarty->assign("sum_photoreq_total",array_sum($photoreq_total));
unset($photoreq_by_male);
unset($photoreq_by_female);
unset($photoreq_total);
/**************************************************************************************************************************/

/**************************************************************************************************************************
                DATA DESCRIPTION        :       The following steps are for computing Number of users saving DPP during
                                                registration, Number of users saving DPP in My Jeevansathi (new and
                                                updations), Number of users updating DPP daily
**************************************************************************************************************************/
list($year,$month,$thisday)=explode("-",$today);

for($loopday=1;$loopday<=$thisday;$loopday++)
{
        if($loopday<10)
                $day="0".$loopday;
        else
                $day=$loopday;

	//Sharding Concept added by Vibhor Garg on table JPARTNER
	for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
	{
		$myDbName=$activeServers[$activeServerId];
                $mysqlObj->ping($myDbArray[$myDbName]);
                $myDb=$myDbArray[$myDbName];
		$date=$year."-".$month."-".$day;
		$total_dpp[$loopday]+=$jpartnerObj->calculateCountInPartnerProfile($myDb,$mysqlObj,$date);                
	}
	//Sharding Concept added by Vibhor Garg on table JPARTNER

        $sql="SELECT COUNT FROM newjs.TRACK_DPP WHERE DATE ='".$year."-".$month."-".$day."'";
        $res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
        $row=mysql_fetch_array($res);
        $total_myjsdpp[$loopday]=$row['COUNT'];
        $sql="SELECT COUNT FROM MIS.REGISTRATION_EDIT_DPP WHERE DATE ='".$year."-".$month."-".$day."'";
        $res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
        $row=mysql_fetch_array($res);
        $total_regdpp[$loopday]=$row['COUNT'];
}
$smarty->assign("total_dpp",$total_dpp);
$smarty->assign("total_myjsdpp",$total_myjsdpp);
$smarty->assign("total_regdpp",$total_regdpp);
$smarty->assign("sum_total_dpp",array_sum($total_dpp));
$smarty->assign("sum_total_myjsdpp",array_sum($total_myjsdpp));
$smarty->assign("sum_total_regdpp",array_sum($total_regdpp));
/**************************************************************************************************************************/

/**************************************************************************************************************************
                DATA DESCRIPTION        :       The following steps are for computing Number of paid contacts (initiated)
                                                daily and Number of paid users (sender) initiating these paid contacts
                                                (daily).
**************************************************************************************************************************/
list($year,$month,$day)=explode("-",$today);

for($loopday=1;$loopday<=$thisday;$loopday++)
{
        if($loopday<10)
                $day="0".$loopday;
        else
                $day=$loopday;

	$i=0;$j=0;
	for($i=0;$i<$noOfActiveServers;$i++)
	{
		$myDbName=$slave_activeServers[$i];
		$myDb=$mysqlObj->connect("$myDbName");
		$sql="SELECT SENDER,RECEIVER FROM newjs.CONTACTS  as ct,newjs.PROFILEID_SERVER_MAPPING as psm WHERE ct.SENDER=psm.PROFILEID and TIME BETWEEN '".$year."-".$month."-".$day." 00:00:00' AND '".$year."-".$month."-".$day." 23:59:59' AND TYPE = 'I' AND  psm.SERVERID=$i";
		$res=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
		while($row=mysql_fetch_array($res))
		{
			$sql_rec="SELECT COUNT(*) cnt FROM newjs.JPROFILE WHERE PROFILEID='$row[RECEIVER]' AND SUBSCRIPTION!=''";
			$res_rec=mysql_query_decide($sql_rec) or die(mysql_error_js($res_rec));
			$row_rec=mysql_fetch_array($res_rec);
			if($row_rec['cnt']>0)
			{
				$i++;
				$sql_sender="SELECT COUNT(*) cnt FROM newjs.JPROFILE WHERE PROFILEID='$row[SENDER]' AND SUBSCRIPTION!=''";
				$res_sender=mysql_query_decide($sql_sender) or die(mysql_error_js($res_sender));
				$row_sender=mysql_fetch_array($res_sender);
				if($row_sender['cnt']>0)
					$j++;
			}
		}
	}
        $cnt_paidrec[$loopday]=$i;
        $cnt_paidsender[$loopday]=$j;
}
$smarty->assign("cnt_paidrec",$cnt_paidrec);
$smarty->assign("cnt_paidsender",$cnt_paidsender);
$smarty->assign("sum_cnt_paidrec",array_sum($cnt_paidrec));
$smarty->assign("sum_cnt_paidsender",array_sum($cnt_paidsender));
unset($cnt_paidrec);
unset($cnt_paidrec);
/**************************************************************************************************************************/

/**************************************************************************************************************************
		DATA DESCRIPTION	:	The following steps are for computing Un-Registered people who have utilsed
					:	"Save-As-Matchalert" feature.
**************************************************************************************************************************/
list($year,$month,$thisday)=explode("-",$today);
$sum_matchalert=0;

mysql_ping_js();

for($loopday=1;$loopday<=31;$loopday++)
{
	if($loopday<10)
		$day="0".$loopday;
	else
		$day=$loopday;

	$sql="SELECT COUNT(*) AS CNT FROM newjs.TOP_SAVE_MATCHALERT WHERE DATE BETWEEN '".$year."-".$month."-".$day." 00:00:00' AND '".$year."-".$month."-".$day." 23:59:59'";
	$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
	$row=mysql_fetch_array($res);

	$saveMatchalert[$loopday]=$row['CNT'];
}
$smarty->assign("saveMatch",$saveMatchalert);
$smarty->assign("sumMatch",array_sum($saveMatchalert));
unset($saveMatchalert);
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


/**************************************************************************************************************************
	DATA DESCRIPTION	:	The following steps are for computing the number of users using the 
				:	"save-as-matchalert" feature to whome the "welcome-mail" has been sent.
**************************************************************************************************************************/
list($year,$month,$thisday)=explode("-",$today);

mysql_ping_js();

for($loopday=1;$loopday<=$thisday;$loopday++)
{
	if($loopday<10)
		$day="0".$loopday;
	else
		$day=$loopday;

	$sql="SELECT COUNT(*) AS CNT,DATE_FORMAT(DATE,'%Y-%m-%d') AS DT FROM newjs.TOP_SAVE_MATCHALERT WHERE DATE BETWEEN '".$year."-".$month."-".$day." 00:00:00' AND '".$year."-".$month."-".$day." 23:59:59' AND MAIL_SENT='Y' GROUP BY DT";	
	$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
	$row=mysql_fetch_array($res);

	$welcome_mail[$loopday]=$row['CNT'];
}
$smarty->assign("WEL_MAIL",$welcome_mail);
$smarty->assign("SUM_WEL_MAIL",array_sum($welcome_mail));
unset($welcome_mail);

/**************************************************************************************************************************/


/**************************************************************************************************************************
	DATA DESCRIPTION	:	The following steps are for computing the number of users who have un-subscribed 
				:	from the "save-as-matchalert" feature.
**************************************************************************************************************************/
list($year,$month,$thisday)=explode("-",$today);

mysql_ping_js();

for($loopday=1;$loopday<=$thisday;$loopday++)
{
	if($loopday<10)
                $day="0".$loopday;
	else
		$day=$loopday;

	$sql="SELECT COUNT(*) AS CNT,DATE_FORMAT(DATE,'%Y-%m-%d') AS DT FROM newjs.TOP_SAVE_MATCHALERT WHERE DATE BETWEEN '".$year."-".$month."-".$day." 00:00:00' AND '".$year."-".$month."-".$day." 23:59:59' AND SUBSCRIBE='N' GROUP BY DT";
	$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
	$row=mysql_fetch_array($res);

	$unsubs[$loopday]=$row['CNT'];
}
$smarty->assign("UNSUB",$unsubs);
$smarty->assign("SUM_UNSUB",array_sum($unsubs));
unset($unsubs);
/**************************************************************************************************************************/

/**************************************************************************************************************************
	DATA DESCRIPTION	:	The following steps are for computing the open rate of matchalert being sent to 
				:	people using the "Save-As-Matchalert" feature.
**************************************************************************************************************************/
$db2 = mysql_connect(MysqlDbConstants::$misSlave[HOST],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS]) or die("Error while connecting to slave");

//$db2=mysql_connect("localhost","root","Km7Iv80l") or die("Error while connecting to slave");
list($year,$month,$day)=explode("-",$today);

$zero=mktime(0,0,0,01,01,2006);				//timestamp for 1-1-2006
$currMonthTime=mktime(0,0,0,$month,01,$year);		//timestamp for 1st of this month
$todayTime=mktime(0,0,0,$month,$day,$year);		//today's timestamp

$maxDay=($todayTime-$zero)/(24*60*60);                                            
$minDay=($currMonthTime-$zero)/(24*60*60);

$sql="SELECT COUNT(*) AS CNT,DATE FROM matchalerts.TOP_VIEW_COUNT WHERE DATE BETWEEN '".$minDay."' AND '".$maxDay."' GROUP BY DATE ORDER BY DATE";
$res=mysql_query_decide($sql,$db2) or die(mysql_error_js()."<BR>".$sql);

for($tmpcnt=1;$tmpcnt<=31;$tmpcnt++)
{
	$open_rate[$tmpcnt]=0;
}

while($row=mysql_fetch_array($res))
{
	$open_rate[$row['DATE']-$minDay+1]=$row['CNT'];
}

$smarty->assign("OPEN_RATE",$open_rate);
$smarty->assign("SUM_OPEN_RATE",array_sum($open_rate));
unset($open_rate);
//mysql_close($db2);
/**************************************************************************************************************************/


list($file_yr,$file_mth,$file_day)=explode("-",$today);
$smarty->assign("mm",$file_mth);
$smarty->assign("yy",$file_yr);
$smarty->assign("mth",$month_array);
//$smarty->display("indicator_mis.htm");

$mishtm=$smarty->fetch("indicator_mis.htm");

$file_name="/usr/local/indicators/".$file_mth."_".$file_yr.".htm";
//$file_name="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/sstemp.htm";

$fd=fopen($file_name,"w");
fwrite($fd,$mishtm);
fclose($fd);

passthru("chmod 664 ".$file_name);
?>
