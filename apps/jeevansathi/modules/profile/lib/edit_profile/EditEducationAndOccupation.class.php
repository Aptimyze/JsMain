<?php
class EditEducationAndOccupation extends EditProfileComponent {
	public function submit() {
		$this->request = $this->action->getRequest();
		$now = date("Y-m-d H:i:s");
		$Educ_Qualification = trim($this->request->getParameter('Educ_Qualification'));
		$Job_Info = trim($this->request->getParameter('Job_Info'));
		$company_name = trim($this->request->getParameter('organisation'));
		$cur_flag = $this->getScreeningFlag(array("EDUCATION" => $Educ_Qualification, "JOB_INFO" => $Job_Info, "COMPANY_NAME" => $company_name));
		$educationAndOcc = array("SCHOOL" => trim($this->request->getParameter('school_name')), "COLLEGE" => trim($this->request->getParameter('college_name')), "OTHER_UG_DEGREE" => trim($this->request->getParameter('other_ug_degree')), "OTHER_PG_DEGREE" => trim($this->request->getParameter('other_pg_degree')), "PG_COLLEGE" => trim($this->request->getParameter('pg_college')), 'PG_DEGREE' => $this->request->getParameter('pg_degree'), 'UG_DEGREE' => $this->request->getParameter('Grad_Degree'),);
		$educationAndOcc_orig = $this->loginProfile->getEducationDetail(1);
		foreach (array('SCHOOL', 'COLLEGE', 'OTHER_UG_DEGREE', 'OTHER_PG_DEGREE', 'PG_COLLEGE') as $field) {
			if ($educationAndOcc[$field]) {
				if ($educationAndOcc[$field] != $educationAndOcc_orig[$field]) $cur_flag = Flag::removeFlag($field, $cur_flag);
			} else $cur_flag = Flag::setFlag($field, $cur_flag);
		}
		$Education_Level = $this->request->getParameter('Education_Level');
		if ($Education_Level) $edu_level = get_old_value($Education_Level, "EDUCATION_LEVEL_NEW");
		$have_jeducation = $this->loginProfile->getHAVE_JEDUCATION();
		$toChangeJProfileEdu = false;
		foreach ($educationAndOcc as $field => $value) {
			$value=trim($value);
			if ($have_jeducation == 'N' && $value) {
				$have_jeducation = 'Y';
				if(!$toChangeJProfileEdu)
					$toChangeJProfileEdu = true;
			}
			if ($value != $educationAndOcc_orig[$field]&& !($value === "" && $educationAndOcc_orig[$field] === "0")) {
				if(!$toChangeJProfileEdu)
					$toChangeJProfileEdu = true;
				$this->changed_fields[]=$field;
			}
		}
		$paramArr = array("EDU_LEVEL_NEW" => $Education_Level, "EDU_LEVEL" => $edu_level, "EDUCATION" => $Educ_Qualification, "OCCUPATION" => $this->request->getParameter('Occupation'), "INCOME" => $this->request->getParameter('Income'), "MARRIED_WORKING" => $this->request->getParameter('Married_Working'), "JOB_INFO" => $Job_Info, "SCREENING" => $cur_flag, "WORK_STATUS" => $this->request->getParameter('work_status'), "GOING_ABROAD" => $this->request->getParameter('settling_abroad'), "MOD_DT" => $now, "HAVE_JEDUCATION" => $have_jeducation, "COMPANY_NAME" => $company_name,);
		if (!$toChangeJProfileEdu) $toChange = $this->checkForChange($paramArr);
		$mark = $this->request->getParameter('mark');
		//To check for incompleteness of profile.
		$incompleteFields = IncompleteLib::incompleteFieldsOfProfile($this->loginProfile);
		$fieldsTocheckForIncomplete = array("EDU_LEVEL_NEW", "OCCUPATION", "INCOME");
		if (!$mark) {
			if (count(array_intersect($fieldsTocheckForIncomplete, $incompleteFields))) {
				$mark = 2;
			}
		}
		//If coming directly after login
		if ($mark) {
			if (!count(array_diff($incompleteFields,$fieldsTocheckForIncomplete)) && $paramArr[EDU_LEVEL_NEW] && $paramArr[OCCUPATION] && $paramArr[INCOME]) 
			{
				$paramArr[INCOMPLETE] = 'N';
				$paramArr[ENTRY_DT] = $now;
				if($this->request->getParameter('IncompleteMail'))
					$paramArr[SEC_SOURCE]='I';
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
			} 
			$this->after_login = 0;
		}else $this->after_login = $mark;
		//keyword to be updated
		if ($toChange || $toChangeJProfileEdu) {
			//update keywords if occupation changed
			if ($paramArr[OCCUPATION] != $this->loginProfile->getOCCUPATION()) $paramArr[KEYWORDS] = $this->getUpdatedKeyword(array("OCCUPATION" => FieldMap::getFieldLabel('occupation', $paramArr[OCCUPATION])));
			$this->updateAndLog($paramArr, $educationAndOcc);
			if ($toChangeJProfileEdu) $this->loginProfile->editEducation($educationAndOcc);
		}
		if (!headers_sent()) update_cookie($paramArr[INCOME], "INCOME");
	}
	public function display() {
		$request=$this->action->getRequest();
		$this->action->from_work_link=$request->getParameter("from_work_link");
		$this->action->from_edu_link=$request->getParameter("from_edu_link");
		$this->educationDetails = $this->loginProfile->getEducationDetail(1);
		$this->action->RELATION = $this->loginProfile->getRELATION();
		$this->action->GENDER = $this->loginProfile->getGENDER();
		$this->action->education_level = DropDownCreator::createDD("education", $this->loginProfile->getEDU_LEVEL_NEW());
		$this->action->occupation = DropDownCreator::createDD("occupation", $this->loginProfile->getOCCUPATION());
		$this->action->INCOME = DropDownCreator::createDD("income_level", $this->loginProfile->getINCOME());
		$this->action->degree_ug = DropDownCreator::createDD("degree_ug", $this->educationDetails[UG_DEGREE]);
		$this->action->degree_pg = DropDownCreator::createDD("degree_pg", $this->educationDetails[PG_DEGREE]);
		$this->action->work_status_opt = DropDownCreator::createDD("work_status", $this->loginProfile->getWORK_STATUS());
		$this->action->going_abroad_radio = DropDownCreator::createRadioStringFromField("going_abroad", $this->loginProfile->getGOING_ABROAD(), "settling_abroad", "chbx vam");
		$this->action->work_after_marriage_radio = DropDownCreator::createRadioStringFromField("working_marriage", $this->loginProfile->getMARRIED_WORKING(), "Married_Working", "chbx vam");
		$this->action->college = $this->educationDetails[COLLEGE];
		$this->action->pg_college = $this->educationDetails[PG_COLLEGE];
		$this->action->other_pg_degree = $this->educationDetails[OTHER_PG_DEGREE];
		$this->action->other_ug_degree = $this->educationDetails[OTHER_UG_DEGREE];
		$this->action->school = $this->educationDetails[SCHOOL];
		$this->action->edu_level_new = $this->loginProfile->getEDU_LEVEL_NEW();
		$this->action->EDUCATION = $this->loginProfile->getEDUCATION();
		$this->action->JOBINFO = $this->loginProfile->getJOB_INFO();
		$this->action->occ_val = $this->loginProfile->getOCCUPATION();
		$this->action->income_val = $this->loginProfile->getINCOME();
		$this->action->WORK_STATUS = $this->loginProfile->getWORK_STATUS();
		$this->action->MARRIED_WORKING = $this->loginProfile->getMARRIED_WORKING();
		$this->action->organisation = $this->loginProfile->getCOMPANY_NAME();
	}
	public function getTemplateName() {
		return "profile_edit_myedu_occ";
	}
	public function getLayerHeading() {
		$gender = $this->loginProfile->getGENDER();
		if ($gender == 'M') return "His Education and Occupation";
		else if ($gender == 'F') return "Her Education and Occupation";
	}
}
