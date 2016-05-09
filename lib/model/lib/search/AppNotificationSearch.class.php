<?php
/**
 * @brief This class list the possible saved search paramters based on user id.
 * @author Lavesh Rawat
 * @created 2014-02-18
 */
class AppNotificationSearch extends PartnerProfile
{
	public function __construct($loggedInProfileObj)
	{
		parent::__construct($loggedInProfileObj);
	}
	
	public function getSearchCriteria($searchId='')
	{
		parent::getDppCriteria();
		$profileid = $this->loggedInProfileObj->getPROFILEID();
		$loginDt = NotificationFunctions::getdppMatchNotificationCalcDate($profileid);
		$this->setLAST_LOGIN_DT($loginDt);
		$this->SORT_LOGIC = SearchSortTypesEnums::dateSortFlag;
	}
}
?>
