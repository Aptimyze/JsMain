<?php

class MatchAlertsDppProfiles extends PartnerProfile {
        /**
        * @private LAST_LOGGEDIN [No. of days in which we consider for last logged in matches]
        */
	private $LAST_LOGGEDIN = 15; 
	private $VERIFIED_CHECK = 2; 
	private $LAST_LOGGEDIN_STARTFROM = "1960-01-01 00:00:00"; 
        private $getFromCache = 1;
        /**
         * 
         * @param type $loggedInProfileObj
         */
        public function __construct($loggedInProfileObj) {
                parent::__construct($loggedInProfileObj);
        }

        /**
         * 
         * @return type
         */
        public function getSearchCriteria($limit,$sort) {
                parent::getDppCriteria('','',$this->getFromCache);
                $this->rangeParams .= ",LAST_LOGIN_DT";
                $this->setRangeParams($this->rangeParams);
                $this->setSortParam($sort, $limit);
                if($sort == SearchSortTypesEnums::SortByTrendsScore){
                        $endDate = date("Y-m-d H:i:s", strtotime("now"));
                        $startDate = date("Y-m-d 00:00:00", strtotime($endDate) - $this->LAST_LOGGEDIN*24*3600);
                        $this->setLLAST_LOGIN_DT($startDate);
                        $this->setHLAST_LOGIN_DT($endDate);
                }elseif($sort != SearchSortTypesEnums::FullDppWithReverseFlag){
                        $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->LAST_LOGGEDIN*24*3600);
                        $this->setLLAST_LOGIN_DT($this->LAST_LOGGEDIN_STARTFROM);
                        $this->setHLAST_LOGIN_DT($endDate);
                }
                
                //just joined 2 day check
                $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->VERIFIED_CHECK*24*3600);
                $this->setLVERIFY_ACTIVATED_DT($this->LAST_LOGGEDIN_STARTFROM);
                $this->setHVERIFY_ACTIVATED_DT($endDate);
                
                $this->setShowFilteredProfiles('N');
        }
        public function getRelaxedSearchCriteria($limit,$sort) {
                $relaxedMtongue = $this->getRelaxedMtongues($this->getMTONGUE());
                $this->setMTONGUE($relaxedMtongue);
                
                $dppSetCastes = $this->getCASTE();
                if($dppSetCastes!=''){
                    $ownCaste = $this->loggedInProfileObj->getCASTE();
                    $relaxedCastes = $ownCaste.','.RelatedCastes::$relatedCasteArr[$ownCaste];
                    $this->setCASTE(trim($dppSetCastes.",".$relaxedCastes,','),1);
                }
                
                $dppEducation = $this->getEDU_LEVEL_NEW();
                if($dppEducation != ''){
                    $relaxedEducation = $this->getRelaxedEducation($dppEducation);
                    $this->setEDU_LEVEL_NEW($relaxedEducation);
                }
                
                $occupation = $this->getOCCUPATION();
                if($occupation!=''){
                    $relaxedOccupation = $this->getRelaxedOccupation($occupation);
                    $this->setOCCUPATION($relaxedOccupation['occ']);
                    if($relaxedOccupation['occ']=='')
                        $this->setOCCUPATION_GROUPING('');
                    if($relaxedOccupation['notOcc']!='')
                        $this->setOCCUPATION_IGNORE($relaxedOccupation['notOcc']);
                }

                
                if($this->getLINCOME() || $this->getLINCOME_DOL()){
                    if($this->getLINCOME()){
                            $rArr["minIR"] = $this->getLINCOME() ;
                            $rArr["maxIR"] = "19" ;
                    }
                    else{       
                            $rArr["minIR"] = "0" ;
                            $rArr["maxIR"] = "19" ;
                    }    
                    if($this->getLINCOME_DOL()){
                            $dArr["minID"] = $this->getLINCOME_DOL() ;
                            $dArr["maxID"] = "19" ;
                    }
                    else{
                            $dArr["minID"] = "0" ;
                            $dArr["maxID"] = "19" ;
                    }
                    $incomeMapObj = new IncomeMapping($rArr,$dArr);
                    $incomeMapArr = $incomeMapObj->incomeMapping();
                    $Income = $incomeMapArr['istr'];
                    $this->setINCOME(str_replace("'", "",$Income));
                }
                
                $city = $this->getCITY_RES();
                if($city != ''){
                    $relaxedCity = $this->getRelaxedCity($city);
                    $this->setCITY_RES($relaxedCity);
                    $this->setCITY_INDIA($relaxedCity);
                }

                $smoking = $this->getSMOKE();

                if($smoking!=''){
                    $relaxedSmoking = $this->getRelaxedSmoking($smoking);
                    $this->setSMOKE($relaxedSmoking);
                }

                $drinking = $this->getDRINK();

                if($drinking!=''){
                    $relaxedDrinking = $this->getRelaxedDrinking($drinking);
                    $this->setDRINK($relaxedDrinking);
                }

                $Hheight = $this->getHHEIGHT();

                if ( $Hheight )
                {
                    $relaxedHheight = $this->getRelaxedHHeight($Hheight);
                    $this->setHHEIGHT($relaxedHheight);   
                }
        }
        /**
         * Function to set sort order and results count
         * @param type $sort
         * @param type $limit
         */
        public function setSortParam($sort, $limit) {
                $this->setSORT_LOGIC($sort);
                $this->setNoOfResults($limit);
        }
        
        public function getRelaxedEducation($dppEducation){

            // 0 : b-professional 1 : b-medical 2 : b-others 3 : m-professional 4 : m-medical 5 : m-others
            // defining mapping here because these are function specific
            $educationDegreesMapping = array('0'=>'35,3,4,34,6','1'=>'25,28,26,32,17','2'=>'1,2,5,33,38,39,40','3'=>'36,13,14,29,18,7,37,8,10,16,20,42,21','4'=>'19,30,43,31','5'=>'11,12,15,41');

            $maleDppEducationMapping = array('0'=>'0,2','1'=>'1','2'=>'2','3'=>'3,5,0,2','4'=>'4,1','5'=>'5,2');

            $femaleDppEducationMapping = array('0'=>'0,3','1'=>'1,4','2'=>'2,0,5,3','3'=>'3','4'=>'4','5'=>'5,3,0');

            $dppEduArr = explode(',',$dppEducation);

            $notFoundinMapping ='';

            $educationDegreesMappingArray = array();

            if ( is_array($dppEduArr))
            {
                foreach ($dppEduArr as $key => $value) {
                    $isDegreeFound = False;
                    foreach ($educationDegreesMapping as $educationDegreesMappingKey => $educationDegreesMappingValue) 
                    {
                        if ( in_array($value,explode(',',$educationDegreesMappingValue)))
                        {
                            $isDegreeFound = True;
                            $educationDegreesMappingArray[] = $educationDegreesMappingKey;
                            break;
                        }
                    }

                    if ( !$isDegreeFound )
                    {
                        $notFoundinMapping .= $value.',';
                    }
                }
            }

            $relaxedEducation = '';

            if ( is_array($educationDegreesMappingArray) )
            {
                $educationDegreesMappingArrayUnique = array_unique($educationDegreesMappingArray);

                foreach ( $educationDegreesMappingArrayUnique as $value) 
                {
                    $relaxedEducationDegreesMappingArray = array();
                    if ( $this->loggedInProfileObj->getGENDER() == 'M' )
                    {
                        $relaxedEducationDegreesMappingArray = explode(',',$maleDppEducationMapping[$value]);
                    }
                    else
                    {
                        $relaxedEducationDegreesMappingArray = explode(',',$femaleDppEducationMapping[$value]);
                    }

                    foreach ($relaxedEducationDegreesMappingArray as $relaxedEducationDegreesMappingValue) 
                    {
                        $relaxedEducation .= $educationDegreesMapping[$relaxedEducationDegreesMappingValue].',';
                    }
                }
            }
            $finalRelaxedEducation = rtrim($relaxedEducation.$notFoundinMapping,',');
            return $finalRelaxedEducation;
        }
        
        public function getRelaxedOccupation($occupation){
            $occValues = explode(',',$occupation);
            $occCheckArr = array(13,52,3,33,57,24,70,53,74,35,56,34,44,36,41,60,58,31);
            $occNotArrCheck = array(13,52,3,44,36,41);
            foreach($occValues as $key=>$occupationValue){
                if(!in_array($occupationValue,$occCheckArr)){
                    
                    foreach($occNotArrCheck  as $key => $value){
                    if(!in_array($value, $occValues))
                        $finalOccArr['notOcc'].= ','.$value;
                    }
                    $finalOccArr['occ']= '';      
                    $finalOccArr['notOcc'] = trim($finalOccArr['notOcc'],',');
                    return $finalOccArr;
                }
            }
            $finalOccArr['occ']= $occupation;           
            return $finalOccArr;            
        }
        
        public function getRelaxedCity($city){
            $cityValues = explode(',',$city);
            $filledMumbai=0;
            $filledNcr=0;
            $mumbaiRegion = TopSearchBandConfig::$mumbaiRegion;
            $mumbaiRegionValues = explode(',',TopSearchBandConfig::$mumbaiRegion);
            $delhiNcrCities = FieldMap::getFieldLabel("delhiNcrCities", '', 1);
            foreach($cityValues as $key=>$value){
                if(in_array(trim($value,' '),$delhiNcrCities)){
                  if(!$filledNcr){
                    $filledNcr =1;
                    $finalCity.=','.implode(',',$delhiNcrCities);
                  }
                }
                else if(in_array($value, $mumbaiRegionValues)){
                    if(!$filledMumbai){
                        $filledMumbai=1;
                        $finalCity.=','.$mumbaiRegion;
                    }
                }
                else
                  $finalCity.=','.$value;  
            }
            return trim($finalCity,',');
        }
        
        public function getRelaxedMtongues($mtongue){
            $removeValuesIfNotPresent = array("7"); // Remove castes if they are not filled in but added as part of group currently bihari
            $mtongueValues = explode(',',$mtongue);
            $allHindiMtongues = FieldMap::getFieldLabel("allHindiMtongues", '', 1);
            $checkHindiMtongues = array_flip($allHindiMtongues);
            $mtongueFlag = 0;
            foreach($mtongueValues as $key=>$value){
                if(array_key_exists(trim($value,' '), $checkHindiMtongues))
                    $mtongueFlag = 1;
                else
                    $finalMtongue.=','.$value;
            }
            foreach($removeValuesIfNotPresent as $val){
                if(!in_array($val, $mtongueValues)){ // remove bihari mtongue if not already present
                        unset($checkHindiMtongues[$val]);
                        $allHindiMtongues = array_flip($checkHindiMtongues);
                }
            }
            if($mtongueFlag==1)
                $finalMtongue.= ','.implode(',',$allHindiMtongues);
            return trim($finalMtongue,',');
        }
        /**
         * returns relaxed smoking
         * @param  string $smoking array which contains comma seprated values.
         * @return empty string or original smoking string
         */
        public function getRelaxedSmoking($smoking)
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
        public function getRelaxedDrinking($drinking)
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
        public function getRelaxedHHeight($hheight)
        {
            $registerationHeight = $this->loggedInProfileObj->getHEIGHT();
            return $registerationHeight>$hheight?$registerationHeight:$hheight;
        }
}
?>

