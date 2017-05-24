<?php
class incentive_PRE_ALLOCATION_PINCODE_MAPPING extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	public function getPincodes($locality)
	{
		try
		{
			$locality =trim($locality);	
			$sql = "SELECT distinct PINCODE FROM incentive.PRE_ALLOCATION_PINCODE_MAPPING WHERE UPPER(LOCALITY)=UPPER(:LOCALITY)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":LOCALITY", $locality, PDO::PARAM_STR);
	                $res->execute();
			while($result = $res->fetch(PDO::FETCH_ASSOC))
				$pincodeArr[] =$result['PINCODE'];
			return $pincodeArr;	
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
