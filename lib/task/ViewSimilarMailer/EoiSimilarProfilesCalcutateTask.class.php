<?php

/* This cron calculates profiles to send in similar profiles mailer
 */

class EoiSimilarProfilesCalculateTask extends sfBaseTask {

    protected function configure() {
        $this->showTime = time();
        $this->addArguments(array(
          new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
          new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        ));
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'jeevansathi'),
        ));
        $this->namespace = 'vsm';
        $this->name = 'EoiSimilarProfilesCalculateTask';
        $this->briefDescription = 'Calculate profiles for sending in ecm';
        $this->detailedDescription = <<<EOF
The [EoiSimilarProfilesCalculateTask|INFO] task does things.
Call it with:

  [php symfony vsm:EoiSimilarProfilesCalculateTask totalScripts currentScripts |INFO]
EOF;
    }

    /**
     * @return void
     * @access protected
     */
    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);
        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number

        $profilesToSendMailObj = new viewSimilar_VSP_MAILER_PROFILES_TO_BE_SENT();
        $profileArr = $profilesToSendMailObj->getProfilesToSendMail($totalScripts, $currentScript);

        $vspLibObj = new ViewSimilarProfilesMailer();
        foreach ($profileArr as $key => $val) {
            if($key%99<50){
                $noOfEois = $val['INTERESTS_SENT'];
                $typeOfEois = $val['TYPE'];
                unset($val['INTEREST_SENT']);
                unset($val['TYPE']);
                $calculatedProfiles = $vspLibObj->getSimilarProfilesForMailer($key, $val);
                if (count($calculatedProfiles) > 0) {
                    $MailerTableObj = new viewSimilar_MAILER();
                    $MailerTableObj->insertProfiles($key,$calculatedProfiles,$noOfEois,$typeOfEois);
                }
            }
            $profilesToSendMailObj->updateIsCalculated($key);
        }
    }

}
