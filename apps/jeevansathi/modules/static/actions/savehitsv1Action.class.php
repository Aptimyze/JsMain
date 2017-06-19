<?php

/**
 * Auto Select actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: actions.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
/**
 * Auto Select feature.<p></p>
 * 	
 *  
 * @author Nikhil dhiman
 */

class savehitsv1Action extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$apiObj=ApiResponseHandler::getInstance();
		$now = date("Y-m-d G:i:s");
                $ip=CommonFunction::getIP();
		$dbMisHits= new MIS_HITS();
		$pageName=$request->getParameter("pagename");
		if(!$pageName)
			$pageName="unknown";
		$source=$request->getParameter("source");
		if(!$source)
			$source="android";
                
                $showConfOnReg = array('showConfirmation'=>1);
                $dbMisHits->insertRecord($source,$now,$pageName,$ip);
		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $apiObj->setResponseBody($showConfOnReg);
		$apiObj->generateResponse();
		die;
	}
}
?>
