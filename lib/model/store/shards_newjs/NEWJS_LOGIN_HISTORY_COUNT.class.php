<?php
class NEWJS_LOGIN_HISTORY_COUNT extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	
	public function updateLoginHistoryCount($pid)
    {
		if(!$pid)
			throw new jsException("","VALUE OR TYPE IS BLANK IN insertIntoLoginHistory() of NEWJS_LOG_LOGIN_HISTORY.class.php");
		try 
		{
				$sql="update newjs.LOGIN_HISTORY_COUNT  set TOTAL_COUNT=TOTAL_COUNT+1 where PROFILEID=:profileid";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
				$prep->execute();
				return $prep->rowCount();
				
		}
		catch(PDOException $e)
		{			
			throw new jsException($e);
		}
	}
	
	public function replaceLoginHistoryCount($pid)
    {
		if(!$pid)
			throw new jsException("","VALUE OR TYPE IS BLANK IN insertIntoLoginHistory() of NEWJS_LOG_LOGIN_HISTORY.class.php");
		try 
		{
				$sql="replace into newjs.LOGIN_HISTORY_COUNT(PROFILEID,TOTAL_COUNT) values (:profileid,1)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
				$prep->execute();
				return $prep->rowCount();
				
		}
		catch(PDOException $e)
		{			
			throw new jsException($e);
		}
	}
}
?>
