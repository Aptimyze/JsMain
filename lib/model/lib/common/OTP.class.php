<?php 
// create its object with profile object and phone type
class OTP extends phoneVerification
{

private $otpRow;
private $otpSettings;

public function __construct($profileObject='',$phoneType,$otp_settings)
{
	parent::__construct($profileObject,$phoneType);
    $this->otpSettings = $otp_settings;
}


	public function sendOtpSMS() 
	{   

    $otpObject=new sms_COMMON_OTP();
	$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone,$this->otpSettings);

	if($this->otpRow)
	{  

			if($this->checkForExpiration())
			{ 
				$newOtp=substr('000'.rand(1,9999),-4);
				$otpObject->renewOTPRecord($this->otpRow['ID'],$newOtp,$this->otpSettings['SMSType']);
    			$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone,$this->otpSettings);	
			
			}

			
			if(!$this->checkForTrials())
				$response['trialsOver']='Y';
			else 
				$response['trialsOver']='N';

			if($this->otpRow['SMS_COUNT']>=OTPENUMS::$deleteProfileOTP['SMSLimit'])
			{
				$response['SMSLimitOver']='Y';
				$response['SMSSent']='N';
				return $response;
			}
			else if($this->otpRow['SMS_COUNT']==(OTPENUMS::$deleteProfileOTP['SMSLimit']-1))
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
		$otpObject->setNewOTPRecord($this->profileObject->getPROFILEID(),$this->phone,$newOtp,$this->otpSettings);
		$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone,$this->otpSettings);
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
  	$arr=array('PHONE_MOB'=>$this->getPhoneWithoutIsd(),'OTP'=>"$OTP");
	$smsViewer = new InstantSMS($this->otpSettings['SMSType'],$this->profileObject->getPROFILEID(),$arr,'');
    $smsViewer->send("OTP");

    $otpObject=new sms_COMMON_OTP();

	$otpObject->incrementSmsCount($this->otpRow['ID']);
}

	public function matchOtp($enteredOtp) 
	{    
    
    	$otpObject=new sms_COMMON_OTP();
		$this->otpRow=$otpObject->getOTPRecord($this->profileObject->getPROFILEID(),$this->phone,$this->otpSettings);

		if($this->otpRow)
		{

		// checkForTrials is false when the trials are already over. But if the trials are one less than limit then the OTP should be checked once and then trials over flag should be set.
			if(!$this->checkForTrials()) 
				return 'C';
			else if ($this->checkForExpiration())
				return 'N';
			else if($this->otpRow['OTP']===$enteredOtp) 
			{	
			 	$this->clearEntry();
			 	return 'Y';
			} 
			else 
			{
				$otpObject->incrementTrialCount($this->otpRow['ID']); 
				if($this->otpRow['TRIAL_COUNT']==(OTPENUMS::$deleteProfileOTP['trialLimit']-1)) 
					return 'C';
				else
					return 'N';
			}		
		}
	
		
	}


private function checkForTrials() 
	{    
		
		if($this->otpRow && $this->otpRow['TRIAL_COUNT'] >= OTPENUMS::$deleteProfileOTP['trialLimit'])
		{
			return false;

		}
	return true;
	
	}

private function checkForExpiration()
{
	if((time() - strtotime($this->otpRow['DATE'])) >= $this->otpSettings['Duration'])
	{
			return true;
	}
	return false;	
}

private function clearEntry()
{
	$otpObject=new sms_COMMON_OTP();	
	$newRow=$otpObject->deleteOTPRow($this->otpRow['ID']);
    	
}






}
