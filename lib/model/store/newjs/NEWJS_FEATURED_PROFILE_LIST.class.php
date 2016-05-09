<?php
class NEWJS_FEATURED_PROFILE_LIST extends TABLE
{
	public function __construct($dbname='')
	{
		parent::__construct($dbname);
	}

	/**
        This function inserts records into FEATURED_PROFILE_LIST table
        @params profileid ,score
        **/	
	public function insertRecord($profileId,$score)
	{
		if(!$profileId || !$score)
			throw new jsException("","PROFILEID OR SCORE IS BLANK IN insertRecord() OF NEWJS_FEATURED_PROFILE_LIST.class.php");

		try
		{
			$sql = "INSERT INTO newjs.FEATURED_PROFILE_LIST(PROFILEID,SCORE,IS_MODIFIED) VALUES (:PROFILEID,:SCORE,1) ON DUPLICATE KEY UPDATE SCORE = SCORE+:SCORE,IS_MODIFIED = 1";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SCORE", $score, PDO::PARAM_INT);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
			jsException::nonCriticalError("lib/model/store/newjs/NEWJS_FEATURED_PROFILE_LIST.class.php(1)-->.$sql".$e);
                        //throw new jsException($e);
                }
	}

	/**
        This function fetches rows from the table
        @params 1) array or a string of profileid 2) optional if SCORE needs to be fetched as well
        @return array of output rows
        **/
	public function getRecords($profileIds,$param="")
	{
		if(!$profileIds)
			throw new jsException("","PROFILEIDS ARE BLANK IN getRecords() OF NEWJS_FEATURED_PROFILE_LIST.class.php");

		if($param)
			$field = ",SCORE";
		try
		{
			if(is_array($profileIds))
				$profileIdsStr = implode(",",$profileIds);
			else
				$profileIdsStr = $profileIds;

			$profileIdsStr = str_replace("'","",$profileIdsStr);
                        $profileIdsStr = str_replace("\"","",$profileIdsStr);
                        $profileIdsStr_arr = explode(",",$profileIdsStr);
                        foreach($profileIdsStr_arr as $k=>$v)
                        {
                                $paramArr[] = ":PARAM".$k;
                        }

			$sql = "SELECT PROFILEID".$field." FROM newjs.FEATURED_PROFILE_LIST WHERE PROFILEID IN (".implode(",",$paramArr).") ORDER BY SCORE ASC";
			$res = $this->db->prepare($sql);
			foreach($profileIdsStr_arr as $k=>$v)
                        {
                                $res->bindValue($paramArr[$k],$v, PDO::PARAM_INT);
                        }
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(PDOException $e)
            	{
			jsException::nonCriticalError("lib/model/store/newjs/NEWJS_FEATURED_PROFILE_LIST.class.php(2)-->.$sql".$e);
                	//throw new jsException($e);
             	}

		if(count($output))
			return $output;
		else
			return false;
	}

	/**
        This function updates score of a profile in the table
        @params 1) profileId 2) score
        **/	
	public function updateScore($profileId,$score)
	{
		if(!$profileId || !$score)
                        throw new jsException("","PROFILEID OR SCORE IS BLANK IN updateScore() OF NEWJS_FEATURED_PROFILE_LIST.class.php");
		try
		{
			$sql = "UPDATE newjs.FEATURED_PROFILE_LIST SET SCORE = SCORE + :SCORE,IS_MODIFIED = 1 WHERE PROFILEID = :PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SCORE", $score, PDO::PARAM_INT);
			$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
			jsException::nonCriticalError("lib/model/store/newjs/NEWJS_FEATURED_PROFILE_LIST.class.php(3)-->.$sql".$e);
                        //throw new jsException($e);
                }
	}
}
?>
