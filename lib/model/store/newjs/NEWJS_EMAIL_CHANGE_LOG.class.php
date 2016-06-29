<?php
//This class is used to determine whether the user has been asked the permission to contact him on DNC numbers  ...

class NEWJS_EMAIL_CHANGE_LOG extends TABLE {
  
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

	public function markAsVerified($profileid,$email)
	{
		try	 	
		{	
			$sql="UPDATE newjs.EMAIL_CHANGE_LOG SET STATUS = 'Y' WHERE PROFILEID = :PROFILEID AND EMAIL=':EMAIL' ORDER BY CHANGE_DATE DESC LIMIT 1";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
        
        public function insertEmailChange($profileid,$email)
	{  
		try
		{
			
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "INSERT INTO newjs.EMAIL_CHANGE_LOG(PROFILEID,CHANGE_DATE,EMAIL,STATUS) VALUES (:PROFILEID,'$timeNow',:EMAIL,'N')";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
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
