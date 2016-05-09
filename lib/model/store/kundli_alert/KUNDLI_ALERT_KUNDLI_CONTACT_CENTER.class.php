<?php
/*
This class is used to send queries to kundli_alert.KUNDLI_CONTACT_CENTER table
*/
class KUNDLI_ALERT_KUNDLI_CONTACT_CENTER extends TABLE
{
        public function __construct($dbname='')
        {
		$dbname= "newjs_slave";
                parent::__construct($dbname);
        }

	/*
	This function checks if a profile has any kundli matches
	@param - profileid
	@return - true if atleast 1 match exist else false
	*/
	public function matchKundliOfProfiles($profile,$match)
	{
			if(!$profile || !$match)
							throw new jsException("","PROFILEID IS BLANK IN KUNDLI_ALERT_KUNDLI_CONTACT_CENTER.class.php");
			try
			{
				$sql="REPLACE INTO kundli_alert.KUNDLI_CONTACT_CENTER (PROFILEID,MATCHID,MAIL_DT) VALUES(:PROFILEID,:MATCHID,now())";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILEID", $profile, PDO::PARAM_INT);
				$prep->bindValue(":MATCHID", $match, PDO::PARAM_INT);
				$prep->execute();
				return true;
			}
			catch(PDOException $e)
					{
							throw new jsException($e);
					}
		return false;

	}
	public function ifKundliMatchesExist($profileid)
	{
		if(JsConstants::$alertServerEnable &&  $this->db)
        {
			if(!$profileid)
							throw new jsException("","PROFILEID IS BLANK IN ifKundliMatchesExist() OF KUNDLI_ALERT_KUNDLI_CONTACT_CENTER.class.php");

			try
			{
				$sql = "SELECT COUNT(*) AS C FROM kundli_alert.KUNDLI_CONTACT_CENTER WHERE PROFILEID = :PROFILEID";
				$res = $this->db->prepare($sql);
							$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
							$res->execute();
				$row = $res->fetch(PDO::FETCH_ASSOC);
				if($row["C"]>0)
					return true;
			}
			catch(PDOException $e)
					{
							//throw new jsException($e);
				jsException::log("getKundli1-->.$sql".$e);
								   return;
					}
		}
		return false;
	}
	
	public function getKundliMatchProfiles($profileid,$skipProfile)
	{
		if (JsConstants::$alertServerEnable &&  $this->db) {
			try {
				$sql = "SELECT MATCHID AS RECEIVER,MAIL_DT AS TIME FROM kundli_alert.KUNDLI_CONTACT_CENTER WHERE PROFILEID = :RECEIVER";
				if ($skippedProfile) {
					$sql   = $sql . " AND USER NOT IN (";
					$count = 1;
					foreach ($skippedProfile as $key1 => $value1) {
						$str                       = $str . ":VALUE" . $count . ",";
						$bindArr["VALUE" . $count] = $value1;
						$count++;
					}
					$str = substr($str, 0, -1);
					$str = $str . ")";
					$sql = $sql . $str;
				}
				if ($limit)
					$sql = $sql . " ORDER BY MAIL_DT DESC, VENUS DESC , MARS DESC , GUNA DESC, ENTRY_DT DESC";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":RECEIVER", $profileId, PDO::PARAM_INT);
				if (isset($bindArr))
					foreach ($bindArr as $k => $v)
						$prep->bindValue($k, $v);
				$prep->execute();
				while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
					$result[] = $row;
				}
			}
			catch (PDOException $e) {
				//throw new jsException($e);
			jsException::log("getKundli2-->.$sql".$e);
                               return;
			}
		}
		return $result;
	}

	public function getKundliAlertProfiles($profileid)
	{
		if (JsConstants::$alertServerEnable &&  $this->db) {
			try {
				$sql = "SELECT MATCHID AS USER,UNIX_TIMESTAMP(MAIL_DT) AS DATE FROM kundli_alert.KUNDLI_CONTACT_CENTER WHERE PROFILEID = :RECEIVER";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":RECEIVER", $profileid, PDO::PARAM_INT);
				$prep->execute();
				while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
					$arr[$row["USER"]] = $row["DATE"];
				}
			}
			catch (PDOException $e) {
			jsException::log("getKundli3-->.$sql".$e);
return;
			//	throw new jsException($e);
			}
		}
		return $arr;
	}
}
?>
