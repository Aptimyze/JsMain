<?php
class page1Action extends sfAction {
    /** Executes Registration page 1
     *
     *
     */
    public function execute($request) {
      //If coming from mobile browser then forward it to mobile registration page
      if(MobileCommon::isMobile())
        $this->forward('register','jsmbPage1');
      
      if($request->getParameter("customReg") === null)
      {
        $request->setParameter('page',RegistrationEnums::$JSPC_REG_PAGE[1]);
        $this->forward('register','regPage');
      }
	$this->getResponse()->addVaryHttpHeader("User-Agent");
        // $timer= sfTimerManager::getTimer(sprintf('Component %s/%s','register','page'));
		
        //Jsb9 page load time tracking page1
        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsRegPage1Url);
        $this->SITE_URL = sfConfig::get("app_site_url");
        //Create source tracking object to track source and set cookie
        $this->source = $request->getParameter('source');
        //Source variables:
        $newsource = $request->getParameter("newsource");
        $tieup_source = $request->getParameter("tieup_source");
        $hit_source = $request->getParameter("hit_source");

        $sourceTracking = new SourceTracking($this->source, SourceTrackingEnum::$REG_PAGE_1_FLAG, $newsource, $tieup_source);
		$reg_params = $request->getParameter("reg");
		if (!$request->getParameter('submit_page1')){
        		$sourceTracking->SourceTracking();
				$this->source=$sourceTracking->getSource();
				if($this->source=='mailer_adc'){
					$siteDownUrl=JsConstants::$siteUrl."/site_down.htm";
					$this->redirect($siteDownUrl);die;
				}
				//Preset values fetched from mini-reg or mailer pages
				//If coming from mini reg then all variables will come in reg parameter in array format else build reg_paramas array from individual variables 
				//as in mailers
				if(!count($reg_params)){
					$reg_params[email]= $request->getParameter("email");
					$reg_params[phone_mob][isd]= $request->getParameter("country_Code");
					$reg_params[phone_mob][mobile]=$request->getParameter("mobile");
					$reg_params[gender]=$request->getParameter("gender");
					$reg_params[mtongue]=$request->getParameter("mtongue");
					$reg_params[religion]=$request->getParameter("religion");
					$dtofbirth=$request->getParameter("year")."-".$request->getParameter("month")."-".$request->getParameter("day");
					$reg_params['dtofbirth'] = $dtofbirth;
					$reg_params['relationship'] = $request->getParameter("relationship");
					$reg_params['caste']=$request->getParameter("caste");
					$reg_params['city_res']=$request->getParameter("city_res");
					$reg_params['country_res']=$request->getParameter("country_res");
				}
		}
        /* Mapping of Gender Based upon Relatiosnhip */
        if ($reg_params[relationship] == '1' || $reg_params[relationship] == '2' || $reg_params[relationship] == '6' || $reg_params[relationship] == '4') $pre_page_gender = 'M';
        else if ($reg_params[relationship] == '2D' || $reg_params[relationship] == '6D' || $reg_params[relationship] == '1D' || $reg_params[relationship] == '4D') $pre_page_gender = 'F';
        if ($reg_params[relationship] == '1D') $reg_params[relationship] = '1';
        else if ($reg_params[relationship] == '4D') $reg_params[relationship] = '4';
        //Get symfony form object related to registration page1
        $this->form = new PageForm(array('email' => $reg_params[email], 'relationship' => $reg_params[relationship], 'dtofbirth' => $reg_params[dtofbirth], 'mtongue' => $reg_params[mtongue], 'religion'=>$reg_params[religion],'phone_mob'=> $reg_params[phone_mob], 'gender' => $pre_page_gender, 'source' => $this->source, 'showphone' => 'Y', 'showmobile' => 'Y'), array("page" => 'DP1', 'request' => $request,'allow_extra_fields'=>1), '');
        $this->RELIGION = $reg_params['religion'];
        $this->CASTE = $reg_params['caste'];
        $this->CITY_RES = $reg_params['city_res'];
        $this->COUNTRY_RES = $reg_params['country_res'];
        //Get array of name of fields that are in this page
        $field_array = RegEditFields::getFieldArray('DP1');
        //Get client side error messages that will be displayed by jquery validator
        $this->errMsg = ErrorHelp::getErrorArray($field_array);
        //To add Fto related communication on registration pages, following variable will tell template to add them
        $this->IS_FTO_LIVE = FTOLiveFlags::IS_FTO_LIVE;
        $LIVE_CHAT_URL = "http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=".JsConstants::$siteUrl."/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=".JsConstants::$siteUrl."/images_try/liveperson";
        $this->LIVE_CHAT_URL = $LIVE_CHAT_URL;
        //canonical URL :
        $can_url="/register/page1";
		$this->getResponse()->setCanonical(sfConfig::get("app_site_url").$can_url);
        
        //Entry into MIS.REG_LEAD
        //Leads related variables:
        $this->leadid = $request->getParameter("leadid");
              
        
        //per mantis 4075 Variables:
        $this->adnetwork = $request->getParameter("adnetwork");
        $this->account = $request->getParameter("account");
        $this->campaign = $request->getParameter("campaign");
        $this->adgroup = $request->getParameter("adgroup");
        $this->keyword = $request->getParameter("keyword");
        $this->match = $request->getParameter("match");
        $this->lmd = $request->getParameter("lmd");
        // other variables:
        $showlogin = $request->getParameter("showlogin");
        $Showphone = $request->getParameter("Showphone");
        $Showmobile = $request->getParameter("Showmobile");
        $domain = $request->getParameter("domain");
        

		
        //**********HIDDEN VARIABLES END***************
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
        if ($lang == "deleted") $lang = "";
        //For tracking purpose of cookies
        $this->OtherVariablesTracking();
        // assert that some things are not be shown in common templates as is the case with homepage
        $this->CAMEFROMHOMEPAGE = "1";
        $this->TIEUP_SOURCE = $this->source;
        //IP ADDRESS
        $this->ip = CommonFunction::getIP();
        //****to check suspected registration from ip address****
        $suspected_check = CommonFunction::suspectedIP($this->ip);
        /* Display for Header Template content */
        //---------> Live Table is emoty hence its not it use now :
        //tieup_creative($source);
        /* End Display Header Template content */
        //Assign GroupName
        $this->assignGroupName();
        $this->affiliateid=$request->getParameter('affiliateid');
        $now = date("Y-m-d G:i:s");
        $today = CommonUtility::makeTime(date("Y-m-d"));
        
        //Get CustomReg Param
        $customReg = $request->getParameter("customReg");
        
        //Check if form is submitted
        if ($request->getParameter('submit_page1')) {
			//Check if no javascript enabled
			if($request->getParameter('js_variable')){
				//if country is not USA(128) or INDIA(51) then remove city_res from form
				if($reg_params['country_res']!=51){
					unset($this->form['city_res']);
				}
        //Bind form variables with form
				$this->form->bind($request->getParameter('reg'));
				//Run server side validators
				if ($this->form->isValid()) {
					/*Code to check spammer, checking for request from same ip. Block registration if request > 5 within 1 minute if not then insert its entry into DB*/
					$dbBlockIP = new NEWJS_BLOCK_IP();
					if ($dbBlockIP->blockIP($this->ip)) die("Too many requests !");
					else $dbBlockIP->insertIP($this->ip);
					/*End of - Code to check spammer*/
					if ($hit_source != 'O') {
						$dbSource = new MIS_SOURCE();
						$force_mail = $dbSource->getSourceFields("FORCE_EMAIL", $tieup_source);
						if ($force_mail["FORCE_EMAIL"] == 'Y') $email_validation = 'Y';
						//REGISTRATION SUBMIT FUNCTIONALITY -->CODE
						
						//Following var is not used any where. need to check its significance.
						//	$ANNULLED_SCREEN='N';
						
						//required keyword variables
						$keywords=RegistrationMisc::getKeywords($reg_params);
						//There are some variables that are to be set to some default and not part of form so then need to send as an array 
						//to updateData function
						//required keyword variables
						$alertArr = RegistrationMisc::getLegalVariables($request);
						
						//Field for identifying the team to which profile belong
						if($source == 'onoffreg')
							$crm_team = 'offline';
						else 
							$crm_team = 'online';
						if($alertArr[SERVICE_EMAIL] == 'S') 
							$match_def = 'A';
						else
							$match_def = 'U';
						if($alertArr[SERVICE_SMS] == 'S') 
							$sms_def = 'Y';
						else 
							$sms_def = 'N';
						if(!$secondary_source = $request->getParameter('secondary_source')) 
							$secondary_source = 'S';
						$age=CommonFunction::getAge($this->form->getValue('dtofbirth'));
						$phone=$this->form->getValue('phone_res');
						if($phone[landline])
							$phone_with_std=$phone[std].$phone[landline];
						$values_that_are_not_in_form = array('INCOMPLETE' => 'Y', 'ACTIVATED' => 'N', 'SCREENING' => 0, 'SERVICE_MESSAGES' => $alertArr[SERVICE_EMAIL], 'ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today, 'SORT_DT' => $now, 'IPADD' => $this->ip, 'PROMO_MAILS' => $alertArr[PROMO_MAIL], 'CRM_TEAM' => $crm_team, 'PERSONAL_MATCHES' => $match_def, 'GET_SMS' => $sms_def, 'SEC_SOURCE' => $secondary_source, 'KEYWORDS' => $keywords,'AGE'=>$age,'PHONE_WITH_STD'=>"$phone_with_std");
					   
						//Update or insert data
						$id = $this->form->updateData('', $values_that_are_not_in_form);
                                                RegistrationFunctions::putCampaignVars($id,$request->getParameter("campaignData"));
						//Initiate a loggedin profile object
						$this->loginProfile = LoggedInProfile::getInstance();
						$this->loginProfile->getDetail($id, "PROFILEID");
						$username = $this->loginProfile->getUSERNAME();
						$request->setAttribute('username',$username);	
						$request->setAttribute('profileid',$id);	
						$request->setAttribute('loginData',array('PROFILEID'=>$id));	
						//create login session
						RegistrationMisc::setAuthenticationCookie();
						// data insertion in JPROFILE_ALERT table
						
						RegistrationMisc::updateAlertData($id,$alertArr);
						
						//Set default jpartner values
						RegistrationMisc::setJpartnerAfterRegistration($this->loginProfile);
						
						/* Sending to the 2nd Page for use in 3rd Page */
						//added by nitesh for Source tracking to store registration caused by sources leading to home page
						if (isset($_COOKIE['JS_SOURCE_HOME'])) {
							$this->source = $_COOKIE['JS_SOURCE_HOME'];
							$sourceTracking->sourceFromHomePage($this->source, $id);
							//Now unser JS_SOURCE_HOME cookie
							setcookie("JS_SOURCE_HOME", "", time() - 3600, "/");
						}
						//end of Source tracking by nitesh
						//Contact Archive updates added by nitesh
						RegistrationMisc::contactArchiveUpdate($this->loginProfile,$this->ip);
						//Assingning profileid to offline OPERATOR Added by nitesh
						if ($_COOKIE['OPERATOR'] != '') {
							$this->operatorAssigning($id, $_COOKIE['OPERATOR'], $tieup_source);
						} //LEAD Table needs to be updated if it is a sugarcrm leads// added by nitesh
						else if ($this->source == 'onoffreg' && $_COOKIE['JS_LEAD']) {
							 RegistrationMisc::updateSugarLead($id,$username,$this->source);
						}
						//CODE ADDED BY Nitesh Sethi to capture outer variable
						if ($this->adnetwork || $this->account || $this->campaign || $this->adgroup || $this->keyword || $this->match || $this->lmd) {
							$dbTrackTieupVariable = new MIS_TRACK_TIEUP_VARIABLE();
							$dbTrackTieupVariable->insert($this->adnetwork, $this->account, $this->campaign, $this->adgroup, $this->keyword, $this->match, $this->lmd, $id);
						}
						//CODE Ended By Nitesh Sethi
						//Added By Nitesh to email authority for informing about suspected email-id.
						if ($suspected_check) SendMail::send_email('vikas@jeevansathi.com,jaiswal.amit@jeevansathi.com', $id, "Profileid of suspected email-id", "register@jeevansathi.com");

						//Insert in NAMES and INCOMPLETE_PROFILE table and also update MIS_REG_COUNT data
						RegistrationMisc::insertInIncompleteProfileAndNames($this->loginProfile);
                                                
                                                if(isset($customReg) && $customReg == 1){
                                                    $partnerField = new PartnerField();
                                                    RegistrationFunctions::UpdateFilter($partnerField);
                                                }
                                                
						//cookie deleted by Nitesh Sethi after registration
						$this->unsetCampaignCookies();
						// Mailer on Registration
						if ('C' == $secondary_source) {
							RegistrationCommunicate::sendEmailAfterRegistrationIncomplete($this->loginProfile);
						}


						// email for verification
						$emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($this->loginProfile->getPROFILEID(),$this->loginProfile->getEMAIL());
					(new emailVerification())->sendVerificationMail($this->loginProfile->getPROFILEID(),$emailUID);
							////////
                    

						//Lead conversion update
						RegistrationMisc::updateLeadConversion($this->loginProfile->getEMAIL(),$this->leadid);

						//Everything done huh ...now forward it to page 2
						unset($this->form);
            
            if(isset($customReg) && $customReg == 1){
             $request->setParameter('customSubmitSuccess',RegistrationEnums::$JSPC_REG_PAGE[3]);
             return sfView::NONE;
            }
            else{
              $this->forward("register", "page2");
            }
					}
				}
				 else {
					if(!isset($this->form['city_res'])){
						$city_field=$this->form->getPageObject()->getFieldByName('CITY_RES');
						$this->form->setWidget('city_res',FormInputFactory::getInputObject($city_field,""));
						$this->form->setValidator('city_res',ValidatorsFactory::getValidator($city_field));
					}
					RegistrationMisc::logServerSideValidationErrors("DP1",$this->form);
				}
			}
		}
		else
		{
			//Rocket fuel pixel for registration page1
			if (PixelCode::RocketFuelValidation($this->GROUPNAME,$this->source,"","","","",1)) {
				$this->pixelcode = PixelCode::fetchRocketFuelCode("regPage1");
            }
		}
		$customReg = $request->getParameter("customReg");
		
      if(isset($customReg) && $customReg == 1)
      {
        $this->customReg = 1;
        $this->heading = $request->getParameter('h');
        $this->subhead1 = $request->getParameter('sh1');
        $this->subhead2 = $request->getParameter('sh2');
        $this->image = $request->getParameter('image');
        $this->page_id = $request->getParameter('p');
        $this->sourcename = $request->getParameter('source');
        $pageVar=RegistrationFunctions::assignGroupName($this->sourcename);
        $this->groupname = $pageVar['GROUPNAME'];
        
        $campaignData = RegistrationFunctions::getCampaignVars($request);
        if($campaignData)
            $this->campaignData = $campaignData;
        
        $this->setLayout(false);
        $this->setTemplate("custompageform");
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
    /** assignGroupName: 
     * Function to assign Groupname Variable based on some specific google source
     * @param
     * @return
     *
     */
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
    /** operatorAssigning: 
     * Function  to assign operators
     * @param $id,$_COOKIE['operator'],$source,$tieup_source
     * @return
     *
     */
    public function operatorAssigning($id, $operator, $tieup_source) {
        if ($tieup_source == '101') {
            $dbJsAdminAssigned101 = new JSADMIN_ASSIGNED_101();
            $dbJsAdminAssigned101->replace($id, $operator);
            $dbJsAdminAssignLog101 = new JSADMIN_ASSIGNLOG_101();
            $dbJsAdminAssignLog101->insert($id, $operator);
        } else if ($this->source == 'onoffreg') {
            $dbOfflineRegistration = new NEWJS_OFFLINE_REGISTRATION();
            $dbOfflineRegistration->insert($id, $operator, $this->source);
        } else {
            $dbOfflineAssigned = new JSADMIN_OFFLINE_ASSIGNED();
            $dbOfflineAssigned->replace($id, $operator);
            $dbOfflineAssignedLog = new JSADMIN_OFFLINE_ASSIGNLOG();
            $dbOfflineAssignedLog->insert($id, $operator);
        }
    }
	private function unsetCampaignCookies(){
                    setcookie("JS_SOURCE", "", 0, "/", $domain);
                    setcookie("JS_ADNETWORK", "", 0, "/");
                    setcookie("JS_ACCOUNT", "", 0, "/");
                    setcookie("JS_CAMPAIGN", "", 0, "/");
                    setcookie("JS_ADGROUP", "", 0, "/");
                    setcookie("JS_KEYWORD", "", 0, "/");
                    setcookie("JS_MATCH", "", 0, "/");
                    setcookie("JS_LMD", "", 0, "/");
	}
}
?>
