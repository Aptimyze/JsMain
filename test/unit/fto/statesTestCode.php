<?php

require_once JsConstants::$docRoot.'/fto/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
require_once(JsConstants::$docRoot.'/fto/config/ProjectConfiguration.class.php');
$configuration =ProjectConfiguration::getApplicationConfiguration('jeevansathi','prod',false);

$p1 = new Profile('',$argv[1]);
$fieldsArray="PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN";

$p1->getDetail('', '', $fieldsArray);

echo "\nPrevious State: ";
print_r($p1->getPROFILE_STATE()->getFTOStates());

if($argv[2])
{	
	updateAction($p1, $argv[2],$fieldsArray,$argv[3]);
	echo "\n\nNew State: ";
        $p1->getDetail('', '', $fieldsArray);
	if($action =="PHOTO_UPLOAD" || $action =="PHOTO_DELETE")
		$action ="PHOTO";
	$p1->getPROFILE_STATE()->updateFTOState($p1, $argv[2]);
print_r($p1->getPROFILE_STATE()->getFTOStates());
//	print_r($p1->getPROFILE_STATE());	
}


function updateAction(Profile $p1,$action='',$fieldsArray,$p2)
{
	$jp=new JPROFILE;
	switch($action)
	{
	case "PHOTO_UPLOAD":
		$jp->edit(array(HAVEPHOTO=>'Y'),$p1->getPROFILEID(),"PROFILEID");
		break;
	case "PHOTO_DELETE":
		$jp->edit(array(HAVEPHOTO=>'N'),$p1->getPROFILEID(),"PROFILEID");
		break;
	case FTOStateUpdateReason::NUMBER_VERIFY:
		$jp->edit(array(MOB_STATUS=>'Y'),$p1->getPROFILEID(),"PROFILEID");
		break;
	case FTOStateUpdateReason::NUMBER_UNVERIFY:
		$jp->edit(array(MOB_STATUS=>'N',LANDL_STATUS=>'N'),$p1->getPROFILEID(),"PROFILEID");
		break;
	case FTOStateUpdateReason::EOI_SENT:
	case FTOStateUpdateReason::ACCEPT_SENT:
	case FTOStateUpdateReason::ACCEPT_RECEIVED:
		if(FTOStateUpdateReason::EOI_SENT==$action)
			$type="I";
		if(FTOStateUpdateReason::ACCEPT_SENT==$action|| FTOStateUpdateReason::ACCEPT_RECEIVED==$action)
			$type="A";
		if($p2)
		{
			$p2 = new Profile('',$p2);
			$p2->getDetail('', '', $fieldsArray);
			$contact = new Contacts($p1, $p2);
		        $engineType="EOI";
			if(FTOStateUpdateReason::ACCEPT_RECEIVED==$action)
			{
				$p=$p1;
				$p1=$p2;
				$p2=$p;
			}
		        $contactHandlerObj = new ContactHandler($p1,$p2,$engineType,$contact,$type,"POST");
        		$contactHandlerObj->setElement("STATUS",$type);
			$event=ContactFactory::event($contactHandlerObj);
		}
		else
			echo "other profile not entered. pass it as last paramter";
		break;
	case FTOStateUpdateReason::REGISTER:
		$jp->edit(array(ACTIVATED =>'U'),$p1->getPROFILEID(),"PROFILEID");
		break;
	case FTOStateUpdateReason::SCREEN:
		$jp->edit(array(ACTIVATED =>'Y'),$p1->getPROFILEID(),"PROFILEID");
		break;
	DEFAULT:
		break;
	}
}
?>
