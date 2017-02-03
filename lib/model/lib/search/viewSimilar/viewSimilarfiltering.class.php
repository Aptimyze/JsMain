<?php
/**
* @brief : This file will be handle view similar search result after expressing interest
* @author Akash Kumar
* @created 2015-08-21
*/

include_once(JsConstants::$docRoot."/profile/algoSuggestedProfiles.php");
include_once(JsConstants::$docRoot."/profile/connect.inc");


class viewSimilarfiltering extends SearchParamters
{
	CONST femaleProfile  = 'F';
	CONST maleProfile = 'M';
	CONST removeFilteredForAllPrivacy='X';
        CONST AgeConstantToBeSub=5;
        
        private $PARTNER_CASTE;
        private $PARTNER_RELIGION;
        private $PARTNER_COUNTRYRES;
        private $PARTNER_ELEVEL_NEW;
        private $PARTNER_INCOME;
        private $LPARTNER_LAGE;
        private $HPARTNER_LAGE;
        private $LPARTNER_HAGE;
        private $HPARTNER_HAGE;
        private $LPARTNER_LHEIGHT;
        private $HPARTNER_LHEIGHT;
        private $LPARTNER_HHEIGHT;
        private $HPARTNER_HHEIGHT;
        private $PARTNER_MSTATUS;
        private $PARTNER_CITYRES;
        private $PARTNER_HANDICAPPED;
        
        private $reverseParamsFemaleLoggedIn = array('PARTNER_MSTATUS','PARTNER_CITYRES','PARTNER_COUNTRYRES','PARTNER_HANDICAPPED','PARTNER_RELIGION','PARTNER_CASTE','LPARTNER_LAGE','HPARTNER_LAGE','LPARTNER_HAGE','HPARTNER_HAGE','LPARTNER_LHEIGHT','HPARTNER_LHEIGHT','LPARTNER_HHEIGHT','HPARTNER_HHEIGHT','PARTNER_ELEVEL_NEW');
    private $reverseParamsMaleLoggedIn = array('PARTNER_MSTATUS','PARTNER_CITYRES','PARTNER_COUNTRYRES','PARTNER_HANDICAPPED','PARTNER_RELIGION','PARTNER_CASTE','LPARTNER_LAGE','HPARTNER_LAGE','LPARTNER_HAGE','HPARTNER_HAGE','LPARTNER_LHEIGHT','HPARTNER_LHEIGHT','LPARTNER_HHEIGHT','HPARTNER_HHEIGHT','PARTNER_INCOME');

        private $whereParamViewSimilar = array('PARTNER_MTONGUE,PARTNER_CASTE,PARTNER_RELIGION,PARTNER_COUNTRYRES,PARTNER_MSTATUS,PARTNER_CITYRES,PARTNER_HANDICAPPED');
	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj,$profileObj,$removeFilters=0)
        {
		    parent::__construct();
                    if($loggedInProfileObj->getPROFILEID()){
			$this->loggedInProfileObj = $loggedInProfileObj;
			$this->ProfileObj=$profileObj;
			$this->pid =  $this->loggedInProfileObj->getPROFILEID();
			if(!$this->pid)
			{
				$context = sfContext::getInstance();
				$context->getController()->forward("static", "logoutPage"); //Logout page
				throw new sfStopException();
			}
			
			if($this->loggedInProfileObj->getGENDER()== self::femaleProfile)
				$this->setGENDER(self::maleProfile);
			else
				$this->setGENDER(self::femaleProfile);
                        if(!$removeFilters){
                            $this->setFilterForAge();
                            $this->setShowFilteredProfiles(self::removeFilteredForAllPrivacy);
                        }
                    }
                    else{
                        $this->ProfileObj=$profileObj;
                        if($profileObj->getGENDER() == self::femaleProfile)
                                $this->setGENDER(self::femaleProfile);
			else
				$this->setGENDER(self::maleProfile);
                    }
		}

	/*
	* This function will set the criteria for similar search.
	*/
	public function getProfilesParameter($searchId='',$channel='')
	{ 
                if($this->loggedInProfileObj->getGENDER()=='F'){
                        $this->reverseParams = $this->reverseParamsFemaleLoggedIn;
                }
                else{
                        $this->reverseParams = $this->reverseParamsMaleLoggedIn;
                }

		$reverseCriteria = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$this->loggedInProfileObj);
                $reverseCriteria->getSearchCriteria();
                foreach($this->reverseParams as $k=>$v)
                {
                        eval('$tempVal = $reverseCriteria->get'.$v.'();');
                        if($tempVal)
                                eval('$this->set'.$v.'("'.$tempVal.'");');
                }

		$this->setWhereParams(SearchConfig::$searchWhereParameters.",".SearchConfig::$membersLookingForMeWhereParameters);
		$this->setRangeParams(SearchConfig::$searchRangeParameters.",".SearchConfig::$membersLookingForMeRangeParameters);
                
                //Call VSP from different URL
                $this->setIS_VSP(1);
	   return $this;	
	}
        
        /*
	* This function will set the criteria for similar search.
	*/
        public function getViewSimilarCriteria($searchId='',$channel='')
	{
		$viewSimilarLibObj=new ViewSimilarProfile;
		// NEW LOGIC
                if($channel=='ios'){
                        $memObject=JsMemcache::getInstance();
                        $profileIdArray = $memObject->get('similar-'.$this->ProfileObj->getPROFILEID().$this->pid);
                }
                if(!$profileIdArray){
                    if($this->loggedInProfileObj)
                        $profileIdArray=$viewSimilarLibObj->getSimilarProfiles($this->ProfileObj,$this->loggedInProfileObj,"fromViewSimilar");
                    else{
                        $db = connect_db();
                        if($searchId)
                          $includeCaste = checkIfCasteSpecified($searchId,$db);
                        $profileIdArray = getSimilarProfilesForLoggedOutCase($this->ProfileObj->getPROFILEID(),$this->ProfileObj->getGENDER(),$includeCaste,$db);
                    }
		}
                
                //OLD LOGIC
                //$profileIdArray=$viewSimilarLibObj->viewOldSimilarProfileResults($this->ProfileObj,$this->loggedInProfileObj);
                //testing array
		//$profileIdArray=array(7610638,3809672,3809444,2354035,1605944,3119194,1130213);
        if(is_array($profileIdArray))
		  $str = implode(" ",$profileIdArray);
		//$str="";
		if($str){
			$this->profilesToShow=$str;
			$this->setProfilesToShow($str);
		}
		else{
			//gender is set to X and profileid 9999999999 so that no search is performed on view similar page
			$this->setGENDER('X');
			$this->profilesToShow=('9999999999');
			//$this->setProfilesToShow($str);
		}
                if($this->loggedInProfileObj)
                  $this->getProfilesParameter($searchId, $channel);
	return $str;	
	}
        
        /*
	* This function will give the criteria for similar search.
	*/
	public function getProfilesToShow()
	{
		return $this->profilesToShow;
	}

        public function getPARTNER_MTONGUE() { return $this->PARTNER_MTONGUE; }
        public function setPARTNER_MTONGUE($x) { $this->PARTNER_MTONGUE = $x; }
        public function getPARTNER_CASTE() { return $this->PARTNER_CASTE; }
        public function setPARTNER_CASTE($x) { $this->PARTNER_CASTE = $x; }
        public function getPARTNER_RELIGION() { return $this->PARTNER_RELIGION; }
        public function setPARTNER_RELIGION($x) { $this->PARTNER_RELIGION = $x; }
        public function getPARTNER_COUNTRYRES() { return $this->PARTNER_COUNTRYRES; }
        public function setPARTNER_COUNTRYRES($x) { $this->PARTNER_COUNTRYRES = $x; }
        public function getPARTNER_BTYPE() { return $this->PARTNER_BTYPE; }
        public function setPARTNER_BTYPE($x) { $this->PARTNER_BTYPE = $x; }
        public function getPARTNER_COMP() { return $this->PARTNER_COMP; }
        public function setPARTNER_COMP($x) { $this->PARTNER_COMP = $x; }
        public function getPARTNER_ELEVEL_NEW() { return $this->PARTNER_ELEVEL_NEW; }
        public function setPARTNER_ELEVEL_NEW($x) { $this->PARTNER_ELEVEL_NEW = $x; }
        public function getPARTNER_INCOME() { return $this->PARTNER_INCOME; }
        public function setPARTNER_INCOME($x) { $this->PARTNER_INCOME = $x; }
        public function getPARTNER_OCC() { return $this->PARTNER_OCC; }
        public function setPARTNER_OCC($x) { $this->PARTNER_OCC = $x; }
        public function getLPARTNER_LAGE() { return $this->LPARTNER_LAGE; }
        public function setLPARTNER_LAGE($x) { $this->LPARTNER_LAGE = $x; }
        public function getHPARTNER_LAGE() { return $this->HPARTNER_LAGE; }
        public function setHPARTNER_LAGE($x) { $this->HPARTNER_LAGE = $x; }
        public function getLPARTNER_HAGE() { return $this->LPARTNER_HAGE; }
        public function setLPARTNER_HAGE($x) { $this->LPARTNER_HAGE = $x; }
        public function getHPARTNER_HAGE() { return $this->HPARTNER_HAGE; }
        public function setHPARTNER_HAGE($x) { $this->HPARTNER_HAGE = $x; }
        public function getLPARTNER_LHEIGHT() { return $this->LPARTNER_LHEIGHT; }
        public function setLPARTNER_LHEIGHT($x) { $this->LPARTNER_LHEIGHT = $x; }
        public function getHPARTNER_LHEIGHT() { return $this->HPARTNER_LHEIGHT; }
        public function setHPARTNER_LHEIGHT($x) { $this->HPARTNER_LHEIGHT = $x; }
        public function getLPARTNER_HHEIGHT() { return $this->LPARTNER_HHEIGHT; }
        public function setLPARTNER_HHEIGHT($x) { $this->LPARTNER_HHEIGHT = $x; }
        public function getHPARTNER_HHEIGHT() { return $this->HPARTNER_HHEIGHT; }
        public function setHPARTNER_HHEIGHT($x) { $this->HPARTNER_HHEIGHT = $x; }
        public function getPARTNER_MSTATUS() { return $this->PARTNER_MSTATUS; }
        public function setPARTNER_MSTATUS($x) { $this->PARTNER_MSTATUS = $x; }
        public function getPARTNER_CITYRES() { return $this->PARTNER_CITYRES; }
        public function setPARTNER_CITYRES($x) { $this->PARTNER_CITYRES = $x; }
        public function getPARTNER_DRINK() { return $this->PARTNER_DRINK; }
        public function setPARTNER_DRINK($x) { $this->PARTNER_DRINK = $x; }
        public function getPARTNER_SMOKE() { return $this->PARTNER_SMOKE; }
        public function setPARTNER_SMOKE($x) { $this->PARTNER_SMOKE = $x; }
        public function getPARTNER_DIET() { return $this->PARTNER_DIET; }
        public function setPARTNER_DIET($x) { $this->PARTNER_DIET = $x; }
        public function getPARTNER_HANDICAPPED() { return $this->PARTNER_HANDICAPPED; }
        public function setPARTNER_HANDICAPPED($x) { $this->PARTNER_HANDICAPPED = $x; }
        public function getPARTNER_MANGLIK() { return $this->PARTNER_MANGLIK; }
        public function setPARTNER_MANGLIK($x) { $this->PARTNER_MANGLIK = $x; }
        
        //this function is to set age filter on profiles
        private function setFilterForAge(){
            $selfAge = $this->loggedInProfileObj->getAGE();
            $othersAge = $this->ProfileObj->getAGE();
            if($this->loggedInProfileObj->getGENDER()==self::maleProfile){
                $LAgeToSet = min($selfAge-self::AgeConstantToBeSub,$othersAge);
                $HAgeToSet = max($selfAge,$othersAge);
            }
            else{
                $LAgeToSet = min($selfAge,$othersAge);
                $HAgeToSet = max($selfAge+self::AgeConstantToBeSub,$othersAge);
            }
            $this->setLAGE($LAgeToSet);
            $this->setHAGE($HAgeToSet);
        }
}
?>
