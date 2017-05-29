<?php

//This class is for the new matches mailers. It is used in the matches generation logic and also on landing on the search page through "See New Matches" link in the mailer

class NewMatchesDppMailer extends PartnerProfile {

        protected $pid;
        protected $loggedInProfileObj;
        public  $forwardCriteria = array("GENDER","LAGE","HAGE","LHEIGHT","HHEIGHT","INCOME","LINCOME","HINCOME","LINCOME_DOL","HINCOME_DOL","MSTATUS","RELIGION","MTONGUE","CASTE","EDU_LEVEL_NEW","OCCUPATION","CITY_RES","CITY_INDIA");
        public function __construct($loggedInProfileObj) {
                if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
                        $this->pid = $loggedInProfileObj->getPROFILEID();
                $this->loggedInProfileObj = $loggedInProfileObj;
                parent::__construct($loggedInProfileObj);
        }

        public function setSearchCriteria() {
                $this->getDppCriteria();
        }

}

?>
