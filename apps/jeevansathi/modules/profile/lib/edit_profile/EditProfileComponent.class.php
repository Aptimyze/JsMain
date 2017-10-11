<?php
abstract class EditProfileComponent implements EditComponent {
	protected $loginProfile;
	protected $action;
	protected $changed_fields;
	public function updateCommon() {
		  SendMail::send_email('esha.jain@jeevansathi.com'," in updatecommon", "edit update comon", "editcommon@jeevansathi.com");
	}
	public function after_submit() {
        ProfileCommon::updateProfileCompletionScore($this->loginProfile->getPROFILEID());
		include_once (sfConfig::get("sf_web_dir") . "/ivr/jsivrFunctions.php");
		$after_login = $this->after_login;
		$request=sfContext::getInstance()->getRequest();
		$from_whr=$request->getParameter('from_where');
		$phone_status = getPhoneStatus(array("PHONE_FLAG" => $this->loginProfile->getPHONE_FLAG(), "LANDL_STATUS" => $this->loginProfile->getLANDL_STATUS(), "MOB_STATUS" => $this->loginProfile->getMOB_STATUS()), $this->loginProfile->getPROFILEID());
		if ($this->check_dup_email) echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/profile/viewprofile.php?ownview=1&EditWhatNew=EmailDup&DupEmail=$this->DupEmail&invalid_email=$this->invalid_email\"></body></html>";
		elseif ($this->to_dpp) {
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/profile/dpp?flag=INTM&oldFlag=PPA\"></body></html>";
		} elseif ($this->tracking) echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/profile/viewprofile.php?ownview=1&tracking=1\"></body></html>";
		elseif ($this->after_login) echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/profile/viewprofile.php?ownview=1&after_login=" . $this->after_login ."&IncompleteMail=". $this->incompleteMail. "\"></body></html>";
		elseif($from_whr){
			switch($from_whr){
			case 'VSP':
			case 'VSP_layer':
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") .'/search/viewSimilarProfile?SIM_USERNAME='.$request->getParameter('SIM_USERNAME').'&contact='.$request->getParameter('contact').'&NAVIGATOR='.$request->getParameter('NAVIGATOR') ."\"></body></html>";
				die;
				break;
			case 'cc':
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") .'/profile/contacts_made_received.php?page=eoi&filter=M'."\"></body></html>";
				die;
			default:
				$uri=urldecode($request->getParameter('prev_url'));
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") .$uri ."\"></body></html>";
			}
		}
		elseif($this->action->FROM_FTO){
			$uri=urldecode($request->getParameter('prev_url'));
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") .$uri ."\"></body></html>";
		}
		elseif($this->action->flag=='INCOMP')
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/profile/viewprofile.php?ownview=1&flag=INCOMP\"></body></html>";
		else echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=" . sfConfig::get("app_site_url") . "/profile/viewprofile.php?ownview=1&EditWhatNew=INTM&oldFlag=" . $this->action->flag . "\"></body></html>";
	die;
	}
	/** Sets loginProfile for this object
	 * */
	public function setLoginProfile($loginProfile) {
		$this->loginProfile = $loginProfile;
	}
	/** Sets action object for this object
	 * */
	public function setActionObject($action) {
		$this->action = $action;
	}
	/* It will be overridden in all inherited classes if an onsubmit script to be added in Edit layer form
	 * @returns javascript as string. Will be used by layoutEdit.tpl
	 * */
	public function getOnSubmitJs() {
		return "";
	}
	/**
	 * It finally changes values permanently for the profile and also update the log.
	 * @param array paramArr That takes key value of profile fields. 
	 * @param array appendToLog contains those values that to be updated only in EditLog(fields that are not there in JPRFOILE
	 * */
	public function updateAndLog($paramArr, $appendToLog = array(),$incompelete=0,$bIsNative_PlaceUpdated=false) {
		if ($this->checkForChange($paramArr,"","update") || count($appendToLog) || $bIsNative_PlaceUpdated) {
			if(!$incompelete){
				//Validate all fields here
				$incorrectFields = ProfileValidator::getIncorrectFields($paramArr);
				//unset all field that has incorrect value to keep old value
				if (count($incorrectFields)) foreach ($incorrectFields as $field) {
					if ($field == "PHONE") {
						unset($paramArr[PHONE_RES]);
						unset($paramArr[PHONE_MOB]);
					}
					unset($paramArr[$field]);
				}
			}
			$log_paramArr = array_merge($paramArr, $appendToLog);
			$log_paramArr['PROFILEID'] = $this->loginProfile->getPROFILEID();
			log_edit($log_paramArr);
			if(array_key_exists("INCOMPLETE",$paramArr))
				insert_in_duplication_check_fields($log_paramArr['PROFILEID'],'new','134217727');
			else
				$this->update_duplication_fields();
			$this->loginProfile->edit($paramArr);
		}
	}

  /**
   * @fn mapAutoSug 
   * @brief This function maps the $value according to the $type auto sug.
   * @param $profile_id The profile id of logged in user
   * @param $type The type of field(e.g. Subcaste, Gothra, etc.)
   * @param $value The actual value which needs to be mapped
   **/
  public function mapAutoSug($profile_id, $type, $value) {
    switch($type) {
      case "SUBCASTE":
          mapAutoSugSubcasteData($profile_id, $value);
          break;
    }
  }
	
  /**
	 * This function compares values of profile with values in paramArray
	 * and return true if there is change else false
	 * */ 
	public function checkForChange($paramArray, $where = '',$from_where="") {
		unset($paramArray[MOD_DT]);
		unset($paramArray[LAST_LOGIN_DT]);
		$flag = false;
		foreach ($paramArray as $key => $value) {
			$getMethod = "get" . $key;
			$orig_value = $this->loginProfile->$getMethod();
			if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
					$flag = true;
				$this->changed_fields[]=$key;
			}
		}
		if($from_where=="update"){
		$searchSetObj=new VerificationSealLib($this->loginProfile);
		$searchSetObj->resetVerificationSeal($this->changed_fields);
	}
		return $flag;
	}
	/**
	 * It sets screening flag for field provided in parameter if that is changed 
	 * @returns changed screening flag
	 * */
	public function getScreeningFlag($fieldArr) {
		$curflag = $this->loginProfile->getSCREENING();
		foreach ($fieldArr as $key => $value) {
			$getMethod = "get" . $key;
			//if ($key == "PHONE_MOB" || $key == "PHONE_RES") $key = str_replace("_", "", $key);
			//if ($key == "MESSENGER_ID") $key = "MESSENGER";
			if ($value == "") $curflag = Flag::setFlag($key, $curflag);
			elseif ($this->loginProfile->$getMethod() != $value) $curflag = Flag::removeFlag($key, $curflag);
		}
		return $curflag;
	}
	/**
	 * It returns keyword for changed values 
	 * */
	public function getUpdatedKeyword($keysToChangeArray) {
		$keywords[AGE] = $this->loginProfile->getAGE();
		$keywords[GENDER] = $this->loginProfile->getDecoratedGender();
		$keywords[HEIGHT] = $this->loginProfile->getDecoratedHeight();
		$keywords[CASTE] = $this->loginProfile->getDecoratedCaste();
		$keywords[OCCUPATION] = $this->loginProfile->getDecoratedOccupation();
		$keywords[CITY] = $this->loginProfile->getDecoratedCity();
		if ($keysToChangeArray[HOBBY]) $hobby = "|" . $keysToChangeArray[HOBBY];
		else $hobby = strstr($this->loginProfile->getKEYWORDS(), "|");
		foreach ($keysToChangeArray as $key => $value) {
			if (in_array($key, array('AGE', 'GENDER', 'HEIGHT', 'CASTE', 'OCCUPATION', 'CITY', 'HOBBY'))) $keywords[$key] = $value;
			else throw Exception("Keyword field $key is not in Key field list. Please use only 'AGE','GENDER','HEIGHT','CASTE','OCCUPATION','CITY','HOBBY' or add an entry for new keyword field here");
		}
		unset($keywords[HOBBY]);
		$keyword = addslashes(stripslashes(implode(",", $keywords) . $hobby));
		return $keyword;
	}
	function update_duplication_fields(){
		if(!count($this->changed_fields))
			return;
			$this->changed_fields=array_unique($this->changed_fields);
		$duplication_fields=array(
			"RELIGION",
			"MTONGUE",
			"CASTE",
			"COUNTRY_RES",
			"CITY_RES",
			"HEIGHT",
			"INCOME",
			"EDU_LEVEL_NEW",
			"CITY_BIRTH",
			"BTIME",
			"PASSWORD",
			"SUBCASTE",
			"OCCUPATION",
			"SCHOOL",
			"COLLEGE",
			"PG_COLLEGE",
			"COMPANY_NAME",
			"EMAIL",
			"MESSENGER_ID",
			"PHONE_MOB",
			"PHONE_RES",
			"ALT_MOBILE",
		);
			$profileid=$this->loginProfile->getPROFILEID();
		$dup_fields=array_intersect($duplication_fields,$this->changed_fields);
		
		if(count($dup_fields)){
			$res=get_from_duplication_check_fields($profileid);
			if($res[TYPE]=='NEW')
				return;
			if($res)
				$val=$res[FIELDS_TO_BE_CHECKED];
			else
				$val=0;
			foreach($dup_fields as $field){
				$val=Flag::setFlag($field,$val,'duplicationFieldsVal');
			}
			insert_in_duplication_check_fields($profileid,'edit',$val);
		}
	}
    
    /*
     * Function for White Listing FieldValue, with the value specified in FieldMapLib
     * @param : $szFieldMapLabel  
     *          Type String, must be a label present in fieldMap Lib
     * @param : $szActualVal
     *          Value recieved from form (i.e. as a paramter)
     * @return : WhiteListed Value
     * @throw  : Void
     */
    public function doWhiteListing($szFieldMapLabel,$szActualVal)
    {
        $arrMapValue = FieldMap::getFieldLabel($szFieldMapLabel, '',1);
        
        if(!$szActualVal || !strlen($szActualVal)){
            return $szActualVal;
        }
        
        if(!is_array($arrMapValue)){
            return ;
        }
        
        //If Array Key exist then trim it and return
        if(array_key_exists(trim($szActualVal),$arrMapValue))
        {
            if(is_string($szActualVal))
                return trim($szActualVal);
            
            return $szActualVal;
        }
        
        return null;
    }
}
