<?php

/* * ************************************************************************************************************************************
 *
 * DESCRIPTION   : Cron script, scheduled daily to send an email
 *  with username to the user who installed the app
 *
CREATE TABLE APP_UNINSTALL (
  PROFILEID int(11) unsigned NOT NULL,
  REGISTRATION_ID varchar(255) ,
  ENTRY_DT datetime ,
  MAILER_SENT char(1) 
);
CREATE INDEX ENTRY_DT ON APP_UNINSTALL (ENTRY_DT);
 * 
 * ************************************************************************************************************************************* */

class AppUninstallMailerTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name', 'operations'),
        ));

        $this->namespace = "mailer";
        $this->name = "AppUninstallMailer";
        $this->briefDescription = "AppUninstallFeedback";
        $this->detailedDescription = <<<EOF
            The [AppUninstallFeedbackMailer|INFO] task does things.
            Call it with:[php symfony mailer:AppUninstallMailer|INFO]
            This Task sends emails to those profiles who have uninstalled the JS app.            
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $appUninstallObj = new NOTIFICATION_APP_UNINSTALL('crm_slave');
        //Going to fetch all profile ID's who have uninstalled the app and mailer not sent
        $uninstallProfileIds = $appUninstallObj->getProfileIdsForMailer("", "");
        if ($uninstallProfileIds) {
            $profileidArr = array_values($uninstallProfileIds);
            $jprofileObj = new JPROFILE('crm_slave');
            //Going to fetch username and emailID for profileIds obtained above
            $profileDetails = $jprofileObj->getProfileSelectedDetails($profileidArr, "PROFILEID, USERNAME, EMAIL");
            $nameOfUserObj = new incentive_NAME_OF_USER();
            //Going to fetch Name for profileIds obtained
            $usernameArr = $nameOfUserObj->getName($profileidArr); 
            foreach ($profileDetails as $profileid => $details) {
                unset($username);
                unset($to);
                $username=$usernameArr[$profileid];
                $to = $profileDetails[$profileid]["EMAIL"];
                $from = "info@jeevansathi.com";
                $subject = "Wish you could have stayed longer";
                $msgBody = "Dear $username,<br/><p>
\nThank you for downloading Jeevansathi app, but it looks like your experience on the app was not as per expectations. We are sad you had to uninstall the app.
It would really help us though if you can take a minute of your time and let us know what led you to uninstall the app.</p>
<a href='https://www.surveymonkey.com/r/DHNXLQV'>Click here</a>
<p>Our teams always strive hard to provide great experience to you. Your feedback will be very valuable in this regard.</p><br/> 
Warm Regards,<br/> 
Team Jeevansathi";  
                SendMail::send_email($to, $msgBody, $subject, $from, '', '', '', '', '');
            }
            //Going to update profiles where mailer sent
            //$appUninstallObj->updateEntryForSent($profileidArr);          Commented assuming that cron will run only once everyday
        }
    }
}
