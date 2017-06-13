<?php

/*********************
* Author : Neha Gupta
*********************/

class incentive_SALES_TARGET extends TABLE{

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be mads->filtered_names_arrie
         */

        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

        public function getDetailsForViewMode($monthYear, $fortnight)
        {
                try
                {
                        $sql="SELECT USERNAME, INDIVIDUAL_TARGET, FINAL_TARGET, MONTHWISE_LEVEL, HAS_DIRECT_REPORTEE from incentive.SALES_TARGET where TARGET_MONTH=:MONTH_YEAR AND FORTNIGHT=:FORTNIGHT ORDER BY(MONTHWISE_ORDER)";
                        $result = $this->db->prepare($sql);
                        $result->bindValue(":MONTH_YEAR", $monthYear, PDO::PARAM_STR);
                        $result->bindValue(":FORTNIGHT", $fortnight, PDO::PARAM_INT);
                        $result->execute();

			$i=0;
                        while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$res[$i]['USERNAME'] = $row['USERNAME'];  
				$res[$i]['INDIVIDUAL_TARGET'] = $row['INDIVIDUAL_TARGET'];  
				$res[$i]['FINAL_TARGET'] = $row['FINAL_TARGET'];  
				$res[$i]['MONTHWISE_LEVEL'] = $row['MONTHWISE_LEVEL'];  
				$res[$i]['HAS_DIRECT_REPORTEE'] = $row['HAS_DIRECT_REPORTEE'];  
				$i++;
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $res;
	}

        public function getDetailsForEditMode($monthYear, $usernames, $fortnight)
        {
                try
                {
                        $usernames = implode("','", $usernames);
                        $usernames = "'".$usernames."'";

                        $sql="SELECT USERNAME, INDIVIDUAL_TARGET from incentive.SALES_TARGET where TARGET_MONTH=:MONTH_YEAR AND FORTNIGHT=:FORTNIGHT AND USERNAME IN ($usernames)";
                        $result = $this->db->prepare($sql);
                        $result->bindValue(":MONTH_YEAR", $monthYear, PDO::PARAM_STR);
                        $result->bindValue(":FORTNIGHT", $fortnight, PDO::PARAM_INT);
                        $result->execute();

                        while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$res[$row['USERNAME']] = $row['INDIVIDUAL_TARGET'];  
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $res;
	}

        public function removeData($monthYear, $fortnight)
        {
                try
                {
                        $sql="DELETE FROM incentive.SALES_TARGET WHERE TARGET_MONTH=:MONTH_YEAR AND FORTNIGHT=:FORTNIGHT";
                        $result = $this->db->prepare($sql);
                        $result->bindValue(":MONTH_YEAR", $monthYear, PDO::PARAM_STR);
                        $result->bindValue(":FORTNIGHT", $fortnight, PDO::PARAM_INT);
                        $result->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}

        public function updateDetails($monthYear, $targetInfo, $fortnight)
        {
            
                try
                {
			for($i=0; $i<count($targetInfo); $i++)
			{
				if(!$targetInfo[$i]['USERNAME'])
					continue;
				if(!$targetInfo[$i]['INDIVIDUAL_TARGET'])
					$targetInfo[$i]['INDIVIDUAL_TARGET']=0;
				if(!$targetInfo[$i]['FINAL_TARGET'])
					$targetInfo[$i]['FINAL_TARGET']=0;
				if(!$targetInfo[$i]['LEVEL'])
					$targetInfo[$i]['LEVEL']=0;
				if(!$targetInfo[$i]['DIRECT_REPORTEE_STATUS'])
					$targetInfo[$i]['DIRECT_REPORTEE_STATUS']=0;
				$monthwise_order = $i;
                        	
				$sql="REPLACE INTO incentive.SALES_TARGET(USERNAME, INDIVIDUAL_TARGET, FINAL_TARGET, TARGET_MONTH, MONTHWISE_ORDER, MONTHWISE_LEVEL, HAS_DIRECT_REPORTEE, FORTNIGHT) VALUES(:USERNAME, :INDIVIDUAL_TARGET, :FINAL_TARGET, :TARGET_MONTH, :MONTHWISE_ORDER, :MONTHWISE_LEVEL, :HAS_DIRECT_REPORTEE, :FORTNIGHT)";

	                        $result = $this->db->prepare($sql);
        	                $result->bindValue(":USERNAME", $targetInfo[$i]['USERNAME'], PDO::PARAM_STR);
        	                $result->bindValue(":INDIVIDUAL_TARGET", $targetInfo[$i]['INDIVIDUAL_TARGET'], PDO::PARAM_INT);
        	                $result->bindValue(":FINAL_TARGET", $targetInfo[$i]['FINAL_TARGET'], PDO::PARAM_INT);
        	                $result->bindValue(":TARGET_MONTH", $monthYear, PDO::PARAM_STR);
        	                $result->bindValue(":MONTHWISE_ORDER", $monthwise_order, PDO::PARAM_INT);
        	                $result->bindValue(":MONTHWISE_LEVEL", $targetInfo[$i]['LEVEL'], PDO::PARAM_INT);
        	                $result->bindValue(":HAS_DIRECT_REPORTEE", $targetInfo[$i]['DIRECT_REPORTEE_STATUS'], PDO::PARAM_INT);
                            $result->bindValue(":FORTNIGHT", $fortnight, PDO::PARAM_INT);
                	        $result->execute();
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
        public function fetchSalesTarget($userArr, $targetMonth, $fortnight)
        {
                try
                {
                        $userArr = implode("','", $userArr);
                        $userArr = "'".$userArr."'";
                        $sql="SELECT USERNAME, INDIVIDUAL_TARGET, FINAL_TARGET FROM incentive.SALES_TARGET WHERE TARGET_MONTH=:MONTH_YEAR AND FORTNIGHT=:FORTNIGHT AND USERNAME IN($userArr)";
                        $result = $this->db->prepare($sql);
                        $result->bindValue(":MONTH_YEAR", $targetMonth, PDO::PARAM_STR);
                        $result->bindValue(":FORTNIGHT", $fortnight, PDO::PARAM_INT);
                        $result->execute();
	                while($row = $result->fetch(PDO::FETCH_ASSOC))
                        {
                                $individual[$row['USERNAME']] = $row['INDIVIDUAL_TARGET'];
                                $final[$row['USERNAME']] = $row['FINAL_TARGET'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return array($individual, $final);
        }
}
?>
