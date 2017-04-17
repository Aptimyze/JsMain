<?php

/**
 * social actions.
 * GetAlbumV1
 * Controller to send album pic urls of a profile
 * @package    jeevansathi
 * @subpackage social
 * @author     Kumar Anand
 */
class GetAlbumV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	* This api handles 3 cases
	(a) When profilechecksum is not passed then self album is being viewed and urls and pictureid is returned
	(b) When profilechecksum is not passed and onlyCount parameter is passed then only count is sent as response
	(c) When profilechecksum is passed then other profile's album is being viewed and only urls are returned 
	*/
	public function execute($request)
	{
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		if($request->getParameter("actionName")=="getAlbum")
                {
			$inputValidateObj->validateGetAlbumData($request);
			$output = $inputValidateObj->getResponse();
			$respObj = ApiResponseHandler::getInstance();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$loggedInProfile = LoggedInProfile::getInstance('newjs_master');
                                $loggedInProfileid = $loggedInProfile->getPROFILEID();
				if($request->getParameter("profileChecksum"))
					$profileid = JsAuthentication::jsDecryptProfilechecksum($request->getParameter("profileChecksum"));
				else
					$profileid = $loggedInProfileid;

				if($profileid!=$loggedInProfileid )
					$viewerObj = Profile::getInstance("newjs_masterRep",$profileid);
				else
					$viewerObj = $loggedInProfile;
				$viewerObj->getDetail("","","HAVEPHOTO,PRIVACY,PHOTO_DISPLAY");
				$photodisplay=$viewerObj->getPHOTO_DISPLAY();
				if($profileid!=$loggedInProfileid && $photodisplay == PhotoProfilePrivacy::photoVisibleIfContactAccepted)
		                {
					$contactsRecordObj = new ContactsRecords();
		                        $contact_status_new = $contactsRecordObj->getContactType($viewerObj,$loggedInProfile);
                                	$contact_status = $contact_status_new["TYPE"];
                		}         
                
				$picServiceObj = new PictureService($viewerObj);
				$album = $picServiceObj->getAlbum($contact_status);
				if($album && is_array($album))
				{
					if($request->getParameter("profileChecksum"))
					{
						foreach($album as $k=>$v)
							$albumArr[] = $v->getMainPicUrl();
					}
					else
					{
						if($request->getParameter("onlyCount"))
						{
							$albumArr["total_photos"] = count($album);
							$albumArr["max_no_of_photos"] = sfConfig::get("app_max_no_of_photos");
						}
						else
						{
                                                	foreach($album as $k=>$v)
                                                	{
                                                        	$albumArr[$k]["pictureid"] = $v->getPICTUREID();
                                                        	$albumArr[$k]["url"] = $v->getMainPicUrl();
                                                	}
						}
                                        }
				}
				else
				{
					if(!$request->getParameter("profileChecksum") && $request->getParameter("onlyCount"))
					{
						$albumArr["total_photos"] = 0;
                                             	$albumArr["max_no_of_photos"] = sfConfig::get("app_max_no_of_photos");
					}
				}
				if($albumArr && is_array($albumArr))
				{
					//Code for album view logging
					if($profileid != $loggedInProfileid)
					{
//						$channel = MobileCommon::getChannel();
//						$date = date("Y-m-d H:i:s");
                                                $producerObj = new Producer();
                                                if($loggedInProfileid && $loggedInProfileid%PictureStaticVariablesEnum::photoLoggingMod<PictureStaticVariablesEnum::photoLoggingRem && $loggedInProfile->getGENDER()!= $viewerObj->getGENDER()){
                                                    if($producerObj->getRabbitMQServerConnected()){
                                                        $triggerOrNot = "inTrigger";
                                                        $queueData = array('process' =>MessageQueues::VIEW_LOG,'data'=>array('type' => $triggerOrNot,'body'=>array('VIEWER'=>$loggedInProfileid,VIEWED=>$profileid)), 'redeliveryCount'=>0 );
                                                        $producerObj->sendMessage($queueData);
                                                    }
                                                    else{    
                                                        $vlt=new VIEW_LOG_TRIGGER();
                                                        $vlt->updateViewTrigger($loggedInProfileid,$profileid);
//                                                        $albumViewLoggingObj = new albumViewLogging();
//                                                        $albumViewLoggingObj->logProfileAlbumView($loggedInProfileid,$profileid,$date,$channel);
                                                    }
                                                }
					}


                    $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
					if($request->getParameter("profileChecksum"))
                                        	$respObj->setResponseBody(array("albumUrls"=>$albumArr));
					else
					{
						if($request->getParameter("onlyCount"))
							$respObj->setResponseBody($albumArr);
						else
                                        		$respObj->setResponseBody(array("albumUrls"=>$albumArr,"max_no_of_photos"=>sfConfig::get("app_max_no_of_photos")));
					}
				}
				else
				{
                                        $respObj->setHttpArray(ResponseHandlerConfig::$PICTURE_NO_PHOTO);
					ValidationHandler::getValidationHandler("","social/actions/GetAlbumV1Action.class.php(1):profilechecksum:".$request->getParameter("profileChecksum")." Reason(no photos on album click)");
					$respObj->setResponseBody(array("albumUrls"=>null,"max_no_of_photos"=>sfConfig::get("app_max_no_of_photos")));
				}
				unset($picServiceObj);
			}
			else
			{
				//logging already done in search validation
                                $respObj->setHttpArray($output);
			}
			unset($output);
                       	$respObj->generateResponse();
		}
		unset($inputValidateObj);
		die;
    	}
}
