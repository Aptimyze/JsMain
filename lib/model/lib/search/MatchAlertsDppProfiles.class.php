<?php

class MatchAlertsDppProfiles extends PartnerProfile {
        /**
        * @private LAST_LOGGEDIN [No. of days in which we consider for last logged in matches]
        */
	private $LAST_LOGGEDIN = 15; 
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
            $educationLevel = FieldMap::getFieldLabel("degree_grouping", '', 1);
            $dppEduArr = explode(',',$dppEducation);
            foreach($educationLevel as $level=>$degrees){
                $levelDegrees = array_flip(explode(' , ',trim($degrees,' ')));
                foreach($dppEduArr as $key=>$dppDegree){
                    if(array_key_exists(trim($dppDegree,' '), $levelDegrees)){
                        $minLevel = $level;
                        break;
                    }
                }
                if($minLevel!='')
                    break;
            }
            $educationGroups = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new", '', 1);
            $categoryArrAlreadyPut = array();
            foreach($educationGroups as $category=>$degrees){
                $categoryDegrees = array_flip(explode(',',$degrees));
                foreach($dppEduArr as $key=>$dppDegree){
                     if(array_key_exists(trim($dppDegree,' '), $categoryDegrees)){
                         if($minLevel == 'PG'){
                           if(!in_array($category, $categoryArrAlreadyPut))
                             foreach($categoryDegrees as $key=>$value){
                                 if(array_key_exists($key,array_flip(explode(' , ',trim($educationLevel['PG'],' '))))){
                                     $finalString.=','.$key;
                                     $categoryArrAlreadyPut[]=$category;
                                 }
                             }
                         }
                         else if(!in_array($category, $categoryArrAlreadyPut)){
                             $finalString.=','.$degrees;
                             $categoryArrAlreadyPut[] = $category;
                         }
                     }
                }
            }
            return trim($finalString,',');
        }
        
        public function getRelaxedOccupation($occupation){
            $occValues = explode(',',$occupation);
            $occCheckArr = array(13,33,57,35,34,36);
            $occNotArrCheck = array(13,36,44,37,41);
            foreach($occValues as $key=>$value){
                if(in_array($value,$occCheckArr)){
                  $finalOccArr['occ']=$occupation;
                  return $finalOccArr;
                }
            }
            foreach($occNotArrCheck  as $key => $value){
                if(!in_array($value, $occValues))
                   $finalOccArr['notOcc'].= ','.$value;
            }
            $finalOccArr['Occ']= '';
            $finalOccArr['notOcc'] = trim($finalOccArr['notOcc'],',');
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
            if($mtongueFlag==1)
                $finalMtongue.= ','.implode(',',$allHindiMtongues);
            return trim($finalMtongue,',');
        }

}
?>

