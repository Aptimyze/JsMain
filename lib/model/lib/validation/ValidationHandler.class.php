<?php
/**
* This class will handle validation result   
* @author Reshu Rajput / Lavesh Rawat
*/
class ValidationHandler
{

	/**
	* This function will check if value(comma seperated) exists in an array
	* @param value value to be searched(comma sepreated)
	* @param arr we search on array keys for O(1) complexity
	*/
	static public function valuesExistsInArr($value,$arr,$checkValueInsteadOfKey='')
	{
		if($value)
		{
			$searchArr = explode(",",$value);
			foreach($searchArr as $k=>$v)
			{
				if($checkValueInsteadOfKey)
				{
					if(!in_array($v,$arr))
						return false;
				}
				elseif(!array_key_exists($v,$arr))
					return false;
			}
		}
		return true;
	}

	/*This function is used to handle the validation provided to this function
	*@param default : If its not set this function will set header status to 400 else case need to be handled 
	*@param errorString : This is the error message for logging or exception throwinf purpose to identify reason of error
	*@param dieFlag : If its set then exception is thrown to stop the execution else logging is done
	*/ 
	static public function getValidationHandler($default="",$errorString="",$dieFlag="")
	{
		return;
		if($errorString)
		{
			$errorString = "Validation Error: ".$errorString."-->>".FetchClientIP()."<<--";
			$errVal = print_r(sfContext::getInstance()->getRequest()->getParameterHolder()->getAll(),true);	
			$errorString = $errorString.$errVal;
			if($dieFlag)
			{
				if(!strstr($_SERVER['PHP_SELF'],'symfony_index.php'))
				{
					jsException::log($errorString);	
					if(!function_exists('logError'))
						include_once(JsConstants::$docRoot."/profile/connect_functions.inc");
					logError("","","ShowErrTemplate");
				}	
				else	
					throw new jsException("",$errorString);
			}
			else
				jsException::log($errorString);	
		}
		/*if($default=="")
                {
                        header("Status: 400 Not Found");
                        header('HTTP/1.0 400 Not Found');
                }
		*/
	}

	/**
	* This function checks if value is an accpetable value for gender
	*/
	static public function validateGender($value)
	{
		if(!$value)return true;
		return self::valuesExistsInArr($value,array_fill_keys(array('M','F'),1));	
	}

	/**
	* This function checks if value is an accpetable value for religion
	*/
	static public function validateReligion($value,$allowSomeValuesArr='')
	{
		if(!$value)return true;

		if($allowSomeValuesArr)
		{
                        $tempArr = FieldMap::getFieldLabel("religion","",1);
                        if(!$tempArr)
                                return false;
                        $arr = $tempArr + $allowSomeValuesArr;
                        return self::valuesExistsInArr($value,$arr);
		}
		return self::valuesExistsInArr($value,FieldMap::getFieldLabel("religion","",1));	
	}

	/**
	* This function checks if value is an accpetable value for caste
	*/
	static public function validateCaste($value,$allowSomeValuesArr='')
	{
		if(!$value)return true;
                if($allowSomeValuesArr)
                {
                        $tempArr = FieldMap::getFieldLabel("caste","",1);
                        if(!$tempArr)
                                return false;
                        $arr = $tempArr + $allowSomeValuesArr;
                        return self::valuesExistsInArr($value,$arr);
                }
		return self::valuesExistsInArr($value,FieldMap::getFieldLabel("caste","",1));	
	}

	/**
	* This function checks if value is an accpetable value for mtongue/community
	*/
	static public function validateMtongue($value,$allowSomeValuesArr='')
	{
		if(!$value)return true;
                if($allowSomeValuesArr)
                {
                        $tempArr = FieldMap::getFieldLabel("community","",1);
                        if(!$tempArr)
                                return false;
                        $arr = $tempArr + $allowSomeValuesArr;
                        return self::valuesExistsInArr($value,$arr);
                }
		return self::valuesExistsInArr($value,FieldMap::getFieldLabel("community","",1));	
	}

	/**
	* This function checks if value is an accpetable value for photo
	*/
	static public function validatePhoto($value)
	{
		if(!$value)return true;
		return self::valuesExistsInArr($value,array_fill_keys(array('Y','N','U'),1));	
	}


	/**
	* This function checks if value is an accpetable value for age
	*/
	static public function validateAge($value)
	{
		if($value)
			if(is_numeric($value))
				if(($value<18 || $value>100))
					return false;
				else
					return true;
			else
				return false;
		return true;
	}
	/*
        * This function checks if value is an accpetable value for height
        */
        static public function validateHeight($value)
        {
                if(!$value)return true;
                return self::valuesExistsInArr($value,FieldMap::getFieldLabel("height","",1));
        }
	/*
	*
	*/

	/*
        * This function checks if value is an accpetable value for income
        */
        static public function validateIncome($value)
        {
                if(!$value)return true;
                return self::valuesExistsInArr($value,FieldMap::getFieldLabel("hincome","",1));
        }
        /*
        *
        */


	static public function validateDropdown($value,$nameofarray)
	{
		//Validate $value
		if(str_replace("'","",$value)!=$value)
		{
			$cnt=count(explode(",",$value));
			$cntqut=count(explode("'",$value))-1;
			if($cnt*2!=$cntqut)
			return false;
		}

		$value=str_replace("'","",$value);
		if($nameofarray)
		{
			if(!$value)return true;
        	        return self::valuesExistsInArr($value,FieldMap::getFieldLabel("$nameofarray","",1));
			
		}
	}
	static public function validateProfileChecksum($value)
	{
		if(!$value)return false;
		return JsCommon::getProfileFromChecksum($value);
	}
	/**
	* This function will check if value consists of number and spaces only
	* @param value value to be validated
	* return false if not validated else string without spaces.
	*/
	static public function validateNumberNSpaces($value = '')
	{
		if($value)
		{
			$value = preg_replace('/\s+/', '', $value);
			if(!is_numeric($value)){
				return false;
			}else{
				return $value;
			}
		}
		return false;
	}

}
