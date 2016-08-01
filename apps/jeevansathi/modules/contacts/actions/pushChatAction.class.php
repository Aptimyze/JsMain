<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pushChatAction extends sfAction
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A	 request object
	 */
	
	function execute($request)
	{
		
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("module"));
		$apiObj                  = ApiResponseHandler::getInstance();

		if ($request->getParameter("action")=="pushChat")
		{
			
			$inputJSON = file_get_contents('php://input');
			$input= json_decode( $inputJSON, TRUE );
			ValidationHandler::getValidationHandler("", $inputJSON);
				$sender=substr($input['from'],0,strrpos($input['from'] ,"@"));
				$receiver=substr($input['to'],0,strrpos($input['to'] ,"@"));
				$message=$input['msg'];
				$chatID=$input['id'];
				$request->setParameter("communicationType",'C');
				$communicationType='C';
				if($message)
					$request->setParameter("message",$message);
				//echo $request->setParameter("chatID",$chatID);die;
			
			$inputValidateObj->validatePushChat($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				//echo $sender."--".$receiver."--".$message;die;
				//Contains logined Profile information;
				$this->senderProfile = new Profile("",$sender);
				$this->senderProfile->getDetail($sender, "PROFILEID");
				$this->receiverProfile= new Profile("",$receiver);
				$this->receiverProfile->getDetail($receiver, "PROFILEID");
					
				$js_communication=new JS_Communication($this->senderProfile,$this->receiverProfile,$communicationType,$message,$chatID);
				$Id=$js_communication->storeCommunication();
				if($Id)
				{
					$responseArray["isSent"] = "true";
					$responseArray["msgId"] = $Id;
					$responseArray["chatId"] = $chatID;
				}
			}
		}
		if (is_array($responseArray)) {
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->setResponseBody($responseArray);
			$apiObj->generateResponse();
		}
		else
		{
			if(is_array($output))
				$apiObj->setHttpArray($output);
			else
				$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiObj->generateResponse();
		}
		die;
	}



}
