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
				$sqlUpdate = "UPDATE MIS.FEATURED_PROFILE_VIEW SET COUNT=COUNT+1 WHERE DATE='$date'";
				
				$count=$this->db->exec($sqlUpdate);
				if($count<=0)
				{
					$sqlInsert="INSERT IGNORE INTO MIS.FEATURED_PROFILE_VIEW (DATE,COUNT) VALUES ('$date','1')";
					
					$this->db->exec($sqlInsert);
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
