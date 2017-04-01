<?php

/*
 * Logging of the logins of backend AGENTS 
 */

class jsadmin_AGENTS_LOGIN_LOG extends TABLE{
    
    public function __construct($dbName = "")
    {
        parent::__construct($dbname);
    }
    
    public function insert($username){
        try{
            if($username){
                $todaysDt = date('Y-m-d', strtotime('570 minute', strtotime(date('Y-m-d H:i:s'))));
                $sql = "INSERT INTO jsadmin.AGENTS_LOGIN_LOG (USERNAME,ENTRY_DT_IST) VALUES (:USERNAME,:ENTRY_DT_IST)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
                $prep->bindValue(":ENTRY_DT_IST",$todaysDt,PDO::PARAM_STR);
                $prep->execute();
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function fetchLoggedInAgentForDate($checkLogDate){
        try{
            if($checkLogDate){
                $sql = "SELECT distinct USERNAME FROM jsadmin.AGENTS_LOGIN_LOG WHERE ENTRY_DT_IST = :ENTRY_DT_IST";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":ENTRY_DT_IST",$checkLogDate,PDO::PARAM_STR);
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row['USERNAME'];
                }
                return $result;
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function deleteLogBeforeDate($deleteBeforeDate){
        try{
            if($deleteBeforeDate){
                $sql = "DELETE FROM jsadmin.AGENTS_LOGIN_LOG WHERE ENTRY_DT_IST < :ENTRY_DT_IST";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":ENTRY_DT_IST",$deleteBeforeDate,PDO::PARAM_STR);
                $prep->execute();
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
