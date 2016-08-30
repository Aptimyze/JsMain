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

        public function haveName($nameArr)
        {
            try
            {
		if(!count($nameArr))
			return false;
		foreach($nameArr as $k=>$v)
			$queryArr[]=":NAME".$k;
		$queryStr = implode(",",$queryArr);
                $sql="SELECT * from newjs.ValidNameList where NAME IN (".$queryStr.")";
                $resSelectDetail = $this->db->prepare($sql);
		foreach($nameArr as $k=>$v)
			$resSelectDetail->bindValue(":NAME".$k, $v);
                $resSelectDetail->execute();
		$return=0;
		while($rowSelectDetail=$resSelectDetail->fetch(PDO::FETCH_ASSOC))
			$return++;
		return $return;
            }
            catch(Exception $e)
            {
                    throw new jsException($e);
            }
        }
		
}
?>
