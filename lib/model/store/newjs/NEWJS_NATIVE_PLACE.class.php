<?php
class NEWJS_NATIVE_PLACE extends TABLE 
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
    
    public function InsertRecord($arrRecordData)
    {
		if(!is_array($arrRecordData))
			throw new jsException("","Array is not passed in InsertRecord OF NEWJS_NATIVE_PLACE.class.php");
			
		try{
			$szINs = implode(',',array_fill(0,count($arrRecordData),'?'));
			
			$arrFields = array();
			foreach($arrRecordData as $key=>$val)
			{
				$arrFields[] = strtoupper($key);
			}
			$szFields = implode(",",$arrFields);
			
			$sql = "INSERT IGNORE INTO newjs.NATIVE_PLACE ($szFields) VALUES ($szINs)";
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
	
	public function UpdateRecord($iProfileID,$arrRecordData)
	{
		if(!is_numeric(intval($iProfileID)) || !$iProfileID)
		{
			throw new jsException("","iProfileID is not numeric in UpdateRecord OF NEWJS_NATIVE_PLACE.class.php");
		}
		
		if(!is_array($arrRecordData))
			throw new jsException("","Array is not passed in UpdateRecord OF NEWJS_NATIVE_PLACE.class.php");
		
		if(isset($arrRecordData['PROFILEID']) && strlen($arrRecordData['PROFILEID'])>0)
			throw new jsException("","Trying to update PROFILEID in  in UpdateRecord OF NEWJS_NATIVE_PLACE.class.php");
			
		try
		{
			$arrFields = array();
			foreach($arrRecordData as $key=>$val)
			{
				$columnName = strtoupper($key);
				
				$arrFields[] = "$columnName = ?";
			}
			$szFields = implode(",",$arrFields);
			
			$sql = "UPDATE newjs.NATIVE_PLACE SET $szFields WHERE PROFILEID = ?";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$count =0;
			foreach ($arrRecordData as $k => $value)
			{
				++$count;
				$pdoStatement->bindValue(($count), $value);
			}
			++$count;
			$pdoStatement->bindValue($count,$iProfileID);
			
			$pdoStatement->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}	
	}
    
    public function getRecord($iProfileID)
    {
		if(!is_numeric(intval($iProfileID)))
		{
			throw new jsException("","iProfileID is not numeric in UpdateRecord OF NEWJS_NATIVE_PLACE.class.php");
		}
		
		try
		{
			$sql = "SELECT NATIVE_COUNTRY,NATIVE_STATE,NATIVE_CITY FROM newjs.NATIVE_PLACE WHERE PROFILEID = ?";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$count =0;
			$pdoStatement->bindValue(++$count,$iProfileID);
			
			$pdoStatement->execute();
			
			$arrResult = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
			return $arrResult[0];
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}	
	}
}
?>
