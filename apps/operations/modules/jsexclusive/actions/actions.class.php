<?php

/**
 * jsexclusive actions.
 *
 * @package    jeevansathi
 * @subpackage jsexclusive
 */
class jsexclusiveActions extends sfActions
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function executeIndex(sfWebRequest $request)
	{
		$this->forward('default', 'module');
	}

	/*ScreenRBInterests - screen RB interests for clients assigned to logged in RM
    * @param : $request
    */
	public function executeScreenRBInterests(sfWebRequest $request){
		$this->name = $request->getAttribute('name');

		$exclusiveObj = new billing_EXCLUSIVE_MEMBERS("newjs_slave");
		$assignedClients = $exclusiveObj->getExclusiveMembers("DISTINCT PROFILEID",true,"",$this->name,"",false);
		if(!is_array($assignedClients) || count($assignedClients)==0){
			$this->infoMsg = "No assigned clients corresponding to logged in RM found..";
		}
		else{
			$this->clientId = $assignedClients[0];
			$pogRBInterestsPids = array(82666,9397643,9061321,134640);

			$exclusiveLib = new ExclusiveFunctions();
			$this->pogRBInterestsPool = $exclusiveLib->formatScreenRBInterestsData($pogRBInterestsPids);
			unset($exclusiveLib);
			
			print_r($this->pogRBInterestsPool);
		}
	}

    /*forwards the request to given module action
    * @param : $module,$action
    */
	public function forwardTo($module,$action)
	{
		$url="/operations.php/$module/$action";
		$this->redirect($url);
	}
}
?>