<?php
/* This class runs a cron to send offer 1 month plan backend link for a set of users through text mailer every 3rd Sunday of the month.
   Eligible profiles for this plan follow 3 conditions:
   1. Last login within 15 days
   2. Registration not within 6 months
   3. Never paid
*/

class offer1MonthPlanMailerSendMailTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'offer1MonthPlanMailerSendMail';
		$this->briefDescription = 'offer 1 month plans through backend link for a set of users which will be sent in a text mailer every 3rd Sunday of the month';
		$this->detailedDescription = <<<EOF
		The [offer1MonthPlanMailerSendMail day|INFO] task does things.
		Call it with:
		[php symfony mailer:offer1MonthPlanMailerSendMail day|INFO]
EOF;
		$this->addArguments(array(
                new sfCommandArgument('day', sfCommandArgument::REQUIRED, 'My argument'),
                ));

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
	
		//fetch data of profiles day-wise
		$day = $arguments['day'];
		if($day<0 || $day>2)
		{
			echo "Wrong Argument";
			die;
		}
		$oneMonthObj = new incentive_BACKEND_LINK_MAILER('newjs_slave');
		$profilesArr = $oneMonthObj->getDataforMailer($day);

		/*SEND MAIL*/
		$mailId ='1800';                        //for 1 month eRISHTA offer plan
                $service = 'P1';                        //for eRISHTA service
		$mmObj = new MembershipMailer();

	        //fetch price of service
        	$serviceObj = new billing_SERVICES('newjs_slave');
	        $servicePriceArr=$serviceObj->fetchServicePrice($service, 'desktop');

		//send email to fetched profiles
		if(count($profilesArr)>0)
		{
			foreach($profilesArr as $row)
				$mmObj->sendServiceBasedEmail($mailId,$row,$service,$servicePriceArr,'desktop');
		}
		/*SEND MAIL*/	
	
		unset($mmObj);
	}
}
