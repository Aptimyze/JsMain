<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class PROFILE_VERIFICATION_AADHAR_VERIFICATION_MAILER_LOG extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

    public function insertProfile($profileId)
    {
        $sql = "INSERT IGNORE INTO PROFILE_VERIFICATION.AADHAAR_VERIFICATION_MAILER_LOG(PROFILEID) VALUES (:PROFILEID)";
        $res = $this->db->prepare($sql);
        $res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
        $res->execute();
    }
    
    public function truncateTable()
    {
        $sql = "TRUNCATE TABLE PROFILE_VERIFICATION.AADHAAR_VERIFICATION_MAILER_LOG";
        $res = $this->db->prepare($sql);
        $res->execute();
    }
    
}