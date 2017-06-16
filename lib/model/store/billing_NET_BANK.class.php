<?php
class billing_NET_BANK extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getNetBanks()
        {
                try
                {
			$sql="SELECT VALUE,LABEL FROM billing.NET_BANK ORDER BY ORDER_ID ASC";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC)){
				$value =$result['VALUE'];
				$resultArr[$value] =$result['LABEL'];
                        }
			return $resultArr;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }
}
?>
