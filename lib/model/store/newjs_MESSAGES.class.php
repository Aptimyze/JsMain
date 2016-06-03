<?php
class NEWJS_MESSAGES extends TABLE{
       

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
					$sql="SELECT ID,MESSAGE FROM newjs.MESSAGES WHERE ID IN('$Id')";
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
		
		public function updateMessages($msgCommObj)
        {
			try 
			{
				if($msgCommObj->getID() && $msgCommObj->getMESSAGE())
				{ 
					$sql="INSERT INTO MESSAGES VALUES (:GENERATEDID,:CUSTOMMESSAGE)  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$msgCommObj->getID(),PDO::PARAM_INT);
					$prep->bindValue(":CUSTOMMESSAGE",$msgCommObj->getMESSAGE(),PDO::PARAM_STR);
					$prep->execute();
					
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function updateMessagesValue($msgCommObj)
		{
			try 
			{
				if($msgCommObj->getID() && $msgCommObj->getMESSAGE())
				{ 
					$sql="UPDATE MESSAGES SET MESSAGE = :CUSTOMMESSAGE WHERE ID = :GENERATEDID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$msgCommObj->getID(),PDO::PARAM_INT);
					$prep->bindValue(":CUSTOMMESSAGE",$msgCommObj->getMESSAGE(),PDO::PARAM_STR);
					$prep->execute();
					
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
