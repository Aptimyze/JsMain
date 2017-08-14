<?php
class NEWJS_MESSAGE_LOG extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			if(strpos($dbname,'master')!==false && JsConstants::$communicationRep)
				$dbname=$dbname."Rep";
			parent::__construct($dbname);
        }
		
		
		
		public function updateMessageLog($msgCommObj)
        {
			try 
			{
				if($msgCommObj->getID())
				{ 
					$sql="INSERT INTO MESSAGE_LOG (ID,SENDER,RECEIVER,DATE,IP,IS_MSG,OBSCENE,MSG_OBS_ID,TYPE, SENDER_STATUS, RECEIVER_STATUS, SEEN, FOLDERID) VALUES (:GENERATEDID,:VIEWERID,:VIEWEDID,:DATE,:IP,:ISMSG,:OBSCENE,:IDOBSCENE,:TYPE,:SENDER_STATUS, :RECEIVER_STATUS, :SEEN, :FOLDERID)  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$msgCommObj->getID(),PDO::PARAM_INT);
					$prep->bindValue(":VIEWERID",$msgCommObj->getSENDER(),PDO::PARAM_INT);
					$prep->bindValue(":VIEWEDID",$msgCommObj->getRECEIVER(),PDO::PARAM_INT);
					$prep->bindValue(":DATE",$msgCommObj->getDATE(),PDO::PARAM_STR);
					$prep->bindValue(":IP",$msgCommObj->getIP(),PDO::PARAM_STR);
					$prep->bindValue(":ISMSG",$msgCommObj->getIS_MSG(),PDO::PARAM_STR);
					$prep->bindValue(":OBSCENE",$msgCommObj->getOBSCENE(),PDO::PARAM_STR);
					$prep->bindValue(":IDOBSCENE",$msgCommObj->getOBS_MSG_ID(),PDO::PARAM_INT);
					$prep->bindValue(":TYPE",$msgCommObj->getTYPE(),PDO::PARAM_STR);
					$prep->bindValue(":SEEN",$msgCommObj->getSEEN(),PDO::PARAM_STR);
					$prep->bindValue(":SENDER_STATUS",$msgCommObj->getSENDER_STATUS(),PDO::PARAM_STR);
					$prep->bindValue(":RECEIVER_STATUS",$msgCommObj->getRECEIVER_STATUS(),PDO::PARAM_STR);
					$prep->bindValue(":FOLDERID",$msgCommObj->getFOLDERID(),PDO::PARAM_INT);
					$prep->execute();
					
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		function getInitiatedContact($pid)
		{
			try 
			{
				if($pid)
				{ 
					$sql="SELECT RECEIVER, DATEDIFF(now(),DATE) as TIME FROM newjs.MESSAGE_LOG WHERE SENDER=:PID and TYPE ='I' order by DATE DESC ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					
					return $res;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
			
		}
		
		public function EOIMessageLog($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT SENDER,RECEIVER,CONVERT_TZ(DATE,'SYSTEM','right/Asia/Calcutta') as DATE,TYPE,IP as IP,ID FROM newjs.MESSAGE_LOG WHERE SENDER = :PROFILEID OR RECEIVER = :PROFILEID and TYPE IN ('A','I','D') ORDER by DATE ASC ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[]= $result;
					}
					
					return $res;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		public function getMessagesBasedOnType($pid,$type)
		{
			try{
				$sql="select RECEIVER, DATEDIFF(now(),`DATE`) as `TIME` from newjs.MESSAGE_LOG where SENDER=:PROFILEID and `TYPE`=:TYPE order by `DATE` DESC";
				 $prep=$this->db->prepare($sql);
                                 $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                                 $prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
	     			 $prep->execute();
				 while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]= $result;
				}

				return $res;
			}
			catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
		}
  
    /**
     * 
     * @param type $pid
     * @return type
     * @throws jsException
     */
    public function MessageLogAndDeletedLog($pid)
    {
      try {
        if ($pid) {
          
          $archiveSuffix = HouseKeepingEnum::DELETE_ARCHIVE_TABLE_SUFFIX;
          $archivePrefix = HouseKeepingEnum::DELETE_ARCHIVE_TABLE_PREFIX;
          
          $archiveTableSql = " UNION SELECT SENDER,RECEIVER,CONVERT_TZ(DATE,'EST','right/Asia/Calcutta') as DATE,TYPE,IP as IP,ID FROM newjs.{$archivePrefix}DELETED_MESSAGE_LOG{$archiveSuffix} WHERE SENDER = :PROFILEID OR RECEIVER = :PROFILEID";
          
          $sql =  <<<SQL
          SELECT SENDER,RECEIVER,CONVERT_TZ(DATE,'SYSTEM','right/Asia/Calcutta') as DATE,TYPE,IP as IP,ID 
          FROM newjs.MESSAGE_LOG 
          WHERE SENDER = :PROFILEID OR RECEIVER = :PROFILEID  
          UNION 
          SELECT SENDER,RECEIVER,CONVERT_TZ(DATE,'EST','right/Asia/Calcutta') as DATE,TYPE,IP as IP,ID 
          FROM newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET 
          WHERE SENDER = :PROFILEID OR RECEIVER = :PROFILEID 
          UNION 
          SELECT SENDER,RECEIVER,CONVERT_TZ(DATE,'EST','right/Asia/Calcutta') as DATE,TYPE,IP as IP,ID 
          FROM newjs.DELETED_MESSAGE_LOG 
          WHERE SENDER = :PROFILEID OR RECEIVER = :PROFILEID
          {$archiveTableSql} 
          ORDER by DATE ASC
SQL;
          $prep = $this->db->prepare($sql);
          $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
          $prep->execute();
          while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $result;
          }

          return $res;
        }
      } catch (PDOException $e) {
        /*       * * echo the sql statement and error message ** */
        throw new jsException($e);
      }
    }

  public function getMessageLogProfile($condition,$skipArray)
		{
			$string = array('TYPE','SEEN','IS_MSG','DATE');
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
		$sql = "SELECT ".$select." as PROFILEID,DATE as TIME,SEEN FROM newjs.MESSAGE_LOG ".$where." ".$skipProfile." ".$order." ".$limit;
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
			$output[$row["PROFILEID"]]["SEEN"] = $row["SEEN"]=='Y'?$row["SEEN"]:'N';
		}
		}
		catch(PDOException $e)
        {
           throw new jsException($e);
        }
        return $output;
	}

		public function getMessageLogCount($where,$group='',$select='',$skippedProfile='',$considerProfile='')
		{
			try{
				if(!$where)
					throw new jsException("","No where condition is specified in funcion getMessageLogCount OF newjs_MESSAGE_LOG.class.php");
				$sql = "SELECT";
				if($select)
					$sql = $sql." ".$select;
				else
					$sql = $sql." COUNT(*) as COUNT";
				if($group)
					$sql = $sql.",".$group;
				$sql = $sql." FROM newjs.MESSAGE_LOG WHERE";
				$count = 1;
				
				if($where)
				{
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
			if($skippedProfile)
			{
				$sql = $sql." AND SENDER NOT IN (";
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
			if($considerProfile)
			{
				$sql.=" AND SENDER IN (";
				foreach($considerProfile as $key1=>$value1)
                                {
                                        $str = $str.":VALUE".$count.",";
                                        $bindArr["VALUE".$count] = $value1;
                                        $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $sql = $sql.$str;
			}
			if($group)
			{
				$sql = $sql." GROUP BY ".$group;				
			}
			$sql = $sql." ORDER BY DATE DESC ";
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
                public function getMaxId()
                {
                        try
                        {
                                $sql="SELECT MAX(ID) as Id FROM newjs.MESSAGE_LOG";
                                $prep=$this->db->prepare($sql);
                                $prep->execute();
                                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $maxId=$result["Id"];
                                }

                                return $maxId;
                        }
                        catch(PDOException $e)
                        {
                                        /*** echo the sql statement and error message ***/
                                        throw new jsException($e);
                        }
                }
                public function getProfilesSentEoiAfter($lastId,$maxId)
                {
                        try
                        {
                                $sql="SELECT SENDER,MIN(DATE) AS EOI_DATE FROM newjs.MESSAGE_LOG WHERE ID>:LAST_ID AND ID<=:MAX_ID AND TYPE=:TYPE GROUP BY SENDER ORDER BY ID";
                                $prep=$this->db->prepare($sql);
                                $prep->bindValue(":LAST_ID",$lastId,PDO::PARAM_INT);
                                $prep->bindValue(":MAX_ID",$maxId,PDO::PARAM_INT);
                                $prep->bindValue(":TYPE",'I',PDO::PARAM_INT);
                                $prep->execute();
                                $i=0;
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                                $res[$i]["PROFILEID"]= $result["SENDER"];
                                                $res[$i]["DATE"]=$result["EOI_DATE"];
                                                $i++;
                                }

                                return $res;
                        }
                        catch(PDOException $e)
                        {
                                        /*** echo the sql statement and error message ***/
                                        throw new jsException($e);
                        }
        }       
        public function hasSentEoiBefore($lastId,$profileid)
                {
                        try
                        {
                                                $sql="SELECT COUNT(*) AS CNT FROM newjs.MESSAGE_LOG WHERE ID <= :LAST_ID AND TYPE=:TYPE AND SENDER=:SENDER GROUP BY SENDER";
                                                $prep=$this->db->prepare($sql);
                                                $prep->bindValue(":LAST_ID",$lastId,PDO::PARAM_INT);
                                                $prep->bindValue(":SENDER",$profileid,PDO::PARAM_INT);
                                                $prep->bindValue(":TYPE",'I',PDO::PARAM_STR);
                                                $prep->execute();
                                                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                                {
                                                                if($result['CNT']>0)
                                                                        return 1;
                                                                else
                                                                        return 0;
                                                }
                                                else
                                                        return 0;
                        }
                        catch(PDOException $e)
                        {
                                        /*** echo the sql statement and error message ***/
                                        throw new jsException($e);
                        }
        }
		
		public function getCustomMessageLogCount($where,$group='',$select='',$skippedProfile='')
		{
			try{
				if(!$where)
					throw new jsException("","No where condition is specified in funcion getMessageLogCount OF newjs_MESSAGE_LOG.class.php");
				$sql = "SELECT";
				if($select)
					$sql = $sql." ".$select;
				else
					$sql = $sql." DISTINCT(SENDER) as SENDER";
				if($group)
					$sql = $sql.",".$group;
				$sql = $sql." FROM newjs.MESSAGE_LOG WHERE";
				$count = 1;
				
				if($where)
				{
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
			if($skippedProfile)
			{
				$sql = $sql." AND SENDER NOT IN (";
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
			
			if($group)
			{
				$sql = $sql." GROUP BY ".$group;				
			}
			$sql = $sql." ORDER BY SEEN DESC";
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
		function getMessageLogDetails($id)
		{
			try{
				if(!$id)
					throw new jsException("","No ID is specified in funcion getMessageLogDetails OF newjs_MESSAGE_LOG.class.php");
				$sql = "SELECT * FROM newjs.MESSAGE_LOG where ID = :ID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":ID",$id,PDO::PARAM_INT);
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res= $result;
				}
				
				return $res;
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
public function updateMessageLogDetails($msgCommObj)
        {
			try 
			{
				if($msgCommObj->getID()) 
				{ 
					$sql="UPDATE newjs.MESSAGE_LOG SET IS_MSG = :IS_MSG,OBSCENE = :OBSCENE,MSG_OBS_ID = :IDOBSCENE WHERE ID = :ID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":ID",$msgCommObj->getID(),PDO::PARAM_INT);
					$prep->bindValue(":IS_MSG",$msgCommObj->getIS_MSG(),PDO::PARAM_STR);
					$prep->bindValue(":OBSCENE",$msgCommObj->getOBSCENE(),PDO::PARAM_STR);
					$prep->bindValue(":IDOBSCENE",$msgCommObj->getOBS_MSG_ID(),PDO::PARAM_INT);
					$prep->execute();
					
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function getMessageHistory($viewer,$viewed)
		{
			try
			{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql = "SELECT SENDER, DATE, MESSAGE FROM  `MESSAGE_LOG` JOIN MESSAGES ON ( MESSAGES.ID = MESSAGE_LOG.ID ) WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ) OR (`RECEIVER` =:VIEWED AND SENDER =:VIEWER ))AND IS_MSG =  'Y' AND TYPE IN ('R','I') ORDER BY DATE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
		
		public function getEOIMessages($receiverProfiles,$senderProfiles)
		{
			try{
				if(!is_array($receiverProfiles) && !is_array($senderProfiles))
				{
					throw new jsException("","profile id is not specified in function getEOIMessages of newjs_MESSAGE_LOG.class.php");
				}
				$count = 1;
				$str = " IN (";
				foreach($senderProfiles as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				foreach($receiverProfiles as $key1=>$value1)
				{
					$str = $str.":VALUE".$count.",";
					$bindArr["VALUE".$count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				$str = $str.")";
				$strS = " RECEIVER ".$str." AND SENDER ".$str;
				$sql = "SELECT SQL_CACHE SENDER,RECEIVER,DATE,MESSAGE,MESSAGES.ID FROM  newjs.`MESSAGE_LOG` JOIN MESSAGES ON ( MESSAGES.ID = MESSAGE_LOG.ID ) WHERE ".$strS." AND IS_MSG='Y' AND TYPE = 'I' ORDER BY SENDER,DATE ASC";
				$prep=$this->db->prepare($sql);
				foreach($bindArr as $k=>$v)
					$prep->bindValue($k,$v);
				$prep->execute();
				
				while($row = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$output[] = $row;
				}
					//print_r($output); die;
				
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}

	public function getEOIMessagesForChat($receiverProfiles,$senderProfiles)
	{
		try{
			if(!is_array($receiverProfiles) && !is_array($senderProfiles))
			{
				throw new jsException("","profile id is not specified in function getEOIMessages of newjs_MESSAGE_LOG.class.php");
			}
			$count = 1;
			$str = " IN (";
			foreach($senderProfiles as $key1=>$value1)
			{
				$str = $str.":VALUE".$count.",";
				$bindArr["VALUE".$count] = $value1;
				$count++;
			}
			foreach($receiverProfiles as $key1=>$value1)
			{
				$str = $str.":VALUE".$count.",";
				$bindArr["VALUE".$count] = $value1;
				$count++;
			}
			$str = substr($str, 0, -1);
			$str = $str.")";
			$strS = " RECEIVER ".$str." AND SENDER ".$str;
			$sql = "SELECT SQL_CACHE SENDER,RECEIVER,DATE, MESSAGE,MESSAGE_LOG.ID FROM  newjs.`MESSAGE_LOG` LEFT JOIN MESSAGES ON ( MESSAGES.ID = MESSAGE_LOG.ID ) WHERE ".$strS."  AND TYPE = 'I' ORDER BY SENDER,DATE ASC LIMIT 1";
			$prep=$this->db->prepare($sql);
			foreach($bindArr as $k=>$v)
				$prep->bindValue($k,$v);
			$prep->execute();

			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
			//print_r($output); die;

		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
		
		public function getMessageListing($condition,$skipArray='',$inArray='')
		{
			try{
				if(!$condition["WHERE"]["IN"]["PROFILE"])
				{
					throw new jsException("","profile id is not specified in function getMessageListing of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					if(count($skipArray)<1000 && count($skipArray)>0)
						$skipSql=1;
					else
						$skipSql=0;
					if($skipSql)
					{
						$count = 0;
						$str = "NOT IN (";
						foreach($skipArray as $key1=>$value1)
						{
							$str = $str.":VALUE".$count.",";
							$bindArr["VALUE".$count] = $value1;
							$count++;
						}
						$str = substr($str, 0, -1);
						$str = $str.")";
						if($skipArray)
						{
							$sender = " AND SENDER ".$str." ";
							$receiver = "AND RECEIVER ".$str." ";
						}
					}
					if(count($inArray)<1000 && count($inArray)>0)
						$inSql = 1;
					else
						$inSql = 0;
					if($inSql)
					{
						
                                                $str =  "  IN (";
							$count = 0;
                                                foreach($inArray as $key1=>$value1)
                                                {
                                                        $str = $str.":VALUE".$count.",";
                                                        $bindInArr["VALUE".$count] = $value1;
                                                        $count++;
                                                }
                                                $str = substr($str, 0, -1);
                                                $str = $str.")";
						if(is_array($inArray))
						{
							$sender1 = " AND SENDER ".$str." ";
							$receiver1 = "AND RECEIVER ".$str." ";
						}
					}
					$sql = "SELECT SQL_CACHE SENDER AS PROFILEID, MESSAGE,  'R' AS SR,SEEN,DATE FROM  `MESSAGE_LOG` USE INDEX (RECEIVER) JOIN MESSAGES ON ( MESSAGE_LOG.ID = MESSAGES.ID ) WHERE  `RECEIVER` =:PROFILEID";
					if($sender)
						$sql.= $sender;
					if($sender1)
						$sql.=$sender1;

					$sql.=" AND  `TYPE` in ('R','I') AND  `IS_MSG` ='Y' UNION ALL SELECT  RECEIVER AS PROFILEID, MESSAGE,  'S' AS SR,SEEN,DATE FROM  `MESSAGE_LOG` USE INDEX (SENDER) JOIN MESSAGES ON ( MESSAGE_LOG.ID = MESSAGES.ID ) WHERE  `SENDER` =:PROFILEID ";
					if($receiver)
						$sql.=$receiver;
					if($receiver1)
						$sql.=$receiver1;
					$sql.=" AND  `TYPE` in ('R','I') AND  `IS_MSG` ='Y' ORDER BY DATE DESC";
					$res=$this->db->prepare($sql);
					$res->bindValue(":PROFILEID",$condition["WHERE"]["IN"]["PROFILE"],PDO::PARAM_INT);
					
					if($skipSql){
						foreach($bindArr as $k=>$v)
						{	
							$res->bindValue($k,$v,PDO::PARAM_INT);
						}
					}
					if($inSql){
						foreach($bindInArr as $k=>$v)
						{	
							$res->bindValue($k,$v,PDO::PARAM_INT);
						}
					}
					$res->execute();
					if(!$skipSql)
					{
						while($row = $res->fetch(PDO::FETCH_ASSOC))
						{
							if(!in_array($row["PROFILEID"],$skipArray))
								$output[$row["PROFILEID"]][] = $row;
						}
					}
					else if(!$inSql && is_array($inArray) && count($inArray)>0)
					{
						 while($row = $res->fetch(PDO::FETCH_ASSOC))
						{
							if(in_array($row["PROFILEID"],$inArray))
								$output[$row["PROFILEID"]][] = $row;
						}
					}
					else
					{
						while($row = $res->fetch(PDO::FETCH_ASSOC))
						{
								$output[$row["PROFILEID"]][] = $row;
						}
					}
					
					 
					if(array_key_exists("LIMIT",$condition))
							$output= array_slice($output,0,$condition["LIMIT"],true);
					
					
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
		
		public function markMessageSeen($viewer,$viewed)
		{
			try{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql = "UPDATE newjs.MESSAGE_LOG SET `SEEN`='Y' WHERE SENDER = :VIEWED AND RECEIVER = :VIEWER AND TYPE = 'R' AND IS_MSG = 'Y'";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					$prep->execute();
					$count = $prep->rowCount();
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $count;
		}
		
		public function alterMessageSeen($viewer,$viewed)
		{
			try{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql = "UPDATE newjs.MESSAGE_LOG SET `SEEN`='Y' WHERE SENDER = :VIEWED AND RECEIVER = :VIEWER ";
					$res=$this->db->prepare($sql);
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					$prep->execute();
					$count = $prep->rowCount();
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $count;
		}
		
		public function getCommunicationHistory($viewer,$viewed)
		{	
			try
			{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql = "SELECT MESSAGE_LOG.ID as ID, SENDER,TYPE,`DATE`,OBSCENE,MESSAGE,RECEIVER FROM  `MESSAGE_LOG` LEFT JOIN MESSAGES ON ( MESSAGES.ID = MESSAGE_LOG.ID ) WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ) OR (`RECEIVER` =:VIEWED AND SENDER =:VIEWER ))  ORDER BY DATE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
		
		public function getPaidMemberCommunicationHistory($viewer,$viewed)
		{	
			try
			{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql = "SELECT MESSAGE_LOG.ID as ID, SENDER,TYPE,`DATE`,OBSCENE,MESSAGE FROM  `MESSAGE_LOG` LEFT JOIN MESSAGES ON ( MESSAGES.ID = MESSAGE_LOG.ID ) WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ))  AND TYPE ='R' ORDER BY DATE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
	
                public function getFirstAcceptanceCount($pid)
        	{
                        try
                        {
                                if($pid)
                                {
                                        $sql="SELECT count(*) CNT from newjs.MESSAGE_LOG WHERE (SENDER=:PROFILEID OR RECEIVER=:PROFILEID) AND TYPE='A'";
                                        $prep=$this->db->prepare($sql);
                                        $prep->bindValue(":PROFILEID",$pid, PDO::PARAM_INT);
                                        $prep->execute();
                                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                                $count = $result['CNT'];
                                        return $count;
                                }
                        }
                        catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
                }
                public function getFirstEoiCount($pid)
                {
                        try
                        {
                                if($pid)
                                {
                                        $sql="SELECT count(*) CNT from newjs.MESSAGE_LOG WHERE SENDER=:PROFILEID AND TYPE='I'";
                                        $prep=$this->db->prepare($sql);
                                        $prep->bindValue(":PROFILEID",$pid, PDO::PARAM_INT);
                                        $prep->execute();
                                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                                $count = $result['CNT'];
                                        return $count;
                                }
                        }
                        catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
                }

		public function getMessageLogOfProfiles($profileid1,$profileid2)
		{
			if(!$profileid1||!$profileid2)
				throw new jsException("","profileid is not specified in getContactsDetails() OF newjs_MESSAGE_LOG.class.php");
			try{
				$sql = "SELECT * FROM newjs.MESSAGE_LOG WHERE (SENDER = :PROFILEID1 AND RECEIVER = :PROFILEID2) OR (RECEIVER = :PROFILEID1 AND SENDER =:PROFILEID2) ORDER BY DATE DESC LIMIT 1";
				$res=$this->db->prepare($sql);
				$res->bindValue("PROFILEID1",$profileid1,PDO::PARAM_INT);
				$res->bindValue("PROFILEID2",$profileid2,PDO::PARAM_INT);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$output = $row;
				}
			}
			catch(PDOException $e)
			{
			   throw new jsException($e);
			}
			return $output;
		}
		public function makeAllMessagesSeen($profileid)
		{
			try{
				$sql = "UPDATE newjs.MESSAGE_LOG SET SEEN = 'Y' WHERE RECEIVER = :PROFILEID AND IS_MSG = 'Y' AND TYPE = 'R'";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->execute();
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		public function getMessageReceivedListing($condition,$skipArray)
		{
			try{
				if(!$condition["WHERE"]["IN"]["PROFILE"])
				{
					throw new jsException("","profile id is not specified in function getMessageListing of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					if(count($skipArray)<1000 && count($skipArray)>0)
						$skipSql=1;
					else
						$skipSql=0;
					if($skipSql)
					{
						$count = 0;
						$str = "NOT IN (";
						foreach($skipArray as $key1=>$value1)
						{
							$str = $str.":VALUE".$count.",";
							$bindArr["VALUE".$count] = $value1;
							$count++;
						}
						$str = substr($str, 0, -1);
						$str = $str.")";
						$sender = " AND SENDER ".$str." ";
						$receiver = "AND RECEIVER ".$str." ";
					}
					$sql = "SELECT SENDER AS PROFILEID, MESSAGE,  'R' AS SR,SEEN,DATE FROM  `MESSAGE_LOG` USE INDEX (RECEIVER) JOIN MESSAGES ON ( MESSAGE_LOG.ID = MESSAGES.ID ) WHERE  `RECEIVER` =:PROFILEID".$sender." AND  `TYPE` ='R' AND  `IS_MSG` ='Y' ORDER BY DATE DESC";
					$res=$this->db->prepare($sql);
					$res->bindValue(":PROFILEID",$condition["WHERE"]["IN"]["PROFILE"],PDO::PARAM_INT);
					
					if($skipSql){
						foreach($bindArr as $k=>$v)
						{	
							$res->bindValue($k,$v,PDO::PARAM_INT);
						}
					}
					$res->execute();
					if(!$skipSql)
					{
						while($row = $res->fetch(PDO::FETCH_ASSOC))
						{
							if(!in_array($row["PROFILEID"],$skipArray))
								$output[$row["PROFILEID"]][] = $row;
						}
					}
					else
					{
						while($row = $res->fetch(PDO::FETCH_ASSOC))
						{
								$output[$row["PROFILEID"]][] = $row;
						}
					}
						if(in_array("LIMIT",$condition))
							$output= array_slice($output,0,$condition["LIMIT"]);
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}


 public function getContactsBasedOnTimeInterval($contactType,$currentTimeObj='',$dateIntervalObj)
 {
             
		if(!$contactType || !$dateIntervalObj )
			throw new jsException("","CONTACT-TYPE IS BLANK IN getContactsCountTotal() OF newjs_CONTACTS.class.php");

		try
		{
			
			if($currentTimeObj) 
				$dtObject=$currentTimeObj;
			else	
				$dtObject=new DateTime();
            $dateEnd = $dtObject->format('Y-m-d H:i:s');
            $dtObject->sub($dateIntervalObj);
            $dateStart=$dtObject->format('Y-m-d H:i:s');
			$sql = "SELECT `DATE`,`RECEIVER` FROM newjs.MESSAGE_LOG WHERE TYPE=:CONTACTTYPE AND `DATE` BETWEEN :DATESTART AND :ENDDATE";
			$res=$this->db->prepare($sql);
			$res->bindValue(":CONTACTTYPE",$contactType,PDO::PARAM_STR);
			$res->bindValue(":DATESTART",$dateStart,PDO::PARAM_STR);
			$res->bindValue(":ENDDATE",$dateEnd,PDO::PARAM_STR);
			$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[]=$row;
			}
return $result;

		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }

}

	public function getInterestRecievedInLastWeek($profileid)
	{
		try 
		{
			if($profileid)
			{ 
				$startDt = date("Y-m-d H:i:s", (time()-7*24*60*60));
				$sql="SELECT SQL_CACHE COUNT(1) AS CNT FROM newjs.MESSAGE_LOG WHERE RECEIVER=:PROFILEID and TYPE ='I' AND DATE>=:START_DT";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->bindValue(":START_DT",$startDt,PDO::PARAM_STR);
				$prep->execute();
				if ($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res = $result['CNT'];
				}
				
				return $res;
			}	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
		
	}

	//Three function for innodb transactions
	public function startTransaction()
	{
		$this->db->beginTransaction();
	}
	public function commitTransaction()
	{
		$this->db->commit();
	}

	public function rollbackTransaction()
	{
		$this->db->rollback();
	}

	public function getAllMessageIdLog($profileid,$senderRecevierStr='SENDER',$timeOfDeletion=null)
	{
		try 
		{
				if(!$profileid)
				{
					throw new jsException("","profile id is not specified in function getAllMessageIdLog of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="select ID FROM newjs.MESSAGE_LOG WHERE ".$senderRecevierStr."=:PROFILEID";
                    
                    if($timeOfDeletion) {
                      $sql.= " AND DATE <= :TIME_OF_DEL";
                    }
                    
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                    
                    if($timeOfDeletion) {
                      $prep->bindValue(":TIME_OF_DEL",$timeOfDeletion,PDO::PARAM_STR);
                    }
					
                    $prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row['ID'];
					}
				
					return $output;
				}	
		}
		catch(PDOException $e)
		{
			jsCacheWrapperException::logThis($e);
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
    
    /**
     * 
     * @param type $profileid
     * @param type $senderRecevierStr
     * @param type $timeOfDeletion
     * @return boolean
     * @throws jsException
     */
	public function deleteMessageLog($profileid,$senderRecevierStr='SENDER', $timeOfDeletion=null)
	{
		try 
		{
				if(!$profileid)
				{
					throw new jsException("","profile id is not specified in function getAllMessageIdLog of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="DELETE FROM newjs.MESSAGE_LOG WHERE ".$senderRecevierStr."=:PROFILEID";
                    
                    if($timeOfDeletion) {
                      $sql.= " AND DATE <= :TIME_OF_DEL";
                    }
					
                    $prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                    
                    if($timeOfDeletion) {
                      $prep->bindValue(":TIME_OF_DEL",$timeOfDeletion,PDO::PARAM_STR);
                    }
                    
					$prep->execute();
					return true;
				}	
		}
		catch(PDOException $e)
		{
			jsCacheWrapperException::logThis($e);
			return false;
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function insertIntoMessageLog($generatedId,$sender,$receiver,$isMsg,$obscene,$idObscene,$type,$seen='',$senderStatus='',$receiverStatus='',$folderId='')
	{
		try 
		{
				if(!$sender || !$receiver || !$isMsg || !$obscene ||!$type)
				{
					throw new jsException("","mandatory params are not specified in function insertIntoMessageLog of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$ip=FetchClientIP();
					if(strstr($ip, ","))    
					{                       
						$ip_new = explode(",",$ip);
						$ip = $ip_new[1];
					}
					$sql="INSERT INTO MESSAGE_LOG (ID,SENDER,RECEIVER,DATE,IP,IS_MSG,OBSCENE,MSG_OBS_ID,TYPE, SENDER_STATUS, RECEIVER_STATUS, SEEN, FOLDERID) VALUES (:GENERATEDID,:VIEWERID,:VIEWEDID,:DATE,:IP,:ISMSG,:OBSCENE,:IDOBSCENE,:TYPE,:SENDER_STATUS, :RECEIVER_STATUS, :SEEN, :FOLDERID)  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$generatedId,PDO::PARAM_INT);
					$prep->bindValue(":VIEWERID",$sender,PDO::PARAM_INT);
					$prep->bindValue(":VIEWEDID",$receiver,PDO::PARAM_INT);
					$prep->bindValue(":DATE",date("Y-m-d H:i:s"),PDO::PARAM_STR);
					$prep->bindValue(":IP",$ip,PDO::PARAM_STR);
					$prep->bindValue(":ISMSG",$isMsg,PDO::PARAM_STR);
					$prep->bindValue(":OBSCENE",$obscene,PDO::PARAM_STR);
					$prep->bindValue(":IDOBSCENE",$idObscene,PDO::PARAM_INT);
					$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
					$prep->bindValue(":SEEN",$seen,PDO::PARAM_STR);
					$prep->bindValue(":SENDER_STATUS",$senderStatus,PDO::PARAM_STR);
					$prep->bindValue(":RECEIVER_STATUS",$receiverStatus,PDO::PARAM_STR);
					$prep->bindValue(":FOLDERID",$folderId,PDO::PARAM_INT);
					$prep->execute();
					return true;
				}	
		}
		catch(PDOException $e)
		{
			jsCacheWrapperException::logThis($e);
			return false;
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	public function getMessageCountSmsActivity($profileid,$lastLoginDt)
	{
		try 
		{
			if(!$profileid || !$lastLoginDt )
			{
				throw new jsException("","mandatory params are not specified in function getMessageCountSmsActivity of newjs_MESSAGE_LOG.class.php");
			}
			else
			{
				$sql="SELECT COUNT(SENDER) MSG_COUNT FROM MESSAGE_LOG WHERE RECEIVER = :PROFILEID AND IS_MSG='Y' AND TYPE='R' AND `DATE`>= :LAST_LOGIN_DT";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->bindValue(":LAST_LOGIN_DT",$lastLoginDt,PDO::PARAM_STR);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$output = $result['MSG_COUNT'];
				}
				return $output;
			}
		}
		catch(PDOException $e)
		{
			jsCacheWrapperException::logThis($e);
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	


	public function deleteMessageLogById($msgId)
	{
		try 
		{
				if(!$msgId)
				{
					throw new jsException(""," id is not specified in function deleteMessageLogById of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="DELETE FROM newjs.MESSAGE_LOG WHERE ID=:MSG_ID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":MSG_ID",$msgId,PDO::PARAM_INT);
					$prep->execute();
					return true;
				}	
		}
		catch(PDOException $e)
		{
			jsCacheWrapperException::logThis($e);
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}


	public function insertMessageLogFromDeletedLogContact($id)
	{
		try 
		{
				if(!$id )
				{
					throw new jsException("","mandatory params are not specified in function insertMessageLogFromDeletedLogContact of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					
					$sql="Insert ignore into newjs.MESSAGE_LOG select * from newjs.DELETED_MESSAGE_LOG where ID=:ID ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":ID",$id,PDO::PARAM_INT);
					$prep->execute();
					return true;
				}	
		}
		catch(PDOException $e)
		{
			jsCacheWrapperException::logThis($e);
			return false;
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	
	public function getMessagesDataSearchPageDetails($profileid,$senderRecevierStr='SENDER')
	{
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profile id is not specified in function getMessagesDataSearchPageDetails of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="SELECT CONVERT_TZ(DATE,'SYSTEM','right/Asia/Calcutta') as DATE,IP,RECEIVER FROM newjs.MESSAGE_LOG  where ".$senderRecevierStr." = :PROFILEID ORDER BY DATE DESC limit 20";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
                        if(false === inet_pton($row['IP'])) {
                            $row['IP']=long2ip($row['IP']);
                        }
                                                        
						$output[] = $row;
					}
				
					return $output;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	
	
	
	public function deleteMultipleLogForSingleProfile($profileArray)
	{
		try 
			{
				if(!is_array($profileArray))
				{
					throw new jsException("","profile id is not specified in function deleteMultipleLogForSingleProfile of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$idStr=implode(",",$profileArray);
					$sql="DELETE FROM newjs.MESSAGE_LOG WHERE ID IN (".$idStr.")";
					$prep=$this->db->prepare($sql);
					$prep->execute();
				
					return true;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function sugarcrmCronSelectSender($profileId,$regDate,$regDurDate)
	{
		
		try 
			{
				if(!$profileId || !$regDate || !$regDurDate)
				{
					throw new jsException("","mandatory params is not specified in function sugarcrmCronSelectSender of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="select DATE from newjs.MESSAGE_LOG WHERE SENDER=:PROFILEID1 AND TYPE='I' AND DATE>=:REGDATE AND DATE<:REGULARDATE ORDER BY DATE ASC LIMIT 1";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID1",$profileId,PDO::PARAM_INT);
					$prep->bindValue(":REGDATE",$regDate,PDO::PARAM_STR);
					$prep->bindValue(":REGULARDATE",$regDurDate,PDO::PARAM_STR);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				
					return $output[0];
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function getMessageLogBilling($profileid,$senderRecevierStr='SENDER',$type='')
	{
		
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					if($type)
						$typeStr=" AND TYPE=:TYPE";
					else
						$typeStr=" ";
					$sql="SELECT RECEIVER,DATE,IP from newjs.MESSAGE_LOG where ".$senderRecevierStr."=:PROFILEID ".$typeStr." order by ID desc limit 20";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					if($type)
						$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				
					return $output[0];
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function getMessageLogScoringAb100($profileid,$senderRecevierStr='SENDER',$date)
	{
		
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="SELECT COUNT(*) as cnt,TYPE FROM newjs.MESSAGE_LOG WHERE ".$senderRecevierStr."= :PROFILEID AND DATE >= :DATE GROUP BY TYPE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->bindValue(":DATE",$date,PDO::PARAM_STR);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				
					return $output;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function getMessageLogCountScoringAb100($profileid,$senderRecevierStr='SENDER',$date)
	{
		
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="SELECT COUNT(*) as cnt FROM newjs.MESSAGE_LOG WHERE ".$senderRecevierStr."= :PROFILEID AND DATE >= :DATE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->bindValue(":DATE",$date,PDO::PARAM_STR);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output = $row['cnt'];
					}
				
					return $output;
				}
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function getMessageLogCountEOIScoringAb100($profileid,$senderRecevierStr='SENDER',$date,$date1)
	{
		
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="SELECT COUNT(*) as cnt FROM newjs.MESSAGE_LOG WHERE ".$senderRecevierStr."= :PROFILEID AND DATE >= :DATE AND DATE<:DATE1 AND TYPE='I'" ;
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
					$prep->bindValue(":DATE",$date,PDO::PARAM_STR);
					$prep->bindValue(":DATE1",$date1,PDO::PARAM_STR);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output = $row['cnt'];
					}
				
					return $output;
				}
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function getMessageLogIDCMR($sender,$receiver)
	{
		
		try 
			{
				if(!$sender || !$receiver)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="select ID from newjs.MESSAGE_LOG where SENDER=:PROFILEID1 and RECEIVER=:PROFILEID2 AND IS_MSG='Y' order by ID limit 1" ;
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID1",$sender,PDO::PARAM_INT);
					$prep->bindValue(":PROFILEID2",$receiver,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output = $row['ID'];
					}
				
					return $output;
				}
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function deleteFromMessageLog($profileid,$senderRecevierStr='SENDER')
	{
		
		try 
			{
				if(!$profileid)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="DELETE FROM newjs.MESSAGE_LOG WHERE ".$senderRecevierStr."=:PROFILEID" ;
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);					
					$prep->execute();
				
					return $output;
				}
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	 public function getContactIP($pid)
        {
			try{
				if(!$pid)
				{
					throw new jsException("","profileId is not specified in function getMessageLogBilling of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$contactsIPArr =array();
					$sql="select SQL_CACHE distinct inet_ntoa(IP) as IP from newjs.MESSAGE_LOG where SENDER=:PROFILEID ORDER BY ID DESC";
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);					
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$contactsIPArr[]=$row['IP'];
					}
					if(is_array($contactsIPArr))
					$contactsIPStr =@implode(", ",$contactsIPArr);
					return $contactsIPStr;
				}
			}
            catch(PDOException $e)
            {
				jsCacheWrapperException::logThis($e);
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
            }
	}
	
	public function insertMessageLogData($pid,$listOfActiveProfile,$whereStrLabel1='RECEIVER',$whereStrLabel2='SENDER')
        {
			if(!$pid || !$listOfActiveProfile)
                        throw new jsException("","VALUE OR TYPE IS BLANK IN selectActiveDeletedData() of NEWJS_MESSAGES.class.php");
			try 
			{ 
					$sql="INSERT IGNORE INTO newjs.MESSAGE_LOG SELECT * FROM newjs.DELETED_MESSAGE_LOG WHERE (".$whereStrLabel1."=:PROFILEID OR ".$whereStrLabel2."=:PROFILEID) AND (".$whereStrLabel1." IN (".$listOfActiveProfile.") OR ".$whereStrLabel2." IN (".$listOfActiveProfile."))";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					return true;
			
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				return false;
				throw new jsException($e);
			}
		}
		
		public function getMessageLogHousekeeping($profileId1,$profileId2)
	{
		
		
		try 
			{
				if(!$profileId1 || !$profileId2)
				{
					throw new jsException("","profile id is not specified in function getMessageLogHousekeeping of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql="SELECT ID FROM newjs.MESSAGE_LOG WHERE SENDER IN (:PROFILEID1,:PROFILEID2) AND RECEIVER IN (:PROFILEID1,:PROFILEID2)";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID1",$profileId1,PDO::PARAM_INT);
					$prep->bindValue(":PROFILEID2",$profileId2,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
					return $output;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
	}
	
	public function getMessageHistoryPagination($viewer,$viewed,$limit="",$msgId="")
		{
			try
			{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					if($limit)
						$limitStr= " limit :limit ";
					else
						$limitStr="";
						
					if($msgId)	
						$paginationStr=" AND MESSAGE_LOG.ID<:MSG_ID ";
					else
						$paginationStr="";
					$sql = "SELECT SENDER,RECEIVER, DATE, MESSAGE,MESSAGE_LOG.ID FROM  `MESSAGE_LOG` JOIN MESSAGES ON ( MESSAGES.ID = MESSAGE_LOG.ID ) WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ) OR (`RECEIVER` =:VIEWED AND SENDER =:VIEWER ))AND IS_MSG =  'Y' AND TYPE IN ('R','I') ".$paginationStr." ORDER BY DATE Desc ".$limitStr;
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					if($msgId)
						$prep->bindValue(":MSG_ID",$msgId,PDO::PARAM_INT);
					if($limit)
						$prep->bindValue(":limit",$limit,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
        
        /**
         * 
         * @param type $pid
         * @param type $listOfActiveProfile
         * @param type $whereStrLabel1
         * @param type $whereStrLabel2
         * @return boolean
         * @throws jsException
         */
        public function insertMessageLogDataFromEligibleForRet($pid, $listOfActiveProfile, $whereStrLabel1 = 'RECEIVER', $whereStrLabel2 = 'SENDER')
        {
            if (!$pid || !$listOfActiveProfile)
                throw new jsException("", "VALUE OR TYPE IS BLANK IN selectActiveDeletedData() of NEWJS_MESSAGES.class.php");
            try {
                $sql = "INSERT IGNORE INTO newjs.MESSAGE_LOG SELECT * FROM newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET WHERE (" . $whereStrLabel1 . "=:PROFILEID OR " . $whereStrLabel2 . "=:PROFILEID) AND (" . $whereStrLabel1 . " IN (" . $listOfActiveProfile . ") OR " . $whereStrLabel2 . " IN (" . $listOfActiveProfile . "))";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
                $prep->execute();
                return true;
            }
            catch (PDOException $e) {
                jsCacheWrapperException::logThis($e);
                return false;
                throw new jsException($e);
            }
        }

}
	?>
