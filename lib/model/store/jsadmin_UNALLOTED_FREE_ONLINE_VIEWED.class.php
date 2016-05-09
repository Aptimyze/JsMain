<?php
class jsadmin_UNALLOTED_FREE_ONLINE_VIEWED extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function insertUnallotedProfile($profileid)
	{
		try{
			$sql="insert ignore into jsadmin.UNALLOTED_FREE_ONLINE_VIEWED (VIEWED) values (:PROFILEID)";
  			$prep_insert = $this->db->prepare($sql_insert);
      			$prep_insert->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      			$prep_insert->execute();
    		}
    		catch (PDOException $e) {
      			throw new jsException($e);
    		}
        }
}
?>
