<?php
class reg_LOG_SERVER_ERROR extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function logError($date,$page,$error)
	{
		try
		{
			if($page)
			{
				$sql="INSERT INTO reg.LOG_SERVER_ERRORS VALUE ( :DATE, :PAGE, :ERROR)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":DATE",$date);
				$prep->bindValue(":PAGE",$page);
				$prep->bindValue(":ERROR",$error);
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
