<?php
class EditFamilyDetails extends EditProfileComponent {
	public function submit() {
		$this->request = $this->action->getRequest();
		$now = date("Y-m-d H:i:s");
		$today = CommonUtility::makeTime(date("Y-m-d"));
		$profile_handler = trim($this->request->getParameter('person_handling_profile'));
		$curflag = $this->getScreeningFlag(array('PROFILE_HANDLER_NAME' => $profile_handler));
		$paramArr = array('MOTHER_OCC' => $this->request->getParameter('mother_occ'), 'T_BROTHER' => $this->request->getParameter('tbrother'), 'M_BROTHER' => $this->request->getParameter('mbrother'), 'T_SISTER' => $this->request->getParameter('tsister'), 'M_SISTER' => $this->request->getParameter('msister'), 'FAMILY_TYPE' => $this->request->getParameter('ftype'), 'FAMILY_STATUS' => $this->request->getParameter('fstatus'), 'FAMILY_VALUES' => $this->request->getParameter('Family_Values'), 'FAMILY_BACK' => $this->request->getParameter('Family_Back'), 'FAMILY_INCOME' => $this->request->getParameter('Family_Income'), 'PARENT_CITY_SAME' => $this->request->getParameter('Parent_City_Same'), 'PROFILE_HANDLER_NAME' => $profile_handler, 'SCREENING' => $curflag, 'LAST_LOGIN_DT' => $today, 'MOD_DT' => $now,);
		$this->updateAndLog($paramArr);
	}
	public function display() {
		$this->action->FAMILY_BACK = DropDownCreator::createDD("family_background", $this->loginProfile->getFAMILY_BACK());
		$this->action->MOTHER_OCC = DropDownCreator::createDD("mother_occupation", $this->loginProfile->getMOTHER_OCC());
		$this->action->FATHER_OCC = $this->loginProfile->getFAMILY_BACK();
		$this->action->family_values_radio = DropDownCreator::createRadioStringFromField("family_values", $this->loginProfile->getFAMILY_VALUES(), "Family_Values", "chbx vam");
		$this->action->family_type_radio = DropDownCreator::createRadioStringFromField("family_type", $this->loginProfile->getFAMILY_TYPE(), "ftype", "chbx vam");
		$this->action->family_status_radio = DropDownCreator::createRadioStringFromField("family_status", $this->loginProfile->getFAMILY_STATUS(), "fstatus", "chbx vam");
		$this->action->family_income_option = DropDownCreator::createDD("income_level", $this->loginProfile->getFAMILY_INCOME());
		$this->action->parent_city_radio = DropDownCreator::createRadioStringFromField("live_with_parents", $this->loginProfile->getPARENT_CITY_SAME(), "Parent_City_Same", "chbx vam");
		$this->action->TBROTHERS = DropDownCreator::createDD("sibling", $this->loginProfile->getT_BROTHER());
		$this->action->TSISTERS = DropDownCreator::createDD("sibling", $this->loginProfile->getT_SISTER());
		$this->action->MBROTHERS = DropDownCreator::createDD("sibling", $this->loginProfile->getM_BROTHER());
		$this->action->MSISTERS = DropDownCreator::createDD("sibling", $this->loginProfile->getM_SISTER());
		$this->action->person_handling_profile = $this->loginProfile->getPROFILE_HANDLER_NAME();
	}
	public function getOnSubmitJs() {
		return "return validate();";
	}
	public function getTemplateName() {
		return "profile_edit_family";
	}
	public function getLayerHeading() {
		return "Family Details";
	}
}
