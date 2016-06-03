<?php

	/************************************************************************************************************************************
	Name :Testing PHP code for PRIVILEGE ERROR
	Description: Contains all the possible testcase scenario for privilege Errorin ErrorHandler Class and write all the possible outcomes in the file->/test/unit/fto/testPrivError.php
	Created By : Nitesh Sethi 
	************************************************************************************************************************************/

	$socialRoot=realpath(dirname(__FILE__));
	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/profileTemplateSnippet.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/baseFromat.php');
	$t = new lime_test(912, new lime_output_color());
	$fhobby=fopen($socialRoot."/testPrivError.php","w");
	fwrite($fhobby,"*******************************Priv White BOX testing********************\n********\n*******\n");
	$fieldsArray="PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
		
		$loggedInProfile = new Profile('',144111);	
		$otherProfile = new Profile('',136580);
		$loggedInProfile->getDetail('', '', $fieldsArray);
		$otherProfile->getDetail('', '', $fieldsArray);
		$contact = new Contacts($loggedInProfile, $otherProfile);
		$arrayAlreadyStatusType= array('I','A','D','C','E','N');
		$arrayStatus=array('I','A','D','C','E','N','CALL_DIRECT','CONTACT_DETAIL');
		$arrayContactType=array('EOI','INFO');
		$arrayPrePost=array('PRE','POST');
		$arrayYesNo=array('Y','N');
		static $count=1;
		foreach($arrayContactType as $K)
		{
			foreach($arrayAlreadyStatusType as $I)
			{
				foreach($arrayStatus as $J)
				{
					foreach($arrayPrePost as $L)
					{
						//echo $I."\n".$K."\n".$L."\n".$J;die;
						$contact->setTYPE($I);
						$contactHandlerObj = new ContactHandler($loggedInProfile,$otherProfile,$K,$contact,"C",$L);
						$privilegeArray=$contactHandlerObj->getPrivilegeObj()->getPrivilegeArray();
						$contactHandlerObj->setElement("STATUS",$J);
						if($K=="EOI")
						{
							foreach($arrayYesNo as $A)
							{
								foreach($arrayYesNo as $B)
								{
									foreach($arrayYesNo as $C)
									{
											$contactHandlerObj->setElement("DRAFT_WRITE",$A);
											$contactHandlerObj->setElement("DROP_DOWN",$B);
											$contactHandlerObj->setElement("MESSAGE_BOX_VISIBLE",$C);
											fwrite($fhobby,"\nPREPOST: ".$L."\nContactType: ".$K."\nCurrent: ".$I."\nAction Performed: ".$J."\nDRAFT_WRITE: ".$A."\nDROP_DOWN: ".$B."\nMESSAGE_BOX_VISIBLE ".$C."\n");
											checkError($contactHandlerObj,$fhobby,$t,$privilegeArray,$count++);
									}
								}
							}
						}
						else
						{
							if($K=="INFO" && $J=="CALL_DIRECT")
								{
									foreach($arrayYesNo as $A)
									{
										foreach($arrayYesNo as $B)
										{
											$contactHandlerObj->setElement("ALLOWED",$A);
											$contactHandlerObj->setElement("APPLICABLE",$B);
											fwrite($fhobby,"\nPREPOST: ".$L."\nContactType: ".$K."\nCurrent: ".$I."\nAction Performed: ".$J."\nALLOWED: ".$A."\nAPPLICABLE: ".$B."\n");
											checkError($contactHandlerObj,$fhobby,$t,$privilegeArray,$count++);
										}
									}
								}
							elseif($K=="INFO" && $J=="CONTACT_DETAIL")
								{
									foreach($arrayYesNo as $A)
									{
										$contactHandlerObj->setElement("VISIBILITY",$A);
										fwrite($fhobby,"\nPREPOST: ".$L."\nContactType: ".$K."\nCurrent: ".$I."\nAction Performed: ".$J."\nVISIBILITY: ".$A."\n");
										checkError($contactHandlerObj,$fhobby,$t,$privilegeArray,$count++);
									}
								}
							else
							{
								fwrite($fhobby,"\nPREPOST: ".$L."\nContactType: ".$K."\nCurrent: ".$I."\nAction Performed: ".$J."\n");
								checkError($contactHandlerObj,$fhobby,$t,$privilegeArray,$count++);
							}
						}
					}
				}
			}
		}
		
		function checkError(ContactHandler $contactHandlerObj,$fhobby,lime_test $t,$privilegeArray,$count)
		{
			
			$errorHandlerObj=new ErrorHandler($contactHandlerObj);
						if(!$errorHandlerObj->checkError())
						{
							$errorTypeArr=$errorHandlerObj->getAllError();
							if($errorTypeArr[0]=='PRIVILEGE')
							{
								if($errorHandlerObj->getErrorMessage()=='You are not allowed to do this action')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not allowed to do this action')."\n***************************");
								}
								elseif($errorHandlerObj->getErrorMessage()=='You are not allowed to use drop_down')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not allowed to use drop_down')."\n***************************");
									
								}
								elseif($errorHandlerObj->getErrorMessage()=='You are not allowed to  write message')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not allowed to  write message')."\n***************************");
								}
								elseif($errorHandlerObj->getErrorMessage()=='You are not allowed to see message box')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not allowed to see message box')."\n***************************");
								}
								elseif($errorHandlerObj->getErrorMessage()=='You are not allowed to view contact Details')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not allowed to view contact Details')."\n***************************");
								}
								elseif($errorHandlerObj->getErrorMessage()=='You are not allowed for CALL DIRECTLY')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not allowed for CALL DIRECTLY')."\n***************************");
								}
								elseif($errorHandlerObj->getErrorMessage()=='You are not APPLICABLE for CALL DIRECTLY')
								{
									fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You are not APPLICABLE for CALL DIRECTLY')."\n***************************");
								}
							}
							elseif($errorTypeArr[0]='DECLINED')
							{
								fwrite($fhobby,"\nTEST FOR ACTION PRIVILEGE DECLINED FAILED : ".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."\n\t\t\t\t\t\t\t\t\t\t\t\t\t  TEST PASSED :".$t->ok($errorHandlerObj->getErrorMessage(),'/P/mainmenu.php','You cannot see the contact details of this profile as the profile has declined any further contacts with you.')."\n***************************");
							}
							else
							{
									fwrite($fhobby," \n".$errorHandlerObj->getErrorMessage()."\nTest Number:".$count."n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t TEST ERROR NOT DETECTED : ".$errorTypeArr[0]."\n***************************");
							}
						}
						else
						{
							$flag=1;
							fwrite($fhobby,"\nTest Number:".$count."\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tTEST PASSED WITH NO ERROR :".$t->ok($flag,'/P/mainmenu.php','1')."\n***************************");
						}
						
						
		}
		
		
