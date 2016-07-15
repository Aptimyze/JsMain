<?php
class NEWJS_MESSAGE_LOG_GET_ID extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        
		
		public function generateID()
        {
			try 
			{
				$sql="INSERT INTO MESSAGE_LOG_GET_ID(ID) VALUES ('')  ";
				$prep=$this->db->prepare($sql);
				$prep->execute();
				return  $this->db->lastInsertId(); 
				
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function deleteGenerateID()
        {
			try 
			{
				$sql="DELETE FROM MESSAGE_LOG_GET_ID WHERE 1  ";
				$prep=$this->db->prepare($sql);
				$prep->execute();
					
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
}
?>
