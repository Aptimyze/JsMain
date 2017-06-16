<?php
class JSADMIN_DELETED_PROFILES extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function profileDeletionData($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT TIME,REASON,RETRIEVED_BY,USER,COMMENTS FROM jsadmin.DELETED_PROFILES WHERE PROFILEID = :PROFILEID ORDER By TIME ASC";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					return $res;
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
