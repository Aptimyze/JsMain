<?php

class NEWJS_LASTSENTMESSAGE extends TABLE
{
	public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function insert($profileid,$type,$message)
    {
    	try{
    		if(!$profileid)
    			throw new jsException("","PROFILEID IS BLANK IN NEWJS_LASTSENTMESSAGE.class.php");
    		$sql = "INSERT IGNORE INTO newjs.LAST_SENT_MESSAGE (PROFILEID,TYPE,MESSAGE,DATETIME) VALUES (:PROFILEID,:TYPE,:MESSAGE,now()) ON DUPLICATE KEY UPDATE MESSAGE = :MESSAGE ,DATETIME=now()";
    		$prep = $this->db->prepare($sql);
      		$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      		$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$prep->bindValue(":MESSAGE",$message,PDO::PARAM_STR); 
			if($prep->execute())
			{
				return $prep->rowCount();
			}
			else
			{
				return 0;
			}
		}
		catch (PDOException $e)
		{
			jsException::log($e->getMessage()."\n".$e->getTraceAsString());
		}
	}
	public function getLastSentMessage($profileid,$type)
	{
		try{
			if(!$profileid)
				throw new jsException("","PROFILEID IS BLANK IN getLastSentMessage() NEWJS_LASTSENTMESSAGE.class.php");
			$sql = "SELECT MESSAGE FROM newjs.LAST_SENT_MESSAGE WHERE PROFILEID=:PROFILEID AND TYPE=:TYPE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
				return $result['MESSAGE'];
            }
			else
			{
				return null;
			}
		}
		catch (PDOException $e)
		{
			jsException::log($e->getMessage()."\n".$e->getTraceAsString());
		}
	}
}
