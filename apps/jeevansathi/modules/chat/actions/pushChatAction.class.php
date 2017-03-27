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
		
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();

		if ($request->getParameter("actionName")=="pushChat")
		{
			
			$inputJSON = file_get_contents('php://input');
			$input= json_decode( $inputJSON, TRUE );
			ValidationHandler::getValidationHandler("", $inputJSON);
				$sender=substr($input['from'],0,strrpos($input['from'] ,"@"));
				$receiver=substr($input['to'],0,strrpos($input['to'] ,"@"));
				$message=addslashes(stripslashes(htmlspecialchars($input['msg'],ENT_QUOTES)));
				$chatID=$input['id'];
				$ip=$input['ip'];
				$request->setParameter("communicationType",'C');
				$request->setParameter("chatID",$chatID);
				$communicationType='C';
				if($message)
					$request->setParameter("message",$message);
				//echo $request->setParameter("chatID",$chatID);die;
			
			$inputValidateObj->validatePushChat($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$data["from"] = $sender;
				$data["to"] = $receiver;
				$data["communicationType"] = $communicationType;
				$data["message"] = $message;
				$data['chatid'] = $chatID;
				$data['ip'] = $ip;
				$date = date("Y-m-d H:i:s");
				$data['date'] = $date;
				$js_communication=new JS_Communication($sender,$receiver,$communicationType,$message,$chatID,$ip,$date);

				if($js_communication->validateChat()) {
					try {
						//send instant JSPC/JSMS notification
						$producerObj = new Producer();
						if ($producerObj->getRabbitMQServerConnected()) {
							$chatData = array('process' => 'CHATMESSAGE', 'data' => array('type' => 'PUSH', 'body' => $data), 'redeliveryCount' => 0);
							$producerObj->sendMessage($chatData);
							//Add for contact roster
						} else {
							//echo $sender."--".$receiver."--".$message;die;
							//Contains logined Profile information;
							$js_communication->storeCommunication();
						}
						unset($producerObj);
					} catch (Exception $e) {
						throw new jsException("Something went wrong while pushing chat message-" . $e);
					}
					$responseArray["isSent"] = "true";
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
			/*if(is_array($output))
				$apiObj->setHttpArray($output);
			else*/
				$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiObj->generateResponse();
		}
		die;
	}



}
