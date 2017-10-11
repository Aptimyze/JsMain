<?php
/**
 * Sorting Factory class
 * This function will return the sorting object associated with the imput paramaters. 
 * @author Lavesh Rawat
 * @package Search
 * @subpackage Sort
 * @copyright 2013 Lavesh Rawat
 * @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
 * @since 2013-12-01
*/
class SortStrategyFactory
{
	/*
	* @param SearchParamters $SearchParamtersObj
	* @param LoggedInProfile $loggedInProfileObj logged in profile object 
	* @param sortLogic char use sortLogic instead of SearchParamtersObj->getSORT_LOGIC for sorting expression. Used in feature profile only
	* @see SearchSortTypesEnums
	*/
	static public function getSorterStrategy($SearchParamtersObj,$loggedInProfileObj='',$sortLogic='')
	{
                
		$slogic = $SearchParamtersObj->getSORT_LOGIC();
		if($sortLogic == SearchSortTypesEnums::featureSortFlag)
			return new SortByFeatureProfileStrategy($SearchParamtersObj,$loggedInProfileObj);
		elseif($sortLogic == SearchSortTypesEnums::ntimesSortFlag)
			return new SortByNtimesStrategy($SearchParamtersObj,$loggedInProfileObj);
		if($slogic == SearchSortTypesEnums::relevanceSortFlag)
			return new SortByRelevanceStrategy($SearchParamtersObj,$loggedInProfileObj);
		if($slogic == SearchSortTypesEnums::popularSortFlag)
			return new SortByPopularStrategy($SearchParamtersObj,$loggedInProfileObj);
		if($slogic == SearchSortTypesEnums::newMatchesMailer)
			return new SortByNewMatchesMailerStrategy($SearchParamtersObj,$loggedInProfileObj);
		if($slogic == SearchSortTypesEnums::justJoinedSortFlag)
			return new SortByVerifyActivationDateStrategy($SearchParamtersObj,$loggedInProfileObj);
		if($slogic == SearchSortTypesEnums::matchAlertFlag) 
			return new SortByMatchAlert($SearchParamtersObj,$loggedInProfileObj);
		if($slogic == SearchSortTypesEnums::kundliAlertFlag) 
			return new SortByKundliAlert($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::viewAttemptFlag) 
			return new SortByViewAttempt($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::FullDppWithReverseFlag) 
			return new SortByLoginWithReverseDpp($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::SortByTrendsScore) 
			return new SortByTrendsScore($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::SortByLoginDate) 
			return new SortByLoginDate($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::SortByVisitorsTimestamp) 
			return new SortByVisitors($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::SortByBroaderDppScore) 
			return new SortByBroaderDppScore($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::SortByRelaxedNonTrends || $slogic == SearchSortTypesEnums::SortByRelaxedTrends) 
			return new SortByRelaxedDpp($SearchParamtersObj,$loggedInProfileObj);
                if($slogic == SearchSortTypesEnums::SortByStrictNonTrends || $slogic == SearchSortTypesEnums::SortByStrictTrends) 
			return new SortByStrictDpp($SearchParamtersObj,$loggedInProfileObj);
                return new SortByDateStrategy($SearchParamtersObj,$loggedInProfileObj);
	}
}
