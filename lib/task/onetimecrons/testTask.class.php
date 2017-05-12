<?php
/* This class runs a cron to send offer 1 month plan backend link for a set of users through text mailer every 3rd Sunday of the month.
   Eligible profiles for this plan follow 3 conditions:
   1. Last login within 15 days
   2. Registration not within 6 months
   3. Never paid
*/

class testTask extends sfBaseTask
{
	protected function configure()
	{
    $this->addArguments(array(new sfCommandArgument('user', sfCommandArgument::OPTIONAL, 'My argument')));

		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'test';
		$this->briefDescription = 'offer 1 month plans through backend link for a set of users which will be sent in a text mailer every 3rd Sunday of the month';
		$this->detailedDescription = <<<EOF
		The [test|INFO] task does things.
		Call it with:
		[php symfony CRM:test|INFO]
EOF;
	}
private function checkRabbitmqServerStatus($serverid,$api_url)
  {
    $server_credentials='FIRST_SERVER';
    $rabbitmq_mgmnt_port=JsConstants::$rabbitmqManagementPort;
    $rabbitmq_host="10.10.18.104";
    $rabbitmq_user="admin";
    $rabbitmq_pswd="admin";
    $rabbitmq_creds="$rabbitmq_user:$rabbitmq_pswd";
    $rabbitmq_base_url="http://{$rabbitmq_host}:{$rabbitmq_mgmnt_port}";    
    $rest_url="{$rabbitmq_base_url}{$api_url}";
    $response=RabbitmqHelper::curlToRabbitmqAPI($rest_url,$rabbitmq_creds);
    return $response;
  }
	protected function execute($arguments = array(), $options = array())
	{
    sfContext::createInstance($this->configuration);
	    // SET BASIC CONFIGURATION
	   $alarmApi_url="/api/nodes";
      $resultAlarm=$this->checkRabbitmqServerStatus($serverid,$alarmApi_url);
      echo "3451122";die;
     
      if(is_array($resultAlarm))
      {
       foreach($resultAlarm as $row)
        {          
          if($row->mem_used >= 0)
          {
            
            $str="\nRabbitmq Error Alert: Memory alarm to be raised soon on the first server. Shifting Server";
            RabbitmqHelper::sendAlert($str,"default");
            
            CommonUtility::sendSlackmessage("Rabbitmq Error Alert: Memory alarm to be raised soon,memory used- ".round($row->mem_used/(1024*1024*1024),2). " GB at ".$row->cluster_links[0]->name);
          }
          
          if(($row->disk_free - $row->disk_free_limit) < MessageQueues::SAFE_LIMIT)
          {
            JsMemcache::getInstance()->set("mqDiskAlarm".$serverid,true);
            $str="\nRabbitmq Error Alert: Disk alarm to be raised soon on the first server. Shifting server";
            
            RabbitmqHelper::sendAlert($str,"default");
          }
          else
            JsMemcache::getInstance()->set("mqDiskAlarm".$serverid,false);
        }
      }
      die;
    $user = $arguments["user"];
    if($user){
      //$memHandlerObj = new MembershipHandler(false);
      //$output = $memHandlerObj->computeMembershipPlanStartingRange($user);
      //print_r($output);
    }
    die;

    /*$instantNotificationObj = new InstantAppNotification("BUY_MEMB");
    $instantNotificationObj->sendNotification(939764339,'',"upgrade");*/
        $instantNotificationObj = new InstantAppNotification("EOI");
        $instantNotificationObj->sendNotification(939764339,7194662);
        die;

          $producerObj = new Producer();
          if($producerObj->getRabbitMQServerConnected())
          {
            $notificationData = array("notificationKey"=>"EOI","selfUserId" => 99401121,"otherUserId" => 1); 
            $producerObj->sendMessage(formatCRMNotification::mapBufferInstantNotification($notificationData));
          }
          unset($producerObj);
          die; 	
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
    	$profileid = 99400941;
   		$memCacheObject = JsMemcache::getInstance();
        if ($memCacheObject->get($profileid . "_MEM_NAME")) {
            $output = unserialize($memCacheObject->get($profileid . "_MEM_NAME"));
            $output = json_encode($output);
            $output = str_replace('"','', $output);
        } 
        print_r($output);die;

		//send eoi reminder notification with default reminder message
    $instantNotificationObj =new InstantAppNotification("EOI_REMINDER");
    $instantNotificationObj->sendReminderInstantAppNotification("bassi",1,702,"testing script"); 
    unset($instantNotificationObj); 

die("ank");
		$limit = 1;
		$vdObj = new billing_VARIABLE_DISCOUNT();
		$durationObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        //$uploadObj = new test_VD_UPLOAD_TEMP('newjs_local111');----
        $uploadObj = new test_VD_UPLOAD_TEMP();
        $count = $uploadObj->getCountOfRecords();
        if($count==0)
           echo "empty"; 
        //fetch rows from start of user table or from last inserted row onwards
        for($i=0;$i<$count;$i+=$limit)
        {
            $rows = $uploadObj->fetchSelectedRecords("*",$limit,$i);
        
            foreach ($rows as $key => $value) 
            {
                $profileid = $vdObj->getProfileidWithDiscount($value['PROFILEID']);
  
                if($profileid)
                {
           			$params = array("PROFILEID"=>$value["PROFILEID"],"SERVICE"=>explode(',',$value["SERVICE"]),"DISC3"=>$value["3"],"DISC6"=>$value["6"],"DISC12"=>$value["12"],"DISCL"=>$value["12"]);
                	$durationObj->addVDOfferDurationServiceWise($params);        
                }
            }
           
        }
	}
}
