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
    if($this->loginProfile->getAGE()== "")
      $this->loginProfile->getDetail($request->getAttribute("profileid"),"PROFILEID","*");
		$jpartnerObj=DetailActionLib::getJPartnerEdit($this);
		$this->loginProfile->setJpartner($jpartnerObj);
		$sectionFlag = $request->getParameter("sectionFlag");
		$apiProfileSectionObj=  ApiProfileSections::getApiProfileSectionObj($this->loginProfile);
		$religion = $this->loginProfile->getReligion();
		if($sectionFlag=="all" ||$sectionFlag=="incomplete" )
		{
			if($sectionFlag=="all")
			{	
				
				if(MobileCommon::isApp()==null)
					{
						$myProfileArr["Album"]["ProfilePicUrl"]="";
						$myProfileArr["Album"]["tempSelectVar"]="";

					}
				if(MobileCommon::isApp())
					$myProfileArr["MyBasicInfo"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiBasicInfo());
				else
					$myProfileArr["Details"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiBasicInfo());
				
				if(MobileCommon::isApp())
					$myProfileArr["Astro"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiAstroKundali());
				else if(($religion==RELIGION::HINDU || $religion==Religion::JAIN || $religion==Religion::SIKH || $religion== Religion::BUDDHIST))
					$myProfileArr["Kundli"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiAstroKundali());
				
				$myProfileArr["Education"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiEducation());
				if(MobileCommon::isApp())
					$myProfileArr["Occupation"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiOccupation());
				else
					$myProfileArr["Career"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiOccupation());
				$myProfileArr["Family"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiFamilyDetails());
				$myProfileArr["Lifestyle"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiLifeAttr());
				$myProfileArr["Contact"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiContactInfo());
				
				
				
				
				//album ny anand
				if(MobileCommon::isApp()){

					$myProfileArr["album"]["privacy"] = $this->loginProfile->getPHOTO_DISPLAY();
					$picServiceObj = new PictureService($this->loginProfile);
					$album = $picServiceObj->getAlbum($request->getParameter("contactType"));
					if($album && is_array($album))
					{ 
						foreach($album as $k=>$v)
						{
								$albumArr[$k]["pictureid"] = $v->getPICTUREID();
								$albumArr[$k]["url"] = $v->getMainPicUrl();
						
                                if(MobileCommon::isApp()=="I" && $v->getOrdering()=="0" && $k==0){
					if($v->getProfilePic235Url())
						 $myProfileArr["album"]["profilePicUrl"]=$v->getProfilePic235Url();
					else if($v->getProfilePic120Url())
						$myProfileArr["album"]["profilePicUrl"]=$v->getProfilePic120Url();
                                        else if($v->getProfilePicUrl())
						$myProfileArr["album"]["profilePicUrl"]=$v->getProfilePicUrl();
                                        else if($v->getMainPicUrl())
						$myProfileArr["album"]["profilePicUrl"]=$v->getMainPicUrl();
					else
						$myProfileArr["album"]["profilePicUrl"]="";
                                }
						}		
					}
					if($albumArr && is_array($albumArr))
					{
						$myProfileArr["album"]["albumUrls"]=$albumArr;
						$myProfileArr["album"]["max_no_of_photos"]=sfConfig::get("app_max_no_of_photos");
						
					}
					else
					{
						$myProfileArr["album"]["albumUrls"]=NULL;
						$myProfileArr["album"]["max_no_of_photos"]=sfConfig::get("app_max_no_of_photos");
					}
					
					//unsetting the picture service object
					unset($picServiceObj);
					//Screening Array
					//$myProfileArr["ScreeningFields"]=$apiProfileSectionObj->getApiScreeningFields();
				}	
                                
				$myProfileArr["Dpp"] = $this->getDppValuesArr($apiProfileSectionObj);
               if(MobileCommon::isApp()){
					$myProfileArr["ScreeningMessage"]="under screening";
					$myProfileArr["c_care"]="18004196299";
				}
                
                //To get complete your profile Links on page save
                if(MobileCommon::isDesktop())
                {
                    $cScoreObject = ProfileCompletionFactory::getInstance(null,$this->loginProfile,null);
                    $myProfileArr["profileCompletion"]["PCS"] = $cScoreObject->getProfileCompletionScore();
                    $noOfMsg = 4;
                    $myProfileArr["profileCompletion"]["msgDetails"] = $cScoreObject->GetIncompleteDetails($noOfMsg);
                    $myProfileArr["profileCompletion"]["linkDetails"] = $cScoreObject->GetLink();
                }
                
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			}
			elseif($sectionFlag=="incomplete")
			{
				//incomeplete testing
				$myProfileArr["Incomplete"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiIncompleteInfo());
				if(!MobileCommon::isApp())
				{
					$pictureServiceObj = new PictureService($this->loginProfile);
					$profilePicObject = $pictureServiceObj->getProfilePic();
					$noOfPhotos=$pictureServiceObj->getUserUploadedPictureCount();
					if ($profilePicObject != NULL && $noOfPhotos && $profilePicObject->getProfilePic120Url())
						$myProfileArr["ProfilePicUrl"]=$profilePicObject->getProfilePic120Url();
                                        elseif ($profilePicObject != NULL && $noOfPhotos && $profilePicObject->getProfilePicUrl)
						$myProfileArr["ProfilePicUrl"]=$profilePicObject->getProfilePicUrl();
					elseif ($profilePicObject != NULL && $noOfPhotos && $profilePicObject->getMainPicUrl)
						$myProfileArr["ProfilePicUrl"]=$profilePicObject->getMainPicUrl();
                                        else
					{
						if($this->loginProfile->getGENDER()=="F")
							$myProfileArr["ProfilePicUrl"]="/images/jsms/commonImg/3_4_NoFemalePhoto.jpg";
						else
							$myProfileArr["ProfilePicUrl"]="/images/jsms/commonImg/3_4_NoMalePhoto.jpg";
					}
					$completionObj=  ProfileCompletionFactory::getInstance("API",$this->loginProfile,null);
					$myProfileArr["ProfileCompletionScore"]=$completionObj->getProfileCompletionScore();
					
				}
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$INCOMPLETE_USER);
			}
			
			$apiResponseHandlerObj->setResponseBody($myProfileArr);
			$apiResponseHandlerObj->generateResponse();
			if(MobileCommon::isApp()==null)
				return sfView::NONE;
			else
				die;
				
		}
                else if($sectionFlag == "dpp"){
                  $toSendArr =  $this->getDppValuesArr($apiProfileSectionObj,'1');                  
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
        private function getDppValuesArr($apiProfileSectionObj,$hasCasteGrouping=''){
            $arrTemp = array();
            $arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppLifeAttr());
            $arrTemp = array_merge($arrOut,$arrTemp);
            $arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppEducationAndOcc());
            $arrTemp = array_merge($arrOut,$arrTemp);
            
            $arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppReligionAndEth());
            if($hasCasteGrouping=='1')
            {
							$arrOut=$this->changeCasteLabelForGrouping($arrOut);
						}
            $arrTemp = array_merge($arrOut,$arrTemp);
            $arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppBasicInfo());
            $arrTemp = array_merge($arrOut,$arrTemp);
            return $arrTemp;
        }

				/**
				 * This function is used to change label format of caste for the user 
				 * 
				 */
        private function changeCasteLabelForGrouping($casteArr){
					foreach($casteArr as $key=>$val)
					{
						if($val["key"]=="P_CASTE")
						{
							$casteGroupArr=FieldMap::getFieldLabel("caste_group_array",'',1);
							
							foreach(explode(",",$val["value"]) as $k=>$v)
							{
									
									$label[] = (FieldMap::getFieldLabel("caste_without_religion",$v)).(array_key_exists($v,$casteGroupArr)?"- All":"");
							}
							$casteArr[$key]["label_val"]=implode(", ",$label);
							
							break;
						}
						
					}
					
					return $casteArr;
					
				}
        
}
