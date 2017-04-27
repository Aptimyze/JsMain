<?php
class NEWJS_CHAT_LOG extends TABLE{
       

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
		
		
	public function insertIntoChatLog($generatedId,$sender,$receiver,$type,$seen='',$chatId='',$ip='',$date)
	{
		
		try 
		{
				if(!$sender || !$receiver || !$type)
				{
					throw new jsException("","mandatory params are not specified in function insertIntoMessageLog of newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					if(!$ip)
					{
						$ip='192.168.192.168';
						/*$ip=FetchClientIP();
						if(strstr($ip, ","))    
						{                       
							$ip_new = explode(",",$ip);
							$ip = $ip_new[1];
						}*/
					}
					$sql="INSERT INTO CHAT_LOG (SENDER,RECEIVER,DATE,IP,TYPE,SEEN, CHATID) VALUES (:VIEWERID,:VIEWEDID,:DATE,:IP,:TYPE,:SEEN, :CHATID) ";
					$prep=$this->db->prepare($sql);
					//$prep->bindValue(":GENERATEDID",$generatedId,PDO::PARAM_INT);
					$prep->bindValue(":VIEWERID",$sender,PDO::PARAM_INT);
					$prep->bindValue(":VIEWEDID",$receiver,PDO::PARAM_INT);
					$prep->bindValue(":DATE",$date,PDO::PARAM_STR);
					$prep->bindValue(":IP",$ip,PDO::PARAM_STR);
					$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
					$prep->bindValue(":SEEN",$seen,PDO::PARAM_STR);
					$prep->bindValue(":CHATID",$chatId,PDO::PARAM_STR);
					$prep->execute();
					
					return true;
				}	
		}
		catch(PDOException $e)
		{
			
			jsCacheWrapperException::logThis($e);
			throw new jsException($e);
			/*** echo the sql statement and error message ***/
			
		}
	}


	public function getMessageHistory($viewer,$viewed,$limit,$pagination="")
		{
			try
			{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					if($pagination)
						$whrStr="AND C.ID < :pagination";
					else
						$whrStr="";
					if($limit)
						$limitStr= " limit :limit ";
					else
						$limitStr="";
						
					$sql = "SELECT SENDER,RECEIVER, DATE, MESSAGE ,CHATID,C.ID FROM  `CHAT_LOG` AS C JOIN CHATS AS M ON ( M.ID = C.CHATID ) WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ) OR (`RECEIVER` =:VIEWED AND SENDER =:VIEWER )) ".$whrStr." ORDER BY DATE DESC,ID DESC ".$limitStr;
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					if($limit)
						$prep->bindValue(":limit",$limit,PDO::PARAM_INT);
					if($pagination)
						$prep->bindValue(":pagination",$pagination,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row;
					}
					return $output;
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
	
	public function getChatCount($viewer,$viewed)
		{
			try
			{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					
					$sql = "SELECT count(*) as CNT FROM  `CHAT_LOG`  WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ) OR (`RECEIVER` =:VIEWED AND SENDER =:VIEWER ))";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$count = $row['CNT'];
					}
					return $count;
				}
			}
			catch (PDOException $e)
			{
				throw new jsException($e);
			}
			return $output;
		}
                
                public function getMessageListing($condition,$skipArray)
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
					$sql = "SELECT SQL_CACHE SENDER AS PROFILEID, MESSAGE,  'R' AS SR,SEEN,DATE FROM  `CHAT_LOG` USE INDEX (RECEIVER) JOIN CHATS ON ( CHAT_LOG.CHATID = CHATS.ID ) WHERE  `RECEIVER` =:PROFILEID".$sender." AND  `TYPE` ='A' UNION ALL SELECT  RECEIVER AS PROFILEID, MESSAGE,  'S' AS SR,SEEN,DATE FROM  `CHAT_LOG` USE INDEX (SENDER) JOIN CHATS ON ( CHAT_LOG.CHATID = CHATS.ID ) WHERE  `SENDER` =:PROFILEID ".$receiver." AND  `TYPE` ='A' ORDER BY DATE DESC";
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
					
					
		
		public function makeAllChatsSeen($profileid)
		{
			try{
				$sql = "UPDATE newjs.CHAT_LOG SET SEEN = 'Y' WHERE RECEIVER = :PROFILEID AND TYPE = 'A'";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->execute();
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		
		public function markChatSeen($viewer,$viewed)
		{
			try{
				if(!$viewer && !$viewed)
				{
					throw new jsException("","profile ids are not specified in  funcion getMessageHistory OF newjs_MESSAGE_LOG.class.php");
				}
				else
				{
					$sql = "UPDATE newjs.CHAT_LOG SET `SEEN`='Y' WHERE SENDER = :VIEWED AND RECEIVER = :VIEWER AND TYPE = 'A' AND SEEN='N'";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
					//$prep->bindValue(":ID",$id,PDO::PARAM_INT);

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
    
    /**
     * 
     * @param type $iProfileID
     * @return type
     * @throws jsException
     */
    public function getAllChatForHousKeeping($iProfileID)
    {
      try{
        if(!$iProfileID)
				{
					throw new jsException("","Profile id is not specified in function getAllChatForHousKeeping of NEWJS_CHAT_LOG.class.php");
				}
        
        $sql = "SELECT CHATID FROM newjs.CHAT_LOG WHERE SENDER = :PID OR RECEIVER = :PID";
        $prep=$this->db->prepare($sql);
        $prep->bindValue(":PID",$iProfileID,PDO::PARAM_INT);
        $prep->execute();
        while($row = $prep->fetch(PDO::FETCH_ASSOC))
        {
          $output[] = $row['CHATID'];
        }
        return $output;
      } catch (Exception $ex) {
        throw new jsException($e);
      }
    }
    
    /**
     * 
     * @param type $iProfileID
     * @return type
     * @throws jsException
     */
    public function deleteAllChatForUser($iProfileID)
    {
      try{
        if(!$iProfileID)
				{
					throw new jsException("","Profile id is not specified in function deleteAllChatForUser of NEWJS_CHAT_LOG.class.php");
				}
        
        $sql = "DELETE FROM newjs.CHAT_LOG WHERE SENDER = :PID OR RECEIVER = :PID";
        $prep=$this->db->prepare($sql);
        $prep->bindValue(":PID",$iProfileID,PDO::PARAM_INT);
        $prep->execute();
        
        } catch (Exception $ex) {
        throw new jsException($e);
      }
    }
    
  /**
   * 
   * @param type $iProfileID
   */
  public function insertRecordsIntoChatLogFromRetreieve($iProfileID,$listOfActiveProfiles)
  {
    try {
      if (!$iProfileID || !$listOfActiveProfiles) {
        throw new jsException("", "PROFILEID OR LISTOFACTIVEPROFILE IS BLANK IN selectActiveDeletedData() of NEWJS_CHAT_LOG.class.php");
      }
      
      $sql = "INSERT IGNORE INTO newjs.CHAT_LOG SELECT * FROM newjs.DELETED_CHAT_LOG_ELIGIBLE_FOR_RET WHERE (SENDER = :PID AND RECEIVER IN ({$listOfActiveProfiles}) ) OR (RECEIVER = :PID AND SENDER IN ({$listOfActiveProfiles}) )";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
      $prep->execute();
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }

}
	?>
