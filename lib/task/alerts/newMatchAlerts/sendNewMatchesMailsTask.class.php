<?php

/*
 * this cron will populate data for sending fresh matchAlerts
 */

include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
class sendNewMatchesMailsTask extends sfBaseTask
{
	private $limitRec = 10;
        private $sameGenderProfiles = array();
    protected function configure()
    {
        $this->addArguments(array(
                	new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('dailyCron', sfCommandArgument::OPTIONAL, 'My argument',0),
		));
        
        $this->addOptions(array(
          new sfCommandOption('application',null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
        
        $this->namespace = 'alert';
        $this->name = 'sendNewMatchesMails';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony alert:sendNewMatchesMails totalscripts currentscript]
EOF;
    }
    
    /*
     * this function will perform task of inserting ids in table and truncating other tables
     */
    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        
        
        $dailyCron = $arguments["dailyCron"];
        $newMatchAlertReceiver = new new_matches_emails_RECEIVER();
        $profilesArr = $newMatchAlertReceiver->getProfilesToSendEmails($arguments["totalScript"],$arguments["currentScript"],$dailyCron);
        foreach($profilesArr as $key=>$value)
        {
                $profileId=$value["PROFILEID"];
                $hasTrends=$value["HASTRENDS"];
                $dppSwitch=$value["DPP_SWITCH"];
                
                $loggedInProfileObj = LoggedInProfile::getInstance();
		$loggedInProfileObj->getDetail($profileId,"PROFILEID","*");
                
                $newMatchAlertReceiver->updateSent($profileId,$dailyCron);
                
                $StrategyFactoryObj = new NewMatchesMailerStrategy($loggedInProfileObj, $this->limitRec,$dppSwitch,$hasTrends);   
                if($StrategyFactoryObj->getSameGenderAndDppExistsError() == false){
                        $StrategyFactoryObj->getMatches($dailyCron);        
                }else{
                        $this->sameGenderProfiles[] = $profileId;
                }
        }
        if(!empty($sameGenderProfiles)){
                mail("bhavanakadwal@gmail.com","Same Gender Error","PROFILEID's = ".implode(',',$this->sameGenderProfiles));
        }
    }
}
