<?php
class dailyMailerMonitoring extends sfBaseTask
{
    var $tempObj,$gcmSenderObj;
  protected function configure()
  {
	$this->namespace        = 'mailer';
    	$this->name             = 'dailyMailerMonitoring';
   	$this->briefDescription = 'mailer monitoring';
    	$this->detailedDescription = <<<EOF
      The task capture mailer delivery report.
      Call it with:

      [php symfony mailer:dailyMailerMonitoring mailer_key
]
EOF;
    $this->addArguments(array(
                new sfCommandArgument('mailer_key', sfCommandArgument::REQUIRED, 'My argument'),
                ));
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

	$entryDate      =date("Y-m-d");
	$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();

	$arg = $arguments['mailer_key'];
	$mailer_key = explode("#",$arg);

	if($mailer_key[1] == 'INSERT')
		$countObj->insertData($mailer_key[0],0,0,0,0,0,0,date("Y-m-d H:00:00"));
	else
	{
		$instanceID = $countObj->getID($mailer_key[0]);

		//Matchalert mailer
		if($mailer_key[0]=='MATCHALERT_MAILER')
		{
			$maObj = new matchalerts_MAILER('newjs_slave');
			$countArr = $maObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//New matchalert mailer
		if($mailer_key[0]=='NMA_MAILER')
		{
			$nmaObj = new new_matches_emails_MAILER('newjs_slave');
			$countArr = $nmaObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//Visitor alert mailer
		if($mailer_key[0]=='VA_MAILER')
		{
			$vaObj = new visitorAlert_MAILER('shard1_slave');
			$countArr = $vaObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//EOI mailer
		if($mailer_key[0]=='EOI_MAILER')
		{
			$eoiObj = new NEWJS_CONTACTS_ONCE('newjs_slave');
			$countArr = $eoiObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//Yes/No mailer
		if($mailer_key[0]=='YESNO_MAILER')
		{
			$ynObj = new MAIL_YesNoMail('newjs_slave');
			$countArr = $ynObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//Filtered mailer
		if($mailer_key[0]=='FILTERED_MAILER')
		{
		       $filterObj = new MAIL_FilterEOI('newjs_slave');
			$countArr = $filterObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//VD mailer
		if($mailer_key[0]=='VD_MAILER')
		{
			$vdObj = new billing_VARIABLE_DISCOUNT();
			$countArr = $vdObj->getMailCountForRange($entryDate, $entryDate);
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//Sales campaign outbound feedback mailer
		if($mailer_key[0]=='SALES_FEEDBACK_MAILER')
		{
			$salesCampaignObj = new incentive_SALES_CAMPAIGN_PROFILE_DETAILS();
			$countArr = $salesCampaignObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//Subscription expiry mailer
		if($mailer_key[0]=='SUBSCRIPTION_EXPIRY_MAILER_10DAYS')
		{
			$expMailerLogObj = new SUBSCRIPTION_EXPIRY_MAILER_LOG_10DAY();
			$countArr = $expMailerLogObj->getMailCountForRange($entryDate);
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}
	
		//Subscription expiry mailer
		if($mailer_key[0]=='SUBSCRIPTION_EXPIRY_MAILER')
		{
			$expMailerLogObj = new SUBSCRIPTION_EXPIRY_MAILER_LOG();
			$countArr = $expMailerLogObj->getMailCountForRange($entryDate);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
       	                unset($countArr);
                }

		//Contact mailer
                if($mailer_key[0]=='CONTACT_MAILER')
                {
                        $contactMailerLogObj = new MAIL_contactViewers();
                        $countArr = $contactMailerLogObj->getMailCountForRange();
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
	}
  }
}
