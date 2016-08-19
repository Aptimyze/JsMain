<?php
class EDIT_LOG
{

	function __construct($db="") {
		$this->editLogObj = new newjs_EDIT_LOG($db);
	}
	public function log_edit($editArray,$pid,$updateMongo=false,$updatemySql=true)
	{
		foreach (array(
	        "HAVE_JCONTACT",
	        "HAVE_JEDUCATION",
	        "PHONE_WITH_STD",
	        "SHOWBLACKBERRY",
	        "SHOWLINKEDIN",
	        "SHOWFACEBOOK",
	        "CALL_ANONYMOUS",
	        "SEC_SOURCE",
	        'LANDL_STATUS',
	        'MOB_STATUS',
	        'ALT_MOB_STATUS'
	    ) as $notToSet) 
		{
			if($editArray[$notToSet])
				unset($editArray[$notToSet]);
		}
		$editArray["IPADD"] = FetchClientIP();
		$jprofileObj = new JPROFILE("newjs_master");
		$jprofileDetails = $jprofileObj->getProfileSelectedDetails($pid,"PROFILEID,USERNAME,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT, EDU_LEVEL,EMAIL,RELATION,COUNTRY_BIRTH,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME, HANDICAPPED,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,FAMILY_VALUES,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,MOD_DT,SOURCE,IPADD");
		
		foreach ($jprofileDetails[$pid] as $key => $value) {
			if(!$value)
				unset($jprofileDetails[$pid][$key]);
		}
		if($updatemySql==true)
		{
			if(!$this->editLogObj->isNewEntry($pid))
			{
				$this->editLogObj->insertEditDetails($jprofileDetails[$pid]);
			}
			$this->editLogObj->insertEditDetails($editArray);
		}
		if($updateMongo==true)
		{
			foreach ($editArray as $key => $value) {
				$updatedEarlierValueArr[$key] = $jprofileDetails[$pid][$key];
			}
			if($updatedEarlierValueArr["MOD_DT"])
					unset($updatedEarlierValueArr["MOD_DT"]);

			if($updatedEarlierValueArr)
			{
				$mongoObj = new PROFILE_EDIT_LOG();
				$mongoObj->insertOne($updatedEarlierValueArr);
				unset($mongoObj);
			}
		}
	}
}
?>
