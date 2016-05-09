<?php
class NEWJS_ANNULLED extends TABLE{

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function AnnulledReason($profileid)
        {
		try{
			if($profileid)
			{ 
				$sql="select REASON,SCREENED from newjs.`ANNULLED` where PROFILEID=:PROFILEID and SCREENED='Y'";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
					return $result[REASON];
				else
					return "No Reason Specified";
			}	
		}	
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
		
}
?>
