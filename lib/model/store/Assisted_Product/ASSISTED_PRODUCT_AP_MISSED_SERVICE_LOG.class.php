<?php
/*
This class is used to send queries to AP_PROFILE_INFO table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_MISSED_SERVICE_LOG extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	public function Update($pid)
	{
		try{
			$sql="UPDATE Assisted_Product.AP_MISSED_SERVICE_LOG SET COMPLETED='N' WHERE COMPLETED='' AND PROFILEID=:profileid";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
}
?>
