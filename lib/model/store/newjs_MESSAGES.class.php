<?php
class NEWJS_MESSAGES extends TABLE{
       

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
        public function Messages($Id)
        {
			try 
			{
				if($Id)
				{ 
					$sql="SELECT ID,MESSAGE FROM newjs.MESSAGES WHERE ID IN('$Id')";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":ID",$Id,PDO::PARAM_INT);
					$prep->execute();
					while($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$res[$result[ID]]= $result[MESSAGE];
					}
					return $res;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function updateMessages($msgCommObj)
        {
			try 
			{
				if($msgCommObj->getID() && $msgCommObj->getMESSAGE())
				{ 
					$sql="INSERT INTO MESSAGES VALUES (:GENERATEDID,:CUSTOMMESSAGE)  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$msgCommObj->getID(),PDO::PARAM_INT);
					$prep->bindValue(":CUSTOMMESSAGE",$msgCommObj->getMESSAGE(),PDO::PARAM_STR);
					$prep->execute();
					
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function updateMessagesValue($msgCommObj)
		{
			try 
			{
				if($msgCommObj->getID() && $msgCommObj->getMESSAGE())
				{ 
					$sql="REPLACE INTO MESSAGES VALUES (:GENERATEDID,:CUSTOMMESSAGE)";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$msgCommObj->getID(),PDO::PARAM_INT);
					$prep->bindValue(":CUSTOMMESSAGE",$msgCommObj->getMESSAGE(),PDO::PARAM_STR);
					$prep->execute();
					
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

		public function deleteMessages($idsArr)
		{
			try 
			{
				if(is_array($idsArr))
				{ 
					$idStr=implode(",",$idsArr);
					$sql="DELETE FROM newjs.MESSAGES WHERE ID IN (".$idStr.")";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					return true;
				}
				else
				{
					throw new jsException("","profile id array is not specified in function deleteMessages of newjs_MESSAGES.class.php");
				}
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

		public function insertIntoMessages($idsArr)
        {
			try 
			{
				if(is_array($idsArr))
				{ 
					$idStr=implode(",",$idsArr);
					$sql="INSERT IGNORE INTO newjs.MESSAGES SELECT * FROM newjs.DELETED_MESSAGES WHERE ID IN (".$idStr.")";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					return true;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				return false;
				/*** echo the sql statement and error message ***/
				//throw new jsException($e);
			}
		}
		
		public function insertMessageData($pid,$listOfActiveProfile,$whereStrLabel1='RECEIVER',$whereStrLabel2='SENDER')
        {
			if(!$pid || !$listOfActiveProfile){
                        throw new jsException("","VALUE OR TYPE IS BLANK IN selectActiveDeletedData() of NEWJS_MESSAGES.class.php");
					}
			try 
			{ 
					$sql="INSERT IGNORE INTO newjs.MESSAGES SELECT * FROM newjs.DELETED_MESSAGES WHERE (".$whereStrLabel1."=:PROFILEID OR ".$whereStrLabel2."=:PROFILEID) AND (".$whereStrLabel1." IN (".$listOfActiveProfile.") OR ".$whereStrLabel2." IN (".$listOfActiveProfile."))";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					while($row = $prep->fetch(PDO::FETCH_ASSOC))
					{
						$output[] = $row['ID'];
					}
					return $output;
			
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				return false;
				throw new jsException($e);
			}
		}

		public function insertSingleMessage($id,$msg)
		{
			try 
			{
				if($id && $msg)
				{ 
					$sql="INSERT INTO MESSAGES VALUES (:GENERATEDID,:CUSTOMMESSAGE)  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$id,PDO::PARAM_INT);
					$prep->bindValue(":CUSTOMMESSAGE",$msg,PDO::PARAM_STR);
					$prep->execute();
					
				}
				else{
					throw new jsException("","VALUE OR TYPE IS BLANK IN insertSingleMessage() of NEWJS_MESSAGES.class.php");
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
         * Insert From Eligible For Retrieve Tables
         * @param type $idsArr
         * @return boolean
         */
        public function insertIntoFromMessagesEligibleForRet($idsArr)
        {
			try 
			{
				if(is_array($idsArr))
				{ 
					$idStr=implode(",",$idsArr);
					$sql="INSERT IGNORE INTO newjs.MESSAGES SELECT * FROM newjs.DELETED_MESSAGES_ELIGIBLE_FOR_RET WHERE ID IN (".$idStr.")";
					$prep=$this->db->prepare($sql);
					$prep->execute();
					return true;
				}	
			}
			catch(PDOException $e)
			{
				jsCacheWrapperException::logThis($e);
				return false;
				/*** echo the sql statement and error message ***/
				//throw new jsException($e);
			}
		}
  
   /**
    * 
    * @param type $Id
    * @param type $tablePrefix
    * @param type $tableSuffix
    * @return type
    * @throws jsException
    */
    public function MessagesFromArchive($Id,$tablePrefix="", $tableSuffix="")
    {
      
      try {
        if ($Id) {
          $sql = "SELECT ID,MESSAGE FROM newjs.{$tablePrefix}DELETED_MESSAGES{$tableSuffix} WHERE ID IN('$Id')";
          $prep = $this->db->prepare($sql);
          $prep->bindValue(":ID", $Id, PDO::PARAM_INT);
          $prep->execute();
          while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
            $res[$result[ID]] = $result[MESSAGE];
          }
          return $res;
        }
      } catch (PDOException $e) {
        jsCacheWrapperException::logThis($e);
        /*       * * echo the sql statement and error message ** */
        throw new jsException($e);
      }
    }

}
?>
