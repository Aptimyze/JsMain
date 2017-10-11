<?php
class NEWJS_FEATURED_PROFILE_CACHE extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
        This function fetches the records from the FEATURED_PROFILE_CACHE table on the basis of search id.
        @params search id
        @return list of profiles in comma separated format or null
        **/
	public function fetch($sid)
	{
		if(!$sid)
                        throw new jsException("","SID IS BLANK IN fetch() OF NEWJS_FEATURED_PROFILE_CACHE.class.php");

		try
		{
			$sql = "SELECT RESULTS FROM newjs.FEATURED_PROFILE_CACHE WHERE SID = :SID";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":SID", $sid, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row["RESULTS"];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return null;
	}

	/**
        This function inserts records into the table
        @params search id and array of profileids
        **/
	public function insert($sid,$results)
	{
		if(!$sid || !$results || !is_array($results))
                        throw new jsException("","SID OR RESULTS IS BLANK IN insert() OF NEWJS_FEATURED_PROFILE_CACHE.class.php");

		try
		{
			$sql = "REPLACE INTO newjs.FEATURED_PROFILE_CACHE(SID,RESULTS,TOTAL) VALUES (:SID,:RESULTS,:TOTAL)";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":SID", $sid, PDO::PARAM_INT);
                        $res->bindValue(":RESULTS", implode(",",$results), PDO::PARAM_STR);
                        $res->bindValue(":TOTAL", count($results), PDO::PARAM_INT);
                        $res->execute();	
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
