<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class WriteMessagev1Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
  function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="WriteMessage")
		{
			$inputValidateObj->validateContactActionData($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$this->loginData    = $request->getAttribute("loginData");
				//Contains logined Profile information;
				$this->loginProfile = LoggedInProfile::getInstance();
				$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
				
				$msgId=$request->getParameter("MSGID");
				$chatId=$request->getParameter("CHATID");
				$pagination=$request->getParameter("pagination");
				if($request->getParameter("hasNext"))
					$limit=CONTACT_ELEMENTS::PAGINATION_LIMIT;
				
				if ($this->loginProfile->getPROFILEID()) {
					$this->userProfile = $request->getParameter('profilechecksum');
					if ($this->userProfile) {
						
						$this->Profile = new Profile();
						$profileid     = JsCommon::getProfileFromChecksum($this->userProfile);
						$this->Profile->getDetail($profileid, "PROFILEID");
						$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					}
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'M',ContactHandler::PRE);
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$messageLogObj = new MessageLog();
					if($limit && $pagination){
						$dbName = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
						$chatLogObj = new NEWJS_CHAT_LOG($dbName);
						$msgDetailsArr = $messageLogObj->getMessageHistoryPagination($this->loginProfile->getPROFILEID(),$profileid,$limit,$msgId);
						$chatDetailsArr = $chatLogObj->getMessageHistory($this->loginProfile->getPROFILEID(),$profileid,$limit,$chatId);
						//print_r($chatDetailsArr);
						//print_r($msgDetailsArr);die;
						if(count($chatDetailsArr))
						{
							$messageDetailsArr=array_merge($msgDetailsArr,$chatDetailsArr);
							//print_r($messageDetailsArr);die;
							usort($messageDetailsArr, function ($a, $b)	{		$t1 = strtotime($a['DATE']);		$t2 = strtotime($b['DATE']);		return $t2 - $t1;	}  );
							//print_r($messageDetailsArr);die;
							if(count($messageDetailsArr)>20){
								$messageDetailsArr=array_slice($messageDetailsArr,0,20);
								$nextPaginationCall=true;
							}
							else
								$nextPaginationCall=false;
				
						}
					}
					else{
						$messageDetailsArr = $messageLogObj->getMessageHistory($this->loginProfile->getPROFILEID(),$profileid);
						$nextPaginationCall=false;
					}
					$count = $messageLogObj->markMessageSeen($this->loginProfile->getPROFILEID(),$profileid);
					if($count>0)
					{
						$profileMemcacheServiceObj = new ProfileMemcacheService($this->loginProfile);
						$profileMemcacheServiceObj->update("MESSAGE_NEW",-1);
						$profileMemcacheServiceObj->updateMemcache();
					}
					$responseArray = $this->getContactArray($messageDetailsArr,$request,$pagination);
					if($pagination){
						if($nextPaginationCall)
							$responseArray['hasNext'] = true;
						else
							$responseArray['hasNext'] = false;
					}
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
	
	private function getContactArray($messageDetailsArr,$request,$pagination=false)
	{
			
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		if(!empty($messageDetailsArr))
		{
			
			foreach ($messageDetailsArr as $key=>$value)
			{
				$arr["messages"][$key]["message"] = $value["MESSAGE"];
				$arr["messages"][$key]["time"] = JsCommon::ESItoIST($value["DATE"]);
				if($this->loginProfile->getPROFILEID() == $value["SENDER"])
					$arr["messages"][$key]["mymessage"] = "true";
				else
					$arr["messages"][$key]["mymessage"] = "false";
				if($pagination){
					if($value["CHATID"])
						$arr["CHATID"]=$value["ID"];
					else
						$arr["MSGID"]=$value["ID"];
				}
			}
			if($pagination){
				if(!$arr["CHATID"] && $request->getParameter("CHATID"))
					$arr["CHATID"]=$request->getParameter("CHATID");
				if(!$arr["MSGID"] && $request->getParameter("MSGID"))
					$arr["MSGID"]=$request->getParameter("MSGID");	
			}
		}
		else
		{
			/*if($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y")
			{
				$arr["systemMessage"]["header"] = "header";
				$arr["systemMessage"]["message1"] = "message1";
				$arr["systemMessage"]["message2"] = "message2";
				$arr["systemMessage"]["message3"] = "message3";
			}*/
		}
		
		if($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y")
			$arr["cansend"] = "true";
		elseif($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] == "N")
		{
			$memHandlerObj = new MembershipHandler();
			$data2 = $memHandlerObj->fetchHamburgerMessage($request);
			$MembershipMessage = $data2['hamburger_message']['top']; 
			$arr["cansend"] = "false";
			if(strpos($request->getParameter("newActions"), "MEMBERSHIP")!== false )
			{
				$arr["button"]["label"]  = "To Write messages, Buy paid membership";
				$arr["button"]["value"] = "";
				$arr["button"]["action"] = "MEMBERSHIP";
				$arr["button"]["text"] = $MembershipMessage;
			}
			else
			{
				$arr["button"]["label"] = "To be able to Write messages, Call us to Buy paid membership";
				$arr["button"]["value"] = "18004196299";
				$arr["button"]["action"] = "CALL";
			}
			
		}
		else
			$arr["cansend"] = "false";
		$arr["label"] = $this->Profile->getUSERNAME();
		$pictureServiceObj=new PictureService($this->Profile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		if($profilePicObj)
			$thumbNail = $profilePicObj->getThumbailUrl();
		if(!$thumbNail)
			$thumbNail = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$this->Profile->getGENDER());
		unset($pictureServiceObj);
		unset($profilePicObj);
		$pictureServiceObj=new PictureService($this->loginProfile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		if($profilePicObj)
			$ownthumbNail = $profilePicObj->getThumbailUrl();
		if(!$ownthumbNail)
			$ownthumbNail = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$this->loginProfile->getGENDER());
		$arr["viewer"] = $ownthumbNail;
		$arr["viewed"] = $thumbNail;
			
		$contactArr1["systemMessage"] = null;
		$contactArr1["cansend"] = null;
		$contactArr1["label"] = null;
		$contactArr1["viewer"]=null;
		$contactArr1["viewed"]=null;
		$contactArr1["messages"]=null;
		$contactArr1["button"]=null;
		
		$finalContactDetailArr = array_merge($contactArr1,$arr);
		return $finalContactDetailArr;
		
	}
}

