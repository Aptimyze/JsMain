<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set('max_execution_time','0');
chdir(dirname(__FILE__));
include("connect.inc");
include("crm/comfunc.inc");//Need to uncomment for 205
//include("comfunc1.inc");//Need to comment for 205

$today=date("Y-m-d");
$ts = time();
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
//$db2 = connect_slave();
$LOG_PRO=array();

$db=connect_db();

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");

}


$timeval = time();
$timeval1 = $timeval;

$sql="SELECT LAST_TIME FROM SWAP_LOG ORDER BY ID DESC LIMIT 1";
$res=mysql_query($sql,$db) or die("0".mysql_error1($db));
$row=mysql_fetch_array($res);
$last_time=$row['LAST_TIME'];
$timeval = date("YmdH0000",$last_time);

$time_5_month_old=mktime(0, 0, 0, date("m")-5, date("d"), date("Y"));
$date_5_month_old = date("Y-m-d",$time_5_month_old);

$sql="truncate table SWAP";
mysql_query($sql,$db) or die("1 ".mysql_error1($db));

$sql="alter table SWAP disable keys";
mysql_query($sql,$db) or die("2 ".mysql_error1($db));



//$db_303=mysql_connect('10.208.66.241','user','CLDLRTa9');
$db_303=mysql_connect(MysqlDbConstants::$misSlave['HOST'],'user','CLDLRTa9');

$sql="select PROFILEID FROM newjs.JPROFILE WHERE LAST_LOGIN_DT >='$date_5_month_old' ";
$result=mysql_query($sql,$db_303) or die("2.5 ".mysql_error1($db_303));

mysql_close($db_303);
$db=connect_db();

while($myrow=mysql_fetch_array($result))
{
	$sql = "INSERT INTO newjs.SWAP SELECT PROFILEID , CASTE , MANGLIK , MTONGUE , MSTATUS , OCCUPATION , COUNTRY_RES , CITY_RES , HEIGHT , EDU_LEVEL , MOD_DT, DRINK , SMOKE , HAVECHILD , RES_STATUS , BTYPE , COMPLEXION , DIET , HANDICAPPED , AGE , HAVEPHOTO, LAST_LOGIN_DT, ENTRY_DT, INCOME, PRIVACY, SORT_DT, if(SUBSCRIPTION like '%D%' AND SUBSCRIPTION NOT LIKE '%S','D',''),COUNTRY_BIRTH, EDU_LEVEL_NEW,'',if(CHAR_LENGTH(PHONE_MOB)>0,'Y','N'),GET_SMS,if(SUBSCRIPTION like '%F%','Y','N'),RELATION,GENDER,ACTIVATED,CONCAT(CASTE,'-',MTONGUE),'','','','',PHOTODATE,PHOTO_DISPLAY,'',RELIGION,'' FROM JPROFILE WHERE PROFILEID='$myrow[PROFILEID]'";
	mysql_query($sql,$db) or die("3 ".mysql_error1($db,$sql));
}


$sql="select PROFILEID from SWAP where (ACTIVATED <>'Y' or PRIVACY='C') and GENDER='M'";
$result=mysql_query($sql,$db) or die("4 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result))
{
	$sql = "delete from SEARCH_MALE where PROFILEID='" . $myrow["PROFILEID"] . "'";
	mysql_query($sql,$db) or die("5 ".mysql_error1($db));
}

mysql_free_result($result);

$sql="select PROFILEID from SWAP where (ACTIVATED <>'Y' or PRIVACY='C') and GENDER='F'";
$result=mysql_query($sql,$db) or die("6 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result))
{
	$sql = "delete from SEARCH_FEMALE where PROFILEID='" . $myrow["PROFILEID"] . "'";
	mysql_query($sql,$db) or die("7 ".mysql_error1($db));
}

mysql_free_result($result);

$sql="delete from SWAP where (ACTIVATED <>'Y' or PRIVACY='C' or LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 5 MONTH))";
mysql_query($sql,$db) or die("8 ".mysql_error1($db));
$sql_points="select GENDER,HAVEPHOTO,SUBSCRIPTION,E_RISHTA,LAST_LOGIN_DT,PROFILEID,ENTRY_DT,PHOTODATE,INCOME FROM SWAP";
$result_points=mysql_query($sql_points,$db) or die("9 ".mysql_error1($db));
$today=date("Y-m-d");

$sql="alter table SWAP enable keys";
mysql_query($sql,$db) or die("10 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result_points))
{
	/*$search_points=0;
	if(strstr($myrow['SUBSCRIPTION'],'D') ||  strstr($myrow['E_RISHTA'],'F'))
		$search_points=1000;
	if($myrow['HAVEPHOTO']=='Y')
		$search_points+=500;
	$diff=DayDiff($myrow['LAST_LOGIN_DT'],$today);
	if($diff<=7)
		$search_points+=2000;                 
	elseif($diff>=400)
		$diff=400;
        $search_points+=(400-$diff);*/
	
	$pid=$myrow['PROFILEID'];
	
	if($myrow['GENDER']=='M')
		$tablename='SEARCH_MALE';	
	else	
		$tablename='SEARCH_FEMALE';	
	
	$sql_total_points="select TOTAL_POINTS FROM $tablename where PROFILEID='$pid'";
	$result_total_points=mysql_query($sql_total_points,$db) or die("finding total_points file swap_jprofile.php ".mysql_error1($db));
	$myrow_total_points=mysql_fetch_array($result_total_points);
	
	$entry_dt=$myrow["ENTRY_DT"];
	$photo_dt=$myrow["PHOTODATE"];
	$income=$myrow["INCOME"];

	if($myrow['HAVEPHOTO']=='Y')	
		$fresh=DayDiff(substr($photo_dt,0,10),$today);
	else
		$fresh=DayDiff(substr($entry_dt,1,10),$today);
													     
	if($fresh<16)
		$freshness_points=300;
	elseif($fresh>15 && $fresh<46)
		$freshness_points=150;
	else
		$freshness_points=100;
	
	if($income==15)
		$income=1;
	elseif($income<=7)
		$income++;
	elseif($income>=8 && $income<=14)
		$income+=4;
	elseif($income>=16 && $income<=18)
		$income-=7;

	if($myrow_total_points['TOTAL_POINTS']!='49')
	{													     
		$score = update_score($pid);

		if($score<=150)
			$score_points=-50;
		elseif($score>150 && $score<326)
			$score_points=150;
		else
			$score_points=300;

		if(DayDiff($myrow['LAST_LOGIN_DT'],$today)>30)
		{
			$total_points_swap=$score_points+$freshness_points;
			if($total_points_swap==450 || $total_points_swap==600)
				$score_points=48-$freshness_points;
			elseif($total_points_swap==400)
				$score_points=47-$freshness_points;
			elseif($total_points_swap==300)
				$score_points=46-$freshness_points;
			elseif($total_points_swap==250)
				$score_points=45-$freshness_points;
			elseif($total_points_swap==100)
				$score_points=44-$freshness_points;
			elseif($total_points_swap==50)
				$score_points=43-$freshness_points;
		}

		$total_points=$score_points+$freshness_points;
		$sql_update="UPDATE SWAP SET SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='$total_points',PROFILE_SCORE='$score',INCOME_SORTBY='$income'  where PROFILEID='$pid'";
		$result_update=mysql_query($sql_update,$db) or die("updating total_points error ".mysql_error1($db));
    	}
	else
	{	$score_points=49-$freshness_points;
		$sql_update="UPDATE SWAP SET SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='49',PROFILE_SCORE='$score',INCOME_SORTBY='$income'  where PROFILEID='$pid'";
		$result_update=mysql_query($sql_update,$db) or die("updating total_points error ".mysql_error1($db));
	}
	
	//$sql_update="UPDATE SWAP SET SEARCH_POINTS='$search_points' where PROFILEID='$pid'";
	//$result_update=mysql_query($sql_update) or die("updating search_points earlier error".mysql_error1($db));

}

mysql_free_result($result_points);



$sql="select PROFILEID from SWAP where GENDER='M'";

$result=mysql_query($sql,$db) or die("12 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result))
{
	$sql = "REPLACE INTO SEARCH_MALE SELECT PROFILEID , CASTE , MANGLIK , MTONGUE , MSTATUS , OCCUPATION , COUNTRY_RES , CITY_RES , HEIGHT , EDU_LEVEL , MOD_DT, DRINK , SMOKE , HAVECHILD , RES_STATUS , BTYPE , COMPLEXION , DIET , HANDICAPPED , AGE , HAVEPHOTO, LAST_LOGIN_DT, ENTRY_DT, INCOME, PRIVACY, SORT_DT, SUBSCRIPTION, COUNTRY_BIRTH, EDU_LEVEL_NEW, SEARCH_POINTS, HAVE_PHONE_MOB, GET_SMS, E_RISHTA, RELATION ,CASTE_MTONGUE,SCORE_POINTS,FRESHNESS_POINTS,TOTAL_POINTS,PROFILE_SCORE,PHOTODATE,PHOTO_DISPLAY,NTIMES,RELIGION,INCOME_SORTBY from SWAP where PROFILEID='" . $myrow["PROFILEID"] . "'";
	mysql_query($sql,$db) or die("13 ".mysql_error1($db));
}

mysql_free_result($result);

$sql="select PROFILEID from SWAP where GENDER='F'";

$result=mysql_query($sql,$db) or die("14 ".mysql_error1($db));

while($myrow=mysql_fetch_array($result))
{

	$sql = "REPLACE INTO SEARCH_FEMALE SELECT PROFILEID , CASTE , MANGLIK , MTONGUE , MSTATUS , OCCUPATION , COUNTRY_RES , CITY_RES , HEIGHT , EDU_LEVEL , MOD_DT, DRINK , SMOKE , HAVECHILD , RES_STATUS , BTYPE , COMPLEXION , DIET , HANDICAPPED , AGE , HAVEPHOTO, LAST_LOGIN_DT, ENTRY_DT, INCOME, PRIVACY, SORT_DT, SUBSCRIPTION, COUNTRY_BIRTH, EDU_LEVEL_NEW, SEARCH_POINTS, HAVE_PHONE_MOB, GET_SMS, E_RISHTA, RELATION,CASTE_MTONGUE,SCORE_POINTS,FRESHNESS_POINTS,TOTAL_POINTS,PROFILE_SCORE,PHOTODATE,PHOTO_DISPLAY, NTIMES,RELIGION,INCOME_SORTBY from SWAP where PROFILEID='" . $myrow["PROFILEID"] . "'";
	mysql_query($sql,$db) or die("15 ".mysql_error1($db));

}

mysql_free_result($result);

$sql="truncate table SWAP";

mysql_query($sql,$db) or die("16 ".mysql_error1($db));

//$sql="INSERT INTO SWAP_LOG (LAST_TIME) VALUES('$timeval1')";
//mysql_query($sql,$db) or die("17".mysql_error1($db));

echo 'script completed';

mail("puneet.makkar@jeevansathi.com,puneetmakkar@gmail.com","swap jprofile one time 5 months ran successfully","Allotment done\nUpdated : $ucnt\nInserted : $icnt\nStart time : $start_time\nEnd Time : $end_time");


function mysql_error1($db,$sql='')
{
	mail("puneet.makkar@jeevansathi.com,puneetmakkar@gmail.com","Jeevansathi Error in swapping",$sql.mysql_error($db));
}

function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', strtotime($StopDate)) - date('U', strtotime($StartDate))) / 86400; //seconds a day
}

function update_score($pid)
{
	global $start_dt;
	global $myDb;
	global $db;
	global $entry_dt;
	global $mysql;

	$sql_pid = "SELECT  AGE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE , HAVEPHOTO,GENDER FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";
												     
	$res_pid = mysql_query($sql_pid,$db) or logError($sql_pid);
												     
	if ($row_pid = mysql_fetch_array($res_pid))
	{
		$source=$row_pid['SOURCE'];
												     
		// query to find the first date in an interval of 30 days when the user logged in
		$myDbName=getProfileDatabaseConnectionName($pid);

		if(!$myDb[$myDbName])
                                $myDb[$myDbName]=$mysql->connect("$myDbName","slave");

		$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$start_dt'";
		$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or logError($sql_login_cnt);
												     
		while($row_login_cnt = mysql_fetch_array($res_login_cnt))
		{
			$login_cnt = $row_login_cnt['CNT'];
		}
												     
		// query to find the count of contacts initiated
		$sql_init_cnt ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TIME >= '$start_dt'";
		$res4 = mysql_query($sql_init_cnt,$db) or logError($sql_init_cnt);
												     
		$row4 = mysql_fetch_array($res4);
												     
		$INITIATE_CNT= $row4['CNT4'];
												     
		// query to find the count of contacts accepted
		$sql_accpt_cnt="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER='$pid' and TYPE='A' AND TIME >= '$start_dt'";
		$result=mysql_query($sql_accpt_cnt,$db) or logError($sql_accpt_cnt);
												     
		$myrow=mysql_fetch_array($result);
												     
		$ACCEPTANCE_MADE = $myrow["CNT"];
		
		$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;
		$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);
		
	$score = calc_user_score_search($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
		return $score;
	}
}
?>
