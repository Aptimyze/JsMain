<?php
/**
 * @brief This class lists the parameters to find results based on last search of a user.
 * @author Sanyam Chopra
 * @created 2016-10-17
 */

class lastSearchResults extends SearchLogger
{
	public function __construct($loggedInProfileObj)
	{
		parent::__construct();
		$this->possibleSearchParamters = SearchConfig::$possibleSearchParamters;
		$this->loggedInProfileObj = $loggedInProfileObj;
		$this->pid =  $this->loggedInProfileObj->getPROFILEID();
		if(!$this->pid)
		{
			$context = sfContext::getInstance();
			$context->getController()->forward("static", "logoutPage"); //Logout page
			throw new sfStopException();
		}
	}
	
	public function getLastSearchResultCriteria()
	{
		$this->getLastSearchCriteria($this->pid,SearchTypesEnums::Advance);
		// if(!$flag)
		// {
		// 	$searchObj = new PartnerProfile($loggedInProfileObj);
		// 	$searchObj->getDppCriteria();
		// 	$flag = 1;		
		// }
	}
}