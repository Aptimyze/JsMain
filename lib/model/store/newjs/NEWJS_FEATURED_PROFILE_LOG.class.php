<?php
class NEWJS_FEATURED_PROFILE_LOG extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/**
        This function inserts a row in the table
        @params profileid and viewid
        **/
	public function insertRecord($profileId,$viewedId)
	{
		if(!$profileId || !$viewedId)
                        throw new jsException("","PROFILEID OR VIEWEDID IS BLANK IN insertRecord() OF NEWJS_FEATURED_PROFILE_LOG.class.php");
		
		try
		{
			$sql = "REPLACE INTO newjs.FEATURED_PROFILE_LOG(PROFILEID,VIEWED,DATE) VALUES (:PROFILEID,:VIEWEDID,NOW())";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":VIEWEDID", $viewedId, PDO::PARAM_INT);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
		}
		catch(PDOException $e)
                {
			jsException::nonCriticalError("lib/model/store/newjs/NEWJS_FEATURED_PROFILE_LOG.class.php(1)-->.$sql".$e);
                        //throw new jsException($e);
                }
	}

	/**
        This function outputs viewed profiles whose entries are more than or equal to 3 for a particualar profileid
        @params profileid and separator(optional)
	@return array of profiles or string of profiles if separator given or null
        **/
	public function getProfilesToIgnore($profileId,$seperator="")
	{
		if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN getProfilesToIgnore() OF NEWJS_FEATURED_PROFILE_LOG.class.php");

		try
		{
			$sql = "SELECT COUNT(*) AS C,VIEWED FROM newjs.FEATURED_PROFILE_LOG WHERE PROFILEID = :PROFILEID GROUP BY VIEWED HAVING C>=:COUNT";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->bindValue(":COUNT",SearchConfig::$limitOfViewedFeatureProfile, PDO::PARAM_INT);
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row["VIEWED"];
                        }
		}
		catch(PDOException $e)
                {
			jsException::nonCriticalError("lib/model/store/newjs/NEWJS_FEATURED_PROFILE_LOG.class.php(2)-->.$sql".$e);
                        //throw new jsException($e);
                }
		if($output)
		{
			if($seperator=="spaceSeperator")
			{
				return implode(" ",$output);
			}
			else
				return $output;
		}
		else
			return null;
	}
}
?>
