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
    public function fetchAllRecords($fields="*",$limit,$offset,$entryDate)
    {
        try
        {
            $sql = "SELECT ".$fields." FROM billing.VARIABLE_DISCOUNT_TEMP WHERE EDATE>=:EDATE LIMIT ".$limit." OFFSET ".$offset;
            $res = $this->db->prepare($sql);
	    $res->bindValue(":EDATE",$entryDate, PDO::PARAM_STR);
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
    public function fetchActiveRecords($entryDate)
    {
        try
        {
            $sql = "SELECT vdt.* FROM billing.VARIABLE_DISCOUNT_TEMP vdt left join billing.VARIABLE_DISCOUNT vd on vdt.PROFILEID=vd.PROFILEID WHERE vdt.EDATE >=:TODAY AND vd.PROFILEID IS NULL"; 
	    //$sql = "SELECT vdt.* FROM billing.VARIABLE_DISCOUNT_TEMP vdt WHERE vdt.EDATE >=:TODAY";	
            $res = $this->db->prepare($sql);
            $res->bindValue(":TODAY",$entryDate, PDO::PARAM_STR);
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
                $valuesStr = $valuesStr."(:PROFILEID,:SDATE,:EDATE,:SERVICE,:DISC2,:DISC3,:DISC6,:DISC12)"; 
                    
                $sql = "INSERT IGNORE INTO billing.VARIABLE_DISCOUNT_TEMP (`PROFILEID`,`SDATE`,`EDATE`,`SERVICE`,`2`,`3`,`6`,`12`) VALUES ".$valuesStr;
                
                $res = $this->db->prepare($sql);
              
                $res->bindValue(":PROFILEID", $params["PROFILEID"], PDO::PARAM_INT);
                $res->bindValue(":SDATE",$params["SDATE"], PDO::PARAM_STR);
                $res->bindValue(":EDATE",$params["EDATE"], PDO::PARAM_STR);
                $res->bindValue(":SERVICE",$params["SERVICE"], PDO::PARAM_STR);
		$res->bindValue(":DISC2", $params["2"], PDO::PARAM_INT);
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

    public function getCountOfRecords($entryDate)
    {
       try
        {
            $sql = "SELECT COUNT(1) AS CNT FROM billing.VARIABLE_DISCOUNT_TEMP WHERE EDATE>=:EDATE";
            $res = $this->db->prepare($sql);
	    $res->bindValue(":EDATE",$entryDate, PDO::PARAM_STR);	
            $res->execute();
            $result = $res->fetch(PDO::FETCH_ASSOC);
            return $result['CNT'];
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        } 
    }
    public function deleteExpiredMiniVdDiscount($todayDate)
    {
        try{
            $sql ="DELETE FROM billing.VARIABLE_DISCOUNT_TEMP WHERE EDATE<:EDATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":EDATE", $todayDate, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>
