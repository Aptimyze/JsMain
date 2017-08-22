<?php

/* This cron populates profiles data to send similar profiles mailer
 */

class EoiSimilarProfilesPopulateTask extends sfBaseTask {

    protected function configure() {
        //$this->showTime = time();
        //$this->addArguments(array( 	));
        $this->namespace = 'vsm';
        $this->name = 'EoiSimilarProfilesPopulate';
        $this->briefDescription = 'Populate profiles for sending ecm';
        $this->detailedDescription = <<<EOF
Call it with:

  [php symfony vsm:EoiSimilarProfilesPopulate |INFO]
EOF;
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
        ));
    }

    /**
     * populate tables to send mailer
     * @return void
     * @access protected
     */
    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);
        $date = date('Y-m-d', strtotime("-1 week"));
        //get profiles from CONTACTS table
        $shards = JsDbSharding::getShardListSlave();

        foreach ($shards as $key => $shardName) {

            $contactsObj = new newjs_CONTACTS($shardName);
            $profilesWithType = $contactsObj->getProfilesWhoHaveContactedInLastFewDays($date, $key);

            if (count($profilesWithType) > 0) {
                foreach ($profilesWithType as $sender => $val) {
                    $populatedProfilesObj = new ViewSimilarProfilesMailer();
                    $result = $populatedProfilesObj->getProfilesForAUserToPopulateTable($val);
                    $profilesToInsertForAUser = $result['profiles'];

                    $typeOfEoi = $result['type'];
                    $noOfEoi = count(explode(',', $val['Receivers']));

                    //insert profiles into populate table
                    if (count($profilesToInsertForAUser) > 0) {
                        $mailerToBeSentObj = new viewSimilar_VSP_MAILER_PROFILES_TO_BE_SENT();
                        $mailerToBeSentObj->setProfilesToSendMail($sender, $profilesToInsertForAUser, $noOfEoi, $typeOfEoi);
                    }
                }
            }
        }
    }

}
