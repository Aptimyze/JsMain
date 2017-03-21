<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class communicationSyncAction extends sfAction
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A	 request object
	 */
	
	function execute($request)
	{
		$this->loginData = $request->getAttribute("loginData");
		$this->loginProfile = LoggedInProfile::getInstance();
		$arr=JsMemcache::getInstance()->getHashOneValue("lastCommunicationId",$this->loginProfile->getPROFILIED());
		$responseArray=json_decode($arr);
	
		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiObj->setResponseBody($responseArray);
		$apiObj->generateResponse();
	
		die;
	}



}
