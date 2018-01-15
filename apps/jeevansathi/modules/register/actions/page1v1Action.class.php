<?php
class page1v1Action extends sfAction {
    /** Executes Registration app page 1
     *
     *
     */
    public function execute($request) {
			$apiObj=ApiResponseHandler::getInstance();
      
			$this->form = new PageForm(array(), array("page" => 'APP1', 'request' => $request,'allow_extra_fields'=>1), '');
			//diable csrf protection
			$this->form->disableLocalCSRFProtection();
			
			$reg_params = $request->getParameter("reg");
			$source=$reg_params[SOURCE];
			
			if($reg_params['country_res']!=51){
					unset($this->form['city_res']);
			}
				
			$this->form->bind($request->getParameter('reg'));

			if ($this->form->isValid()) {
			$keywords=RegistrationMisc::getKeywords($reg_params);
			
			$alertArr = RegistrationMisc::getLegalVariables($request,1);
			
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
      $this->ip = CommonFunction::getIP();
      
			$values_that_are_not_in_form = array('INCOMPLETE' => 'Y', 'ACTIVATED' => 'N', 'SCREENING' => 0, 'SERVICE_MESSAGES' =>'S', 'ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today, 'SORT_DT' => $now, 'IPADD' => $this->ip, 'PROMO_MAILS' =>'S', 'CRM_TEAM' => "online", 'PERSONAL_MATCHES' =>'A', 'GET_SMS' =>'Y', 'SEC_SOURCE' => "S", 'KEYWORDS' => $keywords,'AGE'=>$age,'SHOWPHONE_MOB'=>'Y','SHOWPHONE_RES'=>'Y');
			$id = $this->form->updateData('', $values_that_are_not_in_form);
			$this->loginProfile = LoggedInProfile::getInstance();
			$this->loginProfile->getDetail($id, "PROFILEID");
			
			//Make user login
			$loginObj=AuthenticationFactory::getAuthenicationObj();
			$result=$loginObj->login($reg_params[email],$reg_params[password],1);
			$apiObj->setAuthChecksum($result[AUTHCHECKSUM]);
			RegistrationMisc::updateAlertData($id,$alertArr,'A');
			
			$jpartnerFields=array("MSTATUS","MTONGUE","CASTE","COUNTRY_RES","CITY_RES","AGE","RELIGION","OCCUPATION","HEIGHT","INCOME","EDU_LEVEL_NEW");
			RegistrationMisc::setJpartnerAfterRegistration($this->loginProfile,$jpartnerFields);
			RegistrationMisc::contactArchiveUpdate($this->loginProfile,$this->ip);
			RegistrationMisc::insertInIncompleteProfileAndNames($this->loginProfile);
			$partnerField = new PartnerField();
			RegistrationFunctions::UpdateFilter($partnerField);
			//Lead conversion update
			RegistrationMisc::updateLeadConversion($this->loginProfile->getEMAIL(),$this->leadid);
			$apiObj->setHttpArray(ResponseHandlerConfig::$APP_REG_VERIFIED);
			$registrationid=$request->getParameter("registrationid");
			$done = NotificationFunctions::manageGcmRegistrationid($registrationid,$id)?"1":"0";
            $loginData=array("GENDER"=>$result[GENDER],"USERNAME"=>$result[USERNAME],"LANDINGPAGE"=>'1',"GCM_REGISTER"=>$done);


               // email for verification
                    $emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($this->loginProfile->getPROFILEID(),$this->loginProfile->getEMAIL());
					(new emailVerification())->sendVerificationMail($this->loginProfile->getPROFILEID(),$emailUID);
					////////
                 
			$apiObj->setResponseBody($loginData);
                        
			$apiObj->generateResponse();
			}
			else
			{
				$errorObj=$this->form->getErrorSchema();
                                $status = $this->checkForAlreadyRegisteredUsers($errorObj,$reg_params['email'],$reg_params['password']);
        if($status)                     
				{
					//Make user login
          $request->setParameter("moduleName","api");  
          $request->setParameter("actionName","login");
          $request->setParameter("INTERNAL","1");
          $request->setParameter("registrationid",$request->getParameter("registrationid"));
	  
          $request->setParameter("email",$reg_params[email]);  
          $request->setParameter("password",$reg_params[password]);
          
          $apiWebHandler = ApiRequestHandler::getInstance($request);
          $forwardingArray=$apiWebHandler->getModuleAndActionName($request);
          unset($apiWebHandler);
          
          ob_start();
          $data = sfContext::getInstance()->getController()->getPresentationFor($forwardingArray["moduleName"], $forwardingArray["actionName"]);
          $output = ob_get_contents();
          ob_end_clean();
          $output = json_decode($output,true);
          $output['LANDINGPAGE'] = 'HOMEPAGE';
          echo json_encode($output);
          die;
				}
                                
				foreach ($errorObj as  $name => $error)
				{
						$errMes=$error->getMessage();//print_r($val);	
						//echo "\n     $name :" . $error.",";
						/*commentinf after discussing with nikhil as error msgs were not displayed properly in App
						 * if($name=="email")
						{
							$temp=explode(".",$errMes);
							$errMes=$temp[0].".";
						}*/
						
						$resp[error][]=$errMes;
						
				}
			RegistrationMisc::logServerSideValidationErrors("APP1",$this->form);

				$apiObj->setHttpArray(ResponseHandlerConfig::$APP_REG_FAILED);
				$apiObj->setResponseBody($resp);
				$apiObj->generateResponse();
			}
			die;
			
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
