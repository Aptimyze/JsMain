<?php
class newJsmsRegAction extends sfAction 
{
    public function execute($request) 
    {
		$this->getResponse()->addVaryHttpHeader("User-Agent");
                //JSB9 Mobile Tracking
                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobRegPage1Url);
		$trackParamArr =array();

                //Source variables:// Page1 tracking
                $this->source           = $request->getParameter('source');
                $newsource              = $request->getParameter("newsource");
                $tieup_source           = $request->getParameter("tieup_source");
                //$hit_source             = $request->getParameter("hit_source");

                $sourceTracking = new SourceTracking($this->source, SourceTrackingEnum::$REG_PAGE_1_FLAG, $newsource, $tieup_source);
                         $sourceTracking->SourceTracking();
                         $this->source=$sourceTracking->getSource();
                         setcookie("JS_REG_ID","",0,"/");
                         if($this->source=='mailer_adc'){
	                        $siteDownUrl=JsConstants::$siteUrl."/site_down.htm";
				$this->redirect($siteDownUrl);die;
			}

		$this->adnetwork1 	= $request->getParameter("adnetwork1");
                $this->adnetwork 	= $request->getParameter("adnetwork");
                $this->account 		= $request->getParameter("account");
                $this->campaign 	= $request->getParameter("campaign");
                $this->adgroup 		= $request->getParameter("adgroup");
                $this->keyword 		= $request->getParameter("keyword");
                $this->match 		= $request->getParameter("match");
                $this->lmd 		= $request->getParameter("lmd");
		$domain 		=$request->getParameter("domain");	

                if(!$secondary_source = $request->getParameter('secondary_source'))
                        $secondary_source = 'S';

	        //To prevent suprious attack ,checking operator name in PSWRDS table.
	        $ch_operator = $_SERVER['operator'];
	        if ($ch_operator != '' && $ch_operator != 'deleted') {
	            $this->operator = $_SERVER['operator'];
	            setcookie("OPERATOR", $_SERVER['operator'], 0, "/", $domain);
	            setcookie("JS_SOURCE", 'hpblack', time() + 2592000, "/", $domain);
	            $_COOKIE['OPERATOR'] = $_SERVER['operator'];
	        } else {
	            //Unset all the parameters used while not registring offline profile.
	            setcookie("OPERATOR", "", 0, "/", $domain);
	            $_COOKIE['OPERATOR'] = '';
	        }

		//For tracking purpose of cookies
                $this->OtherVariablesTracking();

                //Assign GroupName/pixel code
                $this->assignGroupName();

                //canonical URL:
	        $can_url="/register/page1";
                $this->getResponse()->setCanonical(sfConfig::get("app_site_url").$can_url);

                // Remove login cookies
                if(!$request->getParameter("incompleteUser"))
					if(!isset($_COOKIE['LOGIN_SET']))
						RegistrationMisc::removeLoginCookies();

                $trackParamArr =array("domain"=>"$domain","newsource"=>"$newsource","tieup_source"=>"$tieup_source","source"=>"$this->source","adnetwork1"=>"$this->adnetwork1","adnetwork"=>"$this->adnetwork","account"=>"$this->account","campaign"=>"$this->campaign","adgroup"=>"$this->adgroup","keyword"=>"$this->keyword","match"=>"$this->match","lmd"=>"$this->lmd","reg_comp_frm_ggl"=>"$this->reg_comp_frm_ggl","reg_comp_frm_ggl_nri"=>"$this->reg_comp_frm_ggl_nri","groupname"=>"$this->GROUPNAME","secondary_source"=>"$secondary_source");
		$this->track =  json_encode($trackParamArr);

                /*If page is refreshed or data once submitted and seesion is already made.User will be redirected.
		 screen re-direction on page-refresh */
		$this->loginData = $request->getAttribute("loginData");
		$this->szLandOnView = null;
		$this->isLogin = (is_array($this->loginData) && $this->loginData[PROFILEID])?true:false;
                $showFamily = null;
                
                if(isset($_COOKIE['reg_family']) && $this->isLogin &&$this->loginData[INCOMPLETE] == "N")
                {
                    $showFamily = true;                    
                }
                if(!$this->loginData && $showFamily){
                    unset($_COOKIE['reg_family']);
                    setcookie('reg_family',null,-1,"/");
                    $showFamily = false;
                }
        if($this->isLogin && $this->loginData[PROFILEID] && $this->loginData[INCOMPLETE] == "N" && !$showFamily){
            //Forward to mainmenu.php
            $this->redirect("/profile/mainmenu.php");
		}
        
		if($this->isLogin && $request->getParameter("incompleteUser")){
			$this->channel=$request->getParameter('channel');
			$this->szLandOnView = '#/s7';
      $this->setIncompleteData();
		}
        //call track for registration process only
        $this->regUniqueId = null;
		// if(!$this->isLogin && !$request->getParameter("incompleteUser")) 
  //       {
  //           $this->trackReg();
  //       }

        //$this->szLandOnView = $this->decideLandingScreen($this->szLandOnView);
        if($this->loginData[PROFILEID] && $showFamily){
            $this->szLandOnView = "#/s9";
        }    
		//$this->landingPage =json_encode($landingParams);
    }
    public function assignGroupName() {
        $DEFAULT_US = array("Google NRI US", "rediff_us_fm", "yahoo_nri", "sulekha_us_fm");
        $dbSource = new MIS_SOURCE();
        $resource = $dbSource->getSourceFields("GROUPNAME", $this->source);
        if ($resource["GROUPNAME"]) {
            if ($resource["GROUPNAME"] == "google") $this->reg_comp_frm_ggl = 1;
            elseif ($resource["GROUPNAME"] == "Google_NRI") $this->reg_comp_frm_ggl_nri = 1;
            if (in_array($resource["GROUPNAME"], $DEFAULT_US)) {
                $country_code = 128;
            }
            $this->GROUPNAME = $resource["GROUPNAME"];
        }
    }
    /** OtherVariablesTracking: 
     * Function to track the cookies
     * @param
     * @return
     *
     */
    public function OtherVariablesTracking() {
        //*********** New Changes as per mantis 4075 (For tracking purpose of cookies) **************
        if ((isset($_COOKIE["JS_ADNETWORK"])) || (isset($_COOKIE["JS_ACCOUNT"])) || (isset($_COOKIE["JS_CAMPAIGN"])) || (isset($_COOKIE["JS_ADGROUP"])) || (isset($_COOKIE["JS_KEYWORD"])) || (isset($_COOKIE["JS_MATCH"])) || (isset($_COOKIE["JS_LMD"]))) {
            $cookie_str.= ":JS_ADNETWORK=" . $_COOKIE["JS_ADNETWORK"];
            $cookie_str.= ":JS_ACCOUNT=" . $_COOKIE["JS_ACCOUNT"];
            $cookie_str.= ":JS_CAMPAIGN=" . $_COOKIE["JS_CAMPAIGN"];
            $cookie_str.= ":JS_ADGROUP=" . $_COOKIE["JS_ADGROUP"];
            $cookie_str.= ":JS_KEYWORD=" . $_COOKIE["JS_KEYWORD"];
            $cookie_str.= ":JS_MATCH=" . $_COOKIE["JS_MATCH"];
            $cookie_str.= ":JS_LMD=" . $_COOKIE["JS_LMD"];
            setcookie('JS_CAMP', $cookie_str, time() + 2592000, "/");
            setcookie("JS_ADNETWORK", "", 0, "/");
            setcookie("JS_ACCOUNT", "", 0, "/");
            setcookie("JS_CAMPAIGN", "", 0, "/");
            setcookie("JS_ADGROUP", "", 0, "/");
            setcookie("JS_KEYWORD", "", 0, "/");
            setcookie("JS_MATCH", "", 0, "/");
            setcookie("JS_LMD", "", 0, "/");
        }
        if (!((isset($_COOKIE["JS_ADNETWORK"])) || (isset($_COOKIE["JS_ACCOUNT"])) || (isset($_COOKIE["JS_CAMPAIGN"])) || (isset($_COOKIE["JS_ADGROUP"])) || (isset($_COOKIE["JS_KEYWORD"])) || (isset($_COOKIE["JS_MATCH"])) || (isset($_COOKIE["JS_LMD"])))) {
            if (isset($_COOKIE["JS_CAMP"])) {
                $cookies = explode(":", $_COOKIE["JS_CAMP"]);
                $adnet = explode("=", $cookies[1]);
                $acnt = explode("=", $cookies[2]);
                $camp = explode("=", $cookies[3]);
                $adgr = explode("=", $cookies[4]);
                $keywd = explode("=", $cookies[5]);
                $mtch = explode("=", $cookies[6]);
                $lm = explode("=", $cookies[7]);
            }
            if ($this->adnetwork == "") {
                if ($adnet) $this->adnetwork = $adnet[1];
            }
            if ($this->account == "") {
                if ($acnt) $this->account = $acnt[1];
            }
            if ($this->campaign == "") {
                if ($camp) $this->campaign = $camp[1];
            }
            if ($this->adgroup == "") {
                if ($adgr) $this->adgroup = $adgr[1];
            }
            if ($this->keyword == "") {
                if ($keywd) $this->keyword = $keywd[1];
            }
            if ($this->match == "") {
                if ($mtch) $this->match = $mtch[1];
            }
            if ($this->lmd == "") {
                if ($lm) $this->lmd = $lm[1];
            }
        } else {
            if ($this->adnetwork == "") {
                if (isset($_COOKIE["JS_ADNETWORK"])) $this->adnetwork = $_COOKIE["JS_ADNETWORK"];
            }
            if ($this->account == "") {
                if (isset($_COOKIE["JS_ACCOUNT"])) $this->account = $_COOKIE["JS_ACCOUNT"];
            }
            if ($this->campaign == "") {
                if (isset($_COOKIE["JS_CAMPAIGN"])) $this->campaign = $_COOKIE["JS_CAMPAIGN"];
            }
            if ($this->adgroup == "") {
                if (isset($_COOKIE["JS_ADGROUP"])) $this->adgroup = $_COOKIE["JS_ADGROUP"];
            }
            if ($this->keyword == "") {
                if (isset($_COOKIE["JS_KEYWORD"])) $this->keyword = $_COOKIE["JS_KEYWORD"];
            }
            if ($this->match == "") {
                if (isset($_COOKIE["JS_MATCH"])) $this->match = $_COOKIE["JS_MATCH"];
            }
            if ($this->lmd == "") {
                if (isset($_COOKIE["JS_LMD"])) $this->lmd = $_COOKIE["JS_LMD"];
            }
        }
        //**********Ends here change of Mantis 4075 ***********
        //Ends here change of Mantis 4075
    }

    public function trackReg()
    {
        try{
            $trackObj = new REG_TRACK_JSMS();
            $cookie_name = 'regUID';
            $uniqueId = $_COOKIE[$cookie_name];
            if($uniqueId)
            {
                //Already unique id exist
                return $uniqueId;
            }
            $randomTime = ceil(9*(2*rand(0,1)+5*rand(0,1)+7*rand(0,1))*rand(0,1));
            $time = microtime();
            $arrTime = explode(" ",$time);
            $time1 = ceil($arrTime[0]*1000);
            $finalTime = $arrTime[1]*1000 + $time1;

            $uniqueId = $finalTime + $randomTime;
            $ip = FetchClientIP();
            $trackObj->insertRecord($uniqueId,$ip);
            $cookieExpireTime = time() + 60*60*24;//1 Day
            setcookie($cookie_name,$uniqueId,$cookieExpireTime,"/");
            return $uniqueId;
        } catch (Exception $ex) {
            //Something went wront
        }
        return null;
    }
    
    private function decideLandingScreen($szLandOnView)
    {
        $arrAllowedGroupNames = array("MobileSEM");
        if( null === $szLandOnView &&  in_array($this->GROUPNAME,$arrAllowedGroupNames))
        {
            //Check For GroupNames
            $szLandOnView = '#/jeevansathi';
        }
        return $szLandOnView;
    }
    
    private function setIncompleteData(){
      
      $iProfileID  = $this->loginData['PROFILEID'];
      $this->familyJsonData = null;
      $this->familyIncomeDep = null;
      
      $this->loginProfile=LoggedInProfile::getInstance("",$iProfileID);
      $listFields = sfConfig::get('mod_register_default_LoggedInProfile');
      $this->loginProfile->getDetail($iProfileID,"PROFILEID",$listFields,"RAW");	

      //Get Family Data
      $familyDataKey = array("t_brother","m_brother","t_sister","m_sister","family_type","family_values","family_status","family_income","family_back","mother_occ","gothra","familyinfo","country_res","ancestral_origin");
      
      $arrWhiteList = array("family_type","family_back","family_status","mother_occ");
      $out = array();
      foreach($familyDataKey as $field){
        $getField = 'get'.strtoupper($field);
        $value = $this->loginProfile->$getField();
        $value = str_replace('"', "\'", htmlspecialchars($value));
        if($value || strlen($value)){
          $out[$field] = $value;
        }
      }
     
	$nativeObj = ProfileNativePlace::getInstance();
	$nativePlaceDataArr = array("native_state","native_country","native_city");
	$nativeData = $nativeObj->getNativeData($iProfileID);
	foreach($nativePlaceDataArr as $k=>$field)
	{
		$value = $nativeData[strtoupper($field)];
		$value = str_replace('"', "\'", htmlspecialchars($value));
		if($value || strlen($value)){
		  $out[$field] = $value;
		}
	}
      //'0' values are invalid so unset those value
      foreach($arrWhiteList as $key){
        if($out[$key] == '0'){
          unset($out[$key]);
        }
      }
      $this->familyIncomeDep = $out['country_res'];
      unset($out['country_res']);
      
      if(count($out)){
        $this->familyJsonData = json_encode($out,JSON_FORCE_OBJECT);
      }
      
      setcookie('reg_family',"1",0,"/");
    }
    
}
?>
