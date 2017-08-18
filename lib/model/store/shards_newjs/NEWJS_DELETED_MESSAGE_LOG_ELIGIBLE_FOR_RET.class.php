<?php

/**
 * 
 */
class NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET extends TABLE
{

    public function __construct($dbname = "")
    {
        if (strpos($dbname, 'master') !== false && JsConstants::$communicationRep)
            $dbname = $dbname . "Rep";
        parent::__construct($dbname);
    }

    public function insert($pid, $whereStrLabel = 'SENDER', $timeOfDeletion=null)
    {
        if (!$pid)
            throw new jsException("", "VALUE OR TYPE IS BLANK IN insert() of NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET.class.php");
        try {
            $sql = "INSERT IGNORE INTO newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET SELECT * FROM newjs.MESSAGE_LOG WHERE " . $whereStrLabel . "=:PROFILEID";
            if($timeOfDeletion) {
              $sql.= " AND DATE <= :TIME_OF_DEL";
            }
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            
            if($timeOfDeletion) {
              $prep->bindValue(":TIME_OF_DEL",$timeOfDeletion,PDO::PARAM_STR);
            }
            
            $prep->execute();
            $count = $prep->rowCount();
            return $count;
        }
        catch (PDOException $e) {
            jsCacheWrapperException::logThis($e);
            return false;
            throw new jsException($e);
        }
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
    public function selectActiveDeletedData($pid, $listOfActiveProfile, $whereStrLabel1 = 'RECEIVER', $whereStrLabel2 = 'SENDER')
    {
        if (!$pid || !$listOfActiveProfile)
            throw new jsException("", "VALUE OR TYPE IS BLANK IN selectActiveDeletedData() of NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET.class.php");
        try {
            $sql = "select ID FROM newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET WHERE (" . $whereStrLabel1 . "=:PROFILEID OR " . $whereStrLabel2 . "=:PROFILEID) AND (" . $whereStrLabel1 . " IN (" . $listOfActiveProfile . ") OR " . $whereStrLabel2 . " IN (" . $listOfActiveProfile . "))";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $row['ID'];
            }
            return $output;
        }
        catch (PDOException $e) {
            jsCacheWrapperException::logThis($e);
            return false;
            throw new jsException($e);
        }
    }

    /**
     * 
     * @param type $profileid
     * @param type $listOfActiveProfile
     * @param type $whereStrLabel1
     * @param type $whereStrLabel2
     * @return boolean
     * @throws jsException
     */
    public function deleteMessages($profileid, $listOfActiveProfile, $whereStrLabel1 = 'RECEIVER', $whereStrLabel2 = 'SENDER')
    {
        try {
            if ($listOfActiveProfile && $profileid) {

                $sql = "DELETE FROM newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET WHERE (" . $whereStrLabel1 . "=:PROFILEID OR " . $whereStrLabel2 . "=:PROFILEID) AND (" . $whereStrLabel1 . " IN (" . $listOfActiveProfile . ") OR " . $whereStrLabel2 . " IN (" . $listOfActiveProfile . "))";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $prep->execute();
                return true;
            }
            else {
                throw new jsException("", "profile id  is not specified in function deleteMessages of NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET.class.php");
            }
        }
        catch (PDOException $e) {
            jsCacheWrapperException::logThis($e);
            /*             * * echo the sql statement and error message ** */
            throw new jsException($e);
        }
    }
  
    /**
     * 
     * @param type $profileid
     * @param type $senderRecevierStr
     * @return type
     * @throws jsException
     */
  public function getMessagesDataSearchPageDetails($profileid, $senderRecevierStr = 'SENDER')
  {
    try {
      if (!$profileid) {
        throw new jsException("", "profile id is not specified in function getMessagesDataSearchPageDetails of NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET.class.php");
      }
      else {
        $sql = "SELECT CONVERT_TZ(DATE,'SYSTEM','right/Asia/Calcutta') as DATE,INET_NTOA(IP) AS IP,RECEIVER FROM newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET  where " . $senderRecevierStr . " = :PROFILEID ORDER BY DATE DESC limit 20";
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
        $prep->execute();
        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
          $output[] = $row;
        }

        return $output;
      }
    } catch (PDOException $e) {
      jsCacheWrapperException::logThis($e);
      /*       * * echo the sql statement and error message ** */
      throw new jsException($e);
    }
  }

}

?>
