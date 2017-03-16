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

    public function insert($pid, $whereStrLabel = 'SENDER')
    {
        if (!$pid)
            throw new jsException("", "VALUE OR TYPE IS BLANK IN insert() of NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET.class.php");
        try {
            $sql = "INSERT IGNORE INTO newjs.DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET SELECT * FROM newjs.MESSAGE_LOG WHERE " . $whereStrLabel . "=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
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

}

?>
