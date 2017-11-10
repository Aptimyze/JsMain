<?php
/*********************************************************************************************
* FILE NAME   	: salesCampaignDialerProcess.php 
* DESCRIPTION 	: Capture sales campaign details for mailer sending 
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerLog.class.php");
$dialerLogObj =new DialerLog();

//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master1['HOST'],MysqlDbConstants::$master1['USER'],MysqlDbConstants::$master1['PASS']) or die("Unable to connect to nmit server ");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query("set session wait_timeout=600",$db_master);

$campaignArr	=array('IB_Sales','IB_Service','IB_PaidService');
$startDt      	=date("Y-m-d");
$checkTime      ='Jan  1 1753 00:00:00:000AM';	
$mailStatus	='N';

//Now we have to run this cron with every 15 min and delete 2 days data
//findind the max date from the SALES_CAMPAIGN_PROFILE_DETAILS
/*$query = "SELECT MAX(DIALER_TIME) as time, CAMPAIGN FROM incentive.SALES_CAMPAIGN_PROFILE_DETAILS GROUP BY CAMPAIGN";
$result = mysql_query($query,$db_master) or die($query.mysql_error($db_master));;
while($srow = mysql_fetch_array($result)){
    $res[$srow['CAMPAIGN']] =$srow['time'];
}*/

foreach($campaignArr as $key=>$campaignName){
    $timeArr =getMaxDialTime($db_master,$campaignName);
    $time =$timeArr['time'];	
    $date1 =$timeArr['date'];
    $date2 =explode(" ",$date1);		
    $date3 =$date2[0];
    if($date3!=$startDt)
	$time='';
     	
    if(!$time)	
	$time =$checkTime;	
    echo "\n".$squery1 = "select distinct(PHONE_NO1) as PHONE_NO,Last_call_time from easy.dbo.ct_$campaignName where Last_call_date >='$startDt' and Last_call_time >'$time' order by Last_call_time asc";
    $sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaignName,$db_dialer,1);
    while($srow1 = mssql_fetch_array($sresult1))
    {
        $phoneNo =$srow1["PHONE_NO"];
        $dialerTime = $srow1["Last_call_time"];
        $phoneNo =phoneNumberCheck($phoneNo);
	//echo "$dialerTime\n";continue;

        if($phoneNo){
            $profileArr =getProfileDetails($phoneNo,$db_js);
            foreach($profileArr as $key=>$profileid){
                $currentDate = date("Y-m-d H:i:s");
                $js_query = "INSERT ignore into incentive.SALES_CAMPAIGN_PROFILE_DETAILS(`PROFILEID`,`PHONE_NO`,`CAMPAIGN`,`MAIL_SENT`,`DATE`,`DIALER_TIME`) VALUES ('$profileid','$phoneNo','$campaignName','$mailStatus','$currentDate','$dialerTime')";
                mysql_query($js_query,$db_master) or die($js_query.mysql_error($db_master));

                //Insert profile for Logging purpose
                $js_query = "INSERT ignore into incentive.SALES_CAMPAIGN_PROFILE_DETAILS_log(`PROFILEID`,`PHONE_NO`,`CAMPAIGN`,`MAIL_SENT`,`DATE`) VALUES ('$profileid','$phoneNo','$campaignName','$mailStatus','$currentDate')";
                mysql_query($js_query,$db_master) or die($js_query.mysql_error($db_master));
            }
        }
        
    }
}

//Now to delete the enrty condition with that it will work 1 time in a day
$Starttime =date("Y-m-d")." 12:00:00";
$endTime = date("Y-m-d")."13:00:00";
if(time() > strtotime($Starttime) && time() < strtotime($endTime)){
    $oldDate = date("Y-m-d", strtotime('-2 day', time()));
    $js_query = "DELETE from incentive.SALES_CAMPAIGN_PROFILE_DETAILS WHERE DATE <'$oldDate' and MAIL_SENT='Y'";
    mysql_query($js_query,$db_master) or die($js_query.mysql_error($db_master));
}   

// mail added
$to="manoj.rana@naukri.com";
$sub="Sales Campaign Details Update";
$from="From:vibhor.garg@jeevansathi.com";
//mail($to,$sub,$profileStr,$from);

function getMaxDialTime($db_master,$campaignName){
	$query = "SELECT DIALER_TIME as time,DATE FROM incentive.SALES_CAMPAIGN_PROFILE_DETAILS WHERE CAMPAIGN='$campaignName' ORDER BY ID DESC LIMIT 1";
	$result = mysql_query($query,$db_master) or die($query.mysql_error($db_master));;
	if($srow = mysql_fetch_array($result)){
	    $dialTime =$srow['time'];
	    $date =$srow['DATE'];	
	}
	return array("time"=>"$dialTime","date"=>"$date");
}

// Phone Number Validate
function phoneNumberCheck($phoneNumber)
{
	$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
        $phoneNumber    =ltrim($phoneNumber,0);
        if(!is_numeric($phoneNumber))
                return false;
        return $phoneNumber;
}
// Fetch profiles
function getProfileDetails($phoneNo, $db_slave)
{
	$profileArr =array();
        $sql= "SELECT PROFILEID,ACTIVATED FROM newjs.JPROFILE WHERE PHONE_MOB='$phoneNo' OR PHONE_WITH_STD='$phoneNo'";
        $res=mysql_query($sql,$db_slave) or die($sql.mysql_error($db_slave));
        while($myrow = mysql_fetch_array($res)){
		if($myrow['ACTIVATED']!='D')
	                $profileArr[] = $myrow["PROFILEID"];
	}

	if(count($profileArr)==0){
	        $sql1= "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE='$phoneNo'";
	        $res1=mysql_query($sql1,$db_slave) or die($sql1.mysql_error($db_slave));
	        while($myrow1 = mysql_fetch_array($res1)){

	                $pid = $myrow1["PROFILEID"];
			$sql2= "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID='$pid' AND ACTIVATED!='D'";
			$res2=mysql_query($sql2,$db_slave) or die($sql2.mysql_error($db_slave));
			if($myrow2 = mysql_fetch_array($res2)){
				$profileArr[] =$myrow2["PROFILEID"];
			}
		}
	}
        return $profileArr;
}
?>
