<?php
/**
 * @brief This class list the possible kundli matches of a user based on DPP .
 * @author Reshu Rajput
 * @created 2016-08-08
 */
class KundliMatches extends PartnerProfile
{
    
       /**
        * Constructor function.
        * @constructor
        * @access public        
        * @param LoggedInProfile $loggedInProfileObj logged in profile object
        */
        public function __construct($loggedInProfileObj)
        {
					$this->loggedInProfileObj = $loggedInProfileObj;
					parent::__construct($loggedInProfileObj);
        }

			/*
	* This function will set the criteria for search.
	*/
				public function getSearchCriteria($sz_callType='')
				{
					
					$this->getDppCriteria();
					$this->setSEARCH_TYPE(SearchTypesEnums::KundliAlerts);
					$this->setHOROSCOPE('Y'); // Horoscope should be present for all the profiles
					
				}


}
?>
