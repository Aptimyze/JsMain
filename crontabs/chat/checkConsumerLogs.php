<?php
/*cron to check last updated time of consumer logs and send alert sms*/
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");

$logFilesPath = array("/log/consumer1.log"=>array("S1"=>20,"S2"=>10),
					 "/log/consumer2.log"=>array("S1"=>10,"S2"=>20),
					 "/log/consumer3.log"=>array("S1"=>10,"S2"=>20)
					 );
$mobileNumberArr = array("9910244159","9650879575","9818424749","8989931104","9810300513");
$branchPath = JsConstants::$cronDocRoot;
$currTime = time();
$i = 0;

$shift = "S1";
if(date('H')>=0 && date('H')<=11){
	$shift = "S1"; //9.30 am to 8.30 pm
}
else{
	$shift = "S2"; //remaining time
}
debugLog("current time ".date("d m Y H:i:s",$currTime));
debugLog("shift---".$shift);
foreach ($logFilesPath as $pathName => $offset) {
	//check if file exists
	$checkExists = file_exists(JsConstants::$cronDocRoot.$pathName);
	if($checkExists != false){
		$stat = stat($branchPath.$pathName);
		$lastUpdatedDiff = $currTime - $stat['mtime'];
		//if file not updated within specified time
		if($lastUpdatedDiff > $offset[$shift]){
			$notUpdatedFiles[$i++] = JsConstants::$cronDocRoot.$pathName;
		}
		clearstatcache();
		debugLog("File- ".$pathName." last modified at: ".date("d m Y H:i:s",$stat['mtime']));
		
	}
	else{
		debugLog("file not exists");
	}
}
if(is_array($notUpdatedFiles)){
	debugLog("---not updated files---");
	debugLog($notUpdatedFiles);
	//send alert sms
	foreach($mobileNumberArr as $k=>$v){
		debugLog("sending sms to ".$v);
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached -- logs not updated-- ".implode(",", $notUpdatedFiles);
        
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$v,$profileid,'','Y');
        debugLog($smsState);
    }
}


function debugLog($message){
	$debug = 0;
	if($debug == 1){
		if(is_array($message)){
			print_r($message);
		}
		else
			echo "\n".$message;
	}
}

?>