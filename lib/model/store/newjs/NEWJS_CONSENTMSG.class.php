<?php
//This class is used to determine whether the user has been asked the permission to contact him on DNC numbers  ...

class NEWJS_CONSENTMSG extends TABLE {
  
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

	public function getConsentStatus($profileid)
	{
		try	 	
		{	
			$sql = "select PROFILEID from newjs.CONSENT_DNC WHERE PROFILEID=:PROFILEID";
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
			$sql = "INSERT IGNORE INTO newjs.CONSENT_DNC (PROFILEID,CONSENT_TIME) VALUES (:PROFILEID,:CONSENT_TIME)";
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
        public function getOptinProfileArr($profileIdArr)
        {
                try{
                        if(is_array($profileIdArr))
                        {
                                foreach($profileIdArr as $key=>$pid){
                                        if($key == 0)
                                                $str = ":PROFILEID".$key;
                                        else
                                                $str .= ",:PROFILEID".$key;
                                }
                                $sql = "SELECT PROFILEID FROM newjs.CONSENT_DNC WHERE PROFILEID IN ($str) ";
                                $res=$this->db->prepare($sql);
                                unset($pid);
                                foreach($profileIdArr as $key=>$pid)
                                        $res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $result[] = $row['PROFILEID'];
                                return $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	
}
