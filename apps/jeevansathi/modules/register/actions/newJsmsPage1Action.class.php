<?php
/**
 * This class will handle all the Registration Api Calls call for New JSMS Page 1
 */
class newJsmsPage1Action extends sfAction
{
	public function execute($request)
	{
                $reg_params	= $request->getParameter('reg');
                $trackParams 	= $reg_params['trackingParams'] ;
                unset($reg_params['trackingParams']);
                $trackParams = json_decode($trackParams,true);

                $this->source           = $trackParams['source'];
                $this->adnetwork        = $trackParams['adnetwork'];
                $this->account          = $trackParams['account'];
                $this->campaign         = $trackParams['campaign'];
                $this->adgroup          = $trackParams['adgroup'];
                $this->keyword          = $trackParams['keyword'];
                $this->match            = $trackParams['match'];
                $this->lmd              = $trackParams['lmd'];
                $this->lmd              = $trackParams['lmd'];
                $this->groupname        = $trackParams['groupname'];
                $this->secondary_source = $trackParams['secondary_source'];
		$tieup_source           = $trackParams['tieup_source'];
		$this->domain		= $trackParams['domain'];
		$newsource		= $trackParams['newsource'];
                
                $campaignData['utm_campaign'] = $trackParams['utm_campaign'];
                if(!$trackParams['utm_campaign'])
                    $campaignData['campaignid'] = $trackParams['campaignid'];
                $campaignData['utm_term'] = $trackParams['utm_term'];
                $campaignData['keyword'] = $trackParams['keyword'];
                $campaignData['adgroupid'] = $trackParams['adgroupid'];
                $campaignData['utm_medium'] = $trackParams['utm_medium'];
                $campaignData['gclid'] = $trackParams['gclid'];
                
		if($reg_params['city_res']=='0' && $reg_params['country_res']==51)
		{
			$reg_params['city_res']=$reg_params['state_res']."OT";
		}
		unset($reg_params['state_res']);
		$request->setParameter('reg',$reg_params);

		$apiObj=ApiResponseHandler::getInstance();
			
		$this->form = new PageForm(array(), array("page" => 'APP1', 'request' => $request,'allow_extra_fields'=>1), '');
		//diable csrf protection
		$this->form->disableLocalCSRFProtection();
		
		if(!$this->source)
			$this->source=$reg_params['source'];
		if($reg_params['country_res']!=51 && $reg_params['country_res']!=128){
				unset($this->form['city_res']);
		}
		$this->form->bind($reg_params);

		if ($this->form->isValid()) {

	                /*Code to check spammer, checking for request from same ip. Block registration if request > 5 within 1 minute if not then insert its entry into DB*/
			$this->ip = CommonFunction::getIP();
	                $dbBlockIP = new NEWJS_BLOCK_IP();
	                if ($dbBlockIP->blockIP($this->ip)){ 
	                        $apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        	                $apiObj->generateResponse();
				die("Too many requests !");
			}
	                else 
				$dbBlockIP->insertIP($this->ip);
	                /*End of - Code to check spammer*/

			$keywords=RegistrationMisc::getKeywords($reg_params);
			$alertArr = RegistrationMisc::getLegalVariables($request,1);

                        if($this->source == 'onoffreg')
	                        $crm_team = 'offline';
        	        else
                	        $crm_team = 'online';
			if($alertArr[SERVICE_EMAIL] == 'S') 
				$match_def = 'A';
			else
				$match_def = 'U';
			if($alertArr[SERVICE_SMS] == 'S') 
				$sms_def = 'Y';
			else 
				$sms_def = 'N';
			
			$age=CommonFunction::getAge($this->form->getValue('dtofbirth'));
			$phone=$this->form->getValue('phone_res');
			if($phone[landline])
				$phone_with_std=$phone[std].$phone[landline];
				
			$now = date("Y-m-d G:i:s");
	    	        $today = CommonUtility::makeTime(date("Y-m-d"));
	  
			$values_that_are_not_in_form = array('INCOMPLETE' => 'Y', 'ACTIVATED' => 'N', 'SCREENING' => 0, 'SERVICE_MESSAGES' =>'S', 'ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today, 'SORT_DT' => $now, 'IPADD' => $this->ip, 'PROMO_MAILS' =>'S', 'CRM_TEAM' => "$crm_team", 'PERSONAL_MATCHES' =>'A', 'GET_SMS' =>'Y', 'SEC_SOURCE' => "$this->secondary_source", 'KEYWORDS' => $keywords,'AGE'=>$age,'SHOWPHONE_MOB'=>'Y','SHOWPHONE_RES'=>'Y');
			$id = $this->form->updateData('', $values_that_are_not_in_form);
                        
                        //print_r($campaignData);die;
                        if($campaignData)
                            RegistrationFunctions::putCampaignVars($id,$campaignData);
                        
			$this->loginProfile = LoggedInProfile::getInstance();
			$this->loginProfile->getDetail($id, "PROFILEID");

			$username 	=$this->loginProfile->getUSERNAME();
			$gender 	=$this->loginProfile->getGENDER();

			//create login session
                        RegistrationMisc::setAuthenticationCookie();
			setcookie("LOGIN_SET","1",0,"/");
                        $request->setAttribute('reg_id',$id);
                        setcookie("JS_REG_ID",$id,time()+2592000,"/");

			//Make user login
			$loginObj=AuthenticationFactory::getAuthenicationObj();
			$result=$loginObj->login($reg_params[email],$reg_params[password],1);
			$apiObj->setAuthChecksum($result[AUTHCHECKSUM]);
			

			// data insertion in JPROFILE_ALERT table
			RegistrationMisc::updateAlertData($id,$alertArr,'M');
			
			$jpartnerFields=array("MSTATUS","MTONGUE","CASTE","COUNTRY_RES","CITY_RES","AGE","RELIGION","OCCUPATION","HEIGHT","INCOME","EDU_LEVEL_NEW");
			RegistrationMisc::setJpartnerAfterRegistration($this->loginProfile,$jpartnerFields,$reg_params["casteNoBar"]);
			RegistrationMisc::contactArchiveUpdate($this->loginProfile,$this->ip);
			RegistrationMisc::insertInIncompleteProfileAndNames($this->loginProfile);
                        $partnerField = new PartnerField();
                        RegistrationFunctions::UpdateFilter($partnerField);
			//Lead conversion update
			RegistrationMisc::updateLeadConversion($this->loginProfile->getEMAIL(),$this->leadid);

                        //added for Source tracking to store registration caused by sources leading to home page
                        if(isset($_COOKIE['JS_SOURCE_HOME'])){
                                $sourceTracking = new SourceTracking($this->source, SourceTrackingEnum::$REG_PAGE_1_FLAG, $newsource, $tieup_source);
                                $this->source = $_COOKIE['JS_SOURCE_HOME'];
                                $sourceTracking->sourceFromHomePage($this->source, $id);
                                //Now unser JS_SOURCE_HOME cookie
                                setcookie("JS_SOURCE_HOME", "", time() - 3600, "/");
                        }
			//Assingning profileid to offline OPERATOR 
			if ($_COOKIE['OPERATOR'] != ''){
				$this->operatorAssigning($id, $_COOKIE['OPERATOR'], $tieup_source);
			}//LEAD Table needs to be updated if it is a sugarcrm leads 
			else if ($this->source == 'onoffreg' && $_COOKIE['JS_LEAD']){
				 RegistrationMisc::updateSugarLead($id,$username,$this->source);
			}

			//CODE ADDED to capture outer variable
                      	if($this->adnetwork || $this->account || $this->campaign || $this->adgroup || $this->keyword || $this->match || $this->lmd){
                      		$dbTrackTieupVariable = new MIS_TRACK_TIEUP_VARIABLE();
        	                $dbTrackTieupVariable->insert($this->adnetwork, $this->account, $this->campaign, $this->adgroup, $this->keyword, $this->match, $this->lmd, $id);
			}
			$this->unsetCampaignCookies();

	                //Added to email authority for informing about suspected email-id.
			$suspected_check = CommonFunction::suspectedIP($this->ip);
                        if($suspected_check) 
				SendMail::send_email('manoj.rana@naukri.com', $id, "Profileid of suspected email-id", "register@jeevansathi.com");
			if('C' == $this->secondary_source) 
                        	RegistrationCommunicate::sendEmailAfterRegistrationIncomplete($this->loginProfile);

            // email for verification
						$emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($this->loginProfile->getPROFILEID(),$this->loginProfile->getEMAIL());
					
						(new emailVerification())->sendVerificationMail($this->loginProfile->getPROFILEID(),$emailUID);
					////////
                                
			//$registrationid=$request->getParameter("registrationid");
			//$done = NotificationFunctions::manageGcmRegistrationid($registrationid,$id)?"1":"0";
			//$loginData=array("GENDER"=>$result[GENDER],"USERNAME"=>$result[USERNAME],"LANDINGPAGE"=>'1',"GCM_REGISTER"=>$done);
			$loginData=array("GENDER"=>$gender,"USERNAME"=>$username,"LANDINGPAGE"=>'SCREEN_6');
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->setResponseBody($loginData);
			$apiObj->generateResponse();
		}
		else
		{
			$errorObj=$this->form->getErrorSchema();
            $status = $this->checkForAlreadyRegisteredUsers($errorObj,$reg_params['email'],$reg_params['password']);
            if($status)
            {
                $username 	=$this->loginProfile->getUSERNAME();
                $gender 	=$this->loginProfile->getGENDER();
                //Login user
                $loginObj=AuthenticationFactory::getAuthenicationObj();
                $result=$loginObj->login($reg_params[email],$reg_params[password],1);
                $apiObj->setAuthChecksum($result[AUTHCHECKSUM]);
                
                $apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $resp =array("LANDINGPAGE"=>'HOMEPAGE');
                $apiObj->setResponseBody($resp);
                $apiObj->generateResponse();
                die;
            }

			foreach ($errorObj as  $name => $error)
			{
					$errMes=$error->getMessage();//print_r($val);	
					$resp[error][]=$errMes;
					
			}
			RegistrationMisc::logServerSideValidationErrors("NMP1",$this->form);

			$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiObj->setResponseBody($resp);
			$apiObj->generateResponse();
		}
		die;
	}
	/** operatorAssigning: 
	* Function  to assign operators
	* @param $id,$_COOKIE['operator'],$source,$tieup_source
	* @return
	*
	*/
    	public function operatorAssigning($id, $operator, $tieup_source)
	{
		if ($tieup_source == '101') {
			$dbJsAdminAssigned101 = new JSADMIN_ASSIGNED_101();
			$dbJsAdminAssigned101->replace($id, $operator);
			$dbJsAdminAssignLog101 = new JSADMIN_ASSIGNLOG_101();
			$dbJsAdminAssignLog101->insert($id, $operator);
		} else if ($this->source == 'onoffreg') {
			$dbOfflineRegistration = new NEWJS_OFFLINE_REGISTRATION();
	    		$dbOfflineRegistration->insert($id, $operator, $this->source);
		} else {
			$dbOfflineAssigned = new JSADMIN_OFFLINE_ASSIGNED();
			$dbOfflineAssigned->replace($id, $operator);
			$dbOfflineAssignedLog = new JSADMIN_OFFLINE_ASSIGNLOG();
        		$dbOfflineAssignedLog->insert($id, $operator);
        	}
    	}
        private function unsetCampaignCookies(){
                    setcookie("JS_SOURCE", "", 0, "/", $this->domain);
                    setcookie("JS_ADNETWORK", "", 0, "/");
                    setcookie("JS_ACCOUNT", "", 0, "/");
                    setcookie("JS_CAMPAIGN", "", 0, "/");
                    setcookie("JS_ADGROUP", "", 0, "/");
                    setcookie("JS_KEYWORD", "", 0, "/");
                    setcookie("JS_MATCH", "", 0, "/");
                    setcookie("JS_LMD", "", 0, "/");
        }
        private function checkForAlreadyRegisteredUsers($errorObj,$email,$pwd)
        {
            if(count($errorObj) == 1 && $errorObj['email'] && stristr($errorObj['email']->getCode(),'err_email_duplicate')!=false)
            {
                //Check password also
                $this->loginProfile = LoggedInProfile::getInstance();
                $this->loginProfile->getDetail($email, "EMAIL",'*');
                $validPassword = PasswordHashFunctions::validatePassword($pwd,  $this->loginProfile->getPASSWORD());    
                return $validPassword;
            }
            return false;
        }
}
?>
