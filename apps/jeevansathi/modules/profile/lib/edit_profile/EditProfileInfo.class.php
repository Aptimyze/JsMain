<?php
class EditProfileInfo extends EditProfileComponent {
	public function submit() {
		$request = $this->action->getRequest();
		$now = date("Y-m-d H:i:s");
		$today = date("Y-m-d");
		if ($request->getParameter("EditWhat") == "spouse") {
			$paramArr[SPOUSE] = stripslashes(trim($request->getParameter('SPOUSE')));
			$toChange = $this->checkForChange($paramArr);
			if ($toChange) {
				$curflag = $this->getScreeningFlag($paramArr);
				$paramArr[SCREENING] = $curflag;
				$paramArr[MOD_DT] = $now;
				$this->updateAndLog($paramArr);
				//MatchAlert Tracking////////////////////
				ProfileCommon::matchAlertTrackingForDPP($request,$this->loginProfile,"UPDATE",MatchAlert_DPP_Tracking::DESKTOP_EDIT);
				//////////////////////////////////////////
			}
			$this->to_dpp = 1;
		} else {
			$paramArr[YOURINFO] = stripslashes(trim($request->getParameter('Information')));
			$paramArr[FAMILYINFO] = stripslashes(trim($request->getParameter('Family')));
			$toChange = $this->checkForChange($paramArr);
			if ($toChange) {
				if($paramArr[YOURINFO]!=$this->loginProfile->getYOURINFO())
				{
                                        RegChannelTrack::insertPageChannel($this->loginProfile->getPROFILEID(),PageTypeTrack::_PAGE2);
					if(Flag::isFlagSet("YOURINFO",$this->loginProfile->getSCREENING()))
					{
						// insert OR update about me in YOUR_INFO_OLD table
						$dbYourInfoOldObj= new YOUR_INFO_OLD();
						$dbYourInfoOldObj->updateAboutMeOld($this->loginProfile->getPROFILEID(),$this->loginProfile->getYOURINFO());
					}
				}
                                if($paramArr[FAMILYINFO]!=$this->loginProfile->getFAMILYINFO())
					RegChannelTrack::insertPageChannel($this->loginProfile->getPROFILEID(),PageTypeTrack::_ABOUTFAMILY);
					
				$curflag = $this->getScreeningFlag($paramArr);
				if (strlen($paramArr[YOURINFO]) >= 100) {
					$incompleteFields = IncompleteLib::incompleteFieldsOfProfile($this->loginProfile);
					$mark = $request->getParameter('mark');
					if ($mark == 1 || (count($incompleteFields) == 1 && $incompleteFields[0] == "YOURINFO")) {
						$paramArr[INCOMPLETE] = 'N';
						$paramArr[ENTRY_DT] = $now;
						if($request->getParameter('IncompleteMail'))
							$paramArr[SEC_SOURCE]='I';
						$this->after_login = 0;
						$this->tracking = 1;
						//Fto state change after completion of page2
                                                $incompleteScreeningDbObj= new MIS_INCOMPLETE_SCREENING();
                                                if($incompleteScreeningDbObj->getIncompeleteScreeningProfileId($this->loginProfile->getPROFILEID()))
                                                        $fto_action = FTOStateUpdateReason::INCOMPLETE_TO_COMPLETE;
                                                else{   
                                                        if(!FTOStateHandler::profileExistsInFTOStateLog($this->loginProfile->getPROFILEID()))
                                                                $fto_action = FTOStateUpdateReason::REGISTER;
                                                }       
                                                if($fto_action) $this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
					} elseif ($mark == 3 || count($incompleteFields) > 1){ $this->after_login = 2;
					$this->incompleteMail=$request->getParameter('IncompleteMail');
					}
					$paramArr[SCREENING] = $curflag;
					$paramArr[MOD_DT] = $now;
					$this->updateAndLog($paramArr);
				} else $this->error = "less_chars";
			}
		}
	}
	public function display() {
		$request = $this->action->getRequest();
		$yourinfo = $this->loginProfile->getYOURINFO();
		$this->action->YOURINFO = $yourinfo;
		$this->action->RELATION = $this->loginProfile->getRELATION();
		$this->action->GENDER = $this->loginProfile->getGENDER();
		$this->action->INFOLEN = strlen($yourinfo);
		$this->action->FAMILYINFO = $this->loginProfile->getFAMILYINFO();
		$this->action->for_fam=$request->getParameter('for_fam');
		if ($for_about_us = $request->getParameter('for_about_us')) {
			$relation = $this->loginProfile->getRELATION();
			if ($relation == 2 && $this->loginProfile->getGENDER == 'M') $relation = 7;
			$this->action->for_about_value = $relation;
			$this->action->for_about_us = $for_about_us;
		}
	}
	public function getTemplateName() {
		return "profile_edit_aboutmyself";
	}
	public function getLayerHeading() {
		$relation = $this->loginProfile->getRELATION();
		if ($relation == 1) return "About Myself";
		elseif ($this->loginProfile->getGENDER() == 'F') return "About Her";
		else return "About Him";
	}
	public function getOnSubmitJs() {
		return "return validate();";
	}
}
