<?php
class newjs_HOROSCOPE extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function getIfHoroscopePresent($pid)
	{
		$sql = "SELECT COUNT(*) as C FROM newjs.HOROSCOPE WHERE PROFILEID=:pid";
                $res=$this->db->prepare($sql);
		$res->bindValue(":pid", $pid, PDO::PARAM_INT);
		$res->execute();
        //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row["C"];
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
			$sql = "REPLACE INTO HOROSCOPE ($keys) VALUES ($values)";
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
	 * copyAllHoroscopFromScreen
	 * @return bool
	 */
	public function copyAllHoroscopFromScreen()
	{
		try{
			$sql = "REPLACE into newjs.HOROSCOPE (PROFILEID,HOROSCOPE) select PROFILEID,HOROSCOPE from newjs.HOROSCOPE_FOR_SCREEN where UPLOADED='Y'";
			$res = $this->db->prepare($sql);
			$res->execute();
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		} catch (PDOException $ex) {
			throw new jsException($e);
		}
	}

	/**
	 * @param $iID
	 * @return bool
	 */
	public function deleteRecord($iProfileID)
	{
		try{
			$sql = 'DELETE FROM newjs.HOROSCOPE WHERE PROFILEID = :PID';
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(':PID',$iProfileID,PDO::PARAM_INT);
			$pdoStatement->execute();
            //JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
			return true;
		} catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}
}
?>
