<?php
class EditDppAbtPartner extends EditDppComponent {
	private $case;
	public function display() {
		$this->action->GENDER = $this->action->loginProfile->getGENDER();
		$this->action->RELATION = $this->action->loginProfile->getRELATION();
		$this->action->SPOUSE = $this->action->loginProfile->getSPOUSE();
	}
	protected function beforeSubmit() {
		//submit action is defined in edit_profile/EditProfileProfileInfo
		
	}
	public function getEditedValues() {
		return array();
	}
	public function createUpdateQuery() {
		return "";
	}
	public function getTemplateName() {
		return "profile_edit_more_aboutdp";
	}
	public function getLayerHeading() {
		return "About Desired Partner Profile";
	}
	public function getOnSubmitJs() {
		return "";
	}
}
?>
