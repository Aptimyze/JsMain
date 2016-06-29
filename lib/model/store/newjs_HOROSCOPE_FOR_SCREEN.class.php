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
			return true;
		} catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}
}
?>
