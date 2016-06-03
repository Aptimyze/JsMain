<?php
class BILLING_PAYSEAL extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function tranInfo($orderId)
        {
			try 
			{
				if($orderId)
				{ 
					$sql="SELECT TXNREFNO,RRN FROM billing.PAYSEAL WHERE ORDERID = :ORDERID ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":ORDERID",$orderId,PDO::PARAM_INT);
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
