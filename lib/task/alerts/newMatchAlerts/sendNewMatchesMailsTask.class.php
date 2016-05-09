<?php

/*
 * this cron will populate data for sending fresh matchAlerts
 */

include_once(JsConstants::$alertDocRoot."/newMatches/Receiver.class.php");
include_once(JsConstants::$alertDocRoot."/newMatches/StrategyNTvsNEW.php");
include_once(JsConstants::$alertDocRoot."/newMatches/StrategyTvsNEW.php");
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
class sendNewMatchesMailsTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
                	new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
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
        
        $mysqlObj = new Mysql;
        $localdb=$mysqlObj->connect("alerts");
        
        $newMatchAlertReceiver = new new_matches_emails_RECEIVER();
        $profilesArr = $newMatchAlertReceiver->getProfilesToSendEmails($arguments["totalScript"],$arguments["currentScript"]);
        foreach($profilesArr as $key=>$value)
        {
            $profileId=$value["PROFILEID"];
            
            $newMatchAlertReceiver->updateSent($profileId);
            
            $receiverObj=new Receiver($profileId,$localdb);//get receiver profile
            
            if($receiverObj->getSameGenderError()=='N' && $receiverObj->getIsPartnerProfileExist()=="Y")
            {
                    if($receiverObj->getHasTrend() != true || $receiverObj->getSwitchToDpp()==1)
                    {
                            $StrategyObj = new StrategyNTvsNEW($receiverObj,$localdb);
                            $StrategyObj->doProcessing();
                    }
                    else
                    {
                            $StrategyObj = new StrategyTvsNEW($receiverObj,$localdb);
                            $StrategyObj->doProcessing();
                    }
                    unset($StrategyObj);
            }
            else
            {
                    $gap=MailerConfigVariables::getNoOfDays();
                    $zeropid=$profileId;
                    
                    $newMatchesEmailsGender = new new_matches_emails_GENDER_OR_JPARTNER_ERROR();
                    $newMatchesEmailsGender->insert($zeropid, $gap);
                    $sameGenderArr.=$profileId. " , ";
            }
        }
        if($sameGenderArr)
        {
            mail("lavesh.rawat@jeevansathi.com","Same Gender Error or DPP data missing","PROFILEID's = ".$sameGenderArr);
        }   
    }
}
