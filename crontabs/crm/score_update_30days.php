<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");
chdir(dirname(__FILE__));
include("../connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$start_time=date("Y-m-d H:i:s");

// calculating date 30 days ago
$ts = time();
$ts-=24*60*60;
$ts1 = $ts;// - 24*60*60;
$ts-=30*24*60*60;
$start_dt=date("Y-m-d H:i:s",$ts);		//31st day before today. 
$ts+=24*60*60;
$start_dt1=date("Y-m-d",$ts);		// 30th day before today.
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

        $sql="SELECT PROFILEID FROM newjs.LOGIN_HISTORY WHERE LOGIN_DT='$start_dt'";
        $res=$mysql->executeQuery($sql,$myDb[$myDbName]);
        while($row=$mysql->fetchArray($res))
        {
                $LOG_PRO[$row['PROFILEID']]=$row['PROFILEID'];
        }

}

$sql_login = "SELECT PROFILEID FROM JPROFILE  WHERE ENTRY_DT BETWEEN '$start_dt 00:00:00' AND '$start_dt 23:59:59' ";
$res_login = mysql_query($sql_login,$db2) or logError($sql_login,$db2);
while($row=mysql_fetch_array($res_login))
{
        $LOG_PRO[$row['PROFILEID']]=$row['PROFILEID'];
}


if(count($LOG_PRO))
foreach($LOG_PRO as $key=>$val)
{
	@mysql_ping($db2);
	$pid =  $val;
	$sql_pid = "SELECT  ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE, SUBSCRIPTION, PHOTODATE, MTONGUE , SMOKE, DRINK, MANGLIK, BTYPE, DIET, SHOW_HOROSCOPE , COUNTRY_RES  FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";
	$res_pid = mysql_query($sql_pid,$db2) or logError($sql_pid,$db2);
	if ($row_pid = mysql_fetch_array($res_pid))
	{
		$source=$row_pid['SOURCE'];
		$entry_dt=$row_pid["ENTRY_DT"];
		$photo_dt=$row_pid["PHOTODATE"];
		$mtongue = $row_pid["MTONGUE"];

		$myDbName=getProfileDatabaseConnectionName($pid);
		if(!$myDbName)
			$myDb[$myDbName]=$mysql->connect("$myDbName","slave");

		// query to find the first date in an interval of 30 days when the user logged in
		$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT BETWEEN '$start_dt1' AND '$today_dt'";
		$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or logError($sql_login_cnt,$myDb[$myDbName]);
		$i=0;
		while($row_login_cnt = mysql_fetch_array($res_login_cnt))
		{
			$login_cnt = $row_login_cnt['CNT'];
		}

		//Code added by Vibhor for sharding of CONTACTS
	        include_once("$_SERVER[DOCUMENT_ROOT]/profile/contacts_functions.php");
	        // query to find the count of contacts initiated
        	$contactResult_ci=getResultSet("COUNT(*) AS CNT4",$pid,"","","","","","TIME BETWEEN '$start_dt1' AND '$today_dt 23:59:59'","","","","","",1);
	        $INITIATE_CNT=$contactResult_ci[0]["CNT4"];

	        // query to find the count of contacts accepted
	        $contactResult_ca=getResultSet("COUNT(*) AS CNT","","",$pid,"","'A'","","TIME BETWEEN '$start_dt1' AND '$today_dt 23:59:59'","","","","","",1);
	        $ACCEPTANCE_MADE = $contactResult_ca[0]["CNT"];
        	//end

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

		$sql_rec_exists = "SELECT COUNT(*) AS CNT, TOTAL_POINTS FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$pid' GROUP BY PROFILEID";
		$res_rec_exists = mysql_query($sql_rec_exists,$db2) or die(mysql_error()); 
		$row_rec_exists = mysql_fetch_array($res_rec_exists);
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
                        elseif($diff>15 && $diff<46)
                                $freshness_points=150;
                        else
                                $freshness_points=100;
			
			if($row_pid['LAST_LOGIN_DT']<=$start_dt)
                        {
				$total_points_swap=$freshness_points+$newscore;

				if($total_points_swap==450 || $total_points_swap==600)
					$newscore=48-$freshness_points;
				elseif($total_points_swap==400)
					$newscore=47-$freshness_points;
				elseif($total_points_swap==300)
					$newscore=46-$freshness_points;
				elseif($total_points_swap==250)
					$newscore=45-$freshness_points;
				elseif($total_points_swap==100)
					$newscore=44-$freshness_points;
				elseif($total_points_swap==50)
					$newscore=43-$freshness_points;
				
				$total_points_swap_search=$freshness_points+$newscore_search;
                                                                                                                             
                                if($total_points_swap_search==450 || $total_points_swap_search==600)
                                        $newscore_search=48-$freshness_points;
                                elseif($total_points_swap_search==400)
                                        $newscore_search=47-$freshness_points;
                                elseif($total_points_swap_search==300)
                                        $newscore_search=46-$freshness_points;
                                elseif($total_points_swap_search==250)
                                        $newscore_search=45-$freshness_points;
                                elseif($total_points_swap_search==100)
                                        $newscore_search=44-$freshness_points;
                                elseif($total_points_swap_search==50)
                                        $newscore_search=43-$freshness_points;
			}

			$total_points=$newscore+$freshness_points;
			$total_points_search=$newscore_search+$freshness_points;

			if ($row_rec_exists['CNT'] > 0)
			{
				if($total_points>49)
					$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET SCORE='$score',CITY_RES='$city_res' , TOTAL_POINTS='$total_points', MTONGUE='$mtongue' , ATTRIBUTE_SCORE='$attribute_score' , CONV_RATE_ATTRIBUTE='$conv_rate_attribute'  WHERE PROFILEID ='$pid'";
				else
					$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET SCORE='$score',CITY_RES='$city_res', MTONGUE='$mtongue'  , ATTRIBUTE_SCORE='$attribute_score' , CONV_RATE_ATTRIBUTE='$conv_rate_attribute' WHERE PROFILEID ='$pid'";
					
				mysql_query($sql_update_pool,$db) or logError($sql_update_pool,$db);
				$ucnt++;

				//if(mysql_affected_rows())
				{
					if($row_pid['GENDER']=="F")
					{
						$sql="UPDATE newjs.SEARCH_FEMALE SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db) or logError($sql,$db);
					}
					else
					{
						$sql="UPDATE newjs.SEARCH_MALE SET SCORE_POINTS='$newscore_search',TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
						mysql_query($sql,$db) or logError($sql,$db);
					}
				}
			}
			else
			{
				if($total_points>49)
					$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL (PROFILEID,SCORE,ALLOTMENT_AVAIL,TIMES_TRIED,SOURCE,ENTRY_DT,CITY_RES,TOTAL_POINTS,MTONGUE,ATTRIBUTE_SCORE,CONV_RATE_ATTRIBUTE) VALUES ('$pid','$score','$allot_avail','0','".addslashes($source)."','$entry_dt','$city_res','$total_points','$mtongue','$attribute_score','$conv_rate_attribute')";
				else
					$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL (PROFILEID,SCORE,ALLOTMENT_AVAIL,TIMES_TRIED,SOURCE,ENTRY_DT,CITY_RES,MTONGUE,ATTRIBUTE_SCORE,CONV_RATE_ATTRIBUTE) VALUES ('$pid','$score','$allot_avail','0','".addslashes($source)."','$entry_dt','$city_res','$mtongue','$attribute_score','$conv_rate_attribute')";
				mysql_query($sql_insert,$db) or logError($sql_insert,$db);
				$icnt++;

				if($row_pid['GENDER']=="F")
				{
					$sql="UPDATE newjs.SEARCH_FEMALE SET SCORE_POINTS='$newscore_search', TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
					mysql_query($sql,$db) or logError($sql,$db);
				}
				else
				{
					$sql="UPDATE newjs.SEARCH_MALE SET SCORE_POINTS='$newscore_search', TOTAL_POINTS='$total_points_search',FRESHNESS_POINTS='$freshness_points',PROFILE_SCORE='$score_search' WHERE PROFILEID='$pid'";
					mysql_query($sql,$db) or logError($sql,$db);
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

$end_time=date("Y-m-d H:i:s");

mail("vibhor.garg@jeevansathi.com,puneet.makkar@jeevansathi.com","Main Admin Pool Allotment - 30 days old","Allotment done\nUpdated : $ucnt\nInserted : $icnt\nStart time : $start_time\nEnd Time : $end_time");

function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
}
?>
