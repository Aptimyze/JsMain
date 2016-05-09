<?php
/*This class is used to handle the kundlialerts*/
class KundliAlerts
{
        public function __construct($dbname='')
        {
                $this->dbname = $dbname;
        }

        public function getProfilesWithOutSorting($profileId,$weekFlag="")
        {
                $matchAlertObj = new KUNDLI_ALERT_KUNDLI_CONTACT_CENTER($this->dbname);
                $output = $matchAlertObj->getKundliAlertProfiles($profileId);
                return $output;
        }

        public function getKundliAlertProfile($profileId,$skipProfile='',$limit='')
        {
                $kundliAlertObj = new KUNDLI_ALERT_KUNDLI_CONTACT_CENTER($this->dbname);
                $output = $kundliAlertObj->getKundliMatchProfiles($profileId,$skipProfile);
                return $output;
        }

}
