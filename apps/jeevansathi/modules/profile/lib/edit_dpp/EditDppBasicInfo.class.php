<?php
class EditDppBasicInfo extends EditDppComponent {
	private $case;
	public function display() {
		$this->beforeDisplay($this->action->loginProfile->getPROFILEID());
		$this->action->MIN_AGE = $this->jpartner->getLAGE();
		$this->action->MAX_AGE = $this->jpartner->getHAGE();
		include_once (sfConfig::get("sf_web_dir") . "/profile/connect_db.php");
		$this->action->maxheight = create_dd($this->jpartner->getHHEIGHT(), "Height", 1);
		$this->action->minheight = create_dd($this->jpartner->getLHEIGHT(), "Height");
		$this->action->partner_mstatus_str = $this->jpartner->getPARTNER_MSTATUS();
		include_once (sfConfig::get("sf_web_dir") . "/profile/advance_search_functions.php");
		fill_MSgadget("Country_ResidenceState", $this->jpartner->getPARTNER_COUNTRYRES(), '', 1);
		$this->action->MSTATUS = FieldMap::getFieldLabel('mstatus', '', 1);
		//Trac 482. Give Married option only to muslinm women
		if (!($this->action->loginProfile->getRELIGION() == 2 && $this->action->loginProfile->getGENDER() == 'F')) unset($this->action->MSTATUS[M]);
                $checkedCityStr = '';
                if($this->jpartner->getPARTNER_CITYRES() && $this->jpartner->getState())
                    $checkedCityStr = $this->jpartner->getPARTNER_CITYRES().",".$this->jpartner->getState();
                else if($this->jpartner->getPARTNER_CITYRES())
                    $checkedCityStr = $this->jpartner->getPARTNER_CITYRES();
                else if($this->jpartner->getState())
                    $checkedCityStr = $this->jpartner->getState();
		$this->action->checked_city = $checkedCityStr;
		$this->action->partner_children = $this->jpartner->getCHILDREN();
		$this->action->setTemplate("profile_edit_partner_basic");
	}
	protected function beforeSubmit() {
		$this->partner_mstatus_arr = $this->action->getPostParameter("partner_mstatus_arr");
		$this->partner_country_arr = $this->action->getPostParameter("partner_country_arr");
		$this->partner_city_arr = $this->action->getPostParameter("partner_city_arr");
		$this->Min_Age = $this->action->getPostParameter("Min_Age");
		$this->Max_Age = $this->action->getPostParameter("Max_Age");
		$this->Min_Height = $this->action->getPostParameter("Min_Height");
		$this->Max_Height = $this->action->getPostParameter("Max_Height");
		$this->partner_children = $this->action->getPostParameter("partner_children");
		if ($this->partner_mstatus_arr&&count($this->partner_mstatus_arr) > 0) {
			$mstr = implode("','", $this->partner_mstatus_arr);
			$this->mstr = "'" . $mstr . "'";
		}
		if ($this->partner_country_arr&&count($this->partner_country_arr) > 0) {
			for ($i = 0;$i < count($this->partner_country_arr);$i++) {
				$co = explode("#", $this->partner_country_arr[$i]);
				$costr1[] = $co[0];
			}
			$costr = implode("','", $costr1);
			$this->costr = "'" . $costr . "'";
		}
		else
			$this->partner_city_arr=array();//if no country is selected then partner's city to be cleared also
		if ($this->partner_city_arr && count($this->partner_city_arr) > 0) {
			
			//For NCR and MNCR
			$key=array_search("NCR",$this->partner_city_arr);
			if(!($key===false))
				unset($this->partner_city_arr[$key]);
			$key1=array_search("MNCR",$this->partner_city_arr);
                        if(!($key1===false))
                                unset($this->partner_city_arr[$key1]);

			$cistr = implode(",", $this->partner_city_arr);
                        $cityStateArr = explode(",",$cistr);
                        $stateIndiaArr = FieldMap::getFieldLabel("state_india",'',1);
                        foreach($cityStateArr as $k=>$v)
                        {
                                if(array_key_exists($v, $stateIndiaArr))
                                {
                                        $stateArr[] =$v;
                                }
                                else
                                {
                                        $cityArr[]= $v;
                                }

                        }
                        if(is_array($cityArr))
                        foreach($cityArr as $key=>$value)
                        {	
                                if(!in_array(substr($value,0,2),$stateArr))
                                {
                                        $cityString .= $value."','";
                                }
                        }
                        if($cityString)
                            $this->cistr = "'".rtrim($cityString,"','")."'";
                        if($stateArr)
                            $this->state = "'".implode("','",$stateArr)."'";
		}
	}
	public function getEditedValues() {
		$ColumnArray["PROFILEID"] = $this->profileid;
		$ColumnArray["CREATED_BY"] = "ONLINE";
		$ColumnArray["CHILDREN"] = $this->partner_children;
		$ColumnArray["LAGE"] = $this->Min_Age;
		$ColumnArray["HAGE"] = $this->Max_Age;
		$ColumnArray["LHEIGHT"] = $this->Min_Height;
		$ColumnArray["HHEIGHT"] = $this->Max_Height;
		$ColumnArray["PARTNER_CITYRES"] = $this->cistr;
		$ColumnArray["PARTNER_COUNTRYRES"] = $this->costr;
		$ColumnArray["PARTNER_MSTATUS"] = $this->mstr;
		$ColumnArray["ACTED_ON_ID"] = $this->APeditID;
                $ColumnArray["STATE"] = $this->state;
		return $ColumnArray;
	}
	public function createUpdateQuery() {
		$scase = "LAGE='$this->Min_Age',HAGE='$this->Max_Age',LHEIGHT='$this->Min_Height',HHEIGHT='$this->Max_Height',PARTNER_MSTATUS=\"$this->mstr\",PARTNER_COUNTRYRES=\"$this->costr\",   PARTNER_CITYRES=\"$this->cistr\",STATE=\"$this->state\",CHILDREN='$this->partner_children'";
		return $scase;
	}
	public function getTemplateName() {
		return "profile_edit_partner_basic";
	}
	public function getLayerHeading() {
		return "Partner's Basic Info";
	}
	public function getOnSubmitJs() {
		return "return validate();";
	}
	public function validateInputs()
	{
            
		if(ValidationHandler::validateAge($this->Min_Age))
		if(ValidationHandler::validateAge($this->Max_Age))
		if(ValidationHandler::validateDropdown($this->Max_Height,"height"))
		if(ValidationHandler::validateDropdown($this->Min_Height,"height"))
		if(ValidationHandler::validateDropdown($this->cistr,"city"))
                if(ValidationHandler::validateDropdown($this->state,"state_india"))  
		if(ValidationHandler::validateDropdown($this->costr,"country"))
		if(ValidationHandler::validateDropdown($this->mstr,"mstatus"))
			return true;
		
		$arr=$this->getEditedValues();
		$data=print_r($arr,true);
		ValidationHandler::getValidationHandler("","Edit page failed $data");
		return false;
	}
}
?>
