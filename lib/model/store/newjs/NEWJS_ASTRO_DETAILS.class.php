<?php
class NEWJS_ASTRO extends TABLE {
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    public function getAstros($pid) {
        try {
            if ($pid) {
                $sql = "SELECT COUNTRY_BIRTH,CITY_BIRTH,PROFILEID FROM ASTRO_DETAILS WHERE PROFILEID=:PROFILEID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
                $prep->execute();
                if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    return $result;
                }
                return array();
            }
        }
        catch(PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    public function update($pid, $paramArr = array()) {
        try {
            $keys = "PROFILEID,";
            $values = ":PROFILEID ,";
            foreach ($paramArr as $key => $value) {
                $keys.= $key . ",";
                $values.= ":" . $key . ",";
            }
            $keys = substr($keys, 0, -1);
            $values = substr($values, 0, -1);
            $sqlEditHobby = "REPLACE INTO ASTRO_DETAILS ($keys) VALUES ($values)";
            $resEditHobby = $this->db->prepare($sqlEditHobby);
            foreach ($paramArr as $key => $val) $resEditHobby->bindValue(":" . $key, $val);
            $resEditHobby->bindValue(":PROFILEID", $pid);
            $resEditHobby->execute();
            return true;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    /** Following function inserts values in ASTRO_PULLING_DETAILS table. 
     * 	It is used currently at registration page3.
     * 	It takes input as PROFILEID
     *
     */
    public function insertInAstroPullingDetails($profileid) {
        try {
            $sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES(:PROFILEID,NOW(),'Y','E')";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
            $res->execute();
            return true;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    /**
     * $profileIds = array of profileids whose astro details need to be found
     * $fields = string of column names that need to be returned
     * setWithProfileId : if its Y then return astro detail array will have pid as key  
     * return - array of astro details
     *
     */
    public function getAstroDetails($profileIds, $fields,$setWithProfileId='') {
         try {
		if ($fields == '') $fields = "*";
        	foreach ($profileIds as $key => $pid) {
            	if ($key <> 0) $bindStr.= ",:PROFILEID$key ";
            	else $bindStr = ":PROFILEID$key ";
        	}
        	$sql = "SELECT $fields FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN ($bindStr)";
        	$res = $this->db->prepare($sql);
        	foreach ($profileIds as $key => $pid) {
            	$res->bindValue(":PROFILEID" . $key, $pid, PDO::PARAM_INT);
        	}
        	$res->execute();
        	while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
	   	if(!$setWithProfileId)
            		$astroArr[] = $result;
	   	else
	   	{
			$pid=$result["PROFILEID"];
			$astroArr[$pid]= $result;
	   	}
        	}
        	return $astroArr;
	}
	catch(PDOException $e) {
            throw new jsException($e);
        }

    }
    public function getIfAstroDetailsPresent($profileid) {
        $sql = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID = :PROFILEID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
        $res->execute();
        if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
            return $result['COUNT'];
        }
        return 0;
    }
    //It Also checks in screening
    public function getIfHoroPresent($profileid) {
        $horo_present = false;
        if (!$this->getIfAstroDetailsPresent($profileid)) {
            $sql = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE WHERE PROFILEID = :PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                if ($result['COUNT']) $horo_present = true;
            }
            if (!$horo_present) {
                $sql = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE_FOR_SCREEN WHERE PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->execute();
                if ($result = $res->fetch(PDO::FETCH_ASSOC)) if ($result['COUNT']) $horo_present = true;
            }
        } else $horo_present = true;
        return $horo_present;
    }
    public function updateType($type,$pid)
	{
		try{

			if($type && $pid)
			{
				$sql="update newjs.ASTRO_DETAILS set TYPE=:type WHERE PROFILEID=:pid";
				$res = $this->db->prepare($sql);
		                $res->bindValue(":pid", $pid, PDO::PARAM_INT);
		                $res->bindValue(":type", $type, PDO::PARAM_STR);
				$res->execute();
			}
		}
		catch(PDOException $e) {
			throw new jsException($e);
        	}
		
	}
    public function deleteEntry($pid)
	{
		try{

			if($pid)
			{
				$sql="delete from newjs.ASTRO_DETAILS WHERE PROFILEID=:pid";
				$res = $this->db->prepare($sql);
		                $res->bindValue(":pid", $pid, PDO::PARAM_INT);
				$res->execute();
			}
		}
		catch(PDOException $e) {
			throw new jsException($e);
        	}
		
	}
}
?>
