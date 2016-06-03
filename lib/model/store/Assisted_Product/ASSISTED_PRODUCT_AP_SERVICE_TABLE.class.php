<?php
/*
This class is used to send queries to AP_PROFILE_INFO table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_SERVICE_TABLE extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	public function Delete($pid)
	{
		try{
			$sql="DELETE FROM Assisted_Product.AP_SERVICE_TABLE WHERE PROFILEID=:profileid";
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
