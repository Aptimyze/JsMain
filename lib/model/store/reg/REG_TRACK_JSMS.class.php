<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of REG_TRACK_JSMS
 *
 * @author Kunal Verma
 * @date 5th Feb 2015
 */
class REG_TRACK_JSMS extends TABLE   {
    //put your code here
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    
    public function isRecordExist($uniqueId)
    {
        if(!$uniqueId)
            return false;
        try{
            $sql = "SELECT * FROM reg.TRACK_JSMS_REG WHERE  UNIQUEID=:UID LIMIT 1";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$pdoStatement->bindValue(':UID',$uniqueId,PDO::PARAM_INT);
			$pdoStatement->execute();
            return $pdoStatement->rowCount();
        } catch (Exception $ex) {
            throw new jsException("","Something went wrong in isRecordExist of REG_TRACK_JSMS.class");
        }
    }
    
    public function insertRecord($uniqueId,$szIp_Add)
    {
        if(!$uniqueId || !is_numeric(intval($uniqueId)))
        {
            throw new jsException("","Unique-id is not numeric in insertRecord OF REG_TRACK_JSMS.class");
        }
        
        try{
            $now = date("Y-m-d H-i-s");
            $sql = "INSERT INTO reg.TRACK_JSMS_REG (`UNIQUEID`,`IP_ADD`,`S0`) VALUES (:UID,:IP_ADD,:VIEW)";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$pdoStatement->bindValue(':UID',$uniqueId,PDO::PARAM_INT);
            $pdoStatement->bindValue(':IP_ADD',$szIp_Add,PDO::PARAM_STR);
            $pdoStatement->bindValue(':VIEW',$now,PDO::PARAM_STR);
			$pdoStatement->execute();
            return $pdoStatement->rowCount();
        } catch (Exception $ex) {
            throw new jsException("","Something went wrong in insertRecord of REG_TRACK_JSMS.class");
        }
    }
    
    public function updateRecord($uniqueId,$view,$profileId=null)
    {
        if(!$uniqueId || !is_numeric(intval($uniqueId)))
        {
            throw new jsException("","Unique-id is not numeric in updateRecord OF REG_TRACK_JSMS.class.php");
        }
        if(!$view || !is_string($view) || !strlen($view))
        {
            throw new jsException("","View is not passed in updateRecord OF REG_TRACK_JSMS.class.php");
        }
        try{
            $now = date("Y-m-d H-i-s");
            $szUpdateCnd = '';
            if($profileId)
                $szUpdateCnd = " ,PROFILEID=:PID ";
            $sql = "UPDATE reg.TRACK_JSMS_REG SET ".$view."=:VIEW".$szUpdateCnd." WHERE UNIQUEID=:UID";
			$pdoStatement = $this->db->prepare($sql);
			
			//Bind Value
			$pdoStatement->bindValue(':UID',$uniqueId,PDO::PARAM_INT);
            $pdoStatement->bindValue(':VIEW',$now,PDO::PARAM_STR);
            if($profileId)
                $pdoStatement->bindValue(':PID',$profileId,PDO::PARAM_INT);
            
			$pdoStatement->execute();
            return $pdoStatement->rowCount();
        } catch (Exception $ex) {
            throw new jsException("","Something went wrong in updateRecord of REG_TRACK_JSMS.class");
        }
    }
}
