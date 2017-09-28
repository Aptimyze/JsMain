<?php
class IncompleteLib
{
	public static function isProfileIncomplete($profile)
	{
		$after_login = 0;
		$username = $profile->getUSERNAME();
		$password = $profile->getPASSWORD();
		$gender = $profile->getGENDER();
		$religion = $profile->getRELIGION();
		$caste = $profile->getCASTE();
		$mtongue = $profile->getMTONGUE();
		$mstatus = $profile->getMSTATUS();
		$dtofbirth = $profile->getDTOFBIRTH();
		$occupation = $profile->getOCCUPATION();
		$edu_level_new = $profile->getEDU_LEVEL_NEW();
		$country_res = $profile->getCOUNTRY_RES();
		$city_res = $profile->getCITY_RES();
		$height = $profile->getHEIGHT();
		$email = $profile->getEMAIL();
		$relation = $profile->getRELATION();
		$income = $profile->getINCOME();
		$phone_res = $profile->getPHONE_RES();
		$phone_mob = $profile->getPHONE_MOB();
		$yourinfo = $profile->getYOURINFO();
                $activated = $profile->getACTIVATED();
		//If country_res is not india and USA, city can be blank
	 	if($country_res!=51)
			$city_res=true;
		if(strlen($yourinfo)<100 && $activated!='Y')
			$after_login=1;
		else if(!$username||!$password||!$gender||!$religion||!$caste||!$mtongue||!$mstatus||$dtofbirth== '0000-00-00'||!$occupation||!$edu_level_new||!$country_res||(!$city_res && $city_res !=0)||!$height||!$email||!$relation||!$income||(!$phone_res && !$phone_mob))
			$after_login=1;
		return $after_login;
	}
	public static function incompleteFieldsOfProfile($profile)
	{
		$incompleteFields=array();
		if(!$profile->getUSERNAME())
			$incompleteFields[]="USERNAME";
		if(!$profile->getPASSWORD())
			$incompleteFields[]="PASSWORD";
		if(!$profile->getGENDER())
			$incompleteFields[]="GENDER";
		if(!$profile->getRELIGION())
			$incompleteFields[]="RELIGION";
		if(!$profile->getCASTE())
			$incompleteFields[]="CASTE";
		if(!$profile->getMTONGUE())
			$incompleteFields[]="MTONGUE";
		if(!$profile->getMSTATUS())
			$incompleteFields[]="MSTATUS";
		if(!$profile->getDTOFBIRTH())
			$incompleteFields[]="DTOFBIRTH";
		if(!$profile->getOCCUPATION())
			$incompleteFields[]="OCCUPATION";
		if(!$profile->getEDU_LEVEL_NEW())
			$incompleteFields[]="EDU_LEVEL_NEW";
		$country_res=$profile->getCOUNTRY_RES();
		if(!$country_res)
			$incompleteFields[]="COUNTRY_RES";
		if((!$profile->getCITY_RES() && $profile->getCITY_RES()!='0') && ($country_res==51 || $country_res ==128))
			$incompleteFields[]="CITY_RES";
		if(!$profile->getHEIGHT())
			$incompleteFields[]="HEIGHT";
		if(!$profile->getEMAIL())
			$incompleteFields[]="EMAIL";
		if(!$profile->getRELATION())
			$incompleteFields[]="RELATION";
		if(!$profile->getINCOME())
			$incompleteFields[]="INCOME";
		if( !$profile->getPHONE_RES() && !$profile->getPHONE_MOB())
			$incompleteFields[]="PHONE";
		if(strlen($profile->getYOURINFO())<100 && $profile->getACTIVATED()!='Y')
			$incompleteFields[]="YOURINFO";
		return $incompleteFields;
	}
	public static function incompleteFieldsFromArray($paramArr)
	{
		$incompelteFields=array();
		$compulsory_fields=array("USERNAME","PASSWORD","GENDER","RELIGION","CASTE","MTONGUE","MSTATUS","DTOFBIRTH","OCCUPATION","EDU_LEVEL_NEW","COUNTRY_RES","CITY_RES","HEIGHT","EMAIL","RELATION","INCOME","PHONE_RES","PHONE_MOB");
		foreach($paramArr as $key=>$value){
			if(in_array($key,$compulsory_fields)){
				if($key=="YOURINFO"){
					if(strlen($value)<100)
						$incompleteFields[]=$key;
				}
				elseif($key=="PHONE_MOB" || $key =="PHONE_RES"){
					if(!$paramArr[PHONE_MOB] && !$paramArr[PHONE_RES])
						$incompleteFields[]="PHONE";
				}
				elseif($key=="CITY_RES"){
					if($paramArr["COUNTRY_RES"]== "51" || $paramArr["COUNTRY_RES"]== "128")
						if(!$value && $value !=='0')
							$incompleteFields[]="CITY_RES";
				}
				elseif(!$value)
					$incompleteFields[]=$key;
			}
		}
		return $incompleteFields;
	}
}
