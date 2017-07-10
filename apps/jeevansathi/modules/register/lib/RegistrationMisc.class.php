<?php
class RegistrationMisc{
	public static function insertInIncompleteProfileAndNames($profile){
		$dbIncompleteProfile = new NEWJS_INCOMPLETE_PROFILES();
		$id=$profile->getPROFILEID();
		$dbIncompleteProfile->insert($id);
		//InsertInto Names Tables:
		$dbNames = new NEWJS_NAMES();
		$dbNames->insert($profile->getUSERNAME());
		/* Tracking Query for the Reg Count */
		$dbRegCount = new MIS_REG_COUNT();
		$dbRegCount->insert($id, "PAGE1");
                /* Tracking channel and datetime */
		RegChannelTrack::insertPageChannel($id,PageTypeTrack::_PAGE1);
	}
    /** contactArchiveUpdate: 
     * Function  to track the contact Archives informataion
     * @param $email,$id,$ip,$phone,$country_code,$state_code,$mobile,$country_code_mob
     * @return
     *
     */
    public static function contactArchiveUpdate($profile,$ip) {
        //EMAIL
        $dbContactArchive = new NEWJS_CONTACT_ARCHIVE();
        $dbContactArchiveInfo = new CONTACT_ARCHIVE_INFO();
		$email=$profile->getEMAIL();
		$id=$profile->getPROFILEID();
		$changeid = $dbContactArchive->insert($id, "EMAIL");
		$dbContactArchiveInfo->insert($changeid, $ip, $email);
        //PHONE_RES
		$phone_res=$profile->getPHONE_RES();
		$phone_mob=$profile->getPHONE_MOB();
        if ($phone_res != '') {
            //required these varaibles:
            $phone = $profile->getISD() . "-" . $profile->getSTD() . "-" . $phone_res;
            $changeid = $dbContactArchive->insert($id, "PHONE_RES");
            $dbContactArchiveInfo->insert($changeid, $ip, $phone);
        }
        //MOBILE_RES
		//required these varaibles:
		$arch_mobile = $profile->getISD() . "-" . $profile->getPHONE_MOB();
		$changeid = $dbContactArchive->insert($id, "PHONE_MOB");
		$dbContactArchiveInfo->insert($changeid, $ip, $arch_mobile);
    }
	/** undateSugarLead updates data in sugarLead and also makes entry into offline_registration table
	 * it need profile id, username and source as input
	 * */
	public static function updateSugarLead($id,$username,$source){
		//get Lead is from cookie
		$lead = $_COOKIE['JS_LEAD'];
		$dbSugarcrmLeads = new sugarcrm_leads();
		$dbSugarcrmLeads->updateSugarRegitrationCompletiton($username, $lead);
		$row_op = $dbSugarcrmLeads->selectUsername($lead);
		$dbOfflineRegistration = new NEWJS_OFFLINE_REGISTRATION();
		$dbOfflineRegistration->insert($id, $row_op['user_name'], $source);
	}
	public static function getKeywords($reg_params){
		$genderKey=FieldMap::getFieldLabel("gender",$reg_params['gender']);
		$heightLabel = FieldMap::getFieldLabel("height", $reg_params['height']);
		$casteLabel = FieldMap::getFieldLabel("caste", $reg_params['caste']);
		$cityLabel = FieldMap::getFieldLabel("city", $reg_params['city_res']);
		//$age=CommonFunction::getAGE($reg_params['dtofbirth']);
		$keywords = addslashes(stripslashes($genderKey . "," . $age . "," . $casteLabel . "," . $heightLabel . "," . $cityLabel));
		return $keywords;
	}
	public static function updateLeadConversion($email,$leadid){
		$dbRegLead = new MIS_REG_LEAD();
		$dbRegLead->updateRegisterEmail("LEAD_CONVERSION ='Y'", $email);
		$dbMiniAjaxLead = new MIS_MINI_REG_AJAX_LEAD();
		$dbMiniAjaxLead->updateRegisterEmail($email);
		if ($leadid) {
			$dbLeadConversion = new MIS_LEAD_CONVERSION();
			$dbLeadConversion->insertConvertedLead($leadid);
		}
	}
	/* This function will create a login session after page 1 submit in desktop and page 2 submit in mobile registration
	 * */
	public static function setAuthenticationCookie(){
		include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
		$protect_obj= new protect;
		$profile=LoggedInProfile::getInstance();
		$cookies['PROFILEID']=$profile->getPROFILEID();
		$cookies['USERNAME']=$profile->getUSERNAME();
		$cookies['GENDER']=$profile->getGENDER();
		$cookies['SUBSCRIPTION']='';
		$cookies['ACTIVATED']='N';
		$cookies['SOURCE']=$profile->getSOURCE();
		$protect_obj->setcookies($cookies);
		$protect_obj->setchatbarcookie();
	}
	/** If displaying page 1, then remove all login cookies.
	 * */
	public static function removeLoginCookies(){
		include_once(sfConfig::get("sf_web_dir")."/classes/authentication.class.php");
		$protect_obj= new protect;
		$protect_obj->RemoveLoginCookies();
	}
	/* It sets jpartner default values
	 * */
	public static function setJpartnerAfterRegistration($profile,$fields=null,$casteNoBar=""){
	//DPP Auto Suggestor implemenation :
		$dppObj=new DppAutoSuggest($profile);
		$jpartnerObj=$dppObj->getJpartnerObj();		
		if($fields)
			$profileFieldArr=$fields;
		else if(!MobileCommon::isMobile())
			$profileFieldArr=array("MSTATUS","MTONGUE","CASTE","COUNTRY_RES","CITY_RES","AGE","RELIGION","HEIGHT");
		else
			$profileFieldArr=array("MSTATUS","MTONGUE","CASTE","COUNTRY_RES","AGE","RELIGION","HEIGHT");
        
        if(in_array("CASTE",$profileFieldArr) && $casteNoBar == "true")
        {
        	$key = array_search("CASTE", $profileFieldArr);
        	unset($profileFieldArr[$key]);
        }        
		  $gender=$profile->getGENDER();
		  $mstatus=$profile->getMSTATUS();		

		  if($gender=='M')
                    $jpartnerObj->setGENDER('F');
		  else
		    $jpartnerObj->setGENDER('M');
		  $jpartnerObj->setDPP('R');		  
		$dppObj->insertJpartnerDPP($profileFieldArr,$casteNoBar);
	}
	public static function setFilterReligion($rel,$mtongue){
		if($rel==1) //Hindu
		{
			if($mtongue=='27' || $mtongue=='13')
				$s="'1','4','9'";
			elseif($mtongue=='20' || $mtongue=='34')
				$s="'1','7','9'";
			else
			{
				/* Changes done as per Trac#392 
				$mt="'".$mtongue."'";
				$all_hindi="'6','7','10','19','25','28','33'";	
				if(strstr($all_hindi,"$mt"))
					$s="'1','9'";
				else
					$s="'".$rel."'";
			}
		
		}
		elseif($rel==2)
			$s="'2','5'"; //muslim
		elseif($rel==3)
			$s="'3','6'"; //christian
		elseif($rel==4)
			$s="'1','4'"; //sikh
		elseif($rel==5)
			$s="'1','2','5','6'";//parsi
		elseif($rel==6)
			$s="'1','3','6'"; //jewish
		elseif($rel==7)
			$s="'1','7'";//buddhist
		elseif($rel==9)
			$s="'1','9'"; //jain
		elseif($rel==8)
			$s=""; //others        

		return $s;*/
			}
		}
	}
	
	public static function updateAlertData($profileid,$alertArr,$cPage='R')
    {
		$dbObj = new JprofileAlertsCache();
		$dbObj->insert($profileid,$alertArr,$cPage);
	}
	
	public static function getLegalVariables($request,$default=0)
	{
		// mail and sms alert variables
        $serviceEmail = $request->getParameter("service_email");
		$serviceSms = $request->getParameter("service_sms");
		$serviceCall = $request->getParameter("service_call");
		$promoEmail = $request->getParameter("promo_email");
		$membSms = $request->getParameter("memb_sms");
		$membIvr = $request->getParameter("memb_ivr");
		$membMails = $request->getParameter("memb_mails");
		
		if($serviceEmail == '') $serviceEmail = 'U';
		if($serviceSms == '')  $serviceSms = 'U';
		if($serviceCall == '') $serviceCall = 'U';
		if($promoEmail == '') $promoEmail = 'U';
		if($membSms == '') $membSms = 'S';
		if($membIvr == '') 	$membIvr = 'U';
		if($membMails == '') $membMails = 'U';

		if($default)
		{
			 $serviceEmail = 'S';
			 $serviceSms = 'S';
                         $serviceCall = 'S';
                         //$promoEmail = 'S';
                         $membSms = 'S'; 
                         $membIvr = 'S';
                         $membMails = 'S';
		}
		
		$alertArr = array('SERVICE_EMAIL'=>$serviceEmail,'SERVICE_CALL'=>$serviceCall,'SERVICE_SMS'=>$serviceSms,'MEM_IVR'=>$membIvr,'MEM_SMS'=>$membSms,'MEM_MAILS'=>$membMails,'PROMO_MAIL'=>$promoEmail);
		return $alertArr;
	}
	public static function logServerSideValidationErrors($page,$form){
		 		$now=date("Y-m-d G:i:s");
				foreach ($form->getFormFieldSchema() as $name => $formField) 
				{ 
					$error=$formField->getError();
					if($error){
						$err_text.="$name :" . $error.",";
					}
				}
				$log=new reg_LOG_SERVER_ERROR();
				$log->logError($now,$page,$err_text);
	}
	public static function SourceTrack($request)
	{
		$source = $request->getParameter('source');
		$newsource = $request->getParameter("newsource");
		$tieup_source = $request->getParameter("tieup_source");
		$hit_source = $request->getParameter("hit_source");
		$sourceTracking = new SourceTracking($source, SourceTrackingEnum::$REG_PAGE_1_FLAG, $newsource, $tieup_source);
		if (!$request->getParameter('submit_page1'))
		{
                        $sourceTracking->SourceTracking();
			$source=$sourceTracking->getSource();
		}
		return $source;
		
	}
	public static function UpdateRegArray($request)
	{
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
		return $reg_params;
	}
	 /** preFilledAboutMe: 
     * Function  to pre Filled about me text on page4 on Mobile Site to reduce registration bounce rate
     * @param $loginProfileObject
     * @return preFilled About Me text
     *
     */
     
	public static function preFilledAboutMe()
	{
		
		//NEW LOGIC JSM-16
		$aboutMe="These are the things I enjoy, and the activities I spend time in…";
		return $aboutMe;
		
		/*
		$highestDegree=$loginProfileObj->getEDU_LEVEL_NEW();		
		$occupation=$loginProfileObj->getOCCUPATION();
		
		$age=$loginProfileObj->getAGE();
		if($loginProfileObj->getCITY_RES())
			$countryCity=$loginProfileObj->getDecoratedCity();
		else
			$countryCity=$loginProfileObj->getDecoratedCountry();
		$decoratedOccupation=$loginProfileObj->getDecoratedOccupation();
		$decoratedEducation=$loginProfileObj->getDecoratedEducation();*/
		
		
		//PREVIOUS LOGIC NOT IN USE:
		//highest degree is not others and occupation is not 'looking for job,not working,others' respectively
		/*
		if($highestDegree!=22 && $occupation !=44 && $occupation!= 36 && $occupation !=43)
		{
			$aboutMe="I am ".$age." years old currently living in ".$countryCity.". I have completed my ".$decoratedEducation." and my occupation is ".$decoratedOccupation.". I will add more information about my interests and my family soon, but until then to know more about me and my family do send me an interest or connect with me on Phone/Email.";
		}//highest degree is not others and occupation is one of 'looking for job,not working,others' respectively
		else if($highestDegree!=22 && ($occupation ==44||$occupation==36||$occupation==43))
		{
			$aboutMe="I am ".$age." years old currently living in ".$countryCity.". I have completed my ".$decoratedEducation.". I will add more information about my interests and my family soon, but until then to know more about me and my family do send me an interest or connect with me on Phone/Email.";
			
		}//highest degree is others and occupation is not 'looking for job,not working,others' respectively
		else if($highestDegree==22 && ($occupation !=44 && $occupation!=36 && $occupation!=43))
		{
			$aboutMe="I am ".$age." years old currently living in ".$countryCity.". My occupation is ".$decoratedOccupation.". I will add more information about my interests and my family soon, but until then to know more about me and my family do send me an interest or connect with me on Phone/Email.";	
		}//highest degree is others and occupation is one of'looking for job,not working,others' respectively
		else if($highestDegree==22 && ($occupation ==44||$occupation==36||$occupation==43))
		{
			$aboutMe="I am ".$age." years old currently living in ".$countryCity.". I will add more information about my interests and my family soon, but until then to know more about me and my family do send me an interest or connect with me on Phone/Email.";	
		}
		
		return $aboutMe;*/
		
	}
}
