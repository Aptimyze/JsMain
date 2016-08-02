<?php
class newjs_ValidNameList extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

        public function haveName($name)
        {
            try
            {
                $sql="SELECT * from newjs.ValidNameList where NAME=:NAME";
                $resSelectDetail = $this->db->prepare($sql);
		$resSelectDetail->bindValue(":NAME", $name);
                $resSelectDetail->execute();
		if($rowSelectDetail=$resSelectDetail->fetch(PDO::FETCH_ASSOC))
			return true;
		return false;
            }
            catch(Exception $e)
            {
                    throw new jsException($e);
            }
        }
		
}
?>
