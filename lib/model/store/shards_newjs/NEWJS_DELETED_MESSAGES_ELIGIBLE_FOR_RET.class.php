<?php

/**
 * 
 */
class NEWJS_DELETED_MESSAGES_ELIGIBLE_FOR_RET extends TABLE
{

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "")
    {
        if (strpos($dbname, 'master') !== false && JsConstants::$communicationRep)
            $dbname = $dbname . "Rep";
        parent::__construct($dbname);
    }

    /**
     * 
     * @param type $profileId
     * @return type
     * @throws jsException
     */
    public function getSenderMessages($profileId)
    {

        try {
            if ($profileId) {
                $sql = "SELECT RECEIVER, SEEN FROM DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET WHERE SENDER = :PROFILEID AND TYPE = 'R' AND IS_MSG = 'Y'";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $prep->execute();
                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res[] = $result;
                }
                return $res;
            }
        }
        catch (PDOException $e) {
            jsCacheWrapperException::logThis($e);
            /*             * * echo the sql statement and error message ** */
            throw new jsException($e);
        }
    }

    /**
     * insertIntoDeletedMessagesEligibleForRetrieve
     * @param type $idsArr
     * @return boolean
     */
    public function insertFromMessages($idsArr)
    {
        try {
            if (is_array($idsArr)) {
                $idStr = implode(",", $idsArr);
                $sql = "INSERT IGNORE INTO newjs.DELETED_MESSAGES_ELIGIBLE_FOR_RET SELECT * FROM newjs.MESSAGES WHERE ID IN (" . $idStr . ")";
                $prep = $this->db->prepare($sql);
                $prep->execute();
            }
            return true;
        }
        catch (PDOException $e) {
            jsCacheWrapperException::logThis($e);
            return false;
            /*             * * echo the sql statement and error message ** */
            //throw new jsException($e);
        }
    }

    public function deleteMessages($idsArr)
    {
        try {
            if (is_array($idsArr)) {
                $idStr = implode(",", $idsArr);
                $sql = "DELETE FROM newjs.DELETED_MESSAGES_ELIGIBLE_FOR_RET WHERE ID IN (" . $idStr . ")";
                $prep = $this->db->prepare($sql);
                $prep->execute();
                return true;
            }
            else {
                throw new jsException("", "profile id array is not specified in function deleteMessages of NEWJS_DELETED_MESSAGES_ELIGIBLE_FOR_RET.class.php");
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
