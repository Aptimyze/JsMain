<?php

class newjs_DELETED_PROFILE_CONTACTS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        public function getContactsCount($where, $group='',$time='',$skipProfile)
	{
		try{
			if(!$where)
				throw new jsException("","No where condition is specified in funcion getContactsCount OF newjs_CONTACTS.class.php");

			$sql = "SELECT COUNT(*) AS COUNT";
			if($group)
			{
				$sql = $sql.",".$group;
			}
			if($time)
				$sql = $sql.",CASE WHEN DATEDIFF(NOW( ) ,  `TIME`) <=90 THEN 0 ELSE 1 END AS TIME1 ";
			$sql = $sql." FROM newjs.DELETED_PROFILE_CONTACTS WHERE";
			if($where)
			{
				$count = 1;
				foreach($where as $key=>$value)
				{
					if(!is_array($value))
					{

						$sqlArr[] = " ".$key."=:VALUE".$count." ";
						$bindArr["VALUE".$count] = $value;
						$count++;
					}
					else
					{
						$str = " ".$key." IN(";

						foreach($value as $key1=>$value1)
						{
							$str = $str.":VALUE".$count.",";
							$bindArr["VALUE".$count] = $value1;
							$count++;
						}
						$str = substr($str, 0, -1);
						$str = $str.")";
						$sqlArr[] = $str;
					}
				}
				$sql = $sql.implode("AND",$sqlArr);

			}
			if($skipProfile)
			{
				$other = isset($where["RECEIVER"])?"SENDER":"RECEIVER";
				$str = " ".$other." NOT IN(";

				foreach($skipProfile as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$sql = $sql." AND ". $str;
			}
			if($group)
			{
				$sql = $sql." GROUP BY ".$group;
				if($time)
					$sql = $sql.",TIME1";
			}
			elseif($time)
			{
				$sql = $sql." GROUP BY TIME1";
			}
			$res=$this->db->prepare($sql);
			foreach($bindArr as $k=>$v)
				$res->bindValue($k,$v);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}


	public function getContactedProfiles($profileId,$senderReceiver,$type='',$cnt='')
	{
		try{
			if(!$profileId)
				throw new jsException("","No ProfileId is specified in funcion getContactedProfiles OF newjs_CONTACTS.class.php");
			$sql = "SELECT ";
			if($senderReceiver == "SENDER")
			{
				$sql = $sql."RECEIVER AS PROFILEID ";
			}
			else
			{
				$sql = $sql."SENDER AS PROFILEID ";
			}
			$sql = $sql.",TYPE ";
			$sql= $sql."FROM newjs.CONTACTS WHERE ".$senderReceiver." = :PROFILEID ";
			if($type)
			{
				$str = " AND TYPE IN(";
				$count = 1;
				foreach($type as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$sql = $sql.$str;
			}
			if($cnt)
				$sql.=" AND COUNT=:COUNT ";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			if($cnt)
				$res->bindValue(":COUNT",$cnt,PDO::PARAM_INT);
			if(is_array($bindArr))
				foreach($bindArr as $k=>$v)
					$res->bindValue($k,$v);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[$row["TYPE"]][] = $row["PROFILEID"];
			}

		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
	public function getContactedProfileArray($condition,$skipArray)
	{
		$string = array('TYPE','SEEN','FILTER','TIME');
		try{
			if(!$condition)
			{
				throw new jsException("","conditions are not specified in getContactedProfileArray() OF newjs_CONTACTS.class.php");
			}
			$count = 0;
			foreach($condition as $key=>$value)
			{
				if($key=="WHERE")
				{
					foreach($value as $key1=>$value1)
					{
						if($key1 == "NOT_IN")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$str = $str = $keyName." NOT IN(";
								if(!is_array($keyValue))
									$keyValue = array($keyValue);
								foreach($keyValue as $key2=>$value2)
								{
									$str = $str.":VALUE".$count.",";
									$bindArr["VALUE".$count]["VALUE"] = $value2;
									if(in_array($keyName,$string))
										$bindArr["VALUE".$count]["TYPE"] = "STRING";
									else
										$bindArr["VALUE".$count]["TYPE"] = "INT";
									$count++;
								}
								$str = substr($str, 0, -1);
								$str = $str.")";
								$keyValues =  implode(",",$keyValue);
								$arr[] = $str;
							}
						}
						if($key1 == "IN")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								if($keyName =="RECEIVER")
									$select = "SENDER";
								elseif($keyName == "SENDER")
									$select = "RECEIVER";
								$str = $str = $keyName." IN(";
								if(!is_array($keyValue))
									$keyValue = array($keyValue);
								foreach($keyValue as $key2=>$value2)
								{
									$str = $str.":VALUE".$count.",";
									$bindArr["VALUE".$count]["VALUE"] = $value2;
									if(in_array($keyName,$string))
										$bindArr["VALUE".$count]["TYPE"] = "STRING";
									else
										$bindArr["VALUE".$count]["TYPE"] = "INT";
									$count++;
								}
								$str = substr($str, 0, -1);
								$str = $str.")";
								$arr[] = $str;
							}
						}
						if($key1 == "GREATER_THAN_EQUAL")
						{
							foreach($value1 as $keyName=>$keyValue)
							{
								$arr[] = $keyName.">= :VALUE".$count;
								$bindArr["VALUE".$count]["VALUE"] = $keyValue;
								if(in_array($keyName,$string))
									$bindArr["VALUE".$count]["TYPE"] = "STRING";
								else
									$bindArr["VALUE".$count]["TYPE"] = "INT";
								$count++;
							}
						}
					}
					$where = "WHERE ".implode(" AND ",$arr);
				}
				if($key == "LIMIT")
				{
					$limit = " LIMIT ";
					$limit = $limit.$value;
				}
				if($key == "ORDER")
				{
					if($value)
					{
						$order = "ORDER BY ".$value." DESC";
					}
				}

			}

			if(is_array($skipArray))
			{
				if($select == "SENDER")
					$str = "SENDER NOT IN (";
				else
					$str = "RECEIVER NOT IN (";
				foreach($skipArray as $key=>$value)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count]["VALUE"] = $value;
					$bindArr["VALUE".$count]["TYPE"] = "INT";
					$count++;
				}
				$str = substr($str, 0, -1);
				$skipProfile = $str.")";
				if(!isset($where))
					$skipProfile = "WHERE ".$skipProfile;
				else
					$skipProfile = "AND ".$skipProfile;
			}
			$sql = "SELECT ".$select." as PROFILEID,TIME,COUNT,SEEN,FILTERED FROM newjs.CONTACTS ".$where." ".$skipProfile." ".$order." ".$limit;
			$res=$this->db->prepare($sql);
			if(is_array($bindArr))
				foreach($bindArr as $k=>$v)
				{
					if($v["TYPE"] =="STRING")
					{
						$res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
					}
					else
					{
						$res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
					}
				}

			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
				$output[$row["PROFILEID"]]["COUNT"] = $row["COUNT"];
				$output[$row["PROFILEID"]]["SEEN"] = $row["SEEN"];
				$output[$row["PROFILEID"]]["FILTERED"] = $row["FILTERED"];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}


	public function getContactsDetails($profileid,$profileArray)
	{
		if(!$profileid)
			throw new jsException("","profileid is not specified in getContactsDetails() OF newjs_CONTACTS.class.php");
		try{
			$count = 0;
			foreach($profileArray as $key=>$value)
			{
				$str = $str.":VALUE".$count.",";
				$bindArr["VALUE".$count]["VALUE"] = $value;
				$bindArr["VALUE".$count]["TYPE"] = "INT";
				$count++;
			}
			$str = substr($str, 0, -1);
			$sql = "SELECT * FROM newjs.CONTACTS WHERE (SENDER = :PROFILEID AND RECEIVER IN ($str)) OR (RECEIVER = :PROFILEID AND SENDER IN ($str))";
			$res=$this->db->prepare($sql);
			$res->bindValue("PROFILEID",$profileid,PDO::PARAM_INT);
			if(is_array($bindArr))
				foreach($bindArr as $k=>$v)
				{
					$res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
				}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
	public function getNoContactAutomationData($noOfOtherProfiles,$loggedIn,$others)
	{
		$otherKeys = array_keys($others);
		$otherStr = implode(",",$otherKeys);
		unset($otherKeys);
                $sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS WHERE (SENDER =:LOGGED_IN AND RECEIVER IN (".$otherStr."))";
                $res=$this->db->prepare($sql);
                $res->bindValue(":LOGGED_IN",$loggedIn,PDO::PARAM_STR);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$details[]=$row['RECEIVER'];
		}
		foreach($others as $k=>$v)
		{
			if(!in_array($v,$details))
				$othersNew[]=$v;
		}
		$otherKeys = array_keys($othersNew);
                $otherStr = implode(",",$otherKeys);
                unset($otherKeys);
		$sql = "SELECT SENDER,RECEIVER FROM newjs.CONTACTS WHERE (RECEIVER=:LOGGED_IN AND SENDER IN (".$otherStr."))";
                $res=$this->db->prepare($sql);
                $res->bindValue(":LOGGED_IN",$loggedIn,PDO::PARAM_STR);
                $res->execute();
		unset($details);
                while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$details[]=$row['SENDER'];
		}
		foreach($othersNew as $k=>$v)
		{
			if(count($othersFinal)>=$noOfOtherProfiles)
				break;
			if(!in_array($v,$details))
				$othersFinal=$v;
		}
		$finalData[$loggedIn]=$othersFinal;
                return $finalData;
	}
        public function getContactedAutomationData($loggedIn,$contactStatus,$noOfLoggedInProfiles,$noOfOtherProfiles,$senderReceiverProfiles)
        {
                $sql = "SELECT ".$loggedIn.",count(*) as count FROM newjs.CONTACTS WHERE ".$loggedIn." IN (".$senderReceiverProfiles.") AND TYPE = :CONTACTSTATUS GROUP BY ".$loggedIn." HAVING count>:NUMBER_OF_OTHER_PROFILES LIMIT :NO_OF_LOGGED_IN";
                $res=$this->db->prepare($sql);
                $res->bindValue(":CONTACTSTATUS",$contactStatus,PDO::PARAM_STR);
                $res->bindValue(":NUMBER_OF_OTHER_PROFILES",$noOfOtherProfiles,PDO::PARAM_INT);
                $res->bindValue(":NO_OF_LOGGED_IN",$noOfLoggedInProfiles*10,PDO::PARAM_INT);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
			$profiles[]=$row[$loggedIn];
		if(!is_array($profiles))
			return false;
		foreach($profiles as $k=>$v)
			$pa[] = ":PROFILEID".$k;
		$str = implode(",",$pa);
		unset($pa);
		$sqlA = "SELECT SENDER,RECEIVER  FROM newjs.CONTACTS WHERE ".$loggedIn." IN (".$str.") AND TYPE = :CONTACTSTATUS";
                $resA=$this->db->prepare($sqlA);
                $resA->bindValue(":CONTACTSTATUS",$contactStatus,PDO::PARAM_STR);
		foreach($profiles as $k=>$v)
			$resA->bindValue(":PROFILEID".$k,$v,PDO::PARAM_INT);
                $resA->execute();
                while($rowA = $resA->fetch(PDO::FETCH_ASSOC))
			$details[]=$rowA;
                return $details;
        }

	/** This store function is used to get SENDER or RECEIVER for a profile ID
         * @param $parameter : Parameters to be fetched or *
         * @param $whereArr : Array specifies whether SENDER or RECEIVER to be find out
         */
	public function contactResultInfo($parameter = "*", $whereArr) {
		try {
			if (!is_array($whereArr)) {
				throw new jsException("", "whereArr array Not Set in newjs_CONTACTS.class.php -> contactResultInfo");
			}

			if (isset($whereArr['SENDER'])) {
				$where = "SENDER = :PROFILEID";
				$profileId = $whereArr['SENDER'];
			} elseif (isset($whereArr['RECEIVER'])) {
				$where = "RECEIVER = :PROFILEID";
				$profileId = $whereArr['RECEIVER'];
			}
			$sql = "SELECT " . $parameter . " FROM newjs.CONTACTS WHERE " . $where;
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$prep->execute();
			while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
				$result[] = $row;
			}
			return $result;
		} catch (PDOException $e) {
			throw new jsException($e);
		}
	}

	public function countAcceptances($profileid, $start_dt, $end_dt)
	{
		try{
			$sql = "SELECT COUNT(*) AS CNT FROM newjs.`CONTACTS` WHERE (`SENDER`=:PROFILEID OR `RECEIVER`=:PROFILEID) AND `TYPE`='A' AND `TIME`>=:START_DT AND TIME<=:END_DT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
			$prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
			$prep->execute();
			$row = $prep->fetch(PDO::FETCH_ASSOC);
			$res = $row['CNT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $res;
	}

        public function countAcceptancesReceived($profileid, $start_dt, $end_dt)
        {
                try{
                        $sql = "SELECT COUNT(*) AS CNT FROM newjs.`CONTACTS` WHERE `SENDER`=:PROFILEID AND `TYPE`='A' AND `TIME`>=:START_DT AND TIME<=:END_DT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
                        $prep->execute();
                        $row = $prep->fetch(PDO::FETCH_ASSOC);
                        $res = $row['CNT'];
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }

        public function updateContactSeen($profileid,$type)
        {
        	try{
        		if($type == ContactHandler::INITIATED)
        			$SENDER_RECEIVER = "RECEIVER";
        		elseif($type == ContactHandler::ACCEPT)
        			$SENDER_RECEIVER = "SENDER";
        		$sql = "UPDATE newjs.`CONTACTS` SET SEEN='Y' WHERE ".$SENDER_RECEIVER." = :PROFILEID and TYPE = :TYPE";
        		$prep = $this->db->prepare($sql);
        		$prep->bindValue("PROFILEID",$profileid, PDO::PARAM_INT);
        		$prep->bindValue("TYPE",$type,PDO::PARAM_STR);
        		$prep->execute();
        	}
        	catch(Execption $e){
        		throw new jsException($e);
        	}
        }
			
}
?>
