<?php
/**
 * Library For Detailed Controller
 * 
 */
 
/**
 * Class DetailActionLib
 * The Library For Detailed Action Controllers 
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @version    09-12-2013
 */
class DetailActionLib
{
	/**
	 * DoHorscope_Check
	 * @param void
	 * @return void
	 * @access Public static
	 */ 
	public static function DoHorscope_Check()
	{
	}
	
	/**
	 * Calculate Next Previous Link for Current Profile ID
	 * 
	 * @param $actionObject : Action Controller Object
	 * @return void
	 * @access Public static
	 */
	public static function Show_Next_Previous($actionObject)
	{
		//if($actionObject->profile)
			ProfileCommon::showNextPrev($actionObject);
	}
	
	/**
	 * fillProfileData
	 * 
	 * This is a Helper Function Used by ProfileCommon::showNextPrev()
	 * but Call is made by the actionObject in ProfileCommon::showNextPrev()
	 * So for providing same interface across the Desktop and AppAPI Classes
	 * Actual call made from $actionObject->setViewed() Function
	 * 
	 * @param $actionObject : Action Controller Object
	 * @param $iProfileid   : Integer
	 * @return void
	 * @throws jsException
	 * @access Public static
	 */
	public static function fillProfileData($iProfileid,$actionObject)
	{
		if($iProfileid)
		{
			$actionObject->profile=Profile::getInstance();
			$actionObject->profile->getDetail($iProfileid,"PROFILEID","","RAW");
		}
		else
				throw new jsException("Please pass on the profileid");
	}
	
	
	//TODO Process BreadCrump Navigation 
	//public static function BreadCrump_Navigation();
	
	//TODO : After Disccusion
	//Check For No Profile Case and if Yes then Also Forward
	// It also call the Depandant Legacy Code 
	// e.g. call to commonVariables() of DetailedAction.class.php
	
	/**
	 * IsNoProfile
	 * 
	 * @param $actionObject : Action Controller Object
	 * @param $szFromWhere  : String
	 * @return void
	 * @access Public static
	 */
	public static function IsNoProfile($actionObjects, $szFromWhere)
	{
		$x=ProfileCommon::checkViewed($actionObjects,$szFromWhere);
		if($x)
			return $x;
	}
	
	/**
	 * LogThisAction
	 * 
	 * Trigger Logging mechanism - Update View Log Tables
	 * @param $actionObject : Action Controller Object
	 * @return void
	 * @access Public static
	 */
	public static function LogThisAction($actionObject)
	{
		return;
		//Insert a entry in View Log///////////////////////////////////////////////////////////////
		if($actionObject->loginProfile->getPROFILEID())
		{	
			
			$privacy=$actionObject->loginProfile->getPRIVACY();
			$vlt=new VIEW_LOG_TRIGGER();
                        $producerObj = new Producer();
			//Privacy is not C for login user 
			if($privacy!='C' && $actionObject->loginProfile->getPROFILEID()!=$actionObject->profile->getPROFILEID() && $actionObject->loginProfile->getGENDER()!=$actionObject->profile->getGENDER())
			{       
                                if($producerObj->getRabbitMQServerConnected())
                                    $triggerOrNot = "inTrigger";
                                else
                                    $vlt->updateViewTrigger($actionObject->loginProfile->getPROFILEID(),$actionObject->profile->getPROFILEID());
			}
                        elseif($producerObj->getRabbitMQServerConnected())
                            $triggerOrNot="notInTrigger";
                        
                        if($producerObj->getRabbitMQServerConnected()){
                            $queueData = array('process' =>MessageQueues::VIEW_LOG,'data'=>array('type' => $triggerOrNot,'body'=>array('VIEWER'=>$actionObject->loginProfile->getPROFILEID(),VIEWED=>$actionObject->profile->getPROFILEID())), 'redeliveryCount'=>0 );
                            $producerObj->sendMessage($queueData);
                        }
                        else
                            $vlt->updateViewLog($actionObject->loginProfile->getPROFILEID(),$actionObject->profile->getPROFILEID());
		}
		/////////////////////////////////////////////////////////////////////////////////////////
	}
	
	//TODO Online Status Check 
	//public static function CheckOnlineStatus();
	
	//TODO Source Tracking
	//public static function TrackSource();
	
	/**
	 * Update_ViewCount
	 * 
	 * Update no. of times profiles viewed.
	 * Helps in scoring algorithm
	 * @param $refProfileObj : Profile Object(refernce)
	 * @return void
	 * @access Public static
	 */
	public static function Update_ViewCount(&$refProfileObj)
	{
                if($refProfileObj->getGENDER()=='M')
                    $queueName = 'nTimesMaleQueue';
                else
                    $queueName = 'nTimesFemaleQueue';
                $memcacheObj = JsMemcache::getInstance();
                $memcacheObj->lpush($queueName,$refProfileObj->getPROFILEID());
                /*
		include(sfConfig::get("sf_web_dir")."/profile/ntimes_function.php");
		
		if($refProfileObj->getPROFILEID()){
                    $jpNtimesObj = new NEWJS_JP_NTIMES();
                    $jpNtimesObj->updateProfileViews($refProfileObj->getPROFILEID());
                }
                */
	}
	
	/**
	 * Seting Profile PIC and ALBUM Count
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access Public static
	 */
	private static function SetPicAndAlbumCount($actionObject)
	{
    if(false == MobileCommon::isOldMobileSite())
      return ;
    
		$login=0;
		
		if($actionObject->loginProfile->getPROFILEID())
			$login=1;
		//contact_status will be initalzed by call to IsNoProfile
		$return=ProfileCommon::getprofilePicnCnt($actionObject->profile,$actionObject->contact_status,$login);
		
		$actionObject->PHOTO=$return[0];
		$actionObject->ALBUM_CNT=$return[1];
		$actionObject->stopAlbumView=$return[2];
	}
	
	/**
	 * Seting Profile PIC and ALBUM Count For API
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access Public static
	 */
	public static function GetProfilePicForApi($actionObject)
	{
		$login=0;
		
		if($actionObject->loginProfile->getPROFILEID())
			$login=1;
		$bOwnProfile = false;
		if($actionObject->loginProfile->getPROFILEID() ===  $actionObject->profile->getPROFILEID())
			$bOwnProfile = true;

		$bPhotoReq = $actionObject->PHOTO_REQUESTED ? 'Y' : 'N';
		//contact_status will be initalzed by call to IsNoProfile
		$return=ProfileCommon::getprofilePicForApi($actionObject->profile,$actionObject->contact_status,$login,$bPhotoReq);
		
		$actionObject->PHOTO=$return[0];
		$actionObject->ALBUM_CNT=$return[1];
		$actionObject->stopAlbumView=$return[2];
		$actionObject->IsMainPic = $return[3];
		$actionObject->THUMB_URL = $return['THUMB_URL'];
		$actionObject->PIC120_URL = $return['PIC120_URL'];
		$actionObject->PIC_MSG = ($bOwnProfile)?null:$actionObject->PHOTO['label'];
		$actionObject->PIC_URL = $actionObject->PHOTO['url'];
		$actionObject->PIC_ACTION = ($bOwnProfile)?null:$actionObject->PHOTO['action'];
	}
	
	/**
	 * GetAlbum 
	 * Fill the Album array with the URLs of Album pics
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return Array 
	 * @access Public static
	 */
	public static function GetAlbum($actionObject)
	{
		
		$objPic_Service=new PictureService($actionObject->profile);
		$album=$objPic_Service->getAlbum($actionObject->contact_status);
		
		if($album && is_array($album))
		{
			foreach($album as $k=>$v)
				$albumArr[] = $v->getMainPicUrl();
		}
		unset($objPic_Service);
		return $albumArr;
	}
	
	/**
	 * Check Contact Limit Reached
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access private static
	 */
	private static function CheckContactLimit($actionObject)
	{
    if(false == MobileCommon::isOldMobileSite())
      return ;
    
		ProfileCommon::contactLimitReached($actionObject);
	}
	
	
	/**
	 * Viewed profie is bookmarked or not.
	 * Presence of viewer and viewed is necessary
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access Public static
	 */
	public static function IsBookmarkedOrIgnore($actionObject)
	{
		
		$sender=$actionObject->loginProfile->getPROFILEID();
		$receiver=$actionObject->profile->getPROFILEID();
		if($sender && $receiver)
		{
                        $viewProfileOptimization = viewProfileOptimization::getInstance($sender,$receiver);
                        $bookmarkStatus = $viewProfileOptimization->getBookmarkStatus();
                        if(isset($bookmarkStatus)){
                            if($bookmarkStatus)
                                $actionObject->BOOKMARKED=1;
                            else
                                $actionObject->BOOKMARKED=0;
                        }
                        else{
                            $bookmark= new NEWJS_BOOKMARKS();
                            if($bookmark->isBookmarked($sender,$receiver))
                              $actionObject->BOOKMARKED=1;
                            else
                              $actionObject->BOOKMARKED=1;
                        }
			$ignStatus = $viewProfileOptimization->getIgnoreProfileStatus();
			if(isset($ignStatus))
			{
				if($ignStatus>0)
				        $actionObject->IGNORED=$ignStatus;
			}
			else
			{
				$ignore=new IgnoredProfiles();
				if($ignore->ifIgnored($sender,$receiver,ignoredProfileCacheConstants::BYME))
				{
				        $actionObject->IGNORED=1;
			        }
			        if(!isset($actionObject->IGNORED) && $ignore->ifIgnored($receiver,$sender,ignoredProfileCacheConstants::BYME))
                	        {
					$actionObject->IGNORED=2;
	      		        }
			}
		}
	}
	
	/**
	 *
	 * IsPhotoRequested()
	 * Check IsPhotoRequested
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access private static
	 */
	private static function IsPhotoRequested($actionObject)
	{
		if($actionObject->loginProfile->getPROFILEID() && ($actionObject->profile->getHAVEPHOTO() =='N'||$actionObject->profile->getHAVEPHOTO() ==''))
		{
			$photoRequestSender[] =$actionObject->loginProfile->getPROFILEID();
			$photoRequestReceiver[] = $actionObject->profile->getPROFILEID(); 
			$pictureObj = new PictureService($actionObject->loginProfile);
			$photoRequestResults = $pictureObj->getIfPhotoRequested($photoRequestSender, $photoRequestReceiver);
			$photoRequests = $photoRequestResults['receivedByViewer'];
			$photosRequested = $photoRequestResults['sentByViewer'];
			if($photosRequested[$actionObject->profile->getPROFILEID()] == 1)	   
				$actionObject->PHOTO_REQUESTED = 1;
			else
				$actionObject->PHOTO_REQUESTED = 0;
		}
	}
	
	/**
	 * UpdateAndLog - 	Common Function For API and Desktop Site for
	 * 					Updating Logs and Tables A
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access Public static
	 */
	public static function UpdateAndLog($actionObject)
	{
		$actionName = sfContext::getInstance()->getActionName();
		$request	= 	sfContext::getInstance()->getRequest();
		
		if(false != stristr($actionName,"apidetailed") )
		{
			/*
			 * if iUpdateLogValue = 0 then Do Not Update Log And Returns Response Only
			 * 	  iUpdateLogValue = 1 then Update Log Obly And Do not returns Response Only	
			 *    iUpdateLogValue = 2 then Update Log Obly as well as Returns Response also	
			 */		
		
			$iUpdateLogValue = $request->getParameter("ul");
			if(isset($iUpdateLogValue) && $iUpdateLogValue != 0)
			{
				self::LogThisAction($actionObject);
				self::Update_ViewCount($actionObject->profile);
				
				self::CheckContactLimit($actionObject);
				self::SetLast_LoginDetails($actionObject);
				
				self::viewedContactLog($actionObject);
				self::alterSeenTable($actionObject);
			}
		}
		else if($actionName == "detailed")
		{
			self::LogThisAction($actionObject);
			self::Update_ViewCount($actionObject->profile);
			
			self::SetPicAndAlbumCount($actionObject);
			
			self::CheckContactLimit($actionObject);
			self::SetLast_LoginDetails($actionObject);
			
			self::viewedContactLog($actionObject);
			self::alterSeenTable($actionObject);			
		}
		
		self::IsPhotoRequested($actionObject);
    self::IsBookmarkedOrIgnore($actionObject);
	}
	
	/**
	 * Set Last Login Details
	 * 
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access private static
	 */
	private static function SetLast_LoginDetails($actionObject)
	{
		$actionObject->OnlineMes=ProfileCommon::getLastLoginFormat($actionObject->profile->getLAST_LOGIN_DT());
	}
	
	/**
	 * Will update Seen field of tables CONTACT,PHOTO_REQUEST,HOROSCOPE
	 * etc.
	 * Will help in removing new tag from profileid.
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access public static
	 */
	public static function alterSeenTable($actionObject)
	{

                if($actionObject->loginProfile->getPROFILEID())
                {

                        $privacy=$actionObject->loginProfile->getPRIVACY();
                        $producerObj = new Producer();
                        //Privacy is not C for login user 
                        if($privacy!='C' && $actionObject->loginProfile->getPROFILEID()!=$actionObject->profile->getPROFILEID() && $actionObject->loginProfile->getGENDER()!=$actionObject->profile->getGENDER())
                        {
                                if($producerObj->getRabbitMQServerConnected())
                                    $triggerOrNot = "inTrigger";
                                else
				{
				    $vlt=new VIEW_LOG_TRIGGER();
                                    $vlt->updateViewTrigger($actionObject->loginProfile->getPROFILEID(),$actionObject->profile->getPROFILEID());
				}
                        }
                        elseif($producerObj->getRabbitMQServerConnected())
                            $triggerOrNot="notInTrigger";

                        if($producerObj->getRabbitMQServerConnected()){
				$viewLogData['triggerOrNot'] = $triggerOrNot;
				$viewLogData['VIEWER'] = $actionObject->loginProfile->getPROFILEID();
				$viewLogData['VIEWED'] = $actionObject->profile->getPROFILEID();
//                            $producerObj->sendMessage($queueData);
                        }
                        else
			{
			    if(!$vlt)
				$vlt=new VIEW_LOG_TRIGGER();
			    $vlt->updateViewLog($actionObject->loginProfile->getPROFILEID(),$actionObject->profile->getPROFILEID());
			}


		//This will help in assingning global variables in alter_Seen_table.
		$fromSym=1;
		$request=$actionObject->getRequest();
		
		if($actionObject->contactEngineObj)
		{
			$who=$actionObject->contactEngineObj->contactHandler->getContactInitiator();
			if($actionObject->contactEngineObj->contactHandler->getContactObj()->getSEEN()==Contacts::NOTSEEN)
			{
				$currentFlag = $actionObject->contactEngineObj->contactHandler->getContactType();
				$profileMemcacheServiceViewerObj = new ProfileMemcacheService($actionObject->contactEngineObj->contactHandler->getViewer());
				switch($currentFlag)
				{
					case ContactHandler::INITIATED:
						if($who==ContactHandler::RECEIVER){
							if($actionObject->contactEngineObj->contactHandler->getContactObj()->getFILTERED() =="Y")
								$profileMemcacheServiceViewerObj->update("FILTERED_NEW",-1);
							else
								$profileMemcacheServiceViewerObj->update("AWAITING_RESPONSE_NEW",-1);
						}
						break;
					case ContactHandler::ACCEPT:
						if($who==ContactHandler::SENDER)
							$profileMemcacheServiceViewerObj->update("ACC_ME_NEW",-1);
						break;
					case ContactHandler::DECLINE:
						if($who==ContactHandler::SENDER)
							$profileMemcacheServiceViewerObj->update("DEC_ME_NEW",-1);
						break;
					case ContactHandler::CANCEL:
					case ContactHandler::CANCEL_CONTACT:
						if($who ==ContactHandler::RECEIVER)
							$profileMemcacheServiceViewerObj->update("DEC_ME_NEW",-1);
						break;
				}
				$profileMemcacheServiceViewerObj->updateMemcache();
			}
			$type=$actionObject->contactEngineObj->contactHandler->getContactObj()->getTYPE();
			if(($who==ContactHandler::SENDER && ($type=='A' OR $type=='D')) || ($who!=ContactHandler::SENDER && ($type=='I' || $type=='E'|| $type=='C')))
				$updatecontact=1;
		}
		
		//Helps in updating CONTACTS table, to handle cases where only 1
		// shards is updated
		$force_query=$request->getParameter("force_query");
		
		$profileid=$actionObject->profile->getPROFILEID();
		
		if($actionObject->loginProfile->getPROFILEID() && $actionObject->loginProfile->getPROFILEID()!=$actionObject->profile->getPROFILEID() && $actionObject->loginProfile->getGENDER()!=$actionObject->profile->getGENDER())
		{
			$mypid=$actionObject->loginProfile->getPROFILEID();
			$randomNumber = rand(0,100);
			if($randomNumber>=100)
			{
			include(sfConfig::get("sf_web_dir")."/profile/alter_seen_table.php");
			}
			else
			{
				if($producerObj->getRabbitMQServerConnected())
				{
					$updateSeenProfileData['fromSym'] = $fromSym;
					$updateSeenProfileData['type'] = $type;
					$updateSeenProfileData['mypid'] = $mypid;
					$updateSeenProfileData['updatecontact'] = $updatecontact;
					$updateSeenProfileData['profileid'] = $profileid;
					
			//		$producerObj->sendMessage($updateSeenProfileData);
				}
				else
				{
//					$this->sendMail();
				}
			}
		}
		if($producerObj->getRabbitMQServerConnected())
		{
			if(is_array($viewLogData))
			{
				$body['VIEW_LOG']=$viewLogData;
			}
			if(is_array($updateSeenProfileData))
			{
				$body['UPDATE_SEEN']=$updateSeenProfileData;
			}
			if(is_array($body))
			{
			$finalQueueData = array("process"=>"UPDATE_SEEN_PROFILE",'data'=>array('body'=>$body));
			$producerObj->sendMessage($finalQueueData);
			}
		}
                }
		
	}
	
	/**
	* Viewed contact log 
	* @param $actionObject : Controller Action objects
	* @return void
	* @access public static
	*/
	public static function viewedContactLog($actionObject)
	{
		if($actionObject->contactEngineObj && $actionObject->loginProfile && $actionObject->profile)
		{
			$who=$actionObject->contactEngineObj->contactHandler->getContactInitiator();
			$type=$actionObject->contactEngineObj->contactHandler->getContactObj()->getTYPE();
			
			//Insert into view contact log
			if($type=='I' && $who!=ContactHandler::SENDER)
			{
				$actionObject->viewerDb = JsDbSharding::getShardNo($actionObject->loginProfile->getPROFILEID(),'');
				
				$evlObj=new NEWJS_EOI_VIEWED_LOG($actionObject->viewerDb);
				$evlObj->insert($actionObject->loginProfile->getPROFILEID(),$actionObject->profile->getPROFILEID());
				
				$actionObject->viewedDb = JsDbSharding::getShardNo($actionObject->profile->getPROFILEID(),'');
				
				if($actionObject->viewedDb!=$actionObject->viewerDb)
				{
					$evlObj=new NEWJS_EOI_VIEWED_LOG($actionObject->viewedDb);
					$evlObj->insert($actionObject->loginProfile->getPROFILEID(),$actionObject->profile->getPROFILEID());
				}
			}
		}
	}
	
	/**
	 * Set Profile Data 
	 * ProfileCommin::SetPageInformation()
	 * @param $actionObject : Controller Action objects
	 * @return void
	 * @access public static
	 */
	public static function GetProfileData($actionObject)
	{
		ProfileCommon::setPageInformation($actionObject,$actionObject->profile);
	}
	
	/**
	 * getJPartnerEdit
	 * @param $actionObject : Controller Action objects
	 * @access public static
	 */
	public static function getJPartnerEdit($actionObject) {
		include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
		$mysqlObj = new Mysql;
		$profileId = $actionObject->loginProfile->getPROFILEID();
		if ($profileId) {
			$myDbName = getProfileDatabaseConnectionName($profileId, '', $mysqlObj);
			$myDb = $mysqlObj->connect("$myDbName");
		}
		$jpartnerObj = new JPartnerDecorated;
		/*if (in_array("T", explode(",", $actionObject->loginProfile->getSUBSCRIPTION()))) {
			$myDb_ap = $mysqlObj->connect("Assisted_Product");
			$APeditID = sfContext::getInstance()->getRequest()->getParameter("APeditID");
			$partnerWhrCond = " AND CREATED_BY='ONLINE'";
			$jpartnerObj_ap = new JPartnerDecorated("Assisted_Product.AP_TEMP_DPP");
			$jpartnerObj_ap->setPartnerDetails($profileId, $myDb_ap, $mysqlObj, "*", $partnerWhrCond);
			if (!$jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) {
				unset($jpartnerObj_ap);
				$partnerWhrCond2 = " AND STATUS='LIVE' ORDER BY DPP_ID DESC LIMIT 1";
				$jpartnerObj_ap_live = new JPartnerDecorated("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
				$jpartnerObj_ap_live->setPartnerDetails($profileId, $myDb_ap, $mysqlObj, "*", $partnerWhrCond2);
				$partnerWhrCond3 = " AND ROLE='ONLINE' AND ONLINE='Y' AND CREATED_BY='ONLINE'";
				$jpartnerObj_ap = new JPartnerDecorated("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
				if ($jpartnerObj_ap_live->isPartnerProfileExist($myDb_ap, $mysqlObj)) $partnerWhrCond3.= " AND DPP_ID>'" . $jpartnerObj_ap_live->getDPP_ID() . "'";
				$partnerWhrCond3.= " ORDER BY DPP_ID DESC LIMIT 1";
				$jpartnerObj_ap->setPartnerDetails($profileId, $myDb_ap, $mysqlObj, "*", $partnerWhrCond3);
				if (!$jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) $jpartnerObj_ap = $jpartnerObj_ap_live;
				else $actionObject->apEditMsg=1;
			}
			else
				$actionObject->apEditMsg=1;
			if ($jpartnerObj_ap && $jpartnerObj_ap->isPartnerProfileExist($myDb_ap, $mysqlObj)) {
				$jpartnerObj = $jpartnerObj_ap;
				if (!$APeditID) $APeditID = $jpartnerObj_ap->getDPP_ID();
				$APoperator = $jpartnerObj_ap->getCREATED_BY();
				$actionObject->APonlineString = "APeditID=$APeditID&APoperator=$APoperator";
			} //If DPP is not there in Archive or temp then get dpp from newjs.Jpartner
			else $jpartnerObj->setPartnerDetails($profileId, $myDb, $mysqlObj);
		} elseif ($actionObject->userType == UserType::AP_EXECUTIVE && $editId = $actionObject->request->getParameter("editID")) {
			$myDb_ap = $mysqlObj->connect("Assisted_Product");
			if (sfContext::getInstance()->getRequest()->getParameter("edited")) {
				$partnerWhrCond = " AND CREATED_BY='" . sfContext::getInstance()->getRequest()->getParameter("matchPointOperator") . "'";
				$jpartnerObj = new JPartnerDecorated("Assisted_Product.AP_TEMP_DPP");
			} else {
				$partnerWhrCond = "AND DPP_ID='" . $editId . "'";
				$jpartnerObj = new JPartnerDecorated("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
			}
			$jpartnerObj->setPartnerDetails(sfContext::getInstance()->getRequest()->getParameter("matchPointPID"), $myDb_ap, $mysqlObj, "*", $partnerWhrCond);
		} else*/
		//If dpp is getting edited by non assisted user
		$jpartnerObj->setPartnerDetails($profileId, $myDb, $mysqlObj);
		return $jpartnerObj;
	}
	/**
	 * Update viewformis table
	 * 
	 */
	private static function updateViewForMis($actionObject)
	{
		if(!stristr($_SERVER['HTTP_USER_AGENT'],"mediapartners-google"))
		{
			if(CommonFunction::isPaid($actionObject->profile->getSUBSCRIPTION()))
				$paid='Y';
			else
				$paid='N';
			
			$vObj=new VIEW_FOR_MIS();
			$vObj->insert($actionObject->profile->getPROFILEID(),$actionObject->profile->getGENDER(),$paid,$actionObject->profile->getHAVEPHOTO(),$actionObject->profile->getMTONGUE(),$actionObject->profile->getCASTE(),date("Y-m-d"),$actionObject->frommatchalert,$actionObject->contact_matchalert,$actionObject->visitoralert);
		}	
	}
    
    public static function GetNextPreviousForContact($request,$actionObj)
    {	
        $szContactID = $request->getParameter("contact_id");
        $iTotalRecord = $request->getParameter('total_rec');
        $iOffset = $request->getParameter('actual_offset');//Offset Range from 1 to TotalRecords

        if((strlen($szContactID)!=0 && $actionObj->loginProfile->getPROFILEID() && ($iOffset)>0 && ($iOffset)<=$iTotalRecord))
        {	
            $actionObj->prevLink = null;
            $actionObj->nextLink = null;
            $actionObj->SHOW_PREV = false;
            $actionObj->SHOW_NEXT = false;
            $actionObj->SHOW_NEXT_PREV = 1;
            if($iOffset>1)
            {	
                $actionObj->SHOW_PREV = true;
                $actionObj->prevLink = "contact_id=".$szContactID."&total_rec=".$iTotalRecord."&actual_offset=".($iOffset-1);
            }
            if($iOffset < $iTotalRecord && $iOffset!=$iTotalRecord)
            {
                $actionObj->SHOW_NEXT = true;
                $actionObj->nextLink = "contact_id=".$szContactID."&total_rec=".$iTotalRecord."&actual_offset=".($iOffset+1);
            }
            $actionObj->$fromPage = 'contacts';
            $actionObj->SHOW_NEXT_PREV = 1;
            
            $pchkSum = $request->getParameter('profilechecksum');
            if(!$pchkSum || strlen($pchkSum)==0)
            {
                $objProfileDisplay = new profileDisplay;

                $actionObj->profilechecksum = $objProfileDisplay->getNextPreviousProfile($actionObj->loginProfile,$szContactID,$iOffset,$request->getParameter('stype'));

                // Subtracting -1 ,as in case of else call to function ProfileCommon::showNextPrev() will need 
                // offset to start from -1 And while baking response DetailedViewApi we add +1 actual_offset
                $actionObj->actual_offset = $iOffset - 1 ;

                $actionObj->stype=$request->getParameter("stype");
                $actionObj->Sort=$request->getParameter("Sort");
                $actionObj->actual_offset_real=$actionObj->actual_offset;
                $actionObj->total_rec=$request->getParameter("total_rec");

                //ProfileID
                $iProfileID = JsCommon::getProfileFromChecksum($actionObj->profilechecksum);
                $actionObj->next_prev_prof=$iProfileID;

                //Seting profile class for this profileid.
                if($actionObj->next_prev_prof)
                    $actionObj->setViewed($actionObj->next_prev_prof);
            }

            return true;
        }
        return false;
    }
    
    /*
     *  Function to handle Next Previous Logic
     */
    public static function GetNextPreviousForJsmsECP($request,$actionObj){
        if(null == $actionObj->loginProfile || null == $actionObj->loginProfile->getPROFILEID())
            return false;
        
        //offset start from 0
        $iOffset         = $request->getParameter("offset");
        $iTotalRecord    = $request->getParameter("total_rec");
        $similarProfileCheckSum = $request->getParameter("similarOf");
        
        $actionObj->prevLink = null;
        $actionObj->nextLink = null;
        $actionObj->SHOW_PREV = false;
        $actionObj->SHOW_NEXT = false;
        $actionObj->SHOW_NEXT_PREV = 1;
        
        //TupleId Start From 1
        $tupleId = $request->getParameter('tupleId');
        if($tupleId != 1)
            $preTupleId = $tupleId - 1;
        $nextTupleId = $tupleId + 1;
        //iOffset Start from 0 to iTotalRecord
        if($iOffset>0)
        {
            $actionObj->SHOW_PREV = true;
            $actionObj->prevLink = "offset=".($iOffset-1)."&total_rec=".$iTotalRecord."&similarOf=".$similarProfileCheckSum."&tupleId=".$preTupleId."&overwrite=1";
        }
        if($iOffset < $iTotalRecord && $iOffset!=$iTotalRecord-1)
        {
            $actionObj->SHOW_NEXT = true;
            $actionObj->nextLink = "offset=".($iOffset+1)."&total_rec=".$iTotalRecord."&similarOf=".$similarProfileCheckSum."&tupleId=".$nextTupleId."&overwrite=1";
        }
    
        $pchkSum = $request->getParameter('profilechecksum');
        if(!$pchkSum || strlen($pchkSum)==0)
        {
            //Viewer and Viewed ProfileId
            
            $viewedProfileID = JsCommon::getProfileFromChecksum($similarProfileCheckSum);
            $viewerProfileID = $actionObj->loginProfile->getPROFILEID();

            $similarProfileObj = new ViewSimilarProfile;
            $iProfileID = $similarProfileObj->getSimilarProfilesFromMemcache($viewedProfileID,$viewerProfileID,$iOffset);
            if(null === $iProfileID)
            {
                $actionObj->bFwdTo_SearchIDExpirePage = true;
                return false;
            }
            $actionObj->profilechecksum = JsCommon::createChecksumForProfile($iProfileID);

            $actionObj->actual_offset = $iOffset;
            $actionObj->stype=$request->getParameter("stype");
            $actionObj->Sort=$request->getParameter("Sort");
            $actionObj->actual_offset_real=$actionObj->actual_offset;

            //ProfileID
            $actionObj->next_prev_prof=$iProfileID;

            //Seting profile class for this profileid.
            if($actionObj->next_prev_prof)
                $actionObj->setViewed($actionObj->next_prev_prof);
            
            return true;
        }
        
        return false;
    }
    /*
     * Common Function to handle Next Previous Logic
     */
    public static function handleNextPreviousLogic($request,$actObj)
    {	
    	//Hit is coming from Myjs Page
    	$bHitFromMyjsPage = strlen($request->getParameter("hitFromMyjs"))!=0?true:false;

        //For Contact Listing Page
        $bIsContactListingPage = strlen($request->getParameter("contact_id"))!=0?true:false;
        
        //For Ecp Listing Page
        $bIsJsmsEcpListingPage = strlen($request->getParameter("similarOf"))!=0?true:false;
        
        //For MyjsPage Next Previous will be handled differently 
        if($bHitFromMyjsPage){
            return self::GetNextPreviousForMyjs($request,$actObj);
        }

        //Logic for Contact Listing Page
        if($bIsContactListingPage){
            return self::GetNextPreviousForContact($request,$actObj);
        }
        
        //Logic for Jsms Ecp Listing Page
        if($bIsJsmsEcpListingPage){
            return self::GetNextPreviousForJsmsECP($request,$actObj);
        }
        
        //Common Logic
        return self::Show_Next_Previous($actObj);
    }
    
    /* VA Whitelisting
     * Common Function to whiteListParams
     */
    public static function whiteListParams($request)
    {
        $stype  = $request->getParameter("stype");
        $sort  = $request->getParameter("Sort");
        $contactId  = $request->getParameter("contact_id");
        $totalRec  = $request->getParameter("total_rec");
        $username  = $request->getParameter("username");
        
        if(strlen($stype)>6 && $stype!="{{stypeInfo}}")
        {
            $http_msg=print_r($_SERVER,true);
            mail("ankitshukla125@gmail.com","Stype whitelisting 3","STYPE :$stype:$http_msg");
        }
        
        if(strlen($sort)>3 && $sort!="null")
        {
            $http_msg=print_r($_SERVER,true);
            mail("ankitshukla125@gmail.com","Sort whitelisting 3","SORT :$sort:$http_msg");
        }
        
        if($contactId && !is_numeric(explode("_",$contactId)[0]) && explode("_",$contactId)[0]!='contactId' && $contactId!='contactId' && $contactId!='{contact_id}')
        {
            $http_msg=print_r($_SERVER,true);
            mail("ankitshukla125@gmail.com","contact Id whitelisting 3","CONTACT_ID :$contactId:$http_msg");
        }
        
        if($totalRec && !is_numeric($totalRec) && $totalRec != "{total_rec}")
        {
            $http_msg=print_r($_SERVER,true);
            mail("ankitshukla125@gmail.com","total records whitelisting 3","TOTAL_REC :$totalRec:$http_msg");
        }
        
//        if(strlen($username)>15)
//        {
//            $http_msg=print_r($_SERVER,true);
//            mail("ankitshukla125@gmail.com","usrname whitelisting 3","USERNAME :$username:$http_msg");
//        }
    }

   /*	This function is used to handle next previous from myjs page
   *	
   */

    public static function GetNextPreviousForMyjs($request,$actionObj)
    {	
    	$maxProfilesOnMyjs = 20;
        $iTotalRecord = $request->getParameter('total_rec');
        //Offset Range from 1 to TotalRecords
        $iOffset = $request->getParameter('actual_offset');
        $iListingType = $request->getParameter('listingName');
        $iListingType = strtoupper($iListingType);
        $profileObj= LoggedInProfile::getInstance();
		$pid = $profileObj->getPROFILEID();

        if($iOffset%$maxProfilesOnMyjs == 1 && $iOffset > 1)
        {	
        	$cacheCriteria = MyjsSearchTupplesEnums::getListNameForCaching($iListingType);
        	$cachedResultsPoolArray = unserialize(JsMemcache::getInstance()->get("cached".$cacheCriteria."Myjs".$pid));

        	$request->setParameter('caching',0);
        	$request->setParameter('hitFromMyjs',1);

        	if($iListingType == 'VERIFIEDMATCHES')
        	{
        		$request->setParameter('verifiedMatches',1);
        	}

        	if($iListingType == 'JUSTJOINED')
        	{
        		$request->setParameter('searchBasedParam','justJoinedMatches');
        		$request->setParameter('justJoinedMatches',1);
        	}

        	if($iListingType == 'DESIREDPARTNERMATCHES')
        	{
        		$request->setParameter('partnermatches',1);
        	}
        	if($iListingType == 'LASTSEARCH')
        	{
        		$request->setParameter('lastsearch',1);
        	}
        	if($iListingType == 'DAILYMATCHES')
        	{
        		$request->setParameter('matchalerts',1);
        	}
        	ob_start();
        	$request->setParameter("useSfViewNone",1);
        	$nextProfileToAppend = sfContext::getInstance()->getController()->getPresentationFor('search','PerformV1');
        	$output = (array)(json_decode(ob_get_contents(),true));

        	ob_end_clean();
        	$iterate = $iOffset-1;
        	if(is_array($output) && array_key_exists("profiles",$output)){
        	foreach ($output['profiles'] as $key => $value) {
        		array_push($cachedResultsPoolArray, $value['profileid']);
        	}
        	}

        	JsMemcache::getInstance()->set("cached".$cacheCriteria."Myjs".$pid,serialize($cachedResultsPoolArray));
							        	
        }

        if($actionObj->loginProfile->getPROFILEID() && ($iOffset)>0 && ($iOffset)<=$iTotalRecord)
        {	
            $actionObj->prevLink = null;
            $actionObj->nextLink = null;
            $actionObj->SHOW_PREV = false;
            $actionObj->SHOW_NEXT = false;
            $actionObj->SHOW_NEXT_PREV = 1;
            if($iOffset>1)
            {	
                $actionObj->SHOW_PREV = true;
                $actionObj->prevLink = "&total_rec=".$iTotalRecord."&actual_offset=".($iOffset-1)."&listingName=".strtolower($iListingType)."&hitFromMyjs=1";
            }
            if($iOffset < $iTotalRecord && $iOffset!=$iTotalRecord)
            {
                $actionObj->SHOW_NEXT = true;
                $actionObj->nextLink ="&total_rec=".$iTotalRecord."&actual_offset=".($iOffset+1)."&listingName=".strtolower($iListingType)."&hitFromMyjs=1";
            }
            $actionObj->fromPage = 'myjs';
            $actionObj->SHOW_NEXT_PREV = 1;
            
            $pchkSum = $request->getParameter('profilechecksum');
            if(!$pchkSum || strlen($pchkSum)==0)
            { 
                $objProfileDisplay = new profileDisplay;
                $actionObj->profilechecksum = $objProfileDisplay->getNextPreviousProfileForMyjs($iListingType,$iOffset);
                // Subtracting -1 ,as in case of else call to function ProfileCommon::showNextPrev() will need 
                // offset to start from -1 And while baking response DetailedViewApi we add +1 actual_offset
                $actionObj->actual_offset = $iOffset - 1 ;

                $actionObj->stype=$request->getParameter("stype");
                $actionObj->Sort=$request->getParameter("Sort");
                $actionObj->actual_offset_real=$actionObj->actual_offset;
                $actionObj->total_rec=$request->getParameter("total_rec");

                //ProfileID
                $iProfileID = JsCommon::getProfileFromChecksum($actionObj->profilechecksum);
                $actionObj->next_prev_prof=$iProfileID;

                //Seting profile class for this profileid.
                if($actionObj->next_prev_prof)
                    $actionObj->setViewed($actionObj->next_prev_prof);
            }

            return true;
        }	
        return false;
    }
    
    
}
?>
