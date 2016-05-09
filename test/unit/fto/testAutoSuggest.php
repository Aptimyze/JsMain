<?php

	/************************************************************************************************************************************
	Name :Testing PHP code for DppAutoSuggest class
	Description: Contains the common test cases to check for the working of the DppAutoSuggest Class and respective functions output
	Created By : Nitesh Sethi 
	************************************************************************************************************************************/

	$socialRoot=realpath(dirname(__FILE__)."/../../..");
	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/profileTemplateSnippet.php');
	//include_once('/var/www/Mailer/lib/model/lib/mailer/Mailerlib/baseFromat.php');
	$t = new lime_test(16, new lime_output_color());
	
$fieldsArray="PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";
	
			
		$profileObj = new Profile('',336);
		
		$profileObj->getDetail('', '', $fieldsArray);
		$jpartnerObj=new Jpartner();
		$dppObj=new DppAutoSuggest($profileObj);
		$profileFieldArr=array("AGE","MTONGUE","HEIGHT","COUNTRY_RES","CITY_RES","MSTATUS","MTONGUE","RELIGION","CASTE","DIET","SMOKE","DRINK","COMPLEXION","BTYPE","OCCUPATION","MANGLIK","HANDICAPPED","AGE","INCOME","EDU_LEVEL_NEW");
		$dppObj->insertJpartnerDPP($profileFieldArr);
		$db=new MIS_SOURCE();
		$sourceObj=new SourceTracking();
		$sourceObj->setSource("IP");
		$sourceObj->setFromPage("HOME_PAGE");
		$sourceObj->SourceTracking();
		
		/*$filterObj=new Filters(336);
		$filterObj->setDPP();*/
		//$filterObj->getFilters();
		
		/*
		 $dbJprofile= new JPROFILE();
				$religionArr=$dbJprofile->get(336,'PROFILEID',"RELIGION");
		 print_r($religionArr);
		*/
		
		/*		
				$dbApDppFilterArchive= new AP_DPP_FILTER_ARCHIVE();
			$liveDPP=$dbApDppFilterArchive->fetchCurrentDPP(3809620);
				//print_r($liveDPP);
		
			$whrStr="AND ONLINE='Y' AND ROLE='ONLINE' AND CREATED_BY='ONLINE'";
			
			if($liveDPP["DPP_ID"])
				$whrStr.=" AND DPP_ID>'$liveDPP[DPP_ID]'";
			$currentdpp=$dbApDppFilterArchive->fetchCurrentDPP(3809620,'',$whrStr);	
			print_r($currentDPP);
			*/
			$dbFilters= new NEWJS_FILTER();
			//$whrStr="AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N', MTONGUE='N',CASTE='N',CITY_RES='N',INCOME='N',COUNT=COUNT+1, HARDSOFT='N'";
					
				//	$result=$dbFilters->updateFilters(336,$whrStr);
//$whrStr="AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N', MTONGUE='N',CASTE='N',CITY_RES='N',INCOME='N',COUNT=1, HARDSOFT='N'";
	//					$result=$dbFilters->insertFilterEntry(336,$whrStr);
$selectStr="COUNT";
		//		$result=$dbFilters->fetchFilterDetails(336,"",$selectStr);
//print_r($result);

//$dbJprofile= new JPROFILE();
				//$religionArr=$dbJprofile->get(336,"PROFILEID","RELIGION");

//print_r($religionArr);
/*
$dbProfileChangeLog= new PROFILECHANGE_LOG();
				$dbProfileChangeLog->insertChangesDone("nitesh",336,"haidsioahidhasiudhasu","JS");
				* */
				
				//EMAIL TESTING:
			/*	$email_sender=new EmailSender(MailerGroup::REGISTRATION_PAGE1,1771);
				$emailTpl=$email_sender->setProfileId(336);
				$email_sender->send('nitesh.s@jeevansathi.com');
				* */
			/*	$emailSender = new EmailSender(MailerGroup::SCREENING_NORMAL_PAGE2,1773);
				$emailSender->setProfileId(336);
				
				$emailSender->send('nitesh.s@jeevansathi.com');*/
				
			/*
			$dbBlockIP= new NEWJS_BLOCK_IP();
				if($dbBlockIP->blockIP('127.0.0.1'))
					echo "hi";
					else
					echo "hello";
					$dbBlockIP->insertIP('127.0.0.1');*/
		$ip='127.0.0.1';
		$source='afaflcry';
		$id=336;
		$email='niteshtest@jeebvansathi.com';
		/*	$dbSource=new MIS_SOURCE();
			$force_mail=$dbSource->getSourceFields("FORCE_EMAIL",'afaflcry');
			print_r($force_mail);
			if($force_mail["FORCE_EMAIL"]=='Y')
				echo $email_validation='Y';	
			*/	
			
			/*	$dbRegHome=new MIS_REG_HOME();
			$dbRegHome->insert($id,$source);
*/

				$dbContactArchive=new NEWJS_CONTACT_ARCHIVE();
				$dbContactArchiveInfo=new CONTACT_ARCHIVE_INFO();
				
				//Email
		/*		echo $changeid=$dbContactArchive->insert($id,"EMAIL");
				echo $dbContactArchiveInfo->insert($changeid,$ip,$email);
		*/
		
				//PHONE_RES
			/*	$phone="91-9784046619";
				echo $changeid=$dbContactArchive->insert($id,"PHONE_RES");
					echo $dbContactArchiveInfo->insert($changeid,$ip,$phone);
*/

				//PHONE_MOB
				/*$arch_mobile="91-9784046619";
					$changeid=$dbContactArchive->insert($id,"PHONE_MOB");
				echo 	$dbContactArchiveInfo->insert($changeid,$ip,$arch_mobile);*/
				$_COOKIE['OPERATOR']="nitesh";
					/*	$dbJsAdminAssigned101= new JSADMIN_ASSIGNED_101();						
						$dbJsAdminAssigned101->replace($id,$_COOKIE['OPERATOR']);
						
						
						$dbJsAdminAssignLog101= new JSADMIN_ASSIGNLOG_101();
						$dbJsAdminAssignLog101->insert($id,$_COOKIE['OPERATOR']);
						* */
					/*	$dbOfflineRegistration= new NEWJS_OFFLINE_REGISTRATION();
						$dbOfflineRegistration->insert($id,$_COOKIE['OPERATOR'],$source);			
						*/
				
				/*$dbOfflineAssigned= new JSADMIN_OFFLINE_ASSIGNED();
						$dbOfflineAssigned->replace($id,$_COOKIE['OPERATOR']);
						
						$dbOfflineAssignedLog= new JSADMIN_OFFLINE_ASSIGNLOG();
						$dbOfflineAssignedLog->insert($id,$_COOKIE['OPERATOR']);		*/
						
						$leadid="336";
						$username="nitesh";
				/*	$dbSugarcrmLeads=new sugarcrm_leads();
					
					$dbSugarcrmLeads->updateSugarRegitrationCompletiton($username,"niteshtest");					
					$row_op=$dbSugarcrmLeads->selectUsername("niteshtest");
					
					$dbOfflineRegistration= new NEWJS_OFFLINE_REGISTRATION();
					$dbOfflineRegistration->insert($id,$_COOKIE['OPERATOR'],$source);
					*/	
				
				/*	$adnetwork="nitesh sethi";$account="nitesh";
					$campaign="nitesh";
					$adgroup="nitesh";
					$keyword_tieup="nitesh";
					$match="nitesh";
					$lmd="nitesh";
					
					$dbTrackTieupVariable= new MIS_TRACK_TIEUP_VARIABLE();
					$dbTrackTieupVariable->insert($adnetwork,$account,$campaign,$adgroup,$keyword_tieup,$match,$lmd,$id);*/
				/*	
					$dbSearchRediff= new MIS_REDIFF_SRCH_REG();
					$dbSearchRediff->insert($id);
					*/
					/*$lang="nitesh";
					$dbLangRegister= new MIS_LANG_REGISTER();
					$dbLangRegister->insert($id,$lang);
*/
/*
					$dbIncompleteProfile= new NEWJS_INCOMPLETE_PROFILES();
					$dbIncompleteProfile->insert($id);*/
					
			/*	echo	$vpin=CommonFunction::vpin_gen();
				$dbInfUsrPin= new INFOVISION_INF_USER_PIN();
				$dbInfUsrPin->insert($id,$vpin);*/
			
			 	/*	$dbRegLead= new MIS_REG_LEAD();
					$dbRegLead->updateRegisterEmail("LEAD_CONVERSION ='Y'",$email);
				*/
			/*		$dbMiniAjaxLead= new MIS_MINI_REG_AJAX_LEAD();
					$dbMiniAjaxLead->updateRegisterEmail($email);
					
					$leadid=99999;
					$dbLeadConversion= new MIS_LEAD_CONVERSION();
					$dbLeadConversion->insertConvertedLead($leadid);
*/

		/*		$dbRegCount=new MIS_REG_COUNT();
				$dbRegCount->insert($id,"PAGE1");
				
				$dbRegCount=new MIS_REG_COUNT();
				$dbRegCount->updateEntryRegPage("PAGE2","Y",$id);*/
				
				//InsertInto Names Tables:
		/*		if($leadid)
			{
				$dbLeadConversion=new MIS_LEAD_CONVERSION();
				$dbLeadConversion->updateLead(11);
			}*/
			/*
			$dbMisUnknownSource=new MIS_UNKNOWN_SOURCE();
			print_r($dbMisUnknownSource);
					$dbMisUnknownSource->insertUnknownSource($source);
			 */
			/*	$dbNames=new NEWJS_NAMES();
				$dbNames->insert($username);
			*/
