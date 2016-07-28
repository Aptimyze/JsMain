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
		
		
	public function insertIntoChatLog($generatedId,$sender,$receiver,$isMsg,$obscene,$idObscene,$type,$seen='',$senderStatus='',$receiverStatus='',$folderId='')
	{
		try 
		{
				if(!$sender || !$receiver || !$isMsg ||!$type)
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
					$sql="INSERT INTO CHAT_LOG (ID,SENDER,RECEIVER,DATE,IP,IS_MSG,OBSCENE,MSG_OBS_ID,TYPE, SENDER_STATUS, RECEIVER_STATUS, SEEN, FOLDERID) VALUES (:GENERATEDID,:VIEWERID,:VIEWEDID,:DATE,:IP,:ISMSG,:OBSCENE,:IDOBSCENE,:TYPE,:SENDER_STATUS, :RECEIVER_STATUS, :SEEN, :FOLDERID)  ";
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
			echo "ASD";die;
			throw new jsException($e);
			jsCacheWrapperException::logThis($e);
			/*** echo the sql statement and error message ***/
			
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
					$sql = "SELECT SENDER, DATE, MESSAGE FROM  `CHAT_LOG` JOIN MESSAGES ON ( MESSAGES.ID = CHAT_LOG.ID ) WHERE ((`RECEIVER` =:VIEWER AND SENDER =:VIEWED ) OR (`RECEIVER` =:VIEWED AND SENDER =:VIEWER ))  ORDER BY DATE";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWER",$viewer,PDO::PARAM_INT);
					$prep->bindValue(":VIEWED",$viewed,PDO::PARAM_INT);
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
		
}
	?>
