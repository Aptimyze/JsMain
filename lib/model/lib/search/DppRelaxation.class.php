<?php

class DppRelaxation {

        private $pid;
        private $loggedInProfileObj;

        public function __construct($loggedInProfileObj) {
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->pid = $this->loggedInProfileObj->getPROFILEID();
        }

        public function getRelaxedMTONGUE($mtongue) {
                $mtongueValues = explode(',', $mtongue);
                $allHindiMtongues = FieldMap::getFieldLabel("allHindiMtongues", '', 1);
                $checkHindiMtongues = array_flip($allHindiMtongues);
                $mtongueFlag = 0;
                foreach ($mtongueValues as $key => $value) {
                        if (array_key_exists(trim($value, ' '), $checkHindiMtongues))
                                $mtongueFlag = 1;
                        else
                                $finalMtongue.=',' . $value;
                }
                if ($mtongueFlag == 1)
                        $finalMtongue.= ',' . implode(',', $allHindiMtongues);
                return trim($finalMtongue, ',');
        }

        public function getRelaxedCASTE($dppcaste) {
                $casteString ="" ;
                if($dppcaste != ""){
                        $ownCaste = $this->loggedInProfileObj->getCASTE();
                        $relaxedCastes = $ownCaste . ',' . RelatedCastes::$relatedCasteArr[$ownCaste];
                        $casteString = trim($dppcaste . "," . $relaxedCastes, ',');
                }
                return $casteString;
        }

        public function getRelaxedEDUCATION($dppEducation) {
                if($dppEducation == ""){
                        return "";
                }
                // 0 : b-professional 1 : b-medical 2 : b-others 3 : m-professional 4 : m-medical 5 : m-others
                // defining mapping here because these are function specific
                $educationDegreesMapping = array('0' => '35,3,4,34,6,45,52,53,54', '1' => '25,28,26,32,17,46,47', '2' => '1,2,5,33,38,39,40,44,48,49,50,51', '3' => '36,13,14,29,18,7,37,8,10,16,20,42,21,45,52,53,54', '4' => '19,30,43,31,46,47', '5' => '11,12,15,41,44,48,49,50,51');

                $maleDppEducationMapping = array('0' => '0,2', '1' => '1', '2' => '2', '3' => '3,5,0,2', '4' => '4,1', '5' => '5,2');

                $femaleDppEducationMapping = array('0' => '0,3', '1' => '1,4', '2' => '2,0,5,3', '3' => '3', '4' => '4', '5' => '5,3,0');

                $dppEduArr = explode(',', $dppEducation);

                $notFoundinMapping = '';

                $educationDegreesMappingArray = array();

                if (is_array($dppEduArr)) {
                        foreach ($dppEduArr as $key => $value) {
                                $isDegreeFound = False;
                                foreach ($educationDegreesMapping as $educationDegreesMappingKey => $educationDegreesMappingValue) {
                                        if (in_array($value, explode(',', $educationDegreesMappingValue))) {
                                                $isDegreeFound = True;
                                                $educationDegreesMappingArray[] = $educationDegreesMappingKey;
                                                break;
                                        }
                                }

                                if (!$isDegreeFound) {
                                        $notFoundinMapping .= $value . ',';
                                }
                        }
                }

                $relaxedEducation = '';

                if (is_array($educationDegreesMappingArray)) {
                        $educationDegreesMappingArrayUnique = array_unique($educationDegreesMappingArray);

                        foreach ($educationDegreesMappingArrayUnique as $value) {
                                $relaxedEducationDegreesMappingArray = array();
                                if ($this->loggedInProfileObj->getGENDER() == 'M') {
                                        $relaxedEducationDegreesMappingArray = explode(',', $maleDppEducationMapping[$value]);
                                } else {
                                        $relaxedEducationDegreesMappingArray = explode(',', $femaleDppEducationMapping[$value]);
                                }

                                foreach ($relaxedEducationDegreesMappingArray as $relaxedEducationDegreesMappingValue) {
                                        $relaxedEducation .= $educationDegreesMapping[$relaxedEducationDegreesMappingValue] . ',';
                                }
                        }
                }
                $finalRelaxedEducation = rtrim($relaxedEducation . $notFoundinMapping, ',');
                return $finalRelaxedEducation;
        }

        public function getRelaxedOCCUPATION($occupation) {
                $occValues = explode(',', $occupation);
                $occCheckArr = array(13, 52, 3, 33, 57, 24, 70, 53, 74, 35, 56, 34, 44, 36, 41, 60, 58, 31);
                $occNotArrCheck = array(13, 52, 3, 44, 36, 41);
                foreach ($occValues as $key => $occupationValue) {
                        if (!in_array($occupationValue, $occCheckArr)) {

                                foreach ($occNotArrCheck as $key => $value) {
                                        if (!in_array($value, $occValues))
                                                $finalOccArr['notOcc'].= ',' . $value;
                                }
                                $finalOccArr['occ'] = '';
                                $finalOccArr['notOcc'] = trim($finalOccArr['notOcc'], ',');
                                return $finalOccArr;
                        }
                }
                $finalOccArr['occ'] = $occupation;
                return $finalOccArr;
        }

        public function getRelaxedINCOME($lincome, $lincomeDol) {
                if ($lincome || $lincomeDol) {
                        if ($lincome) {
                                $rArr["minIR"] = $lincome;
                                $rArr["maxIR"] = "19";
                        } else {
                                $rArr["minIR"] = "0";
                                $rArr["maxIR"] = "19";
                        }
                        if ($lincomeDol) {
                                $dArr["minID"] = $lincomeDol;
                                $dArr["maxID"] = "19";
                        } else {
                                $dArr["minID"] = "0";
                                $dArr["maxID"] = "19";
                        }
                        $incomeMapObj = new IncomeMapping($rArr, $dArr);
                        $incomeMapArr = $incomeMapObj->incomeMapping();
                        $Income = $incomeMapArr['istr'];
                        return str_replace("'", "", $Income);
                }
                return "";
        }

        public function getRelaxedCITY_RES($city) {
                if($city == ""){
                        return "";
                }
                $cityValues = explode(',', $city);
                $filledMumbai = 0;
                $filledNcr = 0;
                $mumbaiRegion = TopSearchBandConfig::$mumbaiRegion;
                $mumbaiRegionValues = explode(',', TopSearchBandConfig::$mumbaiRegion);
                $delhiNcrCities = FieldMap::getFieldLabel("delhiNcrCities", '', 1);
                foreach ($cityValues as $key => $value) {
                        if (in_array(trim($value, ' '), $delhiNcrCities)) {
                                if (!$filledNcr) {
                                        $filledNcr = 1;
                                        $finalCity.=',' . implode(',', $delhiNcrCities);
                                }
                        } else if (in_array($value, $mumbaiRegionValues)) {
                                if (!$filledMumbai) {
                                        $filledMumbai = 1;
                                        $finalCity.=',' . $mumbaiRegion;
                                }
                        } else
                                $finalCity.=',' . $value;
                }
                return trim($finalCity, ',');
        }
        /**
         * returns relaxed smoking
         * @param  string $smoking array which contains comma seprated values.
         * @return empty string or original smoking string
         */
        public function getRelaxedSMOKE($smoking)
        {
            $smokingArray = explode(',',$smoking);
            if ( in_array('N',$smokingArray) && in_array('NS',$smokingArray) && count($smokingArray) == 2 )
            {
                return $smoking;
            }
            return '';
        }
        /**
         * returns relaxed drinking
         * @param  string $drinking array which contains comma seprated values.
         * @return string empty string or original smoking string
         */
        public function getRelaxedDRINK($drinking)
        {
            $drinkingArray = explode(',',$drinking);
            if ( in_array('N',$drinkingArray) && in_array('NS',$drinkingArray) && count($drinkingArray) == 2 )
            {
                return $drinking;
            }
            return '';
        }
        /**
         * returns height depending on max of desired partner or own height
         * @param  int $hheight 
         * @return int          height
         */
        public function getRelaxedHHEIGHT($hheight)
        {
            $registerationHeight = $this->loggedInProfileObj->getHEIGHT();
            return $registerationHeight>$hheight?$registerationHeight:$hheight;
        }
}

?>
