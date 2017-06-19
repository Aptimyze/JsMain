<?php

class jsValidatorDateOfBirth extends sfValidatorBase {

        const MALE_MIN_AGE = 21;
        const FEMALE_MIN_AGE = 18;
        const MAX_AGE = 71;

        protected function configure($arrOptions = array(), $arrMessages = array()) {
                $this->addOption('dtofbirth', $arrOptions[DTOFBIRTH]);
        }

        protected function doClean($value) {

                $dtofbirth = $this->getOption("dtofbirth");
                $Gender = $this->getOption("Gender");

                $arrMale = array("M", "MALE", "1", "m", "Male", "male");
                $allowedMinAge = in_array($Gender, $arrMale) ? self::MALE_MIN_AGE : self::FEMALE_MIN_AGE;
                $date1=date('Y-m-d', strtotime("-".$allowedMinAge." Years"));
                $tempArr=explode('-', $dtofbirth);
                $date2 = date("Y-m-d", mktime(0, 0, 0, $tempArr[1], $tempArr[2], $tempArr[0]));
                if ($date2>$date1) {
                        throw new sfValidatorError($this, ErrorHelp::$ERR_DOB_MINAGE["minAge"], array('value' => $dtofbirth));
                }
                $dateMax=date('Y-m-d', strtotime("-".self::MAX_AGE." Years"));
                if ($date2<$dateMax) {
                        throw new sfValidatorError($this, ErrorHelp::$ERR_DOB_MAXAGE["maxAge"], array('value' => $dtofbirth));
                }
                $clean = $date2;
                return $clean;
        }

}

?>
