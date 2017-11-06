<?php
class newjs_ValidNameList extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="",$gender)
        {
			parent::__construct($dbname);
			if(!$gender ||!in_array($gender,array("M","F")))
				throw new jsException("", "gender not provided in names table");
			$this->tableName = "newjs.ValidNameList_".$gender;
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
                $sql="SELECT * from ".$this->tableName." where NAME IN (".$queryStr.")";
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
		
		
		public function getValidNames()
		{
			$sql="SELECT NAME from ".$this->tableName;
			$resSelectDetail = $this->db->prepare($sql);
			$resSelectDetail->execute();
			while($rowSelectDetail=$resSelectDetail->fetch(PDO::FETCH_ASSOC)){
				$output[]=$rowSelectDetail["NAME"];
			}
			return $output;
		}
}
?>
