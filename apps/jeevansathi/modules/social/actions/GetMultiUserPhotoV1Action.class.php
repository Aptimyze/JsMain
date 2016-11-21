<?php
/**
 * social actions.
 * get ........................
 * @package    jeevansathi
 * @subpackage social
 * @author     Lavesh Rawat
 */
class GetMultiUserPhotoV1Action extends sfActions
{ 
        protected $PHOTO_DISPLAY = array('C');
        protected $PROFILE_PRIVACY_FILTERED = array('F');
        
	public function execute($request)
	{
		/*
		$pidArr["PROFILEID"] ='5547372,8914646,8953994,1,2,3,4';
		$photoType = 'MainPicUrl';
		*/
		$profiles = $request->getParameter("profiles");
		$pid = array_keys($profiles);
		$pid = implode(",", $pid);
		$contactsBetweenViewedAndViewer = "";
		$skipPrivacyFilterArr = "";
		
		$photoType =  $request->getParameter("photoType");
		$photoTypeArr = explode(",",$photoType);
		$whitelistedPhotoTypes = array_keys(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR);
		foreach($photoTypeArr as $k=>$v)
		{
//VA Whitelisting
			if($v!='' && !in_array($v,$whitelistedPhotoTypes))
			{
				SendMail::send_email("eshajain88@gmail.com,lavesh.rawat@gmail.com","apps/jeevansathi/modules/social/actions/GetMultiUserPhotoV1Action.class.php phototype not whitelisted and came as".$v." for profile ".$pid,"GetMultiUserPhotoV1Action.class.php phototype not whitelisted");
				$photoTypeArr[$k]="ProfilePic120Url";
			}

		}
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$inputValidateObj->validateGetMultiUserPhotoV1Action($request);
		$finalArr = $inputValidateObj->getResponse();
		if($finalArr["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
		{
			$profileObj=LoggedInProfile::getInstance('newjs_master');
			if($pid)
				$pidArr["PROFILEID"] =  $pid;
			else
			{
				$pidArr["PROFILEID"] =  $profileObj->getPROFILEID();
				$selfPicture=1;
			}

			$multipleProfileObj = new ProfileArray();
			$pidArrTemp = $pidArr["PROFILEID"];

			$pidArrTemp = explode(",",$pidArr["PROFILEID"]);
			
			/***/
			if(!$selfPicture)
			{
				foreach($pidArrTemp as $profileId)
				{
					if($profiles[$profileId]["GROUP"] == "intsent"){
						$contactsBetweenViewedAndViewer[$profileId] = 'I';
					}
					else if($profiles[$profileId]["GROUP"] == "intrec"){
						$contactsBetweenViewedAndViewer[$profileId] = 'RI';
						$skipPrivacyFilterArr[] = $profileId;
					}
					else if($profiles[$profileId]["GROUP"] == "acceptance"){
						$contactsBetweenViewedAndViewer[$profileId] = 'A';
						$skipPrivacyFilterArr[] = $profileId;
					}
					else{
						$contactsBetweenViewedAndViewer[$profileId] = '';
					}
					$key = $photoType."_".$profileId;
					$temp = JsMemcache::getInstance()->get($key);
					if($temp===false)
					{
						$key = $photoType."_".$profileObj->getPROFILEID()."_".$profileId;
						$temp = JsMemcache::getInstance()->get($key);
						//echo $profileId."::";
					}
					else
					{
					}
					if($temp!==false)
					{
						if($temp=='')
							$finalArr['profiles'][$profileId]['PHOTO'] = $temp;	
						else
							$finalArr['profiles'][$profileId]['PHOTO'][$photoType] = $temp;
						$ignoreArr[] = $profileId;
					}
				}
			}
			if($ignoreArr)
			{
				unset($pidArr);
				$pidArrTemp = array_diff($pidArrTemp,$ignoreArr);
				if(is_array($pidArrTemp) && count($pidArrTemp)>0)
					$pidArr["PROFILEID"] = implode(",",$pidArrTemp);
			}
			/***/
			if($pidArr)
			{
				$profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pidArr);

                                $photoDisp = array();
                                foreach($profileDetails as $profileDetail){
                                        if(in_array($profileDetail->getPHOTO_DISPLAY(),$this->PHOTO_DISPLAY) || in_array($profileDetail->getPRIVACY(), $this->PROFILE_PRIVACY_FILTERED)){
                                                $photoDisp[$profileDetail->getPROFILEID()] = $profileDetail->getPHOTO_DISPLAY();
                                        }
                                }

				$multiplePictureObj = new PictureArray($profileDetails);
				if(!$selfPicture){
					$photosArr = $multiplePictureObj->getProfilePhoto('N', '','','','',$contactsBetweenViewedAndViewer,'','',$skipPrivacyFilterArr);
				}
				else{
					$photosArr = $multiplePictureObj->getProfilePhoto();
				}

				if(is_array($pidArrTemp))
				foreach($pidArrTemp as $profileId)
				{
					$photoObj = $photosArr[$profileId];
					if($selfPicture)
						$profileId=0;
					if($photoObj)
					{
						foreach($photoTypeArr as $k=>$v)
						{
							eval('$temp =$photoObj->get'.$v.'();');
							if($temp)
								$finalArr['profiles'][$profileId]['PHOTO'][$v] = $temp;
							else
								$finalArr['profiles'][$profileId]['PHOTO'][$v] = '';
							/****/
                                                        PictureFunctions::photoUrlCachingForChat($profileId, $photoDisp, $photoType, $profileObj->getPROFILEID(), "set", $temp);
							/****/
							unset($temp);
						}
					}
					else
					{
						$finalArr['profiles'][$profileId]['PHOTO'] = '';
                                                PictureFunctions::photoUrlCachingForChat($profileId, $photoDisp, $photoType, $profileObj->getPROFILEID(), "set", "");
					}
				}
			}
		}
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody($finalArr);
		$respObj->generateResponse();
		die;
	}
}
