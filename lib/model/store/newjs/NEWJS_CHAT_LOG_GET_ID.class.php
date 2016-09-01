<?php
class NEWJS_CHAT_LOG_GET_ID extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        
		
		/**
		This function is used to get autoincrement id from the table.
		Column NO_USE_VARIABLE is used for maintaining unique r/l.So that everytime a replace commnad is run existing row gets repalced and we can get a new increment id and not even increasing rows of table. 
		@return ID id auto increment id which will be used as pictureId for MESSAGE_LOG AND MESSAGES
        */
	public function getAutoIncrementMessageId()
	{
                $sql="REPLACE INTO newjs.CHAT_LOG_GET_ID(ID,NO_USE_VARIABLE) VALUES('','X')";
                $res=$this->db->prepare($sql);
				$res->execute();
				return $this->db->lastInsertId();
    }
		
		
}
?>
