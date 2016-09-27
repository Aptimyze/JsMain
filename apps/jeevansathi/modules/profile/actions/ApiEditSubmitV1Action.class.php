<?php

/**
 * profile actions.
 * ApiEditSubmitV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ApiEditSubmitV1Action extends sfActions
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
		$this->loginProfile = LoggedInProfile::getInstance();
    if($this->loginProfile->getAGE()== "")
      $this->loginProfile->getDetail($request->getAttribute("profileid"),"PROFILEID","*");
		$jpartnerObj=DetailActionLib::getJPartnerEdit($this);
		$this->loginProfile->setJpartner($jpartnerObj);
		//Get symfony form object related to Edit Fields coming.
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$this->editFieldNameArr=$request->getParameter('editFieldArr');
		if($this->editFieldNameArr['STATE_RES'] && $this->editFieldNameArr['CITY_RES']=="0")
		{
			$this->editFieldNameArr['CITY_RES']=  $this->editFieldNameArr['STATE_RES'] ."OT";
		}
		unset($this->editFieldNameArr['STATE_RES']);
                if(!empty($_FILES)){
                        foreach($_FILES as $f1){
                                foreach($f1 as $fKey=>$fVal){
                                        foreach($fVal as $key=>$fileVAlue){
                                                $this->editFieldNameArr[$key][$fKey] = $fileVAlue;
                                        }
                                }
                        }
                }
		if(MobileCommon::isApp())
		{
			foreach ($this->editFieldNameArr as $key=>$value)
			{
				if(is_array($value))
				{
					foreach($value as $k=>$v)
						$arr[$key][$k]=urldecode($v);
				}
				else
					$arr[$key]=urldecode($value);
			}
			
			$this->editFieldNameArr=$arr;
		}
		if(strtoupper($request->getParameter('incomplete'))==EditProfileEnum::$INCOMPLETE_YES)
			$this->incomplete=EditProfileEnum::$INCOMPLETE_YES;
		else
			$this->incomplete=EditProfileEnum::$INCOMPLETE_NO;
		if(is_array($this->editFieldNameArr))
		{
			$this->form = new FieldForm($this->editFieldNameArr,$this->loginProfile,$this->incomplete);
			$nonEditableField=$this->form->editableFieldsValidation($this->editFieldNameArr,$this->incomplete);
			
			//valid incomplete fields only
			$incompleteFieldFlag=true;
			if($this->incomplete==EditProfileEnum::$INCOMPLETE_YES)
			{
				$apiProfileSectionObj= ApiProfileSections::getApiProfileSectionObj($this->loginProfile);
				$incompleteArr=ProfileCommon::removeBlank($apiProfileSectionObj->getApiIncompleteInfo());
				$incompleteFieldArr=$this->form->incompleteFieldsValidation($this->editFieldNameArr,$incompleteArr);
				if(is_array($incompleteFieldArr))
					$incompleteFieldFlag=false;
				else
					$incompleteFieldFlag=true;
			}
			
			$this->form->bind($this->editFieldNameArr);
			if ($this->form->isValid() && !$nonEditableField && $incompleteFieldFlag)
			{       
                                if($this->editFieldNameArr["FAMILYINFO"])
					RegChannelTrack::insertPageChannel($request->getAttribute("profileid"),PageTypeTrack::_ABOUTFAMILY);
				$this->form->updateData();
				if($this->incomplete==EditProfileEnum::$INCOMPLETE_YES)
				{
					//Channel tracking for Incomplete SMS to track incomplete to complete )
					if($request->getParameter('channel')=='INCOM_SMS')
					{	
						$MIS_INCOMPLETE_SMS_OBJ=new MIS_INCOMPLETE_SMS();
						$MIS_INCOMPLETE_SMS_OBJ->insertCompletion($this->loginProfile->getPROFILEID());
					}
					$request->setParameter('email', $this->loginProfile->getEMAIL());
					$request->setParameter('password', $this->loginProfile->getPASSWORD());
					$request->setParameter('fromIncompleteApi',1);
                                        if(MobileCommon::isIOSApp()){
                                            $familyArr = $this->bakeIOSResponse(); 
                                            $request->setParameter('setFamilyArr',$familyArr);
                                        }
					$this->forward("api","LoginV1");
				}
        if(MobileCommon::isDesktop()){
          $apiResponseHandlerObj->setResponseBody($this->bakeDesktopResponse());
        }
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        
        ProfileCommon::updateProfileCompletionScore($this->loginProfile->getPROFILEID());
			}
			else
			{
				$error=array();
				$e=$this->form->getErrorSchema();
				foreach($e as $k=>$v)
				{
					$errorArr[$k]=$v->getMessageFormat();
				}
				if($nonEditableField)
				{
					if($this->incomplete==EditProfileEnum::$INCOMPLETE_YES)
						$errorArr["NonEditableField"]=$nonEditableField." is not a Valid incomplete field";
					else
						$errorArr["NonEditableField"]=$nonEditableField."is not a valid Edit field";
					ValidationHandler::getValidationHandler("",$nonEditableField." is not a valid Edit field");
				}
				if(!$incompleteFieldFlag){
					if(count($incompleteFieldArr))
					{
						foreach($incompleteFieldArr as $k=>$v)
						{
							$errorArr[$v]=" wrong field for incomplete user";
							ValidationHandler::getValidationHandler("",$v."wrong field for incomplete user");
						}
					}
					else
					{
						$errorArr["NonEditableField"]=" Invalid incomplete Request for this profile";
						ValidationHandler::getValidationHandler("","Invalid incomplete Request for this profile");
					}
				}
        $jsonErroString = "";
        if(MobileCommon::isDesktop()){
         $jsonErroString = json_decode(json_encode($errorArr), FALSE);
        }
        else{
          $jsonErroString = json_decode(json_encode(array_values($errorArr)), FALSE);
        }
				if(is_array($errorArr))
					$error[error]=$jsonErroString;
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				$apiResponseHandlerObj->setResponseBody($error);
			}
		}
		else
		{
			$errorArr["ERROR"]="Field Array is not valid";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
			ValidationHandler::getValidationHandler("","EditField Array is not valid");
		}
		$apiResponseHandlerObj->generateResponse();
		die;
	}
  
  private function bakeDesktopResponse(){
   
    //Edit View
    ob_start();
    $this->request->setParameter("sectionFlag", "all");
    $this->request->setParameter("internal", 1);
    $fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile", "ApiEditV1");
    $editData = json_decode(ob_get_contents(), true);
    ob_end_clean();
    
    
    //View Data
    viewProfileOptimization::destroy();
    //Call Desktop View 
    $this->profile = $this->loginProfile;
    $jparnterObj = DetailActionLib::getJPartnerEdit($this);

    $this->profile->setJpartner($jparnterObj);
    $nullValueMarker = ApiViewConstants::JSPC_NULL_VALUE_MARKER;
    $this->loginProfile->setNullValueMarker($nullValueMarker);
    ApiViewConstants::setUserDefinedNullValueMarker($nullValueMarker);
    
    DetailActionLib::GetProfilePicForApi($this);
    $objDetailedDisplay = new desktopView($this);
    $objDetailedDisplay->setResponseForEditView('Y');
    $viewData = array();
    $viewData = $objDetailedDisplay->getResponse();
    
    $this->loginProfile->setNullValueMarker("-");
    ApiViewConstants::setUserDefinedNullValueMarker(null);
    
    $arrOut = array();
    $arrOut['editApi'] = $editData;
    $arrOut['viewApi'] = $viewData;

    return $arrOut;
  }
  private function bakeIOSResponse(){
      //Edit Family section
    ob_start();
    $this->request->setParameter("sectionFlag", "family");
    $this->request->setParameter("internal", 1);
    $fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile", "ApiEditV1");
    $editFamilyData = json_decode(ob_get_contents(), true);
    ob_end_clean();
    return $editFamilyData;
  }
}
