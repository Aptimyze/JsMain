<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");

include("../connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

// calculating date 30 days ago
$i = 0;
$ts = time();
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);
$end_dt = date("Y-m-d H:i:s");

//$db = connect_db();

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
$db2 = connect_737();
$db=connect_db();

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");
}


// query to find the cities which are not covered by any branch excluding Southern states
$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH IN ('','UP25') AND ( VALUE NOT LIKE 'TN%' AND VALUE NOT LIKE 'AP%' AND VALUE NOT LIKE 'KA%' AND VALUE NOT LIKE 'KE%')";
$res_city = mysql_query($sql_city,$db) or die("$sql_city".mysql_error($db));
while($row_city = mysql_fetch_array($res_city))
{
	$crm_cities[] = $row_city['VALUE'];
}
mysql_free_result($res_city);
unset($sql_city);

$sql_pid = "SELECT PROFILEID , ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES FROM test.TEMP_POOL";
//$sql_pid = "SELECT PROFILEID , ENTRY_DT , AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES FROM newjs.JPROFILE";
$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));


while($row_pid = mysql_fetch_array($res_pid))
{
	@mysql_ping($db2);

	$pid = $row_pid['PROFILEID'];

	$entry_dt=$row_pid["ENTRY_DT"];

	$myDbName=getProfileDatabaseConnectionName($pid);

	if(!$myDb[$myDbName])
		$myDb[$myDbName]=$mysql->connect("$myDbName","slave");

	// query to find the first date in an interval of 30 days when the user logged in
	$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY1 WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$start_dt'";
	
	$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or die("$sql_login_cnt".mysql_error($myDb[$myDbName]));
	$row_login_cnt = mysql_fetch_array($res_login_cnt);
	$login_cnt = $row_login_cnt['CNT'];
	
	//Code added by Vibhor for sharding of CONTACTS
	include_once("$_SERVER[DOCUMENT_ROOT]/profile/contacts_functions.php");
	// query to find the count of contacts initiated
	$contactResult_ci=getResultSet("COUNT(*) AS CNT4",$pid,"","","","","","TIME BETWEEN '$start_dt 00:00:00' AND '$end_dt'");
	$INITIATE_CNT=$contactResult_ci[0]["CNT4"];

	// query to find the count of contacts accepted
	$contactResult_ca=getResultSet("COUNT(*) AS CNT","","",$pid,"","'A'","","TIME BETWEEN '$start_dt 00:00:00' AND '$end_dt'");
	$ACCEPTANCE_MADE = $contactResult_ca[0]["CNT"];
	//end
	$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;

	$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);

	$score = calc_user_score($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);

	if (in_array($row_pid['CITY_RES'],$crm_cities))
		$allot_avail = 'Y';
	else
		$allot_avail = 'N';

	@mysql_ping($db);

	$sql_rec_exists = "SELECT COUNT(*) AS CNT FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$pid'";
	$res_rec_exists = mysql_query($sql_rec_exists,$db) or die("$sql_rec_exists".mysql_error($db));
	if ($row_rec_exists = mysql_fetch_array($res_rec_exists))
	{
		if ($row_rec_exists['CNT'] > 0)
		{
			$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET SCORE='$score' WHERE PROFILEID ='$pid'";
			mysql_query($sql_update_pool,$db) or die("$sql_update_pool".mysql_error($db));
		}
		else
		{
			$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL (PROFILEID,SCORE,ALLOTMENT_AVAIL,TIMES_TRIED,SOURCE,ENTRY_DT) VALUES ('$pid','$score','$allot_avail','0','".addslashes($source)."','$entry_dt')";
			mysql_query($sql_insert,$db) or die("$sql_insert".mysql_error($db));
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
}

// query to change the allotment availability in case the profile is alreafy allocated to a crm user 
$sql_alloted = "UPDATE incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN  m SET p.ALLOTMENT_AVAIL='N' WHERE p.PROFILEID = m.PROFILEID";
//mysql_query($sql_alloted,$db) or die("$sql_alloted".mysql_error());

?>
