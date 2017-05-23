<?php
class incentive_LOCATION_CITY extends TABLE 
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID")
        {
                if(!$valueArray && !$excludeArray  && !$greaterThanArray)
                        throw new jsException("","no where conditions passed");
                try
                {
                        $sqlSelectDetail = "SELECT $fields FROM incentive.LOCATION_CITY WHERE ";
                        $count = 1;
                        if(is_array($valueArray))
                        {
                                foreach($valueArray as $param=>$value)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $param IN ($value) ";
                                        else
                                                $sqlSelectDetail.=" AND $param IN ($value) ";
                                        $count++;
                                }
                        }
                        if(is_array($excludeArray))
                        {
                                foreach($excludeArray as $excludeParam => $excludeValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $excludeParam NOT IN ($excludeValue) ";
                                        else
						 $sqlSelectDetail.=" AND $excludeParam NOT IN ($excludeValue) ";
                                        $count++;
                                }
                        }
                        if(is_array($greaterThanArray))
                        {
                                foreach($greaterThanArray as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam > '$gValue' ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam > '$gValue' ";
                                        $count++;
                                }
                        }

                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
                        /*
                        foreach ($valueArray as $k => $val)
                        {
                                $resSelectDetail->bindValue(($k+1), $val);
                        }
                        */
                        $resSelectDetail->execute();
                        while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $rowSelectDetail;
                        }
                          return $detailArr;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }

	public function fetchCitiesOfStates($stateArr)
	{
		try
		{
			$stateStr="'".implode("','",$stateArr)."'";
			$valueArr['STATE']=$stateStr;
                        $city=$this->getArray($valueArr,"","","VALUE");
                        for($i=0;$i<count($city);$i++)
                                $cityArr[]=$city[$i]['VALUE'];
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $cityArr;
	}
	public function fetchLocationWithoutBranches($state,$centersStr)
	{
		try
                {
		/*	$count = count($centers);
			$in_params = trim(str_repeat('?, ', $count), ', ');
                        $sql="SELECT VALUE FROM incentive.LOCATION_CITY WHERE STATE=$state AND VALUE NOT IN ({$in_params})";
			$prep = $this->db->prepare($sql);
                        $prep->execute($centers);
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $city[] = $result['VALUE'];
                        }*/
			$valueArr['STATE']="'"."$state"."'";
			$excludeArr['VALUE']=$centersStr;
			$city=$this->getArray($valueArr,$excludeArr,"","VALUE");
			for($i=0;$i<count($city);$i++)
				$cityArr[]=$city[$i]['VALUE'];

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $cityArr;
	}
	public function fetchStateWithoutBranches($centers)
        {
                try
                {
			$count = count($centers);
                        $in_params = trim(str_repeat('?, ', $count), ', ');
                        $sql="select DISTINCT STATE from incentive.LOCATION_CITY WHERE STATE NOT IN({$in_params})";
                        $prep = $this->db->prepare($sql);
                        $prep->execute($centers);
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $state[] = $result['STATE'];
                        }

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $state;
        }
	public function fetchValueOfState($state)
        {
                try
                {
                        $sql="SELECT VALUE FROM incentive.LOCATION_CITY WHERE STATE=:STATE";
                        $prep = $this->db->prepare($sql);
			$prep->bindParam(":STATE", $state, PDO::PARAM_STR);
                        $prep->execute($centers);
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $res[] = $result['VALUE'];
                        }

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
	}	
}
