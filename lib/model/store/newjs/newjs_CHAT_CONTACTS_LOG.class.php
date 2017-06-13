<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newjs_CHAT_CONTACTS_LOG
 *
 * @author nitish
 */
class newjs_CHAT_CONTACTS_LOG extends TABLE {
    
    public function __construct($dbname='')
	{
		parent::__construct($dbname);
	}
    
    public function insert($paramsArr){
        try{
            //$sql = "INSERT INTO newjs.CHAT_CONTACTS_LOG (PROFILEID, ACC_BY_ME, ACC_ME, AWAITING_RESPONSE, BOOKMARK, ENTRY_DT, TYPE) VALUES(:PROFILEID, :ACC_BY_ME, :ACC_ME, :AWAITING_RESPONSE, :BOOKMARK, :ENTRY_DT, :TYPE)";
            $sql = "REPLACE INTO newjs.CHAT_CONTACTS_LOG (PROFILEID, ACC_BY_ME, ACC_ME, AWAITING_RESPONSE, BOOKMARK, ENTRY_DT, TYPE) VALUES(:PROFILEID, :ACC_BY_ME, :ACC_ME, :AWAITING_RESPONSE, :BOOKMARK, :ENTRY_DT, :TYPE)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_INT);
            $prep->bindValue(":ACC_BY_ME",$paramsArr["ACC_BY_ME"],PDO::PARAM_INT);
            $prep->bindValue(":ACC_ME",$paramsArr["ACC_ME"],PDO::PARAM_INT);
            $prep->bindValue(":AWAITING_RESPONSE",$paramsArr["AWAITING_RESPONSE"],PDO::PARAM_INT);
            $prep->bindValue(":BOOKMARK",$paramsArr["BOOKMARK"],PDO::PARAM_INT);
            $prep->bindValue(":ENTRY_DT",date("Y-m-d H:i:s"),PDO::PARAM_STR);
            $prep->bindValue(":TYPE",$paramsArr["TYPE"],PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            //throw new jsException($ex);
        }
    }
}
