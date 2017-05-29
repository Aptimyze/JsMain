<?php
class test_VD_UPLOAD_TEMP extends TABLE{
       
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }
	

	/*function to get records from table with limit and offset
    * @params : $fields(","separated list or *),$limit,$offset
    * @return: rows 
    */
    public function fetchSelectedRecords($fields="*",$limit,$offset)
    {
        try
        {
            $sql = "SELECT ".$fields." FROM test.VD_UPLOAD_TEMP LIMIT ".$limit." OFFSET ".$offset;
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
    /*function to get count of rows in table
    * @params : none
    * @return: count 
    */
    public function getCountOfRecords()
    {
        try
        {
            $sql = "SELECT COUNT(1) AS CNT FROM test.VD_UPLOAD_TEMP";
            $res = $this->db->prepare($sql);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_ASSOC);
            return $result["CNT"];
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }
    
    // Add records in Temp
    public function addVDRecordsInUploadTemp($profileid,$startDate,$endDate,$discount,$service)
    {
        try{
                $sql = "INSERT IGNORE INTO test.VD_UPLOAD_TEMP (`PROFILEID`,`SDATE`,`EDATE`,`SERVICE`,`1`,`2`,`3`,`6`,`12`) VALUES(:PROFILEID,:SDATE,:EDATE,:SERVICE,:DISC1,:DISC2,:DISC3,:DISC6,:DISC12)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":SDATE",$startDate, PDO::PARAM_STR);
                $res->bindValue(":EDATE",$endDate, PDO::PARAM_STR);
                $res->bindValue(":SERVICE",$service, PDO::PARAM_STR);
		$res->bindValue(":DISC1", $discount, PDO::PARAM_INT);
                $res->bindValue(":DISC2", $discount, PDO::PARAM_INT);
                $res->bindValue(":DISC3", $discount, PDO::PARAM_INT);
                $res->bindValue(":DISC6", $discount, PDO::PARAM_INT);
                $res->bindValue(":DISC12", $discount, PDO::PARAM_INT);
                $res->execute();
	}
       	catch(Exception $e)
	{
        	throw new jsException($e);
        }


     }
        public function truncate()
        {
                try
                {
                        $sql="TRUNCATE TABLE test.VD_UPLOAD_TEMP";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

}
?>
