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
						$whrStr="AND M.ID < :pagination";
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
					$sql = "UPDATE newjs.CHAT_LOG SET `SEEN`='Y' WHERE SENDER = :VIEWED AND RECEIVER = :VIEWER AND TYPE = 'A' ";
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
}
	?>
