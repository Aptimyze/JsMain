<?php
/*
* This class will contain the  validation of form .....
* @author lavesh
*/
class MmmFormValidation
{
	/**
	* This section will handle validation related to create mail .....
	* @param arr key-value pair of form submitted.
	* @return error error-msg corresponding to keys. NULL if no error.
	*/	
	public static function validateCreateMailerForm($arr)
	{
		$error = NULL;
		$compulsoryFieldArr = array("mailer_name","client_name","mail_type","response_type","companyName_lr1","mailer_for","company");
		foreach($arr as $key => $value)
		{
			if(in_array($key,$compulsoryFieldArr) && MmmCommonValidation::valueExists($value))
				$error[$key] = ValidationErrorMsg::defaultError;
		}
		return $error;		
	}


	/**
	* This section will handle validation related to url-mail .....
	* @param arr key-value pair of form submitted.
	* @return error error-msg corresponding to keys. NULL if no error.
	*/	
	public static function validateSubmitUrlMail($arr)
	{
		$error = NULL;
		$compulsoryFieldArr = array("mailer_id","template_name","browserUrl","subject","f_email","f_name","stagger","mail_type","rl_reminder_date","hour","minute");
		foreach($arr as $key => $value)
		{
			if(in_array($key,$compulsoryFieldArr) && MmmCommonValidation::valueExists($value))
				$error[$key] = ValidationErrorMsg::defaultError;
			if($key=='subject'){
				$subject = $value;
				if(strstr($subject,'$')){
					$key_str = '';
					$true = 1;
                        		foreach(MmmConfig::$variableMapping as $k=>$v){
						$key_str .= '~$'.$v.'`,';
					}
					$preg_str = '/\$(.*?)`/';
					preg_match_all($preg_str,$subject,$matches);
					foreach($matches[1] as $k1=>$v1){
						if(!in_array($v1,MmmConfig::$variableMapping)){
                                                	$true=0;
							break;
						}

					}
					if(!$true){
						$key_str = substr($key_str,0,-1);
						$error[$key] = 'Invalid Smarty. Options are '.$key_str;
					}
				}
			}
		}
		if(!$error["browserUrl"])
		{
			$returnErr = MmmFormValidation::validateSubmitUrlContentForError($arr);	
			if($returnErr == 'E')
				$error["browserUrl"] = ValidationErrorMsg::smartyErrorInHtml;	
			else if($returnErr == 'D')
				$error["browserUrl"] = ValidationErrorMsg::fileDoesntExist;
			else if($returnErr == 'B')
				$error["browserUrl"] = ValidationErrorMsg::bodyTagMissing;	
		}
		return $error;		
	}

	/**
	* This section will handle validation related to hardcode mail .....
	* @param arr key-value pair of form submitted.
	* @return error error-msg corresponding to keys. NULL if no error.
	*/	
	public static function validateHardcodeMail($arr)
	{
		$error = NULL;
		$compulsoryFieldArr = array("mailer_id","template_name","subject","f_email","f_name","stagger","mail_type","data");
		foreach($arr as $key => $value)
		{
			if(in_array($key,$compulsoryFieldArr) && MmmCommonValidation::valueExists($value))
				$error[$key] = ValidationErrorMsg::defaultError;
			if($key=='subject'){
				$subject = $value;
				if(strstr($subject,'$')){
					$key_str = '';
					$true = 1;
                        		foreach(MmmConfig::$variableMapping as $k=>$v){
						$key_str .= '~$'.$v.'`,';
					}
					$preg_str = '/\$(.*?)`/';
					preg_match_all($preg_str,$subject,$matches);
					foreach($matches[1] as $k1=>$v1){
						if(!in_array($v1,MmmConfig::$variableMapping)){
                                                	$true=0;
							break;
						}

					}
					if(!$true){
						$key_str = substr($key_str,0,-1);
						$error[$key] = 'Invalid Smarty. Options are '.$key_str;
					}
				}
			}
		}
		return $error;		
	}

	/**
	* This function will validate the urlMail form .....
	* @param arr key-value pair of form submitted.
	* @return response char set if there is some error.
	*/
	public static function validateSubmitUrlContentForError($arr)
	{
		$error = NULL;
		$url      = $arr["browserUrl"];
		$mailerId = $arr["mailer_id"];
		$response = MmmCommonValidation::validateUrlForSmartyErrors($url,$mailerId);
		return $response;
	}
}
?>
