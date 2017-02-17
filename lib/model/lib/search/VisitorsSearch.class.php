<?php

/*
 * This class is a search class for Visitors 
 */
class VisitorsSearch extends SearchParamters
{
    
        private $filteredParams = array('LAGE','HAGE','MSTATUS','COUNTRY_RES','CITY_RES','RELIGION','MTONGUE','CASTE','INCOME');
        
        /**
		* @const SHOW_FILTERED_PROFILES we dont show filtered profiles at all.
		*
		*/
		const SHOW_FILTERED_PROFILES='N';
		
		  /**
        * @access private
        * @var String $m_sz_callType type of call like countOnly.
        */
		private $m_sz_callType;
		
		const VISITORS_LIMIT = 500;


        public function __construct($loggedInProfileObj)
        {
                parent::__construct();
                $this->possibleSearchParamters = SearchConfig::$possibleSearchParamters;
				$this->loggedInProfileObj= $loggedInProfileObj;
                $this->pid =  $this->loggedInProfileObj->getPROFILEID();
                $this->SORT_LOGIC = SearchSortTypesEnums::SortByVisitorsTimestamp;
				$this->showFilteredProfiles = self::SHOW_FILTERED_PROFILES;
                
        }
        
        
        public function getSearchCriteria($sz_callType='',$daysBefore=''){
			$this->m_sz_callType = $sz_callType;
			$skipContactedType = SkipArrayCondition::$VISITOR;
			$skipProfileObj    = SkipProfile::getInstance($this->pid);
			$skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
                        SkipProfile::unsetInstance($this->pid);
                        unset($skipProfileObj);
			$viewLogObj        = new VIEW_LOG_TRIGGER();
			$visitorsProfile   = $viewLogObj->getViewLogData($this->pid, $skipProfile,$daysBefore,self::VISITORS_LIMIT);
			if (is_array($visitorsProfile)){
				$profileIdArr = array_map(array($this,"extractProfileId"), $visitorsProfile);
				 $this->setProfilesToShow(implode(" ",$profileIdArr));
			}
			else{
				 $this->setProfilesToShow("0 0");
			}
			
			$this->getForwardFiltersCriteria($fromMailer);
		
		}
		
		private function extractProfileId($value)
		{
			return $value["VIEWER"];
		}
		/*
         * sets forward filter search criteria
         */
        private function  getForwardFiltersCriteria($fromMailer=''){
                if($this->loggedInProfileObj->getGENDER()=="M")
                        $this->setGENDER("F");
                elseif($this->loggedInProfileObj->getGENDER()=="F")
                        $this->setGENDER("M");
                $this->setGENDER($gender);
            $forwardCriteria = PredefinedSearchFactory::getSetterBy('PartnerProfile',$this->loggedInProfileObj);
            $forwardCriteria->getDppCriteria();
                $filtersObj = new NEWJS_FILTER();
                $filters = $filtersObj->fetchEntry($this->pid);
                
                if($filters['CITY_RES']=='Y' && $filters['COUNTRY_RES']!='Y')
                    $filters['COUNTRY_RES'] = 'Y';
                
                foreach($this->filteredParams as $k=>$v)	
                {
                    if($filters[$v] == 'Y' || (($v=='LAGE' || $v=='HAGE') && $filters['AGE']=='Y')){
                        eval('$tempVal = $forwardCriteria->get'.$v.'();');
                        if($tempVal)
                                eval('$this->set'.$v.'("'.$tempVal.'");');
                    }
                }
        }
        
      
}
