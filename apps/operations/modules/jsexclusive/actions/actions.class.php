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
	public function forwardTo($module,$action){
		$url="/operations.php/$module/$action";
		$this->redirect($url);
	}

	/*ScreenRBInterests - screen RB interests for clients assigned to logged in RM
    * @param : $request
    */
	public function executeScreenRBInterests(sfWebRequest $request){
		$exclusiveObj = new billing_EXCLUSIVE_MEMBERS("newjs_slave");
		$assignedClients = $exclusiveObj->getExclusiveMembers("DISTINCT PROFILEID",true,"",$this->name,"",false);
		$this->clientIndex = $request->getParameter("clientIndex");
		
		if(empty($this->clientIndex) || !is_numeric($this->clientIndex)){
			$this->clientIndex = 0;
		}

		if(!is_array($assignedClients) || count($assignedClients)==0){
			$this->infoMsg = "No assigned clients corresponding to logged in RM found..";
		}
		else if(!empty($this->clientIndex) && $this->clientIndex>=count($assignedClients)){
			$this->infoMsg = "All clients for logged in RM have been screened..";
		}
		else{
			$this->clientId = $assignedClients[$this->clientIndex];
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
		$this->clientIndex = $request->getParameter("clientIndex");
		if(!isset($this->clientIndex)){
			$this->clientIndex = -1;
		}
		++$this->clientIndex;
		$request->setParameter("clientIndex",$this->clientIndex);
		$this->forwardTo("jsexclusive","screenRBInterests");
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
