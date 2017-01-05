<?php

/* 
 * This cron will pick users based on date of registration and send mail with their dpp
 * to review it
 */
include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
class dppReviewMailerTask extends sfBaseTask
{
	protected function configure()
  	{    
             $this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     $this->addArguments(array(
                	new sfCommandArgument('isOneTime', sfCommandArgument::REQUIRED, 'My argument'),
		));
             
	    $this->namespace        = 'mailer';
	    $this->name             = 'dppReviewMailer';
	    $this->briefDescription = 'send the mail to jeevansathi users for reviewing their dpp.';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:dppReviewMailer isOneTime] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
                    sfContext::createInstance($this->configuration);
                /**
		*daily monitoring
		*/
		$php5 = JsConstants::$php5path;
                $cronDocRoot = JsConstants::$cronDocRoot;
                $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG("newjs_slave");
                $instanceId = $countObj->getID('DPP_REVIEW_MAILER');

                $registeredProfiles = new JPROFILE("newjs_slave");
                //for one time mailers
                if($arguments["isOneTime"]){
                    $date = date('Y-m-d',strtotime('-5 month'));
                    $profilesArr = $registeredProfiles->getProfilesWithinGivenActiveDate($date); 
                }
                else{
                    $dateArr['first_up'] = date('Y-m-d',strtotime('-7 day'));
                    $dateArr['first_low'] = date('Y-m-d',strtotime('-8 day'));
                    $dateArr['sec_up'] = date('Y-m-d',strtotime('-29 day'));
                    $dateArr['sec_low'] = date('Y-m-d',strtotime('-30 day'));
                    $dateArr['third_up'] = date('Y-m-d',strtotime('-89 day'));
                    $dateArr['third_low'] = date('Y-m-d',strtotime('-90 day'));
                    $dateArr['fourth_up'] = date('Y-m-d',strtotime('-149 day'));
                    $dateArr['fourth_low'] = date('Y-m-d',strtotime('-150 day'));
                    $profilesArr = $registeredProfiles->getProfilesWithGivenRegDates($dateArr);
                }
                //$profilesArr = array(0=>array(PROFILEID=>658));
                foreach($profilesArr as $key=>$value){
                    $value = $value[PROFILEID];
                    dppReviewMailerFunctions::sendDppReviewMail($value,$instanceId);
                }
                
                passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring DPP_REVIEW_MAILER");
                passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring DPP_REVIEW_MAILER#INSERT");
                $dppMailerLogObj = new PROFILE_DPP_REVIEW_MAILER_LOG('newjs_masterDDL');
                $dppMailerLogObj->truncateTable();
	}
}
