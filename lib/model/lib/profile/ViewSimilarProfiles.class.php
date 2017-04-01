<?php
/**
* @brief : This file will be handle view similar search result after expressing interest
* @author Nitesh Sethi
* @created 2014-09-18
*/
class ViewSimilarPageProfiles extends SearchParamters
{
	CONST femaleProfile  = 'F';
	CONST maleProfile = 'M';
	CONST removeFilteredForAllPrivacy='X';
	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj,$profileObj)
        {
			parent::__construct();
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
			$this->setShowFilteredProfiles(self::removeFilteredForAllPrivacy);
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
                        $profileIdArray=$viewSimilarLibObj->getSimilarProfiles($this->ProfileObj,$this->loggedInProfileObj,"fromViewSimilar");
		}
                //OLD LOGIC
                //$profileIdArray=$viewSimilarLibObj->viewOldSimilarProfileResults($this->ProfileObj,$this->loggedInProfileObj);
                //testing array
		//$profileIdArray=array(7610638,3809672,3809444,2354035,1605944,3119194,1130213);
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
                //call viewsimilar profiles from another URL 
                $this->IS_VSP(1);
	return $str;	
	}
	
	
	/*
	* This function will give the criteria for similar search.
	*/
	public function getProfilesToShow()
	{
		return $this->profilesToShow;
	}
}
?>
