<?php
/**
This class is used to search the Feature Profiles corresponding to logged in and logged out case.
**/
class FeaturedProfile extends MembersLookingForMe
{
	private $loggedInProfileObj;
	private $FEATURE_PROFILE;
	private $avoidRevereseCriteria;
	private $logoutScore = 1;
	private $loginScore = 5;
        private $lastLoginForFeatured = 15;

	public function getFEATURE_PROFILE(){return $this->FEATURE_PROFILE;}
        public function setFEATURE_PROFILE($x){$this->FEATURE_PROFILE = $x;}

	public function getAvoidRevereseCriteria(){return $this->avoidRevereseCriteria;}
        public function setAvoidRevereseCriteria($x){$this->avoidRevereseCriteria = $x;}

	public function __construct($loggedInProfileObj)
	{
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
			$this->loggedInProfileObj = $loggedInProfileObj;
		parent::__construct($this->loggedInProfileObj);
	}

	/**
        This function sets the variable of Featured Profile class depending on logged in and logged out scenario
        @params SearchParametersObj,avoidRevereseCriteria (optional) set to 1 if reverse criteria parameters neet to be avoided.
        **/
	public function getFeaturedSearchCriteria($SearchParamtersObj,$avoidRevereseCriteria="")
	{
		if($this->loggedInProfileObj && $this->loggedInProfileObj->getGENDER()!=$SearchParamtersObj->getGENDER() && !$avoidRevereseCriteria)
		{
			parent::getSearchCriteria();
		}

		$SearchParamtersArr = explode(",",SearchConfig::$possibleSearchParamters);

		foreach($SearchParamtersArr as $k=>$v)
		{
			eval('$value = $SearchParamtersObj->get'.$v.'();');
			if($value)
				eval('$this->set'.$v.'("'.$value.'");');
		}

		if($avoidRevereseCriteria)
			$this->setAvoidRevereseCriteria($avoidRevereseCriteria);
		$this->setFEATURE_PROFILE(1);
		$this->setSEARCH_TYPE(SearchTypesEnums::FeatureProfile);
		$this->setWhereParams(SearchConfig::$searchWhereParameters.",".SearchConfig::$featureProfileWhereParameters);
		if($SearchParamtersObj->getOnlineProfiles())
			$this->setOnlineProfiles($SearchParamtersObj->getOnlineProfiles());
		if($SearchParamtersObj->getIgnoreProfiles())
			$this->setIgnoreProfiles($SearchParamtersObj->getIgnoreProfiles());
		if($SearchParamtersObj->getProfilesToShow())
			$this->setProfilesToShow($SearchParamtersObj->getProfilesToShow());
                
                $this->setLLAST_LOGIN_DT(date('Y-m-d h:m:s',  strtotime('-'.$this->lastLoginForFeatured.' days')));
                $this->setHLAST_LOGIN_DT(date('Y-m-d h:m:s'));
                $this->setRangeParams(SearchConfig::$searchRangeParameters.",".SearchConfig::$membersLookingForMeRangeParameters.",".SearchConfig::$featuredProfileParams);

		if($this->loggedInProfileObj)
		{
			$SearchUtilityObj = new SearchUtility;
			$SearchUtilityObj = $SearchUtilityObj->removeProfileFromSearch($this,'spaceSeperator',$this->loggedInProfileObj);
			unset($SearchUtilityObj);
		}
		$this->setNoOfResults(SearchConfig::$featuredProfilesCount);
		unset($SearchSortObj);
	}

	/**
        This function inserts records into FEATURED_PROFILE_CACHE and FEATURED_PROFILE_LIST table
        @params search id and array of feature profile ids
        **/
	public function performDbAction($sid,$idArr)
	{
		if($idArr && is_array($idArr))
		{
			if(SearchConfig::$featureProfileCache)
			{
				$key = $sid."_FEATUREPROFILE";
				JsMemcache::getInstance()->set($key,implode(",",$idArr),3600);
			}
			else
			{
				$fpcObj = new NEWJS_FEATURED_PROFILE_CACHE;
				$fpcObj->insert($sid,$idArr);
				unset($fpcObj);
			}

			$fplObj = new NEWJS_FEATURED_PROFILE_LIST;
			if($this->loggedInProfileObj)
				$score = $this->loginScore;
			else
				$score = $this->logoutScore;
			$fplObj->insertRecord($idArr[0],$score);
			unset($fplObj);

			if($this->loggedInProfileObj)
			{
				$fplObj = new NEWJS_FEATURED_PROFILE_LOG;
				$fplObj->insertRecord($this->loggedInProfileObj->getPROFILEID(),$idArr[0]);
				unset($fplObj);
			}
			return $idArr[0];
		}
		else
			return null;
	}

	public function getProfile($action="",$profileId="",$searchId)
	{
		if($searchId)
		{
			if(SearchConfig::$featureProfileCache)
                        {
                                $key = $searchId."_FEATUREPROFILE";
                                $profiles = JsMemcache::getInstance()->get($key);
                        }
                        else
			{
				$fpcObj = new NEWJS_FEATURED_PROFILE_CACHE;
				$profiles = $fpcObj->fetch($searchId);
				unset($fpcObj);
			}
			if($profiles)
			{
				$profilesArr = explode(",",$profiles);
				$key = 0;
				if($profileId)
				{
					$key = array_search($profileId,$profilesArr);
					if($key===false)
						return null;
					else
					{
						if($action == "next")
						{
							if($key==count($profilesArr)-1)
								$key = 0;
							else
								$key = $key+1;	
						}
						elseif($action == "prev")
						{
							if($key==0)
								$key = count($profilesArr)-1;
							else
								$key = $key-1;
						}
					}
				}
			
				$outputId = $profilesArr[$key];
				unset($output);
				$output["TOTAL"] = count($profilesArr);
				$output["PROFILEID"] = $outputId;
				if(count($profilesArr)==1)
					$output["POSITION"] = "single";
				else
				{
					if($key==0)
						$output["POSITION"] = "first";
					elseif($key == count($profilesArr)-1)
						$output["POSITION"] = "last";
				}

				if($outputId)
				{
					$fplObj = new NEWJS_FEATURED_PROFILE_LIST;
					if($this->loggedInProfileObj)
						$fplObj->insertRecord($outputId,$this->loginScore);
					else
						$fplObj->insertRecord($outputId,$this->logoutScore);
					unset($fplObj);

					if($this->loggedInProfileObj)
					{
						$fplObj = new NEWJS_FEATURED_PROFILE_LOG;
						$fplObj->insertRecord($this->loggedInProfileObj->getPROFILEID(),$outputId);
						unset($fplObj);
					}
				}
				$profiles = str_replace(","," ",$profiles);
				$output["All"] = $profiles;
				return $output;
			}
			else
				return null;
		}
		else
			return null;
	}
}
?>
