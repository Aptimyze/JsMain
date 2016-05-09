<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));

// ONE TIME SCRIPT

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

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");
}

$sql_login = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS";
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

		$myDbName=getProfileDatabaseConnectionName($pid);

		if(!$myDb[$myDbName])
                                $myDb[$myDbName]=$mysql->connect("$myDbName","slave");

		// query to find the first date in an interval of 30 days when the user logged in
		$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT BETWEEN '$start_dt' AND '$today_dt'";
		$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or logError($sql_login_cnt,$myDb[$myDbName]);
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

		$static_score=0;	//static_score is global in function calc_user_score so its value is calculated and available after function's execution is over.
		
		$score = calc_user_score($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);

		/*$sql_group=" select GROUPNAME from MIS.SOURCE WHERE SourceID='$source' ";
		$res_group=mysql_query($sql_group,$db2) or logError($sql_group,$db2);
		$row_group = mysql_fetch_array($res_group);*/

		/*$sql_paid=" SELECT PROFILEID FROM billing.PURCHASES WHERE PROFILEID='$pid' AND STATUS='DONE'";
		$res_paid=mysql_query($sql_paid,$db2) or logError($sql_paid,$db2);
		if(mysql_num_rows($res_paid)>0)
			$paid='Y';
		else*/
		$paid='N';
	
		$n=0;
		$attribute_score=0;
		
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
	
		@mysql_ping($db);

		$icnt++;
		
		$sql=" UPDATE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS SET ATTRIBUTE_SCORE = '$attribute_score' where PROFILEID='$pid'";
                mysql_query($sql,$db) or logError($sql,$db);
		
		if($row_pid['COUNTRY_RES']!=51)
                        $sql=" UPDATE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS SET  CONV_RATE = IF(SCORE>550,$conv_rate[600], IF(SCORE>500,$conv_rate[550], IF(SCORE>450,$conv_rate[500], IF(SCORE>400,$conv_rate[450], IF(SCORE>350,$conv_rate[400], IF(SCORE>300,$conv_rate[350], IF(SCORE>250,$conv_rate[300],IF(SCORE>200,$conv_rate[250],$conv_rate[200]  )))))))) , CONV_RATE_ATTRIBUTE = IF(ATTRIBUTE_SCORE>550,$conv_rate_nri[600], IF(ATTRIBUTE_SCORE>500,$conv_rate_nri[550], IF(ATTRIBUTE_SCORE>450,$conv_rate_nri[500], IF(ATTRIBUTE_SCORE>400,$conv_rate_nri[450], IF(ATTRIBUTE_SCORE>350,$conv_rate_nri[400], IF(ATTRIBUTE_SCORE>300,$conv_rate_nri[350],IF(ATTRIBUTE_SCORE>250,$conv_rate_nri[300],IF(ATTRIBUTE_SCORE>200,$conv_rate_nri[250],$conv_rate_nri[200]  )))))))) where PROFILEID='$pid'";
                else
                        $sql=" UPDATE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS SET CONV_RATE = IF(SCORE>550,$conv_rate[600], IF(SCORE>500,$conv_rate[550], IF(SCORE>450,$conv_rate[500], IF(SCORE>400,$conv_rate[450], IF(SCORE>350,$conv_rate[400], IF(SCORE>300,$conv_rate[350], IF(SCORE>250,$conv_rate[300],IF(SCORE>200,$conv_rate[250],$conv_rate[200]  )))))))) , CONV_RATE_ATTRIBUTE = IF(ATTRIBUTE_SCORE>550,$conv_rate_i[600], IF(ATTRIBUTE_SCORE>500,$conv_rate_i[550], IF(ATTRIBUTE_SCORE>450,$conv_rate_i[500], IF(ATTRIBUTE_SCORE>400,$conv_rate_i[450], IF(ATTRIBUTE_SCORE>350,$conv_rate_i[400], IF(ATTRIBUTE_SCORE>300,$conv_rate_i[350],IF(ATTRIBUTE_SCORE>250,$conv_rate_i[300],IF(ATTRIBUTE_SCORE>200,$conv_rate_i[250],$conv_rate_i[200]  )))))))) where PROFILEID='$pid'";
                mysql_query($sql,$db) or logError($sql,$db);
	}
		
	unset($pid);
	unset($score);
	unset($contact_cnt);
	unset($PROFILELENGTH);
	unset($ACCEPTANCE_MADE);
	unset($INITIATE_CNT);
	unset($login_dt);
}


mail("puneetmakkar@jeevansathi.com","Main Admin Pool calculate score into system 20 days one time","Inserted : $icnt\nStart time : $start_time\nEnd Time : $end_time");

?>
