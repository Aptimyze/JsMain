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
            $sql = "SELECT COUNT(*) AS CNT FROM test.VD_UPLOAD_TEMP";
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
}
?>
