<?php
class newjs_PINCODE_MAPPING extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	public function getPincode($locality)
	{
		try
		{
			$locality =trim($locality);	
			$sql = "SELECT distinct PINCODE FROM newjs.PINCODE_MAPPING WHERE UPPER(LOCALITY)=UPPER(:LOCALITY)";
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
        public function checkPincodeExist($pincode)
        {
                try
                {
                        $pincode =trim($pincode);
                        $sql ="SELECT PINCODE FROM newjs.PINCODE_MAPPING WHERE PINCODE=:PINCODE";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":PINCODE", $pincode, PDO::PARAM_INT);
                        $res->execute();
                        if($result = $res->fetch(PDO::FETCH_ASSOC))
                                $pincodeVal =$result['PINCODE'];
                        return $pincodeVal;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function getPincodeList()
        {
                try
                {
                        $sql ="SELECT PINCODE FROM newjs.PINCODE_MAPPING";
                        $res=$this->db->prepare($sql);
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
