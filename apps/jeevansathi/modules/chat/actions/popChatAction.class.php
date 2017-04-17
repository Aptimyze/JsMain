<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class popChatAction extends sfAction
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A	 request object
	 */
	
	function execute($request)
	{
		
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();

		if ($request->getParameter("actionName")=="popChat")
		{
			$inputJSON = file_get_contents('php://input');
			$input= json_decode( $inputJSON, TRUE );
			
			
			
				$sender=substr($input['from'],0,strrpos($input['from'] ,"@"));
				$receiver=substr($input['to'],0,strrpos($input['to'] ,"@"));
				$request->setParameter("sender",$sender);
			//	$message=$input['msg'];
				$request->setParameter("communicationType",'C');
				$communicationType='C';
				$msgIdNo=$input['messageId'];
			//	$request->setParameter("message",$message);
			
			$inputValidateObj->validatePopChat($request);
			$output = $inputValidateObj->getResponse();
			
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				//echo $sender."--".$receiver."--".$message;die;
				//Contains logined Profile information;
				/*$this->senderProfile = new Profile("",$sender);
				$this->senderProfile->getDetail($sender, "PROFILEID");
				$this->receiverProfile= new Profile("",$receiver);
				$this->receiverProfile->getDetail($receiver, "PROFILEID");*/
					
				$js_communication=new JS_Communication($sender,$receiver,$communicationType,$message);
				$arr=$js_communication->getCommunication($msgIdNo);
				if($arr)
				{
					if(count($arr)<JS_Communication::$RESULTS_PER_PAGE_CHAT)
						$responseArray["pagination"] = 0;
					else
						$responseArray["pagination"] = 1;
					$responseArray["Message"] = json_encode($arr);
				}
				else
					$responseArray["pagination"] = 0;
				
				if($sender!="" && $receiver!=""){
	
					$loginProfileObj = new Profile();
					$loginProfileObj->getDetail($sender, "PROFILEID", "*");
	
					$otherProfileObj = new Profile();
					$otherProfileObj->getDetail($receiver, "PROFILEID", "*");
					
					if(!$otherProfileObj->getPROFILE_STATE()->getPaymentStates()->isPAID() && !$loginProfileObj->getPROFILE_STATE()->getPaymentStates()->isPAID()) {
						$responseArray["canChat"] = "false";
					}
				}
				else{
					$responseArray["canChat"] = "false";
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


	function executePopChat($request)
	{
		echo "getMessageHistory";die;
		
	}
}

