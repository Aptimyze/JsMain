<?php
class EditDppLifeStyle extends EditDppComponent {
	public function display() {
		$this->beforeDisplay($this->action->loginProfile->getPROFILEID());
		$this->action->partner_diet_str = $this->jpartner->getPARTNER_DIET();
		$this->action->partner_smoke_str = $this->jpartner->getPARTNER_SMOKE();
		$this->action->partner_drink_str = $this->jpartner->getPARTNER_DRINK();
		$this->action->partner_complexion_str = $this->jpartner->getPARTNER_COMP();
		$this->action->partner_body_str = $this->jpartner->getPARTNER_BTYPE();
		$this->action->partner_handicapped_str = $this->jpartner->getHANDICAPPED();
		$this->action->partner_nhandicapped_str = $this->jpartner->getNHANDICAPPED();
		$this->action->DIET = FieldMap::getFieldLabel('diet', '', 1);;
		$this->action->SMOKE = FieldMap::getFieldLabel('smoke', '', 1);
		$this->action->DRINK = FieldMap::getFieldLabel('drink', '', 1);
		$this->action->COMPLEXION = FieldMap::getFieldLabel('complexion', '', 1);
		$this->action->BODY_TYPE = FieldMap::getFieldLabel('bodytype', '', 1);
		$this->action->handicap = FieldMap::getFieldLabel('handicapped', '', 1);
		$this->action->nhandicap = FieldMap::getFieldLabel('nature_handicap', '', 1);
	}
	protected function beforeSubmit() {
		$this->partner_diet_arr = $this->action->getPostParameter("partner_diet_arr");
		$this->partner_complexion_arr = $this->action->getPostParameter("partner_complexion_arr");
		$this->partner_body_arr = $this->action->getPostParameter("partner_body_arr");
		$this->partner_handicapped_arr = $this->action->getPostParameter("partner_handicapped_arr");
		$this->partner_nhandicapped_arr = $this->action->getPostParameter("partner_nhandicapped_arr");
		$this->partner_smoke_arr = $this->action->getPostParameter("partner_smoke_arr");
		$this->partner_drink_arr = $this->action->getPostParameter("partner_drink_arr");
		if ($this->partner_diet_arr) {
			$dstr = implode("','", $this->partner_diet_arr);
			$this->dstr = "'" . $dstr . "'";
		} else $this->dstr = '';
		if ($this->partner_complexion_arr) {
			$comstr = implode("','", $this->partner_complexion_arr);
			$this->comstr = "'" . $comstr . "'";
		} else $this->comstr = '';
		if ($this->partner_body_arr) {
			$bstr = implode("','", $this->partner_body_arr);
			$this->bstr = "'" . $bstr . "'";
		} else $this->bstr = '';
		if ($this->partner_handicapped_arr) {
			$han = implode("','", $this->partner_handicapped_arr);
			$this->han = "'" . $han . "'";
		} else $this->han = '';
		if ($this->partner_nhandicapped_arr) {
			$nhan = implode("','", $this->partner_nhandicapped_arr);
			$this->nhan = "'" . $nhan . "'";
		} else $this->nhan = '';
		if ($this->partner_smoke_arr) {
			$smoke = implode("','", $this->partner_smoke_arr);
			$this->smoke = "'" . $smoke . "'";
		} else $this->smoke = '';
		if ($this->partner_drink_arr) {
			$drink = implode("','", $this->partner_drink_arr);
			$this->drink = "'" . $drink . "'";
		} else $this->drink = '';
	}
	protected function createUpdateQuery() {
		$scase = "PARTNER_DIET=\"$this->dstr\",PARTNER_COMP=\"$this->comstr\",PARTNER_BTYPE=\"$this->bstr\",PARTNER_SMOKE=\"$this->smoke\",PARTNER_DRINK=\"$this->drink\",HANDICAPPED=\"$this->han\",NHANDICAPPED=\"$this->nhan\"";
		return $scase;
	}
	protected function getEditedValues() {
		$editedValues["PROFILEID"] = $this->profileid;
		$editedValues["CREATED_BY"] = "ONLINE";
		$editedValues["PARTNER_DIET"] = $this->dstr;
		$editedValues["PARTNER_COMP"] = $this->comstr;
		$editedValues["PARTNER_BTYPE"] = $this->bstr;
		$editedValues["PARTNER_SMOKE"] = $this->smoke;
		$editedValues["PARTNER_DRINK"] = $this->drink;
		$editedValues["HANDICAPPED"] = $this->han;
		$editedValues["NHANDICAPPED"] = $this->nhan;
		$editedValues["ACTED_ON_ID"] = $this->APeditID;
		return $editedValues;
	}
        public function validateInputs()
        {

                if(ValidationHandler::validateDropdown($this->dstr,"diet"))
                if(ValidationHandler::validateDropdown($this->comstr,"complexion"))
                if(ValidationHandler::validateDropdown($this->bstr,"bodytype"))
                if(ValidationHandler::validateDropdown($this->smoke,"smoke"))
                if(ValidationHandler::validateDropdown($this->drink,"drink"))
                if(ValidationHandler::validateDropdown($this->han,"handicapped"))
                if(ValidationHandler::validateDropdown($this->nhan,"nature_handicap"))
                        return true;
                
                $arr=$this->getEditedValues();
                $data=print_r($arr,true);
                ValidationHandler::getValidationHandler("","Edit page failed $data");
                return false;
        }

	public function getTemplateName() {
		return "profile_edit_partner_lifestyle";
	}
	public function getLayerHeading() {
		return "Partner's Lifestyle and Attributes";
	}
}
