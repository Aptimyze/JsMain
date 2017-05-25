<?php
class RegistrationFunctions
{
  /*
   * fetching source related parmeters like source tracking
   */
  public static function getSourceParams($request)
  {
        $sourceArr['source'] = $request->getParameter("source");
        foreach(RegistrationEnums::$sourceParamList as $key=>$val)
                $sourceArr[$val]=$request->getParameter($val);
        $sourceArr['sourceTracking'] = new SourceTracking($sourceArr['source'], SourceTrackingEnum::$REG_PAGE_1_FLAG, $sourceArr['newsource'], $sourceArr['tieup_source']);
	$sourceArr['tieup_source']=$sourceArr['source'];
        return $sourceArr;
  }
  /*
   * fetch tieup source parameters
   */
  public static function getTieupParams($request)
  {
    foreach (RegistrationEnums::$tieupCookieList as $key => $val) {
      $param = RegistrationEnums::$tieupGetterList[$key];
      $tieupArr[$val] = $request->getParameter($param);
    }
    $tieupArr = self::OtherVariablesTracking($tieupArr);
    return $tieupArr;
  }
 /*
  * crm team status
  */
  public static function getCrm($source)
  {
        $crm_team = 'online';
        if(source=="onoffreg")
                $crm_team = "offline";
	return $crm_team;
  }
  /*
   * fetch secondary source
   */
  public static function getSecondarySource($request)
  {
        if(!$request->getParameter('secondary_source'))
                return $secondary_source = 'S';
  }

	public static function setOperatorCookie($request)
	{
		$ch_operator = $request->getParameter('operator');
		if ($ch_operator != '' && $ch_operator != 'deleted') 
		{
			setcookie("OPERATOR", $ch_operator, 0, "/", $domain);
			setcookie("JS_SOURCE", 'hpblack', time() + 2592000, "/", $domain);
			$_COOKIE['OPERATOR'] = $ch_operator;
		} 
		else 
		{
			setcookie("OPERATOR", "", 0, "/", $domain);
			$_COOKIE['OPERATOR'] = '';
			$ch_operator = '';
		}
		return $ch_operator;
	}
        /*
         * map gender to relationship status
         */
	public static function mapGender($relation)
	{
		if(in_array($relation,RegistrationEnums::$relationIdForMale))
			return $gender = "M";
		if(in_array($relation,RegistrationEnums::$relationIdForFemale))
			return $gender = "F";
		return false;
	}
        /*
         * asigning JSAdmin operators
         */
	public static function operatorAssigning($id, $operator, $tieup_source,$source) 
	{
		if ($tieup_source == '101') 
		{
			$dbJsAdminAssigned101 = new JSADMIN_ASSIGNED_101();
			$dbJsAdminAssigned101->replace($id, $operator);
			$dbJsAdminAssignLog101 = new JSADMIN_ASSIGNLOG_101();
			$dbJsAdminAssignLog101->insert($id, $operator);
		} 
		else if ($this->source == 'onoffreg')
		{
			$dbOfflineRegistration = new NEWJS_OFFLINE_REGISTRATION();
			$dbOfflineRegistration->insert($id, $operator, $source);
		} 
		else 
		{
			$dbOfflineAssigned = new JSADMIN_OFFLINE_ASSIGNED();
			$dbOfflineAssigned->replace($id, $operator);
			$dbOfflineAssignedLog = new JSADMIN_OFFLINE_ASSIGNLOG();
			$dbOfflineAssignedLog->insert($id, $operator);
		}
	}
        /*
         * unset campaign cookies
         */
        public static function unsetCampaignCookies($domain){
                    setcookie("JS_SOURCE", "", 0, "/", $domain);
		    foreach(RegistrationEnums::$tieupCookieList as $k=>$cookieName)
			    setcookie($cookieName, "", 0, "/");
        }
        /*
         * assign groupname for a particular source id
         */
	public static function assignGroupName($source) 
	{
    //TODO below line is mising
    //$DEFAULT_US = array("Google NRI US", "rediff_us_fm", "yahoo_nri", "sulekha_us_fm");
		$dbSource = new MIS_SOURCE();
		$resource = $dbSource->getSourceFields("GROUPNAME", $source);
		if ($resource["GROUPNAME"]) 
		{
			if ($resource["GROUPNAME"] == "google") 
				$groupNameArr['reg_comp_frm_ggl'] = 1;
			elseif ($resource["GROUPNAME"] == "Google_NRI")
				$groupNameArr['reg_comp_frm_ggl_nri'] = 1;
		    $groupNameArr['GROUPNAME'] = $resource["GROUPNAME"];
		}
		return $groupNameArr;
	}
        /*
         * tracking different other variables and setting cookies based on that
         */
	public static function OtherVariablesTracking($tieupParams) 
	{
		if ((isset($_COOKIE["JS_ADNETWORK"])) || (isset($_COOKIE["JS_ACCOUNT"])) || (isset($_COOKIE["JS_CAMPAIGN"])) || (isset($_COOKIE["JS_ADGROUP"])) || (isset($_COOKIE["JS_KEYWORD"])) || (isset($_COOKIE["JS_MATCH"])) || (isset($_COOKIE["JS_LMD"]))) 
		{
			foreach(RegistrationEnums::$tieupCookieList as $k=>$param)
				$cookie_str.= ":".$param."=" . $_COOKIE[$param];
			setcookie('JS_CAMP', $cookie_str, time() + 2592000, "/");
			foreach(RegistrationEnums::$tieupCookieList as $k=>$param)
				setcookie($param, "", 0, "/");
        	}
		if (!((isset($_COOKIE["JS_ADNETWORK"])) || (isset($_COOKIE["JS_ACCOUNT"])) || (isset($_COOKIE["JS_CAMPAIGN"])) || (isset($_COOKIE["JS_ADGROUP"])) || (isset($_COOKIE["JS_KEYWORD"])) || (isset($_COOKIE["JS_MATCH"])) || (isset($_COOKIE["JS_LMD"])))) 
		{
			if (isset($_COOKIE["JS_CAMP"])) 
			{
				$cookies = explode(":", $_COOKIE["JS_CAMP"]);
				foreach(RegistrationEnums::$tieupCookieList as $k=>$param)
					$tieupCookies[$param] = explode("=", $cookies[$k]);
			}
			foreach(RegistrationEnums::$tieupCookieList as $k=>$param)
			{
				if($tieupParams[$param]==''&&$tieupCookies[$param])
					$tieupParams[$param]=$tieupCookies[$param][1];
			}
		} 
		else 
		{
			foreach(RegistrationEnums::$tieupCookieList as $k=>$param)
			{
				if($tieupParams[$param]==''&& isset($_COOKIE[$param]))
					$tieupParams[$param]=$_COOKIE[$param];
			}
		}
		return $tieupParams;
	}
        /*
         * set cookie for Source_home
         */
	public static function setSourceHomeCookie($id)
	{
		if (isset($_COOKIE['JS_SOURCE_HOME'])) 
		{
			$source = $_COOKIE['JS_SOURCE_HOME'];
			$sourceTracking->sourceFromHomePage($source, $id);
			setcookie("JS_SOURCE_HOME", "", time() - 3600, "/");
		}
		return $source;
	}
        /*
         * tracking of tieup parameters
         */
	public static function trackTieupParams($tieupParams,$id) 
	{
		if ($tieupParams['JS_ADNETWORK'] || $tieupParams['JS_ACCOUNT'] || $tieupParams['JS_CAMPAIGN'] || $tieupParams['JS_ADGROUP'] || $tieupParams['JS_KEYWORD'] || $tieupParams['JS_MATCH'] || $tieupParams['JS_LMD']) 
		{
			$dbTrackTieupVariable = new MIS_TRACK_TIEUP_VARIABLE();
			$dbTrackTieupVariable->insert($tieupParams['JS_ADNETWORK'],$tieupParams['JS_ACCOUNT'], $tieupParams['JS_CAMPAIGN'], $tieupParams['JS_ADGROUP'], $tieupParams['JS_KEYWORD'], $tieupParams['JS_MATCH'], $tieupParams['JS_LMD'],$id);
		}
	}
        /*
         * setting legal variables like those of user accepting to terms and conditions
         */
        public static function setLegalVariables($request)
        {
            foreach(RegistrationEnums::$legalVariableList as $vars){
		$legalVar = $request->getParameter($vars);
                if($legalVar == '') {
                    $request->setParameter($vars,"S");
                }
            }
        }
    /**
     * UpdateFilter
     * @param type $partnerField
     */
    public static function UpdateFilter($partnerField) 
    {      
      $arrFilter = array();
      if ($partnerField->partnerObj->getPARTNER_MSTATUS()) {
        $arrFilter["MSTATUS"] = 'Y';
      } 
      if ($partnerField->partnerObj->getPARTNER_RELIGION()) {
        $arrFilter["RELIGION"] = 'Y';
      } 
      if ($partnerField->partnerObj->getPARTNER_CASTE()) {
        $arrFilter["CASTE"] = 'Y';
      } 
      //for marathi profiles, we have to auto set the filter
      if($partnerField->partnerObj->getPARTNER_MTONGUE() == RegistrationEnums::$marathiValue)
      {
        $arrFilter["MTONGUE"] = 'Y';
      }

      if(count($arrFilter)) {
        $hardSoft="Y";
        $count=10;
      } else {
        $hardSoft="N";
        $count=0;
      }
      $arrFilter['HARDSOFT'] = $hardSoft;
      $arrFilter['COUNT'] = $count;

      $dbObj = new NEWJS_FILTER;
      $dbObj->insertRecord(LoggedInProfile::getInstance()->getPROFILEID(), $arrFilter);
    }
        public static function getPrefilledDataForUser($loginProfileObj,$pageId) {
            $completeFields = array();
            if($pageId == "JSPCR2"){
                $completeFields["religion"] = $loginProfileObj->getRELIGION();
                $completeFields["caste"] = $loginProfileObj->getCASTE();
                $completeFields["casteMuslim"] = $loginProfileObj->getSECT();
                $completeFields["mtongue"] = $loginProfileObj->getMTONGUE();
                $completeFields["mstatus"] = $loginProfileObj->getMSTATUS();
                $completeFields["height"] = $loginProfileObj->getHEIGHT();
                $completeFields["subcaste"] = $loginProfileObj->getSUBCASTE();
                $completeFields["manglik"] = CommonFunction::setManglikWithoutDontKnow($loginProfileObj->getMANGLIK());
                $completeFields["haveChildren"] = $loginProfileObj->getHAVECHILD();
                $completeFields["pin"] = $loginProfileObj->getPINCODE();
                $completeFields["horoscopeMatch"] = $loginProfileObj->getHOROSCOPE_MATCH();
                if($completeFields["religion"] =='2' && $completeFields["caste"]=='152'){
                    $religionInfo = (array)$loginProfileObj->getReligionInfo(1);
                    $completeFields["jamaat"] = $religionInfo['JAMAAT'];
                }
                $country_res=$loginProfileObj->getCOUNTRY_RES();
                $completeFields["countryReg"] = $country_res;
                if($country_res==51 || $country_res==128){
                    if($country_res==51 && $loginProfileObj->getCITY_RES()!="0")
                        $completeFields["stateReg"] = substr($loginProfileObj->getCITY_RES(),0,2);
                    if(substr($loginProfileObj->getCITY_RES(),2)=="OT")
                        $city = "0";
                    else
                        $city = $loginProfileObj->getCITY_RES();
                    $completeFields["cityReg"] = $city;
                }
            }
            if($pageId == "JSPCR3"){
                $completeFields["occupation"] = $loginProfileObj->getOCCUPATION();
                $completeFields["hdegree"] = $loginProfileObj->getEDU_LEVEL_NEW();
                $completeFields["income"] = $loginProfileObj->getINCOME();
                $completeFields["aboutme"] = $loginProfileObj->getYOURINFO();
            }
            return json_encode($completeFields,JSON_FORCE_OBJECT);
        }
        
        //this function modifies existing email which has been deleted to make new entry
        public static function deletedEmailModify($email){
            $jprofileObj = new JPROFILE();
            //fetch if already emails exist
            $emailArr = $jprofileObj->getEmailLike($email.RegistrationEnums::$emailModification);
            $lastNumber = 1;
            $max=0;
            if(is_array($emailArr)){
                foreach($emailArr as $key=>$val){
                    $lastNumber = explode(RegistrationEnums::$emailModification,$val[EMAIL])[1];
                    if($max < $lastNumber)
                        $max=$lastNumber;
                }
            }
            $affectedRows = $jprofileObj->updateEmail($email,$email.RegistrationEnums::$emailModification.($max+1));
            return $affectedRows;
        }
}
