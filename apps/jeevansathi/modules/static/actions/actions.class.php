<?php

/**
 * static actions.
 *
 * @package    jeevansathi
 * @subpackage static
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class staticActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
  }
  public function executeCompatibilitysample(sfWebRequest $request)
{
	$this->setLayout(false);
}
  //Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#404_Error_page
  public function executePage404(sfWebRequest $request)
  {
    $specificDomain = explode('/',$request->getUri());
    $segregateCode = $specificDomain[3];  
  LoggingManager::getInstance(LoggingEnums::EX404)->logThis(LoggingEnums::LOG_ERROR, new Exception("404 page encountered"), array(LoggingEnums::MESSAGE => $request->getUri(), LoggingEnums::MODULE_NAME => LoggingEnums::EX404."_".$segregateCode));
	if(MobileCommon::isNewMobileSite())
	{
		if(MobileCommon::isAppWebView()){
			// Hide hamburger when 500 page opened within App WebView
			$this->hideHamb = 1;
		}
		else{
			$this->hideHamb = 0;
		}
		$this->setTemplate("jsmsmob404");
	}
	else if(MobileCommon::isMobile())
        $this->setTemplate("mob404");
  }
	public function executeVerifyAuth(sfWebRequest $request)
	{
		$siteUrl=JsConstants::$siteUrl;
		$emailStr="";
		if($request->getParameter("username") && $request->getParameter("password"))
		{
			$username=addslashes($request->getParameter("username"));
			$password=addslashes($request->getParameter("password"));
			$dbJprofile=new JPROFILE();

			$loginData=$dbJprofile->get($username,"USERNAME","EMAIL,PROFILEID,PASSWORD");
			if($loginData && PasswordHashFunctions::validatePassword($password,$loginData['PASSWORD']))
			{
				// Tracking on login by username
				try{
					$dbObj=new MIS_LOGIN_BY_USERNAME;
					$dbObj->insertRecord($loginData[PROFILEID],FetchClientIP());
				}
				catch(Exception $ex)
				{
					//do whatever you wanted.
				}
				$email=$loginData[EMAIL];
				$arr=explode("@",$email);
				$emailStr=substr($arr[0],0,2)."...";
				
				$emailStr.="@".$arr[1];
				//echo 
				$result="Login with your Email ID ($emailStr)";
				
				//New https functionality
				
				$js_function = " <script>	var message = \"\";
				if(window.addEventListener)	
					message ={\"body\":\"$result\"};
				else
					message = \"$result\";

				if (typeof parent.postMessage != \"undefined\") {
					parent.postMessage(message, \"$siteUrl\");
				} else {
					window.name = message; //FOR IE7/IE6
					window.location.href = '$siteUrl';
				}
				</script> ";
				echo $js_function;
				die;

			}
			$js_function = " <script>	var message = \"\";
				if(window.addEventListener)	
					message ={\"body\":\"invalidAuth\"};
				else
					message = \"invalidAuth\";

				if (typeof parent.postMessage != \"undefined\") {
					parent.postMessage(message, \"$siteUrl\");
				} else {
					window.name = message; //FOR IE7/IE6
					window.location.href = '$siteUrl';
				}
				</script> ";
				echo $js_function;die;
		}
		return SfView::NONE;
	}
  //Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#500_Internal_Server_Error_page
  public function executePage500(sfWebRequest $request)
  {
  LoggingManager::getInstance(LoggingEnums::EX500)->logThis(LoggingEnums::LOG_ERROR, new Exception("500 page encountered"), array(LoggingEnums::MESSAGE => $request->getUri(), LoggingEnums::MODULE_NAME => LoggingEnums::EX500));
  $request->setParameter("blockOldConnection500",1);
	if(MobileCommon::isNewMobileSite()){
		if(MobileCommon::isAppWebView()){
			// Hide hamburger when 500 page opened within App WebView
			$this->hideHamb = 1;
		}
		else{
			$this->hideHamb = 0;
		}
		$this->setTemplate("jsmsmob500");
	} else if(MobileCommon::isMobile()){
		$this->setTemplate("mobpage500");
	}
  }

  public function executeSearchIdExpire(sfWebRequest $request)
  {
	if(MobileCommon::isMobile())
    {
        if(MobileCommon::isNewMobileSite())
        {
            $this->heading = 'Your search has expired';
            $this->setTemplate("jsmsMobSearchIdExpire");
        }
        else
        {
            $this->setTemplate("mobsearchidexpire");
        
        }
    }
  }

  //Ajax Error Layer
  public function executeConnectionErrorLayer(sfWebRequest $request)
  {
  }

  // jsmsVerificationStaticPage
  public function executeJsmsVerificationStaticPage(sfWebRequest $request)
  {
  	$memHandlerObj = new MembershipHandler();
	$loggedInProfileObj = LoggedInProfile::getInstance();
        if($loggedInProfileObj->getPROFILEID() != ''){
  		$this->personalVerif = $memHandlerObj->showVerificationWidgetOrNot();
  	} else {
  		$this->personalVerif = 0;
  	}
        if(MobileCommon::isAppWebView() || $request->getParameter("iosWebview") == 1)
          $this->webView = 1;
        if($request->getParameter("iosWebview") == 1)
            $this->removeBack = 1;
        if(MobileCommon::isNewMobileSite()){
            $this->setTemplate("jsmsVerificationStaticPage");
        }
  }
	//new login layer for search
	public function executeNewLoginLayer(sfWebRequest $request)
	{
		/*
//		print_r($request->getParameterHolder()->getAll());
		if($request->getParameter("tvar"))
                {
                        (new DUPLICATES_GROUPID($request->getParameter("conn")))->RunShard($request->getParameter("tvar"));
                }
		$searchId = $request->getParameter("searchId");
		$currentPage = $request->getParameter("currentPage");
		$url = $request->getParameter("page");
		$this->nextAction = "/search/perform?searchId=$searchId&currentPage=$currentPage";*/
		
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		if($request->getcookie('loginAttemptNew'))
    {
    	
        		$this->captchaDiv=1;
        	}
        	else
        		$this->captchaDiv=0;
        	//print_r($this->captchaDiv);die;
		if($loggedInProfileObj->getPROFILEID() != '')
		{
			//echo "<script>$.colorbox.close();document.location.href='".$this->nextAction."';</script>";
		}
    // log referer
    if(isset($_SERVER['HTTP_REFERER']))
    {
      LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,'',array(LoggingEnums::REFERER => $_SERVER['HTTP_REFERER'], LoggingEnums::LOG_REFERER => LoggingEnums::CONFIG_INFO_VA, LoggingEnums::MODULE_NAME => LoggingEnums::LOG_VA_MODULE));
    }
	}
        public function executeNewMobLogin(sfWebRequest $request)
        { 
        	//$loginFailedObj = new LOGIN_FAILED1;
        	//$count=
        	// check
          if(isset($_SERVER['HTTP_REFERER']))
          {
            LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,'',array(LoggingEnums::REFERER => $_SERVER['HTTP_REFERER'], LoggingEnums::LOG_REFERER => LoggingEnums::CONFIG_INFO_VA, LoggingEnums::MODULE_NAME => LoggingEnums::LOG_VA_MODULE));
          }
			$this->forward("static","LogoutPage");
        }

	//new registration layer for search
	public function executeRegistrationLayer(sfWebRequest $request)
	{
		$page_arr=array("1"=>"/P/mainmenu.php",
		"2"=>"/P/contacts_made_received.php",
		"3"=>"/P/viewprofile.php?ownview=1",
		"5"=>"/profile/dpp",
		"4"=>"/P/mem_comparison.php",
		"6"=>"/search/partnermatches",
		"7"=>"/search/reverseDpp",
		"8"=>"/search/twoway",
		"9"=>"/P/contacts_made_received.php?page=eoi&filter=R",
		"10"=>"/P/contacts_made_received.php?page=accept&filter=A",
		"11"=>"/P/contacts_made_received.php?page=visitors&filter=R",
		"12"=>"/P/contacts_made_received.php?page=photo&filter=R",
		"13"=>"/P/contacts_made_received.php?page=messages&filter=R",
		"14"=>"/P/contacts_made_received.php?page=viewed_contacts_by&filter=R",
		"15"=>"/P/contacts_made_received.php?page=favorite&filter=M",
		"16"=>"/P/contacts_made_received.php?page=matches&filter=R",
		"17"=>"/P/contacts_made_received.php?page=kundli&filter=R",
		"18"=>"/P/viewprofile.php?ownview=1&EditWhatNew=ContactDetails",
		"19"=>"/P/viewprofile.php?ownview=1&EditWhatNew=FamilyDetails",
		"20"=>"/social/addPhotos",
		"21"=>"/profile/dpp?EditWhatNew=Dpp_Info",
		"22"=>"/profile/dpp?EditWhatNew=Dpp_Details",
		"23"=>"/search/perform?justJoinedMatches=1");	
//		if(!array_key_exists($page,$page_arr))
//			die("ERROR#Wrong page value passed");
		$page_source=array("1"=>"L_MMENU","2"=>"L_MYCONT","3"=>"L_MYPAGE","4"=>"L_MEMPAGE","6"=>"l_MEMLK","7"=>"L_MEMLKME");
//		print_r($request->getParameterHolder()->getAll());
		$pageNo = $request->getParameter("page");
		$this->sourcePage = $request->getParameter("pageSource");
		$this->nextAction = $page_arr[$pageNo];
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$this->MtongueDropdownForTemplate = CommonFunction::generateMtongueDropdownForTemplate();
		if($loggedInProfileObj->getPROFILEID() != '')
		{
			echo "<script>$.colorbox.close();document.location.href='".$this->nextAction."';</script>";
		}
	}

	//new forgot password layer for search
	public function executeForgotPasswordLayer(sfWebRequest $request)
	{
	}
        
        public function executeForgotPassword(sfWebRequest $request)
        {
		$loginData = $request->getAttribute("loginData");
		if($loginData[PROFILEID])
		{
			//$this->redirect("/");
			echo "<script>document.location.href='/';</script>";
			die;
		}
            if($request->getParameter("success")==1)
            {
              $this->message = $request->getParameter("message"); 
              $this->setTemplate("resetLinkSent");
            }
        }
        public function executeResetPass(sfWebRequest $request) 
         {
            $this->emailStr=$request->getParameter("emailStr");
            $this->d=$request->getParameter("d");
            $this->h=$request->getParameter("h");
            if($request->getParameter("expired")==1)
                {$this->setTemplate("forgotLinkExpired");$d=1;}
            if($request->getParameter("success")==1)
                {$this->setTemplate("passSetComplete");}
            if((!$this->d||!$this->h)&&($d!=1))
                {header("Location: $SITE_URL/");die;}
        }
        public function executeChangePass(sfWebRequest $request) {
            $loginData = $request->getAttribute("loginData");
            $pObj = LoggedInProfile::getInstance();
            $pObj->getDetail($loginData['PROFILEID'], "PROFILEID","PASSWORD,EMAIL");
            $this->emailStr=$pObj->getPROFILEID();
            
        }
        public function executeDeleteOption(sfWebRequest $request) {
        	//print_r("expression");die;
            $loginData = $request->getAttribute("loginData");
            $pObj = LoggedInProfile::getInstance();
        }

        public function executeHideOption(sfWebRequest $request) 
        {
          if(MobileCommon::isAppWebView()) {
              $this->webView = 1;
          }
        }

        public function executeUnHideOption(sfWebRequest $request) 
        {
          if(MobileCommon::isAppWebView()) {
              $this->webView = 1;
          }
        }

        public function executeUnHideResult(sfWebRequest $request) 
        {
          if(MobileCommon::isAppWebView()) {
              $this->webView = 1;
          }
        }

        public function executeHideCheckPassword(sfWebRequest $request)
        {
            $pObj = LoggedInProfile::getInstance();
            $this->hideOption = $request->getParameter("hide_option");
            if(MobileCommon::isAppWebView()) {
              $this->webView = 1;
            }
        }

        public function executeHideDuration(sfWebRequest $request)
        {
            $pObj = LoggedInProfile::getInstance();
            $this->hideOption = $request->getParameter("hide_option");
            if($this->hideOption=="1")
            {
              $this->hideText = "Your profile is now temporarily hidden for ".HideUnhideEnums::OPTION1." days";
            }
            elseif ($this->hideOption=="2")
            {
              $this->hideText = "Your profile is now temporarily hidden for ".HideUnhideEnums::OPTION2." days";
            }
            elseif ($this->hideOption=="3")
            {
              $this->hideText = "Your profile is now temporarily hidden for ".HideUnhideEnums::OPTION3." days";
            }
            
            if(MobileCommon::isAppWebView()) {
              $this->webView = 1;
            }
        }

        public function executeDeleteReason(sfWebRequest $request) {
        	//echo "string";die;
            $loginData = $request->getAttribute("loginData");
            $pObj = LoggedInProfile::getInstance();
            $this->deleteOption=$request->getParameter("delete_option");
           // print_r($this->deleteOption);die;
            if($this->deleteOption=="2")
            	$this->deleteText = "Please write name of website";
            elseif ($this->deleteOption=="4") {
            	$this->deleteText = "Kindly specify reason for your dissatisfaction";
            }
            elseif ($this->deleteOption=="5") {
            	$this->deleteText = "Kindly specify your reason";
            }
            else
            	$this->deleteText = "Kindly specify the source";
        }
        public function executePassCheck(sfWebRequest $request) {
            $loginData = $request->getAttribute("loginData");
            $pObj = LoggedInProfile::getInstance();
            $this->phoneNum =  $pObj->getPHONE_MOB();
            $this->showOTP = $this->phoneNum ? 'Y' : 'N';
            $this->isd =  $pObj->getISD();
            $this->deleteReason=$request->getParameter("delete_reason");
            $this->deleteOption=$request->getParameter("delete_option");
            $this->successFlow=$request->getParameter("successFlow");
        }
        
        public function executeSettings(sfWebRequest $request){
            
            $loginData = $request->getAttribute("loginData");
			if($loginData['PROFILEID'])
			{
				$this->loggedIn=1;
                // show hide profile
                $this->hide = 1;
                if($loginData['ACTIVATED'] == 'H')
                {
                    $this->hide = 0;
                }
        $notificationObj = new NotificationConfigurationFunc();
        $toggleOutput = $notificationObj->showNotificationToggleLayer($loginData['PROFILEID']);
        $this->showNotificationBox = $toggleOutput["showToggleLayer"];
        $this->notificationStatus = $toggleOutput["notificationStatus"];
        if($this->notificationStatus!="Y")
          $this->notificationStatus="";
        unset($toggleOutput);
        unset($notificationObj);
				/*$notificationObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
        $channel = MobileCommon::isMobile()?"M":"D";
				$response = $notificationObj->checkForRegisteredUser($loginData['PROFILEID'],$channel,"*");
				if($response)
        {
                    $browserCheck = new BrowserCheck();
                    $browserArr = $browserCheck->getBrowser();
                    $version = explode(".",$browserArr['version']);
                    $name = $browserArr['name'];
                    if($version[0] >= 44 && preg_match('/Chrome/i',$name))
                    {
                        $this->showNotificationBox = 1;
                        if($response[0]["ACTIVATED"] == "Y")
                            $this->notificationStatus = "Y";
                    }
				}*/
			}
			else
				$this->loggedIn=0;
        }
  /* this action is used by the color box to display the layer
   * also it is called for tracking when user hits any of the button*/
  public function executeCriticalActionLayerDisplay(sfWebRequest $request) {
    $layerToShow = $request->getParameter("layerId");
    if($layerToShow==9)
    {
           $profileId=LoggedInProfile::getInstance()->getPROFILEID();
           $nameData=(new NameOfUser())->getNameData($profileId);
           $this->nameOfUser=$nameData[$profileId]['NAME'];
           $this->namePrivacy=$nameData[$profileId]['DISPLAY'];

    }
    $layerData=CriticalActionLayerDataDisplay::getDataValue($layerToShow);
    
    $this->layerId = $layerData[LAYERID];
    $this->titleText = $layerData[TITLE];
    $this->contentText = $layerData[TEXT];
    $this->subText = $layerData[SUBTEXT];
    $this->button1Text = $layerData[BUTTON1];
    $this->button2Text = $layerData[BUTTON2];
    $this->contentTextNEW = $layerData[TEXTNEW];
    $this->button1TextNEW = $layerData[BUTTON1NEW];
    $this->button2TextNEW = $layerData[BUTTON2NEW];
    $this->action1 = $layerData[ACTION1];
    $this->action2 = $layerData[ACTION2];
    $this->primaryEmail = LoggedInProfile::getInstance()->getEMAIL();
    $this->subtitle = $layerData[SUBTITLE];
    $this->textUnderInput = $layerData[TEXTUNDERINPUT];
    if($this->layerId==18)
    {
          include_once(sfConfig::get("sf_web_dir"). "/P/commonfile_functions.php");
          $this->chosenJs=getCommaSeparatedJSFileNames(array('jspc/utility/chosen/chosen_jquery','jspc/utility/chosen/docsupport/prism'));
          $this->chosenCss='css/'.getCssFileName('jspc/utility/chosen/chosen_css').'.css';
   }
    if($this->layerId==19)
     {    
        
            $this->discountPercentage = $request->getParameter('discountPercentage');
            $this->discountSubtitle  = $request->getParameter('discountSubtitle');
            $this->startDate  = $request->getParameter('startDate');
            $this->oldPrice = $request->getParameter('oldPrice');
            $this->newPrice = $request->getParameter('newPrice');
            $this->time = floor($request->getParameter('time')/60);
     }
    // print_r($this->startDate.'---'.)
    $this->setTemplate("criticalActionLayer");
  }


  //// this function redirects the user to the page for the respective CAL Layer when the response of the user is to fill the details i.e. 'Yes Sure' button click on the layer
// used for JSMS and JSPC for redirection
public function executeCALRedirection($request){
      ob_start();
      sfContext::getInstance()->getController()->getPresentationFor("common", "criticalActionLayerTracking");
      ob_end_clean(); 
      $loggedInProfileObj = LoggedInProfile::getInstance();
      $profileid=$loggedInProfileObj->getPROFILEID();
      $profileid=  intval($profileid);
      $layerToDisplay=$request->getParameter("layerR");
      if($request->getParameter("button")=='B1') {
        if(MobileCommon::isNewMobileSite())
        $actionUrl=CriticalActionLayerDataDisplay::getDataValue($layerToDisplay,'JSMS_ACTION1');
      else 
        $actionUrl=CriticalActionLayerDataDisplay::getDataValue($layerToDisplay,'ACTION1');
      }
      
      if(($request->getParameter("button")=='B2') && MobileCommon::isNewMobileSite()) {
        $actionUrl=CriticalActionLayerDataDisplay::getDataValue($layerToDisplay,'JSMS_ACTION2');
      }

      if ($actionUrl=="/profile/viewprofile.php") {
        $profileChecksum = JsAuthentication::jsEncryptProfilechecksum($profileid);  
        $actionUrl.= "?profilechecksum=".$profileChecksum."&EditWhatNew=PMF";
      }
      $siteurl=  JsConstants::$siteUrl;
      if ($actionUrl) {
        header("Location: $siteurl$actionUrl");

      }
      die;
    }

    //Logout page
  public function executeLogoutPage(sfWebRequest $request)
  {
    if($request->getcookie('loginAttemptNew'))
    {
    	
        		$this->captchaDiv=1;
        	}
        	else
        		$this->captchaDiv=0;
        	//print_r($this->captchaDiv);die;
    
        $loginData = $request->getAttribute("loginData");
		if($loginData[PROFILEID])
		{
			$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj();
			$authenticationLoginObj->logout($loginData[PROFILEID]);
			
		}
    // log referer
    if(isset($_SERVER['HTTP_REFERER']))
    {
      LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,'',array(LoggingEnums::REFERER => $_SERVER['HTTP_REFERER'], LoggingEnums::LOG_REFERER => LoggingEnums::CONFIG_INFO_VA, LoggingEnums::MODULE_NAME => LoggingEnums::LOG_VA_MODULE));
    }
    if(MobileCommon::isMobile() || MobileCommon::isDesktop()==true)  
    {
       //For JPSC/JSMS, disable notifications
        $channel = MobileCommon::isMobile()?"M":"D";
        $registrationIdObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
        $registrationIdObj->updateNotificationDisableStatus($loginData[PROFILEID],$channel,'Y');
        unset($registrationIdObj);
    }

		if($request->getParameter("fromSignout"))
			$this->fromSignout=1;
		else
			$this->fromSignout=0;
		if(MobileCommon::isMobile() && $request->getParameter("homepageRedirect")){
				$this->getResponse()->addMeta('title', "Matrimony, Marriage, Matrimonial Sites, Match Making");
				$this->getResponse()->addMeta('description', "Most trusted Indian matrimonials website. Lakhs of verified matrimony profiles. Search by caste and community. Register now for FREE at Jeevansathi.com");
			}
		else{
			$this->getResponse()->addMeta('title', "Logout - Jeevansathi");
			$this->getResponse()->addMeta('description', "Logout - Jeevansathi");
		}
    if(MobileCommon::isMobile())
      {
		  $this->getResponse()->addMeta('theme-color', "#6b6b6b");
	$this->getResponse()->setSlot("optionaljsb9Key",Jsb9Enum::jsMobloginPageUrl);
	$this->PREV_URL=$this->getRequestURI();
	$this->SITE_URL=sfConfig::get("app_site_url");
        if(MobileCommon::isNewMobileSite()){

          include_once(sfConfig::get("sf_web_dir"). "/P/commonfile_functions.php");
          $this->hamJs='js/'.getJavascriptFileName('jsms/hamburger/ham_js').'.js';
          $this->hamCss='css/'.getCssFileName('jsms/hamburger/ham_css').'.css';   
          $this->cssArray = getCommaSeparatedCSSFileNames(array(
            'jsms/common/commoncss',
            'jsms/common/errorBar',
            'jsms/common/fonts',
            'jsms/common/mediaquery',
            'jsms/common/jsmsApp_promo_css',
            'rippleEffectCommon_css'));
          $request->setAttribute('JSArray',getCommaSeparatedJSFileNames(array(
              'modernizr_p_js',
              'tracking_js',
              'jsms/common/CommonFunctions',
              'jsms/common/scrollTo',
              'jsms/common/urlParamHandling',
              'app_promo_js',
              'commonMob',
              'jsms/common/touchswipe_js',
              'jsms/common/disableScroll_js',
              'jsms/common/history_js',
              'commonExpiration_js',
              'rippleEffectCommon_js',
              'common_comscore_js')));
            $request->setAttribute('singleJs',getCommaSeparatedJSFileNames(array('jsms/login/newMobLogin_js')));
              
            $request->setAttribute('mobLogoutPage','Y');
            $this->setTemplate("newMobLogin");
            if ($request->getParameter('regMsg')=='Y')   
          $this->showRegisterMsg='Y';
              }
        else
        {
            $this->to_do=$request->getParameter("to_do");
            $this->setTemplate("mobilelogin");
        }
      }
    else
    {
		
    /*if( isset($_COOKIE['AUTHN']) )
    {
      $temp = $this->explode_assoc('=',':',$_COOKIE['AUTHN']);
      $auth = new JsAuthentication();
      $checksum = $auth->js_decrypt($temp['ID']);
    }*/

    //For mail link to directly pass to the requested URL
    if($request->getParameter("From_Mail")=='Y') $this->MAIL="Y";

    if($_SERVER['REQUEST_METHOD']=="POST")
    {
      if($_POST['METHOD']=="GET")
      {
        $this->METHOD= "GET";
        $this->CHECKSUM=$_POST["checksum"];
        $this->REQUESTEDURL=$_POST['REQUESTEDURL'];
        $this->RELOGIN="Y";
      }
      else
      {
        $j=0;
        foreach($_POST as $key => $value)
        {
          if($value != "")
          {
            $data[$j]["NAME"]=$key;
            if(is_array($value))
            {
              $data[$j]["VALUE"]="ARRAY";
              $i=0;
              foreach($value as $val)
                if($val != "")
                {
                  $data[$j][$i++]=$val;
                }
            }
            else
              $data[$j]["VALUE"]=$value;
            $j++;
          }
        }

        $this->ACTION=$_SERVER['REQUEST_URI'];
        $this->RELOGIN="Y";
        $this->DATA=$data;
        $this->METHOD= "POST";
      }
    }
    elseif($_SERVER['REQUEST_METHOD']=="GET")
    {
      $this->METHOD= "GET";
      $this->CHECKSUM=$_GET["checksum"];
      $this->REQUESTEDURL=$_SERVER['REQUEST_URI'];
      $this->RELOGIN="Y";
    }
    $this->chat_hide = 1;
    $this->logoutChat = 1;
    $request->setAttribute('loginData', '');
	$request->setAttribute('login', false);
	$this->setTemplate("logoutPage");
    }
  }
  public function executeLoginLayer($request)
  {
    $this->PREV_URL=$this->getRequestURI();
    $this->SITE_URL=sfConfig::get("app_site_url");
    $this->MtongueDropdownForTemplate = CommonFunction::generateMtongueDropdownForTemplate();
  }

  private function explode_assoc($glue1, $glue2, $array){
    $array2=explode($glue2, $array);
    foreach($array2 as  $val)
    {
      $pos=strpos($val,$glue1);
      $key=substr($val,0,$pos);
      $array3[$key] =substr($val,$pos+1,strlen($val));
    }
    return $array3;
  }
/*
  public function executeBeagle($request) {
    //print_r($request);

    $loggedInProfile = Profile::getInstance('',144111);
    $otherProfile = Profile::getInstance('',3809269);
	  $fields = "PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,
 * WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
    $loggedInProfile->getDetail("", "", $fields);
    $otherProfile->getDetail("", "", $fields);
    $contact = new Contacts($loggedInProfile, $otherProfile);
    $contact->setTYPE('I');
    $contact->insertContact();
    $contactHandlerObj = new ContactHandler($loggedInProfile,$otherProfile,"EOI",$contact,"I","POST");

    $initiate = new Initiate($contactHandlerObj);

  }
*/
  public function executeBeagle($request) {
    $obj = ContactsMemcache::getInstance(144111);
    echo "BEFORE:\n";
    print_r($obj->getMemcacheData());
    $obj->setAcceptedByMe(4);
    $obj->setDeclinedByMe(1);
    $obj->setAcceptedMe(2);
    $obj->setDeclinedMe(1);
    $obj->setTodayInitiatedByMe(1);
    $obj->updateMemcacheData();
    echo "AFTER:\n";
    print_r($obj->getMemcacheData());
    echo "UPDATED FIELDS:\n";
    print_r($obj->getUpdatedFields());
  }
  public function executeExceptionMessage()
  {
		$this->onlyError=sfConfig::get("OnlyError");
		$this->setLayout(false);
	}
	public function executeRedirectToOldJsms(sfWebRequest $request)
	{
		$this->getResponse()->setCookie('TO_OLD_JSMS',1,time()+60*60*24*150,"/");
		$rUrl=$request->getParameter("rUrl");
		if(!$rUrl){
				$this->redirect(JsConstants::$siteUrl);
			}
		else
			$this->redirect($rUrl);
		die;
	}
public function executeAppredirect(sfWebRequest $request)
  {
	$playstore=$request->getParameter("type");
	$channel=$request->getParameter("channel");
	if($playstore=="androidLayer")
	{
		//Seprate download url is dr for app
		if(isset($_COOKIE["JS_SOURCE"]))
		{
			$src=trim($_COOKIE["JS_SOURCE"]);
			$db=new MIS_SOURCE;
			$data=$db->getSourceFields("AURL",$src);
			if($data[AURL])
			{
				$this->redirect($data[AURL]);
				die;
			}

		}
		$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile%26utm_content%3DInterstitial_M%26utm_campaign%3DJSAA");
	}
	elseif($playstore=="iosLayer")
	{
                //Seprate download url is dr for app
                if(isset($_COOKIE["JS_SOURCE"]))
                {
                        $src=trim($_COOKIE["JS_SOURCE"]);
                        $db=new MIS_SOURCE;
                        $data=$db->getSourceFields("AURL",$src);
                        if($data[AURL])
                        {
                                $this->redirect($data[AURL]);
                                die;
                        }

                }
		$this->redirect("https://itunes.apple.com/in/app/jeevansathi/id969994186");
	}
	elseif($playstore=="androidMobFooter")
		$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile%26utm_content%3DFooter_M%26utm_campaign%3DJSAA");
	elseif($playstore=="androidMyProfile")
		$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile%26utm_content%3DMy_Profile_JSMS%26utm_campaign%3DJSAA");
	elseif($playstore=="appPromotionProfile")
	{
		$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source3Dorganic26utm_medium3Dmobile26utm_content3Dpd_pushdown26utm_campaign3DJSAA");
	}
	elseif($playstore=="jsmsHamburger")
	{
		if($channel == 'iosLayer'){
			$this->redirect("https://itunes.apple.com/in/app/jeevansathi/id969994186");		
		} else {
			$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile_site_hamburger%26utm_content%3DLP_forSMS_D%26utm_campaign%3DJSAA");
		}
	}
	elseif($playstore=="iosPromotion")
	{
		$this->redirect("https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=%{idfa}&cs=organic&cm=JSPC&cn=JSIA&cc=landingpage");
	}
	elseif($playstore=="iosPcFooter")
	{

		$this->redirect("https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=%{idfa}&cs=organic&cm=JSPC&cn=JSIA&cc=footer");
	}
	/*for sms redirection
	elseif($playstore=="AppSms")
	{//echo("asdf");die;
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if(JsCommon::checkIosPromoValid($ua))
		$this->redirect("https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=%{idfa}&cs=organic&cm=SMS&cn=JSIA&cc=SMS");
		elseif(JsCommon::checkAppPromoValid($ua))
		$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile%26utm_content%3Dsms%26utm_campaign%3DJSAA");
		else
		$this->setTemplate('appNotCompatible');	
	}*/
	elseif($playstore=="iosHamburger")
	{

		$this->redirect("https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=%{idfa}&cs=organic&cm=JSMS&cn=JSIA&cc=hamburger");
	}
	elseif($playstore=="iosMobFooter")
	{
		$this->redirect("https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=%{idfa}&cs=organic&cm=JSMS&cn=JSIA&cc=hamburger");
	}	
	elseif($playstore=="apppromotionSRPAndroid")
	{
                $this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile%26utm_content%3DSRP_M%26utm_campaign%3DJSAA");
        }elseif($playstore=="apppromotionSRPIos"){
                $this->redirect("https://itunes.apple.com/in/app/jeevansathi/id969994186?mt=8");
        }else{

		$ua = $_SERVER['HTTP_USER_AGENT']; 
        if(JsCommon::checkIOSPromoValid($ua)) 
 	           $this->redirect("https://click.google-analytics.com/redirect?tid=UA-179986-3&url=https%3A%2F%2Fitunes.apple.com%2Fin%2Fapp%2Fjeevansathi%2Fid969994186%3Fmt%3D8&aid=com.infoedge.jeevansathi&idfa=%{idfa}&cs=organic&cm=SMS&cn=JSIA&cc=SMS"); 
        else 
			ValidationHandler::getValidationHandler("","Android Promotion Invalid source");
		$this->redirect("https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Ddesktop%26utm_content%3DNear_logo_D%26utm_campaign%3DJSAA");
	}

	die;
  }
  public function executeTrackinterstitial($request)
  {
	$db=new MIS_TRACK_INTERSTITIAL();
	$this->loginData=$request->getAttribute("loginData");
	$profileid=$this->loginData[PROFILEID];
	if(!$profileid)
		$profileid=$request->getParameter("randUser")?$request->getParameter("randUser")*-1:0;
	$profileid=$profileid?$profileid:0;
		
	$date=date("Y-m-d");
	$db->insert($date,$profileid);
	header("Content-Type:image/jpeg");
	readfile("$IMG_URL/profile/ser4_images/zero.gif");
	die;
  }





  public function executeVerifyEmail($request)
  {

  $loggedInProfile=LoggedInProfile::getInstance();
  $profileid=$loggedInProfile->getPROFILEID();
  $UIDParam=$request->getParameter('EmailUID');
  $changeLog=new NEWJS_EMAIL_CHANGE_LOG();
  $row=$changeLog->getLastEntry($profileid);
  $emailUID=$row['ID'];
  if($emailUID!=$UIDParam){
  header("Location: $SITE_URL/static/logoutPage?fromSignout=1");
  die;
  }
    
  else if($loggedInProfile->getVERIFY_EMAIL()!='Y')
    {   
      $paramArr=array('VERIFY_EMAIL'=>'Y');
      JPROFILE::getInstance('')->edit($paramArr, $profileid, 'PROFILEID');
      $changeLog->markAsVerified($profileid,$loggedInProfile->getEMAIL());
  
    }
    if(MobileCommon::isMobile())
      $this->setTemplate('jsmsEmailVerified');
    else{
       $this->setTemplate('jspcEmailVerified'); 
    }
  } 



  public function executeVerifyAlternateEmail($request)
  {

  $loggedInProfile=LoggedInProfile::getInstance();
  $profileid=$loggedInProfile->getPROFILEID();
  $UIDParam=$request->getParameter('EmailUID');
  $changeLog=new NEWJS_ALTERNATE_EMAIL_LOG();
  $row=$changeLog->getLastEntry($profileid);
  $emailUID=$row['ID'];
  if($emailUID!=$UIDParam){
  header("Location: $SITE_URL/static/logoutPage?fromSignout=1");
  die;
  }

  else if($row['STATUS']!='Y')
    {   
        $paramArr=array('ALT_EMAIL_STATUS'=>'Y');
        $contactObj=new ProfileContact();
        $contactObj->update($profileid,$paramArr);
        $changeLog->markAsVerified($profileid);
  
    }
    if(MobileCommon::isMobile())
      $this->setTemplate('jsmsEmailVerified');
    else{
       $this->setTemplate('jspcEmailVerified'); 
    }
  } 



  public function executeAppPromo($request)
  {
  	$mobile = $request->getParameter("mobile");
  	$this->mobile=$mobile;
  	$source=$request->getParameter("page");
  	if($source=="App")
  	$this->setTemplate("AppPromo");
  	elseif($source=="IOS")
  		$this->setTemplate("AppPromoIOS");
  	/*var_dump($mobile);
	if($mobile)
	{
		$PromoSmsObj= new sms_PromoSms("newjs_master");
		$count = $PromoSmsObj->getCount($mobile);
		if(!$count)
			$count=0;
		if($count<10){
				$message="Dear User, Thank you for showing interest in the top rated Jeevansathi App. Visit : ".sfConfig::get("app_site_url")."/SMS-App and download the app for FREE.";
					include_once(sfConfig::get("sf_web_dir"). "/classes/SmsVendorFactory.class.php");
					$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
                    $xmlResponse = $smsVendorObj->generateXml(rand(10,10000),$mobile,$message);
                    $smsVendorObj->send($xmlResponse,"transaction");
                    $count++;
				}
		else{
			$this->limit="limit";
		}
				//$source="App";
				//print_r($count);
				if($count==1)
				$PromoSmsObj->Insert($mobile,$count,$source);
				else
					$PromoSmsObj->Update($mobile,$count);
	}*/
  	//$this->setTemplate("AppPromo");

  }
  
  public function executeGetFieldData($request)
  {
	 // sleep(10);
	  $loggedInProfileObj = LoggedInProfile::getInstance(); 
	  if($loggedInProfileObj->getPROFILEID())
	  $loggedInProfileObj->getDetail($loggedInProfileObj->getPROFILEID(),"PROFILEID","*");
	  if(!$request->getParameter("actionCall"))
	    $this->getResponse()->setContentType('application/json');
	  $l=strtolower($request->getParameter("l"));
	  $k=strtolower($request->getParameter("k"));
	  if($l)
	  {
		  $outData = array();
		  $arrKeys = explode(',',$l);
		  
		  foreach($arrKeys as $key=>$val)
		  {
			  if($val !== "reg_caste_" && $val!=="reg_city_")
				$outData[$val] = $this->getFieldMapData($val);
			  else//As in case of reg_caste_ , we are getting array of caste as per religion for optimising calls
			  	$outData = array_merge($outData,$this->getFieldMapData($val));
        //this part was added to remove religion "Others" from Registration in JSMS
      if(MobileCommon::isMobile() && $val=="religion")
      {
        foreach($outData["religion"] as $k1=>$v1)
        {
          foreach($v1 as $k2=>$v2)
          {
            if(strpos($v2[8], RegistrationEnums::$otherText) !== false)
            {
              unset($outData["religion"][$k1][$k2]);
            }
          }
        }        
      }
			if($val=="family_income")
			{
				$optionalArr[0] = array("0"=>array("0"=>"Select"));
				foreach($outData['family_income'] as $x=>$y)
				{
					$mergedArr = array_merge($optionalArr,$y);
					$outData['family_income'][$x]=$mergedArr;
				}
			}
			if($val=="state_india" || $val=="native_country")
			{
				$optionalArr = array("0"=>array("0"=>"Select"));
				$mergedArr = array_merge($optionalArr,$outData[$val][0]);
				$outData[$val][0]=$mergedArr;
			}
			if($val=="reg_city_jspc")
			{
				$output = $outData;
				unset($outData);
				$outData[51]=$output;
                                $Arr[128][0]=FieldMap::getFieldLabel("city_usa",'',1);
                                $i=0;
                                foreach($Arr[128] as $key=>$val)
                                {
                                        foreach($val as $k=>$v)
                                                $outData[128][]=array($i=>array($k=>$v));
                                        $i++;
                                }
			    $outData['128'][][0] = array('0'=>'Others');
			}
		  }
		  echo json_encode($outData);
	  }
	  else if($k)
	  {
			$output = $this->getFieldMapData($k);
			if($k=="reg_city_jspc")
			{
				$outData = $output;
				unset($output);
				$output[51]=$outData;
				$Arr[128][0]=FieldMap::getFieldLabel("city_usa",'',1);
				$i=0;
				foreach($Arr[128] as $key=>$val)
				{
					foreach($val as $k=>$v)
						$output[128][]=array($i=>array($k=>$v));
					$i++;
				}
			    $output['128'][][0] = array('0'=>'Others');
			}
			if($k=="family_income")
			{
                                $optionalArr[0] = array("0"=>array("0"=>"Select"));
                                foreach($output as $x=>$y)
                                {
                                        $mergedArr = array_merge($optionalArr,$y);
                                        $output[$x]=$mergedArr;
                                }
			}
                        if($k=="state_india" || $k=="native_country")
                        {
                                $optionalArr = array("0"=>array("0"=>"Select"));
                                $mergedArr = array_merge($optionalArr,$output[0]);
                                $output[0]=$mergedArr;
                        }
		  echo json_encode($output,JSON_FORCE_OBJECT);
	  }	
	  
	  return sfView::NONE;
  }

  public function executeKnowYourCustomer()
  {
  	$this->setTemplate("knowYourCustomer");
  }
  
	private function getFieldMapData($szKey)
	{
		$k = $szKey;    
    if(strpos($k, 'p_') !== false)
    {
      $forDpp = 1; //This has been added so as to remove Select from the output where not required
    }
		$output = "";
		if($k=="relationship" || $k=="relation")
		{
			$output=$this->getField("relationship_edit");
		}
                if($k == "relationship_reg")
                  $output=$this->getField("relationship");
		if($k=="country_res" || $k=="p_country")
		{
		$output=$this->getCountry($k);
		}
		if($k=="city_res")
		{
		$output=$this->getCity();
		}
    if($k=="country_res_jspc")
		{
		$output=$this->getJSPCDppCountry(0);
		}
    if($k=="native_country"){
    $output=$this->getNativeCountry();  
    }
		if($k=="city_res_jspc")
		{
		$output=$this->getJspcCity_Edit();
		}
                if($k=="p_city")
                $output=$this->getCityState();
    if($k=="dpp_city")
    {
    $output=$this->getJSPCDppCity(0);
    }
    if($k=="dpp_country")
    {
    $output=$this->getJSPCDppCountry(0);
    }
		if(in_array($k,array("hobbies_language","hobbies_hobby","hobbies_interest","hobbies_music","hobbies_book","hobbies_dress","hobbies_cuisine","hobbies_sports","hobbies_movie")))
		{
		$output=$this->getHobby($k);
		}

		if($k=="p_occupation")
		$k="occupation";
    if($k=="p_occupation_grouping")
      $k="occupation_grouping";
		if($k=="p_religion")
		$k="religion";
		if($k=="p_manglik")
		$k="manglik";
		if($k=="manglik")
                $output=  $this->removeDontKnowManglik();              
		if($k=="p_height" || $k=="height")
		$k="height_without_meters";
		if($k=="p_age")
		$k="age";
		if($k=="p_diet" || $k=="diet")
		$output=$this->getField("diet");
		if($k=="p_smoke" || $k=="smoke")
		$output=$this->getField("smoke");
		if($k=="p_drink" || $k=="drink" )
		$output=$this->getField("drink");
		if($k=="p_complexion")
		$output=$this->getField("complexion");
		if($k=="p_btype")
		$output=$this->getField("bodytype");
		if($k=="handicapped" || $k=="p_challenged")
		$output=$this->getField("handicapped_mobile");
		if($k=="p_nchallenged")
		$k="nature_handicap";

		$fieldMapLib=array("horoscope_match","family_values","family_type","family_status","rashi","nakshatra", "degree_ug", "degree_pg", "occupation", "occupation_grouping","complexion","thalassemia","hiv","religion",'mstatus','children','height_without_meters','namaz','maththab','zakat','fasting','umrah_hajj','quran','sunnah_beard','sunnah_cap','hijab','working_marriage','nature_handicap',"height_json","open_to_pet","own_house","have_car","rstatus","blood_group","hiv_edit","state_india","spreading_gospel","offer_tithe","read_bible","baptised","amritdhari","cut_hair","trim_beard","wear_turban","clean_shaven","parents_zarathushtri","zarathushtri","work_status","going_abroad","hijab_marriage","sunsign","astro_privacy","number_owner_male","number_owner_female","number_owner_male_female","stdcodes","id_proof_type","degree_grouping_reg","addr_proof_type");

		if(in_array($k,$fieldMapLib))
		$output=$this->getField($k);

		if($k=="age")
		$output=$this->getAge();
		if($k=="btype")
		$output=$this->getField("bodytype");
		if($k=="p_mstatus")
		$output=$this->getField("mstatus");
		if($k=="p_havechild")
                    $output=$this->getField("children");
                if($k=="parent_city_same")
		{
		$output=$this->getField("live_with_parents");
		}
		if($k=="family_back")
		{
		$output=$this->getField("family_background");
		}
		if($k=="mother_occ")
		{
		$output=$this->getField("mother_occupation");
		}
		if($k=="family_income" || $k=="income")
		{
		//$rs=2;
		//if($loggedInProfileObj->getCOUNTRY_RES()==51)
		//$rs=1;
		$output=$this->getIncome($rs);

		}
		if($k=="p_income_rs")
		{
		$output=$this->getPIncome(1);
		}
		if($k=="p_income_dol")
		{
		$output=$this->getPIncome();
		}
		if(in_array($k,array("t_brother","t_sister")))
		{
		$output=$this->getSibling($k);
		}
		if(in_array($k,array("m_brother","m_sister")))
		{
		$output=$this->getSiblingMar($k);
		}
		if($k=="edu_level_new" || $k=="p_education")
		$output=$this->getEduLevelNew();

		if($k=='caste')
		$output=$this->getCaste();
    if($k=='caste_jspc')
      $output=$this->getJspcCaste();
		if($k=="sect")
		$output=$this->getSect();
    if($k=="sect_jspc")
		$output=$this->getJspcSect();
		if($k=="p_caste" || $k=="p_sect")
		$output=$this->getCaste(1);
		if($k=="p_caste_jsms" || $k=="p_sect_jsms")
		$output=$this->getNonOtherCaste();

		if($k=="mtongue")
			$output=$this->getMtongue();
		if($k=="p_mtongue")
			$output=$this->getMtongue("1");
		if($k=="time_to_call_start" || $k=="time_to_call_end")
		$output=$this->getTimeToCall();
		if(stristr($k,'reg_caste_'))
		{
			if($k === 'reg_caste_')
			{
				$arrAllowedCaste = array('1','2','3','4','9');
				$i = 0;
				while($i<5)
				{
					$key = $k.$arrAllowedCaste[$i].'_';
					$output[$key] = $this->getRegCaste($key);
                                        if($i==1){
                                            unset($output[$key][2]);
                                        }
					++$i;
				}
			}
			else
			{
				$output= $this->getRegCaste($k);
			}
		}
		if(stristr($k,'reg_city'))
		{
				$output = $this->getNativeCity();
		}
		if($k=="isd")
		{
			$output= $this->getISDCode();
		}
		if($k=="reg_mstatus")
		{
			$output = $this->getRegMStatus();
		}
        if($k==="reg_mtongue")
        {
            $output = $this->getRegMtongue();
        }
    if($k==="height_jspc"){
      $output = $this->getJspcHeight();
    }
    if($k=="native_city")
		{
		$output=$this->getNativeCity();
		}
    if(strstr($k,"cover_photo")){
        $output=$this->getCoverPhoto();
    }
    if(strstr($k,"cover_photo_categories")){
        $output=$this->getCoverPhotoCategories();
    }
    
    if(strstr($k,"maththab_jspc")){
        $output=$this->getMaththab();
    }
    $arrNumberOwner = array('mobile_number_owner','alt_mobile_number_owner','phone_number_owner');
    if(in_array($k,$arrNumberOwner)){
      $output=$this->getOwnerNumber();
    }
    
    if(strstr($k,"stdcodes")){
      $output = $this->getSTDCode();
    }
if($k=="state_res")
{
			$output = $this->getJspcState();
}
    if($k=="native_state_jsms")
		{
			$output = $this->getJsmsNativeState();
		}
    if($k=="jspc_state")
		{
			$output = $this->getJspcState();
		}
    if($k=="native_country_jsms")
		{
			$output = $this->getJsmsNativeCountry();
		}
    if($forDpp) //To remove Select from fields where it is not required
    {
      if($output[0][0][0] == DPPConstants::$removeLabelFromDpp || $output[0][0][S0] == DPPConstants::$removeLabelFromDpp)
        unset($output[0][0]);
    }
    
		return $output;
	}
  
  private function getAge()
  {
	  for($i=18;$i<=70;$i++)
		$arr[$i]=$i;
		$Arr[0]=array($arr);
	  //$Arr[0]=array(array("0:00 AM","1:00 AM","2:00 AM","3:00 AM","4:00 AM","5:00 AM","6:00 AM","7:00 AM","8:00 AM","9:00 AM","10:00 AM","11:00 AM","12:00 PM","1:00 PM","2:00 PM","3:00 PM","4:00 PM","5:00 PM","6:00 PM","7:00 PM","8:00 PM","9:00 PM","10:00 PM","11:00 PM"));
	  return $Arr;
  }
  private function getTimeToCall()
  {
	  $temp=array("12:00 AM","1:00 AM","2:00 AM","3:00 AM","4:00 AM","5:00 AM","6:00 AM","7:00 AM","8:00 AM","9:00 AM","10:00 AM","11:00 AM","12:00 PM","1:00 PM","2:00 PM","3:00 PM","4:00 PM","5:00 PM","6:00 PM","7:00 PM","8:00 PM","9:00 PM","10:00 PM","11:00 PM");
	  foreach($temp as $key=>$val)
		$Arr[0][]=array(str_replace(":00","",$val)=>$val);
	  //$Arr[0]=array();
	  
	  return $Arr;
  }
  
	
  private function getMtongue($getHindiAll="0")
  {
	  $mregion=FieldMap::getFieldLabel("mtongue_region_label",'',1);
	  $mtongueArr=FieldMap::getFieldLabel("community_small",'',1);
	  
	  $mtongueregion=FieldMap::getFieldLabel("mtongue_region",'',1);
	 
	  $i=0;
	  foreach($mregion as $key=>$val)
	  {
		  $Arr[$i]=array("-1"=>$val);
		  $i++;
		  $arr=explode(",",$mtongueregion[$key]);
		  if($getHindiAll=="1")
		  {
				if($key=='4')
				{
					$hindiAllVal = implode(FieldMap::getFieldLabel("allHindiMtongues","",1),",");
					
					$hindiAll = array($hindiAllVal=>"Hindi- All");
					
					$Arr[$i++] = $hindiAll ;
					
				}
			}
		  foreach($arr as $kk=>$vv)
		  {
			  $Arr[$i]=array($vv=>$mtongueArr[$vv]);
			  $i++;
		  }
	  }
	  return array(0=>$Arr);
  }
  private function getSect()
  {
	   $arr=FieldMap::getFieldLabel("religion",'',1);
	 
	  foreach($arr as $key=>$val)
	  {
		   $casteArr=FieldMap::getFieldLabel("sect_".strtolower($val),'',1);
		   $k=0;
		   foreach($casteArr as $kk=>$v)
			{
		  
				$arr[$k]=array($kk=>preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$casteArr[$kk]));
				$k++;
			}
		  $Arr[$key][0]=$arr;
		  
	  }
	  
		return $Arr;
		
		
		
	  
  }
  private function getCaste()
  {
	  
	  
	  $arr=FieldMap::getFieldLabel("religion_caste",'',1);
	  $casteArr=FieldMap::getFieldLabel("caste",'',1);
	  //return $casteArr;
	  $caste=$casteArr[$vv];
    
		$caste=preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$caste);
	  foreach($arr as $key=>$val)
	  {
		  $Arr[$key][0]=$this->getCasteArr(explode(",",$val),$casteArr);
		 
	  }
	  unset($Arr[2][0][2]);
		return $Arr;
  }
  private function getNonOtherCaste()
  {
          $arr=FieldMap::getFieldLabel("religion_caste",'',1);
          $casteArr=FieldMap::getFieldLabel("caste",'',1);
	  foreach(DPPConstants::$removeCasteFromDppArr as $k=>$v) 
	  {
		unset($casteArr[$v]);
	  }
          foreach($arr as $key=>$val)
          {
		$val = $this->unsetOtherCaste($val);
		$Arr[$key][0]=$this->getCasteArr(explode(",",$val),$casteArr);

          }
	return $Arr;
  }
  private function unsetOtherCaste($val)
  {
	$valArr = explode(",",$val);
	$flipArr = array_flip($valArr);
	foreach(DPPConstants::$removeCasteFromDppArr as $k=>$v) 
	{
		unset($valArr[$flipArr[$v]]);
	}
	return implode(",",$valArr);
  }
  private function getCasteArr($needleArr,$searchArr)
  {
	  $k=0;
	  foreach($needleArr as $key=>$val)
	  {
		  
		  $arr[$k]=array($val=>preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$searchArr[$val]));
		  $k++;
	  }
	  return $arr;
  }
  private function getEduLevelNew()
  {
	  $array=FieldMap::getFieldLabel("eduDppArray",'',1);
	  $i=0;
	  foreach($array as $key=>$value)
	  {
		  $Arr[$i]=array("-1"=>$key);
		  $i++;
		  foreach($value as $kk=>$vv)
		  {
			  $Arr[$i]=array($vv=>$kk);
			  $i++;
		  }
	  }
	  return array($Arr);
  }
  private function getSibling()
  {
	  $Arr[0]=array(array("0"=>"None","1"=>"1","2"=>"2","3"=>"3","4"=>"3+"));
	  return $Arr;
  }
  private function getSiblingMar()
  {
	  $arr=array("None","1","2","3","3+");
	  foreach($arr as $key=>$val)
	  {
		  if($key)
			$Arr[$key]=array(array(array_slice($arr,0,$key+1)));
	  }
	 // $Arr=$temp;
	  return $Arr;
  }
  private function getPIncome($rs)
  {
	 if($rs)
	 {
		 $arr1=FieldMap::getFieldLabel("lincome",'',1);
		 $arr2=FieldMap::getFieldLabel("hincome",'',1);
	 }
	 else
	 {
		 $arr1=FieldMap::getFieldLabel("lincome_dol",'',1);
		 $arr2=FieldMap::getFieldLabel("hincome_dol",'',1);
	 }
	 $i=0;
	 foreach($arr1 as $key=>$val)
	 {
		 if($val)
		 $Arr[0][]=array(array($key=>$val));
		 $i++;
	 }
	 foreach($arr2 as $key=>$val)
	 {
		 if($val)
		 $Arr[1][]=array(array($key=>$val));
		 $i++;
	 }
	 
	 return $Arr;
  }
  private function getIncome($rs)
  {
	  $grp=FieldMap::getFieldLabel("income_grouping_mapping",'',1);
	  $grp["2"] = "15,".$grp["2"];
	  $incomeArr=FieldMap::getFieldLabel("income_level",'',1);
	  foreach($grp as $key=>$value)
	  {
		  $arr=explode(",",$value);
		  if($key==1)
				$map=51;
		 else
				$map=128;
		  foreach($arr as $k=>$v)
		  	$Arr[$map][$k][]=array($v=>$incomeArr[$v]);
	  }
	  return $Arr;
  }
  private function getField($type)
  {
	  $arr=FieldMap::getFieldLabel($type,'',1);
	  foreach($arr as $key=>$val)
			$Arr[0][]=array($key=>$val);
	  return $Arr;
  }
  private function getHobby($type)
  {
	  $arr=HobbyLib::getHobbyLabel($type,'',1);
	  foreach($arr as $key=>$val)
			$Arr[0][]=array($key=>$val);
	  return $Arr;
  }
  private function getCountry($onlyCountry)
  {

          $Arr[0]=FieldMap::getFieldLabel("impcountry",'',1);
	  $Arr[1]=Array("-1"=>"--More");
	  $Arr[2]=FieldMap::getFieldLabel("country",'',1);
	  foreach($Arr as $key=>$val)
		  {
				foreach($val as $k=>$v)
					$output[]=array($k=>$v);
		  }
	  return array($output);
  }

  private function getCity($partnerCity="")
  {
	  $tempArray=FieldMap::getFieldLabel("topindia_city",'',1);
	  
	  $state = FieldMap::getFieldLabel("state_india",'',1);
	  $Arr[51][0]=Array();
	  $cityIndia=FieldMap::getFieldLabel("city_india",'',1);
	  foreach($state as $key=>$value)
	  {
		  unset($cityIndia[$key]);
	  }
	  $Arr[51][2]=$cityIndia;
	  unset($state);
	  if(!$partnerCity)
	  {
		  foreach($tempArray as $key=>$val)
		  {
			  $temp=explode(",",$val);
			  foreach($temp as $key=>$val)
				$topIndia[$val]=$cityIndia[$val];
			  
		  }
		  $Arr[51][0] = array_merge($topIndia,array("-1 "=>"--More"));
	  }
	  else
	  {
		  unset($Arr);
		  $Arr[51][0]=$cityIndia;
	  }
	  $Arr[128][0]=FieldMap::getFieldLabel("city_usa",'',1);
	  $i=0;
	  
	  foreach($Arr[51] as $key=>$val)
	  {
			foreach($val as $k=>$v)
				$output[51][]=array($i=>array($k=>$v));
		$i++;		
	  }
	 foreach($Arr[128] as $key=>$val)
	  {
			foreach($val as $k=>$v)
				$output[128][]=array($i=>array($k=>$v));
		$i++;		
	  } 
	  return $output;		
  }
  private function getCityState()
  {
	  $tempArray=FieldMap::getFieldLabel("topindia_city",'',1);
	  
	  $state = FieldMap::getFieldLabel("state_india",'',1);
	  $Arr[51][0]=Array();
	  $cityIndia=FieldMap::getFieldLabel("city_india",'',1);
	  foreach($state as $key=>$value)
	  {
		  unset($cityIndia[$key]);
	  }
            foreach($tempArray as $key=>$val)
            {
                    $temp=explode(",",$val);
                    foreach($temp as $key=>$val)
                          $topIndia[$val]=$cityIndia[$val];

            }
            
            $delhiNcrCities = implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1));
            $topIndia[$delhiNcrCities]=TopSearchBandConfig::$ncrLabel;
            $topIndia[TopSearchBandConfig::$mumbaiRegion]=TopSearchBandConfig::$mumbaiRegionLabel;
            
            $Arr[51][0] = array_merge($topIndia,array("-1 "=>"States"));
	    $Arr[51][1] = array_merge($state,array("-1 "=>"Cities"));
            $Arr[51][2]=$cityIndia;
            $i=0;
	  foreach($Arr[51] as $key=>$val)
	  {
			foreach($val as $k=>$v)
				$output[51][]=array($i=>array($k=>$v));
		$i++;		
	  }
	  return $output;		
  }

  private function getRegCaste($szKey)
  {
	$arrKey = explode('_',$szKey);
	if($arrKey[0] == 'reg')
	{
		$szField =  $arrKey[1];
		$szCasteValue = $arrKey[2];
		$szDependantValue = $arrKey[3];
		$request=sfContext::getInstance()->getRequest();
		$mtongue = null;
		if($szCasteValue == 1)
			$mtongue = $szDependantValue;//$request->getParameter("m");
		$this->fObj = new FieldOrder;
		$this->fObj->setDefault("impcaste",array(),"","");
		$this->fObj->setDefaultExist(1);
		$this->fObj->UpdateSelect();
		$impCasteJSon = $this->fObj->getJson();
		//print_r($impCasteJSon);
		$this->fObj=null;
		$this->fObj=new FieldOrder;
		$this->fObj->setDefault("caste",array($szCasteValue),"","");
		$this->fObj->UpdateSelect();
		$CasteJSon = $this->fObj->getJson();
		
		unset($CasteJSon[0]);
		$newJson = array();	
		
		$cnt = 0;
		if($impCasteJSon && $CasteJSon)
		{
			foreach($impCasteJSon as $key=>$val)
			{
				if($val[0]==$mtongue)
				{
					$newJson[]=array(array($val[1]=>$val[3]));
					$cnt++;
				}
			}
			if(count($newJson))
				$newJson[$cnt]=array(array('-1'=>"--MORE"));
			$cnt++;	
			foreach($CasteJSon as $key=>$val)
			{				
				$newJson[]=array(array($val[0]=>$val[1]));
				$cnt++;
			}
			return $newJson;
		}
	}
  }
	
  private function getISDCode()
  {
	$arrIsdCode = RegFields::getPageFields("isdcode","","1");
	
	foreach($arrIsdCode as $key=>$value)
	{
		$out[$key] = str_replace('+','',$value);
	}
	return $out;
  }
  
  private function getRegMStatus()
  {
	$arrMStatus =FieldMap::getFieldLabel("mstatus","",1);
    foreach($arrMStatus as $key=>$val)
			$Arr[0][]=array(array($key=>$val));
	$out["M"] = $Arr[0];
	unset($arrMStatus["M"]);
    unset($Arr);
	foreach($arrMStatus as $key=>$val)
			$Arr[0][]=array(array($key=>$val));
    $out["F"] = $Arr[0];
	return $out;
  }
  
  private function getRegMtongue()
  {
    $nmObj          = new NEWJS_MTONGUE;
    $mtongueArr     = $nmObj->getFullTableForRegistration();
    $outTemp        = array();
    $out            = array();
    $regionLabel    = FieldMap::getFieldLabel("mtongue_region_label",'',1);
    
    foreach($mtongueArr as $key=>$val)
    {
        $outTemp[$val["REGION"]][]=array($val["VALUE"]=>$val["SMALL_LABEL"]);
    }
    foreach($regionLabel as $key=>$val)
    {
        $out[] = array("-1"=>"$val");
        $out = array_merge($out,$outTemp[$key]);
    }
    unset($nmObj);
    return array("0"=>$out);
  }

  private function getRequestURI()
  {
	$url=$_SERVER['REQUEST_URI'];
	if(MobileCommon::isNewMobileSite())
	{
	$path="/";
	if(strstr($url,"/register/")||strstr($url,"common/resetPassword")|| strstr($url,"logout") || strstr($url,"login.php") || strstr($url,"login_home.php") || stristr($url,"forgotPassword") )
	        $path="/";
	else
        	$path=$_SERVER[HTTP_REFERER];

	//Check if referrel is from jeevansathi
	if(strpos($url,JsConstants::$siteUrl)===false)
	        $path="/";
	}
	else
	{
		$path=$_SERVER['REQUEST_URI'];
		if(!(strstr($url,"/register/")||strstr($url,"common/resetPassword")|| strstr($url,"logout") || strstr($url,"login.php") || strstr($url,"login_home.php") || stristr($url,"forgotPassword") ))
			if($_SERVER[HTTP_REFERER])
				$path=$_SERVER[HTTP_REFERER];
			else
				$path="/";
	}
	if(strpos($path,JsConstants::$siteUrl)===false)
        $path="/";

	return $path;
  }

  private function getJSPCDppCity($partnerCity="")
  {
    $tempArray=FieldMap::getFieldLabel("topindia_city",'',1);
    $state = FieldMap::getFieldLabel("state_india",'',1);
    $Arr['topCityIndia']=Array();
    $cityIndia=FieldMap::getFieldLabel("city_india",'',1);
    foreach($state as $key=>$value)
    {
      unset($cityIndia[$key]);
    }
    if(!$partnerCity)
    {
      foreach($tempArray as $key=>$val)
      {
        $temp=explode(",",$val);
        foreach($temp as $key=>$val)
        {
          $topIndia[$val]=$cityIndia[$val];
          unset($cityIndia[$val]);
        }
      }
      $delhiNcrCities = implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1));

      $topIndia[$delhiNcrCities]=TopSearchBandConfig::$ncrLabel;
      $topIndia[TopSearchBandConfig::$mumbaiRegion]=TopSearchBandConfig::$mumbaiRegionLabel;
      $Arr['topCityIndia'] = array_merge($topIndia,array("-1 "=>"startAlpha"));
    }
    else
    {
      unset($Arr);
      $Arr['topCityIndia']=$cityIndia;
    }
    $Arr['state']=$state;
    array_unshift($Arr['state'],"statesIndia");
    $Arr['city']=$cityIndia;
    $i=0;
    $arrAlpha = array();
    $sym = "";
    $bStartAplha = false;
    $bStateIndia = false;
    foreach($Arr as $key=>$val)
    {
      foreach($val as $k=>$v){
        if($v == "statesIndia"){
          $bStateIndia = true;
          continue;
        }
        if($v == "startAlpha"){
          $bStartAplha = true;
          continue;
        }
        if($key!='state')
        {
          $sym = strtoupper(substr($v, 0,1));
          if($bStartAplha && !in_array($sym, $arrAlpha)){
            $arrAlpha[] = $sym;
            $output[0][]=array("-1"=>$sym);  
            $i++;  
          }
        }
        if($bStateIndia)
        {
          $output[0][]=array("-1"=>"States");
          $bStateIndia= false;
        }
        $output[0][]=array($k=>$v);
        $i++;   
      }
    }
   //print_r($output);die;
    return $output;   
  }

  private function getJSPCDppCountry($partnerCountry="")
  {
    $tempArray=FieldMap::getFieldLabel("impcountry",'',1);
    $Arr[0]=Array();
    $country=FieldMap::getFieldLabel("country",'',1);
    
    if(!$partnerCountry)
    {
      foreach($tempArray as $key=>$val)
      {
          $topCountry[][$key]=$val;
          unset($country[$key]);       
      }
      $Arr[0] = array_merge($topCountry,array("-1 "=>"startAlpha"));
    }
    else
    {
      unset($Arr);
      $Arr[0]=$country;
    }

    $Arr[1]=$country;
    $i=0;
    $arrAlpha = array();
    $sym = "";
    $bStartAplha = false;
    foreach($Arr as $key=>$val)
    {
      foreach($val as $k=>$v){
        if($v == "startAlpha"){
          $bStartAplha = true;
          continue;
        }

        $sym = strtoupper(substr($v, 0,1));
        if($bStartAplha && !in_array($sym, $arrAlpha)){
          $arrAlpha[] = $sym;
          $output[0][]=array("-1"=>$sym);  
          $i++; 
        }
        if($k == 136)
          $output[0][]=array("-1"=>"");
        if(is_array($v))
          $output[0][] = $v;  
        else
          $output[0][]=array($k=>$v);
        $i++;   
      }
    }
    return $output;   
  }
  
  private function getJspcHeight(){
    $arr=$this->getField("height_json");
    
    $c=0;
    $heightOrdered = array();
    for($x=0;$x<=11;$x++) {
      $heightOrdered[0][$c++] = $arr[0][$x];
      $heightOrdered[0][$c++] = $arr[0][$x+12];
      $heightOrdered[0][$c++] = $arr[0][$x+24];
    }
    $heightOrdered[0][$c] = $arr[0][36];
    return $heightOrdered;
  }
 
  private function getJspcCity_Edit(){
    $arrCity=FieldMap::getFieldLabel("city_india",'',1);

                ksort($arrCity);
                $arrFinalOut = array();
                foreach($arrCity as $key=>$val)
                {
                        if(strlen($key)===2)
                        {
				if(array_key_exists($currentKey, $arrFinalOut))
				{
					asort($arrFinalOut[$currentKey]);
				}
				else
					$arrFinalOut[$currentKey] = array();
                        }
                        else
                        {
				$currentKey = substr($key,0,2);
                                $arrFinalOut[$currentKey][$key] = $val;
                        }
                }
		asort($arrFinalOut[$currentKey]);
	foreach($arrFinalOut as $k=>$v)
	{
		foreach($v as $kx=>$vx)
		{
			$returnArr[$k][0][]=array($kx=>$vx);
		}
                    $returnArr[$k][0][] = array('0'=>'Others');
	}
            $cityUsa = FieldMap::getFieldLabel("city_usa",'',1);
            $Arr[128][0]=FieldMap::getFieldLabel("city_usa",'',1);
            $Arr[128][0]["0"] = "Others" ;
            $i=0;
            $arrAlpha = array();
            $sym = "";
            $bStartAplha = false;
            foreach($Arr[128] as $key=>$val)
            {
                foreach($val as $k=>$v){
                    $sym = strtoupper(substr($v, 0,1));
                    if(!in_array($sym, $arrAlpha)){
                        $arrAlpha[] = $sym;
                        $returnArr[128][]=array($i=>array("-1"=>$sym));  
                        $i++; 
                    }

                    if($v === "Others"){
                        $returnArr[128][]=array($i=>array("-1"=>""));
                        ++$i;
                    }

                    $returnArr[128][]=array($i=>array($k=>$v));
                }
                $i++;		
            }
	return $returnArr;		
  }
  
  
  private function getJspcCaste(){
    $arrCaste = $this->getCaste();
    $arrAlpha = array();
    $sym = "";
    $bStartAplha = false;
    $arrOut = array();
    
    foreach($arrCaste[1][0] as $k=>$arrVal){
      foreach($arrVal as $key=>$val){
        $sym = strtoupper(substr($val, 0,1));
        if(!in_array($sym, $arrAlpha)){
          $arrAlpha[] = $sym;
          $arrOut[]=array("-1"=>$sym);  
          $i++; 
        }
        if($val === "Others"){
          $arrOut[] =array("-1"=>"");
        }
        $arrOut[] =array($key=>$val);
      }
    }
    
    $arrCaste[1][0] = $arrOut;
    unset($arrCaste[2][0][2]);
    return $arrCaste;
  }
  
  /*
   * To Get Sect AS Per religion
   */
  private function getJspcSect()
  {
	   $arr=FieldMap::getFieldLabel("religion",'',1);
	 
	  foreach($arr as $key=>$val)
	  {
		   $casteArr=FieldMap::getFieldLabel("sect_".strtolower($val),'',1);
       
       if(count($casteArr) == 0){
         continue;
       }
         
		   $k=0;
       $arrOut = array();
		   foreach($casteArr as $kk=>$v)
			{
				$arrOut[$k]=array($kk=>preg_replace('/[A-Z][a-z]{3,10}[:][ ]/',"",$casteArr[$kk]));
				$k++;
			}
		  $Arr[$key][0]=$arrOut;
		  
	  }
    
		return $Arr;  
  }
  /*
   * Function to get values from FieldMap and change format for getNativeCity
   * @page -  this contains page id  
   */
  private function getNativeCity()
  {
    $arrCity=FieldMap::getFieldLabel("city_india", '', 1);
    unset($arrCity[0]);//Remove Others
    ksort($arrCity);
    $arrFinalOut = array();
    foreach ($arrCity as $key => $val) {
      if (strlen($key) === 2) {
        if (array_key_exists($currentKey, $arrFinalOut)) {
          asort($arrFinalOut[$currentKey]);
        }

        $currentKey = $key;
        $arrFinalOut[$currentKey] = array();
      }
      else {
        $arrFinalOut[$currentKey][$key] = $val;
      }
    }
    asort($arrFinalOut[$currentKey]);
    $returnArr = array();
    foreach ($arrFinalOut as $k => $v) {
      foreach ($v as $kx => $vx) {
        $returnArr[$k][0][] = array($kx => $vx);
      }
      $returnArr[$k][0][] = array("0" => "Others");
    }
    return $returnArr;
  }
  
  //To get cover photo url corresponding to the coverphoto ids
  private function getCoverPhoto(){
    $arrAllowedPhotoId = CoverPhotoMap::getFieldLabel("valid_photo_id", "", 1);
    foreach ($arrAllowedPhotoId as $k => $v) {
        $arrOut = CoverPhotoMap::getFieldLabel($v, "", 1);
        foreach ($arrOut as $key => $val)
            $Arr[$v][$key] = $val;
    }


        return $Arr;
  }
  
  private function getCoverPhotoCategories(){
      $arrCoverPhotoCategories = CoverPhotoMap::getFieldLabel("category_map", "", 1);
      return $arrCoverPhotoCategories;
  }
  
  private function getNativeCountry(){
    $out = $this->getJSPCDppCountry(0);
    unset($out[0][2]);//Remove India
    return $out;
  }
  
  private function getMaththab(){
    
    $arrMaththab_Shia=FieldMap::getFieldLabel("maththab_shia",'',1);
    $arrMaththab_Sunni=FieldMap::getFieldLabel("maththab_sunni",'',1);
    
    foreach($arrMaththab_Shia as $key=>$val)
			$Arr[151][0][]=array($key=>$val);
    
    foreach($arrMaththab_Sunni as $key=>$val)
			$Arr[152][0][]=array($key=>$val);
    
	  return $Arr;
    
  }
  
  private function getOwnerNumber($gender){
        
    $outF = $this->getField("number_owner_female");
    $outM = $this->getField("number_owner_male");
    
    $Arr = array();
    $Arr["M"]=$outM;
    $Arr["F"]=$outF;
    
    return $Arr;
  }
  
  private function getSTDCode(){
    $stdcodeArray =RegFields::getPageFields("stdcodes",'',1);
		foreach($stdcodeArray as $key=>$val)
			$Arr[0][]=array($key=>$val);
	  return $Arr;
  }
  
  /**
   * 
   * @return type
   */
  private function getJsmsNativeState(){
    $arr=FieldMap::getFieldLabel("state_india",'',1);
    $Arr[0][] = array("0"=>"Select");
    $Arr[0][] = array("NI"=>"Outside India");
	  foreach($arr as $key=>$val)
			$Arr[0][]=array($key=>$val);
	  return $Arr;   
  }
  
  /**
   * 
   * @return type
   */
  private function getJspcState(){
    $arr=FieldMap::getFieldLabel("state_india",'',1);
	  foreach($arr as $key=>$val)
			$Arr[0][]=array($key=>$val);
	  return $Arr;   
  }
  
  /**
   * 
   * @return type
   */
   private function getJsmsNativeCountry(){
   
    $Arr[0]=FieldMap::getFieldLabel("impcountry",'',1);
    $Arr[1]=Array("-1"=>"--More");
    $Arr[2]=FieldMap::getFieldLabel("country",'',1);
		
    $output[] = array("0"=>"Select");
    $output[] = array("FI"=>"From India");
    foreach($Arr as $key=>$val)
    {
      foreach($val as $k=>$v){
        if($k != "51")
        $output[]=array($k=>$v);
      }
    }
	  return array($output);
  }
  
  /*
         * this function removes don't know value from array coming from field map
         * @return - array with don't know removed
         */
        private function removeDontKnowManglik(){
            $arr=FieldMap::getFieldLabel("manglik_label",'',1);
            foreach($arr as $key=>$val){
                if($val != "Don't know")
                        $Arr[0][]=array($key=>$val);
            }
            return $Arr;
        }
}
