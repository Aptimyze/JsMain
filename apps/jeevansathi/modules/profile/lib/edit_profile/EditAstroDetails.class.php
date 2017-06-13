<?php
class EditAstroDetails extends EditProfileComponent {
	public function submit() {
		$request = $this->action->getRequest();
		$now = date("Y-m-d H:i:s");
		$today = CommonUtility::makeTime(date("Y-m-d"));
		$submit_layer = $request->getParameter('submit_layer');
		if ($submit_layer == 1) {
			$paramArr = array("HOROSCOPE_MATCH" => $request->getParameter("horo_match"), "MOD_DT" => $now, "RASHI" => $request->getParameter("rashi"), "MANGLIK" => $request->getParameter("manglik"), "NAKSHATRA" => $request->getParameter("nakshatra"), "SUNSIGN" => $request->getParameter("sunsign"), "LAST_LOGIN_DT" => $today,);
			$this->updateAndLog($paramArr);
			$horo_action = $request->getParameter('horo_action');
			if ($horo_action) {
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/viewprofile.php?ownview=1&EditWhatNew=AstroData&nextLayer=$horo_action\"></body></html>";
				die;
			}
		}
	}
	public function display() {
		$this->request = $this->action->getRequest();
		$this->action->nextLayer = $this->request->getParameter("nextLayer");
		$now = date("Y-m-d H:i:s");
		$today = date("Y-m-d");
		list($BIRTH_YR, $BIRTH_MON, $BIRTH_DAY) = explode("-", $this->loginProfile->getDTOFBIRTH());
		$this->action->BIRTH_YR = $BIRTH_YR;
		$this->action->BIRTH_DAY = $BIRTH_DAY;
		$this->action->BIRTH_MON = $BIRTH_MON;
		$this->action->DTOFB = $BIRTH_DAY . " " . $MTOFBIRTH . " , " . $BIRTH_YR;
		$dob = explode("-", $this->loginProfile->getDTOFBIRTH());
		$this->action->MTOFBIRTH = my_format_date($dob[2], $dob[1], $dob[0], 3);
		$old_showHorocope = $this->loginProfile->getSHOW_HOROSCOPE();
		$request = $this->action->getRequest();
		$user_mtongue = $this->loginProfile->getMTONGUE();
		$this->action->GENDER = $this->loginProfile->getGENDER();
		update_astro_layer_mis($this->action->profileId, $request->getParameter('type'), $user_mtongue);
		$this->action->nak_array = loadnakshatra($user_mtongue, $this->loginProfile->getNAKSHATRA());
		$astroDetails = $this->loginProfile->getAstroKundali("onlyValues");
		if ($this->action->nextLayer) {
			if ($old_showHoroscope == 'Y') $country_birth_value = get_country_birth_value($astroDetails[COUNTRY_BIRTH]);
			else $country_birth_value = $this->loginProfile->getCOUNTRY_BIRTH();
			$this->action->COUNTRY_BIRTH = DropDownCreator::createDD("Country_Birth", $country_birth_value);
			$this->action->CITY_BIRTH = $this->loginProfile->getCITY_BIRTH();
			$birthtime = explode(":", $this->loginProfile->getBTIME());
			$this->action->HOUR = $birthtime[0];
			$this->action->MINUTE = $birthtime[1];
			$this->action->SECOND = $birthtime[2];
			$this->action->js_UniqueID = $this->loginProfile->getPROFILEID();
		}
		$this->action->NAKSHATRA = $this->loginProfile->getNAKSHATRA();
		$this->action->HOROSCOPE_MATCH = $this->loginProfile->getHOROSCOPE_MATCH();
		$this->action->SUNSIGN = DropDownCreator::createDD("sunsign", $this->loginProfile->getSUNSIGN(), "", 1);
		$this->action->RASHI = DropDownCreator::createDD("rashi", $this->loginProfile->getRASHI(), "", 1);
		$this->action->MANGLIK = DropDownCreator::createDD("manglik_label", $this->loginProfile->getMANGLIK(), "", 1);
	}
	public function getTemplateName() {
		if ($this->request->getParameter("nextLayer")) return "profile_edit_horoscope1";
		else return "profile_edit_horoscope";
	}
	public function getLayerHeading() {
		return "Astro/Kundali Details";
	}
}
