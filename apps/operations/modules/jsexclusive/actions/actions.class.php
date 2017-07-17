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
	public function executeIndex(sfWebRequest $request){
		$this->forward('default', 'module');
	}

	/*forwards the request to given module action
    * @param : $module,$action
    */
	public function forwardTo($module,$action){
		$url="/operations.php/$module/$action";
		$this->redirect($url);
	}

	/*ScreenRBInterests - screen RB interests for clients assigned to logged in RM
    * @param : $request
    */
	public function executeScreenRBInterests(sfWebRequest $request){
		$this->name = $request->getAttribute('name');

		$exclusiveObj = new billing_EXCLUSIVE_MEMBERS("newjs_slave");
		$assignedClients = $exclusiveObj->getExclusiveMembers("DISTINCT PROFILEID",true,"",$this->name,"",false);
		$clientIndex = $request->getParameter("clientIndex");
		if(!isset($clientIndex)){
			$clientIndex = 0;
		}

		if(!is_array($assignedClients) || count($assignedClients)==0){
			$this->infoMsg = "No assigned clients corresponding to logged in RM found..";
		}
		else if(!empty($clientIndex) && $clientIndex>=count($assignedClients)){
			$this->infoMsg = "All clients for logged in RM have been screened..";
		}
		else{
			$this->clientId = $assignedClients[$clientIndex];
			$pogRBInterestsPids = array(82666,9397643,9061321,134640,6999918);

			$clientProfileObj = new Operator;
			$clientProfileObj->getDetail($this->clientId,"PROFILEID","PROFILEID,USERNAME,GENDER,HOROSCOPE_MATCH");

			if($clientProfileObj){
				$this->horoscopeMatch = $clientProfileObj->getHOROSCOPE_MATCH();
				$this->clientData = array("HoroscopeMatch"=>"N");
				$this->clientData["HoroscopeMatch"] = $this->horoscopeMatch;
				$this->clientData["gender"] = $clientProfileObj->getGENDER();
				unset($clientProfileObj);

				$exclusiveLib = new ExclusiveFunctions();
				$this->pogRBInterestsPool = $exclusiveLib->formatScreenRBInterestsData($this->clientData,$pogRBInterestsPids);
				unset($exclusiveLib);
			}
		}
	}

	/*SubmitScreenRBInterests - submit screened RB interests for clients assigned to logged in RM and filtered by RM
    * @param : $request
    */
	public function executeSubmitScreenRBInterests(sfWebRequest $request){

    }
}
?>