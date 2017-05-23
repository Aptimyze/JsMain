<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");

include("../connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

/*
$db2 = connect_737();
*/
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
//$db2 = connect_slave();
$db = connect_db();
$LOG_PRO=array();

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");
	

}
// query to find the cities which are not covered by any branch excluding Southern states
$sql_city = "SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH IN ('','UP25') AND ( VALUE NOT LIKE 'TN%' AND VALUE NOT LIKE 'AP%' AND VALUE NOT LIKE 'KA%' AND VALUE NOT LIKE 'KE%')";
$res_city = mysql_query($sql_city,$db) or die("$sql_city".mysql_error());
while($row_city = mysql_fetch_array($res_city))
{
	$crm_cities[] = $row_city['VALUE'];
}
mysql_free_result($res_city);
unset($sql_city);


// calculating date 30 days ago
$i = 0;
$ts = time();
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);
$end_dt = date("Y-m-d H:i:s");


//$sql_pid = "SELECT PROFILEID , AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES FROM test.TEMP_POOL";
$sql_map = "SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL";
$res_map = mysql_query($sql_map,$db) or die("$sql_map".mysql_error($db));
//mysql_close($db2);

while($row_map = mysql_fetch_array($res_map))
{
	$pid = $row_map['PROFILEID'];

	$sql_pid="SELECT PROFILEID , AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
	$row_pid=mysql_fetch_array($res_pid);
	mysql_free_result($res_pid);

	$source=$row_pid['SOURCE'];

	$myDbName=getProfileDatabaseConnectionName($pid);


	if(!$myDb[$myDbName])
		$myDb[$myDbName]=$mysql->connect("$myDbName","slave");
	// query to find the first date in an interval of 30 days when the user logged in
	$sql_login_cnt = "SELECT LOGIN_DT FROM newjs.LOGIN_HISTORY1 WHERE PROFILEID = '$pid' ORDER BY LOGIN_DT DESC LIMIT 2 ";
	$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or die(mysql_error($myDb[$myDbName]));
	$i=0;
	while($row_login_cnt = mysql_fetch_array($res_login_cnt))
	{
		if($i>0)
			$login_dt = $row_login_cnt['LOGIN_DT'];
		$i++;
	}
	unset($i);
	
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

	$score = calc_user_score($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$row_pid['LAST_LOGIN_DT'],$login_dt,$contact_cnt);

	if (in_array($row_pid['CITY_RES'],$crm_cities))
		$allot_avail = 'Y';
	else
		$allot_avail = 'N';

        //$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL (PROFILEID,SCORE,ALLOTMENT_AVAIL,TIMES_TRIED) VALUES ('$pid','$score','$allot_avail','0')";

	@mysql_ping($db);
        $sql_insert = "UPDATE incentive.MAIN_ADMIN_POOL SET SCORE='$score',ALLOTMENT_AVAIL='$allot_avail',SOURCE='".addslashes($source)."' WHERE PROFILEID='$pid'";
        mysql_query($sql_insert,$db) or die("$sql_insert".mysql_error($db));
	
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
mysql_query($sql_alloted,$db) or die("$sql_alloted".mysql_error());

?>
