<?php

class billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING extends TABLE
{
	public function __construct($dbname="")
	{
    	parent::__construct($dbname);
  	}

  	/**
     * Function to insert entry into EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $params
     * @return  none
     */ 
	public function addClientMemberEntry($params)
	{
		try{
			if(is_array($params) && $params){
				$sql = "INSERT IGNORE INTO billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING (ID,CLIENT_ID,MEMBER_ID,ENTRY_DT,SCREENED_STATUS) VALUES(NULL,:CLIENT_ID,:MEMBER_ID,:ENTRY_DT,:SCREENED_STATUS)";
				$res = $this->db->prepare($sql);
				$res->bindValue(":CLIENT_ID", $params["CLIENT_ID"], PDO::PARAM_INT);
				$res->bindValue(":MEMBER_ID", $params["MEMBER_ID"], PDO::PARAM_INT);
				$res->bindValue(":SCREENED_STATUS", $params["SCREENED_STATUS"], PDO::PARAM_STR);
				$res->bindValue(":ENTRY_DT", date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$res->execute();
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}


  	/**
     * Function to get pending entry from EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $none
     * @return  none
     */ 
	public function getPendingJsExclusiveEoiData()
	{
		try{
				$sql = "select * from  billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING where SCREENED_STATUS='P' /*and DATE(ENTRY_DT)=CURDATE()*/";
				$res = $this->db->prepare($sql);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{	
					$output[] = $row;
				}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	/**
     * Function to update screened status entry in EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $id
     * @return  none
     */ 
	public function updateScreenedStatus($id)
	{
		try{
			if($id){
				$sql = "UPDATE  billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING SET  `SCREENED_STATUS` =  'Y' WHERE  `ID` =:ID ";
				$res = $this->db->prepare($sql);
				$res->bindValue(":ID", $id, PDO::PARAM_INT);
				$res->execute();
				return true;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
			return false;
		}
		
	}

	/**
     * Function to update error reason and status entry in EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $id
     * @return  none
     */ 
	public function updateSendEoiError($id,$reason)
	{
		try{
			if($id){
				$sql = "UPDATE  billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING SET  `SCREENED_STATUS` =  'E', `FAILURE_REASON`=:REASON WHERE  `ID` =:ID ";
				$res = $this->db->prepare($sql);
				$res->bindValue(":ID", $id, PDO::PARAM_INT);
				$res->bindValue(":REASON", $reason, PDO::PARAM_STR);
				$res->execute();
				return true;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
			return false;
		}
		
	}

	public function getRBInterestsForAgent($clientArr,$startDT,$endDT){
	    try{
            $sql = "SELECT SCREENED_STATUS, ENTRY_DT FROM billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING WHERE ENTRY_DT >= :START_DT AND ENTRY_DT <= :END_DT AND CLIENT_ID IN (";
            $COUNT = 1;
            foreach ($clientArr as $key=>$value){
                $valueToInsert .= ":KEY".$COUNT.",";
                $bind[":KEY".$COUNT] = $value;
                $COUNT++;
            }
            $sql .= rtrim($valueToInsert,',').")";
            $res = $this->db->prepare($sql);
            $res->bindValue(":START_DT", $startDT, PDO::PARAM_STR);
            $res->bindValue(":END_DT", $endDT, PDO::PARAM_STR);
            foreach ($bind as $key=>$value){
                $res->bindValue($key, $value, PDO::PARAM_INT);
            }
            $res->execute();
            $output = array();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $day = date("d",strtotime($result["ENTRY_DT"]));
                $day = ltrim($day,"0");
                if(!$output[$day]){
                    $output[$day]["Y"] = 0;
                    $output[$day]["N"] = 0;
                    $output[$day]["P"] = 0;
                    $output[$day]["E"] = 0;
                    $output[$day]["S"] = 0;
                    $output[$day]["D"] = 0;
                }

                $output[$day][$result["SCREENED_STATUS"]]++;

            }
            return $output;
        } catch(Exception $e){
	        throw new jsException($e);
        }
    }

    public function getRBInterestsForClients($clientArr,$startDT,$endDT){
        try{
            $sql = "SELECT CLIENT_ID, SCREENED_STATUS, ENTRY_DT FROM billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING WHERE ENTRY_DT >= :START_DT AND ENTRY_DT <= :END_DT AND CLIENT_ID IN (";
            $COUNT = 1;
            foreach ($clientArr as $key=>$value){
                $valueToInsert .= ":KEY".$COUNT.",";
                $bind[":KEY".$COUNT] = $value;
                $COUNT++;
            }
            $sql .= rtrim($valueToInsert,',').")";
            $res = $this->db->prepare($sql);
            $res->bindValue(":START_DT", $startDT, PDO::PARAM_STR);
            $res->bindValue(":END_DT", $endDT, PDO::PARAM_STR);
            foreach ($bind as $key=>$value){
                $res->bindValue($key, $value, PDO::PARAM_INT);
            }
            $res->execute();
            $output = array();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $day = date("d",strtotime($result["ENTRY_DT"]));
                $day = ltrim($day,"0");
                if(!$output[$result["CLIENT_ID"]][$day]){
                    $output[$result["CLIENT_ID"]][$day]["Y"] = 0;
                    $output[$result["CLIENT_ID"]][$day]["N"] = 0;
                    $output[$result["CLIENT_ID"]][$day]["P"] = 0;
                    $output[$result["CLIENT_ID"]][$day]["E"] = 0;
                    $output[$result["CLIENT_ID"]][$day]["S"] = 0;
                    $output[$result["CLIENT_ID"]][$day]["D"] = 0;
                }

                $output[$result["CLIENT_ID"]][$day][$result["SCREENED_STATUS"]]++;
            }
            return $output;
        } catch(Exception $e){
            throw new jsException($e);
        }
    }

    public function getClientInfo($clientID,$startDT,$endDT){
        try{
            $sql = "SELECT SCREENED_STATUS FROM billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING WHERE ENTRY_DT >= :START_DT AND ENTRY_DT <= :END_DT AND CLIENT_ID = :CLIENT_ID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":START_DT", $startDT, PDO::PARAM_STR);
            $res->bindValue(":END_DT", $endDT, PDO::PARAM_STR);
            $res->bindValue(":CLIENT_ID", $clientID, PDO::PARAM_INT);
            $res->execute();
            $output = array();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                if($output[$result["SCREENED_STATUS"]]){
                    $output[$result["SCREENED_STATUS"]]++;
                } else{
                    $output[$result["SCREENED_STATUS"]] = 1;
                }
            }
            return $output;
        } catch (Exception $e){
            throw new jsException($e);
        }
    }
}
?>