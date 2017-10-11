<?php
	$domain = "http://10.10.18.67:9090/";
	$rabbitmqConfig = array('HOST'=>'10.10.18.62','PORT'=>'5672','USER'=>'admin','PASS'=>'admin','VHOST'=>'/','MANAGEMENT_PORT'=>'15672');
	$mqQueuesArr = array("profile-created-queue","profile-deleted-queue","roster-created-acceptance","roster-created-acceptance_sent","roster-created-intrec","roster-created-intsent","roster-created-shortlist","roster-updated-queue","roster-created-dpp","chat");
	$msgLimitPerQueue = 5000;
	$queuesWithExtraLimit = array("roster-created-dpp"=>7000);
	$restartShellPath = "/home/developer/projects/JsRosterConsumer/restartChatConsumers.sh";

	$consumerMapping = array(
							"profile-created-queue"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=1 -jar jsrosterconsumer-0.1.0.jar"),
							"profile-deleted-queue"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=2 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-created-shortlist"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=3 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-created-dpp"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=4 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-created-intrec"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=5 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-created-intsent"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=6 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-created-acceptance"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=7 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-updated-queue"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=8 -jar jsrosterconsumer-0.1.0.jar"),
							"roster-created-acceptance_sent"=>array(
								"consumerGrep"=>"/home/developer/projects/JsRosterConsumer/jsrosterconsumer-0.1.0.jar",
								"consumerPath"=>"java -Dspring.profiles.active=9 -jar jsrosterconsumer-0.1.0.jar"),
							"chat"=>array(
								"consumerGrep"=>"/home/developer/projects/JsMessagingConsumer/jsmessagingconsumer-0.1.0.jar",
								"consumerPath"=>"java -jar -Dspring.profiles.active=live jsmessagingconsumer-0.1.0.jar"),
						);
	//print_r($consumerMapping);
	//print_r($queueToConsumerMap);
	//die;
	
		//get data about rabbitmq queues
		$queueResponse = checkRabbitmqQueueMsgCount($rabbitmqConfig);
		//check overflow in queues and send alert in case of overflow
		checkForQueueOverflow($mqQueuesArr,$queueResponse);

		function checkForQueueOverflow($queueArr,$queueResponse){
			global $msgLimitPerQueue,$queuesWithExtraLimit,$consumerMapping,$restartShellPath;
			if(is_array($queueResponse)){
			        foreach($queueResponse as $arr){
			                $queue_data=$arr;
			                $msgLimit = $msgLimitPerQueue;
			                if($queuesWithExtraLimit[$queue_data->name]){
			                        $msgLimit = $queuesWithExtraLimit[$queue_data->name];
			                }
			                if(in_array($queue_data->name, $queueArr) && $queue_data->messages_ready>$msgLimitPerQueue)
			                {
			                        $overflowQueueArr[$queue_data->name] = $queue_data->messages_ready;
			                }
			        }
			}
			//die;
			unset($queueResponse);
			//print_r($overflowQueueArr);die;
			if($overflowQueueArr && count($overflowQueueArr)>0){
					$mailMsg = "";
					foreach ($overflowQueueArr as $key => $value) {
						$mailMsg = $mailMsg.",".$key."(".$value.")";
						killConsumers($consumerMapping[$key]);
					}
					//restart chat consumers
					exec("nohup sh ".$restartShellPath." > /dev/null &");
			        //var_dump($mailMsg);die;
			       mail ("lavesh.rawat@gmail.com,pankaj139@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com","Overflow in chat queues @10.10.18.62","Please check queues - ".$mailMsg.".........consumers restarted as well");
			       //mail ("lavesh.rawat@gmail.com","Overflow in chat queues @10.10.18.62","Please check queues - ".$mailMsg.",consumers restarted as well");
			}
		}
	function checkRabbitmqQueueMsgCount($rabbitmqConfig){
		$rabbitmq_mgmnt_port=$rabbitmqConfig['MANAGEMENT_PORT'];
		$api_url = "/api/queues/%2F";
		$rabbitmq_creds="{$rabbitmqConfig['USER']}:{$rabbitmqConfig['PASS']}";
		$rabbitmq_url="http://{$rabbitmqConfig['HOST']}:{$rabbitmq_mgmnt_port}{$api_url}"; 
		$curl=  curl_init();
	    curl_setopt($curl, CURLOPT_URL,$rabbitmq_url);
	    curl_setopt($curl,CURLOPT_RETURNTRANSFER,True);
	    curl_setopt($curl, CURLOPT_USERPWD,$rabbitmq_creds);

        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_USERAGENT,"JsInternal");    

	    $response= curl_exec($curl);

            // remove header from curl Response 
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $response = substr($response, $header_size);

	    curl_close($curl);
	    $response =json_decode($response); 
		//print_r($response);die;
		return $response;
	}

	function killConsumers($consumerDetails){
		exec("ps aux | grep '".$consumerDetails["consumerPath"]."' | grep -v grep | awk '{ print $2 }'", $processNumbers);
		//var_dump("ps aux | grep '".$consumerDetails["consumerPath"]."' | grep -v grep | awk '{ print $2 }'");
		//print_r($processNumbers);
		if(!empty($processNumbers) && is_array($processNumbers)){
			foreach($processNumbers as $key => $value){
				$count = shell_exec("ps -p ".$value." | wc -l") -1;
                if($count >0)
                  exec("kill -9 ".$value);
      		}
		} 
	}
?>
