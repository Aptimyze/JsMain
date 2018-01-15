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
		$output = $this->getLastSearchCriteria($this->pid,SearchTypesEnums::Advance);
        
		//The output is 1 in case there is an array and 0 in case no results were found. Therefore, setProfilesToShow is set to "0 0" so as to show the ZERO results message
       	if($output == 0)
        {
        	$this->setProfilesToShow("0 0");
        }
        $this->setSEARCH_TYPE(SearchTypesEnums::LAST_SEARCH_RESULTS);
	}
}