<?php
 /*
	This is auto-generated class by running lib/utils/ProfileFieldClassBuilder.php
	This class should not be updated manually.
	Created on 2014-02-24
 */
	class ProfileEditFields
	{
		/*This function will return all the fields of this page*/
    	public static function getPageField($fieldName,$returnArr='')
    	{
			switch($fieldName){
case 'AGE':
					$field=new Field('','AGE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:AGE");
				break;

case 'ALT_MOBILE':
					$field=new Field('','ALT_MOBILE');
					$field->setFieldType("mobile");
					$field->setConstraintClass("alt_mobile");
					$field->setTableName("JPROFILE_CONTACT:ALT_MOBILE-mobile,ALT_MOBILE_ISD-isd");
				break;

case 'AMRITDHARI':
					$field=new Field('','AMRITDHARI');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_SIKH:AMRITDHARI");
				break;

case 'ANCESTRAL_ORIGIN':
					$field=new Field('','ANCESTRAL_ORIGIN');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:ANCESTRAL_ORIGIN");
				break;

case 'BAPTISED':
					$field=new Field('','BAPTISED');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_CHRISTIAN:BAPTISED");
				break;

case 'BLOOD_GROUP':
					$field=new Field('','BLOOD_GROUP');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:BLOOD_GROUP");
				break;

case 'BTYPE':
					$field=new Field('','BTYPE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:BTYPE");
				break;

case 'CASTE':
					$field=new Field('','CASTE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("caste");
					$field->setTableName("JPROFILE:CASTE");
				break;

case 'CITY_RES':
					$field=new Field('','CITY_RES');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("city");
					$field->setTableName("JPROFILE:CITY_RES");
				break;

case 'CLEAN_SHAVEN':
					$field=new Field('','CLEAN_SHAVEN');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_SIKH:CLEAN_SHAVEN");
				break;

case 'COLLEGE':
					$field=new Field('','COLLEGE');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE_EDUCATION:COLLEGE");
				break;

case 'COMPANY_NAME':
					$field=new Field('','COMPANY_NAME');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:COMPANY_NAME");
				break;

case 'COMPLEXION':
					$field=new Field('','COMPLEXION');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:COMPLEXION");
				break;

case 'CONTACT':
					$field=new Field('','CONTACT');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:CONTACT");
				break;

case 'COUNTRY_RES':
					$field=new Field('','COUNTRY_RES');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("country");
					$field->setTableName("JPROFILE:COUNTRY_RES");
				break;

case 'CUT_HAIR':
					$field=new Field('','CUT_HAIR');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_SIKH:CUT_HAIR");
				break;

case 'DEGREE_PG':
					$field=new Field('','DEGREE_PG');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE_EDUCATION:PG_DEGREE");
				break;

case 'DEGREE_UG':
					$field=new Field('','DEGREE_UG');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE_EDUCATION:UG_DEGREE");
				break;

case 'DIET':
					$field=new Field('','DIET');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:DIET");
				break;

case 'DIOCESE':
					$field=new Field('','DIOCESE');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JP_CHRISTIAN:DIOCESE");
				break;

case 'DRINK':
					$field=new Field('','DRINK');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:DRINK");
				break;

case 'DTOFBIRTH':
					$field=new Field('','DTOFBIRTH');
					$field->setFieldType("date");
					$field->setConstraintClass("dob");
					$field->setTableName("JPROFILE:DTOFBIRTH");
				break;

case 'EDUCATION':
					$field=new Field('','EDUCATION');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:EDUCATION");
				break;

case 'EDU_LEVEL_NEW':
					$field=new Field('','EDU_LEVEL_NEW');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setTableName("JPROFILE:EDU_LEVEL_NEW");
				break;

case 'EMAIL':
					$field=new Field('','EMAIL');
					$field->setFieldType("text");
					$field->setConstraintClass("email");
					$field->setTableName("JPROFILE:EMAIL");
				break;
case 'ALT_EMAIL':
					$field=new Field('','ALT_EMAIL');
					$field->setFieldType("text");
					$field->setConstraintClass("alt_email");
					$field->setTableName("JPROFILE_CONTACT:ALT_EMAIL");
				break;

case 'FAMILYINFO':
					$field=new Field('','FAMILYINFO');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:FAMILYINFO");
				break;

case 'FAMILY_BACK':
					$field=new Field('','FAMILY_BACK');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:FAMILY_BACK");
				break;

case 'FAMILY_INCOME':
					$field=new Field('','FAMILY_INCOME');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:FAMILY_INCOME");
				break;

case 'FAMILY_STATUS':
					$field=new Field('','FAMILY_STATUS');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:FAMILY_STATUS");
				break;

case 'FAMILY_TYPE':
					$field=new Field('','FAMILY_TYPE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:FAMILY_TYPE");
				break;

case 'FAMILY_VALUES':
					$field=new Field('','FAMILY_VALUES');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:FAMILY_VALUES");
				break;

case 'FASTING':
					$field=new Field('','FASTING');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:FASTING");
				break;

case 'FAV_BOOK':
					$field=new Field('','FAV_BOOK');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JHOBBY:FAV_BOOK");
				break;

case 'FAV_FOOD':
					$field=new Field('','FAV_FOOD');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JHOBBY:FAV_FOOD");
				break;

case 'FAV_MOVIE':
					$field=new Field('','FAV_MOVIE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JHOBBY:FAV_MOVIE");
				break;

case 'FAV_TVSHOW':
					$field=new Field('','FAV_TVSHOW');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JHOBBY:FAV_TVSHOW");
				break;

case 'FAV_VAC_DEST':
					$field=new Field('','FAV_VAC_DEST');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JHOBBY:FAV_VAC_DEST");
				break;

case 'GOING_ABROAD':
					$field=new Field('','GOING_ABROAD');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:GOING_ABROAD");
				break;

case 'GOTHRA':
					$field=new Field('','GOTHRA');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:GOTHRA");
				break;

case 'HANDICAPPED':
					$field=new Field('','HANDICAPPED');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("HANDICAPPED");
					$field->setTableName("JPROFILE:HANDICAPPED");
				break;

case 'HAVECHILD':
					$field=new Field('','HAVECHILD');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("havechild");
					$field->setTableName("JPROFILE:HAVECHILD");
				break;

case 'HAVE_CAR':
					$field=new Field('','HAVE_CAR');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:HAVE_CAR");
				break;

case 'HEIGHT':
					$field=new Field('','HEIGHT');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setTableName("JPROFILE:HEIGHT");
				break;

case 'HIJAB':
					$field=new Field('','HIJAB');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:HIJAB");
				break;

case 'HIJAB_MARRIAGE':
					$field=new Field('','HIJAB_MARRIAGE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:HIJAB_MARRIAGE");
				break;

case 'HIV':
					$field=new Field('','HIV');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:HIV");
				break;

case 'HOBBIES_BOOK':
					$field=new Field('','HOBBIES_BOOK');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_CUISINE':
					$field=new Field('','HOBBIES_CUISINE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_DRESS':
					$field=new Field('','HOBBIES_DRESS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_HOBBY':
					$field=new Field('','HOBBIES_HOBBY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_INTEREST':
					$field=new Field('','HOBBIES_INTEREST');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_LANGUAGE':
					$field=new Field('','HOBBIES_LANGUAGE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_MOVIE':
					$field=new Field('','HOBBIES_MOVIE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_MUSIC':
					$field=new Field('','HOBBIES_MUSIC');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOBBIES_SPORTS':
					$field=new Field('','HOBBIES_SPORTS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JHOBBY:HOBBY");
				break;

case 'HOROSCOPE_MATCH':
					$field=new Field('','HOROSCOPE_MATCH');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:HOROSCOPE_MATCH");
				break;

case 'INCOME':
					$field=new Field('','INCOME');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setTableName("JPROFILE:INCOME");
				break;

case 'ISD':
					$field=new Field('','ISD');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:");
				break;

case 'JOB_INFO':
					$field=new Field('','JOB_INFO');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:JOB_INFO");
				break;

case 'MANGLIK':
					$field=new Field('','MANGLIK');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:MANGLIK");
				break;

case 'MARRIED_WORKING':
					$field=new Field('','MARRIED_WORKING');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:MARRIED_WORKING");
				break;

case 'MATHTHAB':
					$field=new Field('','MATHTHAB');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:MATHTHAB");
				break;

case 'MESSENGER_CHANNEL':
					$field=new Field('','MESSENGER_CHANNEL');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("messenger_channel");
					$field->setTableName("JPROFILE:MESSENGER_CHANNEL");
				break;

case 'MESSENGER_ID':
					$field=new Field('','MESSENGER_ID');
					$field->setFieldType("text");
					$field->setConstraintClass("messenger_id");
					$field->setTableName("JPROFILE:MESSENGER_ID");
				break;

case 'MOTHER_OCC':
					$field=new Field('','MOTHER_OCC');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:MOTHER_OCC");
				break;

case 'MSTATUS':
					$field=new Field('','MSTATUS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("mstatus");
					$field->setTableName("JPROFILE:MSTATUS");
				break;

case 'MTONGUE':
					$field=new Field('','MTONGUE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setTableName("JPROFILE:MTONGUE");
				break;

case 'M_BROTHER':
					$field=new Field('','M_BROTHER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("sibling");
					$field->setTableName("JPROFILE:M_BROTHER");
				break;

case 'M_SISTER':
					$field=new Field('','M_SISTER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("sibling");
					$field->setTableName("JPROFILE:M_SISTER");
				break;

case 'NAKSHATRA':
					$field=new Field('','NAKSHATRA');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:NAKSHATRA");
				break;

case 'NAMAZ':
					$field=new Field('','NAMAZ');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:NAMAZ");
				break;

case 'NAME':
					$field=new Field('','NAME');
					$field->setFieldType("text");
					$field->setConstraintClass("stringName");
					$field->setTableName("NAME_OF_USER:NAME");
				break;

case 'NATURE_HANDICAP':
					$field=new Field('','NATURE_HANDICAP');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("NATURE_HANDICAP");
					$field->setTableName("JPROFILE:NATURE_HANDICAP");
				break;

case 'OCCUPATION':
					$field=new Field('','OCCUPATION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setTableName("JPROFILE:OCCUPATION");
				break;

case 'OFFER_TITHE':
					$field=new Field('','OFFER_TITHE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_CHRISTIAN:OFFER_TITHE");
				break;

case 'OPEN_TO_PET':
					$field=new Field('','OPEN_TO_PET');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:OPEN_TO_PET");
				break;

case 'OWN_HOUSE':
					$field=new Field('','OWN_HOUSE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:OWN_HOUSE");
				break;

case 'PARENTS_ZARATHUSHTRI':
					$field=new Field('','PARENTS_ZARATHUSHTRI');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_PARSI:PARENTS_ZARATHUSHTRI");
				break;

case 'PARENT_CITY_SAME':
					$field=new Field('','PARENT_CITY_SAME');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:PARENT_CITY_SAME");
				break;

case 'PASSWORD':
					$field=new Field('','PASSWORD');
					$field->setFieldType("password");
					$field->setConstraintClass("password");
					$field->setTableName("JPROFILE:PASSWORD");
				break;

case 'PG_COLLEGE':
					$field=new Field('','PG_COLLEGE');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE_EDUCATION:PG_COLLEGE");
				break;

case 'PHONE_MOB':
					$field=new Field('','PHONE_MOB');
					$field->setFieldType("mobile");
					$field->setConstraintClass("mobile");
					$field->setTableName("JPROFILE:ISD-isd,PHONE_MOB-mobile");
				break;

case 'PHONE_RES':
					$field=new Field('','PHONE_RES');
					$field->setFieldType("landline");
					$field->setConstraintClass("landline");
					$field->setTableName("JPROFILE:ISD-isd,STD-std,PHONE_RES-landline");
				break;

case 'PINCODE':
					$field=new Field('','PINCODE');
					$field->setFieldType("text");
					$field->setConstraintClass("pin");
					$field->setTableName("JPROFILE:PINCODE");
				break;

case 'PROFILE_HANDLER_NAME':
					$field=new Field('','PROFILE_HANDLER_NAME');
					$field->setFieldType("textarea");
					$field->setConstraintClass("stringName");
					$field->setTableName("JPROFILE:PROFILE_HANDLER_NAME");
				break;

case 'P_BTYPE':
					$field=new Field('','P_BTYPE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_btype");
					$field->setTableName("JPARTNER:PARTNER_BTYPE");
				break;

case 'P_CASTE':
					$field=new Field('','P_CASTE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_caste");
					$field->setTableName("JPARTNER:PARTNER_CASTE");
				break;

case 'P_CHALLENGED':
					$field=new Field('','P_CHALLENGED');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_challenged");
					$field->setTableName("JPARTNER:HANDICAPPED");
				break;

case 'P_CITY':
					$field=new Field('','P_CITY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_city");
					$field->setTableName("JPARTNER:PARTNER_CITYRES");
				break;

case 'P_COMPLEXION':
					$field=new Field('','P_COMPLEXION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_complexion");
					$field->setTableName("JPARTNER:PARTNER_COMP");
				break;

case 'P_COUNTRY':
					$field=new Field('','P_COUNTRY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_country");
					$field->setTableName("JPARTNER:PARTNER_COUNTRYRES");
				break;

case 'P_DIET':
					$field=new Field('','P_DIET');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_diet");
					$field->setTableName("JPARTNER:PARTNER_DIET");
				break;

case 'P_DRINK':
					$field=new Field('','P_DRINK');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_drink");
					$field->setTableName("JPARTNER:PARTNER_DRINK");
				break;

case 'P_EDUCATION':
					$field=new Field('','P_EDUCATION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_education");
					$field->setTableName("JPARTNER:PARTNER_ELEVEL_NEW");
				break;

case 'P_GENDER':
					$field=new Field('','P_GENDER');
					$field->setFieldType("radio");
					$field->setConstraintClass("string");
					$field->setTableName("JPARTNER:GENDER");
				break;

case 'P_HAGE':
					$field=new Field('','P_HAGE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_age");
					$field->setTableName("JPARTNER:HAGE");
				break;

case 'P_HDS':
					$field=new Field('','P_HDS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_dollar");
					$field->setTableName("JPARTNER:HINCOME_DOL");
				break;

case 'P_HHEIGHT':
					$field=new Field('','P_HHEIGHT');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_height");
					$field->setTableName("JPARTNER:HHEIGHT");
				break;

case 'P_HRS':
					$field=new Field('','P_HRS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_rupee");
					$field->setTableName("JPARTNER:HINCOME");
				break;

case 'P_LAGE':
					$field=new Field('','P_LAGE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPARTNER:LAGE");
				break;

case 'P_LDS':
					$field=new Field('','P_LDS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPARTNER:LINCOME_DOL");
				break;

case 'P_LHEIGHT':
					$field=new Field('','P_LHEIGHT');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_height");
					$field->setTableName("JPARTNER:LHEIGHT");
				break;

case 'P_LRS':
					$field=new Field('','P_LRS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPARTNER:LINCOME");
				break;

case 'P_MANGLIK':
					$field=new Field('','P_MANGLIK');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_manglik");
					$field->setTableName("JPARTNER:PARTNER_MANGLIK");
				break;

case 'P_MSTATUS':
					$field=new Field('','P_MSTATUS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_mstatus");
					$field->setTableName("JPARTNER:PARTNER_MSTATUS");
				break;

case 'P_MTONGUE':
					$field=new Field('','P_MTONGUE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_mtongue");
					$field->setTableName("JPARTNER:PARTNER_MTONGUE");
				break;

case 'P_NCHALLENGED':
					$field=new Field('','P_NCHALLENGED');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_nchallenged");
					$field->setTableName("JPARTNER:NHANDICAPPED");
				break;

case 'P_OCCUPATION':
					$field=new Field('','P_OCCUPATION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_occupation");
					$field->setTableName("JPARTNER:PARTNER_OCC");
				break;

case 'P_RELIGION':
					$field=new Field('','P_RELIGION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_religion");
					$field->setTableName("JPARTNER:PARTNER_RELIGION");
				break;

case 'P_SMOKE':
					$field=new Field('','P_SMOKE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_smoke");
					$field->setTableName("JPARTNER:PARTNER_SMOKE");
				break;

case 'QURAN':
					$field=new Field('','QURAN');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:QURAN");
				break;

case 'RASHI':
					$field=new Field('','RASHI');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:RASHI");
				break;

case 'READ_BIBLE':
					$field=new Field('','READ_BIBLE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_CHRISTIAN:READ_BIBLE");
				break;

case 'RELATION':
					$field=new Field('','RELATION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setTableName("JPROFILE:RELATION");
				break;

case 'RELIGION':
					$field=new Field('','RELIGION');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("mandatory");
					$field->setTableName("JPROFILE:RELIGION");
				break;

case 'RES_STATUS':
					$field=new Field('','RES_STATUS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:RES_STATUS");
				break;

case 'SAMPRADAY':
					$field=new Field('','SAMPRADAY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JP_JAIN:SAMPRADAY");
				break;

case 'SCHOOL':
					$field=new Field('','SCHOOL');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE_EDUCATION:SCHOOL");
				break;

case 'SECT':
					$field=new Field('','SECT');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("sect");
					$field->setTableName("JPROFILE:SECT");
				break;

case 'SHOWADDRESS':
					$field=new Field('','SHOWADDRESS');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SHOWADDRESS");
				break;

case 'SHOWMESSENGER':
					$field=new Field('','SHOWMESSENGER');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SHOWMESSENGER");
				break;

case 'SHOWMOBILE':
					$field=new Field('','SHOWMOBILE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:SHOWPHONE_MOB");
				break;

case 'SHOWPHONE':
					$field=new Field('','SHOWPHONE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:SHOWPHONE_RES");
				break;

case 'SMOKE':
					$field=new Field('','SMOKE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SMOKE");
				break;

case 'SPEAK_URDU':
					$field=new Field('','SPEAK_URDU');
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:SPEAK_URDU");
				break;

case 'SPOUSE':
					$field=new Field('','SPOUSE');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:SPOUSE");
				break;

case 'SPREADING_GOSPEL':
					$field=new Field('','SPREADING_GOSPEL');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_CHRISTIAN:SPREADING_GOSPEL");
				break;

case 'SUBCASTE':
					$field=new Field('','SUBCASTE');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:SUBCASTE");
				break;

case 'SUNNAH_BEARD':
					$field=new Field('','SUNNAH_BEARD');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:SUNNAH_BEARD");
				break;

case 'SUNNAH_CAP':
					$field=new Field('','SUNNAH_CAP');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:SUNNAH_CAP");
				break;

case 'THALASSEMIA':
					$field=new Field('','THALASSEMIA');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:THALASSEMIA");
				break;

case 'TIME_TO_CALL_END':
					$field=new Field('','TIME_TO_CALL_END');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("time_to_call");
					$field->setTableName("JPROFILE:TIME_TO_CALL_END");
				break;

case 'TIME_TO_CALL_START':
					$field=new Field('','TIME_TO_CALL_START');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("time_to_call");
					$field->setTableName("JPROFILE:TIME_TO_CALL_START");
				break;

case 'TRIM_BEARD':
					$field=new Field('','TRIM_BEARD');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_SIKH:TRIM_BEARD");
				break;

case 'T_BROTHER':
					$field=new Field('','T_BROTHER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("sibling");
					$field->setTableName("JPROFILE:T_BROTHER");
				break;

case 'T_SISTER':
					$field=new Field('','T_SISTER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("sibling");
					$field->setTableName("JPROFILE:T_SISTER");
				break;

case 'UMRAH_HAJJ':
					$field=new Field('','UMRAH_HAJJ');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:UMRAH_HAJJ");
				break;

case 'WEAR_TURBAN':
					$field=new Field('','WEAR_TURBAN');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_SIKH:WEAR_TURBAN");
				break;

case 'WEIGHT':
					$field=new Field('','WEIGHT');
					$field->setFieldType("radio");
					$field->setConstraintClass("integer");
					$field->setTableName("JPROFILE:WEIGHT");
				break;

case 'WORKING_MARRIAGE':
					$field=new Field('','WORKING_MARRIAGE');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:WORKING_MARRIAGE");
				break;

case 'WORK_STATUS':
					$field=new Field('','WORK_STATUS');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:WORK_STATUS");
				break;

case 'YOURINFO':
					$field=new Field('','YOURINFO');
					$field->setFieldType("textarea");
					$field->setConstraintClass("yourinfo");
					$field->setTableName("JPROFILE:YOURINFO");
				break;

case 'ZAKAT':
					$field=new Field('','ZAKAT');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_MUSLIM:ZAKAT");
				break;

case 'ZARATHUSHTRI':
					$field=new Field('','ZARATHUSHTRI');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JP_PARSI:ZARATHUSHTRI");
				break;
                            
case 'P_HAVECHILD':
					$field=new Field('','P_HAVECHILD');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_havechild");
					$field->setTableName("JPARTNER:CHILDREN");
				break;
case "NATIVE_COUNTRY":
          $field=new Field('','NATIVE_COUNTRY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("NATIVE_PLACE:NATIVE_COUNTRY");
					break;
				case "NATIVE_STATE":
          $field=new Field('','NATIVE_STATE');
          $field->setFieldType("dropdown");
					$field->setConstraintClass("native_place");
					$field->setTableName("NATIVE_PLACE:NATIVE_STATE");
					break;
				case "NATIVE_CITY":
          $field=new Field('','NATIVE_CITY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("native_place");
					$field->setTableName("NATIVE_PLACE:NATIVE_CITY");
					break;
case 'GOTHRA_MATERNAL':
					$field=new Field('','GOTHRA_MATERNAL');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:GOTHRA_MATERNAL");
				break;
case 'OTHER_UG_DEGREE':
					$field=new Field('','OTHER_UG_DEGREE');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE_EDUCATION:OTHER_UG_DEGREE");
				break;      
case 'OTHER_PG_DEGREE':
					$field=new Field('','OTHER_PG_DEGREE');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE_EDUCATION:OTHER_PG_DEGREE");
				break; 
case 'SUNSIGN':
					$field=new Field('','SUNSIGN');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SUNSIGN");
				break;
case 'ASTRO_PRIVACY':
					$field=new Field('','ASTRO_PRIVACY');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SHOW_HOROSCOPE");
				break;
case 'MOBILE_NUMBER_OWNER':
					$field=new Field('','MOBILE_NUMBER_OWNER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:MOBILE_NUMBER_OWNER");
				break;      
case 'PHONE_NUMBER_OWNER':
					$field=new Field('','PHONE_NUMBER_OWNER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:PHONE_NUMBER_OWNER");
				break;      
case 'ALT_MOBILE_NUMBER_OWNER':
					$field=new Field('','ALT_MOBILE_NUMBER_OWNER');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE_CONTACT:ALT_MOBILE_NUMBER_OWNER");
				break;      
case 'PHONE_OWNER_NAME':
					$field=new Field('','PHONE_OWNER_NAME');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:PHONE_OWNER_NAME");
				break;
case 'MOBILE_OWNER_NAME':
					$field=new Field('','MOBILE_OWNER_NAME');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:MOBILE_OWNER_NAME");
				break;
case 'ALT_MOBILE_OWNER_NAME':
					$field=new Field('','ALT_MOBILE_OWNER_NAME');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE_CONTACT:ALT_MOBILE_OWNER_NAME");
				break;
case 'SHOWALT_MOBILE':
    $field=new Field('','SHOWALT_MOBILE');
    $field->setFieldType("dropdown");
    $field->setConstraintClass("dropdown_not_req");
    $field->setTableName("JPROFILE_CONTACT:SHOWALT_MOBILE");
  break;
case 'SHOWPHONE_MOB':
					$field=new Field('','SHOWPHONE_MOB');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SHOWPHONE_MOB");
				break;
case 'SHOWPHONE_RES':
					$field=new Field('','SHOWPHONE_RES');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SHOWPHONE_RES");
				break;
case 'PARENT_PINCODE':
					$field=new Field('','PARENT_PINCODE');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:PARENT_PINCODE");
				break;      
case 'SHOW_PARENTS_CONTACT':
					$field=new Field('','SHOW_PARENTS_CONTACT');
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:SHOW_PARENTS_CONTACT");     
      break;
case 'PARENTS_CONTACT':
					$field=new Field('','PARENTS_CONTACT');
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:PARENTS_CONTACT");
				break;
case 'ID_PROOF_TYP':
                    $field=new Field('','ID_PROOF_TYP');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setTableName("JPROFILE:ID_PROOF_TYP");
				break;
case 'ID_PROOF_NO':
                    $field=new Field('','ID_PROOF_NO');
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setTableName("JPROFILE:ID_PROOF_NO");
				break;
case 'ID_PROOF_TYPE':
                    $field=new Field('','ID_PROOF_TYPE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("proof_type");
					$field->setTableName("VERIFICATION_DOCUMENT:PROOF_TYPE");
				break;
case 'ID_PROOF_VAL':
                    $field=new Field('','ID_PROOF_VAL');
					$field->setFieldType("text");
					$field->setConstraintClass("proof_val");
					$field->setTableName("VERIFICATION_DOCUMENT:PROOF_VAL");
				break;
case 'ADDR_PROOF_TYPE':
                    $field=new Field('','ADDR_PROOF_TYPE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("proof_type");
					$field->setTableName("VERIFICATION_DOCUMENT:PROOF_TYPE");
				break;
case 'ADDR_PROOF_VAL':
                    $field=new Field('','ADDR_PROOF_VAL');
					$field->setFieldType("text");
					$field->setConstraintClass("proof_val");
					$field->setTableName("VERIFICATION_DOCUMENT:PROOF_VAL");
                                break;
case 'P_STATE':
					$field=new Field('','P_STATE');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_state");
					$field->setTableName("JPARTNER:STATE");
				break;
case 'CITY_INDIA':
					$field=new Field('','CITY_INDIA');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_city_india");
					$field->setTableName("JPARTNER:CITY_INDIA");
				break;
case "DISPLAYNAME":
					$field=new Field('','DISPLAYNAME');
					$field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setTableName("NAME_OF_USER:DISPLAY");
				break;
case 'P_OCCUPATION_GROUPING':
					$field=new Field('','P_OCCUPATION_GROUPING');
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_occupation_grouping");
					$field->setTableName("JPARTNER:OCCUPATION_GROUPING");
				break;
                        case 'MSTATUS_PROOF':
                                        $field=new Field('','MSTATUS_PROOF');
					$field->setFieldType("text");
					$field->setConstraintClass("mstatus_proof");
					$field->setTableName("CRITICAL_INFO_CHANGED_DOCS:DOCUMENT_PATH");
				break;
}

				return($field);
			}
			}
