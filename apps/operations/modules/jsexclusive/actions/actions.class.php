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
			$pogRBInterestsPids = array(9397643,9061321);
			$this->pogRBInterestsPool = array();
			foreach ($pogRBInterestsPids as $key => $pid) {
				$profileObj = new Operator;
				$profileObj->getDetail($pid,"PROFILEID",'PROFILEID,USERNAME,YOURINFO');
				if($profileObj){
					$this->pogRBInterestsPool[$pid]['USERNAME'] = $profileObj->getUSERNAME();
					$this->pogRBInterestsPool[$pid]['ABOUT_ME'] = $profileObj->getYOURINFO();
				}
			}
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