<?php
class NEWJS_FILTER extends TABLE{
       

        

        public function __construct($dbname="")
        {
			$dbname=$dbname?$dbname:"newjs_master";

			parent::__construct($dbname);
        }
	public function fetchEntry($profileid)
	{
		try
		{
			$res=null;
			if($profileid)
			{
				$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID=:PROFILEID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->execute();
        JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res=$result;
				}
			}
			else
				throw new jsException("error in filter class no profileid");
				
			return $res;	
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	/**
	  * This function returns details from newjs.FILTERS for an array of profileids passed.
	**/

	public function fetchFilterDetailsForMultipleProfiles($profileIdArr)
	{
		if(is_array($profileIdArr))
		{
			foreach($profileIdArr as $key=>$pid)
			{
				if($key == 0)
					$str = ":PROFILEID".$key;
				else
					$str .= ",:PROFILEID".$key;
			}
			$sql = "SELECT * FROM newjs.FILTERS WHERE PROFILEID IN ($str) ";
			$res=$this->db->prepare($sql);
			unset($pid);
			foreach($profileIdArr as $key=>$pid)
			{
				$res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
			}
			$res->execute();
      JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row['PROFILEID']] = $row;
			}
			return $result;
		}
	}
	
	/**
	  * This function update detail into newjs.FILTERS filter for a profileid.
	**/

	public function updateFilters($profileId,$updStr)
	{
    $arrRecordData = $this->convertUptStrToArray($updStr);
    return $this->updateRecord($profileId, $arrRecordData);
	}
	
	/**
	  * This function insert row into newjs.FILTERS filter for a profileid.
	**/

	public function insertFilterEntry($profileId,$updStr)
	{
    $arrRecordData = $this->convertUptStrToArray($updStr);
    return $this->insertRecord($profileId, $arrRecordData);
	}
	
	/*
	  * This function fetches the row depending upon some conidtion newjs.FILTERS filter for a profileid.
	**/

	public function fetchFilterDetails($profileId,$whrStr="",$selectStr="*")
	{
		try
		{
			$res=null;
			if($profileId)
			{
				$sql="select $selectStr from newjs.FILTERS where PROFILEID=:profileId ".$whrStr;
				
				
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":profileId",$profileId,PDO::PARAM_INT);
				
				$prep->execute();
        JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res=$result;
					return $res;
				}
			}
			else
				throw new jsException("error in filter class either no profileid or no updStr");
			
				
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
  public function setAllFilters($profile)
  {
    if (!$profile)
      throw new jsException("", "PROFILEID IS BLANK IN newjs_FILTERS.class.php");
    try {
      $sql = "REPLACE INTO newjs.FILTERS (PROFILEID,AGE,MSTATUS,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MTONGUE,INCOME,COUNT,HARDSOFT) VALUES(:PROFILEID,:AGE,:MSTATUS,:RELIGION,:CASTE,:COUNTRY_RES,:CITY_RES,:MTONGUE,:INCOME,:COUNT,:HARDSOFT)";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PROFILEID", $profile, PDO::PARAM_INT);
      $prep->bindValue(":AGE", "Y", PDO::PARAM_STR);
      $prep->bindValue(":MSTATUS", "Y", PDO::PARAM_STR);
      $prep->bindValue(":RELIGION", "Y", PDO::PARAM_STR);
      $prep->bindValue(":CASTE", "Y", PDO::PARAM_STR);
      $prep->bindValue(":COUNTRY_RES", "Y", PDO::PARAM_STR);
      $prep->bindValue(":CITY_RES", "Y", PDO::PARAM_STR);
      $prep->bindValue(":MTONGUE", "Y", PDO::PARAM_STR);
      $prep->bindValue(":INCOME", "Y", PDO::PARAM_STR);
      $prep->bindValue(":COUNT", 1, PDO::PARAM_INT);
      $prep->bindValue(":HARDSOFT", "Y", PDO::PARAM_STR);
      $prep->execute();
      JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
      return true;
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
    return false;
  }

  /**
   * updateRecord
   * @param type $iProfileID
   * @param type $arrRecordData
   * @return boolean
   * @throws jsException
   */
  public function updateRecord($iProfileID,$arrRecordData)
	{
    if(!is_numeric(intval($iProfileID)) || !$iProfileID)
		{
			throw new jsException("iProfileID is not numeric in UpdateRecord OF NEWJS_FILTER.class.php");
		}
    
    if(!is_array($arrRecordData)){
			throw new jsException("","Array is not passed in UpdateRecord OF NEWJS_FILTER.class.php");
    }
		
		if(isset($arrRecordData['PROFILEID']) && strlen($arrRecordData['PROFILEID'])>0) {
  		throw new jsException("","Trying to update PROFILEID in  in UpdateRecord OF NEWJS_FILTER.class.php");
    }
    
		try	{
      $arrFields = array();
			foreach($arrRecordData as $key=>$val)
			{
				$columnName = strtoupper($key);
				
				$arrFields[] = "$columnName = ?";
			}
			$szFields = implode(",",$arrFields);
      
      $sql="UPDATE newjs.FILTERS set $szFields where PROFILEID = ?";
      $pdoStatement=$this->db->prepare($sql);
      
      //Bind Value
			$count =0;
			foreach ($arrRecordData as $k => $value)
			{
				++$count;
        $paramType = PDO::PARAM_STR;
        if(is_numeric($value)) {
          $paramType = PDO::PARAM_INT;
        }
        $pdoStatement->bindValue(($count), $value,$paramType);
			}
			++$count;
			$pdoStatement->bindValue($count,$iProfileID);
      $pdoStatement->execute();
      JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
      if($pdoStatement->rowCount()){
        return true;
      }
      return false;
		} catch(PDOException $e) {
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
   /**
   * updateRecord
   * @param type $iProfileID
   * @param type $arrRecordData
   * @return boolean
   * @throws jsException
   */
  public function insertRecord($iProfileID,$arrRecordData)
	{
    if(!is_numeric(intval($iProfileID)) || !$iProfileID)
		{
			throw new jsException("iProfileID is not numeric in UpdateRecord OF NEWJS_FILTER.class.php");
		}
    
    if(!is_array($arrRecordData)){
			throw new jsException("","Array is not passed in UpdateRecord OF NEWJS_FILTER.class.php");
    }
		
		$arrRecordData['PROFILEID'] = $iProfileID;
    
    try	{
      $szINs = implode(',',array_fill(0,count($arrRecordData),'?'));
			
			$arrFields = array();
			foreach($arrRecordData as $key=>$val)
			{
				$arrFields[] = strtoupper($key);
			}
			$szFields = implode(",",$arrFields);
			
			$sql = "INSERT IGNORE INTO newjs.FILTERS ($szFields) VALUES ($szINs)";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$count =0;
			foreach ($arrRecordData as $k => $value)
			{
				++$count;
        $paramType = PDO::PARAM_STR;
        if(is_numeric($value)) {
          $paramType = PDO::PARAM_INT;
        }
				$pdoStatement->bindValue(($count), $value,$paramType);
			}
			$pdoStatement->execute();
      JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return $pdoStatement->rowCount();
		} catch(PDOException $e) {
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
  
  /**
   * 
   * @param type $uptStr
   * @return type
   */
  private function convertUptStrToArray($uptStr)
  {
    $arrayColumns = explode(",",$uptStr);
    $arrOut = array();
    foreach($arrayColumns as $params) {
      $arrTokens = explode("=",$params);
      $szVal = $arrTokens[1];
      $szVal = str_replace(array('\'','"',"\\"), "", $szVal);
      $arrOut[trim($arrTokens[0])] = trim($szVal);
    }
    return $arrOut;
  }


  public function fetchField($field,$limit='',$offset='')
  {
  	try
  	{
  		
  		$sql="SELECT PROFILEID FROM FILTERS WHERE ".$field." =  ''";

  		if($limit && $offset==""){
  			$sql = $sql." LIMIT :LIMIT";
  		}
  		else if($limit && $offset!=""){
  			$sql = $sql." LIMIT :OFFSET,:LIMIT";
  		}

  		$prep=$this->db->prepare($sql);

		if($limit && $offset==""){
            $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
		}
        else if($limit && $offset!=""){
            $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
            $prep->bindValue(":OFFSET",$offset,PDO::PARAM_INT);
        }

  		$prep->execute();
      JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
  		if($result = $prep->fetchAll(PDO::FETCH_ASSOC))
  		{
  			return $result;
  		}
  		return false;
  	}
  	catch(PDOException $e)
  	{
  		throw new jsException($e);
  	}
  }

  public function updateField($field,$profileIdArr)
  {
  	try
  	{
  		foreach($profileIdArr as $key=>$pid)
		{
			if($key == 0)
				$str = ":PROFILEID".$key;
			else
				$str .= ",:PROFILEID".$key;
		}

  		$sql="UPDATE FILTERS SET ".$field." = 'Y' WHERE profileid in ($str) AND ".$field." = ''";

  		$prep=$this->db->prepare($sql);

  		foreach($profileIdArr as $key=>$pid)
  		{
  			$prep->bindValue(":PROFILEID".$key, $pid['PROFILEID'], PDO::PARAM_INT);
  		}
  		$prep->execute();
      JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
  	}
  	catch(PDOException $e)
  	{
  		throw new jsException($e);
  	}
  }



}
?>
