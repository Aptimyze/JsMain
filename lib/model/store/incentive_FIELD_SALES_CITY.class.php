<?php
class incentive_FIELD_SALES_CITY extends TABLE 
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
	public function getAllCity()
	{
		try
                {
                        $sql="SELECT VALUE from incentive.FIELD_SALES_CITY";
			$prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $cityArr[] = $result['VALUE'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $cityArr;
	}

	public function checkFieldSalesCityCodeExists($city_res){
		try
		{
			$sql="SELECT COUNT(*) AS COUNT from incentive.FIELD_SALES_CITY WHERE VALUE=:CITY_RES";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":CITY_RES",$city_res,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$cityArr = $result['COUNT'];
			} else {
				$cityArr = 0;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $cityArr;
	}
}
