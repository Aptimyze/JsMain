<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of incentive_EXCLUSIVE_CLIENT_NOTES
 *
 * @author tushar
 */
class incentive_EXCLUSIVE_CLIENT_NOTES extends TABLE{
    //put your code here
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    
    public function setClientNotes($clientId, $notes) {
        try {
            $sql = "REPLACE INTO incentive.EXCLUSIVE_CLIENT_NOTES "
                    . "(CLIENT_ID, CLIENT_NOTES) VALUES ( :CLIENT_ID,  :CLIENT_NOTES)";
                
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENT_ID", $clientId, PDO::PARAM_INT);
            $res->bindValue(":CLIENT_NOTES", $notes, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getClientNotes($clientId) {
        try {
            $sql = "SELECT CLIENT_NOTES FROM incentive.EXCLUSIVE_CLIENT_NOTES "
                    . "WHERE CLIENT_ID = :CLIENT_ID";
            
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENT_ID", $clientId, PDO::PARAM_INT);
            $res->execute();
            return $res->fetch(PDO::FETCH_ASSOC)["CLIENT_NOTES"];
            
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
}
