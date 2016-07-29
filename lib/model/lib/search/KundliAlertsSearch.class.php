<?php
/**
* @brief : This file will be handle match alerts search requirements.
* @author Lavesh Rawat
* @created 2014-09-01
*/
class KundliAlertsSearch extends SearchParamters
{
	CONST femaleProfile  = 'F';
	CONST maleProfile = 'M';
        CONST showAllResults = 'All';

	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj)
        {
		parent::__construct();
		$this->loggedInProfileObj = $loggedInProfileObj;
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
	}

	/*
	* This function will set the criteria for search.
	*/
	public function getSearchCriteria()
	{
		$this->setSEARCH_TYPE(SearchTypesEnums::KundliAlerts);
		$KundliAlerts = new KundliAlerts();
		$arr = $KundliAlerts->getProfilesWithOutSorting($this->pid);
		$this->alertsDateConditionArr = $arr;
		$str = implode(" ",array_keys($arr));
		$this->setProfilesToShow($str);
		$this->setSORT_LOGIC(SearchSortTypesEnums::kundliAlertFlag);
                if(!$this->getKUNDLI_DATE_CLUSTER())
                        $this->setKUNDLI_DATE_CLUSTER(self::showAllResults);
	}
}
?>
