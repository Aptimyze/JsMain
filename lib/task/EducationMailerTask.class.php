<?php

class EducationMailerTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    $this->addOptions(array(
    				new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),));

    $this->namespace        = 'cron';
    $this->name             = 'EducationMailer';
    $this->briefDescription = 'FTO Education Mailer';
    $this->detailedDescription = <<<EOF
The [EducationMailer|INFO] task get Profiles which are in D1 state and send fto education Mail to those.
Call it with:

  [php symfony EducationMailer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
  		
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);
  		$emailSenderObj = new EmailSender(MailerGroup::EDUCATION);
		$detailArr ='PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT, EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB, HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN';
		
		$today = date("Y-m-d");
		$d1day3= date("Y-m-d",JSstrToTime('-3 day',JSstrToTime($today)));
		$d1day5=date("Y-m-d",JSstrToTime('-5 day',JSstrToTime($today)));
		$count = 0;
		$profileArr1=FTOStateHandler::getProfilesInStateOnDate(FTOStateTypes::FTO_ACTIVE,'D1',$d1day3);
		$profileArr2=FTOStateHandler::getProfilesInStateOnDate(FTOStateTypes::FTO_ACTIVE,"D1",$d1day5);
		$profileArr=@array_merge($profileArr1,$profileArr2);
		if(!$profileArr)
			$profileArr=$profileArr1;
		if(!$profileArr)
			$profileArr=$profileArr2;
		if(is_array($profileArr))
		foreach($profileArr as $key=>$val)
		{			
			$profileObj = new Profile('',$val);
			$profileObj->getDetail('', '', $detailArr);
			$tplObj=$emailSenderObj->setProfile($profileObj);
			$suggested_matches_array=SearchCommonFunctions::getDppMatches($val,'fto_offer',SearchSortTypesEnums::popularSortFlag);
			$p_list=new PartialList;
			$p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
			$p_list->addPartial('suggested_profiles','suggested_profiles1',$suggested_matches_array["SEARCH_RESULTS"],false);
			$tplObj->setPartials($p_list);
			$emailSenderObj->send();
			$count++;
		
		}
		 SendMail::send_email("nitesh.s@jeevansathi.com,nikhil.dhiman@jeevansathi.com","$count Education Mailer sent out","EducationMailer cron completed");
  }
}
