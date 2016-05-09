<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MessageHandleV1Action extends sfAction
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
		$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
		if ($request->getParameter('profilechecksum')) {
			$messageCommunication = new MessageCommunication('',$this->loginProfile->getPROFILEID());
			$messageCommunication->insertMessage();
			if(!$messageCommunication->obsceneMessage())
			{
				$draft = $request->getParameter("draft");
				$type  = $request->getParameter('type');
				$LastSentMessageObj = new LastSentMessage();
				$LastSentMessageObj->insertMessage($this->loginProfile->getPROFILEID(),$type,$draft);
				
				if($type=="R")
				{
					//send eoi reminder notification with reminder message set by paid member
					$senderName = $this->loginProfile->getUSERNAME();
					$senderProfileid = $this->loginProfile->getPROFILEID();
					$receiverid = $request->getParameter("receiver");
	    			//$instantNotificationObj =new InstantAppNotification("EOI_REMINDER");
	    			//$instantNotificationObj->sendReminderInstantAppNotification($senderName,$receiverid,$senderProfileid,$draft); 
	    			//unset($instantNotificationObj); 
    			}
			}
			$array["isSent"] = "true";
		}	
		$apiObj                  = ApiResponseHandler::getInstance();
		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiObj->setResponseBody($array);
		$apiObj->generateResponse();
		die;
	}
	
}
