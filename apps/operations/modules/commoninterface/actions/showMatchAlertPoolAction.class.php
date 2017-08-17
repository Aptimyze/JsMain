<?php
/**
 * show match alert pool Action
 * This class fetches and displays the matching pool for the particular user
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Sanyam Chopra
 */
/**
* 
*/
class showMatchAlertPoolAction extends sfActions
{
	/**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
	public function execute($request)
	{
		$this->profileid = $request->getParameter('pid');
        $this->cid       = $request->getParameter('cid');

		$removeFilteredProfiles = true;
		$compeleteResponse = true;
        $profileObj = LoggedInProfile::getInstance('',$this->profileid);        
		$profileObj->getDetail('','','*');
		$this->username = $profileObj->getUSERNAME();
		$partnerObj = new SearchCommonFunctions();
		//dpp call without filters
		$matchesObj = $partnerObj->getMyDppMatches('',$profileObj,$limit,'','','',$removeFilteredProfiles,'','','','',$compeleteResponse);			
		$this->dppCount = $matchesObj->getTotalResults();
		unset($matchesObj);
		//dpp call without filters minus the matchalerts
		$matchesObj = $partnerObj->getMyDppMatches('',$profileObj,$limit,'','',1,$removeFilteredProfiles,'','','','',$compeleteResponse);
		$this->diffCount = $matchesObj->getTotalResults();
		unset($matchesObj);
	}
}