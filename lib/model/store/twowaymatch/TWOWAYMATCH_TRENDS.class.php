<?php
class TWOWAYMATCH_TRENDS extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
        This function checkes if a profileid has an entry in twowaymatch.TRENDS table.
        * @param  profileid
        * @return true if entry is present else false
        **/
	public function checkHaveTrends($profileId)
	{
		if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN checkHaveTrends() of TWOWAYMATCH_TRENDS.class.php");

		try
		{
			$sql = "SELECT PROFILEID FROM twowaymatch.TRENDS WHERE PROFILEID = :PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			if($row["PROFILEID"])
				return true;
			else
				return false;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/**
        This function fetches the trends score of various profiles corresponding to a given profileid from twowaymatch.TRENDS table.
        * @param  profileid, expression for calculating trends score
        * @return array of scores corresponding to various profileids
        **/
	public function getTrendsScore($profileId,$param)
	{
		if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN getTrendsScore() of TWOWAYMATCH_TRENDS.class.php");
		if(!$param)
                        throw new jsException("","PARAMETER IS BLANK IN getTrendsScore() of TWOWAYMATCH_TRENDS.class.php");

		try
		{
			$sql = "SELECT ".$param." MAX_SCORE FROM twowaymatch.TRENDS WHERE PROFILEID = :PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			if($row)
				return $row;
			else
				return false;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/*
	This function is used to get all data for a profile
	@param - profileid
	@return - result set row
	*/
	public function getData($profileId)
	{
		if(!$profileId){
                        return false;
                        throw new jsException("","PROFILEID IS BLANK IN getData() of TWOWAYMATCH_TRENDS.class.php");
                }

		try
		{
			$sql = "SELECT SQL_CACHE * FROM twowaymatch.TRENDS WHERE PROFILEID = :PROFILEID";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $row;
	}
        /*
        This function is used to get all INITIATED  , ACCEPTED data for a profile
        @param - profileid
        @return - result set row
        */
	public function getInitialtedAndAcceptedCount($profileId)
	{
		if(!$profileId){
                        return false;
                        throw new jsException("","PROFILEID IS BLANK IN getInitialtedAndAcceptedCount() of MATCHALERTS_TRENDS.class.php");
                }

		try
		{
			$sql = "select INITIATED,ACCEPTED FROM twowaymatch.TRENDS WHERE PROFILEID = :PROFILEID";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
                        $cnt=$row["INITIATED"] + $row["ACCEPTED"];
                        return $cnt;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $row;
	}
}
?>
