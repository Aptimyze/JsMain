<?php

/**
 * social actions.
 * RequestPhotoV1
 * Controller to perform request photo from app
 * @package    jeevansathi
 * @subpackage social
 * @author     Kumar Anand
 */
class RequestPhotoV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$respObj = ApiResponseHandler::getInstance();
		if($request->getParameter("actionName")=="requestPhoto")
		{
			$inputValidateObj->validateRequestPhotoData($request);
                        $output = $inputValidateObj->getResponse();
                        if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
                        {
				$profileid = JsAuthentication::jsDecryptProfilechecksum($request->getParameter("profileChecksum"));
                                $viewerObj = Profile::getInstance("newjs_master",$profileid);
                                $viewerObj->getDetail("","","USERNAME,PRIVACY,GENDER");
                                
                                $senderProfileObj=LoggedInProfile::getInstance('newjs_master');
                                
                                $ignoredProfileObj = new IgnoredProfiles();
                                
                                if($ignoredProfileObj->ifIgnored($profileid,$senderProfileObj->getPROFILEID())){
                                        $output="blocked";
                                        $blockedError = PhotoMessagesEnum::PHOTO_REQUEST_POG_BLOCKED_PG;
                                        $headerMsg = PhotoMessagesEnum::PHOTO_REQUEST_BLOCKED;
                                }
                                elseif($ignoredProfileObj->ifIgnored($senderProfileObj->getPROFILEID(),$profileid)){
                                        $output="blocked";
                                        $blockedError = PhotoMessagesEnum::PHOTO_REQUEST_PG_BLOCKED_POG;
                                        $headerMsg = PhotoMessagesEnum::PHOTO_REQUEST_BLOCKED;
                                }
                                elseif($viewerObj->getACTIVATED() == ProfileEnums::PROFILE_HIDDEN){
                                        $output="blocked";
                                        $blockedError = PhotoMessagesEnum::PHOTO_REQUEST_POG_IS_HIDDEN;
                                        $headerMsg = PhotoMessagesEnum::PHOTO_REQUEST_HIDDEN;
                                }
                                elseif($viewerObj->getACTIVATED() == ProfileEnums::PROFILE_DELETED){
                                        $output="blocked";
                                        $blockedError = PhotoMessagesEnum::PHOTO_REQUEST_POG_IS_DELETED;
                                        $headerMsg = PhotoMessagesEnum::PHOTO_REQUEST_DELETED;
                                }
                                else{ 
                                        $picServiceObj = new PictureService($viewerObj);
                                        $output = $picServiceObj->performPhotoRequest();
                                }
                                
				if($output == "Success")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_SUCCESS_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
				}
                                elseif($output == "SameGender")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
					$actionDetails["headerlabel"] = PhotoMessagesEnum::PHOTO_REQUEST_SAME_GENDER_HEADER;
					$actionDetails["errmsgiconid"] = 17;
					$actionDetails["errmsglabel"] = PhotoMessagesEnum::PHOTO_REQUEST_SAME_GENDER;
				}
                                elseif($output == "FilteredProfile")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
					$actionDetails["headerlabel"] = PhotoMessagesEnum::PHOTO_REQUEST_FILTERED_PROFILE_HEADER;
					$actionDetails["errmsgiconid"] = 12;
					$actionDetails["errmsglabel"] = PhotoMessagesEnum::PHOTO_REQUEST_FILTERED_PROFILE;
				}
                                elseif($output == "ExceededLimit")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
					$actionDetails["headerlabel"] = PhotoMessagesEnum::PHOTO_REQUEST_LIMIT_EXCEEDED_HEADER;
					$actionDetails["errmsgiconid"] = 16;
					$actionDetails["errmsglabel"] = PhotoMessagesEnum::PHOTO_REQUEST_LIMIT_EXCEEDED;
				}
                                elseif($output == "SenderNotActivated")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
					$actionDetails["headerlabel"] = PhotoMessagesEnum::PHOTO_REQUEST_SENDER_NOT_ACTIVATED_HEADER;
					$actionDetails["errmsgiconid"] = 16;
					$actionDetails["errmsglabel"] = PhotoMessagesEnum::PHOTO_REQUEST_SENDER_NOT_ACTIVATED;
				}
                                elseif($output == "NotLogin")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
					$actionDetails["headerlabel"] = null;
					$actionDetails["errmsgiconid"] = null;
					$actionDetails["errmsglabel"] = PhotoMessagesEnum::PHOTO_REQUEST_LOGOUT;
				}
                                elseif($output == "blocked")
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
					$imageButtonDetail["url"] = null;
					$imageButtonDetail["action"] = null;
					$actionDetails["headerlabel"] = $headerMsg;
					$actionDetails["errmsgiconid"] = 12;
					$actionDetails["errmsglabel"] = str_replace("<PoGID>",$viewerObj->getUSERNAME(),$blockedError);
				}
				else
				{
					$imageButtonDetail["label"] = PhotoMessagesEnum::PHOTO_REQUEST_INITIATE_HEADER;
                                        $imageButtonDetail["url"] = null;
                                        $imageButtonDetail["action"] = null;
				}


					$memObject=JsMemcache::getInstance();
					$memObject->delete('commHistory_'.$profileid.'_'.$senderProfileObj->getPROFILEID());
					$memObject->delete('commHistory_'.$senderProfileObj->getPROFILEID().'_'.$profileid);



				if($actionDetails && is_array($actionDetails))
					$outputArr["actionDetails"] = ButtonResponse::actionDetailsMerge($actionDetails);
				else
					$outputArr["actionDetails"] = null;
				$outputArr["imageButtonDetail"] = $imageButtonDetail;
                                $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                             	$respObj->setResponseBody($outputArr);
			}
                        else
                        {
                                $respObj->setHttpArray($output);
                        }
                        unset($output);
		}
               	$respObj->generateResponse();
		unset($inputValidateObj);
		die;
    	}
}
