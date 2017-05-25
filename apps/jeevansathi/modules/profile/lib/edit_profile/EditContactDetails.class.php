<?php
class EditContactDetails extends EditProfileComponent {
	public function submit() {
		global $CALL_NOW;
		$this->request = $this->action->getRequest();
		$paramArr['EMAIL'] = strtolower(trim($this->request->getParameter('Email')));
		$paramArr['PARENTS_CONTACT'] = trim($this->request->getParameter('Parents_Contact'));
		$paramArr['CONTACT'] = trim($this->request->getParameter('Address'));
		$paramArr['PHONE_RES'] = trim($this->request->getParameter('Phone'));
		$paramArr['STD'] = trim($this->request->getParameter('State_Code'));
		$paramArr['ISD'] = trim($this->request->getParameter('ISD'));
		if(strpos($paramArr['ISD'],"+")===0){
			$paramArr['ISD']=substr($paramArr['ISD'],1);
		}
		//Remove leading zeros from ISD field
		$pattern_isd='/^0+/';
		preg_match ($pattern_isd , $paramArr['ISD'],$isd_matches);
		if($isd_matches[0]!=='')
			$paramArr['ISD']=str_replace($isd_matches[0],'',$paramArr['ISD']);
		$paramArr['PHONE_MOB'] = trim($this->request->getParameter('Mobile'));
		$paramArr['MESSENGER_ID'] = trim($this->request->getParameter('Messenger_ID'));
		$paramArr['PINCODE'] = trim($this->request->getParameter('pincode'));
		$paramArr['PARENT_PINCODE'] = trim($this->request->getParameter('parent_pincode'));
		$paramArr['SHOWADDRESS'] = $this->request->getParameter('showAddress');
		$paramArr['SHOW_PARENTS_CONTACT'] = $this->request->getParameter('Show_Parents_Contact');
		$paramArr['SHOWPHONE_RES'] = $this->request->getParameter('Showphone');
		$paramArr['SHOWPHONE_MOB'] = $this->request->getParameter('Showmobile');
		$paramArr['SHOWMESSENGER'] = $this->request->getParameter('showMessenger');
		$paramArr[PHONE_OWNER_NAME] = trim($this->request->getParameter('PHONE_OWNER_NAME'));
		$paramArr[MOBILE_OWNER_NAME] = trim($this->request->getParameter('MOBILE_OWNER_NAME'));
		$paramArr[PHONE_NUMBER_OWNER] = $this->request->getParameter('PHONE_NUMBER_OWNER');
		$paramArr[MOBILE_NUMBER_OWNER] = $this->request->getParameter('MOBILE_NUMBER_OWNER');
		$paramArr[TIME_TO_CALL_START] = $this->request->getParameter('time_to_call_start') . " " . $this->request->getParameter('start_am_pm');
		$paramArr[TIME_TO_CALL_END] = $this->request->getParameter('time_to_call_end') . " " . $this->request->getParameter('end_am_pm');
		$country_residence_val = explode("|X|", $this->request->getParameter('country_residence'));
		$country_residence_val = explode("|}|", $country_residence_val[0]);
		$country_residence = $country_residence_val[1];
		$city_residence = $this->request->getParameter('city_residence');
		if ($country_residence != 51 && $country_residence != 128) $city_residence = "";
		if ($city_residence != '0') {
			$city_residence_val = explode("|{|", $city_residence);
			$city_residence = $city_residence_val[1];
		}
		$paramArr[CITY_RES] = $city_residence;
		$paramArr[COUNTRY_RES] = $country_residence;
		$paramArr[RES_STATUS] = $this->request->getParameter('Rstatus');
		$paramArr[MESSENGER_CHANNEL] = $this->doWhiteListing('messenger_channel',$this->request->getParameter('Messenger'));
		//JPROFILE_CONTACT request parameters
		$contactParamArr[ALT_MOBILE] = trim($this->request->getParameter('ALT_MOBILE'));
		$contactParamArr[ALT_MOBILE_ISD] = trim($this->request->getParameter('ALT_MOBILE_ISD'));
		$contactParamArr[SHOWALT_MOBILE] = $this->request->getParameter('ALT_Showmobile');
		$contactParamArr[ALT_MOBILE_OWNER_NAME] = trim($this->request->getParameter('ALT_MOBILE_OWNER_NAME'));
		$contactParamArr[ALT_MOBILE_NUMBER_OWNER] = $this->request->getParameter('ALT_MOBILE_NUMBER_OWNER');
		$contactParamArr[ALT_MESSENGER_ID] = trim($this->request->getParameter('Alt_Messenger_ID'));
		$contactParamArr[ALT_MESSENGER_CHANNEL] = $this->doWhiteListing('messenger_channel',$this->request->getParameter('Alt_Messenger'));
		$contactParamArr[SHOW_ALT_MESSENGER] = $this->request->getParameter('Alt_showMessenger');
		$contactParamArr[BLACKBERRY] = trim($this->request->getParameter('blackberry_pin'));
		$contactParamArr[LINKEDIN_URL] = trim($this->request->getParameter('linkedin_id'));
		$contactParamArr[FB_URL] = trim($this->request->getParameter('facebook_id'));
		$contactParamArr[SHOWBLACKBERRY] = $this->request->getParameter('show_blackberry');
		$contactParamArr[SHOWLINKEDIN] = $this->request->getParameter('show_linkedin');
		$contactParamArr[SHOWFACEBOOK] = $this->request->getParameter('show_facebook');
		$contactInfoOld = $this->loginProfile->getExtendedContacts("OnlyValues");
		//Apply Call anonymous related setting
		if ($CALL_NOW) if (($paramArr['PHONE_RES'] && $paramArr['SHOWPHONE_RES'] == 'CN') or ($paramArr['PHONE_MOB'] && $paramArr['SHOWPHONE_MOB'] == 'CN') or ($contactParamArr[ALT_MOBILE] && $contactParamArr[SHOWALT_MOBILE] == 'CN')) {
			$contactParamArr[CALL_ANONYMOUS] = 'Y';
			$paramArr['SHOWPHONE_RES'] = 'N';
			$paramArr['SHOWPHONE_MOB'] = 'N';
			$contactParamArr[SHOWALT_MOBILE] = 'N';
		}
		//Check if any change is done
		$have_jcontact = $this->loginProfile->getHAVE_JCONTACT();
		$toChangeJProfileContact = false;
		foreach ($contactParamArr as $field => $value) {
			if ($have_jcontact == 'N' && $value) {
				$have_jcontact = 'Y';
				if(!$toChangeJProfileContact)
					$toChangeJProfileContact = true;
			}
			if ($value != $contactInfoOld[$field]) {
				if(!$toChangeJProfileContact)
				$toChangeJProfileContact = true;
				$this->changed_fields[]=$field;
			}
		}
		if(is_array($this->changed_fields))
		{
			if(in_array("ALT_MOBILE",$this->changed_fields))
			{
				$contactParamArr['ALT_MOB_STATUS']="N";
				$altMobUpdated = 1;
			}
		}
		//IF there is change in any of contact numbers, update FTO state of the user
		if((is_array($this->changed_fields) && in_array('ALT_MOBILE',$this->changed_fields))|| $paramArr['PHONE_MOB']!=$this->loginProfile->getPHONE_MOB()|| $paramArr['PHONE_RES']!=$this->loginProfile->getPHONE_RES()||$paramArr['ISD']!=$this->loginProfile->getISD())
		{
		    $call_fto_state_number_unverify_change=true;
		}
		if($paramArr['ISD']!=$this->loginProfile->getISD()){
			$paramArr['MOB_STATUS']='N';
			$paramArr['LANDL_STATUS']='N';
			$contactParamArr['ALT_MOBILE_ISD']=$paramArr[ISD];
			$contactParamArr['ALT_MOB_STATUS']='N';
		}
		if (!$toChangeJProfileContact) $toChange = $this->checkForChange($paramArr);
		//if any value is updated then do following otherwise do nothing)
		if ($toChangeJProfileContact || $toChange) {
			//If ALT_MOBILE and PHONE_MOB same then save only PHONE_MOB
			if ($contactParamArr[ALT_MOBILE]) if ($contactParamArr[ALT_MOBILE] == $paramArr[PHONE_MOB]) $contactParamArr[ALT_MOBILE] = '';
			//duplication check on messenger id if email address is provided in it
			if ($paramArr[MESSENGER_ID]) $paramArr[MESSENGER_ID] = $this->correctMessengerId($paramArr[MESSENGER_ID], $this->loginProfile->getMESSENGER_ID());
			if ($contactParamArr[ALT_MESSENGER_ID]) {
				
			//Check if alt messenger id given is same as messenger id, make it blank if true
				$contactParamArr[ALT_MESSENGER_ID] = ($contactParamArr[ALT_MESSENGER_ID] == $paramArr[MESSENGER_ID]) ? '' : $contactParamArr[ALT_MESSENGER_ID];
				$contactParamArr[ALT_MESSENGER_ID] = $this->correctMessengerId($contactParamArr[ALT_MESSENGER_ID], $contactInfoOld[ALT_MESSENGER_ID]);
			}
			//Email Verification
			$email_orig = strtolower($this->loginProfile->getEMAIL());
			if ($email_orig != $paramArr[EMAIL]) {
				$email_changed=true;
				$email_err_flag=checkemail($paramArr[EMAIL]);
				$this->DupEmail = $paramArr[EMAIL];
				if ( $email_err_flag ==2|| checkoldemail($paramArr[EMAIL],$this->action->profileId)) {
					$check_dup_email = 1;
					$this->check_dup_email = "Y";
				}else{
					if($email_err_flag){
						$check_dup_email = 1;
						$this->check_dup_email = "Y";
						$this->invalid_email=1;
					}
					else
					{
						//Insert into autoexpiry table, to expire all autologin url coming before date
						$autoExObj=new ProfileAUTO_EXPIRY();
						$autoExObj->replace($this->action->profileId,'E',date("Y-m-d H:i:s"));
						//end
						
						insert_in_old_email($this->action->profileId, $email_orig);
					}
				}
			}
			//Email verification ends here
			if (!$check_dup_email) {
				//Saving the source since offline operator can change the DATA
				$off_source = $this->loginProfile->getSOURCE();
				//Legace code
				$mbureau = $this->request->getParameter('mbureau');
				if ($crmback == 'admin' && $inf_profile != 'Y') unset($paramArr[EMAIL]);
				//Legacy code ends
				if ($mbureau != "bureau1") {
					$paramArr[PHONE_MOB] = redo_mobile_no($paramArr[PHONE_MOB]);
					$contactParamArr[ALT_MOBILE] = redo_mobile_no($contactParamArr[ALT_MOBILE]);
					if ($paramArr[EMAIL]) $screeningFieldArr[EMAIL] = $paramArr[EMAIL];
					$screeningFieldArr[CONTACT] = $paramArr[CONTACT];
					$screeningFieldArr[PARENTS_CONTACT] = $paramArr[PARENTS_CONTACT];
					$screeningFieldArr[PHONE_RES] = $paramArr[PHONE_RES];
					$screeningFieldArr[PHONE_MOB] = $paramArr[PHONE_MOB];
					$screeningFieldArr[MESSENGER_ID] = $paramArr[MESSENGER_ID];
					$screeningFieldArr[PHONE_OWNER_NAME] = $paramArr[PHONE_OWNER_NAME];
					$screeningFieldArr[MOBILE_OWNER_NAME] = $paramArr[MOBILE_OWNER_NAME];
					//default privacy setting will be yes for all contact details
					//Privacy fields
					$privacyFields = array("SHOWADDRESS", "SHOW_PARENTS_CONTACT", "SHOWPHONE_RES", "SHOWPHONE_MOB", "SHOWMESSENGER");
					foreach ($privacyFields as $privacyField) $paramArr[$privacyField] = $paramArr[$privacyField] ? $paramArr[$privacyField] : "Y";
					if ($Country_Code != '') $ISD = $Country_Code;
					elseif ($Country_Code_Mob != '') $ISD = $Country_Code_Mob;
				} else unset($paramArr[EMAIL]);
				$curflag = $this->getScreeningFlag($screeningFieldArr);
				//Set screening flag for extended contact details
				foreach (array( 'FB_URL', 'LINKEDIN_URL', 'BLACKBERRY', 'ALT_MOBILE_OWNER_NAME', 'ALT_MESSENGER_ID') as $field) {
					if ($contactParamArr[$field]) {
						if ($contactParamArr[$field] != $contactInfoOld[$field]) $curflag = Flag::removeFlag($field, $curflag);
					} else $curflag = Flag::setFlag($field, $curflag);
				}
				if ($paramArr[PHONE_RES] != $this->loginProfile->getPHONE_RES()) {
					$phone_updated = 1;
					$phone_changed = 1;
					$landlUpdated=1;
				}
				if ($paramArr[PHONE_MOB] == "") {$mobile_removed = 1;$mobUpdated=1;}
				if ($paramArr[PHONE_MOB] != $this->loginProfile->getPHONE_MOB()) {
					$phone_updated = 1;
					$mob_updated = 1;
					$mobUpdated = 1;
				}
				if ($this->request->getParameter('post_login')) {
					$this->action->post_login = 1;
					if ($phone_changed || $mob_updated) {
						$post_login = 0;
					}
				}
				//Archive contact details function defined in functions_edit_profile.php
				$smartyVar = archive_contacts($paramArr, $this->action->profileId);
				if(count($smartyVar))foreach ($smartyVar as $key => $val) $this->action->$key = $val;
				$paramArr[SCREENING] = $curflag;
				$paramArr[HAVE_JCONTACT] = $have_jcontact;
				
				if($paramArr[PHONE_RES]!="")
					$paramArr[PHONE_WITH_STD] = $paramArr[STD] . $paramArr[PHONE_RES];
				else
					$paramArr[PHONE_WITH_STD]="";

				$paramArr[MOD_DT] = date("Y-m-d H:i:s");
				$paramArr[KEYWORDS] = $this->getUpdatedKeyword(array('CITY' => FieldMap::getFieldLabel("city", $paramArr[CITY_RES])));
				$this->updateAndLog($paramArr, $contactParamArr);
				if(is_array($this->changed_fields))
					$this->loginProfile->editContact($contactParamArr);
				if($mobUpdated==1)
					phoneUpdateProcess($this->loginProfile->getPROFILEID(), '', 'M', 'E');
				if($landlUpdated==1)
					phoneUpdateProcess($this->loginProfile->getPROFILEID(), '', 'L', 'E');
				if($altMobUpdated==1)
					phoneUpdateProcess($this->loginProfile->getPROFILEID(), '', 'A', 'E');
				//ENtry into respective bot table if gmail or yahoo email provided
				if($email_changed)
					bot_email_entry($this->action->profileId, $paramArr[EMAIL]);
				//Call manoj's ivr verification code
				ivr_call($this->action->profileId, $phone_changed, $phone_updated, $mob_updated, $paramArr[PHONE_MOB], $paramArr[PHONE_RES], $paramArr[STD], $off_source);
				//Change fto state if contact address changed
				if($call_fto_state_number_unverify_change){
						$fto_action = FTOStateUpdateReason::NUMBER_UNVERIFY;
						$this->loginProfile->getDetail();
						$this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
				}
				//Sms Cannot be sent to following user.
				if ($mobile_removed || $this->request->getParameter('Country_Residence') != '51') remove_from_invalid_email_mailer($this->action->profileId);
			}
		}
	}
	public function display() {
		//Contact details layer has addition html next to save button, that need to be added in common edit layout
		global $CALL_NOW;
		$this->action->getResponse()->setSlot("additnl_save", $this->action->getPartial("contact_save"));
		$this->action->EMAIL = $this->loginProfile->getEMAIL();
		$this->action->PHONE_NUMBER_OWNER = $this->loginProfile->getPHONE_NUMBER_OWNER();
		$this->action->PHONE_OWNER_NAME = $this->loginProfile->getPHONE_OWNER_NAME();
		$this->action->MOBILE_NUMBER_OWNER = $this->loginProfile->getMOBILE_NUMBER_OWNER();
		$this->action->MOBILE_OWNER_NAME = $this->loginProfile->getMOBILE_OWNER_NAME();
		$this->action->HOROSCOPE_MATCH = $this->loginProfile->getHOROSCOPE_MATCH();
		$timetocallstart = $this->loginProfile->getTIME_TO_CALL_START();
		$timetocallend = $this->loginProfile->getTIME_TO_CALL_END();
		$time_to_call_start = substr($timetocallstart, 0, 2);
		$start_am_pm = trim(substr($timetocallstart, 2, 4));
		$this->action->time_to_call_start = $time_to_call_start;
		$this->action->start_am_pm = $start_am_pm;
		$time_to_call_end = substr($timetocallend, 0, 2);
		$end_am_pm = trim(substr($timetocallend, 2, 4));
		$this->action->time_to_call_end = $time_to_call_end;
		$this->action->end_am_pm = $end_am_pm;
		$this->action->COUNTRY_RES_VAL = $this->loginProfile->getDecoratedCountry();
		$this->action->COUNTRY_RES = DropDownCreator::createDD("cityAndCountry", $this->loginProfile->getCOUNTRY_RES(),"","",1);
		if($this->loginProfile->getCITY_RES()==='0')
			$this->action->CITY_SELECTED='0';
		else
			$this->action->CITY_SELECTED = "|{|" . $this->loginProfile->getCITY_RES();
		$this->action->RES_STATUS = $this->loginProfile->getRES_STATUS();
		$this->action->FAMILYINFO = $this->loginProfile->getFAMILYINFO();
		$this->action->PARENTS_CONTACT = $this->loginProfile->getPARENTS_CONTACT();
		$this->action->PARENT_PINCODE = $this->loginProfile->getPARENT_PINCODE();
		$this->action->SHOW_PARENTS_CONTACT = $this->loginProfile->getSHOW_PARENTS_CONTACT();
		$this->action->CONTACT = $this->loginProfile->getCONTACT();
		$this->action->SHOWADDRESS = $this->loginProfile->getSHOWADDRESS();
		$this->action->PINCODE = $this->loginProfile->getPINCODE();
		$this->action->PHONE_RES = $this->loginProfile->getPHONE_RES();
		$this->action->PHONE_MOB = $this->loginProfile->getPHONE_MOB();
		$this->action->SHOWPHONE_RES = $this->loginProfile->getSHOWPHONE_RES();
		$this->action->SHOWPHONE_MOB = $this->loginProfile->getSHOWPHONE_MOB();
		$this->action->MESSENGER_ID = $this->loginProfile->getMESSENGER_ID();
		$this->action->MESSENGER_CHANNEL = $this->loginProfile->getMESSENGER_CHANNEL();
		$this->action->SHOWMESSENGER = $this->loginProfile->getSHOWMESSENGER();
		$this->action->CALL_NOW = $CALL_NOW;
		if ($this->loginProfile->getISD() == '') $Country_Code = get_code('COUNTRY', $this->loginProfile->getCOUNTRY_RES());
		else $Country_Code = "+".$this->loginProfile->getISD();
		if ($this->loginProfile->getSTD() == '' && $this->loginProfile->getCOUNTRY_RES() == 51) $State_Code = get_code('CITY_INDIA', $this->loginProfile->getCITY_RES());
		else $State_Code = $this->loginProfile->getSTD();
		$ccc = create_code("COUNTRY");
		$csc = create_code("CITY_INDIA");
		$this->action->country_isd_code = $ccc;
		$this->action->india_std_code = $csc;
		$this->action->country_code = $Country_Code;
		$this->action->country_code_mob = $Country_Code;
		$this->action->state_code = $State_Code;
		$extendedContacts = $this->loginProfile->getExtendedContacts("onlyValues");
		$this->action->ALT_MOBILE_ISD = $extendedContacts[ALT_MOBILE_ISD];
		$this->action->ALT_MOBILE = $extendedContacts[ALT_MOBILE];
		$this->action->ALT_MOBILE_NUMBER_OWNER = $extendedContacts[ALT_MOBILE_NUMBER_OWNER];
		$this->action->ALT_SHOWPHONE_MOB = $extendedContacts[SHOWALT_MOBILE];
		$this->action->ALT_MOBILE_OWNER_NAME = $extendedContacts[ALT_MOBILE_OWNER_NAME];
		$this->action->ALT_MESSENGER_CHANNEL = $extendedContacts[ALT_MESSENGER_CHANNEL];
		$this->action->ALT_SHOWMESSENGER = $extendedContacts[SHOW_ALT_MESSENGER];
		$this->action->ALT_MESSENGER_ID = $extendedContacts[ALT_MESSENGER_ID];
		$this->action->blackberry_pin = $extendedContacts[BLACKBERRY];
		$this->action->linkedin_url = $extendedContacts[LINKEDIN_URL];
		$this->action->FB_URL = $extendedContacts[FB_URL];
		$this->action->SHOWBLACKBERRY = $extendedContacts[SHOWBLACKBERRY];
		$this->action->SHOWLINKEDIN = $extendedContacts[SHOWLINKEDIN];
		$this->action->SHOWFACEBOOK = $extendedContacts[SHOWFACEBOOK];
		if ($CALL_NOW) if ($extendedContacts[CALL_ANONYMOUS] == 'Y') {
			$this->action->ALT_SHOWPHONE_MOB = 'CN';
			$this->action->SHOWPHONE_RES = 'CN';
			$this->action->SHOWPHONE_MOB = 'CN';
		}
	}
	public function getTemplateName() {
		return "profile_edit_contact";
	}
	public function getLayerHeading() {
		return "Contact Information";
	}
	public function getOnSubmitJs() {
		return "return validate();";
	}
	private function correctMessengerId($messengerIdNew, $messengerIdOld) {
		if ($messengerIdNew != $messengerIdOld) {
			if (strstr($messengerIdNew, "@")) if (checkemail($messengerIdNew)) return $messengerIdOld;
		}
		return $messengerIdNew;
	}
}
