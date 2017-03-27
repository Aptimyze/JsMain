<?php
class NEWJS_HOROSCOPE_FOR_SCREEN extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getHoroscope($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT HOROSCOPE from HOROSCOPE_FOR_SCREEN where PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
                    //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return $result[HOROSCOPE];
					}
					return false;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		 public function getHoroscopeIfNotDeleted($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT HOROSCOPE from HOROSCOPE_FOR_SCREEN where UPLOADED != 'D' AND PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
                    //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return $result[HOROSCOPE];
					}
					return false;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

	/**
	 * replaceRecord
	 * @param $pid
	 * @param array $paramArr
	 * @return bool
	 */
	public function replaceRecord($pid, $paramArr = array()) {
		try {
			$keys = "PROFILEID,";
			$values = ":PROFILEID ,";
			foreach ($paramArr as $key => $value) {
				$keys.= $key . ",";
				$values.= ":" . $key . ",";
			}
			$keys = substr($keys, 0, -1);
			$values = substr($values, 0, -1);
			$sql = "REPLACE INTO HOROSCOPE_FOR_SCREEN ($keys) VALUES ($values)";
			$res = $this->db->prepare($sql);

			foreach ($paramArr as $key => $val) {
				$res->bindValue(":" . $key, $val);
			}

			$res->bindValue(":PROFILEID", $pid);
			$res->execute();
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		}
		catch(PDOException $e) {
			throw new jsException($e);
		}
	}

	/**
	 * @param $iProfileID
	 * @param $blobHoroscope
	 * @param $cHoroscope
	 */
	public function insertRecord($iProfileID,$blobHoroscope,$cHoroscope='')
	{
		try{
			$sql = "INSERT INTO HOROSCOPE_FOR_SCREEN (`PROFILEID`,`HOROSCOPE`,`UPLOADED`) VALUE (:PID,:HORO,:UPLOADED)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
			$pdoStatement->bindValue(":HORO", $blobHoroscope);
			$pdoStatement->bindValue(":UPLOADED", $cHoroscope);
			$pdoStatement->execute();
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		} catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}

	/**
	 * updateRecord
	 * @param $iProfileID
	 * @param $arrRecordData
	 */
	public function updateRecord($iProfileID,$arrRecordData)
	{
		if(!is_numeric(intval($iProfileID)) || !$iProfileID)
		{
			throw new jsException("","iProfileID is not numeric in UpdateRecord OF NEWJS_HOROSCOPE_FOR_SCREEN.class.php");
		}

		if(!is_array($arrRecordData))
			throw new jsException("","Array is not passed in UpdateRecord OF NEWJS_HOROSCOPE_FOR_SCREEN.class.php");

		if(isset($arrRecordData['PROFILEID']) && strlen($arrRecordData['PROFILEID'])>0)
			throw new jsException("","Trying to update PROFILEID in  in UpdateRecord OF NEWJS_HOROSCOPE_FOR_SCREEN.class.php");

		try
		{
			$arrFields = array();
			foreach($arrRecordData as $key=>$val)
			{
				$columnName = strtoupper($key);

				$arrFields[] = "$columnName = ?";
			}
			$szFields = implode(",",$arrFields);

			$sql = "UPDATE newjs.HOROSCOPE_FOR_SCREEN SET $szFields WHERE PROFILEID = ?";
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
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	/**
	 * deleteAllScreenedRecords
	 * query to remove the entries from HOROSCOPE_FOR_SCREEN table
	 * which have UPLOADED field as 'Y' or 'D'
	 * @return bool
	 */
	public function deleteAllScreenedRecords()
	{
		try{
			$sql = 'DELETE FROM newjs.HOROSCOPE_FOR_SCREEN WHERE UPLOADED=\'Y\' or UPLOADED=\'D\'"';
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->execute();
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		} catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}

	/**
	 * @param $iID
	 * @return bool
	 */
	public function deleteRecord($iID)
	{
		try{
			$sql = 'DELETE FROM newjs.HOROSCOPE_FOR_SCREEN WHERE ID = :ID';
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(':ID',$iID,PDO::PARAM_INT);
			$pdoStatement->execute();
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		} catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}
}
?>
