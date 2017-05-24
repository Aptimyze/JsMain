<?php
/**
 * This Class provide functions to Update Verification Seal in SWAP Table
 * @author Akash Kumar
 * @created Aug 8, 2014
*/
class newjs_SWAP extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function sealUpdate($profileId,$verificationSeal)
	{
		try
		{
			$sql = "UPDATE `newjs`.`SWAP` SET VERIFICATION_SEAL=:FINAL_SEAL WHERE PROFILEID=:PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->bindValue(":FINAL_SEAL", $verificationSeal, PDO::PARAM_STR);
			$res->execute();
			return 1;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return NULL;
	}
        
}
?>