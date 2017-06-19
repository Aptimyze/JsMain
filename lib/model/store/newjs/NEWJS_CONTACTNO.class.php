<?php
//This class is used to get the mobile number and the residence phone number of the user from newjs.JPROFILE table

class NEWJS_CONTACTNO extends TABLE {
  
  public function __construct($dbname = "") {
    
    parent::__construct($dbname);
  }

	public function getPhoneNumbers($profileid)
	{
		try
		{	$sql = "select PHONE_MOB, PHONE_RES from newjs.JPROFILE WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $result;	
	}	 

	public function getSecondaryNumber($profileid)
	{
		try
		{
			$sql = "select ALT_MOBILE from newjs.JPROFILE_CONTACT WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $result;	
	}	 

}
