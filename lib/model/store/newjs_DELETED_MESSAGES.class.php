<?php
class NEWJS_DELETED_MESSAGES extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function Messages($Id)
        {
			try 
			{
				if($Id)
				{ 
					$sql="SELECT ID,MESSAGE FROM newjs.DELETED_MESSAGES WHERE ID IN('$Id')";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":ID",$Id,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[$result[ID]]= $result[MESSAGE];
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
