<?php
/*
 * this cron will populate data for sending fresh matchAlerts
 */

include_once(JsConstants::$alertDocRoot."/newMatches/TrackingFunctions.class.php");

class newMatchAlertsPopulateTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
                	new sfCommandArgument('dailyCron', sfCommandArgument::OPTIONAL, 'My argument'),
		));
        $this->addOptions(array(
          new sfCommandOption('application',null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
        ));
        
        $this->namespace = 'alert';
        $this->name = 'newMatchAlertsPopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony alert:newMatchAlertsPopulate]
EOF;
    }
    
    /*
     * this function will perform task of inserting ids in table and truncating other tables
     */
    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        
        if(!$php5)
	$php5=JsConstants::$php5path; //live php5
/** code for daily count monitoring**/
		$cronDocRoot = JsConstants::$cronDocRoot;
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring NMA_MAILER");
	 
/**code ends*/
/** code for inserting daily count**/
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring NMA_MAILER#INSERT");
	 
/**code ends*/           
        $temp = 0;
        if($arguments["dailyCron"] == 1){
           $temp = 1;
        }
        $newMatchAlertReceiver = new new_matches_emails_RECEIVER();
        $newMatchAlertReceiver->truncateTable($temp);
        
        $sortDate = date('Y-m-d H:i:s', strtotime('-6 months'));
        $entryDate = date('Y-m-d H:i:s', strtotime('-15 days'));
        $loginDate = date('Y-m-d H:i:s', strtotime('-3 months'));
        $affectedCount = $newMatchAlertReceiver->insertValuesFromJprofileAndJprofileAlerts($sortDate,$entryDate,$loginDate,$temp);
        //$newMatchAlertReceiver->updateTrends();
        //$newMatchAlertReceiver->resetTrendsIfOldLogicSet();
        
        $newMatchAlertLog = new new_matches_emails_LOG();
        $newMatchAlertLog->insertFromLog_Temp($temp);
        
        $newMatchAlertLog_Temp = new new_matches_emails_LOG_TEMP();
        $newMatchAlertLog_Temp->truncateTable($temp);
        
        $deleteDate = MailerConfigVariables::getNoOfDays();
        $deleteDate = $deleteDate-31;
        $newMatchAlertLog->deleteEntriesBeforeDate($deleteDate);
        
        $newMatchAlertMAILER = new new_matches_emails_MAILER();
        $newMatchAlertMAILER->truncateTable($temp);
        
        $trackObj = new TrackingFunctions();
        $output = $trackObj->trackingMis(array("PROFILES_CONSIDERED"=>$affectedCount));
        unset($trackObj);
    }
}