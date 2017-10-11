<?php
//This class is used to validate the type of values coming in the search parameters. Validation of alphabets,numerals,spaces,comma etc

class SearchInputValidation
{
	public function __construct()
	{
	}

	public static function validateInput($label,$value)
	{
		$flag = 1;
		$pattern1 = "/^([A-Z ,_])+$/";
		$pattern2 = "/^([A-Za-z0-9 ,\-._&])+$/";
		$pattern3 = "/^([A-Z0-9 ,])+$/";
		$pattern4 = "/^([0-9 ])+$/";
		$pattern5 = "/^([A-Z0-9 ,])+$/";
		$pattern6 = "/^([A-Z0-9 \-#,_])+$/";
		$keyword_allowed_array = array("DONT_MATTER","O","DM","undefined");

		if(strstr(SearchConfig::$possibleSearchParamters,$label) && $value && !in_array($value,$keyword_allowed_array))
		{
			if($label == "GENDER" || $label == "MSTATUS" || $label == "HAVEPHOTO" || $label == "MANGLIK" || $label == "HAVECHILD" || $label == "DIET" || $label == "SMOKE" || $label == "DRINK" || $label == "HIV" || $label == "HIJAB_MARRIAGE" || $label == "SPEAK_URDU" || $label == "ZARATHUSHTRI" || $label == "HOROSCOPE" || $label == "AMRITDHARI" || $label == "CUT_HAIR" || $label == "WEAR_TURBAN" || $label == "LIVE_PARENTS" || $label == "MARRIED_WORKING" || $label == "GOING_ABROAD" || $label == "VIEWED" || $label == "SORT_LOGIC" || $label == "NEWSEARCH_CLUSTERING" || $label == "ONLINE" || $label == "WIFE_WORKING" || $label == "MSTATUS_IGNORE" || $label == "HIV_IGNORE" || $label == "MANGLIK_IGNORE" || $label == "KNOWN_COLLEGE_IGNORE")
			{
				if(!preg_match($pattern1,$value))
                                        $flag = 0;
                                else
                                        $flag = 1;
			}
			elseif($label == "CASTE" || $label == "MTONGUE" || $label == "OCCUPATION" || $label == "OCCUPATION_IGNORE"|| $label == "BTYPE" || $label == "COMPLEXION" || $label == "RELATION" || $label == "COUNTRY_RES" || $label == "RELIGION" || $label == "EDU_LEVEL_NEW" || $label == "SAMPRADAY" || $label == "MATHTHAB" || $label == "NATURE_HANDICAP" || $label == "INCOME" || $label == "EDUCATION_GROUPING" || $label == "LAST_ACTIVITY" || $label == "OCCUPATION_GROUPING" || $label == "INDIA_NRI" || $label == "CASTE_GROUP" || $label == "CASTE_DISPLAY" || $label == "WORK_STATUS" || $label == "PROFILE_ADDED" || $label == "EDU_LEVEL")
			{
				if(!preg_match($pattern3,$value) && !in_array($value,$keyword_allowed_array))
                                        $flag = 0;
                                else
                                        $flag = 1;
			}
			elseif($label == "SUBCASTE" || $label == "KEYWORD" || $label == "KEYWORD_TYPE")
			{
				if(!preg_match($pattern2,$value))
                                        $flag = 0;
                                else
                                        $flag = 1;
			}
			elseif($label == "LAGE" || $label == "HAGE" || $label == "LHEIGHT" || $label == "HHEIGHT" || $label == "LINCOME" || $label == "HINCOME" || $label == "LINCOME_DOL" || $label == "HINCOME_DOL")
			{
				if(!preg_match($pattern4,$value))
                                        $flag = 0;
                                else
                                        $flag = 1;
			}
			elseif($label == "HANDICAPPED" || $label == "STATE" || $label == "NATIVE_STATE" || $label == "CITY_INDIA" || $label == "SEARCH_TYPE" || $label == "CITY_RES" || $label == "HANDICAPPED_IGNORE" || $label == "KNOWN_COLLEGE")
			{
				if(!preg_match($pattern5,$value))
                                        $flag = 0;
                                else
                                        $flag = 1;
			}
			elseif($label == "LAST_LOGIN_DT" || $label == "NoRelaxParams")
			{
				if(!preg_match($pattern6,$value))
                                        $flag = 0;
                                else
                                        $flag = 1;
			}
			else
				$flag = 1;	
		}
		else
			$flag = 1;

		if($flag == 0)
		{
			/*
			global $_SERVER;
			$date = date("Y-m-d");
			$file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/searchValueCheck_".$date.".txt","a");
			$errString = "LABEL = ".$label." | VALUE = ".$value;
			$http_msg=print_r($_SERVER,true);
			$errString = $http_msg."-->".$errString;
			fwrite($file,$errString."\n");
			fclose($file);
			*/
			/*
			$http_msg=print_r($_SERVER,true);
			if($label!="MTONGUE" || !strstr($value,"#"))
				ValidationHandler::getValidationHandler("",$errString."----".$http_msg);
			*/
		}
		return $flag;
	}
}
?>
