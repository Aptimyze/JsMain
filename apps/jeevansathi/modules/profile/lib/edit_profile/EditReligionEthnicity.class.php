<?php
class EditReligionEthnicity extends EditProfileComponent {
	public function submit() {
		
		$today = CommonUtility::makeTime(date("Y-m-d"));
		$this->request = $this->action->getRequest();
		$Subcaste = $this->request->getParameter('subcaste');
		$Subcaste = trim($Subcaste);
		//$Religion_temp = explode('|X|', $this->request->getParameter('Religion'));
		$Religion = $this->loginProfile->getRELIGION();
		$religionInfo = $this->loginProfile->getReligionInfo(1);
		//Screening flag calculation ends
		$now = date("Y-m-d H-i-s");
		$paramArr = array('MTONGUE' => $this->request->getParameter('Mtongue'), 'SUBCASTE' => '', 'SECT' => '', 'GOTHRA' => '', 'GOTHRA_MATERNAL' => '', 'LAST_LOGIN_DT' => $today, 'MOD_DT' => $now,'ANCESTRAL_ORIGIN'=>trim($this->request->getParameter('native_place')));
		
		$native_place = trim($this->request->getParameter('reg_ancestral_origin'));
	
		$paramArr['ANCESTRAL_ORIGIN'] = $native_place;
		
		$nativePlaceObj = new JProfile_NativePlace($this->loginProfile);
		$nativePlaceObj->getInfo();
		$nativePlaceArr['NATIVE_COUNTRY'] = $this->request->getParameter('reg_native_country');
		$nativePlaceArr['NATIVE_CITY'] = $this->request->getParameter('reg_native_city');
		$nativePlaceArr['NATIVE_STATE'] = $this->request->getParameter('reg_native_state');
		//Compare new submit native info with last info
		//Unset Info if they are same
		if($nativePlaceArr['NATIVE_COUNTRY']===$nativePlaceObj->getNativeCountry())
			unset($nativePlaceArr['NATIVE_COUNTRY']);
		if($nativePlaceArr['NATIVE_CITY']===$nativePlaceObj->getNativeCity())
			unset($nativePlaceArr['NATIVE_CITY']);
		if($nativePlaceArr['NATIVE_STATE']===$nativePlaceObj->getNativeState())
			unset($nativePlaceArr['NATIVE_STATE']);
		//Validating Native Place 
		$this->validateNativePlace($nativePlaceArr);
		
		switch ($Religion) {
			case Religion::HINDU:
				$Caste = $this->request->getParameter('Caste_hindu');
				$Sect = $this->request->getParameter('sect_hindu');
				//calculate Screening flag
				$gotra_maternal = trim($this->request->getParameter('gotra_maternal'));
				$Gothra = trim($this->request->getParameter('gotra'));
				$fieldsToScreen = array("SUBCASTE" => $Subcaste,"GOTHRA" => $Gothra, "GOTHRA_MATERNAL" => $gotra_maternal,);
				$curflag = $this->getScreeningFlag($fieldsToScreen);
				$paramArr['SUBCASTE'] = $Subcaste;
				$paramArr['GOTHRA'] = $Gothra;
				$paramArr['GOTHRA_MATERNAL'] = $gotra_maternal;
			break;
			case Religion::MUSLIM:
				$Caste = $this->request->getParameter('Caste_muslim');
				$Sect = $this->request->getParameter('sect_muslim');
				$maththab_shia = $this->request->getParameter("maththab_shia");
				$maththab_sunni = $this->request->getParameter("maththab_sunni");
				if ($maththab_shia && $Caste == 151) $maththab = $maththab_shia;
				elseif ($maththab_sunni && $Caste == 152) $maththab = $maththab_sunni;
				$religion_paramArray = array('PROFILEID' => $this->action->profileId, 'MATHTHAB' => $maththab, 'NAMAZ' => $this->request->getParameter('namaz'), 'ZAKAT' => $this->request->getParameter('zakat'), 'FASTING' => $this->request->getParameter('fasting'), 'QURAN' => $this->request->getParameter('quran'), 'UMRAH_HAJJ' => $this->request->getParameter('umrah_hajj'), 'SUNNAH_BEARD' => $this->request->getParameter('sunnah_beard'), 'SUNNAH_CAP' => $this->request->getParameter('sunnah_cap'), 'HIJAB' => $this->request->getParameter('hijab'), 'HIJAB_MARRIAGE' => $this->request->getParameter('hijab_marriage'), 'WORKING_MARRIAGE' => $this->request->getParameter('working_marriage'),);
				edit_nonHindu_religion($religion_paramArray, 'newjs.JP_MUSLIM');
				$paramArr['SPEAK_URDU'] = $this->request->getParameter('speak_urdu');
				break;
			case Religion::JAIN:
				$Caste = $this->request->getParameter('Caste_jain');
				$Sect = $this->request->getParameter('sect_jain');
				$Gothra = trim($this->request->getParameter('gotra_jain'));
				$fieldsToScreen = array("GOTHRA" => $Gothra, );
				$paramArr['GOTHRA'] = $Gothra;
				$curflag = $this->getScreeningFlag($fieldsToScreen);
				$religion_paramArray = array('PROFILEID' => $this->action->profileId, 'SAMPRADAY' => $this->request->getParameter('sampraday'),);
				edit_nonHindu_religion($religion_paramArray, 'newjs.JP_JAIN');
				break;
			case Religion::CHRISTIAN:
				$Caste = $this->request->getParameter('Caste_christian');
				$diocese = trim($this->request->getParameter('diocese'));
				$curflag = Flag::setFlag("SUBCASTE", $curflag);
				$religion_paramArray = array('PROFILEID' => $this->action->profileId, 'DIOCESE' => $diocese, 'BAPTISED' => $this->request->getParameter('baptised'), 'READ_BIBLE' => $this->request->getParameter('read_bible'), 'OFFER_TITHE' => $this->request->getParameter('offer_tithe'), 'SPREADING_GOSPEL' => $this->request->getParameter('spreading_gospel'),);
				edit_nonHindu_religion($religion_paramArray, 'newjs.JP_CHRISTIAN');
				break;
			case Religion::SIKH:
				$Caste = $this->request->getParameter('Caste_sikh');
				$Sect = $this->request->getParameter('sect_sikh');
				$Gothra = trim($this->request->getParameter('gotra_sik'));
				$fieldsToScreen = array("GOTHRA" => $Gothra,);
				$paramArr['GOTHRA'] = $Gothra;
				$curflag = $this->getScreeningFlag($fieldsToScreen);
				$religion_paramArray = array('PROFILEID' => $this->action->profileId, 'AMRITDHARI' => $this->request->getParameter('amritdhari'), 'CUT_HAIR' => $this->request->getParameter('cut_hair'), 'TRIM_BEARD' => $this->request->getParameter('trim_beard'), 'WEAR_TURBAN' => $this->request->getParameter('wear_turban'), 'CLEAN_SHAVEN' => $this->request->getParameter('clean_shaven'),);
				edit_nonHindu_religion($religion_paramArray, 'newjs.JP_SIKH');
				break;
			case Religion::PARSI:
				$Caste = 153;
				$religion_paramArray = array('PROFILEID' => $this->action->profileId, 'ZARATHUSHTRI' => $this->request->getParameter('zarathushtri'), 'PARENTS_ZARATHUSHTRI' => $this->request->getParameter('parent_zarathushtri'),);
				edit_nonHindu_religion($religion_paramArray, 'newjs.JP_PARSI');
				break;
			default:
				if ($Religion == Religion::JEWISH) $Caste = 148;
				elseif ($Religion == Religion::BUDDHIST) {
					$Caste = 1;
					$Sect = $this->request->getParameter('sect_buddhist');
					$Gothra = trim($this->request->getParameter('gotra_bud'));
					$fieldsToScreen = array("GOTHRA" => $Gothra,);
					$paramArr['GOTHRA'] = $Gothra;
					$curflag = $this->getScreeningFlag($fieldsToScreen);
				}
			}
			$paramArr[CASTE] = $Caste;
			$paramArr[SECT] = $Sect;
			if ($Religion != Religion::HINDU) {
				$curflag = $this->loginProfile->getSCREENING();
				$curflag = Flag::setFlag("SUBCASTE", $curflag);
			}
			if ($Religion != Religion::CHRISTIAN) unset_diocese($this->action->profileId);
			else {
				if ($diocese == "") $curflag = Flag::setFlag("GOTHRA", $curflag);
				elseif ($religionInfo["DIOCESE"] != $diocese) $curflag = Flag::removeFlag("GOTHRA", $curflag);
			}
			//ANCESTRAL_ORIGIN Screening
			if($native_place!=$this->loginProfile->getANCESTRAL_ORIGIN())
				$curflag = Flag::removeFlag("ANCESTRAL_ORIGIN", $curflag);
			//If Blank is specify by user then set flag
			if(strlen($native_place) == 0)
				$curflag = Flag::setFlag("ANCESTRAL_ORIGIN", $curflag);

			$paramArr['SCREENING'] = $curflag;
			if (!headers_sent()) {
				$domain = $this->request->getParameter('domain');
				setcookie('JS_RELIGION', $Religion, 0, "/", $domain);
				setcookie('JS_MTONGUE', $Mtongue, 0, "/", $domain);
				setcookie('JS_CASTE', $Caste, 0, "/", $domain);
			}
			//update mis if any
			edit_religion_mis_updates($this->action->profileId, $Mtongue);
			//Check if Keyword is to be updated
			if ($Caste != $this->loginProfile->getCASTE()) {
				$paramArr['KEYWORDS'] = $this->getUpdatedKeyword(array('CASTE' => FieldMap::getFieldLabel('caste', $Caste)));
			}
			
			//Update in Native Place
			$bIsNative_PlaceUpdated=false;
			if(count($nativePlaceArr)){
				$bIsNative_PlaceUpdated=true;
				$nativePlaceStoreObj = ProfileNativePlace::getInstance();
				if($nativePlaceObj->IsRecordExist())
					$nativePlaceStoreObj->UpdateRecord($this->loginProfile->getPROFILEID(),$nativePlaceArr);
				else
				{
					$nativePlaceArr['PROFILEID'] = $this->loginProfile->getPROFILEID();
					$nativePlaceStoreObj->InsertRecord($nativePlaceArr);
				}
				//Log this update
				$nativePlaceArr['PROFILEID'] = $this->loginProfile->getPROFILEID();
				$nativePlaceObj->LogUpdate($nativePlaceArr);
			}
			//Finally update in JProfile and Edit_log tables
			if($bIsNative_PlaceUpdated)
				$this->updateAndLog($paramArr,array(),0,$bIsNative_PlaceUpdated);
			else
				$this->updateAndLog($paramArr);
      //mapping auto sug data to the user input.
      $this->mapAutoSug($this->loginProfile->getPROFILEID(), "SUBCASTE", $paramArr['SUBCASTE']);
		}
		
		public function display() {
			$this->religionInfo = $this->loginProfile->getReligionInfo(1); //Get religion related data in array
			
			$this->action->RELIGION = populate_religion($this->loginProfile->getRELIGION());
			$this->action->RELIGION_SELF = FieldMap::getFieldLabel("religion", $this->loginProfile->getRELIGION());
			$this->action->RELIGION_VALUE=$this->loginProfile->getRELIGION();
			$this->action->community = $this->loginProfile->getMTONGUE();
			$this->action->MTONGUE = DropDownCreator::createDD("mtongue", $this->loginProfile->getMTONGUE());
			$this->action->NATIVE_PLACE = $this->loginProfile->getANCESTRAL_ORIGIN();
			//Get Caste Label
			$this->action->casteLabel = JsCommon::getCasteLabel($this->loginProfile);
			$this->action->sectLabel = JsCommon::getSectLabel($this->loginProfile);
			//Caste drop downs
			$Caste = $this->loginProfile->getCASTE();
			$Sect = $this->loginProfile->getSECT();
			if (in_array($Caste, array(14, 149, 2, 154, 173))) $Caste = '';
			$this->action->CASTE = DropDownCreator::createDD("caste", $Caste, $this->action->RELIGION);
			$this->action->CASTE_HINDU = DropDownCreator::createDD("caste_hindu", $Caste);
			$this->action->SECT_HINDU = DropDownCreator::createDD("sect_hindu", $Sect);
			$this->action->CASTE_JAIN = DropDownCreator::createDD("caste_jain", $Caste);
			$this->action->SECT_JAIN = DropDownCreator::createDD("sect_jain", $Sect);
			$this->action->CASTE_CHRISTIAN = DropDownCreator::createDD("caste_christian", $Caste);
			$this->action->CASTE_PARSI = DropDownCreator::createDD("caste_parsi", $Caste);
			$this->action->CASTE_MUSLIM = DropDownCreator::createDD("caste_muslim", $Caste);
			$this->action->SECT_MUSLIM = DropDownCreator::createDD("sect_muslim", $Sect);
			$this->action->CASTE_SIKH = DropDownCreator::createDD("caste_sikh", $Caste);
			$this->action->SECT_SIKH = DropDownCreator::createDD("sect_sikh", $Sect);
			$this->action->SECT_BUDDHIST = DropDownCreator::createDD("sect_buddhist", $Sect);
			//Native Place
			$nativePlaceObj = new JProfile_NativePlace($this->loginProfile);
			$nativePlaceObj->getInfo();
			$this->action->NATIVE_STATE = DropDownCreator::createDD("state_india",$nativePlaceObj->getNativeState(),"",1);
			$this->action->NATIVE_COUNTRY = DropDownCreator::createDD("country",$nativePlaceObj->getNativeCountry(),"",1);
			$this->action->NATIVE_CITY = DropDownCreator::createDD("native_city",$nativePlaceObj->getNativeCity(),$nativePlaceObj->getNativeState());
			$this->action->COUNTRY_DEFAULT = (!$nativePlaceObj->getNativeCountry())?51:($nativePlaceObj->getNativeCountry());
			$this->action->OUTSIDE_INDIA=0;
			if($this->action->COUNTRY_DEFAULT && $this->action->COUNTRY_DEFAULT != 51)
			{
				$this->action->OUTSIDE_INDIA = 1;
			}
			//Caste drop downs end here
			//Religion specific dropdowns
			//for Jain
			$this->action->SAMPRADAY_ARR = DropDownCreator::createDD("sampraday", $this->religionInfo);
			//for Muslims
			$this->action->MATHTHAB_SUNNI = DropDownCreator::createDD("maththab_sunni", $this->religionInfo['MATHTHAB']);
			$this->action->MATHTHAB_SHIA = DropDownCreator::createDD("maththab_shia", $this->religionInfo['MATHTHAB']);
			$this->action->NAMAZ_ARR = DropDownCreator::createDD("namaz", $this->religionInfo['NAMAZ']);
			$this->action->FASTING_ARR = DropDownCreator::createDD("fasting", $this->religionInfo['FASTING']);
			$this->action->QURAN_ARR = DropDownCreator::createDD("quran", $this->religionInfo['QURAN']);
			$this->action->UMRAH_HAJJ_ARR = DropDownCreator::createDD("umrah_hajj", $this->religionInfo['UMRAH_HAJJ']);
			$this->action->SUNNAH_CAP_ARR = DropDownCreator::createDD("sunnah_cap", $this->religionInfo['SUNNAH_CAP']);
			$this->action->SUNNAH_BEARD_ARR = DropDownCreator::createDD("sunnah_beard", $this->religionInfo['SUNNAH_BEARD']);
			
			$this->action->NATIVE_PLACE = $this->loginProfile->getANCESTRAL_ORIGIN();
			//Religion Specific Dropdown ends
			switch ($this->loginProfile->getRELIGION()) {
				case Religion::HINDU:
					$this->action->SUBCASTE = $this->loginProfile->getSUBCASTE();
					$this->action->GOTHRA = $this->loginProfile->getGOTHRA();
					$this->action->GOTHRA_MATERNAL = $this->loginProfile->getGOTHRA_MATERNAL();
				break;
				case Religion::PARSI:
					$this->action->ZARATHUSHTRI = $this->religionInfo[ZARATHUSHTRI];
					$this->action->PARENTS_ZARATHUSHTRI = $this->religionInfo[PARENTS_ZARATHUSHTRI];
				break;
				case Religion::CHRISTIAN:
					$this->action->DIOCESE = $this->religionInfo[DIOCESE];
					$this->action->BAPTISED = $this->religionInfo[BAPTISED];
					$this->action->READ_BIBLE = $this->religionInfo[READ_BIBLE];
					$this->action->OFFER_TITHE = $this->religionInfo[OFFER_TITHE];
					$this->action->SPREADING_GOSPEL = $this->religionInfo[SPREADING_GOSPEL];
				break;
				case Religion::SIKH:
					$this->action->AMRITDHARI = $this->religionInfo[AMRITDHARI];
					$this->action->CUT_HAIR = $this->religionInfo[CUT_HAIR];
					$this->action->TRIM_BEARD = $this->religionInfo[TRIM_BEARD];
					$this->action->WEAR_TURBAN = $this->religionInfo[WEAR_TURBAN];
					$this->action->CLEAN_SHAVEN = $this->religionInfo[CLEAN_SHAVEN];
				break;
				case Religion::JAIN:
					$this->action->SAMPRADAY = FieldMap::getFieldLabel("sampraday", $this->religionInfo);
				break;
				case Religion::MUSLIM:
					$this->action->MATHTHAB = $this->religionInfo[MATHTHAB];
					$this->action->SPEAK_URDU = $this->loginProfile->getSPEAK_URDU();
					$this->action->NAMAZ = FieldMap::getFieldLabel("namaz", $this->religionInfo[NAMAZ]);
					$this->action->ZAKAT = $this->religionInfo["ZAKAT"];
					$this->action->FASTING = FieldMap::getFieldLabel("fasting", $this->religionInfo[FASTING]);
					$this->action->QURAN = FieldMap::getFieldLabel("quran", $this->religionInfo[QURAN]);
					$this->action->UMRAH_HAJJ = FieldMap::getFieldLabel("umrah_hajj", $this->religionInfo[UMRAH_HAJJ]);
					$this->action->SUNNAH_BEARD = FieldMap::getFieldLabel("sunnah_beard", $this->religionInfo[SUNNAH_BEARD]);
					$this->action->SUNNAH_CAP = FieldMap::getFieldLabel("sunnah_cap", $this->religionInfo[SUNNAH_CAP]);
					$this->action->HIJAB_MARRIAGE = $this->religionInfo[HIJAB_MARRIAGE];
					$this->action->WORKING_MARRIAGE = $this->religionInfo[WORKING_MARRIAGE];
				break;
			}
		}
		public function getTemplateName() {
			return "profile_edit_religion";
		}
		public function getLayerHeading() {
			return "Religion and Ethnicity";
		}
	
		private function validateNativePlace(&$infoArr)
		{
			foreach($infoArr as $szKey=>$szVal)
			{
				$szFMLabel = ObjectiveFieldMap::getFieldMapKey($szKey);
				$arrMap = FieldMap::getFieldLabel($szFMLabel,'',1);
				
				if(!array_key_exists($szVal,$arrMap) && $szVal !=='0')
				{
					$infoArr[$szKey] = '';
				}
			}	
		}	
	}
	
