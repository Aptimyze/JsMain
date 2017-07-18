<?php
/*
 * This Class is used for aadhar verification details
 * @author Sanyam Chopra
 * @created July 13, 2017
*/

class PROFILE_VERIFICATION_AADHAR_VERIFICATION extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

    public function insertAadharDetails($profileId,$username,$date,$aadharId,$requestId)
    {
    	try
    	{
    		$sql = "INSERT IGNORE INTO PROFILE_VERIFICATION.AADHAR_VERIFICATION(PROFILEID,USERNAME,DATE,AADHAR_NO,REQUEST_ID) VALUES (:PROFILEID,:USERNAME,:DATE,:AADHARID,:REQUESTID)";
    		$res = $this->db->prepare($sql);
    		$res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
    		$res->bindParam(":USERNAME", $username, PDO::PARAM_STR);
    		$res->bindParam(":DATE", $date, PDO::PARAM_STR);
    		$res->bindParam(":AADHARID", $aadharId, PDO::PARAM_INT);
    		$res->bindParam(":REQUESTID", $requestId, PDO::PARAM_STR);
    		$res->execute();
			return true;
    	}
    	catch(PDOException $e)
    	{
    		throw new jsException($e);
    	}
    }

    public function getAadharDetails($profileId)
    {
    	try
    	{
    		$sql = "SELECT PROFILEID,AADHAR_NO,REQUEST_ID,VERIFY_STATUS from PROFILE_VERIFICATION.AADHAR_VERIFICATION WHERE PROFILEID = :PROFILEID";
    		$res = $this->db->prepare($sql);
    		$res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
    		$res->execute();
    		while($row=$res->fetch(PDO::FETCH_ASSOC))
    		{
    			$output[$profileId]["AADHAR_NO"] =$row['AADHAR_NO'];
    			$output[$profileId]["REQUEST_ID"] =$row['REQUEST_ID'];
    			$output[$profileId]["VERIFY_STATUS"] =$row['VERIFY_STATUS'];
    		}
            return $output;
    	}
    	catch(PDOException $e)
    	{
    		throw new jsException($e);
    	}
    }

    public function updateVerificationStatus($profileId,$status)
    {
    	try
    	{
    		$sql = "UPDATE PROFILE_VERIFICATION.AADHAR_VERIFICATION SET VERIFY_STATUS = :STATUS WHERE PROFILEID=:PROFILEID";
    		$res = $this->db->prepare($sql);
    		$res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
    		$res->bindParam(":STATUS", $status, PDO::PARAM_STR);
    		$res->execute();
    		return true;
    	}
    	catch(PDOException $e)
    	{
    		throw new jsException($e);
    	}
    }

    public function resetAadharDetails($profileId)
    {
    	try
    	{
    		$sql = "DELETE FROM PROFILE_VERIFICATION.AADHAR_VERIFICATION WHERE PROFILEID=:PROFILEID";
    		$res = $this->db->prepare($sql);
    		$res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
    		$res->execute();
    	}
    	catch(PDOException $e)
    	{
    		throw new jsException($e);
    	}

    }
}
