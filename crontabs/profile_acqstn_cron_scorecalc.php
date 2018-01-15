<?php 
  $curFilePath = dirname(__FILE__)."/"; 
  include_once("/usr/local/scripts/DocRoot.php");

/**************************************************************************************************************************
*       FILE NAME        : profile_acqstn_cron_scorecalc.php
*       CREATED BY       : Shobha Kumari
*       CREATED ON       : 09.02.2006
*       FILE DESCRIPTION : This file calculates score for a particular profile depending on different criteria viz
			   geography , age ,  community for the profiles registered 3 days ago.
*       FILES INCLUDED   :connect.inc
*       LAST MODIFIED BY :
**************************************************************************************************************************/
ini_set("max_execution_time","0");
chdir(dirname(__FILE__));
include("connect.inc");

$db2 = connect_737();

$ts=time();
$ts-=3*24*60*60;
$start_date = date("Y-m-d",$ts) ." 00:00:00";
$end_date = date("Y-m-d",$ts) ." 23:59:59";

$sql_pid = "SELECT PROFILEID , SOURCE , AGE , GENDER , COUNTRY_RES , CITY_RES , MTONGUE, ENTRY_DT FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED IN ('Y','H','D') AND INCOMPLETE='N'";
$res_pid = mysql_query($sql_pid,$db2) or die("$sql_pid".mysql_error($db2));

$db = connect_db();
while($row_pid = mysql_fetch_array($res_pid))
{
        $pid = $row_pid['PROFILEID'];
	$score = calc_user_score_here($row_pid['CITY_RES'],$row_pid['COUNTRY_RES'],$row_pid['AGE'],$row_pid['GENDER'],$row_pid['MTONGUE']);
	$source = addslashes(stripslashes($row_pid['SOURCE']));
	$sql_insert = "INSERT IGNORE INTO MIS.PROFILE_ACQUISTION_SCORE (PROFILEID,SCORE,SOURCE,ENTRY_DT) VALUES ('$pid','$score','$source','$row_pid[ENTRY_DT]')";
        mysql_query($sql_insert,$db) or die("$sql_insert".mysql_error($db));
}
mysql_close($db);

mail("manoj.rana@naukri.com,vibhor.garg@jeevansathi.com","score data capture","done");

function calc_user_score_here($country,$city,$age,$gender,$mtongue)
{
        $country_arr = array('126','128','22','6','10','35','41','53','54','56','60','63','66','87','96','99','114','121','122','125','132','7');
        $metros_arr = array('GU01','KA02','PH00','TN02','DE00','HA02','UP12','HA03','AP03','WB05','MH04','UP25','MH08');
        $northweststate_arr = array('CH','GU','HA','MP','MH','PU','RA','UP','UT');
                                                                                                                            
        $comm1_arr = array('7','10','12','16','20','27','33');
        $comm2_arr=array('3','6','17','28','31');
        $comm3_arr = array('13','19','25','30');
                                                                                                                            
        $user_score = 0;
                                                                                                                            
        if (in_array($country,$country_arr))
        {
                $user_score += 100;
        }
        else
        {
                if ($country=='51')
                {
                        if (in_array($city_res,$metros_arr))
                        {
                                $user_score += 75;
                        }
                        elseif (in_array($city_res,$northweststate_arr))
                        {
                                $user_score += 50;
                        }
                        else
                                $user_score += 25;
                }
                elseif ($country =='88')
			$user_score += 25;
		else
                        $user_score += 50;
        }
        if ($gender == 'F')
        {
		if ($age >= 27)
                {
                        $user_score += 200;
                }
                elseif ($age >= 24 && $age <= 26)
                {
                        $user_score += 150;
                }
                elseif ($age >= 22 && $age <= 23)
                {
                        $user_score += 100;
                }
                elseif ($age <= 21)
                {
                        $user_score += 50;
                }
        }
        else
        {
                if ($age >= 29)
                {
                        $user_score += 200;
                }
                elseif ($age >= 25 && $age <= 28)
                {
                        $user_score += 150;
                }
                elseif ($age >= 23 && $age <= 24)
                {
                        $user_score += 100;
                }
                elseif ($age < 23)
                {
                        $user_score += 50;
                }
        }
	if ($mtongue)
        {
                if (in_array($mtongue,$comm1_arr))
                        $user_score += 100;
                elseif (in_array($mtongue,$comm2_arr))
                        $user_score += 75;
                elseif (in_array($mtongue,$comm3_arr))
                        $user_score += 50;
                else
                        $user_score += 25;
        }
                                                                                                                            
        unset($metros_arr);
        unset($northweststate_arr);
        unset($comm1_arr);
        unset($comm2_arr);
        unset($comm3_arr);
        return $user_score;
}
?>
