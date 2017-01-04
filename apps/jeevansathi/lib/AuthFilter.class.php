<?php
/**
 * AuthFilter
 *
 * This filter handles authentication. It integrates older authentication functionality to symfony. It sets global session variables to request attribute holder. Once we shift authentication to symfony, it will be moved to session attribute holder.
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#Authentication_Filter
 *
 * @package    jeevansathi
 * @author     Tanu Gupta
 * @created    20-06-2011
 */
class AuthFilter extends sfFilter {
	public function execute($filterChain) {

	if(strstr($_SERVER["REQUEST_URI"],"api/v1/notification/poll") || strstr($_SERVER["REQUEST_URI"],"api/v1/notification/poll/repeatAlarm"))
	{
		$notifCheck =NotificationFunctions::notificationCheck();
        	if($notifCheck){echo $notifCheck;die;}
	}

	$context = $this->getContext();
		$request = $context->getRequest();

		// Code added to switch to hindi.jeevansathi.com if cookie set !
		if($request->getcookie('JS_MOBILE'))
		{
			if($request->getcookie("jeevansathi_hindi_site")=='Y'){
				$authchecksum = $request->getcookie('AUTHCHECKSUM');
				if($request->getParameter('newRedirect') != 1 && $request->getcookie("redirected_hindi")!='Y'){
					@setcookie('redirected_hindi', 'Y',time() + 10000000000, "/");
					$context->getController()->redirect('http://hindi.jeevansathi.com?AUTHCHECKSUM='.$authchecksum."&newRedirect=1", array('request' => $request));
				}
			} else {
				@setcookie('redirected_hindi', 'N', 0, "/");
			}
		}
		// End hindi switch code !
		
		if ($matchPointCID = $request->getParameter("matchPointCID")) {
			$flag = 1; //to check if user is logged in
			$TOUT = sfConfig::get("app_tout");
			list($md, $userno) = explode("i", $matchPointCID);
			if (md5($userno) == $md) {
				/*$conn = new jsadmin_CONNECT();
				$userDetails = $conn->findUser($userno);*/
				$backendLibObj = new backendActionsLib(array("jsadmin_CONNECT"=>"newjs_master"),crmCommonConfig::$useCrmMemcache);
				$userDetails = $backendLibObj->fetchSessionDetailsBySessionID($userno);
				if ($userDetails) 
				{
					if (time() - $userDetails["TIME"] < $TOUT) 
					{
						//$conn->updateUserTime($userno);
						$backendLibObj->updateAgentSessionTime($userno);
						$flag = 0;
						unset($backendLibObj);
					}
				}
			}
			if ($flag) {
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/jsadmin\"></body></html>";
				throw new sfStopException();
			}
		} 
		elseif($request->getParameter('module')=="mailer" && $request->getParameter('action')=="openRate")
		{}
		elseif($request->getParameter('module')=="common" && $request->getParameter('action')=="resetPassword")
		{
			JsCommon::oldIncludes();
			$protect_obj = new protect;
			$protect_obj->logout();
		}
		else {
			
			global $protect_obj;
			if(($request->getParameter('module')=="homepage" && $request->getParameter('action')=="index" )|| ($request->getParameter('module')=="myjs" && $request->getParameter('action')=="jspcPerform" ) || $request->getParameter("blockOldConnection500")){
				JsCommon::oldIncludes(false);
			}
			else{
				if(strstr($_SERVER["REQUEST_URI"],"api/v1/social/getAlbum") || strstr($_SERVER["REQUEST_URI"],"api/v1/social/getMultiUserPhoto") || strstr($_SERVER["REQUEST_URI"],"api/v1/notification/poll"))
					JsCommon::oldIncludes(false);
				else
					JsCommon::oldIncludes(true);
			}
				$protect_obj = new protect;
				$request->setAttribute("protect_obj",$protect_obj);
				if(strpos($_SERVER['REQUEST_URI'],"search")!==false)
					$request->setParameter('searchRepConn', 1);
					
			if ($this->isFirstCall() && !$request->getAttribute('FirstCall')) {
				$request->setAttribute('FirstCall', 1);
				
				// Code to execute after the action execution, before the rendering
				$fromRegister="";
				if($request->getParameter('module')=="register")
					$fromRegister="y";
				$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj(null);
				if($request->getParameter("FROM_GCM")==1)
						$gcm=1;
				
				if($request->getParameter("crmback")=="admin" || $request->getParameter("allowLoginfromBackend")==1)
				{
					$authenticationLoginObj->setTrackLogin(false);
					if($request->getParameter("allowLoginfromBackend"))
						$data=$authenticationLoginObj->setCrmAdminAuthchecksum($request->getParameter("profileChecksum"),"Y");	
					else
						$data=$authenticationLoginObj->setCrmAdminAuthchecksum($request->getParameter("profileChecksum"),"N");
				}
				else
					$data=$authenticationLoginObj->authenticate(null,$gcm);
		
				$request->setAttribute('loginData', $data);
				if ($data[PROFILEID]) $login = true;
				else $login = false;
				$request->setAttribute('login', $login);
				$request->setAttribute('profileid', $data[PROFILEID]);
				$ipAddress = CommonFunction::getIP();

				///////// check for currency and ip address
				$geoIpCountry = $_SERVER['GEOIP_COUNTRY_CODE'];
		        if(!empty($geoIpCountry)){
		        	if($geoIpCountry == 'IN'){
		        		$currency = 'RS';
		         	} else {
		        		$currency = 'DOL';
		        	}
		        } else {
		        	//$countryIpAddressObj = new jsadmin_ip_country_live();
	        		//$getCountry = $countryIpAddressObj->getUserCountry($ipAddress);
	        		//if ($getCountry == 'IN'){
	        			$currency = "RS";
	        		//} else {
	        		//	$currency = "DOL";
	        		//}
		        }
				$request->setAttribute('currency', $currency);
				///////// check for currency and ip address ends here


				//App promotion need to be off for Login Profiles already have app installed
				$AppLoggedInUser=$this->LoggedInAppPromo($data[PROFILEID]);
				$request->setAttribute("AppLoggedInUser",$AppLoggedInUser);
				//end of app promotion
				
					
				
				if($request->getParameter('module')!="e")
				{
		            if($request->getParameter('module')!="api" && $request->getParameter('module')!="static"  && ($request->getParameter('module')!="register" || $request->getParameter('action')=="page5") && $request->getParameter('action')!="alertManager" && $data['PROFILEID'])
		            {

					            	if ($data['PROFILEID'])
							{
								$memObject=JsMemcache::getInstance();
								$showConsentMsg=$memObject->get('showConsentMsg_'.$data['PROFILEID']); 
								if(!$showConsentMsg) {
									$showConsentMsg = JsCommon::showConsentMessage($data['PROFILEID']) ? 'Y' : 'N';
									$memObject->set('showConsentMsg_'.$data['PROFILEID'],$showConsentMsg);
								}
								$request->setParameter("showConsentMsg",$showConsentMsg);

							}


		            	

						if($data[INCOMPLETE]=='Y' )
						{
							$request->setParameter("incompleteUser",1);
							if(MobileCommon::isNewMobileSite()){
								$context->getController()->forward("register","newJsmsReg",0);
                                                                die;
                                                        }
                                                        elseif(MobileCommon::isDesktop()){
                                                                $context->getController()->redirect("/register/page2?incompleteUser=1");
                                                                die;
                                                        }
						}
							
						
						if($request->getParameter('module')!="phone" && $request->getParameter('module')!="common")
						{							
							$phoneVerified = JsMemcache::getInstance()->get($data['PROFILEID']."_PHONE_VERIFIED");
							
							if(!$phoneVerified)
							{
								include_once(sfConfig::get("sf_web_dir")."/ivr/jsivrFunctions.php");
								$phoneVerified = phoneVerification::hidePhoneVerLayer(LoggedInProfile::getInstance());
								JsMemcache::getInstance()->set($data['PROFILEID']."_PHONE_VERIFIED",$phoneVerified);
							}



							if($phoneVerified!="Y")
							{
								
								if(!MobileCommon::isNewMobileSite() && MobileCommon::isMobile())
								{ 
									include_once(JsConstants::$docRoot.'/jsmb/phoneVerify.php');
									die;
								}
								elseif(MobileCommon::isNewMobileSite()){
									$context->getController()->forward("phone","jsmsDisplay",0);
									die;
								}
								else{
									if($_GET["crmback"]!="admin"){

										$context->getController()->forward("phone","phoneVerificationPcDisplay",0);
									}
									die;
								}
							}
							
							if($showConsentMsg=="Y" && MobileCommon::isNewMobileSite())
							{
								$context->getController()->forward("phone","consentMessage",0);
								die;
							}

						}
					}
				}
					
					
				if ($_COOKIE['SULEKHACO'] == "yes") $request->setAttribute("SULEKHACO", 1);
				$mob_cookie_arr = explode(",", $_COOKIE['JS_MOBILE']);
				$MOB_COOKIE = $mob_cookie_arr[0];
				if ($MOB_COOKIE == 'Y') $request->setAttribute('MOB_YES', 1);
				//$request->setAttribute('loginData', $data);
				//$request->setAttribute('protect_obj', $protect_obj);				
				$bmsObj = new BMSHandler();
				$zedo = $bmsObj->setBMSVariable($data,1,$request);
				$request->setAttribute("zedo",$zedo);
				
				//Kundli Block 
				if($login)
				{
					$key = $data["PROFILEID"]."_KUNDLI_LINK";
					if(JsMemcache::getInstance()->get($key))
                                        {
                                                $kundli_link = JsMemcache::getInstance()->get($key);
                                        }
					else
					{
						$naObj = ProfileAstro::getInstance();
                                                if($naObj->getIfAstroDetailsPresent($data["PROFILEID"]))
						{
							if(JsConstants::$alertServerEnable) {
                                                        $kakcc = new KUNDLI_ALERT_KUNDLI_CONTACT_CENTER("newjs_slave");
                                                        if($kakcc->ifKundliMatchesExist($data["PROFILEID"]))
								$kundli_link = 2;
							else
								$kundli_link = 3;
                                                        unset($kakcc);
							}
							else
								$kundli_link = 3;
						}
                                                else
                                                       	$kundli_link = 1;
                                                unset($naObj);
						JsMemcache::getInstance()->set($key,$kundli_link,1800);
					}
				}
				else
					$kundli_link = 3;

				$request->setAttribute('kundli_link', $kundli_link);
				//OLD Variables block
				//Paid memeber or free member for header link name changes
				if($data[SUBSCRIPTION]!=""){
					$request->setAttribute('subscriptionHeader',"0");
				}
				else
					$request->setAttribute('subscriptionHeader',"1");
				
				//$request->setAttribute('subscription',$data[SUBSCRIPTION]);
				$request->setAttribute('checksum', $data[CHECKSUM]);
				$request->setAttribute('profilechecksum', (md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]));
				$request->setAttribute('username', $data[USERNAME]);
				$request->setAttribute('activated', $data[ACTIVATED]);
				$request->setAttribute('gender', $data[GENDER]);
				if($data[PROFILEID])
				$request->setAttribute('AJAX_CALL_MEMCACHE',Header::checkMemcacheUpdated($data["PROFILEID"]));
				if ($request->getParameter("ID_CHECKED")) $request->setParameter("ID_CHECKED", urlencode($request->getParameter("ID_CHECKED")));
				if ($request->getParameter("after_login_call")) $request->setParameter("after_login_call", stripslashes($request->getParameter("after_login_call")));
				
				//MODULE ENABLE LOGIN FUNCTIONALITY
				$enable_login=sfConfig::get('mod_' . $request->getParameter('module') . '_' . $request->getParameter('action') . '_enable_login');
				if(!$enable_login)
					$enable_login=sfConfig::get('mod_' . strtolower($request->getParameter('module')) . '_' . strtolower($request->getParameter('action')) . '_enable_login');
					
				if(!$enable_login)
					$enable_login=sfConfig::get('mod_' . $request->getParameter('module') . '_default'. '_enable_login');
				if ($enable_login !== 'off') {
					if (!$data[PROFILEID] && $context->getResponse()->getStatusCode() == '200') {
						//$protect_obj->TimedOut(); //Displays timeout page
						if (sfConfig::get('mod_' . $request->getParameter('module') . '_' . $request->getParameter('action') . '_enable_login_layer') == 'on') $context->getController()->forward("static", "loginLayer",0); //Login layer
						else $context->getController()->forward("static", "logoutPage",0); //Logout page
					//	throw new sfStopException();
					die;
					}
				}
            
            

               		//$request->setAttribute('UNIQUE_REQUEST_SUB_ID',uniqid());

            
               $headers = getallheaders();
            
	            
	            if (false === isset($headers[LoggingEnums::RAJX])) {
	            	$out = LoggingManager::getInstance()->getUniqueId();
	            	$request->setAttribute(LoggingEnums::RIFT,$out); 

	            }
	            else
	            {   	            	
	            	LoggingManager::getInstance()->setUniqueId($headers[LoggingEnums::RAJX]);
	            	$request->setAttribute(LoggingEnums::RIFT,$headers[LoggingEnums::RAJX]);
	            	$request->setAttribute(LoggingEnums::AJXRSI,uniqid());

	            	
	            }
	          	                        
			}
			else
			{
				if($request->getAttribute('mobileAppApi')==1)
				{
					if (!$request->getAttribute('FirstMobileApiCall')) 
					{
						if($request->getAttribute('loginData')=='')
						{
							$this->apiWebHandler = ApiRequestHandler::getInstance($request);
							$forwardingArray=$this->apiWebHandler->getModuleAndActionName($request);
							$enable_login=sfConfig::get('mod_' . ($forwardingArray["moduleName"]) . '_' . ($forwardingArray["actionName"]) . '_enable_login');
							if ($enable_login !== 'off') 
							{
								$respObj = ApiResponseHandler::getInstance();
								$respObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
								$respObj->generateResponse();		
								die;					
							}
						}
						$request->setAttribute('FirstMobileApiCall', 1);
					}
				}
			
			}
		}
		if($data[PROFILEID])
		{
			if(!strstr($_SERVER["REQUEST_URI"],"api/v1/notification/poll")){
				$profileObj= LoggedInProfile::getInstance();
				if($profileObj->getPROFILEID()!='')
				{
					if(in_array($profileObj->getRELIGION(), array('1','4','7','9')))
					$request->setParameter('showKundliList', 1);
				}
			}
		}
		//code to fetch the revision number to clear local storage
		$revisionObj= new LatestRevision();
		$r_n_u_m = $revisionObj->getLatestRevision();
		$request->setParameter('revisionNumber',$r_n_u_m);
		unset($revisionObj);
		$filterChain->execute();
	}
	
	public function LoggedInAppPromo($profileid){
		if($profileid){
			if(JsMemcache::getInstance()->get($profileid."_appPromo")===null)
			{
				$dbAppLoginProfiles=new MOBILE_API_APP_LOGIN_PROFILES();
				$appProfileIdFlag=$dbAppLoginProfiles->getAppLoginProfile($profileid);
				if($appProfileIdFlag){
					JsMemcache::getInstance()->set($profileid."_appPromo",0);
					return 0;
				}
				else{
					JsMemcache::getInstance()->set($profileid."_appPromo",1);
					return 1;
				}
			}
			else
			{
				return JsMemcache::getInstance()->get($profileid."_appPromo");
			}
		}
		else
			return 1;
	}
}
