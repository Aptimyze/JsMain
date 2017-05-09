<?php
/**
 * ApiIgnoreProfile
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Kunal Verma
 * @date	   24th July 2014
 */
class ApiIgnoreProfileV1Action extends sfActions
{ 
	//Member Variables
	private $m_arrOut		= null;// Array used to store api response
	const UNBLOCK 			= 0;
	const BLOCK 			= 1;
	const STATUS			= 2;
	const IGNORED_LIMIT			= 4000;
        const IGNOREDMESSAGE    ="This profile will be removed from your search results and other lists. This profile will not be able to contact you any further.";
	private $m_iResponseStatus;
	private $loginProfile;
	private $ignoreProfile;
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	//Member Functions
	public function execute($request)
	{
		$this->Process($request);
		//Api Response Object
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray($this->m_iResponseStatus);
		$apiResponseHandlerObj->setResponseBody($this->m_arrOut);	
		$apiResponseHandlerObj->generateResponse();
		
		if($request->getParameter('INTERNAL')==1){
			return sfView::NONE;
		} else {
			die;
		}
	}
	
	/**
	 * Process
	 * Main Function for process request
	 */
	private function Process($request)
	{
		$loginData=$request->getAttribute("loginData");
		if(!$loginData['PROFILEID'])
		{
			//Set Error Message and return false
			$this->m_iResponseStatus = ResponseHandlerConfig::$LOGOUT_PROFILE;
			return false;
		}
		$profileID = $loginData['PROFILEID'];
		$this->loginProfile = new Profile("",$profileID);
		$this->loginProfile->getDetail("","","*");
		if($request->getParameter("profilechecksum"))
		{
			$arrParameter["profilechecksum"] = $request->getParameter("profilechecksum");
			$arrParameter["action"] = $request->getParameter("ignore");
		}
		else
			$arrParameter = $request->getParameter("blockArr");
		$ignoredProfileid = null;
        if($arrParameter['profilechecksum'] && strlen($arrParameter['profilechecksum']) )
        {
            $ignoredProfilechecksum = $arrParameter['profilechecksum'];
            $ignoredProfileid = JsAuthentication::jsDecryptProfilechecksum($ignoredProfilechecksum);
            $this->ignoreProfile = new Profile("",$ignoredProfileid);
			$this->ignoreProfile->getDetail("","","*");
        }
			
		if($ignoredProfileid && intval($ignoredProfileid)   &&
           !is_null($arrParameter['action'])                &&
           $request->isMethod('POST')
          )
		{
			$ignore_Store_Obj = new IgnoredProfiles("newjs_master");
			switch($arrParameter['action'])
			{
				case self::UNBLOCK :
				{
                                         $ignoreCount=JsMemcache::getInstance()->get('IGNORED_COUNT_'.$profileID);
                                         if(is_null($ignoreCount)|| $ignoreCount===false)
                                        {
                                            //changed to library call
                                            $ignoreArr=$ignore_Store_Obj->getCountIgnoredProfiles($profileID);
                                            $ignoreCount=$ignoreArr['CNT'];
                                        
                                        }
                    //changed to library call
					$ignore_Store_Obj->undoIgnoreProfile($profileID,$ignoredProfileid);
					JsMemcache::getInstance()->remove($profileID);
					JsMemcache::getInstance()->remove($ignoredProfileid);
                                        $ignoreCount--;
					JsMemcache::getInstance()->set('IGNORED_COUNT_'.$profileID,$ignoreCount);    
					$page["source"] = $request->getParameter("pageSource");
					$buttonObj = new ButtonResponse($this->loginProfile,$this->ignoreProfile,$page);
					$button = $buttonObj->getButtonArray();
					$this->m_iResponseStatus = ResponseHandlerConfig::$SUCCESS;                                     
					if(empty($button["buttons"]))
					{
						$responseArray["buttondetails"] = $button;
						$buttonDetails = ButtonResponse::buttonDetailsMerge($responseArray);
						$this->m_arrOut["buttondetails"] = $buttonDetails;
						$arr["notused"]= "true";
						$this->m_arrOut["actiondetails"] = ButtonResponse::actionDetailsMerge($arr);
					}
					else
						$this->m_arrOut["buttondetails"] = $button;
					$this->m_iResponseStatus = ResponseHandlerConfig::$SUCCESS;
					$this->m_arrOut=array_merge($this->m_arrOut,array('status'=>"0",'message'=>null,'button_after_action'=>$button));
					//changed to library call
					$isIgnored = $ignore_Store_Obj->ifIgnored($ignoredProfileid,$profileID,ignoredProfileCacheConstants::BYME);

					if(!$isIgnored) {
						$this->contactObj = new Contacts($this->loginProfile, $this->ignoreProfile);
						if ($this->contactObj->getTYPE() == ContactHandler::ACCEPT) {
							$type = "ACCEPTANCE";
						}
						if ($this->contactObj->getTYPE() == ContactHandler::INITIATED) {
							$type = "INITIATE";
						}
						if ($type) {
							if ($this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID()) {
								$sender = $this->loginProfile;
								$receiver = $this->ignoreProfile;
							} else {
								$receiver = $this->loginProfile;
								$sender = $this->ignoreProfile;
							}
							//Entry in Chat Roster
							try {
								$producerObj = new Producer();
								if ($producerObj->getRabbitMQServerConnected()) {
									$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => $type, 'body' => array('sender' => array('profileid' => $sender->getPROFILEID(), 'checksum' => JsAuthentication::jsEncryptProfilechecksum($sender->getPROFILEID()), 'username' => $sender->getUSERNAME()), 'receiver' => array('profileid' => $receiver->getPROFILEID(), 'checksum' => JsAuthentication::jsEncryptProfilechecksum($receiver->getPROFILEID()), "username" => $receiver->getUSERNAME()))), 'redeliveryCount' => 0);
									$producerObj->sendMessage($chatData);
								}
								unset($producerObj);
							} catch (Exception $e) {
								throw new jsException("Something went wrong while sending instant EOI notification-" . $e);
							}

							//End
						}
					}
					break;

				}
				case self::BLOCK :
				{
                                    
                                    $ignoreCount=JsMemcache::getInstance()->get('IGNORED_COUNT_'.$profileID);
                                    if(is_null($ignoreCount) || $ignoreCount===false)
                                    {
                                        $ignoreArr=$ignore_Store_Obj->getCountIgnoredProfiles($profileID);
                                        $ignoreCount=$ignoreArr['CNT'];
                                        JsMemcache::getInstance()->set('IGNORED_COUNT_'.$profileID,$ignoreCount);    
                                        
                                    }
                                    
                                    if($ignoreCount>=self::IGNORED_LIMIT)
                                    {
                                        $this->m_iResponseStatus = ResponseHandlerConfig::$IGNORED_MESSAGE;
					$this->m_arrOut=array('status'=>"1",'message'=>"BLOCK LIMIT REACHED");
					break;
                                    }   
                                        
                                        $ignore_Store_Obj->ignoreProfile($profileID,$ignoredProfileid);
					JsMemcache::getInstance()->remove($profileID);
					JsMemcache::getInstance()->remove($ignoredProfileid);
                                        $ignoreCount++;
					JsMemcache::getInstance()->set('IGNORED_COUNT_'.$profileID,$ignoreCount);    
                                        
					$this->m_iResponseStatus = ResponseHandlerConfig::$SUCCESS;
					$button["buttons"]["primary"][] = ButtonResponseJSMS::getIgnoreButton("","" , 1, 1);
					$button["buttons"]["other"] = null;
					$responseArray["confirmLabelMsg"] = $this->getIgnoreMessage();
					$responseArray["confirmLabelHead"] = "Profile moved to Blocked Member's list";
					$responseArray["infomsglabel"] = "You have blocked this user";
 					if(MobileCommon::isApp()=="I"){
						$pictureServiceObj=new PictureService($this->ignoreProfile);
	                    $profilePicObj = $pictureServiceObj->getProfilePic();
	                    if($profilePicObj)
	                            $thumbNail = $profilePicObj->getThumbailUrl();
	                    $iphoto = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,"Th
	                    	umbailUrl","",$this->ignoreProfile->getGENDER())['url'];
	                    $button["photo"]=ButtonResponseJSMS::getPhotoDetail($iphoto);
	                }
	                if(MobileCommon::getChannel() == "P")
	                {
	                	$params["isIgnored"] = 1;
	                	$responseArray["buttons"][0] = ButtonResponse::getIgnoreButton("",$params);
	                }
	             if(MobileCommon::isApp() == "A")
	                {
	                	$params["isIgnored"] = 1;
	                	$responseArray["buttons"][0] = ButtonResponseApi::getIgnoreButton("",'','Y',true,'Undo Ignore');
	                }
					$buttonDetails = ButtonResponse::buttonDetailsMerge($responseArray);
					$actionDetails = ButtonResponse::actionDetailsMerge(array("notused"=>1));
					$this->m_arrOut=array('status'=>"1",'message'=>$this->getIgnoreMessage(),'button_after_action'=>$button,'buttondetails'=>$buttonDetails);
					//Entry in Chat Roster
					try {
						$producerObj = new Producer();
						if ($producerObj->getRabbitMQServerConnected()) {
							$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'BLOCK', 'body' => array('sender' => array('profileid'=>$this->loginProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->loginProfile->getPROFILEID()),'username'=>$this->loginProfile->getUSERNAME()), 'receiver' => array('profileid'=>$this->ignoreProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->ignoreProfile->getPROFILEID()),"username"=>$this->ignoreProfile->getUSERNAME()))), 'redeliveryCount' => 0);
							$producerObj->sendMessage($chatData);
						}
						unset($producerObj);
					} catch (Exception $e) {
						throw new jsException("Something went wrong while sending instant EOI notification-" . $e);
					}

					//End
					break;
				}
				case self::STATUS : 
				{
					$bStatus = ($ignore_Store_Obj->ifIgnored($profileID,$ignoredProfileid,ignoredProfileCacheConstants::BYME))?1:0;
					$this->m_iResponseStatus = ResponseHandlerConfig::$SUCCESS;
					$this->m_arrOut=array('status'=>"$bStatus");
					break;
				}
				default :
				{
					$this->m_iResponseStatus = ResponseHandlerConfig::$FAILURE;
					$this->m_arrOut = array('error'=>"not a valid request.");
					return false;
				}
			}
		}
		else
		{
			//Set Error Message and return false
			$this->m_iResponseStatus = ResponseHandlerConfig::$FAILURE;
			$this->m_arrOut = array('error'=>"not a valid request.");
			return false;
		}
		return true;	
	}

	private function getIgnoreMessage()
	{
		$username = $this->ignoreProfile->getUSERNAME();
		$message = str_ireplace("ABCD1234", $username, self::IGNOREDMESSAGE);
		return $message;

	}
}


