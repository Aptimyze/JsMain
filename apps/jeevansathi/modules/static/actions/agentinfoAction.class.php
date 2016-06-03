<?php

/**
 * Agent Info actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nitesh Sethi
 */
/**
 * Sales Agent Allocation information page.<p></p>
 * 	
 *  
 * @author Nitesh Sethi
 */

class agentinfoAction extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	 /**
     * Automatically calls before the action to execute.
     *
     */
	public function preExecute()
	{
	}
	
	/**
     * Handles Detail info page of Sales Agent Allocation  
     *@param $request contains sfWebrequest parameter send by symfony
     *
     */
	public function execute($request)
	{
                $this->tollNo = "1800-419-6299";
                $this->loginData = $request->getAttribute("loginData");
                $memHandlerObj = new MembershipHandler();
				if($this->loginData['PROFILEID']){
			  		$this->personalVerif = $memHandlerObj->showVerificationWidgetOrNot();
			  		//print_r($this->personalVerif);
			  	} else {
			  		$this->personalVerif = 0;
			  	}
			  	//print_r($this->personalVerif);
                if ($this->loginData) {
                        $this->loginProfile = LoggedInProfile::getInstance();
                        $this->loginProfile->getDetail($this->loginData['PROFILEID'], "PROFILEID");
                        
                        //Tracking Mis for static executive information page

                        $dbMisRelationExec = new MIS_RELATION_EXEC_PAGE();
                        $source = $request->getParameter("source");
                        $dbMisRelationExec->insertRecord($this->loginData['PROFILEID'], $source);
                }
        }
	
	
}
?>
