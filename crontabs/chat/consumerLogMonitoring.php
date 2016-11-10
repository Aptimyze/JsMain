<?php
	$domain = "http://10.10.18.67:9090/";
	$logFilePath = array("USER_CREATE_Log"=>"/data/projects/logs/JsRosterConsumer_1.log",
						 "USER_DELETE_Log"=>"/data/projects/logs/JsRosterConsumer_2.log",
						 "SHORTLIST_ROSTER_Log"=>"/data/projects/logs/JsRosterConsumer_3.log",
						 "DPP_ROSTER_Log"=>"/data/projects/logs/JsRosterConsumer_4.log",
						 "INT_REC_ROSTER_Log"=>"/data/projects/logs/JsRosterConsumer_5.log",
						 "INT_SENT_ROSTER_Log"=>"/data/projects/logs/JsRosterConsumer_6.log",
						 "ACCEPTANCE_ROSTER_Log"=>"/data/projects/logs/JsRosterConsumer_7.log",
						 "ROSTER_UPDATOR_Log"=>"/data/projects/logs/JsRosterConsumer_8.log",
						 "ACCEPTANCE_SENT_Log"=>"/data/projects/logs/JsRosterConsumer_9.log",
						 "MESSAGE_Log"=>"/projects/logs/JsMessagingConsumer.log"
						 );

	$consumerMapping = array(
							$logFilePath["USER_CREATE_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=1 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["USER_DELETE_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=2 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["SHORTLIST_ROSTER_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=3 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["DPP_ROSTER_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=4 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["INT_REC_ROSTER_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=5 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["INT_SENT_ROSTER_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=6 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["ACCEPTANCE_ROSTER_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=7 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["ROSTER_UPDATOR_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=8 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["ACCEPTANCE_SENT_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=9 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
							),
							$logFilePath["MESSAGE_Log"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsMessagingConsumer/jsmessagingconsumer-0.1.0.jar",
								"consumerPath"=>"java -jar -Dspring.profiles.active=live jsmessagingconsumer-0.1.0.jar &"),
							)
						);
	//print_r($consumerMapping);
	//print_r($logFilePath);
	//die;
	$currentTime = time();
	$timeThreshold = 900; //in sec
	foreach ($logFilePath as $key => $path) {
		$fileExist = file_exists($path);
		if($fileExist != false){
			$stat = stat($path);
			$lastUpdatedDiff = $currentTime - $stat['mtime'];
			if($lastUpdatedDiff > $timeThreshold){
				restartConsumers($path);
				$subject = "Log Monitoring: Consumers associated with ".$path." on ".$domain."restarted";
				sendMailAlert($subject);
			}
			clearstatcache();
		}
		else{
			$subject = "Log Monitoring: File ".$path." does not exist on ".$domain;
			sendMailAlert($subject);
		}
	}

	function restartConsumers($path){
		global $consumerMapping;
		foreach($consumerMapping[$path] as $key => $consumerDetails){
			unset($processNumbers);
			exec("ps aux | grep '".$consumerDetails["consumerPath"]."' | grep -v grep | awk '{ print $2 }'", $processNumbers);
			if(!empty($processNumbers) && is_array($processNumbers)){
				foreach($processNumbers as $key => $value){
					$count = shell_exec("ps -p ".$value." | wc -l") -1;
	                if($count >0)
	                  exec("kill -9 ".$value);
          		}
			}
		}
	}

	function sendMailAlert($subject){
		//mail("lavesh.rawat@gmail.com,pankaj139@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com",$subject,"Please check");
		mail("lavesh.rawat@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com",$subject,"Please check, restarting consumers as well");
		//mail("nitishpost@gmail.com",$subject,"Please check");
	}
?>