<?php

/*
 * This file will empty the logging table and make entry in History eoi table at AP cron completion
 */
class APEmptyLoggingTable extends sfBaseTask
{
    protected function configure()
    {
          $this->addOptions(array(
              new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
          ));
    $this->namespace        = 'cron';
    $this->name             = 'APEmptyLoggingTable';
    $this->briefDescription = 'Empty the logging table at completion and make entry in History eoi sent table';
    $this->detailedDescription = <<<EOF
The [APSendEOI|INFO] task does things.
Call it with:

  [php symfony cron:APEmptyLoggingTable|INFO]
EOF;

  }
	/**
    * @return void
    * @access protected
    */	
	protected function execute($arguments = array(), $options = array())
	{
		sfContext::createInstance($this->configuration);
                
                //profiles sending eoi
                $tempProfileRecords = new ASSISTED_PRODUCT_AP_PROFILE_INFO_LOG();
                $alreadySentCount = $tempProfileRecords->getCount();
                
                //profiles to whom eoi's have been sent
                $autoContObj = new ASSISTED_PRODUCT_AUTOMATED_CONTACTS_TRACKING();
                $todaysSentContacts = $autoContObj->getCountAfterDate(date('Y-m-d'));
                
                //todays entries
		$HistoryRecord = new ASSISTED_PRODUCT_HISTORY_EOI_SENT();
		$HistoryRecord->INSERT($todaysSentContacts);
                
                $receiverEoiObj = new receiverEoiCount();
                $receiverEoiObj->emptyTable();
		
		// if script completes successfully send mail
                SendMail::send_email("ankitshukla125@gmail.com","$todaysSentContacts Auto Contacts sent out for $alreadySentCount users","Auto Contacts cron completed");
                $ApProfileInfoLogDDL = new ASSISTED_PRODUCT_AP_PROFILE_INFO_LOG('newjs_masterDDL');
                $ApProfileInfoLogDDL->delete();
		echo "EOI's sent for ".$alreadySentCount." Profiles";
        }
        
}
                