<?php
	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/profileTemplateSnippet.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/baseFromat.php');
	$t = new lime_test(50, new lime_output_color());
	
	$fieldsArray="PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
	
		
		$loggedInProfile = new Profile('',144111);	
		$otherProfile = new Profile('',136580);
		$loggedInProfile->getDetail('', '', $fieldsArray);
		$otherProfile->getDetail('', '', $fieldsArray);
		
		$otherProfile->setGENDER('M');
		$t->comment('Same Gender Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::SAMEGENDER,$t);
		
		
		$otherProfile->setGENDER('F');
		$otherProfile->setACTIVATED('D');
		$t->comment('Deleted Profile Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::DELETED,$t);
		
		$otherProfile->setACTIVATED('Y');
		$contactAllotedObj = new jsadmin_CONTACTS_ALLOTED();
		/* Make Alloted and Viewed Count equal */
		$contactAllotedObj->insertViewedContacts($loggedInProfile->getPROFILEID(), 75, 75);
		$t->comment('View Limit Check');
		/* to check View Limit Error,Viewer must be paid member and any existing entry shouldn't be present		 
		 for viewer and viewed in VIEW_CONTACTS_LOG table.
		 */
		runTest($loggedInProfile,$otherProfile,ErrorHandler::CONT_VIEW_LIMIT,$t);
		$contactAllotedObj->insertViewedContacts($loggedInProfile->getPROFILEID(), 75, 0);	
		
		$contactsMemcacheObj = ContactsMemcache::getInstance($loggedInProfile->getPROFILEID());
		$limitArr = CommonFunction::getContactLimits($loggedInProfile->getSUBSCRIPTION());
		
		$d = $contactsMemcacheObj->getTodayInitiatedByMe();
		$m = $contactsMemcacheObj->getMonthInitiatedByMe();
		$w = $contactsMemcacheObj->getWeekInitiatedByMe();
		$o = $contactsMemcacheObj->getOverallContactsMade();
		
		/* Reseting Count to 0 and then doing it equal to alloted limit */
		$contactsMemcacheObj->setTodayInitiatedByMe(-$d);
		$contactsMemcacheObj->setWeekInitiatedByMe(-$w);
		$contactsMemcacheObj->setMonthInitiatedByMe(-$m);
		
		$contactsMemcacheObj->setTodayInitiatedByMe($limitArr['DAY_LIMIT']);
		$t->comment('Today Contact Limit Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::EOI_CONTACT_LIMIT,$t);
		
		$contactsMemcacheObj->setTodayInitiatedByMe(-$limitArr['DAY_LIMIT']);
		$contactsMemcacheObj->setWeekInitiatedByMe($limitArr['WEEKLY_LIMIT']);
		$t->comment('Weekly Contact Limit Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::EOI_CONTACT_LIMIT,$t);
		
		$contactsMemcacheObj->setWeekInitiatedByMe(-$limitArr['WEEKLY_LIMIT']);
		$contactsMemcacheObj->setMonthInitiatedByMe($limitArr['MONTH_LIMIT']);
		$t->comment('Monthly Contact Limit Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::EOI_CONTACT_LIMIT,$t);
		
		$contactsMemcacheObj->setMonthInitiatedByMe(-$limitArr['MONTH_LIMIT']);		
		//$contactsMemcacheObj->setWeekInitiatedByMe($w);
		
		unset($contactsMemcacheObj);
		/*$contactsMemcacheObj->setOverallContactsMade();
		$t->comment('Contact Limit Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::EOI_CONTACT_LIMIT,$t);
		
		$contactsMemcacheObj->setOverallContactsMade();*/
		
		$loggedInProfile->setACTIVATED('U');
		$t->comment('UnderScreening Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::UNDERSCREENING,$t);
		
		$loggedInProfile->setACTIVATED('A');
		$loggedInProfile->setINCOMPLETE('Y');
		$t->comment('Incomplete Profile Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::INCOMPLETE,$t);
		
		$loggedInProfile->setACTIVATED('H');
		$loggedInProfile->setINCOMPLETE('N');
		$t->comment('Hidden Profile Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::PROFILE_HIDDEN,$t);
		
		$loggedInProfile->setACTIVATED('A');
		$t->comment('Declined Error Check');
		runTest($loggedInProfile,$otherProfile,ErrorHandler::DECLINED,$t);
		
		
function runTest($loggedInProfile,$otherProfile,$errorType,$t)
{	
	$contact = new Contacts($loggedInProfile, $otherProfile);
	if($errorType==ErrorHandler::SAMEGENDER)
	{	
		$engineArr = array('0'=>"EOI","1"=>"INFO");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I","E","A","C","D");
		$setTypeArr = array("N","I");
	}
	if($errorType==ErrorHandler::DECLINED)
	{
		$engineArr = array('0'=>"INFO");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I","E","A","C","D");
		$setTypeArr = array("D");
	}
	if($errorType==ErrorHandler::DELETED)
	{
		$engineArr = array('0'=>"EOI","1"=>"INFO");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I","E","A","C","D");
		$setTypeArr = array("N");
	}
	if($errorType==ErrorHandler::CONT_VIEW_LIMIT)
	{
		$engineArr = array('0'=>"INFO");
		$actionArr = array("0"=>"POST");
		$typeArr = array("N","I","E","A","C","D");
		$setTypeArr = array("N","I");		
	}
	if($errorType==ErrorHandler::EOI_CONTACT_LIMIT)
	{ 
		$engineArr = array("0"=>"EOI");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I");
		$setTypeArr = array("N","E");
		
	}
	if($errorType==ErrorHandler::INCOMPLETE)
	{ 
		$engineArr = array("0"=>"EOI","INFO");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I");
		$setTypeArr = array("N");		
	}
	if($errorType==ErrorHandler::UNDERSCREENING)
	{ 
		$engineArr = array("0"=>"EOI","INFO");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I");
		$setTypeArr = array("N");		
	}
	if($errorType==ErrorHandler::PROFILE_HIDDEN)
	{ 
		$engineArr = array("0"=>"EOI","INFO");
		$actionArr = array("0"=>"PRE","1"=>"POST");
		$typeArr = array("N","I");
		$setTypeArr = array("N");		
	}	
	foreach ($engineArr as $key => $val)
	{
		foreach($actionArr as $k=>$v)
		{
			for($i=0;$i<count($typeArr);$i++)
			{
				foreach($setTypeArr as $type)
				{
						$contact->setType($type);
						$contactHandlerObj = new ContactHandler($loggedInProfile,$otherProfile,$val,$contact,$typeArr[$i],$v);
						$errorHandlerObj=new ErrorHandler($contactHandlerObj);
						$errorHandlerObj->setErrorType(ErrorHandler::PRIVILEGE,0);
						$errorHandlerObj->checkError();
						$errorArr = $errorHandlerObj->getErrorType();
						echo $errorHandlerObj->getErrorMessage();
						$t->is($errorArr[$errorType],2,"Good ".$val." ".$v." ".$typeArr[$i]);							
				}
			}
		}	
	}
	
}
