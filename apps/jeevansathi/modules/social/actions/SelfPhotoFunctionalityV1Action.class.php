<?php

/**
 * social actions.
 * SelfPhotoFunctionalityV1
 * Controller to perform upload photo/delete photo and set profile photo from app
 * @package    jeevansathi
 * @subpackage social
 * @author     Kumar Anand
 */
class SelfPhotoFunctionalityV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
                if($request->getParameter("sourceMobilePhotoAction")!="1" && $request->getParameter("HTTP_USER_AGENT")!="JsApple")
                        $_SERVER['HTTP_USER_AGENT']="JsAndroid";
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$respObj = ApiResponseHandler::getInstance();
		if($request->getParameter("actionName")=="uploadPhoto")
		{ 
			$profileObj = LoggedInProfile::getInstance('newjs_master');
			$mobileAPiUploadTracking = new MOBILE_API_PHOTO_UPLOAD_APP_TRACKING();
			$mobileAPiUploadTracking->insert($profileObj->getPROFILEID());
			$inputValidateObj->validateUploadPhotoData($request);
                        $output = $inputValidateObj->getResponse();
                        if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
                        {
				$pictureServiceObj = new PictureService($profileObj);

				$uploadSource = $request->getParameter("uploadSource");
				if($uploadSource=="camera"){
                                        if(MobileCommon::isApp()=="A")
                                                $source = "appPicsCamera";
                                        else
                                                 $source = "iOSPicsCamera";
                                }
				elseif($uploadSource=="gallery"){
                                        if(MobileCommon::isApp()=="A")
                                                $source = "appPicsGallery";
                                        else
                                                 $source = "iOSPicsGallery";
                                }
				elseif($uploadSource=="mobGallery")
					$source = "mobPicsGallery";
				else
					$source = "appPicsUpload";
				if($uploadSource=="desktopGallery")
					$source = "computer_noFlash";
				$outputArr = $pictureServiceObj->saveAlbum('',$source); //Save picture

                        	$uploaded_success = $outputArr['ActualFiles']-$outputArr['ErrorCounter'];
				if($uploaded_success == $outputArr['ActualFiles'] &&  !$outputArr['MaxCountError'])
				{
					$output = "Successfully uploaded";
					if($request->getParameter("setProfilePhoto"))
					{
						$whereArr["PICTUREID"] = $outputArr['PIC_ID'];
                                		$whereArr["PROFILEID"] = $profileObj->getPROFILEID();
                                		$currentPicObj = $pictureServiceObj->getPicDetails($whereArr);

                                		$output1=$pictureServiceObj->setProfilePic($currentPicObj[0]);
					}
                                	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				}
				else
				{
					if($outputArr['MaxCountError'])
						$output = "Photo upload limit exceeded";
					elseif($outputArr['SizeErrorCounter'])
                                                $output = "Size error";
					elseif($outputArr['FormatErrorCounter'])
						$output = "Format error";
					else
						$output = "File could not be uploaded";
                                	
					$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				}
                             	$respObj->setResponseBody(array("label"=>$output,"PICTUREID"=>$outputArr['PIC_ID']));
			}
                        else
                        {
                                $respObj->setHttpArray($output);
                        }
                        unset($output);
		}
		elseif($request->getParameter("actionName")=="deletePhoto")
		{
			$inputValidateObj->validateDeletePhotoData($request);
                        $output = $inputValidateObj->getResponse();
                        if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
                        {
				$profileObj = LoggedInProfile::getInstance('newjs_master');
				$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY");
                                $pictureServiceObj = new PictureService($profileObj);
                                $userType = $request->getParameter("userType")=="newMobile"?$request->getParameter("userType"):"other";
				$output = $pictureServiceObj->deletePhoto($request->getParameter("pictureId"),$profileObj->getPROFILEID(),$userType);
				if($output)
					$output = "Successfully deleted";
				else
					$output = "Error in deletion";
				$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                                $respObj->setResponseBody(array("label"=>$output));
			}
                        else
                        {
                                $respObj->setHttpArray($output);
                        }
                        unset($output);
		}
		elseif($request->getParameter("actionName")=="setProfilePhoto")
		{
			$inputValidateObj->validateSetProfilePhotoData($request);
                        $output = $inputValidateObj->getResponse();
                        if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
                        {
                                $profileObj = LoggedInProfile::getInstance('newjs_master');
                                $pictureServiceObj = new PictureService($profileObj);
				
				$whereArr["PICTUREID"] = $request->getParameter("pictureId");
                		$whereArr["PROFILEID"] = $profileObj->getPROFILEID();
                		$currentPicObj = $pictureServiceObj->getPicDetails($whereArr,1);

                		$output=$pictureServiceObj->setProfilePic($currentPicObj[0]);
				if($output)
				{
                                        $output = "Successfully set profile pic";
					$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				}
                                else
				{
                                        $output = "Error in setting profile pic";
					$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				}
                                $respObj->setResponseBody(array("label"=>$output));
                        }
                        else
                        {
                                $respObj->setHttpArray($output);
                        }
                        unset($output);
		}
		elseif($request->getParameter("actionName")=="saveCroppedProfilePic")
                { 
                       $inputValidateObj->validateSaveCroppedProfilePic($request);
                       $output = $inputValidateObj->getResponse();
                       if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
                       {
                               $profileObj = LoggedInProfile::getInstance('newjs_master');
                               $pictureServiceObj = new PictureService($profileObj);
                               $source =  "userCroppedProfilePic";
                               
                               $output = $pictureServiceObj->saveAlbum('',$source); //Save picture
                               
                               $outputArr = explode("**-**",$output);unset($output);
                               if($outputArr[0]==0 && $outputArr[1]>0 && $outputArr[2])
                               {
                                       $output = "Cropped profile pic saved successfully";
                                       $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                               }
                               else
                               {
                                       $output = "Error in saving cropped pic";
                                       $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                               }
                               $respObj->setResponseBody(array("label"=>$output));
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
