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
	public function preExecute()
	{
		$request=sfContext::getInstance()->getRequest();
		$this->cid=$request->getParameter("cid");
		$this->name=$request->getParameter("name");
		$this->module = $request->getParameter("module");
		$this->action = $request->getParameter("action");

		//put module wise condition
		if($this->name && $this->module=="jsexclusive" && in_array($this->action, array("screenRBInterests","menu"))){
			$exclusiveObj = new billing_EXCLUSIVE_SERVICING();
			$this->unscreenedClientsCount = $exclusiveObj->getUnScreenedClientCount($this->name);
			unset($exclusiveObj);
		}
		else{
			$this->unscreenedClientsCount = 0;
		}
	}
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
	public function forwardTo($module,$action,$params=""){
		$url="/operations.php/$module/$action";
		if(is_array($params)){
			foreach ($params as $key => $value) {
				if(strpos($url, "?")){
					$url .= "&".$key."=".$value;
				}
				else{
					$url .= "?".$key."=".$value;
				}
			}
		}
		$this->redirect($url);
	}

	/*ScreenRBInterests - screen RB interests for clients assigned to logged in RM
    * @param : $request
    */
	public function executeScreenRBInterests(sfWebRequest $request){
		$exclusiveObj = new billing_EXCLUSIVE_SERVICING();
		$assignedClients = $exclusiveObj->getUnScreenedExclusiveMembers($this->name,"ASSIGNED_DT");
		$this->clientIndex = $request->getParameter("clientIndex");
		$this->showNextButton = 'N';
		
		if(empty($this->clientIndex) || !is_numeric($this->clientIndex)){
			$this->clientIndex = 0;
		}
		
		if(!is_array($assignedClients) || count($assignedClients)==0){
			$this->infoMsg = "No assigned clients corresponding to logged in RM found..";
		}
		else if(!empty($this->clientIndex) && $this->clientIndex>=count($assignedClients)){
			$this->infoMsg = "No more clients left for screening for logged in RM..";
		}
		else{
			$this->clientId = $assignedClients[$this->clientIndex];
			$assistedProductObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES("newjs_slave");
			$pogRBInterestsPids = $assistedProductObj->getPOGInterestEligibleProfiles($this->clientId);
			//$pogRBInterestsPids = array(543);
			unset($assistedProductObj);

			$clientProfileObj = new Operator;
			$clientProfileObj->getDetail($this->clientId,"PROFILEID","PROFILEID,USERNAME,GENDER,HOROSCOPE_MATCH,CASTE");

			if($clientProfileObj){
				$this->horoscopeMatch = $clientProfileObj->getHOROSCOPE_MATCH();
				$this->clientData = array("clientUsername"=>$clientProfileObj->getUSERNAME(),"HoroscopeMatch"=>"N","PROFILEID"=>$this->clientId,"clientCaste"=>$clientProfileObj->getCASTE());
				$this->clientData["HoroscopeMatch"] = $this->horoscopeMatch;
				$this->clientData["gender"] = $clientProfileObj->getGENDER();
				unset($clientProfileObj);

				if(is_array($pogRBInterestsPids) && count($pogRBInterestsPids)>0){
					$exclusiveLib = new ExclusiveFunctions();
					$this->pogRBInterestsPool = $exclusiveLib->formatScreenRBInterestsData($this->clientData,$pogRBInterestsPids);
					unset($exclusiveLib);
				}
				else{
					$this->infoMsg = "No members for this client found..";
					$this->showNextButton = 'Y';
				}
			}
			unset($clientProfileObj);
		}
	}

	/*SubmitScreenRBInterests - submit screened RB interests for clients assigned to logged in RM and filtered by RM
    * @param : $request
    */
	public function executeSubmitScreenRBInterests(sfWebRequest $request){
		$formArr = $request->getParameterHolder()->getAll();

		if(is_numeric($formArr["clientIndex"])){
			$this->clientIndex = $formArr["clientIndex"];
			if(empty($this->clientIndex)){
				$this->clientIndex = 0;
			}
			if($formArr["submit"] == "SUBMIT"){
				$acceptArr = $formArr["ACCEPT"];
				$discardArr = $formArr["DISCARD"];
				if(is_array($acceptArr) && is_array($discardArr)){
					$acceptArr = array_diff($acceptArr, $discardArr);
					$acceptArr = array_values($acceptArr);
				}
				
				$email = $request->getParameter("email");
				$exclusiveObj = new ExclusiveFunctions();
				$exclusiveObj->processScreenedEois(array("agentUsername"=>$this->name,"clientId"=>$request->getParameter("clientId"),"acceptArr"=>$acceptArr,"discardArr"=>$discardArr));
				unset($exclusiveObj);
			}
			++$this->clientIndex;
			$this->forwardTo("jsexclusive","screenRBInterests",array("clientIndex"=>$this->clientIndex));
		}
		else{
			$this->forwardTo("jsexclusive","screenRBInterests");
		}
    }
  
	public function executeMenu(sfWebRequest $request)
	{
	//Get Count for each option 
	  
	  //Counter for welcome calls
	}

	public function executeWelcomeCalls(sfWebRequest $request){
	  
	//Get all clients here
	  
	}
}
?>
