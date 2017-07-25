<?php
class page2v1Action extends sfAction {
    /** Executes Registration app page 1
     *
     *
     */
    public function execute($request) {
			
			$apiObj=ApiResponseHandler::getInstance();
			$this->loginData = $request->getAttribute("loginData");
			if(!$this->loginData)
			{
				$apiObj->setHttpArray(ApiResponseHandler::APP_REG_PAGE2_NOT_LOGGEDIN);
				$apiObj->generateResponse();
				die;
			}	
			
			$profileid=$this->loginData[PROFILEID];
			$this->loginProfile = LoggedInProfile::getInstance();
			$this->loginProfile->getDetail($this->loginData['PROFILEID'], "PROFILEID","*");

			$this->form = new PageForm('', array("page" => 'APP2'), '');
			$this->form->bind($request->getParameter('reg'));
			
				
			if ($this->form->isValid()) {
				$now = date("Y-m-d G:i:s");
				$today = CommonUtility::makeTime(date("Y-m-d"));
				$values_that_are_not_in_form = array('INCOMPLETE' => 'N','ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today);
        $this->form->updateData($profileid,$values_that_are_not_in_form);
                
                //Added Community wise Welcome discount
                $memHandlerObj = new MembershipHandler();
                $memHandlerObj->addCommunityWelcomeDiscount($profileid,$this->loginProfile->getMTONGUE());
				

  			//Communicate to user through email and sms starts
				try{
							$fto_action = FTOStateUpdateReason::REGISTER;
							$this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
							$dbRegCount = new MIS_REG_COUNT();
					                $dbRegCount->updateEntryRegPage("PAGE2", 'Y', $this->loginProfile->getPROFILEID());
							/* Lead table updated */
							$dbRegLead = new MIS_REG_LEAD();
							$dbRegLead->updateRegisterEmail("INCOMPLETE='N'", $this->loginProfile->getEMAIL());
							RegistrationCommunicate::sendEmailAfterRegCompletion($profileid);
							RegistrationCommunicate::sendSms($profileid);
				}
				catch(Exception $ex)
				{
					
				}
				//Making user login again, since change in incomplete 
				$loginObj=AuthenticationFactory::getAuthenicationObj();
				$loginObj->hashedPasswordFromDb=true;
        	                $result=$loginObj->login($this->loginProfile->getEMAIL(),$this->loginProfile->getPASSWORD(),1);
	                        $apiObj->setAuthChecksum($result[AUTHCHECKSUM]);
				$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$apiObj->generateResponse();
			}
			else
			{
				RegistrationMisc::logServerSideValidationErrors("APP2",$this->form);

				foreach ($this->form->getFormFieldSchema() as $name => $formField)
				{
					$error=$formField->getError();
					
					if($error){
						$errMes=$error->getMessageFormat();
						//$resp[error][$name]=$errMes;
						$resp[error][]=$errMes;
					}
				}
				$apiObj->setHttpArray(ResponseHandlerConfig::$APP_REG_FAILED);
				$apiObj->setResponseBody($resp);
				$apiObj->generateResponse();
			}
			die;
			
    }
}
?>

