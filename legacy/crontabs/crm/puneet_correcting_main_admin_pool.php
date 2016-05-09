<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
ini_set('max_execution_time','0');
ini_set("memory_limit","-1");
include("../connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$start_time=date("Y-m-d H:i:s");

// calculating date 30 days ago
$ts = time();
//$ts-=24*60*60;
$ts1 = $ts - 24*60*60;
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);
$end_dt = date("Y-m-d H:i:s");

// today's date
$today_dt=date('Y-m-d',$ts1);

$icnt=0;
$ucnt=0;

$conv_rate_i[50]=0;
$conv_rate_i[100]=0;
$conv_rate_i[150]=0;
$conv_rate_i[200]=0;
$conv_rate_i[250]=1.77;
$conv_rate_i[300]=6.14;
$conv_rate_i[350]=13.85;
$conv_rate_i[400]=21.64;
$conv_rate_i[450]=32.77;
$conv_rate_i[500]=47.86;
$conv_rate_i[550]=60.90;
$conv_rate_i[600]=74.15;

$conv_rate_nri[50]=0;
$conv_rate_nri[100]=0;
$conv_rate_nri[150]=0;
$conv_rate_nri[200]=0;
$conv_rate_nri[250]=2.64;
$conv_rate_nri[300]=8.72;
$conv_rate_nri[350]=17.93;
$conv_rate_nri[400]=26.32;
$conv_rate_nri[450]=37.32;
$conv_rate_nri[500]=49.35;
$conv_rate_nri[550]=65.49;
$conv_rate_nri[600]=77.94;


include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
$db2 = connect_slave();
$db=connect_db();
$LOG_PRO=array();

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");
}

//$sql = "CREATE TEMPORARY TABLE test.DIFF_JPROFILE_MAIN_ADMIN_POOL(PROFILEID MEDIUMINT(6) PRIMARY KEY) ";
//$result = mysql_query($sql,$db2) or logError($sql,$db2);

//$sql = "INSERT INTO test.DIFF_JPROFILE_MAIN_ADMIN_POOL SELECT PROFILEID FROM newjs.JPROFILE WHERE ENTRY_DT < '2008-04-07' ";
//$result = mysql_query($sql,$db2) or logError($sql,$db2);


$sql = "SELECT A.PROFILEID FROM test.DIFF_JPROFILE_MAIN_ADMIN_POOL AS A LEFT JOIN incentive.MAIN_ADMIN_POOL AS B on A.PROFILEID=B.PROFILEID WHERE B.PROFILEID IS NULL ";
$result = mysql_query($sql,$db2) or logError($sql,$db2);

while($row=mysql_fetch_array($result))
{
	$LOG_PRO[$row['PROFILEID']]=$row['PROFILEID'];
}

//$sql = "DROP TABLE test.DIFF_JPROFILE_MAIN_ADMIN_POOL";
//$result = mysql_query($sql,$db2) or logError($sql,$db2);

if(count($LOG_PRO))
foreach($LOG_PRO as $key=>$val)
{
	@mysql_ping($db2);
	$pid =  $key;
	
	$sql="SELECT GENDER FROM JPROFILE WHERE PROFILEID='$pid'";
	$result=mysql_query($sql,$db2) or logError($sql,$db2);
	$myrow=mysql_fetch_array($result);
	if($myrow['GENDER']=='M')
		$tablename='SEARCH_MALE';
	else
		$tablename='SEARCH_FEMALE';
			
	$sql="select TOTAL_POINTS FROM $tablename where PROFILEID='$pid'";
	$result=mysql_query($sql,$db2) or logError($sql,$db2);
	$myrow=mysql_fetch_array($result);

	if($myrow['TOTAL_POINTS']!='49')
	{
		$sql_pid = "SELECT  ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE ,SUBSCRIPTION, PHOTODATE, MTONGUE , SMOKE, DRINK, MANGLIK, BTYPE, DIET, SHOW_HOROSCOPE , COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";
		$res_pid = mysql_query($sql_pid,$db2) or logError($sql_pid,$db2);
		if ($row_pid = mysql_fetch_array($res_pid))
		{
		
			$source=$row_pid['SOURCE'];
			$entry_dt=$row_pid["ENTRY_DT"];
			$photo_dt=$row_pid["PHOTODATE"];
			$mtongue = $row_pid["MTONGUE"];

			$myDbName=getProfileDatabaseConnectionName($pid);

			if(!$myDb[$myDbName])
				$myDb[$myDbName]=$mysql->connect("$myDbName","slave");

			// query to find the first date in an interval of 30 days when the user logged in
			$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT BETWEEN '$start_dt' AND '$today_dt'";
			$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or logError($sql_login_cnt,$db2);
			$i=0;
			while($row_login_cnt = mysql_fetch_array($res_login_cnt))
			{
				$login_cnt = $row_login_cnt['CNT'];
			}

			// query to find the count of contacts initiated
			$sql_init_cnt ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TIME BETWEEN '$start_dt' AND '$today_dt 23:59:59'";
			$res4 = mysql_query($sql_init_cnt,$db2) or logError($sql_init_cnt,$db2);
			$row4 = mysql_fetch_array($res4);
			$INITIATE_CNT= $row4['CNT4'];

			// query to find the count of contacts accepted
			$sql_accpt_cnt="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER='$pid' and TYPE='A' AND TIME BETWEEN '$start_dt' AND '$today_dt 23:59:59'";
			$result=mysql_query($sql_accpt_cnt,$db2) or logError($sql_accpt_cnt,$db2);
			$myrow=mysql_fetch_array($result);
			$ACCEPTANCE_MADE = $myrow["CNT"];
			$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;

			$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);

			$static_score=0;        //static_score is global in function calc_user_score so its value is calculated and available after function's execution is over.

			$score = calc_user_score($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
			$score_search = calc_user_score_search($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);

			$n=0;
			$attribute_score=0;
			$conv_rate_attribute=0;
			
			if($row_pid['SMOKE'])
				$n++;
			if($row_pid['DRINK'])
				$n++;
			if($row_pid['MANGLIK'])
				$n++;
			if($row_pid['BTYPE'])
				$n++;
			if($row_pid['DIET'])
				$n++;
			if($row_pid['SHOW_HOROSCOPE'])
				$n++;
			if($n>=4)
				$attribute_score=$static_score;
		
			if($row_pid['COUNTRY_RES']!=51)
			{
				if($attribute_score>550)
					$conv_rate_attribute=$conv_rate_nri[600];
				elseif($attribute_score>500)
					$conv_rate_attribute=$conv_rate_nri[550];
				elseif($attribute_score>450)
					$conv_rate_attribute=$conv_rate_nri[500];
				elseif($attribute_score>400)
					$conv_rate_attribute=$conv_rate_nri[450];
				elseif($attribute_score>350)
					$conv_rate_attribute=$conv_rate_nri[400];
				elseif($attribute_score>300)
					$conv_rate_attribute=$conv_rate_nri[350];
				elseif($attribute_score>250)
					$conv_rate_attribute=$conv_rate_nri[300];
				elseif($attribute_score>200)
					$conv_rate_attribute=$conv_rate_nri[250];
				else
					$conv_rate_attribute=$conv_rate_nri[200];
			}
			else
			{
				if($attribute_score>550)
					$conv_rate_attribute=$conv_rate_i[600];
				elseif($attribute_score>500)
					$conv_rate_attribute=$conv_rate_i[550];
				elseif($attribute_score>450)
					$conv_rate_attribute=$conv_rate_i[500];
				elseif($attribute_score>400)
					$conv_rate_attribute=$conv_rate_i[450];
				elseif($attribute_score>350)
					$conv_rate_attribute=$conv_rate_i[400];
				elseif($attribute_score>300)
					$conv_rate_attribute=$conv_rate_i[350];
				elseif($attribute_score>250)
					$conv_rate_attribute=$conv_rate_i[300];
				elseif($attribute_score>200)
					$conv_rate_attribute=$conv_rate_i[250];
				else
					$conv_rate_attribute=$conv_rate_i[200];
			}
				
			$allot_avail = 'Y';
			$city_res=$row_pid['CITY_RES'];

			@mysql_ping($db);

			$sql_rec_exists = "SELECT COUNT(*) as CNT FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$pid'";
			$res_rec_exists = mysql_query($sql_rec_exists,$db2) or logError($sql_rec_exists,$db2);
			if ($row_rec_exists = mysql_fetch_array($res_rec_exists))
			{
				if($score<=150)
					$newscore=-50;
				elseif($score<326)
					$newscore=150;
				else
					$newscore=300;

				if($score_search<=150)
					$newscore_search=-50;
				elseif($score_search<326)
					$newscore_search=150;
				else
					$newscore_search=300;
				
				if($row_pid['HAVEPHOTO']=='Y')
					$diff=DayDiff(substr($photo_dt,0,10),$today_dt);
				else
					$diff=DayDiff(substr($entry_dt,0,10),$today_dt);
				$freshness_points=0;
				if($diff<16)
					$freshness_points=300;
				elseif($diff<46)
					$freshness_points=150;
				else
					$freshness_points=100;

				$total_points=$newscore+$freshness_points;
				$total_points_search=$newscore_search+$freshness_points;

				if ($row_rec_exists['CNT'] > 0)
				{
					$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET SCORE='$score',CITY_RES='$city_res', TOTAL_POINTS='$total_points', MTONGUE='$mtongue' , ATTRIBUTE_SCORE='$attribute_score' , CONV_RATE_ATTRIBUTE='$conv_rate_attribute'  WHERE PROFILEID ='$pid'";
					mysql_query($sql_update_pool,$db) or logError($sql_update_pool,$db);
					$ucnt++;

					if($row_pid['GENDER']=="F")
					{
						$sql="UPDATE newjs.SEARCH_FEMALE SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db) or logError($sql,$db);

						$sql="UPDATE newjs.SEARCH_FEMALE_FULL1 SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points' ,PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db2) or logError($sql,$db2);
					}
					else
					{
						$sql="UPDATE newjs.SEARCH_MALE SET SCORE_POINTS='$newscore_search', TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db) or logError($sql,$db);

						$sql="UPDATE newjs.SEARCH_MALE_FULL1 SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db2) or logError($sql,$db2);
					}
				}
				else
				{
					$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL (PROFILEID,SCORE,ALLOTMENT_AVAIL,TIMES_TRIED,SOURCE,ENTRY_DT,CITY_RES,TOTAL_POINTS,MTONGUE,ATTRIBUTE_SCORE,CONV_RATE_ATTRIBUTE) VALUES ('$pid','$score','$allot_avail','0','".addslashes($source)."','$entry_dt','$city_res','$total_points','$mtongue','$attribute_score','$conv_rate_attribute')";
					mysql_query($sql_insert,$db) or logError($sql_insert,$db);
					$icnt++;

					if($row_pid['GENDER']=="F")
					{
						$sql="UPDATE newjs.SEARCH_FEMALE SET SCORE_POINTS='$newscore_search', TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db) or logError($sql,$db);

						$sql="UPDATE newjs.SEARCH_FEMALE_FULL1 SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db2) or logError($sql,$db2);
					}
					else
					{
						$sql="UPDATE newjs.SEARCH_MALE SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db) or logError($sql,$db);

						$sql="UPDATE newjs.SEARCH_MALE_FULL1 SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db2) or logError($sql,$db2);
					}
					
				}
			}
			
			unset($pid);
			unset($score);
			unset($allot_avail);
			unset($contact_cnt);
			unset($PROFILELENGTH);
			unset($ACCEPTANCE_MADE);
			unset($INITIATE_CNT);
			unset($login_dt);
			unset($total_points);
			unset($freshness_points);
			unset($newscore);
		}
	}
}

$sql="UPDATE incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN  m SET p.ALLOTMENT_AVAIL='N' WHERE p.PROFILEID = m.PROFILEID";
mysql_query($sql,$db) or logError($sql,$db);

$end_time=date("Y-m-d H:i:s");

mail("shiv.narayan@jeevansathi.com,puneet.makkar@jeevansathi.com","Main Admin Pool Allotment","Allotment done\nUpdated : $ucnt\nInserted : $icnt\nStart time : $start_time\nEnd Time : $end_time");

function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
}
?>
