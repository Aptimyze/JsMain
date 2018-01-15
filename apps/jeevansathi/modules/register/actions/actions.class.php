<?php

/**
 * register actions.
 *
 * @package    jeevansathi
 * @subpackage register
 * @author     Hemant Agrawal
 * @version    SVN: $Id: actions.class.php  $
 */
class registerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	 $this->form=new Page1Form(); 
  }
  
 
  /** Executes Mini Reg page
   * */
  public function executeMinireg(sfWebRequest $request)
  {
    
	$this->getResponse()->addVaryHttpHeader("User-Agent");
  
  $source = $request->getParameter('newsource');
  $request->setParameter('source',$source);
  $request->setParameter('p','7');
  $this->forward('register','customreg');
  die;

	 $source = $request->getParameter('newsource');
	 $mainheading = $request->getParameter('mainheading');
	 $reg = $request->getParameter("reg");
	 $this->source = $source ? $source : $reg[source];
	 $misObj = new MIS_MINI_REG_CUSTOMIZE();
	 $miniRegArr = $misObj->showMiniRegStory($this->source);
	 $this->STORY = $miniRegArr['STORY'];
	 $this->HEADING = $miniRegArr['HEADING'];
	 $this->COUP_IMAGE = $miniRegArr['COUP_IMAGE'];
	 $this->CAT = $miniRegArr['CATEGORY'];
	 
	 if($mainheading != '')
	 {
		$mainheading = htmlspecialchars($mainheading,ENT_QUOTES);
		$this->HEADING = preg_replace('/\[b\](.*?)\[b\]/', '<b>\1</b>',$mainheading);
		
	 }
	 
	 $this->setMetaTags($this->CAT);
	  
	 $this->form=new PageForm(array('source'=>$this->source,'phone_mob'=>array('isd'=>'+91')),array("page"=>'MR'),''); 
	 $field_array = RegEditFields::getFieldArray('MR');
	 $this->errMsg = ErrorHelp::getErrorArray($field_array);
	 $this->IS_FTO_LIVE = FTOLiveFlags::IS_FTO_LIVE;
	 
	 if ($request->isMethod('post'))
	 {
			 $this->form->bind($request->getParameter('reg'));
			 if ($this->form->isValid())
			 {
				 $this->form->updateData();
				 $this->forward("register","page1");
				 
			 }	
			 
		 }	  
  }
   /** Executes miniregLead page
   * */
  public function executeMinireglead(sfWebRequest $request)
  {
	
	$email = $request->getParameter('email');
	$flag=$this->validateLeadEmail($email);
	 if($flag)
		$email="";
	
	 $mobile = preg_replace( '/[^0-9]/', '', $request->getParameter('mobile'));
	 $source = $request->getParameter('source');
	 if($email && $mobile)
	 {
		 $misObj = new MIS_MINI_REG_AJAX_LEAD();
		 $misObj->insertLead($email,$mobile,$source);
	 }
	 die; // die after inserting the data

  }
  
   public function executeMisRegCapturelead(sfWebRequest $request)
  {
  
	$now = date("Y-m-d G:i:s");
	$email = $request->getParameter('email');
	$mobile = $request->getParameter('mobile');
	$source = $request->getParameter('source');
	$flag=$this->validateLeadEmail($email);	
	if(!$flag)
	{
		$mobile=preg_replace( '/[^0-9]/', '', $mobile );
		$paramArr=array("EMAIL"=>$email,"RELATIONSHIP"=>"","GENDER"=>"","DTOFBIRTH"=>'',"MTONGUE"=>'',"SOURCE"=>$source,"ISD"=>'',"PHONE_MOB"=>$mobile);
		$dbRegLead = new MIS_REG_LEAD();
		$leadFlag=$dbRegLead->insert($paramArr);
		if(!$leadFlag)
		{
			$lead_flag=$dbRegLead->selectValues($email);
			
			if($lead_flag=='N')
			{
				$leadid=$dbRegLead->replaceValues($paramArr);
				if($leadid)
				die($leadid);
			}
		}
		
	}
	die;
}
  /** Executes Mobile Registration page 1
   * */
  public function executeJsmbPage1(sfWebRequest $request)
  {
	  if(MobileCommon::isNewMobileSite())
	  {
		$this->forward('register','newJsmsReg');
	  }
	$this->getResponse()->addVaryHttpHeader("User-Agent");
	
	//JSB9 Mobile Tracking
	$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobRegPage1Url);
	
        $this->source = $request->getParameter('source');
        //Source variables:
        $newsource = $request->getParameter("newsource");
        $tieup_source = $request->getParameter("tieup_source");
        $hit_source = $request->getParameter("hit_source");
        $sourceTracking = new SourceTracking($this->source, SourceTrackingEnum::$REG_PAGE_1_FLAG, $newsource, $tieup_source);
		if (!$request->getParameter('jsmbPage1_submit')){ 
        		$sourceTracking->SourceTracking();
			$this->source=$sourceTracking->getSource();
			setcookie("JS_REG_ID","",0,"/");
		}
		 //per mantis 4075 Variables:
        $this->adnetwork = $request->getParameter("adnetwork");
        $this->account = $request->getParameter("account");
        $this->campaign = $request->getParameter("campaign");
        $this->adgroup = $request->getParameter("adgroup");
        $this->keyword = $request->getParameter("keyword");
        $this->match = $request->getParameter("match");
        $this->lmd = $request->getParameter("lmd");
	

	$this->operaMini=0;
	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strpos($user_agent,"opera mini")!==false) { 
		$this->operaMini=1;
	}
	        
        //For tracking purpose of cookies
        $this->OtherVariablesTracking();
        
        //IP ADDRESS
        $this->ip = CommonFunction::getIP();
        //****to check suspected registration from ip address****
        $suspected_check = CommonFunction::suspectedIP($this->ip);
		
		//canonical URL :
        $can_url="/register/page1";
		$this->getResponse()->setCanonical(sfConfig::get("app_site_url").$can_url);
		
        //Assign GroupName
        $this->assignGroupName();
	 $this->form=new PageForm(array('source'=>$this->source,'phone_mob'=>array('isd'=>'+91')),array("page"=>'MP1','request' => $request));

	 $field_array=RegEditFields::getFieldArray('MP1');
	 $this->errMsg=ErrorHelp::getErrorArray($field_array,'Mob');	 
    	RegistrationMisc::removeLoginCookies();
	 if ($request->getParameter('jsmbPage1_submit'))
	     {
			 $this->form->bind($request->getParameter('reg'));
			 if ($this->form->isValid())
			 {
                /*Code to check spammer, checking for request from same ip. Block registration if request > 5 within 1 minute if not then insert its entry into DB*/
                $dbBlockIP = new NEWJS_BLOCK_IP();
                if ($dbBlockIP->blockIP($this->ip)) die("Too many requests !");
                else $dbBlockIP->insertIP($this->ip);
                /*End of - Code to check spammer*/
                    if(!$secondary_source = $request->getParameter('secondary_source')) $secondary_source = 'S';
                    $values_that_are_not_in_form = array('SEC_SOURCE' => $secondary_source);
				 $id=$this->form->updateData('',$values_that_are_not_in_form);
				 $request->setAttribute('reg_id',$id);
				setcookie("JS_REG_ID",$id,time()+2592000,"/");
				 $this->forward('register','jsmbPage2');
			 }	
		 }	  
  }
  /** Executes Mobile Registration page 2
   * */
  public function executeJsmbPage2(sfWebRequest $request)
  {
	 //JSB9 Mobile Tracking
	 $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobRegPage2Url); 
	 
	 $formEle=$request->getParameter("reg");
	 $mob_reg_id=$request->getAttribute('reg_id')?$request->getAttribute('reg_id'):$formEle[reg_id];
	unset($formEle);
	 $this->form=new PageForm(array('reg_id'=>$mob_reg_id),array("page"=>'MP2','request' => $request)); 
	 $field_array=RegEditFields::getFieldArray('MP2');
	 $this->errMsg=ErrorHelp::getErrorArray($field_array,'Mob');	 
	 $regArray = $request->getParameter('reg');
     $relationship = $regArray['relationship'];
      //per mantis 4075 Variables:
        $this->adnetwork = $request->getParameter("adnetwork");
        $this->account = $request->getParameter("account");
        $this->campaign = $request->getParameter("campaign");
        $this->adgroup = $request->getParameter("adgroup");
        $this->keyword = $request->getParameter("keyword");
        $this->match = $request->getParameter("match");
        $this->lmd = $request->getParameter("lmd");
     $this->mtongue = $regArray['mtongue'];
	
	 if ($relationship == '2') $this->yourHeading = "your Son";
	 elseif ($relationship == '2D') $this->yourHeading = "your Daughter";
     	elseif ($relationship == '6') $this->yourHeading = "your Brother";
	elseif ($relationship == '6D') $this->yourHeading = "your Sister";
     elseif ($relationship == '4') $this->yourHeading = "your Relative";
     elseif ($relationship == '5') $this->yourHeading = "your Client";
     
     if($this->yourHeading)
		$heading = $this->yourHeading;
	 else
		$heading = "yourself";
     $response=sfContext::getInstance()->getResponse();
     $title="Jeevansathi.com - More about ".$heading;
     $response->setTitle($title);

	$this->gender = $regArray['gender'];
 	
	 if ($request->getParameter('jsmbpage2_submit'))
	     {
			 $this->form->bind($request->getParameter('reg'));
			 if ($this->form->isValid())
			 {
				 //If page is refreshed or data once submitted and seesion is already made.User will be redirected to page3.
				       $this->loginData = $request->getAttribute("loginData");
					    if($this->loginData[PROFILEID])
							        $this->forward("register","jsmbPage3");
					//Adhoc profile table(registration_page1) pdo
					 $reg_pdo=new NEWJS_REGISTRATION_PAGE1();
					$reg_id=$this->form->getValue('reg_id');
					if(!$reg_id)
						$reg_id=$_COOKIE['JS_REG_ID'];
					
					$lead_info=$reg_pdo->getLeadInfo($reg_id);
					if(!$lead_info)
						$this->forward("register","page1");

					//If page is refreshed or data once submitted in Jprofile and session is already made and no login Data recieved.User will be redirected to page3.
					$dbJprofile=new JPROFILE();			
					//$paramArr='EMAIL';
					if($lead_info["EMAIL"])	
					{
						$jProfileData=$dbJprofile->get($lead_info["EMAIL"],"EMAIL");
						if(is_array($jProfileData))
							$this->forward("register","jsmbPage3");
					}
					
					$now = date("Y-m-d G:i:s");
					$today = CommonUtility::makeTime(date("Y-m-d"));
					//required keyword variables
					$keywords=RegistrationMisc::getKeywords(array('height'=>$lead_info[HEIGHT],'gender'=>$lead_info[GENDER],'caste'=>$this->form->getValue('caste'),'dtofbirth'=>$lead_info[DTOFBIRTH]));
					//There are some variables that are to be set to some default and not part of form so then need to send as an array 
				$alertArr[SERVICE_EMAIL] = 'S'; 
				$alertArr[SERVICE_CALL] = 'S'; 
				$alertArr[SERVICE_SMS] = 'S';
				$alertArr[MEM_IVR] = 'S'; 
				$alertArr[MEM_SMS] = 'S'; 
				$alertArr[MEM_MAILS] = 'S'; 
				$alertArr[PROMO_MAIL] = 'S'; 
                
					
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
					//Calculate age
					   $age=CommonFunction::getAge($lead_info['DTOFBIRTH']);
					//to updateData function
				if(!$source)
                                        $source="hp_black";
				if($lead_info)
                    $values_that_are_not_in_form = $lead_info + array('INCOMPLETE' => 'Y', 'ACTIVATED' => 'N', 'SCREENING' => 0, 'SERVICE_MESSAGES' => $alertArr[SERVICE_EMAIL],'ENTRY_DT'=>$now,'MOD_DT'=>$now,'LAST_LOGIN_DT' => $today, 'SORT_DT'=>$now,'SOURCE'=>$source,'SHOWPHONE_MOB'=>'Y','SHOWPHONE_RES'=>'Y', 'CRM_TEAM' => $crm_team, 'PERSONAL_MATCHES' => $match_def, 'GET_SMS' => $sms_def, 'KEYWORDS' => $keywords,'AGE'=>$age,'IPADD'=>CommonFunction::getIP(),'PROMO_MAILS' => $alertArr[PROMO_MAIL]);
                        else
                                $values_that_are_not_in_form = array('INCOMPLETE' => 'Y', 'ACTIVATED' => 'N', 'SCREENING' => 0, 'SERVICE_MESSAGES' => $alertArr[SERVICE_EMAIL],'ENTRY_DT'=>$now,'MOD_DT'=>$now,'LAST_LOGIN_DT' => $today, 'SORT_DT'=>$now,'SOURCE'=>$source,'SHOWPHONE_MOB'=>'Y','SHOWPHONE_RES'=>'Y', 'CRM_TEAM' => $crm_team, 'PERSONAL_MATCHES' => $match_def, 'GET_SMS' => $sms_def, 'KEYWORDS' => $keywords,'AGE'=>$age,'IPADD'=>CommonFunction::getIP(),'PROMO_MAILS' => $alertArr[PROMO_MAIL]);
				//If page is refreshed or data once submitted in Jprofile and session is already made and no login Data recieved.User will be redirected to page3.
				$dbObj=new JPROFILE();			
				if($lead_info["EMAIL"])	
				{
					$jProfileData=$dbObj->get($lead_info["EMAIL"],"EMAIL");
					if(is_array($jProfileData))
					{
						jsException::log(" Mobile email already exists ".CommonFunction::getIP()." ".$lead_info["EMAIL"]);
						$this->forward("register","jsmbPage3");
					}
				}
			
				 $id=$this->form->updateData('',$values_that_are_not_in_form);
				
				 //Post update operations now
				//Initiate a loggedin profile object
				//TODO return loginProfile from updateData function and remove following instantiation to reduce queries
				$this->loginProfile = LoggedInProfile::getInstance();
				$this->loginProfile->getDetail($id, "PROFILEID");
				$username = $this->loginProfile->getUSERNAME();
				$request->setAttribute('username',$username);	
				$request->setAttribute('profileid',$id);	
				//create login session
				$request->setAttribute('loginData',array('PROFILEID'=>$id));	
				RegistrationMisc::setAuthenticationCookie();
				RegistrationMisc::updateAlertData($id,$alertArr,'L');
				unset($alertArr);
				//Set default jpartner values
				$mobRegObj = new NEWJS_REGISTRATION_PAGE1();
				$mobRegObj->setConverted($reg_id);
				RegistrationMisc::setJpartnerAfterRegistration($this->loginProfile);
				//added by nitesh for Source tracking to store registration caused by sources leading to home page
				if (isset($_COOKIE['JS_SOURCE_HOME'])) {
					$this->source = $_COOKIE['JS_SOURCE_HOME'];
					$sourceTracking->sourceFromHomePage($this->source, $id);
					//Now unser JS_SOURCE_HOME cookie
					setcookie("JS_SOURCE_HOME", "", time() - 3600, "/");
				}
				//end of Source tracking by nitesh
				//Contact Archive updates added by nitesh
			  	RegistrationMisc::contactArchiveUpdate($this->loginProfile,CommonFunction::getIP());
                 if ($this->source == 'onoffreg' && $_COOKIE['JS_LEAD'])
						 RegistrationMisc::updateSugarLead($id,$username,$this->source);
					//Insert in NAMES and INCOMPLETE_PROFILE table and also update MIS_REG_COUNT data
					RegistrationMisc::insertInIncompleteProfileAndNames($this->loginProfile);
                    // Mailer on Registration
                    if ('C' == $secondary_source) {
						RegistrationCommunicate::sendEmailAfterRegistrationIncomplete($this->loggedInProfile);
                    }
			$this->loggedInProfile=$this->loginProfile;	
                    // email for verification
                    $emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($this->loggedInProfile->getPROFILEID(),$this->loggedInProfile->getEMAIL());
					(new emailVerification())->sendVerificationMail($this->loggedInProfile->getPROFILEID(),$emailUID);
					////////
                    //Lead conversion update
					RegistrationMisc::updateLeadConversion($this->loginProfile->getEMAIL(),$this->leadid);
					//Everything is done now forward to page3
				 $this->forward('register','jsmbPage3');
			 }	
		/*	 else{
		 foreach($this->form->getFormFieldSchema() as $name=>$formField)
			 echo "$name :".$formField->getError();
		} */
		 }	 
  }
  public function executeJsmbPage3(sfWebRequest $request)
  {
	//JSB9 Mobile Tracking
	$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobRegPage3Url);
	
	$this->loginData = $request->getAttribute("loginData");
	if(!$this->loginData[PROFILEID])
		$this->forward("register","page1");
	 $this->form=new PageForm('',array("page" => 'MP3', 'request' => $request),array("page"=>'MP3'));
	 $field_array=RegEditFields::getFieldArray('MP3');
	 $this->errMsg=ErrorHelp::getErrorArray($field_array,'Mob');
	$this->loginProfile = LoggedInProfile::getInstance();
	$this->loginProfile->getDetail($this->loginData[PROFILEID], "PROFILEID");
	 $this->country = $this->loginProfile->getCOUNTRY_RES(); 
	 //If conuntry is not india then we need not include city field in form on page3
	 if($this->country!='51')
		 unset($this->form['city_res']);
	 $this->yourHeading = $request->getParameter('heading');
	 
	 if($this->yourHeading)
		$heading = $this->yourHeading."'s";
	 else
		$heading = "your";
				 
	 $response=sfContext::getInstance()->getResponse();
     $title="Jeevansathi.com - About ".$heading." education & work";
     $response->setTitle($title);	 
	//per mantis 4075 Variables:
        $this->adnetwork = $request->getParameter("adnetwork");
        $this->account = $request->getParameter("account");
        $this->campaign = $request->getParameter("campaign");
        $this->adgroup = $request->getParameter("adgroup");
        $this->keyword = $request->getParameter("keyword");
        $this->match = $request->getParameter("match");
        $this->lmd = $request->getParameter("lmd");
	
	
	
	 if ($request->getParameter('jsmbpage3_submit'))
	     {
	     	 $this->form->bind($request->getParameter('reg'));
			 if ($this->form->isValid())
			 {
				 $id=$this->form->updateData($this->loginData[PROFILEID]);
				 $dppObj=new DppAutoSuggest($this->loginProfile);
				 $profileFieldArr=array("OCCUPATION","CITY_RES","EDU_LEVEL_NEW");
				 $dppObj->insertJpartnerDPP($profileFieldArr);
				 //CODE ADDED BY Nitesh Sethi to capture outer variable
				if ($this->adnetwork || $this->account || $this->campaign || $this->adgroup || $this->keyword || $this->match || $this->lmd) {
					$dbTrackTieupVariable = new MIS_TRACK_TIEUP_VARIABLE();
					$dbTrackTieupVariable->insert($this->adnetwork, $this->account, $this->campaign, $this->adgroup, $this->keyword, $this->match, $this->lmd,$this->loginData[PROFILEID]);
				}	
				
				 $this->forward('register','jsmbPage4');
			 }	
/*			 else{
				 foreach($this->form->getFormFieldSchema() as $name=>$formField)
					 echo "$name :".$formField->getError();
}*/
		 } 
  }
  
  public function executeJsmbPage4(sfWebRequest $request)
  {
	 //JSB9 Mobile Tracking
	$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobRegPage4Url);
	
	$this->loginData = $request->getAttribute("loginData");
	if(!$this->loginData[PROFILEID])
		$this->forward("register","page1");
	
	$this->AboutMe=RegistrationMisc::preFilledAboutMe();
	$this->form=new PageForm(array('yourinfo'=>$this->AboutMe),array("page"=>'MP4'));

	// $this->form=new PageForm('',array("page"=>'MP4'));
	 $field_array=RegEditFields::getFieldArray('MP4');
	 $this->errMsg=ErrorHelp::getErrorArray($field_array,'Mob');
	 $this->yourHeading = $request->getParameter('heading');
	 
	 if($this->yourHeading)
		$heading = $this->yourHeading;
	 else
		$heading = "yourself";
	 
	 $response=sfContext::getInstance()->getResponse();
     $title="Jeevansathi.com - Write about ".$heading." to create profile";
     $response->setTitle($title);
	
	 if ($request->getParameter('jsmbPage4_submit'))
	     {
	     	 $this->form->bind($request->getParameter('reg'));
			 if ($this->form->isValid())
			 {
				$now = date("Y-m-d G:i:s");
				$today = CommonUtility::makeTime(date("Y-m-d"));
				$alertArr = RegistrationMisc::getLegalVariables($request);
				
				if($alertArr[SERVICE_EMAIL] == 'S') 
					$match_def = 'A';
				else
					$match_def = 'U';
				if($alertArr[SERVICE_SMS] == 'S') 
					$sms_def = 'Y';
				else 
					$sms_def = 'N';
				$values_that_are_not_in_form = array('INCOMPLETE' => 'N','ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today,'SERVICE_MESSAGES' => $alertArr[SERVICE_EMAIL],'PROMO_MAILS' => $alertArr[PROMO_MAIL], 'GET_SMS' => $sms_def,'PERSONAL_MATCHES' => $match_def);
				
				$this->loginProfile = LoggedInProfile::getInstance();
				$this->loginProfile->getDetail($this->loginData[PROFILEID], "PROFILEID");
                $this->form->updateData($this->loginData[PROFILEID],$values_that_are_not_in_form);
				$fto_action = FTOStateUpdateReason::REGISTER;
				$this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
				$id = $this->loginProfile->getPROFILEID();
				// data insertion in JPROFILE_ALERT table
				RegistrationMisc::updateAlertData($id,$alertArr,'M');
				/* Tracking Query for the Reg Count */
                
                $dbRegCount = new MIS_REG_COUNT();
                $dbRegCount->updateEntryRegPage("PAGE2", 'Y', $this->loginData[PROFILEID]);
                /* Ends Here */
                /* Lead table updated */
                $dbRegLead = new MIS_REG_LEAD();
                $dbRegLead->updateRegisterEmail("INCOMPLETE='N'", $this->loginProfile->getEMAIL());
                /* Ends Here */
				//Communicate to user through email and sms starts
				RegistrationCommunicate::sendEmailAfterRegCompletion($this->loginData[PROFILEID]);
				RegistrationCommunicate::sendSms($this->loginData[PROFILEID]);
				//Communicate to user through email and sms ends
               //Mark lead as converted 
				if ($leadid) {
					$dbLeadConversion = new MIS_LEAD_CONVERSION();
					$dbLeadConversion->updateLead($leadid);
				}
				$loginObj=AuthenticationFactory::getAuthenicationObj();
				$loginObj->loginFromReg();
				$this->forward('register','jsmbPage5');
			 }	
		 }	  
  }
  
  /**
   * executeJsmbPage5
   */
	public function executeJsmbPage5(sfWebRequest $request)
	{
		 //JSB9 Mobile Tracking
		$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobRegPage5Url);
		
		$this->loginData = $request->getAttribute("loginData");
		if(!$this->loginData[PROFILEID])
			$this->forward("register","page1");
		$this->form=new PageForm('',array("page"=>'MP5','request' => $request));
		$field_array=RegEditFields::getFieldArray('MP5');
		
		$this->SITE_URL = sfConfig::get("app_site_url");
		$this->loginProfile = LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($this->loginData[PROFILEID], "PROFILEID","RELIGION");
		//Profile Religion
		$this->RELIGION = $this->loginProfile->getRELIGION();
		//Page Title
		$this->yourHeading = $request->getParameter('heading');
		if($this->yourHeading)
			$heading = $this->yourHeading;
		else
			$heading = "your family";
		$response=sfContext::getInstance()->getResponse();
		$title="Jeevansathi.com - More about ".$heading;
		$response->setTitle($title);
		//End of page Title
		
		//Smarty Parameter
		$this->groupname=$request->getParameter('groupname');
		$this->adnetwork1=$request->getParameter('adnetwork1');
		//Reg Para
		$arrRegPara = $request->getParameter('reg');
		$this->countryDefault = $arrRegPara['native_country'];
		if($this->countryDefault =='' ||  !$this->countryDefault)
		{
			$this->countryDefault = 51;
		}
		
		if($request->getParameter('jsmbPage5_submit') && $this->loginData["PROFILEID"])
		{
			$this->form->bind($arrRegPara);
			if ($this->form->isValid()) 
			{	
                                if($this->form->getValue('familyinfo'))
					RegChannelTrack::insertPageChannel($this->loginData[PROFILEID],PageTypeTrack::_ABOUTFAMILY);
                                
				$this->form->updateData($this->loginData["PROFILEID"]);
				
				//Redirect to my jeevansathi page.
				$request->setParameter('fromReferer',0);
				if(FTOLiveFlags::IS_FTO_LIVE){
					$this->forward('fto','offer');
				}
				else{
					header("Location:/profile/viewprofile.php?ownview=1&fromReg=1&groupname=".$this->groupname."&adnetwork1=".$this->adnetwork1);
					die;
				}
			}
		}
		 
	}
  /*
   * Controller for Auto Suggest Caste 
   */
  public function executeAutoSug(sfWebRequest $request){
    $obj = new AutoSuggestCaste;
    $obj->Process($request);
    unset($obj);
		return sfView::NONE;
  }
  /**
   * Meta tags for mini reg page
   * 
   * @param $CAT
   * 
   * */
   
  private function setMetaTags($CAT)
  {
    $response = sfContext::getInstance()->getResponse();
    $desc = "Searching for $CAT brides & grooms? Register Free with Jeevansathi.com and find suitable $CAT girls & boys for $CAT matrimonials. Join Free & Add your $CAT  Matrimonial Profile Now! Find suitable Indian and NRI $CAT brides and grooms.";
    
    $title = "$CAT Matrimonials - $CAT Matrimony - $CAT Bride - $CAT Groom - $CAT Boy - $CAT Girl";
    
    $keyword = "Jeevansathi.com, $CAT matrimony, $CAT, matrimony, matrimonial, matrimonials, matrimony services, online matrimonials, Indian marriage, match making, matchmaking, matchmaker, match maker, marriage bureau , matchmaking services, matrimonial profiles, bride, groom, matrimony classified";
    
    $title = htmlspecialchars_decode($title,ENT_QUOTES);
    $response->setTitle($title);

    $desc = htmlspecialchars_decode($desc,ENT_QUOTES);
    $response->addMeta('description', $desc);

    $keyword = htmlspecialchars_decode($keywords,ENT_QUOTES);
    $response->addMeta("keywords",$keyword);
    
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
    
    public function executeCustomreg(sfWebRequest $request)
    {
		$loginData=$request->getAttribute("loginData");
		//IF User is login then redirect to site
		if(is_numeric($loginData[PROFILEID]))
		{
			$url = sfConfig::get(app_site_url);
			$this->redirect($url);
		}
		if(MobileCommon::isMobile())
                        $this->forward('register','jsmbPage1');
		$objCustomPage = new CustomRegPage;
		$newHtmlContent = $objCustomPage->ProcessRequest($request);
		//If 1 is returned then Page_id Does Not exit, then Fwd to Page1 of Register
		if(is_numeric($newHtmlContent) && $newHtmlContent == 1)
		{
			$this->forward("register", "page1");
		}
    
    $customSubmitSuccess = $request->getParameter("customSubmitSuccess");
    if(isset($customSubmitSuccess) && $customSubmitSuccess){
      $this->forward("register", "page3");
    }
		$this->myHtml = $newHtmlContent;
		//echo $newHtmlContent;
	}
	
	public function validateLeadEmail($email){
		 $flag=0;
		 $email=trim($email);	
		if($email=="")
		{
			$flag=1;
		}
		elseif(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/",$email))
		{
			$flag=1;
		}
		else
		{
			$ip = CommonFunction::getIP();
			$dbTrackDuplicate= new MIS_TRACK_DUPLICATE_EMAIL();
			$dbJprofile= new JPROFILE();
			$emailArray=$dbJprofile->get($email,"EMAIL","count(*)");
			$page="REG";
			if($emailArray["count(*)"]>0)
			{	
				$flag=2;
				$dup_email_flag='Y';
				if($page&& $flagDup!='N')
					$dbTrackDuplicate->insert($email,$page,$ip,$dup_email_flag);
			}
			else
			{
				$dup_email_flag='N';
				if($page && $flagDup!='N')
					$dbTrackDuplicate->insert($email,$page,$ip,$dup_email_flag);
			}
		}
		$part=explode("@",$email);
		if(!$flag)
		{
			if(strtolower($part[1])=="jeevansaathi.com")
				$flag=3;
			elseif(strtolower($part[1])=="jeevansathi.com")
				$flag=3;
		}
			
		if(!$flag)
		{
			$dotpos = strrpos($part[1],".");
			$middle = substr($part[1],0,$dotpos);
			$dbInvalidDomain= new NEWJS_INVALID_DOMAINS();
			if($dbInvalidDomain->selectValues($middle))
				$flag = 4;
				
		}
		return $flag;
	 }
	
	public function executeNewJsmbPage1($request)
	{
		$reg = $request->getParameter('reg');
		$trackParams = $reg['trackingParams'] ;
		unset($reg['trackingParams']);
		$trackParams = json_decode($trackParams);
		var_dump($trackParams->source);
		var_dump($trackParams);
		var_dump($reg);
		die(X);
		$regApiObj = new JsWapSite_RegApi;
		$regApiObj->regPage1($request);
	}
	
	public function executeNewJsmbPage2($request)
	{
		$regApiObj = new JsWapSite_RegApi;
		$regApiObj->regPage2($request);
	}
}
