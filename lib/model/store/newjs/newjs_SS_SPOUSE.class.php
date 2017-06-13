<?php
class NEWJS_SS_SPOUSE extends TABLE
{
        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

	/**
	  * This function gets a list of profiles that have been viewed by a user.
	  * Pass $keyVal as 1 if the profileids are to sent in the key of the returned array.
	**/

        public function getCount($username)
        {
                try
                {
			$sql="SELECT COUNT(*) AS CNT FROM newjs.SS_SPOUSE WHERE SPOUSE_USERNAME=:USERNAME";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			return $result[CNT];
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
	public function insert($id,$spouse)
	{
		try
                {
			$sql="INSERT IGNORE INTO newjs.SS_SPOUSE(`ID`,`SPOUSE_USERNAME`)values(:ID,:SPOUSE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ID",$id,PDO::PARAM_INT);
			$prep->bindValue(":SPOUSE",$spouse,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
}
?>
