<?php
class incentive_SUCCESS_STORY_EMAIL_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
    

    public function getLogEntry($mailid,$status)
    {
        try
        {
            if (!$mailid){
                throw new jsException("", "MAILERID IS BLANK in incentive_SUCCESS_STORY_EMAIL_LOG -> getLogEntry()");
            }
            $sql ="SELECT PROFILEID FROM incentive.SUCCESS_STORY_EMAIL_LOG where MAILERID=:MAILID";
            if($status){
                $sql .= " AND STATUS =:STATUS";
            }
            $res = $this->db->prepare($sql);
            $res->bindValue(":MAILID", $mailid, PDO::PARAM_STR);
            if($status){
                $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
            }
            $res->execute();
            if($row = $res->fetch(PDO::FETCH_ASSOC))
                return $row['PROFILEID'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    



}


