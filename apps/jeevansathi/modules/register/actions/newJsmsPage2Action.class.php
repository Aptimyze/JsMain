<?php
/**
 * This class will handle all the Registration Api Calls call for New JSMS Page 2 
 */
class newJsmsPage2Action extends sfAction {
     
    public function execute($request) {

                $reg_params    	= $request->getParameter('reg');
                $trackParams    = $reg_params['trackingParams'] ;
                unset($reg_params['trackingParams']);
                $trackParams = json_decode($trackParams,true);

		$apiObj=ApiResponseHandler::getInstance();
		$this->loginData = $request->getAttribute("loginData");
		if(!$this->loginData)
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$resp =array("LANDINGPAGE"=>'SCREEN_1');
			$apiObj->setResponseBody($resp);
			$apiObj->generateResponse();
			die;
		}	
		$profileid=$this->loginData[PROFILEID];
		$this->loginProfile = LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($this->loginData['PROFILEID'], "PROFILEID","*");
		$username = $this->loginProfile->getUSERNAME();
                $request->setAttribute('username',$username);
                $request->setAttribute('profileid',$profileid);

		$this->form = new PageForm('', array("page" => 'APP2'), '');
		$this->form->bind($reg_params);
		
		if ($this->form->isValid()) {

			$now = date("Y-m-d G:i:s");
			$today = CommonUtility::makeTime(date("Y-m-d"));
			$values_that_are_not_in_form = array('INCOMPLETE' => 'N','ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today);
			$this->form->updateData($profileid,$values_that_are_not_in_form);
	                RegistrationMisc::setAuthenticationCookie();
                    
            //Added Community wise Welcome discount
            $memHandlerObj = new MembershipHandler();
            $memHandlerObj->addCommunityWelcomeDiscount($profileid,$this->loginProfile->getMTONGUE());

			//Communicate to user through email and sms starts
			try{
		                //DPP Auto Suggestor implemenation:
        		        $dppObj=new DppAutoSuggest($this->loginProfile);
                                $profileFieldArr=array("DIET","SMOKE","DRINK","COMPLEXION","BTYPE","OCCUPATION","EDU_LEVEL_NEW");
                                $dppObj->insertJpartnerDPP($profileFieldArr);
                                //End of DPP auto Suggestor

				// FTO
                                $fto_action = FTOStateUpdateReason::REGISTER;
                                $this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
                      
				// Mis reg count updated
				$dbRegCount = new MIS_REG_COUNT();
				$dbRegCount->updateEntryRegPage("PAGE2", 'Y', $this->loginProfile->getPROFILEID());
                //Make user login
                $loginObj=AuthenticationFactory::getAuthenicationObj();
                $loginObj->loginFromReg();
				/* Lead table updated */
				$dbRegLead = new MIS_REG_LEAD();
				$dbRegLead->updateRegisterEmail("INCOMPLETE='N'", $this->loginProfile->getEMAIL());
				RegistrationCommunicate::sendEmailAfterRegCompletion($profileid);
				RegistrationCommunicate::initiatePhoneVerification($this->loginProfile);
				RegistrationCommunicate::sendSms($profileid);
			}
			catch(Exception $ex)
			{
				
			}
			//Making user login again, since change in incomplete 
			/*$loginObj=new AuthenticationFactory::getAuthenicationObj();
			$loginObj->hashedPasswordFromDb=true;
			$result=$loginObj->login($this->loginProfile->getEMAIL(),$this->loginProfile->getPASSWORD());
			$apiObj->setAuthChecksum($result[AUTHCHECKSUM]);
			*/
            //////////////////////////////////
            //Remove trackRegJSMS Cookie
            if(isset($_COOKIE['regUID']))
            {
                unset($_COOKIE['regUID']);
                setcookie('regUID',null,-1,"/");
            }
            ////////////////////////////
            setcookie('reg_family',"1",0,"/");
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                        $resp =array("LANDINGPAGE"=>'FAMILYPAGE'); 
                        $apiObj->setResponseBody($resp);
			$apiObj->generateResponse();
		}
		else
		{
			RegistrationMisc::logServerSideValidationErrors("NMP2",$this->form);
			foreach ($this->form->getFormFieldSchema() as $name => $formField)
			{
				$error=$formField->getError();
				
				if($error){
					$errMes=$error->getMessageFormat();
					$resp[error][]=$errMes;
				}
			}
			$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiObj->setResponseBody($resp);
			$apiObj->generateResponse();
		}
		die;
		
    }
}
?>

