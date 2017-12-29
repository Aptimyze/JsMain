<?php

/*********************************************************************************************
* FILE NAME   	: DailyOutboundCallsReaderCronTask.php 
* DESCRIPTION 	: Copies Outbound Details from Thread_report_table
* MADE BY     	: MANOJ RANA
*********************************************************************************************/

include_once("MysqlDbConstants.class.php");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],
                           MysqlDbConstants::$dialer['USER'],
                           MysqlDbConstants::$dialer['PASS']) 
        		   or die("Unable to connect to dialer server");

$db_slave111 = mysql_connect(MysqlDbConstants::$slave111["HOST"],
		    MysqlDbConstants::$slave111["USER"],
                    MysqlDbConstants::$slave111["PASS"])
		    or die("Unable to connect to slave server");

$startDate	=date("Y-m-d 00:00:00:000",strtotime("-1 days"));
$endDate  	=date('Y-m-d 23:59:59.000',strtotime("-1 days"));
//$sql= "SELECT * from Thread_report_table";
$sql= "SELECT * from Thread_report_table where call_start_time>='$startDate' AND call_start_time<='$endDate'";
$result = mssql_query($sql,$db_dialer) or die($sql . mysql_error($db_dialer));

$count=0;
while ($singleRowData = mssql_fetch_array($result)) {
    $date = date("Y-m-d");
    $code = trim($singleRowData['code']);
    $ph_number = trim($singleRowData['ph_number']);
    $origin = trim($singleRowData['origin']);
    $termination_status = trim($singleRowData['termination_status']);

    $call_start_time  = trim($singleRowData['call_start_time']);
    $timeStamp =strtotime($call_start_time);
    $call_start_time = date("Y-m-d H:i:s", $timeStamp);

    $skill_profile = trim($singleRowiData['skill_profile']);
    $wait_time = trim($singleRowData['wait_time']);

    $agent_start_time = trim($singleRowData['agent_start_time']);
    $agent_start_time = strtotime($agent_start_time);
    $agent_start_time = date("Y-m-d H:i:s", $agent_start_time);

    $agent = trim($singleRowData['agent']);
    $talk_time = trim($singleRowData['talk_time']);
    $hold_time = trim($singleRowData['hold_time']);
    $wrap_time = trim($singleRowData['wrap_time']);
    $disconnect = trim($singleRowData['disconnect']);
    $global_call = trim($singleRowData['global_call']);
    $campaign = trim($singleRowData['campaign']);
    $report_user = trim($singleRowData['report_user']);
    $report_name = trim($singleRowData['report_name']);
    $call_direction = trim($singleRowData['call_direction']);
    $call_thread = trim($singleRowData['call_thread']);
    $ct_easycode = trim($singleRowData['ct_easycode']);
    $profileId = trim($singleRowData['ProfileID']);
    $lastDisposition = trim($singleRowData['LastDisposition']);

	// Mysql query to insert data
	$sql = "INSERT into js_crm.DIALER_OUTBOUND_DETAILS (DATE_IST,code,ph_number,origin,termination_status,call_start_time_ist,skill_profile,wait_time,agent_start_time_ist,agent,talk_time,hold_time,wrap_time,disconnect,global_call,campaign,report_user,report_name,call_direction,call_thread,ct_easycode,profileid,lastDisposition)
	values('$date','$code','$ph_number','$origin','$termination_status','$call_start_time','$skill_profile','$wait_time','$agent_start_time','$agent','$talk_time','$hold_time','$wrap_time','$disconnect','$global_call','$campaign','$report_user','$report_name','$call_direction','$call_thread','$ct_easycode','$profileId','$lastDisposition')";
	mysql_query($sql,$db_slave111) or die($sql.mysql_error($db_slave111));
	$count++;
}
echo ""[.$count."]";

?>
