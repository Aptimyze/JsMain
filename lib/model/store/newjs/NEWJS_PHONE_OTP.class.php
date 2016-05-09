<?php
class NEWJS_PHONE_OTP extends TABLE 
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
    
    public function getOTPRecord($profileid,$phone)
    {
		if(!$phone || !$profileid)
			throw new jsException("","Phone Number is empty in arguements in function getOTPRecordFromPhone() of NEWJS_OTP class");
			
		try{
			$sql = "SELECT * FROM newjs.PHONE_OTP WHERE PHONE_WITH_ISD = :PHONE AND PROFILEID=:PROFILEID ORDER BY DATE DESC LIMIT 1";
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":PHONE",$phone,PDO::PARAM_STR);
			$pdoStatement->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
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
	
	public function setNewOTPRecord($profileid,$phone,$otp)
    {
		if(!$phone || !$profileid)
			throw new jsException("","Phone Number is empty in arguements in function getOTPRecordFromPhone() of NEWJS_OTP class");
			
		try{

			$sql = "INSERT INTO newjs.PHONE_OTP(PROFILEID,PHONE_WITH_ISD,OTP,DATE) VALUES (:PROFILEID,:PHONE,:OTP,:DATE);";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PHONE",$phone,PDO::PARAM_STR);
			$pdoStatement->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATE",(new DateTime)->format('Y-m-j H:i:s'),PDO::PARAM_STR);
			$pdoStatement->bindValue(":OTP",$otp,PDO::PARAM_STR);
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
			throw new jsException("","ID is empty in arguements in function incrementSmsCount() of NEWJS_OTP class");
			
		try{
			$sql = "UPDATE newjs.PHONE_OTP SET SMS_COUNT=SMS_COUNT+1 WHERE `ID`=:ID";
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
		throw new jsException("","Phone Number or profileid is empty in arguements in function incrementSmsCount() of NEWJS_OTP class");
			
		try{
			$sql = "UPDATE newjs.PHONE_OTP SET TRIAL_COUNT=TRIAL_COUNT+1 WHERE `ID`=:ID";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
			$pdoStatement->execute();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	


	public function renewOTPRecord($id,$otp)
    {
		if(!$id || !$otp)
		throw new jsException("","ID or OTP is empty in arguements in function incrementSmsCount() of NEWJS_OTP class");
			
		try{
			$now=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "UPDATE newjs.PHONE_OTP SET TRIAL_COUNT=0,SMS_COUNT=0,OTP=:OTP,DATE='$now' WHERE `ID`=:ID";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
			$pdoStatement->bindValue(":OTP",$otp,PDO::PARAM_STR);
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
		throw new jsException("","ID is empty in arguements in function incrementSmsCount() of NEWJS_OTP class");
			
		try{
			$sql = "DELETE FROM newjs.PHONE_OTP  WHERE `ID`=:ID";
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
