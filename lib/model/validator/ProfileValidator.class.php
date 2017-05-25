<?php
class ProfileValidator
{
	private $profile;
	private $error;

	function __construct(Profile $profile)
	{
		$this->profile=$profile;
	}
	function validateAll()
	{
	}
	public function __call($name,$arguments)
	{
		if(preg_match("/^validate/",$name)){
			$funcName=str_replace("validate","get",$name);
			$labelName=str_replace("validate","",$name);
			if(ProfileValidator::isObjectiveField($labelName))
			{
				$fieldMapKey=ObjectiveFieldMap::getFieldMapKey($lableName);
				if(array_key_exists($this->profile->$funcName(),FieldMap::getFieldLabel($fieldMapKey,'',1))){
					return false;
				}
				else{
					return ErrorType::INVALID_DROP_DOWN;
					$this->incorrectFields[]=$labelName;
				}
			}
			elseif(self::isTextField($labelName)){
				if(self::checkText($labelName))
					return false;
				else{
					return ErrorType::INVALID_TEXT;
					$this->incorrectFields[]=$labelName;
				}
			}
		}
	}
	public static function getIncorrectFields($paramArr)
	{
		$incorrectFields=IncompleteLib::incompleteFieldsFromArray($paramArr);
		if(!$incorrectFields)
			$incorrectFields=array();
		foreach($paramArr as $key=>$val){
			if(!in_array($key,$incorrectFields)){
				if($val){
			if(self::isObjectiveField($key)){
				$fieldMapKey=ObjectiveFieldMap::getFieldMapKey($key);
				$all_values=FieldMap::getFieldLabel($fieldMapKey,'',1);
					if(is_array($all_values))
						if(!array_key_exists($val,$all_values))
						$incorrectFields[]=$key;
			}
			elseif(self::isTextField($key)){
				if(self::checkText($key,$paramArr)){
						$incorrectFields[]=$key;
				}
			}
			}
				}
		}
		if(in_array('ID_PROOF_TYP',$incorrectFields))
			$incorrectFields[]="ID_PROOF_NO";
		if(in_array('ID_PROOF_NO',$incorrectFields))
			$incorrectFields[]="ID_PROOF_TYP";
		return $incorrectFields;
	}
	public static function isObjectiveField($field)
	{
		$objectiveFields=array(
			'GENDER',
			'RELIGION',
			'CASTE',
			'MANGLIK',
			'MTONGUE',
			'MSTATUS',
			'OCCUPATION',
			'COUNTRY_RES',
			'CITY_RES',
			'HEIGHT',
			'EDU_LEVEL',
			'RELATION',
			'COUNTRY_BIRTH',
			'DRINK',
			'SMOKE',
			'HAVECHILD',
			'RES_STATUS',
			'BTYPE',
			'COMPLEXION',
			'DIET',
			'INCOME',
			'HANDICAPPED',
			'FAMILY_BACK',
			'EDU_LEVEL_NEW',
			'MARRIED_WORKING',
			'PARENT_CITY_SAME',
			'FAMILY_VALUES',
			'MOTHER_OCC',
			'T_BROTHER',
			'T_SISTER',
			'M_BROTHER',
			'M_SISTER',
			'FAMILY_TYPE',
			'FAMILY_STATUS',
			'BLOOD_GROUP',
			'CITIZENSHIP',
			'HIV',
			'NATURE_HANDICAP',
			'LIVE_WITH_PARENTS',
			'WORK_STATUS',
			'HOROSCOPE_MATCH',
			'SPEAK_URDU',
			'RASHI',
			'FAMILY_INCOME',
			'THALASSEMIA',
			'GOING_ABROAD',
			'OPEN_TO_PET',
			'HAVE_CAR',
			'OWN_HOUSE',
			'SECT',
			'ID_PROOF_TYP',
		);
		if(in_array($field,$objectiveFields))
			return true;
		else 
			return false;
	}
	public static function isTextField($field)
	{
		$textFields=array(
			'GOTHRA',
			'NAKSHATRA',
			'MESSENGER_ID',
			'MESSENGER_CHANNEL',
			'CONTACT',
			'SUBCASTE',
			'YOURINFO',
			'FAMILYINFO',
			'SPOUSE',
			'EDUCATION',
			'PINCODE',
			'FATHER_INFO',
			'SIBLING_INFO',
			'JOB_INFO',
			'ANCESTRAL_ORIGIN',
			'PROFILE_HANDLER_NAME',
			'PARENT_PINCODE',
			'GOTHRA_MATERNAL',
			'COMPANY_NAME',
			'ID_PROOF_NO',
		);
		if(in_array($field,$textFields))
			return true;
		else 
			return false;
	}
	public static function checkText($field,$values){
		switch($field){
		case 'ID_PROOF_NO':
		{
			switch($values[ID_PROOF_TYP]){
			case 'N':
				$pattern="/^[a-zA-Z]{5}\d{4}[a-zA-Z]$/";
				break;
			case 'P':
				$pattern="/^[a-zA-Z]\d{7}$/";
				break;
			case 'U':
				$pattern="/^\d{12}$/";
				break;
			case 'V':
				$pattern="/^[a-zA-Z]{3}\d{7}$/";
				break;
			case 'D':
				if(strlen($values[ID_PROOF_NO])>18)
					$invalid=true;
				$pattern="/.*[^\s].*[^\s].*[^\s].*[^\s].*/";
				break;
			default:
				$pattern="/.*/";
				break;
			}
			if(!preg_match($pattern,$values[ID_PROOF_NO]))
				$invalid=true;
			return $invalid;
		}
		break;
	default:
		return false;
		}
	}
	public function hasError()
		{
			if(count($this->incorrectFields))
				return true;
		}
}
