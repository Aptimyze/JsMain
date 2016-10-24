<?php
class SMS_COMMON_OTP extends TABLE 
{
	
	/**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    
    public function getOTPRecord($profileid,$phone,$otpSettings)
    {  
		if(!$phone || !$profileid)
			throw new jsException("","Phone Number is empty in arguements in function getOTPRecordFromPhone() of sms_OTP class");
			
		try{
			$sql = "SELECT * FROM sms.COMMON_OTP WHERE PHONE_WITH_ISD = :PHONE AND PROFILEID=:PROFILEID AND TYPE=:TYPEOTP ORDER BY DATE DESC LIMIT 1";
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":PHONE",$phone,PDO::PARAM_STR);
			$pdoStatement->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":TYPEOTP",$otpSettings['SMSType'],PDO::PARAM_STR);
			$pdoStatement->execute();
			
			if ($res=$pdoStatement->fetch(PDO::FETCH_ASSOC))
				return $res;
			return null;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function setNewOTPRecord($profileid,$phone,$otp,$otpSettings)
    {
		if(!$phone || !$profileid)
			throw new jsException("","Phone Number is empty in arguements in function getOTPRecordFromPhone() of sms_OTP class");
			
		try{
	
			$sql = "INSERT INTO sms.COMMON_OTP(PROFILEID,PHONE_WITH_ISD,OTP,DATE,TYPE) VALUES (:PROFILEID,:PHONE,:OTP,:DATE,:TYPEOTP);";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PHONE",$phone,PDO::PARAM_STR);
			$pdoStatement->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATE",(new DateTime)->format('Y-m-j H:i:s'),PDO::PARAM_STR);
			$pdoStatement->bindValue(":OTP",$otp,PDO::PARAM_STR);
			$pdoStatement->bindValue(":TYPEOTP",$otpSettings['SMSType'],PDO::PARAM_STR);
			$pdoStatement->execute(); 

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
    public function incrementSmsCount($id)
    {
		if(!$id)
			throw new jsException("","ID is empty in arguements in function incrementSmsCount() of sms_OTP class");
			
		try{
			$sql = "UPDATE sms.COMMON_OTP SET SMS_COUNT=SMS_COUNT+1 WHERE `ID`=:ID";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
			$pdoStatement->execute();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	 public function incrementTrialCount($id)
    {
		if(!$id)
		throw new jsException("","Phone Number or profileid is empty in arguements in function incrementSmsCount() of sms_OTP class");
			
		try{
			$sql = "UPDATE sms.COMMON_OTP SET TRIAL_COUNT=TRIAL_COUNT+1 WHERE `ID`=:ID";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
			$pdoStatement->execute();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	


	public function renewOTPRecord($id,$otp,$typeOTP)
    { 
		if(!$id || !$otp ||!$typeOTP)
		throw new jsException("","ID or OTP is empty in arguements in function incrementSmsCount() of sms_OTP class");
			
		try{
			$now=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "UPDATE sms.COMMON_OTP SET TRIAL_COUNT=0,SMS_COUNT=0,OTP=:OTP,DATE='$now',TYPE=:TYPEOTP WHERE `ID`=:ID";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
			$pdoStatement->bindValue(":OTP",$otp,PDO::PARAM_STR);
			$pdoStatement->bindValue(":TYPEOTP",$typeOTP,PDO::PARAM_STR);
			$pdoStatement->execute();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	


	public function deleteOTPRow($id)
    {
		if(!$id)
		throw new jsException("","ID is empty in arguements in function incrementSmsCount() of sms_COMMON_OTP class");
			
		try{
			$sql = "DELETE FROM sms.COMMON_OTP  WHERE `ID`=:ID";
			$pdoStatement = $this->db->prepare($sql);			
			$pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
			$pdoStatement->execute();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}


    }
}
?>
