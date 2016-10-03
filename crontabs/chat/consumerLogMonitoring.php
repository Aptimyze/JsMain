<?php
	$domain = "http://10.10.18.67:9090/";
	$logFilePath = array("remainingLog"=>"/data/projects/logs/JsRosterConsumer.log",
						 "updateLog"=>"/data/projects/logs/JsRosterConsumer_update.log");

	$consumerMapping = array($logFilePath["remainingLog"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-create.jar",
								"consumerPath"=>"java -Dspring.profiles.active=1 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-shortlist.jar",
								"consumerPath"=>"java -Dspring.profiles.active=3 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-delete.jar",
								"consumerPath"=>"java -Dspring.profiles.active=2 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-intrec.jar",
								"consumerPath"=>"java -Dspring.profiles.active=5 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-intsent.jar",
								"consumerPath"=>"java -Dspring.profiles.active=6 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-acceptance.jar", 
								"consumerPath"=>"java -Dspring.profiles.active=7 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-acceptance_sent.jar",
								"consumerPath"=>"java -Dspring.profiles.active=9 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								),
							$logFilePath["updateLog"]=>array(array(
								"consumerGrep"=>"/home/developer/projects/JsConsumer/jsconsumer-update.jar",
								"consumerPath"=>"java -Dspring.profiles.active=8 -jar jsrosterconsumer-0.1.0.jar >>/dev/null &"),
								));
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
			exec("ps aux | grep ".$consumerDetails["consumerGrep"]." | grep -v grep | awk '{ print $2 }'", $processNumbers);
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
		mail("lavesh.rawat@gmail.com,pankaj139@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com",$subject,"Please check");
		//mail("nitishpost@gmail.com",$subject,"Please check");
	}
?>