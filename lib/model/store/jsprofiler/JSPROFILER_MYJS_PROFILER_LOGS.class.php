<?php
class JSPROIFLER_MYJS_PROFILER_LOGS extends TABLE 
{
	
	/**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    
    /**
     * 
     * @param type $arrRecordData
     * @return type
     * @throws jsException
     */
    public function insertRecord($arrRecordData)
    {
		if(!is_array($arrRecordData))
			throw new jsException("","Array is not passed in InsertRecord OF JSPROIFLER_MYJS_PROFILER_LOGS.class.php");
			
		try{
			$szINs = implode(',',array_fill(0,count($arrRecordData),'?'));
			
			$arrFields = array();
			foreach($arrRecordData as $key=>$val)
			{
				$arrFields[] = strtoupper($key);
			}
			$szFields = implode(",",$arrFields);
			
			$sql = "INSERT IGNORE INTO jsprofiler.MYJS_PROFILER_LOGS ($szFields) VALUES ($szINs)";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$count =0;
			foreach ($arrRecordData as $k => $value)
			{
				++$count;
				$pdoStatement->bindValue(($count), $value);
			}
			$pdoStatement->execute();
      
			return $pdoStatement->rowCount();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
}
?>
