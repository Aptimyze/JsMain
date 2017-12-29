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
    /**
     * fetch receivers whose alert option is not equal to U
     * @param  [Array] $receiverData [description]
     */
    public function fetchReceivers($receiverData)
    {

        try
        {
            if(is_array($receiverData))
            {
                $sql="SELECT PROFILEID FROM visitoralert.VISITOR_ALERT_OPTION WHERE ALERT_OPTION = 'U' AND PROFILEID IN ( ";

                foreach($receiverData as $key=>$value)
                {
                    $sql .=":PROFILEID".$key.",";
                }
                $sql = rtrim($sql,",");
                $sql .= ")";
                $res = $this->db->prepare($sql);
                foreach($receiverData as $key => $value)
                {
                    $res->bindValue(":PROFILEID".$key, $value["VIEWED"], PDO::PARAM_INT);
                }
                $res->execute();
                $output = array();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
                {
                   $output[] = $row["PROFILEID"];
                }
                return $output;
            }
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
        
    }
    
    public function getResult($profileid){
        try {
            $sql= "SELECT ALERT_OPTION FROM visitoralert.VISITOR_ALERT_OPTION WHERE PROFILEID = :PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            if($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $out = $row["ALERT_OPTION"];
            }
            return $out;
            
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
}
?>
