<?php

/**
 * social actions.
 * get profile pic
 * @package    jeevansathi
 * @subpackage social
 * @author     Esha Jain
 */
class GetProfilePicV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	* This api handles 3 cases
	(a) When profilechecksum is not passed then self album is being viewed and urls and pictureid is returned
	(b) When profilechecksum is not passed and onlyCount parameter is passed then only count is sent as response
	(c) When profilechecksum is passed then other profile's album is being viewed and only urls are returned 
	*/
	public function execute($request)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY,GENDER");
		$pictureServiceObj=new PictureService($profileObj);
		$ProfilePicUrlObj = $pictureServiceObj->getProfilePic();
		$profilePicUrl='';
		if (is_subclass_of($ProfilePicUrlObj, 'Picture'))
		{
			$profilePicPictureId = $ProfilePicUrlObj->getPICTUREID();
			$profilePicUrl = $ProfilePicUrlObj->getProfilePic235Url();
			if(!$profilePicUrl)
				$profilePicUrl = $ProfilePicUrlObj->getMainPicUrl();
		}
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody(array("profilePicUrl"=>$profilePicUrl,"profilePicPictureId"=>$profilePicPictureId,"label"=>"success profile pic"));
		$respObj->generateResponse();
		die;
	}
}
