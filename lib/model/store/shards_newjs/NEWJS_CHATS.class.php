<?php
class NEWJS_CHATS extends TABLE{
       

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
		
		
	public function insertSingleMessage($id,$msg)
		{
			try 
			{
				if($id && $msg)
				{ 
					$sql="INSERT INTO CHATS  (ID,MESSAGE) VALUES (:GENERATEDID,:CUSTOMMESSAGE)  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":GENERATEDID",$id,PDO::PARAM_STR);
					$prep->bindValue(":CUSTOMMESSAGE",$msg,PDO::PARAM_STR);
					$prep->execute();
					
				}
				else{
					
					throw new jsException("","VALUE OR TYPE IS BLANK IN insertSingleMessage() of NEWJS_CHATS.class.php");
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
     * @param type $arrChatIds
     * @return type
     * @throws jsException
     * @throws jsExcception
     */
    public function removeRecords($arrChatIds)
    {
      try {
        if (0 === count($arrChatIds)) {
          throw new jsException("", "Empty array passed to removeRecords in NEWJS_CHAT.class.php");
        }

        $strChatIds = "'".implode("','", $arrChatIds)."'";

        $sql = "DELETE FROM newjs.CHATS where ID IN ({$strChatIds})";

        $prep = $this->db->prepare($sql);
        $prep->bindValue(":CHATIDS", $strChatIds, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
      } catch (Exception $ex) {
        throw new jsException($ex);
      }
    }
        
		/**
     * 
     * @param type $arrChatIds
     * @return type
     * @throws jsException
     */
    public function insertFromEligibleForRet($arrChatIds)
    {
      try{
        if (0 === count($arrChatIds)) {
          throw new jsException("", "Empty array passed to insertFromEligibleForRet in NEWJS_CHATS.class.php");
        }

        $strChatIds = "'".implode("','", $arrChatIds)."'";
        $sql = "INSERT INTO newjs.CHATS SELECT * FROM newjs.DELETED_CHATS_ELIGIBLE_FOR_RET where ID IN ({$strChatIds})";

        $prep = $this->db->prepare($sql);
        $prep->bindValue(":CHATIDS", $strChatIds, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
      } catch (Exception $ex) {
        throw new jsException($ex);
      }
    }
        }
	?>
