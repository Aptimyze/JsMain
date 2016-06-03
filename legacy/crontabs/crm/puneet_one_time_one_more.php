<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
// this script calculates scores of users on 20th day into our system
// it also changes paid staus of users of table MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS if anyone paid after 20 days

ini_set('max_execution_time','0');

include("../connect.inc");
include_once("comfunc.inc");

$start_time=date("Y-m-d H:i:s");

$ts = time();
$ts1 = $ts - 24*60*60;
$ts_ealier_20_days=$ts-20*24*60*60;
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);
$end_dt = date("Y-m-d H:i:s");
$earlier_20_days_dt=date("Y-m-d",$ts_ealier_20_days);

// today's date
$today_dt=date('Y-m-d',$ts1);

$icnt=0;

//$db2 = connect_slave();
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$mysql=new Mysql;
$db2 = connect_slave();
$db = connect_db();
//$db=connect_db();
$LOG_PRO=array();

//$sql_login = "(SELECT PROFILEID FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '2007-10-01 00:00:00' AND '2008-01-08 23:59:59')";
$sql_login = "( SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL )";
$res_login = mysql_query($sql_login,$db2) or logError($sql_login,$db2);


/*$conv_rate[50]=0.2;
$conv_rate[100]=0.2;
$conv_rate[150]=0.2;
$conv_rate[200]=0.2;
$conv_rate[250]=0.2;
$conv_rate[300]=0.2;
$conv_rate[350]=2.5;
$conv_rate[400]=7.5;
$conv_rate[450]=15;
$conv_rate[500]=25;
$conv_rate[550]=35;
$conv_rate[600]=40;*/

/*
$conv_rate[50]=0.5;
$conv_rate[100]=0.5;
$conv_rate[150]=0.5;
$conv_rate[200]=0.5;
$conv_rate[250]=0.5;
$conv_rate[300]=0.5;
$conv_rate[350]=4.25;
$conv_rate[400]=8.65;
$conv_rate[450]=16.5;
$conv_rate[500]=30;
$conv_rate[550]=40;
$conv_rate[600]=60;*/

/*$conv_rate[50]=1;
$conv_rate[100]=1;
$conv_rate[150]=1;
$conv_rate[200]=1;
$conv_rate[250]=1;
$conv_rate[300]=1;
$conv_rate[350]=6;
$conv_rate[400]=10;
$conv_rate[450]=20;
$conv_rate[500]=35;
$conv_rate[550]=45;
$conv_rate[600]=65;*/

/*$conv_rate[50]=1;
$conv_rate[100]=1;
$conv_rate[150]=1;
$conv_rate[200]=1;
$conv_rate[250]=1;
$conv_rate[300]=1;
$conv_rate[350]=6.5;
$conv_rate[400]=15.4;
$conv_rate[450]=26.5;
$conv_rate[500]=41;
$conv_rate[550]=52.5;
$conv_rate[600]=66.5;*/

$conv_rate[50]=0;
$conv_rate[100]=0;
$conv_rate[150]=0;
$conv_rate[200]=0;
$conv_rate[250]=2;
$conv_rate[300]=6;
$conv_rate[350]=12.5;
$conv_rate[400]=19;
$conv_rate[450]=29;
$conv_rate[500]=42;
$conv_rate[550]=53;
$conv_rate[600]=65;

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


while($row_login = mysql_fetch_array($res_login))
{
	@mysql_ping($db2);
	$pid =  $row_login['PROFILEID'];
	
	$sql_pid = "SELECT  ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , SOURCE, COUNTRY_RES, SMOKE, DRINK, MANGLIK, BTYPE, DIET, SHOW_HOROSCOPE FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";
	$res_pid = mysql_query($sql_pid,$db2) or logError($sql_pid,$db2);
	if ($row_pid = mysql_fetch_array($res_pid))
	{
		$entry_dt=$row_pid["ENTRY_DT"];
		$source=$row_pid['SOURCE'];

		$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);

		$static_score=0;	//static_score is global in function calc_user_score so its value is calculated and available after function's execution is over.
		
		$score = calc_user_score($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);

		$sql_group=" select GROUPNAME from MIS.SOURCE WHERE SourceID='$source' ";
		$res_group=mysql_query($sql_group,$db2) or logError($sql_group,$db2);
		$row_group = mysql_fetch_array($res_group);

		/*$sql_paid=" SELECT PROFILEID FROM billing.PURCHASES WHERE PROFILEID='$pid' AND STATUS='DONE'";
		$res_paid=mysql_query($sql_paid,$db2) or logError($sql_paid,$db2);
		if(mysql_num_rows($res_paid)>0)
			$paid='Y';
		else*/
		$paid='N';

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
		

		@mysql_ping($db);

		//$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS (PROFILEID,SCORE,ENTRY_DT,SOURCE,GROUPNAME,PAID_ALLTIME,COUNTRY_RES,ATTRIBUTE_SCORE,CONV_RATE_ATTRIBUTE) VALUES ('$pid','0','$entry_dt','".addslashes($source)."','".addslashes($row_group['GROUPNAME'])."','$paid','$row_pid[COUNTRY_RES]','$attribute_score','$conv_rate_attribute')";

		$sql_insert = "UPDATE incentive.MAIN_ADMIN_POOL SET ATTRIBUTE_SCORE='$attribute_score' , CONV_RATE_ATTRIBUTE='$conv_rate_attribute'  WHERE PROFILEID ='$pid'";
		mysql_query($sql_insert,$db) or logError($sql_insert,$db);
		$icnt++;
	}
		
	unset($pid);
	unset($score);
	unset($contact_cnt);
	unset($PROFILELENGTH);
	unset($ACCEPTANCE_MADE);
	unset($INITIATE_CNT);
	unset($login_dt);
}


//$sql=" UPDATE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS, billing.PURCHASES SET incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS.PAID_ALLTIME='Y' WHERE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS.PROFILEID = billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS='DONE'";
//mysql_query($sql,$db) or logError($sql,$db);

//$sql=" UPDATE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS as A, incentive.MAIN_ADMIN_POOL AS B SET A.CURRENT_SCORE=B.SCORE WHERE A.PROFILEID=B.PROFILEID";
//mysql_query($sql,$db) or logError($sql,$db);

echo 'script completed one time main admin pool';

$end_time=date("Y-m-d H:i:s");

mail("puneet.makkar@jeevansathi.com","Main Admin Pool one time by puneet completed","Allotment done\nUpdated : $ucnt\nInserted : $icnt\nStart time : $start_time\nEnd Time : $end_time");


?>
