<?php
/**
* This class will handle picture validation result   
* @author Kumar Anand
*/
class PictureModuleInputValidate extends ValidationHandler
{
	private $response;

	public function getResponse()
	{
		return $this->response;
	}

	/*
        This function validates the POST parameters for /social/getAlbum url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateGetAlbumData($request)
        {
                $pattern1 = "/^([a-zA-Z])+$/";
                $pattern2 = "/^([A-Za-z0-9])+$/";
		$profileChecksum = $request->getParameter("profileChecksum");
		$contactType = $request->getParameter("contactType");
		$onlyCount = $request->getParameter("onlyCount");
		if($profileChecksum && (!preg_match($pattern2,$profileChecksum) || !JsAuthentication::jsDecryptProfilechecksum($profileChecksum)))
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(1) : Reason(profilechecksum invalid)";
			ValidationHandler::getValidationHandler("",$errorString);
			$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}
		elseif(!$profileChecksum)
		{
			$profileObj=LoggedInProfile::getInstance('newjs_master');
                	if(!$profileObj || !$profileObj->getPROFILEID())
                	{
				$errorString = "picture/validation/PictureModuleInputValidate.class.php(2) : Reason(noprofilecheck + nologgedin)";
				ValidationHandler::getValidationHandler("",$errorString);
				$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
			}
		}
	
		if($contactType)
		{
			if(!preg_match($pattern1,$contactType))
			{
				$errorString = "picture/validation/PictureModuleInputValidate.class.php(3) : Reason(contactType invalid)";
				ValidationHandler::getValidationHandler("",$errorString);
				$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
			}
		}
		if($onlyCount && $onlyCount!="Y")
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(4): Reason(onlyCount invalid)";
			ValidationHandler::getValidationHandler("",$errorString);
			$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}
		
                if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
        }

	/*
        This function validates the POST parameters for /social/requestPhoto url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateRequestPhotoData($request)
        {
		$pattern1 = "/^([a-zA-Z])+$/";
                $pattern2 = "/^([A-Za-z0-9])+$/";
		$profileChecksum = $request->getParameter("profileChecksum");
		if(!$profileChecksum || !preg_match($pattern2,$profileChecksum) || !JsAuthentication::jsDecryptProfilechecksum($profileChecksum))
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(5): Reason(profilechecksum invalid) ";
			ValidationHandler::getValidationHandler("",$errorString);
                        $resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}

		if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
	}

	/*
        This function validates the POST parameters for /social/uploadPhoto url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateUploadPhotoData($request)
        {
		$pattern1 = "/^([A-Za-z])+$/";

		if(!$_FILES || !$_FILES["photo"] || !is_array($_FILES["photo"]) || $_FILES["photo"]["error"]>0)
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(6) : Reason(invalid _FILES)";
			ValidationHandler::getValidationHandler("",$errorString);
			$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;			
		}
		
		if($request->getParameter("setProfilePhoto") && $request->getParameter("setProfilePhoto")!="Y")
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(7) : Reason(setProfilePhoto invalid)";
			ValidationHandler::getValidationHandler("",$errorString);
			$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}

		$uploadSource = $request->getParameter("uploadSource");
		if(!$uploadSource || !preg_match($pattern1,$uploadSource))
		{
			//$errorString = "picture/validation/PictureModuleInputValidate.class.php(8) : Reason(uploadSource invalid)";
			//ValidationHandler::getValidationHandler("",$errorString);
			//$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}
		
		if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
	}

	/*
        This function validates the POST parameters for /social/deletePhoto url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateDeletePhotoData($request)
        {
                $pattern1 = "/^([0-9])+$/";
		$pictureId = $request->getParameter("pictureId");

		if(!preg_match($pattern1,$pictureId))
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(9) : Reason(invalid pictureId)";
			ValidationHandler::getValidationHandler("",$errorString);
                       	$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}

		if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
	}

	/*
        This function validates the POST parameters for /social/setProfilePhoto url and set the response in the class variable
        @param - sfWebRequest object
        */
        public function validateSetProfilePhotoData($request)
        {
		$pattern1 = "/^([0-9])+$/";
                $pictureId = $request->getParameter("pictureId");

                if(!preg_match($pattern1,$pictureId))
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(10) : Reason(invalid pictureId)";
			ValidationHandler::getValidationHandler("",$errorString);
                   	$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}

		if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
	} 
	
	/**
        * This function validates the POST parameters for /social/saveCroppedProfilePic url and set the response in the class variable
        * @param - sfWebRequest object
        */
	public function validateSaveCroppedProfilePic($request) 
	{ 
		if(!$_FILES) 
		{ 
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(6) : Reason(invalid _FILES)"; 
			ValidationHandler::getValidationHandler("",$errorString); 
			$resp = ResponseHandlerConfig::$POST_PARAM_INVALID; 
		} 
 
		if(!$resp) 
			$this->response = ResponseHandlerConfig::$SUCCESS; 
		else 
			$this->response = $resp; 
	} 

        public function validateGetMultiUserPhotoV1Action($request)
        {
		$pid =  $request->getParameter("pid");
                $photoType =  $request->getParameter("photoType");

		if(!$request->getParameter("photoType"))
		{
			$errorString = "picture/validation/PictureModuleInputValidate.class.php(1) : validateGetMultiUserPhotoV1Action($pid) ($photoType)";
			ValidationHandler::getValidationHandler("",$errorString);
			$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
		}
		else
		{
			$photoType =  $request->getParameter("photoType");
			$photoTypeArr = explode(",",$photoType);
                        foreach($photoTypeArr as $k=>$v)
			{	
				if(!in_array($v,ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS))
				{
					$errorString = "picture/validation/PictureModuleInputValidate.class.php(2) : validateGetMultiUserPhotoV1Action($pid) ($photoType)";
					ValidationHandler::getValidationHandler("",$errorString);
					$resp = ResponseHandlerConfig::$POST_PARAM_INVALID;
				}
			}
		}

		if(!$resp)
                        $this->response = ResponseHandlerConfig::$SUCCESS;
                else
                        $this->response = $resp;
	}
}
?>
