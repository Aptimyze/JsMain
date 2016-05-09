<?php

class visitoralert_VISITOR_ALERT_OPTION extends TABLE
{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function fetchAlertOption($profileid) {
        try {
            $sql = "SELECT ALERT_OPTION FROM visitoralert.VISITOR_ALERT_OPTION WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
            if($result = $res->fetch(PDO::FETCH_ASSOC))
			{
				return $result['ALERT_OPTION'];
			}
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function updateAlertOption($profileid,$alertOption) {
        try {
            $sql = "REPLACE INTO visitoralert.VISITOR_ALERT_OPTION VALUES (:PROFILEID,:ALERT_OPTION)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":ALERT_OPTION", $alertOption, PDO::PARAM_STR);
            $res->execute();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
