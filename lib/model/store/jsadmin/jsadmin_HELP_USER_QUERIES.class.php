<?php

/**
 * Description of jsadmin_HELP_USER_QUERIES
 *
 * @author nitish
 */
class jsadmin_HELP_USER_QUERIES extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function insert($paramsArr){
        if(!$paramsArr){
            throw new jsException("Some input parameter missing");
        }
        try{
            $sql = "INSERT INTO jsadmin.HELP_USER_QUERIES VALUES (null, :EMAIL, :CATEGORY, :USERNAME, :QUERY, :ENTRY_DT, :CHANNEL)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EMAIL",$paramsArr['email'],PDO::PARAM_STR);
            $prep->bindValue(":CATEGORY",$paramsArr['category'],PDO::PARAM_STR);
            $prep->bindValue(":USERNAME",$paramsArr['username'],PDO::PARAM_STR);
            $prep->bindValue(":QUERY",$paramsArr['query'],PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DT",date('Y-m-d'),PDO::PARAM_STR);
            $prep->bindValue(":CHANNEL",$paramsArr['channel'],PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
