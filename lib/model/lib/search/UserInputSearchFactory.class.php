<?php
/**
 * @class UserInputSearchFactory
 * @brief This class is the factory class of user performed searches like Quick , Advannce ....
 * @author Lavesh Rawat
 * @created 2012-09-14
 */
class UserInputSearchFactory
{
	/*
	* @param predefinedSearch search to be perfomed (mySavedSearch,PartnerProfile,MembersLookingForMe,Pagination)
	* @param loggedInProfileObj profile object
	*/
	static public function getSetterBy($predefinedSearch="",$loggedInProfileObj="")
	{
		$object = null;
		switch($predefinedSearch)
		{
			case 'TopSearchBand' :
				$object = new TopSearchBand();
				break;
			case 'QuickSearchBand' :
				$object = new QuickSearchBand($loggedInProfileObj);
				break;
			case 'AppSearch' :
				$object = new AppSearch($loggedInProfileObj);
				break;
			case 'AdvanceSearch' :
				$object = new AdvanceSearch($loggedInProfileObj);
				break;
			case 'NewMatchesMailer' :
				$object = new NewMatchesMailer($loggedInProfileObj);
				break;
		}
		return $object;
	}
}
