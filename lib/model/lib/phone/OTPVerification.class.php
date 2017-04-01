<?php 
class OTPVerification extends phoneVerification
{

private $otpRow;
	public function sendOtpSMS() 
	{    
    $otpObject=new NEWJS_PHONE_OTP();
	$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone);
	if($this->otpRow)
	{

			if($this->checkForExpiration())
			{
				$newOtp=substr('000'.rand(1,9999),-4);
				$otpObject->renewOTPRecord($this->otpRow['ID'],$newOtp);
    			$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone);								
			}

			
			if(!$this->checkForTrials())
				$response['trialsOver']='Y';
			else 
				$response['trialsOver']='N';

			if($this->otpRow['SMS_COUNT']>=phoneEnums::$OTPSMSLimit)
			{
				$response['SMSLimitOver']='Y';
				$response['SMSSent']='N';
				return $response;
			}
			else if($this->otpRow['SMS_COUNT']==(phoneEnums::$OTPSMSLimit-1))
			{   
				if($response['trialsOver']=='Y')
					$response['SMSSent']='N';
				else 
				{	
					$response['SMSSent']='Y';
					$this->sendMessage($this->otpRow['OTP']);
				}
				$response['SMSLimitOver']='Y'; // after sending this sms in this function the limit will be over. hence this response.
		    	return $response;
			} 
			else   		
			{
				$response['SMSLimitOver']='N'; 
				
				if($response['trialsOver']=='Y')
					$response['SMSSent']='N';
				else 
				{	
					$response['SMSSent']='Y';
					$this->sendMessage($this->otpRow['OTP']);
				}
				return $response;
			} 		
	}
	else
	{	
		$newOtp=substr('000'.rand(1,9999),-4);
		$otpObject->setNewOTPRecord($this->profileObject->getPROFILEID(),$this->phone,$newOtp);
		$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone);
		$response['SMSLimitOver']='N';
		$response['SMSSent']='Y';
		$response['trialsOver']='N';
		$this->sendMessage($newOtp);
		return $response;			
	}

	}




private function sendMessage($OTP)
{
    include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
	$message=phoneEnums::$OTPMessage;
	$arr=array('PHONE_MOB'=>$this->getPhoneWithoutIsd(),'ISD'=>$this->getIsd(),'OTP'=>"$OTP");
	$smsViewer = new InstantSMS("OTP",$this->profileObject->getPROFILEID(),$arr,'');
    $smsViewer->send("OTP");

    $otpObject=new NEWJS_PHONE_OTP();
	$otpObject->incrementSmsCount($this->otpRow['ID']);
}

	public function matchOtp($enteredOtp) 
	{    
    
    	$otpObject=new NEWJS_PHONE_OTP();
		$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone);

		if($this->otpRow)
		{
			 
		// checkForTrials is false when the trials are already over. But if the trials are one less than limit then the OTP should be checked once and then trials over flag should be set.
			if(!$this->checkForTrials()) 
				return 'C';
			else if ($this->checkForExpiration())
				return 'N';
			else if($this->otpRow['OTP']===$enteredOtp) 
			{
			 	$this->phoneUpdateProcess('OTP');	
			 	$this->clearEntry();
			 	return 'Y';
			} 
			else 
			{
				$otpObject->incrementTrialCount($this->otpRow['ID']); 
				if($this->otpRow['TRIAL_COUNT']==(phoneEnums::$OTPTrialLimit-1)) 
					return 'C';
				else
					return 'N';
			}		
		}
	
		
	}


private function checkForTrials() 
	{    
	if($this->otpRow)
	{
		
		if($this->otpRow['TRIAL_COUNT']>=phoneEnums::$OTPTrialLimit)
		{
			return false;

		}

		
	}
	return true;
	
	}

private function checkForExpiration()
{
	$otpObject=new NEWJS_PHONE_OTP();	
	if((time() - strtotime($this->otpRow['DATE'])) >= 60*60*phoneEnums::$OTPHoursLimit)
	{
			return true;
	}
	return false;	
}

private function clearEntry()
{
	$otpObject=new NEWJS_PHONE_OTP();	
	$newRow=$otpObject->deleteOTPRow($this->otpRow['ID']);
    	
}






}
