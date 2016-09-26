<?php
/**
* This will mail too many connection reason.
* @author : Prashant Pal
* @package Monitoring
* @since 2015-06-29
*/
class MonitotingJSMTask extends sfBaseTask
{
	CONST minNo  = 45000;
	CONST maxNo  = 80000;
	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));
          
            $this->namespace        = 'monitoring';
            $this->name             = 'MonitoringJSM';
            $this->briefDescription = 'This cron runs to monitor the working of the script of azkaban if th ecrons fails to run';
            $this->detailedDescription = <<<EOF
          [php symfony monitoring:MonitoringJSM]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
        	$this->APSendEOITaskCheck();
        }

        public function APSendEOITaskCheck()
        {
		$HistoryRecord = new ASSISTED_PRODUCT_HISTORY_EOI_SENT();
		$totalRecords=$HistoryRecord->SELECT();
		if($totalRecords<self::minNo || $totalRecords>self::maxNo)
		{
			SendMail::send_email("ankitshukla125@gmail.com,lavesh.rawat@gmail.com","$totalRecords Auto Contacts sent out for users","Auto Contacts cron failed");

			include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
			$arrMob = array('9711818214','9818424749');
			$date = date("Y-m-d h");
			$message        = "Mysql Error Count have reached APeoifail $totalRecords within 5 minutes";
			$from           = "JSSRVR";
			$profileid      = "144111";
			foreach($arrMob as $mobile1)
				$smsState = send_sms($message,$from,$mobile1,$profileid,'','Y');
			
		}
		else
		{
			SendMail::send_email("ankitshukla125@gmail.com","$totalRecords Auto Contacts sent out for users","Auto Contacts cron completed");
		}
        }
} 
