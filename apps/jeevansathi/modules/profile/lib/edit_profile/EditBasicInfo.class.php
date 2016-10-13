<?php
class EditBasicInfo extends EditProfileComponent {
	function submit() {
		$UPDATE = 1;
		$this->request = $this->action->getRequest();
		$uname= $this->request->getParameter('username');
		if((preg_match('/^[a-zA-Z\.\,\s\']+$/',$uname))||$uname=="")
		{
			$db= new incentive_NAME_OF_USER;
			$this->action->oldName=$db->getName($this->loginProfile->getPROFILEID());
			$curflag = $this->getScreeningFlag('name');
			if($this->request->getParameter('username'))
			{
				if ($this->request->getParameter('username') != $this->action->oldName)
				{
					$curflag = Flag::removeFlag('name', $curflag);
				}
			} else {
				$curflag = Flag::setFlag('name', $curflag);
				}
			$db->insertName($this->loginProfile->getPROFILEID(),$uname);		
		}
		$now = date("Y-m-d H:i:s");
		$id_type=$this->request->getParameter('id_proof_no')?$this->request->getParameter('id_proof_type'):'';
		$paramArr = array('RELATION' => $this->request->getParameter('Relationship'), 'HEIGHT' => $this->request->getParameter('Height'), 'HAVECHILD' => $this->request->getParameter('Has_Children'), 'CITIZENSHIP' => $this->request->getParameter('Citizenship'), 'ID_PROOF_TYP'=>$id_type,'ID_PROOF_NO'=>strtoupper($this->request->getParameter('id_proof_no')),'MOD_DT' => $now,'SCREENING'=>$curflag);
		$mstatus = $this->loginProfile->getMSTATUS();
		//Make changes only if at least a field was changed
		if ($paramArr[HEIGHT] != $this->loginProfile->getHEIGHT()) $paramArr[KEYWORDS] = $this->getUpdatedKeyword(array("HEIGHT" => FieldMap::getFieldLabel('height', $paramArr[HEIGHT])));
		$this->updateAndLog($paramArr);
		if ($mstatus == MStatus::ANNULLED || $mstatus == MStatus::AWAITING_DIVORCE || $mstatus == MStatus::MARRIED) {
			$date_an = $this->request->getParameter('Year') . '-' . $this->request->getParameter('Month') . '-' . $this->request->getParameter('Day');
			update_annulled_reason($this->loginProfile->getPROFILEID(), $this->request->getParameter('COURT'), $date_an, $this->request->getParameter('REASON'), $mstatus);
		}
	}
	public function display() {
		$nameObj= new NameOfUser;
                $nameData = $nameObj->getNameData($this->loginProfile->getPROFILEID());
                $this->action->NAME = null;
                if(!empty($nameData))
                $this->action->NAME = $nameData[$this->loginProfile->getPROFILEID()]["NAME"];

		$this->action->GENDER = $this->loginProfile->getGENDER();
		$this->action->ID_PROOF_NO = $this->loginProfile->getID_PROOF_NO();
		$dob = explode("-", $this->loginProfile->getDTOFBIRTH());
		$this->action->DTOFB = JsCommon::formatDate($this->loginProfile->getDTOFBIRTH());
		$this->action->RELATION = $this->loginProfile->getRELATION();
		$this->action->HEIGHT = DropDownCreator::createDD("Height", $this->loginProfile->getHEIGHT());
		$mstatus = $this->loginProfile->getMSTATUS();
		$this->action->MSTATUS = $mstatus;
		$this->action->MSTATUS_BI = FieldMap::getFieldLabel("mstatus", $mstatus);
		$this->action->HAVECHILD = $this->loginProfile->getHAVECHILD();
		$this->action->COUNTRY_RES = $this->loginProfile->getCOUNTRY_RES();
		$this->action->CITIZEN_RES = DropDownCreator::createDD("Country_Residence", $this->loginProfile->getCITIZENSHIP());
		$this->action->ID_TYPE_RADIO=DropDownCreator::createRadioStringFromField("id_proof_typ",$this->loginProfile->getID_PROOF_TYP(),"id_proof_type","chbx vam",3);
		if ($mstatus == MStatus::ANNULLED || $mstatus == MStatus::AWAITING_DIVORCE || $mstatus == MStatus::MARRIED) {
			$annulledDetails = get_annulled_details($this->loginProfile->getPROFILEID());
			if ($annulledDetails) {
				$this->action->anul_entry = 1;
				$REASON = $annulledDetails["REASON"];
				$this->action->REASON = $REASON;
				if ($mstatus == MStatus::ANNULLED) {
					$COURT = $annulledDetails["COURT"];
					$ad = explode("-", $annulledDetails["DATE"]);
					$ANUL_DATE = $ad[2] . "/" . $ad[1] . "/" . $ad[0];
					$this->action->COURT = $COURT;
					$this->action->ANUL_DATE = $ANUL_DATE;
					$this->action->year = $ad[0];
					$this->action->years=DropDownCreator::createDD("year",$ad[0]);
					$this->action->ANUL_MON = $ad[1];
					$this->action->day = $ad[2];
				} else {
					$REASON_MSG = substr($REASON, 0, 56) . "...";
					$this->action->REASON_MSG = $REASON_MSG;
				}
			}
		}
	}
	public function getTemplateName() {
		return "profile_edit_basicinfo";
	}
	public function getOnSubmitJs() {
		        return "return validate(1);";
				    }

	public function getLayerHeading() {
		return "Basic Information";
	}
}
