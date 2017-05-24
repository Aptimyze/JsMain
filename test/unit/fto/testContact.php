<?php

	/************************************************************************************************************************************
	Name :Testing PHP code for PAID Privilege class
	Description: Contains the common test cases to check for the working of the FTO Paid Privilege class and respective functions output
	Created By : Nitesh Sethi 
	************************************************************************************************************************************/

	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/profileTemplateSnippet.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/baseFromat.php');
	$t = new lime_test(16, new lime_output_color());
//	print_r($_SERVER[argv]);
	$pid1=$_SERVER[argv][1];
	$pid2=$_SERVER[argv][2];
	$type=$_SERVER[argv][3];
	$action=$_SERVER[argv][4];
	$engineType=$_SERVER[argv][5];
	$fieldsArray="PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
	
		$loggedInProfile = new Profile('',$pid1);	
		$otherProfile = new Profile('',$pid2);
		$loggedInProfile->getDetail('', '', $fieldsArray);
		$otherProfile->getDetail('', '', $fieldsArray);
		
		$contact = new Contacts($loggedInProfile, $otherProfile);
		//$contact->setTYPE('I');
//		var_dump($contact);die;	
		$contactHandlerObj = new ContactHandler($loggedInProfile,$otherProfile,$engineType,$contact,$type,$action);
		//$contactHandlerObj->setElements("status","I");
		$k=ContactFactory::event($contactHandlerObj);
//		print_r($k);die;
		$senderObj = $contactHandlerObj->getViewer();
		echo $senderObj->getGENDER();
		$mescomm = new MessageCommunication($contactHandlerObj);
		$mescomm->insertMessage();
		$paidPrivilege= new PaidPrivilege($loggedInProfile,$otherProfile,'A','R');

		$conmemcache = new ContactsMemcache(59544);
		print_r($conmemcache->getContactCount());

		$privilegeArray=$contactHandlerObj->getPrivilegeObj()->getPrivilegeArray();
		print_r($privilegeArray);
		$errorHandlerObj=new ErrorHandler($contactHandlerObj);
		if(!$errorHandlerObj->checkError())
		{
			echo $errorHandlerObj->getErrorMessage();
			print_r($errorHandlerObj->getAllError());
		}
		
