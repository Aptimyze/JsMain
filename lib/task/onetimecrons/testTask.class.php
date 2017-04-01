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

	protected function execute($arguments = array(), $options = array())
	{
	    // SET BASIC CONFIGURATION
	   //send instant JSPC/JSMS notification

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
