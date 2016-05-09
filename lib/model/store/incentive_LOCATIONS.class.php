<?php
class incentive_LOCATION extends TABLE 
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
	public function fetchLocations($state)
	{
		try
                {
                        $sql="SELECT VALUE from incentive.LOCATION WHERE STATE = :STATE";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":STATE",$state,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $locations[] = $result['VALUE'];
                        }

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $locations;
	}
	public function fetchSpecialCities()
	{
		try
                {
                        $sql="SELECT VALUE,STATE FROM incentive.LOCATION WHERE SPECIAL_CITY='Y'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $city = $result['VALUE'];
				$citySelArr[$city]=$result['STATE'];
                        }

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $citySelArr;
	}
	public function fetchStatesOfCities($centers)
	{
		try
                {
			$count = count($centers);
                        $in_params = trim(str_repeat('?, ', $count), ', ');
                        $sql="SELECT DISTINCT STATE from incentive.LOCATION WHERE UPPER(NAME) IN({$in_params})";
                        $prep = $this->db->prepare($sql);
                        $prep->execute($centers);
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $states[] = $result['STATE'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $states;

	}
        public function fetchLocationName($locationValue)
        {
                try
                {
                        $sql="SELECT NAME from incentive.LOCATION WHERE VALUE=:VALUE";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":VALUE",$locationValue,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $locationName =trim($result['NAME']);
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $locationName;
        }
}
