<?php
/*cron to check last updated time of consumer logs and send alert sms*/
//include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
//include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");

$logFilesPath = array("/data/projects/logs/consumer_user_acceptance.log"=>array("S1"=>20,"S2"=>10),
					 "/data/projects/logs/consumer_user_acceptance_sent.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_create.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_intrec.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_intsent.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_shortlist.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_update.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/jscommunication.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/JsMessagingConsumer.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_accptance.log"=>array("S1"=>10,"S2"=>20),
					 "/data/projects/logs/consumer_user_accptance_sent.log"=>array("S1"=>10,"S2"=>20)
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
	//$checkExists = file_exists(JsConstants::$cronDocRoot.$pathName);
	$checkExists = file_exists($pathName);
	if($checkExists != false){
		$stat = stat($pathName);
		$lastUpdatedDiff = $currTime - $stat['mtime'];
		//if file not updated within specified time
		if($lastUpdatedDiff > $offset[$shift]){
			//$notUpdatedFiles[$i++] = JsConstants::$cronDocRoot.$pathName;
			$notUpdatedFiles[$i++] = $pathName;
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
	/*foreach($mobileNumberArr as $k=>$v){
		debugLog("sending sms to ".$v);
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached -- logs not updated-- ".implode(",", $notUpdatedFiles);
        
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = sendMonitoringSMS($message,$from,$v,$profileid,'','Y');
        debugLog($smsState);
    }*/
    sendAlertMail(implode(",", $notUpdatedFiles));
}


function debugLog($message){
	$debug = 1;
	if($debug == 1){
		if(is_array($message)){
			print_r($message);
		}
		else
			echo "\n".$message;
	}
}

function sendAlertMail($message){
	debugLog("sending mail-".$message."\n");
	mail("lavesh.rawat@gmail.com,pankaj139@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com","Error in chat consumers","Please check the logs- ".$message);
}

/*function sendMonitoringSMS($message,$from,$mobile,$profileid,$gsm,$table=''){
	

	$xml_content="";
	$i = 0;
	$xml_head="%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
	$xml_content.="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22$message%22%20PROPERTY=%220%22%20ID=%22$profileid%22%3E%3CADDRESS%20FROM=%22$from%22%20TO=%22$mobile%22%20SEQ=%22$profileid%22%20TAG=%22%22/%3E%3C/SMS%3E";
													     
	$xml_end="%3C/MESSAGE%3E";
	$xml_code=$xml_head.$xml_content.$xml_end;
	$fd=@fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send","rb");  
	echo "http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send"."\n";                     
	$response = '';
	if($fd)
	{
		while (!feof($fd))
		{
			$response= fread($fd, 4096);
		}
		fclose($fd);
		$ts=time();
		$today=date('Y-m-d',$ts);
	}
	//debugLog($response);
	return 1;
}*/

?>