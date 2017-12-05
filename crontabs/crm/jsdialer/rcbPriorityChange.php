<?php
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_renewal.php
* DESCRIPTION 	: Change the dialer priority based in eligibility criteria on daily basis
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");
include('PriorityHandler.class.php');
include("DialerApplication.class.php");
$dir ="/home/developer/jsdialer";
include_once($dir.'/plugins/predis-1.1/autoload.php');
$ifSingleRedis ='tcp://172.10.18.75:6379';

// Redis Data fetch
$client = new Predis\Client($ifSingleRedis);
$onlineProfilesArr = $client->zRange('online_user', 0, -1);

// Live Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer);
$priorityHandlerObj =new PriorityHandler($db_js, $db_js_111, $db_dialer);
$dialerApplicationObj = new DialerApplication();
$campaign_nameArr =array('OB_JS_RCB');
$limit =10;
$todayDate =date("Y-m-d H:i:s");
$todayDate1 =strtotime($todayDate);

foreach($campaign_nameArr as $key=>$campaignName)
{
       	$status=0;	
	for($i=$status;$i<$limit;$i++){
		$dialerData =$priorityHandlerObj->getDialerProfileForPriority($campaignName,'',$i);
		if(!$dialerData)
			continue;

		foreach($dialerData as $profileid=>$dataArr){
			$priority		=$dataArr['priority'];
			if($priority<=0)
				continue;	

			echo "\n".$priority."---";
			$uploadTimeStamp      =$dataArr['PREFERRED_TIME_IST'];
			$uploadTimeStamp1     =strtotime($uploadTimeStamp);

			if($todayDate1>=$uploadTimeStamp1){
				$diffTime 	=$todayDate1-$uploadTimeStamp1;
				$diffInMin 	=intval($diffTime/60);
				echo "(".$diffInMin.")";			

				$slotExist =$priorityHandlerObj->getTimeSlot($diffInMin);
				if($slotExist){
                    if($dialerApplicationObj->checkProfileInProcess($profileid,true,false,true)){
                        $priorityHandlerObj->prioritizeProfile($profileid,$campaignName,$dialerData,6);
                    } else if(in_array($profileid,$onlineProfilesArr)){
                        $priorityHandlerObj->prioritizeProfile($profileid,$campaignName,$dialerData,5);
                    } else{
                        $priorityHandlerObj->dePrioritizeProfile($profileid,$campaignName,$dialerData);
                    }
				}
			}
		}
		echo "DONE$i"."\n";
	}
}
$to="manoj.rana@naukri.com";
$sub="RCB Prioritization Done.";
$from="From:vibhor.garg@jeevansathi.com";
//mail($to,$sub,'',$from);
?>
