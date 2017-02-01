<?php
/**
 * dpp Matches Show Stats Action
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Sanyam Chopra
 */
/**
* 
*/
class dppMatchesShowStatsAction extends sfActions
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
		$partnerObj = new SearchCommonFunctions();
		$matchesObj = $partnerObj->getMyDppMatches('',$profileObj,$limit,'','','',$removeFilteredProfiles,'','','','',$compeleteResponse);	
		$resultArr = $matchesObj->getResultsArr();
		$this->totalCount = $matchesObj->getTotalResults();				
		foreach($resultArr as $key=>$value)
		{
			$usernameArr[] = $value["USERNAME"];
		}
		$this->usernameArr = $usernameArr;
	}
}