<?php
//This class is used to log changes in the alternate email id of the user or when he registers and inserts the new alternate email

class NEWJS_ALTERNATE_EMAIL_LOG extends TABLE {
  
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

	public function markAsVerified($profileid,$email)
	{
		try	 	
		{	
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql="UPDATE newjs.ALTERNATE_EMAIL_LOG SET STATUS = 'Y', VERIFY_DATE='$timeNow' WHERE PROFILEID = :PROFILEID AND EMAIL=:EMAIL ORDER BY CHANGE_DATE DESC LIMIT 1";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
		return false;
		}
	}
        
        public function insertEmailChange($profileid,$email)
	{  
		try
		{
			
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "INSERT INTO newjs.ALTERNATE_EMAIL_LOG(PROFILEID,CHANGE_DATE,EMAIL,STATUS) VALUES (:PROFILEID,'$timeNow',:EMAIL,'N')";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->execute();
            
        
		}
		catch(Exception $e)
		{
			return false;
		}
		return $this->db->lastInsertId();	
	}
        public function getLastEntry($profileid)
        {
                try{
                        
                                $sql = "SELECT ID FROM newjs.ALTERNATE_EMAIL_LOG WHERE PROFILEID = :PROFILEID ORDER BY ID DESC LIMIT 1";
                                $res=$this->db->prepare($sql);
                                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                                $res->execute();
                                if($result = $res->fetch(PDO::FETCH_ASSOC))
                                    return $result;
    							else return false;                    
                }
                catch(Exception $e)
                {
                        return false;
                }
        }
	
}
