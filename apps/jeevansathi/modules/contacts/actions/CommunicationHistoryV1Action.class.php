<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommunicationHistoryV1Action extends sfAction
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A	 request object
	 */
	
	function execute($request)
	{
		$request         = $this->getRequest();
		$this->loginData = $request->getAttribute("loginData");
		
		//Contains logined Profile information;
		$this->loginProfile = LoggedInProfile::getInstance();
	//	$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
		if ($request->getParameter('profilechecksum')) {
			$this->Profile=new Profile();
			$profileid = JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
			$this->Profile->getDetail($profileid,"PROFILEID");
			$communicationObj = new CommunicationHistory($this->loginProfile,$this->Profile);
			$pageNo=$request->getParameter('pageNo');
			$history = $communicationObj->getHistory($pageNo);
			$gender = $this->loginProfile->getGENDER();
			$otherGender = $this->Profile->getGENDER();
			$communicationHistory["history"] = $communicationObj->getResultSetApi($history,$gender,$otherGender);
			$havePhoto=$this->Profile->getHAVEPHOTO();
			if($havePhoto=='Y'){
			    $pictureServiceObj=new PictureService($this->Profile);
			    $profilePicObj = $pictureServiceObj->getProfilePic();
			    if($profilePicObj)
			    {
					$thumbNailArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getThumbailUrl(),'ThumbailUrl','',$otherGender);
            	  	if($thumbNailArray[label] != '')
                   		$thumbNail = PictureFunctions::getNoPhotoJSMS($otherGender,'ProfilePic120Url');
               		else
                   		$thumbNail = $thumbNailArray['url'];
           		}
				else $thumbNail = PictureFunctions::getNoPhotoJSMS($otherGender,'ProfilePic120Url');
			}
			else 
				$thumbNail = PictureFunctions::getNoPhotoJSMS($otherGender,'ProfilePic120Url');
			unset($pictureServiceObj);
			unset($profilePicObj);
			unset($havePhoto);
			unset($thumbNailArray);
			$havePhoto=$this->loginProfile->getHAVEPHOTO();
			if($havePhoto=='Y')
			{
			
			$pictureServiceObj=new PictureService($this->loginProfile);
			$profilePicObj = $pictureServiceObj->getProfilePic();
				if($profilePicObj)
				{
				$thumbNailArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getThumbailUrl(),'ThumbailUrl','',$gender);
				if($thumbNailArray[label] != '')
				$ownthumbNail = PictureFunctions::getNoPhotoJSMS($gender,'ProfilePic120Url');
				else $ownthumbNail = $thumbNailArray['url'];
				}
				else 
				$ownthumbNail = PictureFunctions::getNoPhotoJSMS($gender,'ProfilePic120Url');

			} 
		else 
				$ownthumbNail = PictureFunctions::getNoPhotoJSMS($gender,'ProfilePic120Url');
		
		
			$communicationHistory["viewer"] = $ownthumbNail;
			$communicationHistory["viewed"] = $thumbNail;
			$communicationHistory["label"] = $this->Profile->getUSERNAME();
			$communicationHistory["nextPage"] = $communicationObj->getNextPage();

		}	
		$apiObj                  = ApiResponseHandler::getInstance();
		if($communicationHistory['history'])	
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		else
			$apiObj->setHttpArray(ResponseHandlerConfig::$NO_COMMUNICATION_HISTORY);
		$apiObj->setResponseBody($communicationHistory);
		$apiObj->generateResponse();
		if($request->getParameter('INTERNAL')==1){
			return sfView::NONE;
		} else {
			die;
		}
		
	}
	
}
