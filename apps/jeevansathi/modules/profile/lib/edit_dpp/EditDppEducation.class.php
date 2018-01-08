<?php
class EditDppEducation extends EditDppComponent {
	public function display() {
		populateIncomeDropDowns();
		$this->beforeDisplay($this->action->loginProfile->getPROFILEID());
		$this->action->partner_occupation_str = $this->jpartner->getPARTNER_OCC();
		$this->action->partner_education_str = $this->jpartner->getPARTNER_ELEVEL_NEW();
		$this->action->rsLIncome = $this->jpartner->getLINCOME();
		$this->action->rsHIncome = $this->jpartner->getHINCOME();
		$this->action->doHIncome = $this->jpartner->getHINCOME_DOL();
		$this->action->doLIncome = $this->jpartner->getLINCOME_DOL();
		$this->action->OCC_ARR = FieldMap::getFieldLabel('occupation', '', 1);
		$this->action->EDU_ARR = FieldMap::getFieldLabel('eduDppArray', '', 1);
		$this->action->partner_lincome = $this->jpartner->getLINCOME();
		$this->action->partner_hincome = $this->jpartner->getHINCOME();
		$this->action->partner_lincome_dol = $this->jpartner->getLINCOME_DOL();
		$this->action->partner_hincome_dol = $this->jpartner->getHINCOME_DOL();
		$this->educationLevelNew();
	}
	protected function beforeSubmit() {
		$this->partner_occupation_arr = $this->action->getPostParameter("partner_occupation_arr");
		$this->rsHIncome = $this->action->getPostParameter("rsHIncome");
		$this->rsLIncome = $this->action->getPostParameter("rsLIncome");
		$this->doHIncome = $this->action->getPostParameter("doHIncome");
		$this->doLIncome = $this->action->getPostParameter("doLIncome");
		$this->partner_education_arr = $this->action->getPostParameter("partner_education_arr");
		$this->APeditID = $this->action->getPostParameter("APeditID");
		if ($this->partner_occupation_arr) {
			$ostr = implode("','", $this->partner_occupation_arr);
			$this->ostr = "'" . $ostr . "'";
		} else $this->ostr = '';
		if(strlen($this->rsHIncome)||strlen($this->rsLIncome))
		{
			$rArr["minIR"] = $this->rsLIncome;
			$rArr["maxIR"] = $this->rsHIncome;
		}
		if(strlen($this->doHIncome)||strlen($this->doLIncome))
		{
			$dArr["minID"] = $this->doLIncome;
			$dArr["maxID"] = $this->doHIncome;
		}
		$incomeMapObj = new IncomeMapping($rArr,$dArr);
		$incomeMapArr = $incomeMapObj->incomeMapping();

		foreach ($incomeMapArr as $var => $value) $this->$var = $value;
		if ($this->partner_education_arr){
			foreach($this->partner_education_arr as $K=>$v)
			{
				if(strpos($v,"#")==false){
				$this->estr.= "'" . $v . "',";
				}
			}
			$this->estr=trim($this->estr,",");
		} else $this->estr = '';		
	}
	protected function createUpdateQuery() {
		$scase = "PARTNER_OCC=\"$this->ostr\",LINCOME=\"$this->rsLIncome\",HINCOME=\"$this->rsHIncome\",LINCOME_DOL=\"$this->doLIncome\",HINCOME_DOL=\"$this->doHIncome\",PARTNER_INCOME=\"$this->istr\",PARTNER_ELEVEL_NEW=\"$this->estr\"";
		return $scase;
	}
	protected function getEditedValues() {
		$editedValue["PROFILEID"] = $this->profileid;
		$editedValue["CREATED_BY"] = "ONLINE";
		$editedValue["PARTNER_OCC"] = $this->ostr;
		$editedValue["PARTNER_INCOME"] = $this->istr;
		$editedValue["LINCOME"] = $this->rsLIncome;
		$editedValue["HINCOME"] = $this->rsHIncome;
		$editedValue["LINCOME_DOL"] = $this->doLIncome;
		$editedValue["HINCOME_DOL"] = $this->doHIncome;
		$editedValue["PARTNER_ELEVEL_NEW"] = $this->Education_Level_New;
		$editedValue["ACTED_ON_ID"] = $this->APeditID;
		return $editedValue;
	}
	public function validateInputs()
        {

                if(ValidationHandler::validateDropdown($this->ostr,"occupation"))
                if(ValidationHandler::validateDropdown($this->istr,"income_level"))
                if(ValidationHandler::validateDropdown($this->rsLIncome,"lincome"))
                if(ValidationHandler::validateDropdown($this->rsHIncome,"hincome"))
                if(ValidationHandler::validateDropdown($this->doLIncome,"lincome_dol"))
                if(ValidationHandler::validateDropdown($this->doHIncome,"hincome_dol"))
                if(ValidationHandler::validateDropdown($this->Education_Level_New,"education"))
                        return true;

                $arr=$this->getEditedValues();
                $data=print_r($arr,true);
                ValidationHandler::getValidationHandler("","Edit page failed $data");
                return false;
        }
	public function getTemplateName() {
		return "profile_edit_partner_edu";
	}
	public function getLayerHeading() {
		return "Partner's Education and Occupation";
	}
	public function getFormAction() {
		return "FLAG=partner&fsubmit=1&val_flag=PPEO&checksum=~\$CHECKSUM`&profilechecksum=~\$profilechecksum`&gli=~\$gli`&~\$matchPointString`&~\$APonlineString`&frommatchalert=~\$frommatchalert`";
	}
	public function getOnSubmitJs() {
		return "return validate_income();";
	}
	public function educationLevelNew() {
		$templateArr=CommonFunction::educationLevelNewMapping($this->action->EDU_ARR);
			$this->action->finalHiddenStr=$templateArr["hiddenStr"];
			$this->action->finalShownStr=$templateArr["shownStr"];
	}
}
?>
