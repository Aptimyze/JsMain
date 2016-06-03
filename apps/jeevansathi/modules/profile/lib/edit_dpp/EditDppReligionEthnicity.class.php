<?php
class EditDppReligionEthnicity extends EditDppComponent {
	public function display() {
		$this->beforeDisplay($this->action->loginProfile->getPROFILEID());
		$caste = $this->jpartner->getPARTNER_CASTE();
		include_once (sfConfig::get("sf_web_dir") . "/profile/advance_search_functions.php");
		if ($caste != '' && $caste != "'DM'") fill_MSgadget('Religion', $caste, $this->jpartner->getPARTNER_RELIGION(), '1');
		else fill_MSgadget('Religion', $this->jpartner->getPARTNER_RELIGION(), '1', '1');
		$this->action->partner_mtongue_str = $this->jpartner->getPARTNER_MTONGUE();
		$this->action->partner_manglik_str = $this->jpartner->getPARTNER_MANGLIK();
		$this->action->MANGLIK_ARR = FieldMap::getFieldLabel('manglik_label', '', 1);
		$this->action->MTONGUE_ARR = FieldMap::getFieldLabel('community', '', 1);
	}
	protected function getEditedValues() {
		$editedValues["PROFILEID"] = $this->profileid;
		$editedValues["CREATED_BY"] = "ONLINE";
		$editedValues["PARTNER_RELIGION"] = $this->rstr;
		$editedValues["PARTNER_CASTE"] = $this->cstr;
		$editedValues["PARTNER_MANGLIK"] = $this->p_mang;
		$editedValues["PARTNER_MTONGUE"] = $this->mstr;
		$editedValues["ACTED_ON_ID"] = $this->APeditID;
		return $editedValues;
	}
	public function validateInputs()
        {       

                if(ValidationHandler::validateDropdown($this->rstr,"religion"))
                if(ValidationHandler::validateDropdown($this->cstr,"caste"))
                if(ValidationHandler::validateDropdown($this->p_mang,"manglik"))
                if(ValidationHandler::validateDropdown($this->mstr,"community"))
                        return true;
                
                $arr=$this->getEditedValues();
                $data=print_r($arr,true);
                ValidationHandler::getValidationHandler("","Edit page failed $data");
                return false;
        }

	protected function beforeSubmit() {
		$this->partner_religion_arr = $this->action->getPostParameter("partner_religion_arr");
		$this->partner_caste_arr = $this->action->getPostParameter("partner_caste_arr");
		$this->partner_mtongue_arr = $this->action->getPostParameter("partner_mtongue_arr");
		$this->partner_manglik_arr = $this->action->getPostParameter("partner_manglik_arr");
		for ($i = 0;$i < count($this->partner_religion_arr);$i++) {
			$re = explode("|", $this->partner_religion_arr[$i]);
			$re1[] = $re[0];
		}
		if (count($re1) > 0) {
			if (count($re1) == 1) $this->rstr = "'" . $re1[0] . "'";
			else {
				$rstr = implode("','", $re1);
				$this->rstr = "'" . $rstr . "'";
			}
		}
		if ($this->partner_caste_arr) {
			if (is_array($this->partner_caste_arr)) {
				foreach ($this->partner_caste_arr as $key => $value) {
					if ($value) $cstrArr[] = $value;
				}
				if (is_array($cstrArr)) {
					$cstr = implode("','", $cstrArr);
					$this->cstr = "'" . $cstr . "'";
				} else $this->cstr = '';
			} else $this->cstr = '';
		} else $this->cstr = '';
		if ($this->partner_mtongue_arr) {
			$mstr = implode("','", $this->partner_mtongue_arr);
			$this->mstr = "'" . $mstr . "'";
		} else $this->mstr = '';
		if ($this->partner_manglik_arr) {
			$p_mang = implode("','", $this->partner_manglik_arr);
			$this->p_mang = "'" . $p_mang . "'";
		} else $this->p_mang = '';
	}
	public function createUpdateQuery() {
		$scase = "PARTNER_RELIGION=\"$this->rstr\",PARTNER_CASTE=\"$this->cstr\",PARTNER_MTONGUE=\"$this->mstr\",PARTNER_MANGLIK=\"$this->p_mang\"";
		return $scase;
	}
	public function getTemplateName() {
		return "profile_edit_partner_religion";
	}
	public function getLayerHeading() {
		return "Partner's Religion and Ethnicity";
	}
}
