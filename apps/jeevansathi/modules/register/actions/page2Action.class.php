<?php

class page2Action extends sfAction {
    /** Executes Registration page 2
     *
     */
    public function execute($request) {
      $request->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[2]);
      $this->forward('register','regPage');
        //Jsb9 page load time tracking page2
		$this->loginData = $request->getAttribute("loginData");
		//$profileid=345345;
		//$this->loginData[PROFILEID]=345345;
		if(!$this->loginData[PROFILEID])
			$this->forward("register","page1");
		$profileid=$this->loginData[PROFILEID];
		$this->loginProfile = LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($this->loginData['PROFILEID'], "PROFILEID");
        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsRegPage2Url);
        $this->SITE_URL = sfConfig::get("app_site_url");
	$degreeGrouping = FieldMap::getFieldLabel("degree_grouping_reg",'','1');
	$this->ugGroup  = $degreeGrouping['ug'];
	$this->gGroup  = $degreeGrouping['g'];
	$this->pgGroup  = $degreeGrouping['pg'];
	$this->phdGroup  = $degreeGrouping['phd'];
        $this->form = new PageForm('', array("page" => 'DP2'), '');
        //Get array of name of fields that are in this page
        $field_array = RegEditFields::getFieldArray('DP2');
        //Get client side error messages that will be displayed by jquery validator
        $this->errMsg = ErrorHelp::getErrorArray($field_array);
        $this->IS_FTO_LIVE = FTOLiveFlags::IS_FTO_LIVE;
        $regArray = $request->getParameter('reg');
        $this->country = $regArray['country_res'];
        $relationship = $regArray['relationship'];
		$this->username=$this->loginProfile->getUSERNAME();
		$this->groupname=$request->getParameter('groupname');
		$this->source=$request->getParameter('source');
		$this->affiliateid=$request->getParameter('affiliateid');
	$this->hisher="her";
	if($this->loginProfile->getGENDER()=="M")
		$this->hisher="his";
        if ($relationship == '2') $this->yourHeading = "your son";
        elseif ($relationship == '2D') $this->yourHeading = "your daughter";
        elseif ($relationship == '6')  $this->yourHeading = "your Brother";
	elseif ( $relationship == '6D') $this->yourHeading = "your Sister";
        elseif ($relationship == '4') $this->yourHeading = "your relative";
        elseif ($relationship == '5') $this->yourHeading = "your client";
	if(!$this->yourHeading)
	{
		$this->yourHeading="yourself";
		$this->hisher="your";
	}
        if ($request->getParameter('submit_page2')) {
            $this->form->bind($request->getParameter('reg'));
            if ($this->form->isValid()) {
				
				/* Instantiate loggedin profile object */
				
				$now = date("Y-m-d G:i:s");
				$today = CommonUtility::makeTime(date("Y-m-d"));
				$values_that_are_not_in_form = array('INCOMPLETE' => 'N','ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today);
                $this->form->updateData($profileid,$values_that_are_not_in_form);
                
                //Added Community wise Welcome discount
                $memHandlerObj = new MembershipHandler();
                $memHandlerObj->addCommunityWelcomeDiscount($profileid,$this->loginProfile->getMTONGUE());
                
                //DPP Auto Suggestor implemenation :
                $dppObj=new DppAutoSuggest($this->loginProfile);
				$profileFieldArr=array("OCCUPATION","EDU_LEVEL_NEW");
				$dppObj->insertJpartnerDPP($profileFieldArr);
				//End of DPP auto Suggestor
				
				$fto_action = FTOStateUpdateReason::REGISTER;
				$this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
                /* Tracking Query for the Reg Count */
                
                $dbRegCount = new MIS_REG_COUNT();
                $dbRegCount->updateEntryRegPage("PAGE2", 'Y', $profileid);
                /* Ends Here */
                /* Lead table updated */
                $dbRegLead = new MIS_REG_LEAD();
                $dbRegLead->updateRegisterEmail("INCOMPLETE='N'", $this->loginProfile->getEMAIL());
                /* Ends Here */
				//Communicate to user through email and sms starts
				//RegistrationCommunicate::sendEmailAfterRegCompletion($profileid,$this->loginProfile->getCITY_RES());
				RegistrationCommunicate::sendEmailAfterRegCompletion($profileid);
				RegistrationCommunicate::sendSms($profileid);
				//Communicate to user through email and sms ends
               //Mark lead as converted 
				if ($leadid) {
					$dbLeadConversion = new MIS_LEAD_CONVERSION();
					$dbLeadConversion->updateLead($leadid);
				}
                //include 3rd page.
                $this->forward("register", "page3");
            }
            else {
				RegistrationMisc::logServerSideValidationErrors("DP2",$this->form);
            }
        }
        else
        {
			//Rocket fuel pixel for registration page2
			if (PixelCode::RocketFuelValidation($this->groupname,$this->source,$this->loginProfile->getAGE(),$this->loginProfile->getGENDER(),$this->loginProfile->getMTONGUE(),$this->loginProfile->getRELIGION())) {
				$this->pixelcode = PixelCode::fetchRocketFuelCode("regPage2");
            }
		}
    }
}
?>
