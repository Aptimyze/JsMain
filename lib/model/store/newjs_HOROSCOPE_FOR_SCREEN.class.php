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
			$sqlEditHobby = "REPLACE INTO HOROSCOPE_FOR_SCREEN ($keys) VALUES ($values)";
			$resEditHobby = $this->db->prepare($sqlEditHobby);
			foreach ($paramArr as $key => $val) $resEditHobby->bindValue(":" . $key, $val);
			$resEditHobby->bindValue(":PROFILEID", $pid);
			$resEditHobby->execute();
			return true;
		}
		catch(PDOException $e) {
			throw new jsException($e);
		}
	}
}
?>
