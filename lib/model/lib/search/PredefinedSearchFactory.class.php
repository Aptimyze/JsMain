<?php
/**
 * @class PredefinedSearchFactory
 * @brief This class is the factory class of predefined searches like savedsearch / Dpp / Reverse-dpp
 * @author Lavesh Rawat
 * @created 2012-06-10
 */
class PredefinedSearchFactory
{
	/*
	* @param predefinedSearch search to be perfomed (mySavedSearch,PartnerProfile,MembersLookingForMe,Pagination)
	* @param loggedInProfileObj profile object
	*/
	static public function getSetterBy($predefinedSearch="",$loggedInProfileObj='')
	{
                $object = null;
                switch($predefinedSearch)
                {
                        case 'mySavedSearch' :
                                $object = new UserSavedSearches($loggedInProfileObj);
                                break;
                        case 'JustJoinedMatches' :
                                $object = new JustJoinedMatches($loggedInProfileObj);
                                break;
                        case 'PartnerProfile' :
                                $object = new PartnerProfile($loggedInProfileObj);
                                break;
                        case 'TwoWayMatch' :
                                $object = new TwoWayMatch($loggedInProfileObj);
                                break;
                        case 'searchId' :
                                $object = new SearchLogger($loggedInProfileObj);
                                break;
                        case 'MembersLookingForMe' :
                                $object = new MembersLookingForMe($loggedInProfileObj);
                                break;
                        case 'appNotificationSearch' :
                                $object = new AppNotificationSearch($loggedInProfileObj);
                                break;
                        case 'ViewSimilarSearch' :
                                $object = new ViewSimilarPageProfiles($loggedInProfileObj);
                                break;
                        case 'MatchAlertsSearch' :
                                $object = new MatchAlertsSearch($loggedInProfileObj);
                                break;
                        case 'KundliAlertsSearch' :
                                $object = new KundliMatches($loggedInProfileObj);
                                break;
                        case 'verifiedMatches' :
                                $object = new verifiedMatches($loggedInProfileObj);
                                break;
                        case 'ContactViewAttempts' :
                                $object = new ContactViewAttempts($loggedInProfileObj);
                                break;
			case 'MatchOfDay':
				$object = new MatchOfDay($loggedInProfileObj);
				break;
                        case 'lastSearchResults'  :
                                $object = new lastSearchResults($loggedInProfileObj);
                                break;
                }
                return $object;
	}
}
