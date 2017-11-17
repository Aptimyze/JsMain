<?php

/**
 This task is used to send zero matchalert mailer 
 *@author : Ankit Shukla
 *created on : 15 Sept 2016
 */
class SendZeroMatchalertsMailerTask extends sfBaseTask
{
    private $smarty;
    private $mailerName = "ZERO_MATCHALERT";
  
  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'sendZeroMatchalertsMailer';
    $this->briefDescription = 'zero matchalert mailer';
    $this->detailedDescription = <<<EOF
      The task send matchalert mailer .
      Call it with:

      [php symfony mailer:sendZeroMatchalertsMailer totalScript currentScript] 
EOF;
    $this->addArguments(array(
		new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                ));
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$totalScript = $arguments["totalScript"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
	$mailerServiceObj = new MailerService();
	// match alert configurations
	$this->smarty = $mailerServiceObj->getMailerSmarty();
        
        $lowMatchesCheckObj = new LowDppMatchesCheck();
        $profilesZeroMatches = $lowMatchesCheckObj->getProfilesWithZeroMatches($totalScript,$currentScript);
        
        if(is_array($profilesZeroMatches))
        foreach($profilesZeroMatches as $key=>$val){
            $profileObj = LoggedInProfile::getInstance('',$val);
            $profileObj->getDetail('','','*');
            
            
            $data["RECEIVER"]["PROFILE"] = $profileObj;
            $data["RECEIVER"]["EMAILID"] = $profileObj->getEMAIL();
            
            
            $mailerLinks = $mailerServiceObj->getLinks();
            $this->smarty->assign('mailerLinks',$mailerLinks);
            $this->smarty->assign('mailerName',MAILER_COMMON_ENUM::getSenderEnum($this->mailerName)["SENDER"]);
            
            $receiverProfilechecksum = JsAuthentication::jsEncryptProfilechecksum($val);
            $receiverechecksum = JsAuthentication::jsEncrypt($val,"");
            
            //Common Parameters required in mailer links
            $data["commonParamaters"] ="/".$receiverechecksum."/".$receiverProfilechecksum;
            $data["subject"]= "Update your 'Desired Partner Profile' to receive more Daily Recommendations"; 
            $data["body"] = "We have very limited new matches available for you based on the <a href='".$mailerLinks['MY_DPP'].$data['commonParamaters']."?From_Mail=Y&EditWhatNew=FocusDpp&stype=".$data['stypeMatch']."&logic_used=".$data.logic."'>'Desired Partner Profile'</a> mentioned on your profile.<br /><br />
You may like to <a href='".$mailerLinks['MY_DPP'].$data['commonParamaters']."?From_Mail=Y&EditWhatNew=FocusDpp&stype=".$data['stypeMatch']."&logic_used=".$data.logic."'>review your Desired Partner Profile</a> and check if you can relax some of your preferences to receive more matches. <br /><br />You should ideally have 500 Mutual Matches to be receiving 20 matches every day (The number of Mutual Matches you have is displayed on top of 'Desired Partner Profile' page),<br /><br />
You may also want some of the matches you can have a look at your Desired Partner Matches, just in case you missed them:<br /><br />
<a href='".$mailerLinks['SUGGESTED_MATCHES'].$data['commonParamaters']."?From_Mail=Y&stype=".$data['stypeMatch']."'>Desired Partner Matches</a>";
            $data["showDpp"]= 1;
            $data["mailSentDate"] = date("Y-m-d H:i:s");
            $subject ='=?UTF-8?B?' . base64_encode($data["subject"]) . '?=';
            $this->smarty->assign('data',$data);
            
            $mailerServiceObj->loadPartials();
            $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
            $status = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$val);
            $lowMatchesCheckObj->updateSent($val,$status);
        }
  }

}
