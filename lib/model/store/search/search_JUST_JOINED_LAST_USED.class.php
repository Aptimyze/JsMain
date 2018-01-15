<?php
/**
* @author Lavesh Rawat
* @created 2014-08-05
*/
class search_JUST_JOINED_LAST_USED extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /*
	* log records
	* Store last time just joined search is run for a user.
        */
	public function ins($pid)
	{
		try
		{
			if(!$pid)
				throw new jsException("","PROFILEID IS BLANK IN ins() of search.JUST_JOINED_LAST_USED");
			$dt = date("Y-m-d h:i:s");
			$sql = "REPLACE INTO search.JUST_JOINED_LAST_USED(PROFILEID,LAST_USED_DT) VALUES (:PID,:DT)";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PID", $pid, PDO::PARAM_INT);
			$res->bindParam(":DT",$dt, PDO::PARAM_INT);
        	        $res->execute();	
			return $dt;
		}
                catch(Exception $e)
                {
			jsException::nonCriticalError("lib/model/store/search/search_JUST_JOINED_LAST_USED.class.php(1)-->.$sql".$e);
                        return '';
                }
	}

        /*
	* get records
	* get last time when just joined search is run for a user.
        */
	public function getDt($pid)
	{
		try
		{
			if(!$pid)
				throw new jsException("","PROFILEID IS BLANK IN get() of search.JUST_JOINED_LAST_USED");
			$sql = "SELECT LAST_USED_DT FROM search.JUST_JOINED_LAST_USED WHERE PROFILEID=:PID";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PID", $pid, PDO::PARAM_INT);
                	$res->execute();
	                $row = $res->fetch(PDO::FETCH_ASSOC);
			if(!$row["LAST_USED_DT"])
				return NULL;
	                return $row["LAST_USED_DT"];
		}
                catch(Exception $e)
                {
			jsException::nonCriticalError("lib/model/store/search/search_JUST_JOINED_LAST_USED.class.php(1)-->.$sql".$e);
                        return '';
                }
	}
}
?>
