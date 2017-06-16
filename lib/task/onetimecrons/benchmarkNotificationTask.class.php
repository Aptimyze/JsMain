<?php

class benchmarkNotificationTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'benchmarkNotification';
		$this->briefDescription = 'benchmark notifications';
		$this->detailedDescription = <<<EOF
		The [benchmarkNotification|INFO] task does things.
		Call it with:
		[php symfony CRM:benchmarkNotification|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{	
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
        $notificationKeys = array("PENDING_EOI","MATCHALERT","FILTERED_EOI");
        $messages = array("Respond to ZZXS8902 members waiting for your response.","Contact these 2 new matches for today which have been handpicked by us based on your preferences","These profiles have sent you an interest but landed in your filtered folder. You may want to consider them and accept/decline their interests.");
        $titles = array("Pending Interests","Match Alerts","Filtered Interests");
        $regObj = new MOBILE_API_REGISTRATION_ID();
        $registrationIds = $regObj->getValidRegisteredProfiles("AND");
        unset($regObj);

        $scheduledObj = new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS();
        foreach ($registrationIds as $key => $regDetails) {
            $modulus = 2;
            if($regDetails["PROFILEID"] % 2 == 0){
                $modulus = 0;
            }
            else if($regDetails["PROFILEID"] % 3 == 0){
                $modulus = 1;
            }
            else if($regDetails["PROFILEID"] % 5 == 0){
                $modulus = 2;
            }
            $insertData[0] = array("PROFILEID"=>$regDetails["PROFILEID"],"NOTIFICATION_KEY"=>$notificationKeys[$modulus],"MESSAGE"=>$messages[$modulus],"LANDING_SCREEN"=>4,"OS_TYPE"=>"AND","COLLAPSE_STATUS"=>"Y","TTL"=>0,"TITLE"=>$titles[$modulus],"COUNT"=>1,"MSG_ID"=>rand(0,99).time().rand(0,99).rand(0,99).rand(0,9),"PRIORITY"=>10,"SENT"=>"N","PHOTO_URL"=>"D","PROFILE_CHECKSUM"=>md5($regDetails["PROFILEID"])."i".$regDetails["PROFILEID"]);
            $scheduledObj->insert($insertData);
        }
        unset($scheduledObj);
	}
}
