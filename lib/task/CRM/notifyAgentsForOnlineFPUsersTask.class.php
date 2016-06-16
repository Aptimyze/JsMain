<?php
/* cron to send notifications to agents for corresponding allocated online or FP users */

class notifyAgentsForOnlineFPUsersTask extends sfBaseTask
{
	/**
	   * 
	   * Configuration details for CRM:notifyAgentsForOnlineFPUsers
	   * 
	   * @access protected
	   * @param none
  	*/
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'notifyAgentsForOnlineFPUsers';
		$this->briefDescription = 'notify agents for corresponding allocated online and FP users';
		$this->detailedDescription = <<<EOF
		The [notifyAgentsForOnlineFPUsers|INFO] task does things.
		Call it with:
		[php symfony CRM:notifyAgentsForOnlineFPUsers|INFO]
EOF;
	}

	/**
	   * 
	   * Function for executing cron. 
	   * 
	   * @access protected
	   * @param $arguments,$options
   */
	protected function execute($arguments = array(), $options = array())
	{	 
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
		$offset = "- 4 min";   
		$lastOffsetDate = date('Y-m-d H:i:s', strtotime($offset));
		$currentDate  = date("Y-m-d H:i:s");
		$lastDayDate = date('Y-m-d H:i:s', strtotime('-1 day'));
		$useFallbackServer = false; //use or not fallback server for agent notifications in case main rabbitmq server is off

		//send notification only during 9.30 am to 7.30 pm
		if(date('H')>=0 && date('H')<=9)
		{
			//updating memcache key "memUpdateDate" and "CRMNotificationEligibleAgents" initially and daily
			if(!JsMemcache::getInstance()->get("memUpdateDate") || (JsMemcache::getInstance()->get("memUpdateDate") && JsMemcache::getInstance()->get("memUpdateDate")<=$lastDayDate))
			{
				
				JsMemcache::getInstance()->set("memUpdateDate",$currentDate);
				//set memcache key for array of active agents
				$pswrdsObj = new jsadmin_PSWRDS('newjs_slave');
				$agentsArr = $pswrdsObj->getAllExecutivesDetails("USERNAME");		
				unset($pswrdsObj);
				JsMemcache::getInstance()->set("CRMNotificationEligibleAgents",serialize($agentsArr));				
			}
			$agentsArr = unserialize(JsMemcache::getInstance()->get("CRMNotificationEligibleAgents"));
			if($agentsArr && is_array($agentsArr))
			{
				$agentsNamesArr = array_map(function ($arr) { return $arr['USERNAME']; }, $agentsArr);     
				$agentsNamesArr = array_values($agentsNamesArr);
			}

	        if($agentsNamesArr && is_array($agentsNamesArr))
	        {
				//find array of recently online allocated profiles to agents in agentsArr
				$profilesObj = new AllocatedProfiles();
				$onlineProfilesArray = $profilesObj->getOnlineProfilesForAllocatedAgent($agentsNamesArr,$lastOffsetDate);
				unset($agentsNamesArr);
				

				//get recent FP users along with their allocated agent
				$recentFPusersArray=array();
				$trackFPObj = new AgentAllocationDetails();
				$recentFPusersArray = $trackFPObj->fetchNewFailedPaymentEligibleProfiles("AGENT_NOTIFICATIONS",$lastOffsetDate,$currentDate);
				
		        
		        //get merged pool of profiles
		        if($onlineProfilesArray && $recentFPusersArray)
		        	$notificationEligibleProfiles = array_merge($onlineProfilesArray,$recentFPusersArray);
		        else
		        {
		        	if($onlineProfilesArray)
		        		$notificationEligibleProfiles = $onlineProfilesArray;
		        	else
		        		$notificationEligibleProfiles = $recentFPusersArray;
		        }
		        unset($onlineProfilesArray);
		        unset($recentFPusersArray);

		        if($notificationEligibleProfiles && is_array($notificationEligibleProfiles))
				{
					//get connection to rabbitmq producer
					$producerObj=new Producer($useFallbackServer);
				    if($producerObj->getRabbitMQServerConnected())
				    {
					    $notifyObj = new formatCRMNotification();
					    $jprofileObj = new JPROFILE('newjs_slave');
					    //push notifications in queue
			        	foreach($notificationEligibleProfiles as $index=>$details)
				        {
					  	
				        	$profileUsername = $jprofileObj->getUsername($details['PROFILEID']);
				        	$params = array("ACTION"=>$details['ACTION'],"AGENT"=>$details['AGENT'],"PROFILE"=>$profileUsername,"PROFILEID"=>$details['PROFILEID']);
				        	$sendData = $notifyObj->mapCRMAgentNotification($params);
				        	if($sendData)
				        	{
				        		//print_r($sendData);
				        		$producerObj->sendMessage($sendData);
				        		$agentID = array_search(array("USERNAME"=>$details['AGENT']), $agentsArr);
				        		if($agentID!=false)
				        		{
				        			$notificationData = array();
					        		//send FSO app notifications
					        		if($params["ACTION"]=="ONLINE")
					        			$notificationData["notificationKey"] = "AGENT_ONLINE_PROFILE";
					        		else if($params["ACTION"]=="FP")
					        			$notificationData["notificationKey"] = "AGENT_FP_PROFILE";
					        		$notificationData["selfUserId"] = $agentID;
					        		$notificationData["otherUserId"] = $details['PROFILEID'];	
					        		$producerObj->sendMessage(formatCRMNotification::mapBufferInstantNotification($notificationData));
					        		//passthru(JsConstants::$php5path." symfony browserNotification:browserNotificationTask INSTANT ".$notificationKey." ".$agentID." ".$details['PROFILEID']." > /dev/null &");
				        		}
				        	}
				        	  
				        }
				        unset($jprofileObj);
				        unset($notifyObj);
			    	
				    }
				    else
				    {
				    	//send mail alert in case of connection failure to rabbitmq producer
				    	$message="Connection to RabbitMQ producer failed in cron notifyAgentsForOnlineFPUsers";
	  					CRMAlertManager::sendMailAlert($message,"AgentNotifications"); 
				    }
				}
				unset($notificationEligibleProfiles);
			}
		}
	}
}
?>
