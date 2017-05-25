<?php
class EditLifeStyle extends EditProfileComponent {
	public function submit() {
		$this->request = $this->action->getRequest();
		$now = date("Y-m-d H:i:s");
		$today = CommonUtility::makeTime(date("Y-m-d"));
		$profile_handler = trim($this->request->getParameter('person_handling_profile'));
		$paramArr = array('DIET' => $this->request->getParameter('Diet'), 'SMOKE' => $this->request->getParameter('Smoke'), 'DRINK' => $this->request->getParameter('Drink'), 'COMPLEXION' => $this->request->getParameter('Complexion'), 'BTYPE' => $this->request->getParameter('Body_Type'), 'BLOOD_GROUP' => $this->request->getParameter('blood_group'), 'WEIGHT' => $this->request->getParameter('weight'), 'HIV' => $this->request->getParameter('hiv'), 'HANDICAPPED' => $this->request->getParameter('handicapped'), 'NATURE_HANDICAP' => $this->request->getParameter('nature_of_handicap'), 'THALASSEMIA' => $this->request->getParameter('thalassemia'), 'OWN_HOUSE' => $this->request->getParameter('own_house'), 'HAVE_CAR' => $this->request->getParameter('have_car'), 'OPEN_TO_PET' => $this->request->getParameter('open_to_pet'), 'RES_STATUS' => $this->request->getParameter('rstatus'), 'LAST_LOGIN_DT' => $today, 'MOD_DT' => $now,);
    
    if ($paramArr['HANDICAPPED'] !== '1' && $paramArr['HANDICAPPED'] !== '2') { //bugid 63425 added server side checks
      $paramArr['NATURE_HANDICAP'] = null;
    }
    
    $language_all = HobbyLib::getHobbyLabel('hobbies_language', "", 1);
		$hobbies = $this->loginProfile->getHobbies("onlyValues");
		$allHobbies = $hobbies['HOBBY'];
		$language_arr = $this->request->getParameter('language_arr');
		if ($allHobbies) {
			$hobby_array = explode(",", $allHobbies);
			$hobbies_to_retain = array();
			foreach ($hobby_array as $hobbyValue) if (!array_key_exists($hobbyValue, $language_all)) $hobbies_to_retain[] = $hobbyValue;
			if (count($language_arr) > 0) $updatedHobbies = array_merge($hobbies_to_retain, $language_arr);
			else
				$updatedHobbies=$hobbies_to_retain;
		}
		else{
			if(count($language_arr))
				$updatedHobbies=$language_arr;
		}
		if (count($updatedHobbies) > 0){ 
			$hobbies[HOBBY] = implode(",", $updatedHobbies);
			foreach ($updatedHobbies as $hob_value) $hob_str.= HobbyLib::getHobbyLabel('hobbies', $hob_value) . ",";
		}
		else
			$hobbies[HOBBY]='';
			$this->loginProfile->editHobby($hobbies);
			//Remove comma from last
			if ($hob_str) $hob_str = substr($hob_str, 0, -1);
			$paramArr[KEYWORDS] = $this->getUpdatedKeyword(array("HOBBY" => $hob_str));
		
		$this->updateAndLog($paramArr);
	}
	public function display() {
		$this->action->diet_radio = DropDownCreator::createRadioStringFromField("diet", $this->loginProfile->getDIET(), "Diet", "chbx vam");
		$this->action->smoke_radio = DropDownCreator::createRadioStringFromField("smoke", $this->loginProfile->getSMOKE(), "Smoke", "chbx vam");
		$this->action->drink_radio = DropDownCreator::createRadioStringFromField("drink", $this->loginProfile->getDRINK(), "Drink", "chbx vam");
		$this->action->blood_group_radio = DropDownCreator::createRadioStringFromField("blood_group", $this->loginProfile->getBLOOD_GROUP(), "blood_group", "chbx vam");
		$this->action->hiv_radio = DropDownCreator::createRadioStringFromField("hiv_edit", $this->loginProfile->getHIV(), "hiv", "chbx vam");
		$this->action->body_type_radio = DropDownCreator::createRadioStringFromField("bodytype", $this->loginProfile->getBTYPE(), "Body_Type", "chbx vam");
		$this->action->complexion_radio = DropDownCreator::createRadioStringFromField("complexion", $this->loginProfile->getCOMPLEXION(), "Complexion", "chbx vam");
		$this->action->thalassemia_radio = DropDownCreator::createRadioStringFromField("thalassemia", $this->loginProfile->getTHALASSEMIA(), "thalassemia", "chbx vam");
		$this->action->own_house_radio = DropDownCreator::createRadioStringFromField("own_house", $this->loginProfile->getOWN_HOUSE(), "own_house", "chbx vam");
		$this->action->have_car_radio = DropDownCreator::createRadioStringFromField("have_car", $this->loginProfile->getHAVE_CAR(), "have_car", "chbx vam");
		$this->action->open_to_pet_radio = DropDownCreator::createRadioStringFromField("open_to_pet", $this->loginProfile->getOPEN_TO_PET(), "open_to_pet", "chbx vam");
		$this->action->blood_group_option = DropDownCreator::createDD("blood_group", $this->loginProfile->getBLOOD_GROUP());
		$this->action->handicapped_radio = DropDownCreator::createRadioStringFromField("handicapped", $this->loginProfile->getHANDICAPPED(), "handicapped", "chbx vam", 0, "onclick=\"shown('value');\"");
		$this->action->nature_handicap_option = DropDownCreator::createDD("nature_handicap", $this->loginProfile->getNATURE_HANDICAP());
		$this->action->rstatus_option = DropDownCreator::createDD("rstatus", $this->loginProfile->getRES_STATUS());
		$this->action->WEIGHT = $this->loginProfile->getWEIGHT();
		$this->action->HANDICAPPED = $this->loginProfile->getHANDICAPPED();
		$language_all = HobbyLib::getHobbyLabel('hobbies_language', "", 1);
		$this->action->LANGUAGE = $language_all;
		$hobbies = $this->loginProfile->getHobbies("onlyValues");
		$allHobbies = $hobbies['HOBBY'];
		if ($allHobbies) {
			$hobby_array = explode(",", $allHobbies);
			foreach ($hobby_array as $hobbyValue) if (array_key_exists($hobbyValue, $language_all)) $language_selected[] = $hobbyValue;
			if (count($language_selected) > 1) $language_selected_str = implode($language_selected, "','");
			else $language_selected_str = "'" . $language_selected[0] . "'";
			$this->action->LANGUAGE_str = "'$language_selected_str'";
		}
	}
	public function getTemplateName() {
		return "profile_edit_lifestyle";
	}
	public function getLayerHeading() {
		return "Lifestyle and Attributes";
	}
	public function getOnSubmitJs() {
		return "return checkWeight();";
	}
}
