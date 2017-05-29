<?php

/**
 * chat actions.
 *
 * @package    jeevansathi
 * @subpackage chat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class chatActions extends sfActions
{
	/**
	 * Executes authenticateChatSession action  - returns jid,sid and rid for chat session
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeAuthenticateChatSessionV1(sfWebRequest $request)
	{
		$xmppPrebind = new XmppPrebind('localhost', 'http://localhost:7070/http-bind/', 'converse', false, false);
		$username = substr("a1@localhost", 0, 2);
		$xmppPrebind->connect($username, '123');
		$xmppPrebind->auth();
		$response = $xmppPrebind->getSessionInfo(); // array containing sid, rid and jid

		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	public function executeLogChatListingFetchTimeoutV1(sfwebrequest $request){
		$username = $request->getParameter("username");
        $cookie = $_SERVER["HTTP_COOKIE"];
        $uagent = $_SERVER["HTTP_USER_AGENT"];
		if($username){
			$chatLoggingObj = new Chat();
	        $chatLoggingObj->storeChatTimeoutProfiles($username,$cookie,$uagent);
	        unset($chatLoggingObj);
	    }
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	public function executeChatUserAuthenticationV1(sfWebRequest $request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$loginData = $request->getAttribute("loginData");
		if ($loginData) {

			$username = $loginData['PROFILEID'];
			if($username && $username!= "0" && is_null($username)== false && empty($username)== false) {
				//$uname = $loginData['USERNAME'];

				$pass = md5($username);
				//$pass = EncryptPassword::generatePassword("test".$username);
				//$pass = "test".$username;

				$url = JsConstants::$openfireConfigInternal['HOST'] . ":" . JsConstants::$openfireConfigInternal['PORT'] . "/plugins/restapi/v1/users/" . $username;
				//$url = "http://localhost:9090/plugins/restapi/v1/users/".$username;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$headers = array();
				$headers[] = 'Authorization: ' . JsConstants::$openfireRestAPIKey;
				$headers[] = 'Accept: application/json';

				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$curlResult = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($curlResult, true);
				$response["fc"] = $result;
				if ($result['username'] && !is_array($result["properties"])) {
					//User exists
					$response['userStatus'] = "User exists";
					$response['hash'] = $pass;
					$apiResponseHandlerObj->setHttpArray(ChatEnum::$userExists);
                    $type = "created";
				} else {
					//create user
					$response['userStatus'] = "Added";
					$profileImporterObj = new Chat();
					$profileImporterObj->addNewProfile($username);
					$apiResponseHandlerObj->setHttpArray(ChatEnum::$addedToQueue);
                    $type="new";
				}
                $memcacheKey = JsMemcache::getInstance()->get($username.'_CHAT_USER');
                if(!$memcacheKey)
                {
                    $chatLoggingObj = new Chat();
                    $chatLoggingObj->storeLoggedInUserContacts($username,$type);
                    unset($chatLoggingObj);
                    JsMemcache::getInstance()->set($username.'_CHAT_USER',"1",36000);
                }
			}
			else{
				$response = "Logged Out Profile";
				$apiResponseHandlerObj->setHttpArray(ChatEnum::$invalidParameter);
			}	
		} else {
			$response = "Logged Out Profile";
			$apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
		}
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	public function executeFetchCredentialsV1(sfWebRequest $request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$loginData = $request->getAttribute("loginData");
		if ($loginData) {
			//$username = $loginData['USERNAME'];
			//$jid = $username."@localhost";
			$jid = $request->getParameter('jid'); //Will be commented later nitish
			$response['jid'] = $jid;
			$response['password'] = '123';
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		} else {
			$response = "Logged Out Profile";
			$apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
		}
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	public function executeFetchVCardV1(sfwebrequest $request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$loginData = $request->getAttribute("loginData");
		if ($loginData) {
			$jid = $request->getParameter('jid');
			if (is_array($jid)) {
				foreach ($jid as $key => $val) {
					$username .= $val . ",";
				}
				$username = rtrim($username, ",");

				$vcardDetailsObj = new chat_ofVcard();
				$storeResult = $vcardDetailsObj->getVCardDetails($username);
				unset($vcardDetailsObj);

				$chatObj = new Chat();
				$result = $chatObj->convertXml($storeResult);
				unset($chatObj);
				$username = $request->getParameter('username');
				$profile["$username"]["NAME"] = "Atul";
				$profile["$username"]["EMAIL"] = "Atul@gmail.com";
				$profile["$username"]["PHOTO"] = "http://mediacdn.jeevansathi.com/3418/10/68370525-1468221044.jpeg";
				$profile["$username"]["AGE"] = "3";
				$profile["$username"]["HEIGHT"] = "5 9";
				$profile["$username"]["PROFFESION"] = "Doctor";
				$profile["$username"]["SALARY"] = "Rs. 15 - 20lac";
				$profile["$username"]["CITY"] = "New Delhi";
				$profile["$username"]["COMMUNITY"] = "Brahmin";
				$profile["$username"]["EDUCATION"] = "B.Tech";
				$d1["action"] = "INITIATE";
				$d1["label"] = "Send Interest";
				$d1["iconid"] = null;
				$d1["primary"] = "true";
				$d1["secondary"] = "true";
				$d1["params"] = "&stype=P17";
				$d1["enable"] = true;
				$d1["id"] = "INITIATE";
				$buttons["buttons"][] = $d1;

				$profile["$username"]["buttonDetails"] = $buttons;
				$response = array("vCard" => $profile);

				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			} else {
				$apiResponseHandlerObj->setHttpArray(ChatEnum::$invalidFormat);
			}
		} else {
			$response = "Logged Out Profile";
			$apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
		}
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
	}


	public function executeGetRosterDataV1(sfwebrequest $request)
	{
		$profileid = $request->getParameter("profileid");
		$type = $request->getParameter("type");
		if($type=='DPP')
		{
			
			$this->forward("chat","getDppDataV1");
		}
		else	
		{
			$limit = $request->getParameter("limit");
			$profileObj = new Profile("",$profileid);
			$profileObj->getDetail($profileid, "PROFILEID", "USERNAME");
			$getRosterDataObj = new GetRosterData($profileid);
			$getData["profiles"] = $getRosterDataObj->getRosterDataByType($type, $limit);
			$getData["count"] = count($getData["profiles"]);
			$getData["USERNAME"] = $profileObj->getUSERNAME();
			$getData["PROFILECHECKSUM"] = JsCommon::createChecksumForProfile($profileid);
			$apiResponseHandlerObj = ApiResponseHandler::getInstance();
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);

			$apiResponseHandlerObj->setResponseBody($getData);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
	}

	public function executeGetProfileDataV1(sfwebrequest $request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$loginData = $request->getAttribute("loginData");
		if ($loginData) {

			$profileid = JsCommon::getProfileFromChecksum($request->getParameter("profilechecksum"));
			//$profileid = $request->getParameter("profileid");
			$profile = new Profile();
			$profile->getDetail($profileid, "PROFILEID", "*");


			//Photo logic
			$pidArr["PROFILEID"] = $profileid;
			//$photoType = 'MainPicUrl';
            $photoType = 'ProfilePic235Url';
			$profileObj = LoggedInProfile::getInstance('newjs_master', $loginData["PROFILEID"]);
			$multipleProfileObj = new ProfileArray();
			$profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pidArr);
			$multiplePictureObj = new PictureArray($profileDetails);
			$photosArr = $multiplePictureObj->getProfilePhoto();
			$photo = '';
			$photoObj = $photosArr[$profileid];
			if ($photoObj) {
				$photoType = preg_replace('/[^A-Za-z0-9\. -_,]/', '', $photoType);
				eval('$temp =$photoObj->get' . $photoType . '();');
                if(! (strstr($temp, '_vis_') || strstr($temp, 'photocomming') || strstr($temp, 'filtered')) )
                    $photo = $temp;
				unset($temp);
			}
			//Ends here


			$response = array(
				"jid" => $profile->getPROFILEID(),
				"username" => $profile->getUSERNAME(),
				"age" => $profile->getAGE() . " Years",
				"height" => $profile->getDecoratedHeight(),
				"religion" => $profile->getDecoratedReligion(),
				"caste" => $profile->getDecoratedCaste(),
				"mtongue" => $profile->getDecoratedCommunity(),
				"education" => $profile->getDecoratedEducation(),
				"occupation" => $profile->getDecoratedOccupation(),
				"income" => $profile->getDecoratedIncomeLevel(),
				//"city" => $profile->getDecoratedCity(),
                                "location" => ($profile->getDecoratedCity() ?: $profile->getDecoratedCountry()),
				"photo" => $photo
			);
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);

		} else {
			$response = "Logged Out Profile";
			$apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
		}
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;

	}

	public function executeGetDppDataV1(sfwebrequest $request)
	{
		$profileid = $request->getParameter("profileid");
		$photoType = $request->getParameter("photoType");
		$limit = $request->getParameter("limit");
		$currentPage = $request->getParameter("currentPage");
		$dontShowFilteredProfiles = $request->getParameter("dontShowFilteredProfiles");

		/***/
		/*
		if (!$photoType)
			$photoType = 'MainPicUrl';
		*/
		if (!$dontShowFilteredProfiles)
			$dontShowFilteredProfiles = 1;
		if (!$limit)
			$limit = 50;
		if (!$currentPage)
			$currentPage = 1;
		$completeResponse = 1;
		/***/
		$dontShowShortlisted = 1; // Set to 0 if shorlisted need to be shown
		
		$profileObj = LoggedInProfile::getInstance('', $profileid);
		$profileObj->getDetail('', '', '*');
		$partnerObj = new SearchCommonFunctions();
		$i=0;
		for($j=1;$j>=0;$j--)
		{
			$showOnlineOnly = $j; // Show which are online only
			$obj = $partnerObj->getMyDppMatches(sort, $profileObj, $limit, $currentPage, $paramArr, $removeMatchAlerts, $dontShowFilteredProfiles, $twoWayMatches, $clustersToShow, $results_orAnd_cluster, $notInProfiles, $completeResponse,'',$dontShowShortlisted,$showOnlineOnly);
			$arr = $obj->getResultsArr();
			if ($arr) {
				
				foreach ($arr as $k => $v) {
					if($i>=$limit)
						break;
					$cArr[$i]["PROFILEID"] = $v["id"];
					$cArr[$i]["USERNAME"] = $v["USERNAME"];
					$cArr[$i]["PROFILECHECKSUM"] = jsAuthentication::jsEncryptProfilechecksum($v["id"]);
					$i++;
	
				}
			}
		}
		$getData["profiles"] = $cArr;
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($getData);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	public function executeSendEOIV1(sfwebrequest $request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$this->loginData = $request->getAttribute("loginData");
		$this->loginProfile = LoggedInProfile::getInstance();
		if ($this->loginProfile->getPROFILEID()) {
			$this->userProfile = $request->getParameter('profilechecksum');
			if ($this->userProfile) {
				$this->Profile = new Profile();
				$profileid = JsCommon::getProfileFromChecksum($this->userProfile);
				$date = date("Y-m-d H:i:s");
				$ip = FetchClientIP();
				$chatid = $request->getParameter('chat_id');
				$chatMessage = $request->getParameter('chatMessage')."--".$date."--".$ip."--".$chatid;
				
				$chatNotification[$this->loginProfile->getPROFILEID()."_".$profileid]=json_encode(array("msg"=>$request->getParameter('chatMessage'),"ip"=>$ip,"from"=>$this->loginProfile->getPROFILEID(),"id"=>$chatid,"to"=>$profileid));

				$this->Profile->getDetail($profileid, "PROFILEID");
				$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'I',ContactHandler::POST);
				$privilegeArray = $this->contactHandlerObj->getPrivilegeObj()->getPrivilegeArray();
				if($request->getParameter('chatMessage') && CONTACTS::isObscene($request->getParameter('chatMessage')))
				{
					$response["cansend"] = true;
					$response['sent'] = false;
					$response["errorMsg"] = "Message not delivered, Please try later";
					$responseArray["cansend"] = true;
					$responseArray['sent'] = false;
					$responseArray["errmsglabel"] = "Message not delivered, Please try later";
					$response["actiondetails"] = ButtonResponseApi::actionDetailsMerge($responseArray);
					$response["buttondetails"] = ButtonResponseApi::buttonDetailsMerge(array());
				}
				else if ($this->contactObj->getTYPE() == ContactHandler::INITIATED && $this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID()) {
					if($privilegeArray["0"]["SEND_REMINDER"]["MESSAGE"] != "Y")
					{
						$response["cansend"] = false;
						$response['sent'] = false;
						$response["errorMsg"] = "Only paid members can start chat";
					}
					else {
						$messageLogObj = new messageLog();
						$message = $messageLogObj->getEOIMessagesForChat($this->loginProfile->getPROFILEID(), array($profileid));
						$msgText = $message[0]["MESSAGE"];
						$forCount = explode("||", $msgText);
						$count = count($forCount);
						if ($count >= 3) {
							$response["cansend"] = false;
							$response['sent'] = false;
							$response["errorMsg"] = "You can send more messages if user replies";
							$responseArray['cansend']=false;
							$responseArray['sent']=false;

							$responseArray["infomsglabel"] = "You can send more messages if user replies";
							$response["actiondetails"] = ButtonResponseApi::actionDetailsMerge(array());
							$response["buttondetails"] = ButtonResponseApi::buttonDetailsMerge($responseArray);
						} else {
							if ($msgText)
								$msgText = $msgText . "||" . $chatMessage;
							else {
								$msgText = $chatMessage;
							}

							$_GET["messageid"] = $message[0]["ID"];
							sfContext::getInstance()->getRequest()->setParameter("messageid", $message[0]["ID"]);
							$_GET["chatMessage"] = $msgText;
							$messageCommunication = new MessageCommunication('', $this->loginProfile->getPROFILEID());
							$messageCommunication->insertMessage();
							JsMemcache::getInstance()->setHashObject("lastChatMsg",$chatNotification);
							$count++;
							if ($count < 3) {
								$response["cansend"] = true;
								$responseArray['cansend']=true;
								$responseArray['sent']=true;
								if(sfContext::getInstance()->getRequest()->getParameter("page_source") == "chat" && sfContext::getInstance()->getRequest()->getParameter("channel") == "A") {
									$androidText = true;
								}
								else
									$androidText = false;

									$buttonResponse = new ButtonResponse($this->loginProfile,$this->Profile,array(),$this->contactHandlerObj);
									$responseArray["buttons"][] = $buttonResponse->getInitiatedButton($androidText);
									$response["actiondetails"] = ButtonResponseApi::actionDetailsMerge(array());
									$response["buttondetails"] = ButtonResponseApi::buttonDetailsMerge($responseArray);

							} else {
								$response["cansend"] = false;
								$response['sent'] = true;
								$response["errorMsg"] = "You can send more messages if user replies";
								$responseArray['cansend']=false;
								$responseArray['sent']=true;

								$responseArray["infomsglabel"] = "You can send more messages if user replies";
								$response["actiondetails"] = ButtonResponseApi::actionDetailsMerge(array());
								$response["buttondetails"] = ButtonResponseApi::buttonDetailsMerge($responseArray);
							}
							$response['sent'] = true;
							$response["messageid"] = $message[0]["ID"];
						}
					}
				}
				else {
					ob_start();
					$request->setParameter('INTERNAL', 1);
					$request->setParameter("actionName","postEOI");
					$request->setParameter("moduleName","contacts");
					$request->setParameter('chatMessage',$chatMessage);
					$request->setParameter("setFirstEoiMsgFlag",true);
					if($request->getParameter("page_source") == "chat" && $request->getParameter("channel") == "A")
					{
						$data  = sfContext::getInstance()->getController()->getPresentationFor('contacts', 'postEOIv1');
					}
					else {
						$data = sfContext::getInstance()->getController()->getPresentationFor('contacts', 'postEOIv2');
					}
					$output = ob_get_contents();
					ob_end_clean();
					$response = json_decode($output, true);
					$response["buttondetails"]["cansend"] = true;
					$response["buttondetails"]["sent"] = true;
					//$response["cansend"] = true;
					//$response['sent'] = true;

				}
			}
		} else {
			$response = "Logged Out Profile";
			$apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
		}
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

    /*
     * Get user's name to be shown after login
     */
    public function executeSelfNameV1(sfwebrequest $request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$loginData = $request->getAttribute("loginData");
		if ($loginData) {
            $profileid = $loginData['PROFILEID'];
            $nameOfUserOb=new NameOfUser();
            $nameOfUserArr = $nameOfUserOb->getNameData($profileid);
            $nameOfUser = $nameOfUserArr[$profileid]["NAME"];
            if(!$nameOfUser){
                $nameOfUser = $loginData['USERNAME'];
            }
            $response["name"] = $nameOfUser;
        }
        else{
            $response = "Logged Out Profile";
			$apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
        }
        $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
        die;
    }
}

?>
