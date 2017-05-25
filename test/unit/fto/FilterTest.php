<?php

		
	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	
	$t = new lime_test(14, new lime_output_color());
	
	$fieldsArray="PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
		
		$loggedInProfile = new Profile('',144111);	
		$otherProfile = new Profile('',237207);
		$loggedInProfile->getDetail('', '', $fieldsArray);
		$otherProfile->getDetail('', '', $fieldsArray);
		
		
		
		//1 - Not Filtered
		$loggedInProfile->setRELIGION(4);
		$loggedInProfile->setCASTE(167);
		$loggedInProfile->setMTONGUE(27);
		$loggedInProfile->setAGE(25);
		$loggedInProfile->setMSTATUS(N);
		$loggedInProfile->setCITY_RES('UP25');
		$loggedInProfile->setCOUNTRY_RES(51);
		$loggedInProfile->setINCOME(12);
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),0, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//2 -  Filter - Religion
		$loggedInProfile->setRELIGION(5);		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//3 Filter - Caste
		$loggedInProfile->setRELIGION(4);
		$loggedInProfile->setCASTE(168);		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		
		//4  Filter - Mtongue
		
		$loggedInProfile->setCASTE(167);
		$loggedInProfile->setMTONGUE(29);		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//5  Filter - Age
		
		$loggedInProfile->setMTONGUE(27);
		$loggedInProfile->setAGE(31);		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//6  Filter - Age
		
		$loggedInProfile->setAGE(25);		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),0, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//7 Filter - Age
		
		$loggedInProfile->setAGE(29);
		$loggedInProfile->setMSTATUS(D);		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		
		//8 Filter - Income
		
		$loggedInProfile->setMSTATUS(N);		
		$loggedInProfile->setINCOME(25);
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		
		//9 Filter - Country
		
		$loggedInProfile->setCITY_RES('DE00');
		$loggedInProfile->setCOUNTRY_RES(56);
		$loggedInProfile->setINCOME(12);
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//10 Filter - City
	
		$loggedInProfile->setCITY_RES('UP26');
		$loggedInProfile->setCOUNTRY_RES(51);	
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//11 Filter - Mstatus
	
		$loggedInProfile->setMSTATUS(A);
		$loggedInProfile->setCITY_RES('UP25');		
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//12 No Filter - Income
		
		$loggedInProfile->setMSTATUS(N);
		$loggedInProfile->setINCOME(19);
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),1, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		//13 No Filter - Income
		

		$loggedInProfile->setINCOME(10);
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),0, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
		
		
		//14 No Filter - Income
		$loggedInProfile->setRELIGION(4);
		$loggedInProfile->setCASTE(167);
		$loggedInProfile->setMTONGUE(27);
		$loggedInProfile->setAGE(25);
		$loggedInProfile->setMSTATUS(N);
		$loggedInProfile->setCITY_RES('UP25');
		$loggedInProfile->setCOUNTRY_RES(51);
		$loggedInProfile->setINCOME(12);
		$filterObj = UserFilterCheck::getInstance($loggedInProfile,$otherProfile);
		$t->is($filterObj->getFilteredContact(),0, Done);
		$key=$loggedInProfile->getPROFILEID()."_".$otherProfile->getPROFILEID();
		unset(UserFilterCheck::$filterObj[$key]);
?>
