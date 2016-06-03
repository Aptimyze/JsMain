<?php
class newjs_HOROSCOPE_REQUEST extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	  * This function returns an array of which all $senders have sent a horoscope request to $receiver.
	  * Pass $keyVal as 1 if the profileids are to be sent in the key of the returned array.
	**/

	public function getIfHoroscopeRequestSent($senders,$receiver,$keyVal='')
	{
		foreach($senders as $key=>$s)
		{
			if($key == 0)
				$str = ":SENDER".$key;
			else
				$str .= ",:SENDER".$key;
		}

		$sql = "SELECT PROFILEID FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID IN ($str) AND PROFILEID_REQUEST_BY=:RECEIVER";
                $res=$this->db->prepare($sql);
		foreach($senders as $key=>$sender)
		{
			$res->bindValue(":SENDER$key", $sender, PDO::PARAM_INT);
		}
		$res->bindValue(":RECEIVER", $receiver, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			if($keyVal == 1)
				$requestSenders[$row['PROFILEID']]=1;
			else
				$requestSenders[] = $row['PROFILEID'];
		}
		return $requestSenders;
	}
	/*
	* This function is used to get the horoscope request count
	* @param - profileid
	* @return - array of resultset rows
	*/
	
	public function getHoroscopeRequestCount($profileid,$skippedProfile='')
	{
		try
		{
			if(!$profileid)
				throw new jsException("","No profileid  is specified in funcion getHoroscopeRequestCount OF newjs_HOROSCOPE_REQUEST.class.php");
			$sql = "SELECT COUNT( * ) AS TOTAL_COUNT, SUM( CASE WHEN  `SEEN` !=  'Y' THEN 1 END ) AS UNSEEN FROM  `HOROSCOPE_REQUEST`  WHERE  `PROFILEID_REQUEST_BY` = :PROFILEID";
			if($skippedProfile)
			{
				$sql = $sql." AND PROFILEID NOT IN (";
				$count = 1;		
				foreach($skippedProfile as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$sql = $sql.$str;
			}
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			if(is_array($bindArr))
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

	/*
	* This function is used to get the horoscope request sent count
	* @param - profileid
	* @return - array of resultset rows
	*/
	
	public function getHoroscopeRequestSentCount($profileid,$skippedProfile='')
	{
		try
		{
			if(!$profileid)
				throw new jsException("","No profileid  is specified in funcion getHoroscopeRequestSentCount OF newjs_HOROSCOPE_REQUEST.class.php");
			$sql = "SELECT COUNT( * ) AS TOTAL_COUNT, SUM( CASE WHEN  `SEEN` !=  'Y' THEN 1 END ) AS UNSEEN FROM  `HOROSCOPE_REQUEST`  WHERE  `PROFILEID` = :PROFILEID";
			if($skippedProfile)
			{
				$sql = $sql." AND PROFILEID_REQUEST_BY NOT IN (";
				$count = 1;		
				foreach($skippedProfile as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$sql = $sql.$str;
			}
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			if(is_array($bindArr))
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

   public function getHoroscopeRequestProfileForCCDesktop($condition, $skipArray)
	{
		$string = array('UPLOAD_SEEN','SEEN','SEND_MAIL','DATE');
		try{
			if(!$condition)
			{
				throw new jsException("","conditions are not specified in getHoroscopeRequestProfileForCCDesktop() OF NEWJS_HOROSCOPE_REQUEST.class.php");
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
								if($keyName =="PROFILEID_REQUEST_BY")
									$select = "PROFILEID";
								elseif($keyName == "PROFILEID")
									$select = "PROFILEID_REQUEST_BY";
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
								$arr[] = $keyName."<= :VALUE".$count;
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
				if($key == "ORDER" && $value)
				{
					$order = "ORDER BY $value DESC";
				} 
					
			}
			if(is_array($skipArray))
			{
				if($select == "PROFILEID")
				$str = "PROFILEID NOT IN (";
				else
				$str = "PROFILEID_REQUEST_BY NOT IN (";
				
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
		$sql = "SELECT ".$select." as PROFILEID,DATE as TIME, SEEN FROM newjs.HOROSCOPE_REQUEST ".$where." ".$skipProfile." ".$order." ".$limit;
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
		}
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
        return $output;
	}
	
	public function getHoroscopeRequestProfile($condition,$skipArray)
	{
		$string = array('UPLOAD_SEEN','SEEN','SEND_MAIL','DATE');
		try{
			if(!$condition)
			{
				throw new jsException("","conditions are not specified in getHoroscopeRequesrProfile() OF newjs_HOROSCOPE_REQUEST.class.php");
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
								if($keyName =="PROFILEID_REQUEST_BY")
									$select = "PROFILEID";
								elseif($keyName == "PROFILEID")
									$select = "PROFILEID_REQUEST_BY";
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
								$arr[] = $keyName."<= :VALUE".$count;
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
					$order = "ORDER BY :VALUE".$count;
					$bindArr["VALUE".$count]["VALUE"] = $value;
					$bindArr["VALUE".$count]["TYPE"] = "STRING";
					$count++;
				} 
					
			}
			if(is_array($skipArray))
			{
				if($select == "PROFILEID_REQUEST_BY")
				$str = "PROFILEID NOT IN (";
				else
				$str = "PROFILEID_REQUEST_BY NOT IN (";
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
		$sql = "SELECT ".$select." as PROFILEID,DATE as TIME,SEEN FROM newjs.HOROSCOPE_REQUEST ".$where." ".$skipProfile." ".$order." ".$limit;
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
		}
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
        return $output;
	}
	
	
	public function getHoroscopeCommunication($viewer,$viewed)
	{
		if(!$viewer  || !$viewed)
			throw new jsException("","Problem in function getHoroscopeCommunication in newjs_HOROSCOPE_REQUEST.class.php");
		try{
			$sql = "select `DATE`,PROFILEID_REQUEST_BY,PROFILEID from `HOROSCOPE_REQUEST` where (PROFILEID_REQUEST_BY=:VIEWER AND PROFILEID=:VIEWED) OR (PROFILEID=:VIEWER AND PROFILEID_REQUEST_BY=:VIEWED) order by `DATE` ASC";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
			$res->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
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

public function insertRequest($param)
	{
		if(!$param && !is_array($param))
			throw new jsException("","param array not specified in insertOrUpdate() OF NEWJS_PHOTO_REQUEST.class.php");

		try
		{
			$sql = "INSERT INTO newjs.HOROSCOPE_REQUEST(PROFILEID,PROFILEID_REQUEST_BY,DATE,CNT) VALUES (:PROFILEID,:PROFILEID_REQUEST_BY,:DATE,:CNT)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$param["PROFILEID"],PDO::PARAM_INT);
			$res->bindValue(":PROFILEID_REQUEST_BY",$param["PROFILEID_REQUEST_BY"],PDO::PARAM_INT);
			$res->bindValue(":DATE",$param["DATE"],PDO::PARAM_INT);
			$res->bindValue(":CNT",1,PDO::PARAM_INT);
			$res->execute();
			$output = $res->rowCount();
		}
		catch(PDOException $e)
        	{
           		throw new jsException($e);
        	}
		return $output;
	}

	public function updateSeenStatusForAll($profileid) {

$sql="UPDATE newjs.HOROSCOPE_REQUEST SET SEEN='Y' WHERE PROFILEID_REQUEST_BY=:PROFILEID and SEEN!='Y'";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();


   }			
   /**
    * This function fetches the data of profiles to send email. Data to be fetched a day before
    * @param type $date a day before date 
    * @return array of receiver and 
    * @throws jsException
    */
   public function getHoroscopeForMails($date){
                try{
                        $sql = "select PROFILEID ,PROFILEID_REQUEST_BY,Count(*) as total_count from `HOROSCOPE_REQUEST` where SEND_MAIL != 'Y'";
                        if($date)
                                $sql = $sql." AND DATE BETWEEN :DATE1 AND :DATE2 ";

                        $sql .= 'Group By PROFILEID_REQUEST_BY';

                        $res = $this->db->prepare($sql);
                        if($date)
                        {
                                $res->bindValue(":DATE1", $date." 00:00:00", PDO::PARAM_STR);
                                $res->bindValue(":DATE2", $date." 23:59:59", PDO::PARAM_STR);
                        }
                        $res->execute();
                        $output = array();
                        $output = $row = $res->fetchAll(PDO::FETCH_ASSOC);
                        return $output;
                }catch (PDOException $e){
			jsException::log("Error in getting horoscope data");
                        return array();
		}
   }
}
?>
