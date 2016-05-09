<?php
class EditDppFilter extends EditDppComponent {
	private $case;
	public function display() {
		$this->beforeDisplay($this->action->loginProfile->getPROFILEID());
		switch ($this->action->filter) {
			case "age":
				$this->action->MIN_AGE = $this->jpartner->getLAGE();
				$this->action->MAX_AGE = $this->jpartner->getHAGE();
			break;
			case "country":
			case "city":
				include_once (sfConfig::get("sf_web_dir") . "/profile/advance_search_functions.php");
				fill_MSgadget("Country_Residence", $this->jpartner->getPARTNER_COUNTRYRES(), '', 1);
				$this->action->checked_city = $this->jpartner->getPARTNER_CITYRES();
			break;
			case "mstatus":
				$this->action->partner_mstatus_str = $this->jpartner->getPARTNER_MSTATUS();
				$this->action->MSTATUS = FieldMap::getFieldLabel('mstatus', '', 1);
				$this->action->partner_children = $this->jpartner->getCHILDREN();
			break;
			case "religion":
			case "caste":
				$caste = $this->jpartner->getPARTNER_CASTE();
				include_once (sfConfig::get("sf_web_dir") . "/profile/advance_search_functions.php");
				if ($caste != '' && $caste != "'DM'") fill_MSgadget('Religion', $caste, $this->jpartner->getPARTNER_RELIGION(), '1');
				else fill_MSgadget('Religion', $this->jpartner->getPARTNER_RELIGION(), '1', '1');
				$this->action->MTONGUE_ARR = FieldMap::getFieldLabel('community', '', 1);
				break;
			case "community":
				$this->action->MTONGUE_ARR = FieldMap::getFieldLabel('community', '', 1);
				$this->action->partner_mtongue_str = $this->jpartner->getPARTNER_MTONGUE();
				break;
			case "income":
				populateIncomeDropDowns();
				$this->action->rsLIncome = $this->jpartner->getLINCOME();
				$this->action->rsHIncome = $this->jpartner->getHINCOME();
				$this->action->doHIncome = $this->jpartner->getHINCOME_DOL();
				$this->action->doLIncome = $this->jpartner->getLINCOME_DOL();
				$this->action->partner_lincome = $this->jpartner->getLINCOME();
				$this->action->partner_hincome = $this->jpartner->getHINCOME();
				$this->action->partner_lincome_dol = $this->jpartner->getLINCOME_DOL();
				$this->action->partner_hincome_dol = $this->jpartner->getHINCOME_DOL();
				break;
			}
		}
		protected function beforeSubmit() {
			switch ($this->action->filter) {
				case "age":
					$this->Min_Age = $this->action->getPostParameter("Min_Age");
					$this->Max_Age = $this->action->getPostParameter("Max_Age");
				break;
				case "mstatus":
					$this->partner_mstatus_arr = $this->action->getPostParameter("partner_mstatus_arr");
					$this->partner_children = $this->action->getPostParameter("partner_children");
				break;
				case "country":
				case "city":
					$this->partner_country_arr = $this->action->getPostParameter("partner_country_arr");
					$this->partner_city_arr = $this->action->getPostParameter("partner_city_arr");
				break;
				case "religion":
				case "caste":
					$this->partner_religion_arr = $this->action->getPostParameter("partner_religion_arr");
					$this->partner_caste_arr = $this->action->getPostParameter("partner_caste_arr");
				break;
				case "community":
					$this->partner_mtongue_arr = $this->action->getPostParameter("partner_mtongue_arr");
				break;
				case "income":
					$this->rsHIncome = $this->action->getPostParameter("rsHIncome");
					$this->rsLIncome = $this->action->getPostParameter("rsLIncome");
					$this->doHIncome = $this->action->getPostParameter("doHIncome");
					$this->doLIncome = $this->action->getPostParameter("doLIncome");
					if($this->rsHIncome||$this->rsLIncome)
					{
						$rArr["minIR"] = $this->rsLIncome;
						$rArr["maxIR"] = $this->rsHIncome;
					}
					if($this->doHIncome||$this->doLIncome)
					{
						$dArr["minID"] = $this->doLIncome;
						$dArr["maxID"] = $this->doHIncome;
					}
					
					$incomeMapObj = new IncomeMapping($rArr,$dArr);
					$incomeMapArr = $incomeMapObj->incomeMapping();
					foreach ($incomeMapArr as $var => $value) $this->$var = $value;
					break;
				}
		}
		public function getEditedValues() {
			$this->createUpdateQuery();
			$ColumnArray["PROFILEID"] = $this->profileid;
			$ColumnArray["CREATED_BY"] = "ONLINE";
			switch ($this->action->filter) {
				case "age":
			$ColumnArray["LAGE"] = $this->Min_Age;
			$ColumnArray["HAGE"] = $this->Max_Age;
				break;
				case "mstatus":
			$ColumnArray["CHILDREN"] = $this->partner_children;
			$ColumnArray["PARTNER_MSTATUS"] = $this->mstatus;
				break;
				case "country":
				case "city":
			$ColumnArray["PARTNER_CITYRES"] = $this->cistr;
			$ColumnArray["PARTNER_COUNTRYRES"] = $this->costr;
				break;
				case "religion":
				case "caste":
			$ColumnArray["PARTNER_RELIGION"] = $this->rstr;
			$ColumnArray["PARTNER_CASTE"] = $this->cstr;
				break;
				case "community":
			$ColumnArray["PARTNER_MTONGUE"] = $this->mstr;
				break;
				case "income":
			$ColumnArray["LINCOME"] = $this->rsLIncome;
			$ColumnArray["HINCOME"] = $this->rsHIncome;
			$ColumnArray["LINCOME_DOL"] = $this->doLIncome;
			$ColumnArray["HINCOME_DOL"] = $this->doHIncome;
			$ColumnArray["PARTNER_INCOME"] = $this->istr;
				break;
			}
			$ColumnArray["ACTED_ON_ID"] = $this->action->request->getParameter('APeditID');
			return $ColumnArray;
		}
		public function validateInputs()
        	{

								return true;
        	}
		public function createUpdateQuery() {
			if ($this->action->filter == 'age') $scase = "LAGE='$this->Min_Age',HAGE='$this->Max_Age'";
			elseif ($this->action->filter == 'country' || $this->action->filter == 'city') {
				if (count($this->partner_country_arr) > 0) {
					for ($i = 0;$i < count($this->partner_country_arr);$i++) {
						$co = explode("#", $this->partner_country_arr[$i]);
						$costr1[] = $co[0];
					}
					$this->costr = implode("','", $costr1);
					$this->costr = "'" . $this->costr . "'";
				}
				if (count($this->partner_city_arr) > 0) {
					$this->cistr = implode("','", $this->partner_city_arr);
					$this->cistr = "'" . $this->cistr . "'";
				}
				$scase = "PARTNER_COUNTRYRES=\"$this->costr\",PARTNER_CITYRES=\"$this->cistr\"";
			} elseif ($this->action->filter == 'religion' || $this->action->filter == 'caste') {
				for ($i = 0;$i < count($this->partner_religion_arr);$i++) {
					$re = explode("|", $this->partner_religion_arr[$i]);
					$re1[] = $re[0];
				}
				if (count($re1) > 0) {
					if (count($re1) == 1) $this->rstr = "'" . $re1[0] . "'";
					else {
						$this->rstr = implode("','", $re1);
						$this->rstr = "'" . $this->rstr . "'";
					}
				}
				if ($this->partner_caste_arr) {
					$this->cstr = implode("','", $this->partner_caste_arr);
					$this->cstr = "'" . $this->cstr . "'";
				} else $this->cstr = '';
				$scase = "PARTNER_RELIGION=\"$this->rstr\",PARTNER_CASTE=\"$this->cstr\"";
			} elseif ($this->action->filter == 'community') {
				if ($this->partner_mtongue_arr) {
					$this->mstr = implode("','", $this->partner_mtongue_arr);
					$this->mstr = "'" . $this->mstr . "'";
				} else $this->mstr = '';
				$scase = "PARTNER_MTONGUE=\"$this->mstr\"";
			} elseif ($this->action->filter == 'mstatus') {
				if (count($this->partner_mstatus_arr) > 0) {
					$this->mstatus = implode("','", $this->partner_mstatus_arr);
					$this->mstatus = "'" . $this->mstatus . "'";
				}
				$scase = "PARTNER_MSTATUS=\"$this->mstatus\"";
			} elseif ($this->action->filter == 'income') {
				$scase = "LINCOME=\"$this->rsLIncome\",HINCOME=\"$this->rsHIncome\",LINCOME_DOL=\"$this->doLIncome\",HINCOME_DOL=\"$this->doHIncome\",PARTNER_INCOME=\"$this->istr\"";
			}
			return $scase;
		}
		public function getTemplateName() {
			return "edit_dpp_from_filter";
		}
		public function getLayerHeading() {
			return "Preferred partner's details";
		}
		public function getOnSubmitJs() {
			if ($this->action->filter == "age") return "return validate();";
			elseif ($this->action->filter == "income") return "return validate_income();";
			else return "";
		}
	}
	
