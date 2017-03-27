<?php
/* This class runs a cron to send offer 1 month plan backend link for a set of users through text mailer every 3rd Sunday of the month.
   Eligible profiles for this plan follow 3 conditions:
   1. Last login within 15 days
   2. Registration not within 6 months
   3. Never paid
*/

class offer1MonthPlanMailerTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'offer1MonthPlanMailer';
		$this->briefDescription = 'offer 1 month plans through backend link for a set of users which will be sent in a text mailer every 3rd Sunday of the month';
		$this->detailedDescription = <<<EOF
		The [offer1MonthPlanMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:offer1MonthPlanMailer|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		// SET BASIC CONFIGURATION
	    	ini_set('max_execution_time',0);
	    	ini_set('memory_limit',-1);
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}

		//Configurable parameters	
		$lastLoginOffset = "- 15 day";       	//to fetch profiles logged in within 15 days
	        $lastRegistrationOffset = "- 6 month";  //to fetch profiles registered before 6 months
        	$neverPaidFlag = true; 			//set to fetch never paid profiles

		//fetch desired set of profiles                         
                $mmObj = new MembershipMailer();
                $profilesArr =$mmObj->fetchOfferConditionsBasedProfiles($lastLoginOffset,$lastRegistrationOffset,$neverPaidFlag);

		//Store data of fetched profiles
                if(count($profilesArr)>0)
                {
			$oneMonthObj=new incentive_BACKEND_LINK_MAILER();
                        foreach($profilesArr as $row)
				$oneMonthObj->setDataforMailer($row);
                }

		unset($oneMonthObj);
		unset($mmObj);
	}
}
