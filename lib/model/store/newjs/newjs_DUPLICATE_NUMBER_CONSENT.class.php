<?php
//This class is used to determine whether the user has been asked the permission to contact him on DNC numbers  ...

class newjs_DUPLICATE_NUMBER_CONSENT extends TABLE {
  
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

	public function getConsentStatus($profileid)
	{
		try	 	
		{	
			$sql = "select PROFILEID from newjs.DUPLICATE_NUMBER_CONSENT WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		if (is_null($result)||($result==''))
		return false;
	
		return true;	
	}
        
        public function setConsentStatus($profileid)
	{
		try
		{
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "INSERT IGNORE INTO newjs.DUPLICATE_NUMBER_CONSENT(PROFILEID,TIME) VALUES (:PROFILEID,:CONSENT_TIME)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":CONSENT_TIME",$timeNow,PDO::PARAM_STR);
            $prep->execute();
            
        
		}
		catch(Exception $e)
		{
			throw new jsException($e);
			return false;
		}
		return true;	
	}

	
}
