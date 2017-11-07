<?php

class cronCampaignsRegUpdateActivatedTask extends sfBaseTask {

        protected $screenDate = 3;

        protected function configure() {
                $this->namespace = 'cron';
                $this->name = 'cronCampaignsRegUpdateActivated';
                $this->briefDescription = 'This cron fetches data to be displayed for LocationAgeRegistration Mis';
                $this->detailedDescription = <<<EOF
The [cronCampaignsRegistrationMis|INFO] ADD DESCRIPTION.
Call it with:

  [php symfony cron:cronCampaignsRegUpdateActivated] 
EOF;
                $this->addOptions(array(
                    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
                ));
        }

        protected function execute($arguments = array(), $options = array()) {
                if (!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

                $jprofileObj = new JPROFILE('newjs_slave');
                $registerDate = date('Y-m-d', strtotime('- ' . $this->screenDate . ' day'));
                $profiles = $jprofileObj->getProfileCampaingnRegistationData($registerDate." 00:00:00");
                $qualityUpdate = new MIS_CAMPAIGN_KEYWORD_TRACKING(); // update quality column in CAMPAIGN keyword tracking MIS
                foreach($profiles as $profile){
                        $havePhoto = "N";
                        if($profile["HAVEPHOTO"] == "Y"){
                                $havePhoto = "Y";
                        }
                        $qualityUpdate->updateActivatedNPhoto($profile["PROFILEID"],$profile["ACTIVATED"],$havePhoto);
                }
        }

}
