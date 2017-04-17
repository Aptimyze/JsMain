<?php

/**
 * api actions.
 * AppRegV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ForgotloginV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$responseData = array();
		$email=$request->getParameter("email");
		$email = trim($email);

		$apiObj=ApiResponseHandler::getInstance();
		// EMAIL validations
		if(!$this->validate($email))
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_ERR);
		}
		else
		{
			$dbJprofile= new JPROFILE();
			$SmsObj = new newjs_SMS_DETAIL();
			$MultipleProfilesPerPhone = 0;
			$SingleProfileFound = 0;
			if($this->flag == 'E')
			{
				$data=$dbJprofile->get($this->finalString,"EMAIL","USERNAME,EMAIL,ACTIVATED,PROFILEID,MOB_STATUS");
				$SmsCount =$SmsObj->getCount("FORGOT_PASSWORD", $data['PROFILEID']);
			}
			else if($this->flag == 'M')
			{
				for ($i=10; $i > 6 && !($MultipleProfilesPerPhone || $SingleProfileFound); $i--) 
				{ 
				
					$phone_mob= substr($this->finalString, -$i);
					$arr=array('PHONE_MOB'=>"'$phone_mob'", 'MOB_STATUS'=>'Y');
					$excludeArr=array('ACTIVATED'=>"'D'");
					$data=$dbJprofile->getArray($arr,$excludeArr,'',"USERNAME,EMAIL,ACTIVATED,PROFILEID,MOB_STATUS");
					if(count($data) == 1)
					{
						//  1 unique profile found
						$data = $data[0];
						$SmsCount =$SmsObj->getCount("FORGOT_PASSWORD", $data['PROFILEID']);
						$SingleProfileFound = 1;
					}
					elseif(count($data) > 1)
					{
						$MultipleProfilesPerPhone = 1;
					}
				
				}
			}
			$data['SmsCount'] = $SmsCount;
			if($this->flag == 'M')
			{
				if ($MultipleProfilesPerPhone)
				{
					$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_PHONE_ERR);
					$apiObj->generateResponse();
					die;
				}
			}
			if($data[EMAIL])
			{
				if($data[ACTIVATED]!='D')
				{
					include_once(sfConfig::get("sf_web_dir")."/profile/sendForgotPasswordLink.php");
					sendForgotPasswordLink($data);
					if($data['SmsCount'] >= 5 || $data['MOB_STATUS']!='Y')
					{
						$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_SMSLIMIT_SUCCESS);
					}
					else
					{
						$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_SUCCESS);
					}
				}
				else
					$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_DELETED);
			}
			else
			{
				$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_ERR);
			}
			
		}
			$apiObj->generateResponse();
		die;
	}
	public function validate($email)
	{
		
		$regex = "/^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})/";
		if($email == '')
		{
			return false;
		}
		else if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$this->flag='E';
			$this->finalString=$email;
			return true;
		}
		else 
		{
			if(strpos($email, '+')===0)
				$email=substr($email, 1);
			$email=$this->replaceFirstOccurence('-','',$email);
			$email = ltrim($email,'0');
			$regex = "/^[0-9]{7,}/";
			if(preg_match($regex, $email)){
				$this->flag='M';
				$this->finalString=$email;
				return true;
			}
		}
		
			return false;
	}

public function replaceFirstOccurence($needle, $replace, $haystack){

$pos = strpos($haystack, $needle);
if ($pos !== false) {
    $newstring = substr_replace($haystack, $replace, $pos, strlen($needle));
    return $newstring;
}
return $haystack;
}

}
