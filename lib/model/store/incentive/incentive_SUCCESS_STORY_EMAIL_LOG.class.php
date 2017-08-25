<?php

class incentive_SUCCESS_STORY_EMAIL_LOG extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    /*
     * Status Index::
     * 'Y': Mailer sent and link is active
     * 'C': Success story uploaded and link is inactive
     * 'N': Mailer expired
     */

    public function getLogEntry($mailid, $status) {
        try {
            if (!$mailid) {
                throw new jsException("", "MAILERID IS BLANK in incentive_SUCCESS_STORY_EMAIL_LOG -> getLogEntry()");
            }
            $sql = "SELECT PROFILEID FROM incentive.SUCCESS_STORY_EMAIL_LOG where MAILERID=:MAILID";
            if ($status) {
                $sql .= " AND STATUS =:STATUS";
            }
            $res = $this->db->prepare($sql);
            $res->bindValue(":MAILID", $mailid, PDO::PARAM_STR);
            if ($status) {
                $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
            }
            $res->execute();
            if ($row = $res->fetch(PDO::FETCH_ASSOC))
                return $row['PROFILEID'];
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function insertLogEntry($profileid, $status) {
        try {
            if (!$profileid || !$status)
                throw new jsException('Error');

            $sql = "INSERT INTO incentive.SUCCESS_STORY_EMAIL_LOG(PROFILEID,ENTRY_DT,STATUS) VALUES (:PROFILEID,now(),:STATUS)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $res->execute();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
        public function updateStatusForMailerId($mailerid, $status) {
        try {
            if (!$mailerid || !$status)
                throw new jsException('Error');

            $sql = "UPDATE incentive.SUCCESS_STORY_EMAIL_LOG SET STATUS=:STATUS where MAILERID=:MAILERID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $res->bindValue(":MAILERID", $mailerid, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

}
