<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");

include("../connect.inc");
include_once("comfunc.inc");

// calculating date 30 days ago
$i = 0;
$ts = time();
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);
$end_dt = date("Y-m-d H:i:s");

$db = connect_db();

//$sql_pid = "SELECT PROFILEID , ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES FROM test.TEMP_POOL";
//$sql_pid = "SELECT PROFILEID , ENTRY_DT , AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES FROM newjs.JPROFILE";
$sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE SCORE IN ('0','50','100','150','200','250','300','350','400','450','500','550','600')";
$res_pid1 = mysql_query($sql,$db) or die("$sql_pid".mysql_error($db));

$db2=connect_737();

while($row_pid1 = mysql_fetch_array($res_pid1))
{
	@mysql_ping($db2);

	$pid = $row_pid1['PROFILEID'];

	$sql="SELECT PROFILEID , ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	$res_pid=mysql_query($sql) or die("$sql".mysql_error());
	$row_pid = mysql_fetch_array($res_pid);

	$entry_dt=$row_pid["ENTRY_DT"];

	// query to find the first date in an interval of 30 days when the user logged in
	$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$start_dt'";
	$res_login_cnt = mysql_query($sql_login_cnt,$db2) or die("$sql_login_cnt".mysql_error($db2));
	$row_login_cnt = mysql_fetch_array($res_login_cnt);
	$login_cnt = $row_login_cnt['CNT'];

	// query to find the count of contacts initiated
	$sql_init_cnt ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TIME BETWEEN '$start_dt 00:00:00' AND '$end_dt'";
	$res4 = mysql_query($sql_init_cnt,$db2) or die("$sql_init_cnt".mysql_error($db2));
	$row4 = mysql_fetch_array($res4);
	$INITIATE_CNT= $row4['CNT4'];

	// query to find the count of contacts accepted
	$sql_accpt_cnt="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER='$pid' and TYPE='A' AND TIME BETWEEN '$start_dt 00:00:00' AND '$end_dt'";
	$result=mysql_query($sql_accpt_cnt,$db2) or die("$sql_accpt_cnt".mysql_error($db2));
	$myrow=mysql_fetch_array($result);
        $ACCEPTANCE_MADE = $myrow["CNT"];
	$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;

	$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);

	$score = calc_user_score($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);

	@mysql_ping($db);

	$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET SCORE='$score' WHERE PROFILEID ='$pid'";
	mysql_query($sql_update_pool,$db) or die("$sql_update_pool".mysql_error($db));
	
	unset($pid);
	unset($score);
	unset($allot_avail);
	unset($contact_cnt);
	unset($PROFILELENGTH);
	unset($ACCEPTANCE_MADE);
	unset($INITIATE_CNT);
	unset($login_dt);
}

// query to change the allotment availability in case the profile is alreafy allocated to a crm user 
$sql_alloted = "UPDATE incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN  m SET p.ALLOTMENT_AVAIL='N' WHERE p.PROFILEID = m.PROFILEID";
//mysql_query($sql_alloted,$db) or die("$sql_alloted".mysql_error());

?>
