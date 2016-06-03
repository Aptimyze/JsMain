<?php

class incentive_CRM_AGENT_CHECKIN_CHECKOUT_LOG extends TABLE{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }
    
    public function insert($operatorName, $clientName, $logType, $agentLocation, $latitude, $longitude, $timestamp){
        try{
            $sql = "INSERT INTO incentive.CRM_AGENT_CHECKIN_CHECKOUT_LOG VALUES (NULL, :AGENT_NAME, :CLIENT_NAME, :LOG_TYPE, :AGENT_LOCATION, :LATITUDE, :LONGITUDE,:DATE_TIME)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":AGENT_NAME",$operatorName,PDO::PARAM_STR);
            $prep->bindValue(":CLIENT_NAME",$clientName,PDO::PARAM_STR);
            $prep->bindValue(":LOG_TYPE",$logType,PDO::PARAM_STR);
            $prep->bindValue(":AGENT_LOCATION",$agentLocation,PDO::PARAM_STR);
            $prep->bindValue(":LATITUDE",$latitude,PDO::PARAM_STR);
            $prep->bindValue(":LONGITUDE",$longitude,PDO::PARAM_STR);
            $prep->bindValue(":DATE_TIME",$timestamp,PDO::PARAM_STR);
            $res = $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
