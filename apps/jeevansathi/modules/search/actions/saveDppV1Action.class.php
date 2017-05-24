<?php

/**
 * social actions.
 * get profile pic
 * @package    jeevansathi
 * @subpackage search
 * @author     Esha Jain
 */
class saveDppV1Action extends sfActions
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
		sfContext::getInstance()->getController()->getPresentationFor('search', 'saveDpp');
		$response = ob_get_contents();
		ob_end_clean();
		$res = json_decode($response,true);
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody(array("label"=>"success","done"=>$res['done']));
		$respObj->generateResponse();
		die;
	}
}
