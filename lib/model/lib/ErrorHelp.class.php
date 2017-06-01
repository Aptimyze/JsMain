<?php
class ErrorHelp{
	    public static $ERR_EMAIL=array(1=>array('id'=>'err_email_del',"msg"=>"Provide your email in proper format, e.g. raj1984@gmail.com"),2=>array('id'=>'err_email_duplicate',"msg"=>"Duplicate"),3=>array('id'=>'err_email_req',"msg"=>"Provide your email in proper format, e.g. raj1984@gmail.com"),4=>array('id'=>'err_email_revoke','msg'=>"This Email is banned due to terms of use violation"),5=>array('id'=>'err_email_same','msg'=>"Both Emails are same"),
				);
	    public static $ERR_NAME_OF_USER=array(1=>array('id'=>'err_name_str',"msg"=>"Please enter characters only"),2=>array('id'=>'err_e',"msg"=>"Duplicate"),3=>array('id'=>'err_email_req',"msg"=>"Provide your email in proper format, e.g. raj1984@gmail.com"),4=>array('id'=>'err_invalid_name',"msg"=>"Please provide a valid name"),
				);
	    public static $ERR_STRING=array(1=>array('id'=>'nameOfUser',"msg"=>"Please provide a valid name"));
		public static $ERR_MESSENGER_ID=array(1=>array('id'=>'err_messenger_invalid',"msg"=>"Word not allowed. Please provide a valid messenger ID."),2=>array('id'=>'err_messenger_min',"msg"=>"Messenger ID should be at least 4 characters long."),3=>array('id'=>'err_messenger_pattern',"msg"=>"Please provide a valid messenger ID."),4=>array('id'=>'err_messenger_alpha',"msg"=>"At least one alphabet should be present in messenger ID."));
		
		public static $ERR_MESSENGER_CHANNEL=array(1=>array('id'=>'err_messenger_req',"msg"=>"Please provide type of messenger - GTalk, Yahoo ..etc."));
		
		public static $ERR_PINCODE=array(1=>array('id'=>'err_pin_req',"msg"=>"Please provide the Pincode of your residence."),2=>array('id'=>'err_pin_invalid',"msg"=>"Pincode you provided is invalid."),3=>array('id'=>'err_pin_delhi',"msg"=>"Please provide a pincode that belongs to Delhi."),4=>array('id'=>'err_pin_mumbai',"msg"=>"Please provide a pincode that belongs to Mumbai."),5=>array('id'=>'err_pin_pune',"msg"=>"Please provide a pincode that belongs to Pune."));
		
		public static $ERR_PHONE_MOB = array(1=>array('id'=>"err_mobile_invalid",'msg'=>"Provide a valid mobile number."),2=>array('id'=>"err_mobile_length_international",'msg'=>"International Mobile number should contain atleast 8 digits."),3=>array('id'=>"err_mobile_isd",'msg'=>"Please provide country code."),4=>array('id'=>'err_isd_code','msg'=>'Please provide valid country isd'),5=>array('id'=>"err_phone_revoke","msg"=>"This Phone is banned due to terms of use violation"),6=>array('id'=>"err_two_phone_num_exist","msg"=>"There are already two other profiles active on Jeevansathi with the same phone number."));
		
		public static $ERR_PHONE_RES = array(1=>array('id'=>"err_phone_invalid",'msg'=>"Provide a valid landline number."),2 => array('id'=>"err_phone_isd",'msg'=>"Please provide country code by selecting country."),3=>array('id'=>"err_landline_revoke","msg"=>"This Phone is banned due to terms of use violation"));
		
		public static $ERR_PASSWORD = array(1=>array('id'=>"err_pass_invalid",'msg'=>"Total length of the password needs to be 8 characters or more."),2 => array('id'=>"err_pass_common",'msg'=>"You cannot use this password because it is guessable. Please choose a tougher password to make your partner search safe."),3=>array('id'=>"err_pass_email",'msg'=>"Your password should not be guessable from your email id. Please choose a tougher password to make your partner search safe."));
		//public static $ERR_GENDER=array(1=>array('id'=>"gender_required",'msg'=>"<div class=\"err_msg\">Please choose a gender.</div>"));
		//public static $ERR_HEIGHT='<div class="err_msg">Please select a height.</div>';
		public static $ERR_DOB=array(1=>array('id'=>"err_gender",'msg'=>'Please choose a gender.'));
		public static $ERR_DOB_MINAGE=array("minAge"=>'Please select valid minimum age.');
		public static $ERR_COUNTRY_RES=array(1=>array('id'=>"err_country_res",'msg'=>'Please provide a location.'));
		//public static $ERR_CITY_RES='<div class="err_msg">Please select a city.</div>';
		//public static $ERR_HAVECHILD=array(1=>array('id'=>'haveChild_required','msg'=>"<div class=\"err_msg\">Please Select Have Children</div>"));
		//public static $ERR_RELIGON =array(1=>array('id'=>"religion_error_required",'msg'=>"<div class=\"err_msg\">Please Select a Religion</div>"));
		//public static $ERR_MTONGUE= array(1=>array('id'=>"mtongue_required",'msg'=>"<div class=\"err_msg\">Please select a mother tongue.</div>"));
		public static $ERR_CASTE=array(1=>array('id'=>"caste_error_muslim",'msg'=>"Please provide a math'thab."),
									2=>array('id'=>"caste_error_christian",'msg'=>'Please provide a sect.'),
									3=>array('id'=>"caste_error_hindu",'msg'=>'Please provide a caste.'));
		public static $ERR_MSTATUS=array(2=>array('id'=>"mstatus_error_muslim",'msg'=>"Please choose married only if you are muslim."),3=>array('id'=>"mstatus_error_muslim_male",'msg'=>'Please choose married only if you are muslim male.'),4=>array('id'=>"mstatus_error_gender",'msg'=>'please provide the gender for marital status.'),5=>array('id'=>"mstatus_error_religion",'msg'=>'Please provide religion for marital status'));		
		public static $ERR_FILEVAL=array(1=>array('id'=>"err_file_type",'msg'=>'Please enter a valid format'),2=>array('id'=>"err_file_size",'msg'=>'Please enter document with size less then 5MB'));
		
									
		public static $ERR_REQUIRED = array('email'=>'Please provide an email.','password'=>'Please provide a password.','relationship'=>'Please choose the person for whom you are looking for.','gender'=>'Please provide a gender.','mtongue'=>'Please provide a mother tongue.','height'=>'Please provide a height.','mstatus'=>'Please provide your marital status.','religion'=>'Please provide a Religion.','city_res'=>'Please provide a city.','havechild'=>'Please provide Have Children.','dtofbirth'=>'Please provide Date of Birth.','caste'=>'Please provide a caste.','edu_level_new'=>' Please provide a degree.','occupation'=>'Please provide a work area.','income'=>'Please provide a income range.','yourinfo'=>'For the benefit of your matches, please write about yourself in at least 100 letters','P_LHEIGHT'=>'Please provide a low height.','P_HHEIGHT'=>'Please provide a high height.','name_of_user'=>'Please provide the name of the person for whom the profile is being created','mstatus_proof'=>'Please provide divorced document.');
		
		public static $ERR_P_HAGE=array(1=>array("id"=>"p_hage_err","msg"=>"Please enter valid Age range !"));
		public static $ERR_P_HHEIGHT=array(1=>array("id"=>"p_hheight_err","msg"=>"Please enter valid Height range !"));
		public static $ERR_P_HRUPEE=array(1=>array("id"=>"p_hrupee_err","msg"=>"Maximum income should be greater than minimum income."));
		public static $ERR_P_HDOLLAR=array(1=>array("id"=>"p_hdollar_err","msg"=>"Maximum income should be greater than minimum income."));
		public static $ERR_FAMILY_INCOME_=array(1=>array("id"=>"p_hdollar_err","msg"=>"FAMILY INCOME"));
		public static $ERR_TIME_TO_CALL=array(1=>array("id"=>"time_to_call_err","msg"=>"Please provide valid time to call"));
		public static $ERR_COUNTRY_CITY=array(1=>array('id'=>"err_country_res",'msg'=>'Please provide a valid country-city combination.'));
		public static $ERR_ISDCODE=array(1=>array('id'=>"err_isd_code",'msg'=>'Please provide a valid isd code.'));
		const INVALID_VALUE_ERR = "Please provide a valid value.";
		
		public static $DPP_ERROR=array(
		'P_COUNTRY'=>'Please provide a valid country.',
		'P_CITY'=>'Please provide a valid city.',
		'P_CASTE'=>'Please provide a valid caste.',
		'P_RELIGION'=>'Please provide a valid religion.',
		'P_MSTATUS'=>'Please provide a valid marital status.',
		'P_MTONGUE'=>'Please provide a valid mother tongue.',
		'P_MANGLIK'=>'Please provide a valid manglik information.',
		'P_DIET'=>'Please provide a valid information about your partner diet.',
		'P_SMOKE'=>'Please provide a valid information about your partner smoke.',
		'P_DRINK'=>'Please provide a valid information about your partner drink.',
		'P_COMPLEXION'=>'Please provide a valid value(Complexion).',
		'P_BTYPE'=>'Please provide a valid value(Body Type).',
		'P_CHALLENGED'=>'Please provide a valid value(Challenged).',
		'P_NCHALLENGED'=>'Please provide a valid value(Nature of Challenged).',
		'P_EDUCATION'=>'Please provide a valid value(Education).',
		'P_OCCUPATION'=>'Please provide a valid value(Occupation).',
		'P_AGE'=>'Please provide a valid range of Age.',
    'P_HHEIGHT'=>'Please enter valid Height range!.',
    'P_LHEIGHT'=>'Please enter valid Height range!.',
		);
	public static function getErrorArray($fieldArr,$type=''){
		foreach($fieldArr as $field){
			$var_name="ERR_$field";
			if(isset(self::$$var_name)){
				if($type=='Mob'){
					foreach(self::$$var_name as $error)
						$error_div.="<div id=\"".$error[id]."\"><span class=\"err_msg\"><small></small>".$error[msg]."</span></div>";
				}
				else{
					foreach(self::$$var_name as $error){
						if($var_name == 'ERR_MESSENGER_ID' || $var_name == 'ERR_MESSENGER_CHANNEL')
							$error_div.="<div id=\"".$error[id]."\"><div class=\"err_msg\" style=\"text-align:left;font-weight:normal;width:200px;\">".$error[msg]."</div></div>";
						else if($var_name == 'ERR_YOURINFO')
							$error_div.="<div id=\"".$error[id]."\"><label>&nbsp;</label><div class=\"err_msg\" style=\"width:427px;\">".$error[msg]."</div></div>";
						else
							$error_div.="<div id=\"".$error[id]."\"><label>&nbsp;</label><div class=\"err_msg\">".$error[msg]."</div></div>";
					}
				}
			}
			$key = strtolower($field);
			if(array_key_exists($key, self::$ERR_REQUIRED))
			{
				if($type=='Mob')
					$error_div.="<div id=\"".$key."_required\"><span class=\"err_msg\"><small></small>".self::getDefaultMessage($field)."</span></div>";
				else{
				if($key== 'yourinfo')
					$error_div.="<div id=\"$key"."_required\"><label>&nbsp;</label><div class=\"err_msg\" style=\"width:340px;\">".self::getDefaultMessage($field)."</div></div>";
				else
					$error_div.="<div id=\"$key"."_required\"><label>&nbsp;</label><div class=\"err_msg\">".self::getDefaultMessage($field)."</div></div>";
				}
			}
			
		}
		return $error_div;
	}
    public static function getErrorArrayByField($field){
		$var_name="ERR_$field";
		$arr= array();
			foreach (self::$$var_name as $error){
				$arr[$error[id]]=$error[msg];
			}
			return $arr;
	}
	public static function getHelpArray($fieldArr){
		$arr=array();
		foreach($fieldArr as $field){
			$var_name="HELP_$field";
			if(isset(self::$$var_name)){
				$arr[strtolower($field)]=self::$$var_name;
			}
		}
		return $arr;
	}
	public static function getDefaultMessage($field)
	{
		return self::$ERR_REQUIRED[strtolower($field)];;	
	}
	public static function getDPP_ERROR($szKey)
	{
		if(array_key_exists(strtoupper($szKey),self::$DPP_ERROR))
			return self::$DPP_ERROR[strtoupper($szKey)];
		
		return self::INVALID_VALUE_ERR;
	}
}
?>
