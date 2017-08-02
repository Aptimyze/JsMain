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
	private $limit = 20;
	/**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
	public function execute($request)
	{
		$this->profileid = $request->getParameter('pid');
        $this->cid       = $request->getParameter('cid');

		$removeFilteredProfiles = false;
		$compeleteResponse = true;
        $profileObj = LoggedInProfile::getInstance('',$this->profileid);        
		$profileObj->getDetail('','','*');
		$this->username = $profileObj->getUSERNAME();
		$partnerObj = new SearchCommonFunctions();
		$matchesObj = $partnerObj->getMyDppMatches('',$profileObj,$limit,'','','',$removeFilteredProfiles,'','','','',$compeleteResponse);			
		$this->dppCount = $matchesObj->getTotalResults();
		$resultArr = $matchesObj->getResultsArr();
		print_r($resultArr);die;
		foreach($resultArr as $key=>$value)
		{
			$usernameArr[] = $value["USERNAME"];
		}
		$this->usernameArr = $usernameArr;
		print_R($this->usernameArr);
		$matchAlertObj = new matchalerts_LOG("newjs_slave");
		$this->matchAlertProfiles = $matchAlertObj->getProfilesSentInMatchAlerts($this->profileid);
		print_r($this->matchAlertProfiles);die;
		$this->diffCount = $this->matchAlertCount["COUNT"] - $this->dppCount;
	}
}