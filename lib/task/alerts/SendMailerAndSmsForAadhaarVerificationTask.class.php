<?php
/*
 * Author: Ankit Shukla
 * This task send the aadhaar verification mailer
 */
 class SendMailerAndSmsForAadhaarVerificationTask extends sfBaseTask
{
     
    protected function configure()
    {
//        $this->addArguments(array(
//            new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
//            new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
//        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
        ));

        $this->namespace        = 'alert';
        $this->name             = 'SendMailerAndSmsForAadhaarVerification';
        $this->briefDescription = 'send the mail to alert jeevansathi users for aadhaar verification';
        $this->detailedDescription = <<<EOF
    Call it with:
      [php symfony alert:SendMailerAndSmsForAadhaarVerification] 
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $aadhaarLibObj = new aadharVerification('newjs_slave');
        $entry_date = date('Y-m-d', strtotime('-3 days'));
        $login_date = date('Y-m-d', strtotime('-15 days'));
        $sendEveryDays = 15;
        $profileToSendMailer = $aadhaarLibObj->getProfilesForAadhaarVerificationMailer($entry_date,$login_date,$sendEveryDays);
        $mailerLogObj = new PROFILE_VERIFICATION_AADHAR_VERIFICATION_MAILER_LOG();
        
        foreach($profileToSendMailer as $profileId=>$arr){
            
            $email_sender = new EmailSender(MailerGroup::AADHAAR_VERIFICATION,1886);
            $emailTpl = $email_sender->setProfileId($profileId);
            $smartyObj = $emailTpl->getSmarty();
            $username = $arr['USERNAME'];
            $basicDetLink = sfConfig::get('app_site_url')."/P/viewprofile.php?username=" . $username . "&ownview=1&EditWhatNew=Basic";
            
            $smartyObj->assign("username",$username);
            $smartyObj->assign("profileId",$profileId);
            
            $email_sender->send();
            
            $smsViewer = new InstantSMS("AADHAAR_VERIFICATION",$profileId);
            $smsViewer->send();
            $mailerLogObj->insertProfile($profileId);
        }
        
        $mailerLogObj->truncateTable();
    }

}
