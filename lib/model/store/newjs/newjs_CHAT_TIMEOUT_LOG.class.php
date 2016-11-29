<?php

/**
 * Description of newjs_CHAT_TIMEOUT_LOG
 *
 * @author ankita
 */
class newjs_CHAT_TIMEOUT_LOG extends TABLE {
    
    public function __construct($dbname='')
	{
		parent::__construct($dbname);
	}
    
    public function insert($paramsArr){
        try{
            $sql = "INSERT INTO newjs.CHAT_TIMEOUT_LOG (PROFILEID,COUNT,ENTRY_DT) VALUES (:PROFILEID,1,:ENTRY_DT) ON DUPLICATE KEY UPDATE COUNT=COUNT+1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_INT);
            $prep->bindValue(":ENTRY_DT",date("Y-m-d"),PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            //throw new jsException($ex);
        }
    }
}
