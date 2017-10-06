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
                $relaxedObj = new DppRelaxation($this->loggedInProfileObj);
                $userReligion = $this->pid = $this->loggedInProfileObj->getRELIGION();
                $relaxedMtongue = $relaxedObj->getRelaxedMTONGUE($this->getMTONGUE(),$userReligion);
                $this->setMTONGUE($relaxedMtongue);
                
                $CasteRelaxed = $relaxedObj->getRelaxedCASTE($this->getCASTE());
                $this->setCASTE($CasteRelaxed,1);
                
                $relaxedEducation = $relaxedObj->getRelaxedEDUCATION($this->getEDU_LEVEL_NEW());
                $this->setEDU_LEVEL_NEW($relaxedEducation);
                
                $occupation = $this->getOCCUPATION();
                if($occupation!=''){
                    $relaxedOccupation = $relaxedObj->getRelaxedOCCUPATION($occupation);
                    $this->setOCCUPATION($relaxedOccupation['occ']);
                    if($relaxedOccupation['occ']=='')
                        $this->setOCCUPATION_GROUPING('');
                    if($relaxedOccupation['notOcc']!='')
                        $this->setOCCUPATION_IGNORE($relaxedOccupation['notOcc']);
                }

                $incomeStr = $relaxedObj->getRelaxedINCOME($this->getLINCOME() , $this->getLINCOME_DOL());
                $this->setINCOME($incomeStr);
                
                $relaxedCity = $relaxedObj->getRelaxedCITY_RES($this->getCITY_RES());
                $this->setCITY_RES($relaxedCity,"",2);

                $relaxedSmoking = $relaxedObj->getRelaxedSMOKE($this->getSMOKE());
                $this->setSMOKE($relaxedSmoking);

                $relaxedDrinking = $relaxedObj->getRelaxedDRINK($this->getDRINK());
                $this->setDRINK($relaxedDrinking);

                $relaxedHheight = $relaxedObj->getRelaxedHHEIGHT($this->getHHEIGHT());
                $this->setHHEIGHT($relaxedHheight);

                //added diet relaxation
                $relaxedDiet = $relaxedObj->getRelaxedDIET($this->getDIET());
                $this->setDIET($relaxedDiet); 
                unset($relaxedObj);
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
}
?>

