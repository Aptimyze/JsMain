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

	if($mailer_key[1] == 'INSERT'){
	    date_default_timezone_set("Asia/Calcutta");
		$countObj->insertData($mailer_key[0],0,0,0,0,0,0,date("Y-m-d H:00:00"));
	}else
	{
		$instanceID = $countObj->getID($mailer_key[0]);

		//Matchalert mailer
		if($mailer_key[0]=='MATCHALERT_MAILER')
		{
			$maObj = new matchalerts_MAILER();
			$countArr = $maObj->getMailCountForRange();
			$countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
			unset($countArr);
		}

		//New matchalert mailer
		if($mailer_key[0]=='NMA_MAILER')
		{
			$nmaObj = new new_matches_emails_MAILER();
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
			$ynObj = new MAIL_YesNoMail();
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
                //dpp review mailer
                if($mailer_key[0]=='DPP_REVIEW_MAILER')
                {
                        $dppMailerLogObj = new PROFILE_DPP_REVIEW_MAILER_LOG();
                        $countArr = $dppMailerLogObj->getMailCountForRange();
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		//inactive mailers
                if($mailer_key[0]=='INCOMPLETE_15')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(15);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		if($mailer_key[0]=='INCOMPLETE_30')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(30);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		if($mailer_key[0]=='INCOMPLETE_45')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(45);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		if($mailer_key[0]=='INCOMPLETE_60')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(60);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		if($mailer_key[0]=='INCOMPLETE_75')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(75);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }

		if($mailer_key[0]=='INCOMPLETE_90')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(90);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		if($mailer_key[0]=='INCOMPLETE_120')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(120);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }
		if($mailer_key[0]=='INCOMPLETE_145')
                {
                        $inactiveMailerLogObj = new NEWJS_INACTIVE_PROFILES("newjs_master");
                        $countArr = $inactiveMailerLogObj->getMailCountForRange(145);
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }

        if($mailer_key[0]=='SHORTLISTED_PROFILES_MAILER')
        		{
                        $dppMailerLogObj = new MAIL_SHORTLISTED_PROFILES();
                        $countArr = $dppMailerLogObj->getMailCountForRange();
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
        		}

         if($mailer_key[0]=='EMAIL_VER_MAILER')
                {
                        $mailVerOb = new MAIL_ALTERNATE_EMAIL_MAILER();
                        $countArr = $mailVerOb->getMailCountForRange();
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }        
         if($mailer_key[0]=='ALTERNATE_EMAIL_VER_MAILER')
                {
                        $mailVerOb = new MAIL_EMAIL_VER_MAILER();
                        $countArr = $mailVerOb->getMailCountForRange();
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }        
           if($mailer_key[0]=='REMINDER_MAILER')
                {
                        $mailVerOb = new MAIL_UNRESPONDED_CONTACTS();
                        $countArr = $mailVerOb->getMailCountForRange();
                        $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
                        unset($countArr);
                }           
        //Expiring interest mailer
        if($mailer_key[0]=='ExpiringInterest_MAILER')
        {
            $eiObj = new MAIL_ExpiringInterest();
            $countArr = $eiObj->getMailCountForRange();
            $countObj->updateData($instanceID,$countArr['TOTAL'],$countArr['SENT'],$countArr['BOUNCED'],$countArr['INCOMPLETE'],$countArr['UNSUBSCRIBE']);
            unset($countArr);
        }
	}
  }
}
