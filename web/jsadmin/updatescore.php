<?php
$today=date("Y-m-d");
$ts = time();
$ts-=30*24*60*60;
$start_dt=date("Y-m-d",$ts);
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

function update_score($pid)
{
        global $start_dt;

	
        $sql_pid = "SELECT  AGE , ENTRY_DT,YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , DATE(LAST_LOGIN_DT) LAST_LOGIN_DT , CITY_RES, SOURCE , HAVEPHOTO,GENDER FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";

        $res_pid = mysql_query_decide($sql_pid) or logError($sql_pid);

        if ($row_pid = mysql_fetch_array($res_pid))
        {
                $source=$row_pid['SOURCE'];
		$entry_dt=$row_pid['ENTRY_DT'];

		$mysqlObj=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
                // query to find the first date in an interval of 30 days when the user logged in
                $sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$start_dt'";
		$res_login_cnt=$mysqlObj->executeQuery($sql_login_cnt,$myDb) or die(mysql_error_js($myDb));

                while($row_login_cnt =$mysqlObj->fetchArray($res_login_cnt))
                {
                        $login_cnt = $row_login_cnt['CNT'];
                }
		connect_db();
                // query to find the count of contacts initiated
                $sql_init_cnt ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TIME >= '$start_dt'";
                $res4 = $mysqlObj->executeQuery($sql_init_cnt,$myDb) or die(mysql_error_js($myDb));

                $row4 =$mysqlObj->fetchArray($res4);

                $INITIATE_CNT= $row4['CNT4'];
		
		// query to find the count of contacts accepted
                $sql_accpt_cnt="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER='$pid' and TYPE='A' AND TIME >= '$start_dt'";
                $result=$mysqlObj->executeQuery($sql_accpt_cnt,$myDb) or die(mysql_error_js($myDb));
                                                  
                $myrow=$mysqlObj->fetchArray($result);

                $ACCEPTANCE_MADE = $myrow["CNT"];

                $contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;

                $PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);

                $score = calc_user_score_search($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
                return $score;
        }
        //return 0;
}
?>
