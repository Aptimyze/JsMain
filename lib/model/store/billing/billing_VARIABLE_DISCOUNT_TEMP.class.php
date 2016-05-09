<?php
class billing_VARIABLE_DISCOUNT_TEMP extends TABLE{
       
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }
	

    /*function to truncate entire table*/
    public function truncateTable()
    {
        try
        {
            $sql = "TRUNCATE TABLE billing.VARIABLE_DISCOUNT_TEMP";
            $res = $this->db->prepare($sql);
            $res->execute();
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }

	/*function to get records from table
    * @params : $fields(","separated list or *),$limit,$offset
    * @return: rows 
    */
    public function fetchAllRecords($fields="*",$limit,$offset)
    {
        try
        {
            $sql = "SELECT ".$fields." FROM billing.VARIABLE_DISCOUNT_TEMP LIMIT ".$limit." OFFSET ".$offset;
            $res = $this->db->prepare($sql);
            $res->execute();
            while($result = $res->fetch(PDO::FETCH_ASSOC))
            {
                $vdData[] = $result;
            }
            return $vdData;
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }

    public function addVDRecordsInTemp($params)
    {
       
        $valuesStr = "";
        try
        {
            if(is_array($params) && $params)
            {
                $valuesStr = $valuesStr."(:PROFILEID,:SDATE,:EDATE,:SERVICE,:DISC3,:DISC6,:DISC12)"; 
                    
                $sql = "INSERT IGNORE INTO billing.VARIABLE_DISCOUNT_TEMP (`PROFILEID`,`SDATE`,`EDATE`,`SERVICE`,`3`,`6`,`12`) VALUES ".$valuesStr;
                
                $res = $this->db->prepare($sql);
              
                $res->bindValue(":PROFILEID", $params["PROFILEID"], PDO::PARAM_INT);
                $res->bindValue(":SDATE",$params["SDATE"], PDO::PARAM_STR);
                $res->bindValue(":EDATE",$params["EDATE"], PDO::PARAM_STR);
                $res->bindValue(":SERVICE",$params["SERVICE"], PDO::PARAM_STR);
                $res->bindValue(":DISC3", $params["3"], PDO::PARAM_INT);
                $res->bindValue(":DISC6", $params["6"], PDO::PARAM_INT);
                $res->bindValue(":DISC12", $params["12"], PDO::PARAM_INT);
                $res->execute(); 
            }
            
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getCountOfRecords()
    {
       try
        {
            $sql = "SELECT COUNT(*) AS CNT FROM billing.VARIABLE_DISCOUNT_TEMP";
            $res = $this->db->prepare($sql);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_ASSOC);
            return $result['CNT'];
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        } 
    }
}
?>
