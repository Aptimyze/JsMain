<?php
class newjs_HIDDEN_DELETED_PROFILES extends TABLE
{
	public function __construct($dbname='')
	{
		parent::__construct($dbname);
	}

	/**
        This function INSERTS records from newjs.HIDDEN_DELETED_PROFILES table
        @params profileid
        **/	
	public function insertProfile($profileId)
	{
		if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN deleteRecord() OF NEWJS_SEARCH_MALE.class.php");

		try
		{
			$sql = "INSERT IGNORE INTO newjs.HIDDEN_DELETED_PROFILES(PROFILEID) VALUES(:PROFILEID)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}  
        public function truncateTable($date = '')
	{
		try
		{
                        if($date != ''){
                              $sql = "DELETE FROM newjs.HIDDEN_DELETED_PROFILES WHERE LAST_UPDATED <= '".$date."'";
                        }else{
                              $sql = "TRUNCATE TABLE newjs.HIDDEN_DELETED_PROFILES";
                        }          
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
        public function getProfiles()
	{
		try
		{
			$sql = "SELECT PROFILEID FROM newjs.HIDDEN_DELETED_PROFILES";
			$res = $this->db->prepare($sql);
			$res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                          $result[] = $row["PROFILEID"];
			return $result;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
