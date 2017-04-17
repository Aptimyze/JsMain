<?php
class NEWJS_LOGIN_HISTORY extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	public function getLastLoginDate($profileid)
	{
		if(!$profileid)
                        throw new jsException("","PROFILEID IS BLANK IN getLastLoginDate() OF NEWJS_LOGIN_HISTORY.class.php");

		try
		{
			$sql = "SELECT LOGIN_DT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = :PROFILEID ORDER BY LOGIN_DT DESC LIMIT 2,1";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row["LOGIN_DT"];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return null;
	}
	public function getLoginCount($profileid,$loginDate)
	{
		if(!$profileid)
                        throw new jsException("","PROFILEID IS BLANK IN getLoginCount() OF NEWJS_LOGIN_HISTORY.class.php");

		try
		{
			$sql = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID =:PROFILEID AND LOGIN_DT >= :LOGIN_DT";
			$res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":LOGIN_DT", $loginDate, PDO::PARAM_STR);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row["CNT"];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return null;
	}
	
	public function insertIntoLoginHistory($pid,$currentTime='')
    {
		if(!$pid)
			throw new jsException("","VALUE OR TYPE IS BLANK IN insertIntoLoginHistory() of NEWJS_LOG_LOGIN_HISTORY.class.php");
		try 
		{
                                $now = $currentTime ? $currentTime : date("Y-m-d H:i:s");
				$sql="insert ignore into LOGIN_HISTORY(PROFILEID,LOGIN_DT) values (:profileid,'".$now."')";
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
