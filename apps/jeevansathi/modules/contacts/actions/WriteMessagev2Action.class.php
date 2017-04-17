<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class WriteMessagev2Action extends sfAction
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
				$msgId=$request->getParameter("MSGID");
				$chatId=$request->getParameter("CHATID");
				$pagination=$request->getParameter("pagination");
				if($request->getParameter("pagination"))
					$limit=CONTACT_ELEMENTS::PAGINATION_LIMIT;

				//Contains logined Profile information;
				$this->loginProfile = LoggedInProfile::getInstance();
		//		$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
				
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
								if(count($msgDetailsArr))
										$messageDetailsArr=array_merge($msgDetailsArr,$chatDetailsArr);
								else
										$messageDetailsArr=$chatDetailsArr;
								//print_r($messageDetailsArr);die;
						}
						else
							$messageDetailsArr=$msgDetailsArr;
						//print_r($messageDetailsArr);die;
						usort($messageDetailsArr, function ($a, $b)	{		$t1 = strtotime($a['DATE']);		$t2 = strtotime($b['DATE']);		return $t2 - $t1;	}  );
						//print_r($messageDetailsArr);die;
						if(count($messageDetailsArr)>20){
							$messageDetailsArr=array_slice($messageDetailsArr,0,20);
							$nextPaginationCall=true;
						}
						else
							$nextPaginationCall=false;
						$countChat = $chatLogObj->markChatSeen($this->loginProfile->getPROFILEID(),$profileid);
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
					if(MobileCommon::getChannel() == "P")
					{
						$tupleName = "INBOX_APP";
						$tupleService = new TupleService();
						$tupleService->setLoginProfileObj($this->loginProfile);
						$userList["INTEREST_RECEIVED"][$profileid] = Array("PROFILEID"=>$profileid);
						$tupleFields            = $tupleService->getFields($tupleName);
						$tupleService->setProfileInfo($userList,$tupleFields);
						unset($this->userList);
						$tuplesValues = $tupleService->getINTEREST_RECEIVED();
						$profileDisplay =  $this->getProfileDisplayData($tuplesValues[$profileid]);
					}
					$responseArray = $this->getContactArray($messageDetailsArr,$request,$pagination);
					if($pagination){
						if($nextPaginationCall)
							$responseArray['hasNext'] = true;
						else
							$responseArray['hasNext'] = false;
					}
					$responseArray['profile'] = $profileDisplay;
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
				$arr["messages"][$key]["message"] = CommonUtility::strip_selected_tags($value["MESSAGE"],'script');
				$timeValue			  = JsCommon::ESItoIST($value["DATE"]);
				$arr["messages"][$key]["time"] 	  = $timeValue ;
				$dateValueArr 			  = @explode(" ",$timeValue);
				$timeTxtVal 			  = CommonUtility::convertDateToDayDiff($value["DATE"]);
				if($this->loginProfile->getPROFILEID() == $value["SENDER"]){
					$arr["messages"][$key]["mymessage"] = "true";
					$arr["messages"][$key]["timeTxt"] =$timeTxtVal;
				}
				else{
					$arr["messages"][$key]["mymessage"] = "false";
					$arr["messages"][$key]["timeTxt"] =$timeTxtVal;
				}
				if($pagination){
					if($value["CHATID"])
						$arr["CHATID"]=$value["ID"];
					else
						$arr["MSGID"]=$value["ID"];
				}
			}
			if($pagination){
				$arr["messages"]=array_reverse($arr["messages"]);
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
		else//if($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] == "N")
		{
			$memHandlerObj = new MembershipHandler();
		$data2 = $memHandlerObj->fetchHamburgerMessage($request);
		$MembershipMessage = $data2['hamburger_message']['top'];
			$arr["cansend"] = "false";
			$arr["button"]["label"]  = "View Membership Plans";
			$arr["button"]["value"] = "";
			$arr["button"]["action"] = "MEMBERSHIP";
			$arr["button"]["text"] = $MembershipMessage;
			
		}
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
		$arr["viewed"] = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,"ThumbailUrl","",$this->Profile->getGENDER())['url'];
			
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

	private function getProfileDisplayData($profileDisplay)
	{
           
		$display["PROFILECHECKSUM"] = $profileDisplay->PROFILECHECKSUM;
		$display["USERNAME"] = $profileDisplay->USERNAME;
		$display["AGE"] = $profileDisplay->AGE;
		$display["HEIGHT"] = $profileDisplay->HEIGHT;
		$display["GENDER"] = $profileDisplay->GENDER;
		$display["OCCUPATION"] = $profileDisplay->OCCUPATION;
		$display["LOCATION"] = $profileDisplay->CITY;
		$display["MSTATUS"] = $profileDisplay->MSTATUS;
		$display["RELIGION"] = $profileDisplay->RELIGION;
		$display["CASTE"] = $profileDisplay->CASTE;
		$display["SUBCASTE"] = $profileDisplay->SUBCASTE;
		$display["MTONGUE"] = $profileDisplay->SUBCASTE;
		$display["INCOME"] = $profileDisplay->INCOME;
		$display["edu_level_new"] = (new MailerService())->getEducationDetails($profileDisplay->PROFILEID);
		$display["ProfilePic120Url"] = $profileDisplay->ProfilePic120Url;
		$display['subscription_icon'] = $profileDisplay->getsubscription_icon();
		$display['userloginstatus'] = $profileDisplay->getuserloginstatus();
		return array_change_key_case($display,CASE_LOWER);
		
	}
	
}

