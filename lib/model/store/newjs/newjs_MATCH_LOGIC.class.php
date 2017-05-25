<?php
class newjs_MATCH_LOGIC extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/*
        This function is used to get if old or new logic
        @param - profileid
        @return - result set row
        */
	public function getPresentLogic($profileId,$newOrOld)
	{
		if(!$profileId){
                        return false;
                        throw new jsException("","PROFILEID IS BLANK IN getpresentLogic() of newjs_MATCH_LOGIC.class.php");
                }

		try
		{
			$sql = "SELECT  COUNT(*) AS CNT from  newjs.MATCH_LOGIC WHERE LOGIC_STATUS='$newOrOld' AND PROFILEID = :PROFILEID";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row["CNT"];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $row;
	}
        /*
        This function is used to toggle old and new logic
        @param - profileid
        */
	public function setNewOrOldLogic($profileId,$newOrOld)
	{
		if(!$profileId){
                        return false;
                        throw new jsException("","PROFILEID IS BLANK IN setNewOrOldLogic() of newjs_MATCH_TRENDS.class.php");
                }

		try
		{
			$sql = "REPLACE INTO newjs.MATCH_LOGIC (PROFILEID,LOGIC_STATUS,MOD_DT) VALUES (:PROFILEID,:LOGIC_STATUS,NOW())";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->bindValue(":LOGIC_STATUS", $newOrOld, PDO::PARAM_STR);
                        $res->execute();
			return $res;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $row;
	}
}
?>
