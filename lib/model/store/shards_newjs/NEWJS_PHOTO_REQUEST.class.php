<?php
class NEWJS_PHOTO_REQUEST extends TABLE
{
	private $uploadedFlag='U';
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

//this function will update all the SEEN's as 'Y' for a particular receiver..... 
   public function updateSeenStatusForAll($profileid) {

$sql="UPDATE newjs.PHOTO_REQUEST SET SEEN='Y' WHERE PROFILEID_REQ_BY=:PROFILEID and SEEN!='Y'";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();


   }     

	public function updateUploadSeen($profileid)
	{
		$sql="UPDATE newjs.PHOTO_REQUEST SET UPLOAD_SEEN='$this->uploadedFlag',UPLOAD_DATE=now() WHERE PROFILEID_REQ_BY=:PROFILEID";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
    }

	/**
	  * This function returns an array of which all $senders have sent a photo request to $receiver.
	  * Pass $keyVal as 1 if the profileids are to be sent in the key of the returned array.
	**/
/*
	public function getIfPhotoRequestSent($senders,$receiver,$keyVal='')
	{
		foreach($senders as $key=>$s)
		{
			if($key == 0)
				$str = ":SENDER".$key;
			else
				$str .= ",:SENDER".$key;
		}

		$sql = "SELECT PROFILEID FROM newjs.PHOTO_REQUEST WHERE PROFILEID IN ($str) AND PROFILEID_REQ_BY=:RECEIVER";
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
*/
	public function getIfPhotoRequested($senders,$receivers)
	{
		try
		{
			if(is_array($senders) && is_array($receivers))
			{
				foreach($senders as $key=>$s)
				{
					if($key == 0)
						$senderStr = ":SENDER".$key;
					else
						$senderStr .= ",:SENDER".$key;
				}

				foreach($receivers as $key=>$r)
				{
					if($key == 0)
						$receiverStr = ":RECEIVER".$key;
					else
						$receiverStr .= ",:RECEIVER".$key;
				}

				$sql = "SELECT PROFILEID,PROFILEID_REQ_BY FROM newjs.PHOTO_REQUEST WHERE PROFILEID IN ($senderStr) AND PROFILEID_REQ_BY IN ($receiverStr)";
				$res=$this->db->prepare($sql);
				foreach($senders as $key=>$sender)
				{
					$res->bindValue(":SENDER$key", $sender, PDO::PARAM_INT);
				}
				foreach($receivers as $key=>$receiver)
				{
					$res->bindValue(":RECEIVER$key", $receiver, PDO::PARAM_INT);
				}
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$results[] = $row;
				}
				return $results;
			}
		}
		catch(PDOException $e)
		{
			jsException::nonCriticalError("shards_newjs/NEWJS_PHOTO_REQUEST.class.php(3)-->.$sql".$e);
			return '';
		}
	}

	public function getPhotoRequestReceived($profileid)
	{
		try{
			$sql = "SELECT count(*) as CNT FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY=:PROFILEID_REQ_BY";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID_REQ_BY",$profileid, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$count =$row['CNT'];
			return $count;
		}
		catch (Exception $e) {
			throw new jsException($e);
		}
        }

	/*
	This function gives the profiles having photo request on the basis of the shard passed.
	@param - activeServerId(0 or 1 or 2), date (optional, if the profiles are required on the basis of date, used in photo request mailers)
	@output - array of profileid's
	*/

	public function getProfilesHavingPhotoRequest($activeServerId,$date='')
	{
		if(!$activeServerId && $activeServerId!=0)
			throw new jsException("","ACTIVE SERVER ID IS BLANK IN getProfilesHavingPhotoRequest() OF NEWJS_PHOTO_REQUEST.class.php");

		try
		{
			if($date)
				$sql = "SELECT DISTINCT A.PROFILEID_REQ_BY AS PROFILEID_REQ_BY FROM newjs.PHOTO_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE DATE BETWEEN :DATE1 AND :DATE2 AND A.PROFILEID_REQ_BY = B.PROFILEID AND B.SERVERID=:ACTIVE_SERVER_ID";
			else
				$sql = "SELECT DISTINCT A.PROFILEID_REQ_BY AS PROFILEID_REQ_BY FROM newjs.PHOTO_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE A.PROFILEID_REQ_BY = B.PROFILEID AND B.SERVERID=:ACTIVE_SERVER_ID";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":ACTIVE_SERVER_ID", $activeServerId, PDO::PARAM_INT);
			if($date)
			{
				$res->bindValue(":DATE1", $date." 00:00:00", PDO::PARAM_STR);
				$res->bindValue(":DATE2", $date." 23:59:59", PDO::PARAM_STR);
			}
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row["PROFILEID_REQ_BY"];
			}
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $output;
	}

	/*
	This function gives the profiles which made photo request to a given profileid
	@param - profileid, optional parameter set to 1 if profiles need to be sorted on date desc
	@output - array of profileid's
	*/
	public function getProfilesWhichMadePhotoRequest($profileid,$photo_request_mailer="",$date='')
	{
		if(!$profileid)
			throw new jsException("","PROFILEID IS BLANK IN getProfilesWhichMadePhotoRequest() OF NEWJS_PHOTO_REQUEST.class.php");
			
		try
		{
			$sql = "SELECT PROFILEID FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY=:PROFILEID ";
			if($date)
				$sql = $sql." AND DATE BETWEEN :DATE1 AND :DATE2 ";
			if($photo_request_mailer)
				$sql = $sql." ORDER BY DATE DESC";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			if($date)
			{
				$res->bindValue(":DATE1", $date." 00:00:00", PDO::PARAM_STR);
				$res->bindValue(":DATE2", $date." 23:59:59", PDO::PARAM_STR);
			}
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
                                $output[] = $row["PROFILEID"];
                        }
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $output;
	}
	   /*
	* This function is used to get the photo request count
	* @param - profileid
	* @return - array of resultset rows
	*/

	public function getPhotoRequestCount($profileid,$skippedProfile='')
	{
		try
		{
			if(!$profileid)
				throw new jsException("","No profileid  is specified in funcion getContactsCount OF newjs_PHOTO_REQUEST.class.php");
			$sql = "SELECT COUNT( * ) AS TOTAL_COUNT, SUM( CASE WHEN  `SEEN` !=  'Y' THEN 1 END ) AS UNSEEN FROM  `PHOTO_REQUEST`  WHERE  `PROFILEID_REQ_BY` = :PROFILEID";
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
			if(isset($bindArr))
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
	* This function is used to get the sent photo request count
	* @param - profileid
	* @return - array of resultset rows
	*/

	public function getPhotoRequestSentCount($profileid,$skippedProfile='')
	{
		try
		{
			if(!$profileid)
				throw new jsException("","No profileid  is specified in funcion getPhotoRequestSentCount OF newjs_PHOTO_REQUEST.class.php");
			$sql = "SELECT COUNT( * ) AS TOTAL_COUNT, SUM( CASE WHEN  `SEEN` !=  'Y' THEN 1 END ) AS UNSEEN FROM  `PHOTO_REQUEST`  WHERE  `PROFILEID` = :PROFILEID";
			if($skippedProfile)
			{
				$sql = $sql." AND PROFILEID_REQ_BY NOT IN (";
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
			if(isset($bindArr))
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

	public function getPhotoRequestProfile($condition, $skipArray)
	{
		$string = array('UPLOAD_SEEN','SEEN','SEND_MAIL','DATE');
		try{
			if(!$condition)
			{
				throw new jsException("","conditions are not specified in getPhotoRequesrProfile() OF NEWJS_PHOTO_REQUEST.class.php");
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
								if($keyName =="PROFILEID_REQ_BY")
									$select = "PROFILEID";
								elseif($keyName == "PROFILEID")
									$select = "PROFILEID_REQ_BY";
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
				$str = "PROFILEID_REQ_BY NOT IN (";
				
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
		$sql = "SELECT ".$select." as PROFILEID,DATE as TIME, SEEN FROM newjs.PHOTO_REQUEST ".$where." ".$skipProfile." ".$order." ".$limit;
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
	
	/*
	This function performs insert or update on newjs.PHOTO_REQUEST table on shards
	@param - array where index has PROFILEID AND PROFILEID_REQ_BY and colums has their values
	@return - 1(insert), 2(update), 0(no action)
	*/
	public function insertOrUpdate($param)
	{
		if(!$param && !is_array($param))
			throw new jsException("","param array not specified in insertOrUpdate() OF NEWJS_PHOTO_REQUEST.class.php");

		try
		{
			$sql = "INSERT INTO newjs.PHOTO_REQUEST(PROFILEID,PROFILEID_REQ_BY,DATE,CNT) VALUES (:PROFILEID,:PROFILEID_REQ_BY,:DATE,:CNT) ON DUPLICATE KEY UPDATE DATE = IF(CNT<:CNT1,:DATE,DATE), CNT = IF(CNT<:CNT2,CNT+1,CNT)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$param["PROFILEID"],PDO::PARAM_INT);
			$res->bindValue(":PROFILEID_REQ_BY",$param["PROFILEID_REQ_BY"],PDO::PARAM_INT);
			$res->bindValue(":CNT",1,PDO::PARAM_INT);
			$res->bindValue(":CNT1",2,PDO::PARAM_INT);
			$res->bindValue(":CNT2",2,PDO::PARAM_INT);
			$res->bindValue(":DATE",date("Y-m-d H:i:s"),PDO::PARAM_STR);
			$res->execute();
			$output = $res->rowCount();
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
			$sql = "INSERT INTO newjs.PHOTO_REQUEST(PROFILEID,PROFILEID_REQ_BY,DATE,CNT) VALUES (:PROFILEID,:PROFILEID_REQ_BY,:DATE,:CNT)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$param["PROFILEID"],PDO::PARAM_INT);
			$res->bindValue(":PROFILEID_REQ_BY",$param["PROFILEID_REQ_BY"],PDO::PARAM_INT);
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
	
	
	public function getPhotoRequestCommunication($viewer,$viewed)
	{
		if(!$viewer  || !$viewed)
			throw new jsException("","Problem in function getPhotoRequestCommunication in newjs_PHOTO_REQUEST.class.php");
		try{
			$sql = "select `DATE`,PROFILEID_REQ_BY,PROFILEID from `PHOTO_REQUEST` where (PROFILEID_REQ_BY=:VIEWER AND PROFILEID=:VIEWED) OR (PROFILEID =:VIEWER AND PROFILEID_REQ_BY=:VIEWED) order by `DATE` ASC";
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
}
?>
