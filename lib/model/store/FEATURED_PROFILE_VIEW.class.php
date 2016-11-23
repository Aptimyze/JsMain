<?php
class FEATURED_PROFILE_VIEW extends TABLE{
       

        

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function update()
        {
			try 
			{
				$date=date('Y-m-d');
				$sqlUpdate = "UPDATE MIS.FEATURED_PROFILE_VIEW SET COUNT=COUNT+1 WHERE DATE=:DATEVAL";
				$res = $this->db->prepare($sqlUpdate);
				$res->bindParam(":DATEVAL", $date, PDO::PARAM_STR);
				$count=$res->execute();
				if($count<=0)
				{
					$sqlInsert="INSERT IGNORE INTO MIS.FEATURED_PROFILE_VIEW (DATE,COUNT) VALUES (:DATEVAL,'1')";
					$res = $this->db->prepare($sqlInsert);
					$res->bindParam(":DATEVAL", $date, PDO::PARAM_STR);
					$res->execute();
				}
				return true;
			}
			catch(PDOException $e)
			{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
			}
		}
		
}
?>
