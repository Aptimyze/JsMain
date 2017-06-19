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
            $data["subject"]= "Get more matching profiles in your Inbox by broadening your Desired Partner Profile"; 
            $data["body"]="We always strive to send the most relevant matches to you based on your Desired Partner Profile. It looks like your Desired Partner Profile could be too strict, which may reduce the number of interests and relevant daily recommendations you receive.<br><br>
Please <a href='".$mailerLinks['MY_DPP'].$data['commonParamaters']."?From_Mail=Y&EditWhatNew=FocusDpp&stype=".$data['stypeMatch']."&logic_used=".$data.logic."'>Edit Desired Partner Profile</a> to get more relevant matches.<br><br>
If you require any assistance with your editing your Desired Partner Profile, do look out for suggestions (available on Desktop Site as of now) while you are editing a section. You may also call us at our toll-free number or Email us at help@jeevansathi.com, so that we can help you set a Desired Partner Profile which best suits your needs.";
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
