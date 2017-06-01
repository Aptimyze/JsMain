<?php

/**
 * profile actions.
 * ApiEditV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ApiEditV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		//Contains login credentials
		$this->loginData = $request->getAttribute("loginData");
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$request->getAttribute("profileid"))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		//Contains loggedin Profile information;
		$this->loginProfile = LoggedInProfile::getInstance();
    if($this->loginProfile->getAGE()== "" || $this->loginProfile->getRELIGION() == "" || is_null($this->loginProfile->getRELIGION()))
      $this->loginProfile->getDetail($request->getAttribute("profileid"),"PROFILEID","*");
                $c =  $this->loginProfile;
                $d = $request->getAttribute("profileid");

		$sectionFlag = $request->getParameter("sectionFlag");
		$apiProfileSectionObj=  ApiProfileSections::getApiProfileSectionObj($this->loginProfile);
		$religion = $this->loginProfile->getReligion();
		
		//created obj of EditDetails
		$editDetailsObj = new EditDetails();
		$jpartnerObj=$editDetailsObj->getJpartnerObj($this);
		$this->loginProfile->setJpartner($jpartnerObj);
		$myProfileArr = array();
		if($sectionFlag=="all" ||$sectionFlag=="incomplete" )
		{
			//called EditDetails method and response stored in $ResponseOut
			$ResponseOut = $editDetailsObj->getEditDetailsValues($this,$apiProfileSectionObj,$sectionFlag,$myProfileArr);
                        if(MobileCommon::isApp()=="A" && $sectionFlag=="all")
                        {
                                if(!$myProfileArr['MyBasicInfo'][8]['value']|| $myProfileArr['MyBasicInfo'][8]['value']==NULL || $myProfileArr['MyBasicInfo'][8]['value']==''){
                                        file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/editReligionProb.txt",var_export($myProfileArr['MyBasicInfo'][8],true)."profileid:".$this->loginProfile->getPROFILEID()."obj".var_export($c,true)."profileidattr:".$d."\n",FILE_APPEND);

}
                        }
                        $myProfileArr["showEdit"] = 0;
                        $infoChngObj = new newjs_CRITICAL_INFO_CHANGED();
                        $editedCritical = $infoChngObj->editedCriticalInfo($this->loginProfile->getPROFILEID());unset($infoChngObj);
                        $myProfileArr["cannot_edit_section"] = array();
                        if($editedCritical===false){
                                $myProfileArr["cannot_edit_section"][] = "Critical";
                        }
			$apiResponseHandlerObj->setHttpArray($ResponseOut);
			$apiResponseHandlerObj->setResponseBody($myProfileArr);
			$apiResponseHandlerObj->generateResponse();
			if(MobileCommon::isApp()==null)
				return sfView::NONE;
			else
				die;
				
		}
                else if($sectionFlag == "dpp"){
                  $toSendArr =  $editDetailsObj->getDppValuesArr($apiProfileSectionObj,'1');                  
                  if($this->apEditMsg){
                     $toSendArr["ap_screen_msg"] = DPPConstants::AP_SCREEN_MSG;
                  }
                  echo json_encode($toSendArr);
                  if($request->getParameter("internal"))
                    return sfView::NONE;
                }
                else if($sectionFlag == "family"){
                  $toSendArr =  $apiProfileSectionObj->getApiFamilyDetails();
                  echo json_encode($toSendArr);
                  if($request->getParameter("internal"))
                    return sfView::NONE;
                }
		else
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				$apiResponseHandlerObj->setResponseMessage("Invalid SectionFlag Parameter");
				$apiResponseHandlerObj->generateResponse();
				ValidationHandler::getValidationHandler("","Invalid SectionFlag Parameter");
				die;
			}
	}


		
        
}
