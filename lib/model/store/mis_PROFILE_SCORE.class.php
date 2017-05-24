<?php
class MIS_PROFILE_SCORE extends TABLE{
       

        

        public function __construct($dbName="")
        {
			parent::__construct($dbname);
        }
	public function insertEntry($profileid,$score)
	{
		try
		{
			$res=null;
			if($profileid && $score)
			{
				$sql="INSERT IGNORE INTO MIS.PROFILE_SCORE(`PROFILE_ID`,`SCORE`)VALUES(:profileid,:score)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileid",$profileid,PDO::PARAM_INT);
				$prep->bindValue(":score",$score,PDO::PARAM_STR);
				$prep->execute();
			}
			else
				throw new jsException("error in prfile score $profileid $score");
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
}
?>
