<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test_ANALYTICS_SCORE_POOL
 *
 * @author nitish
 */
class test_ANALYTICS_SCORE_POOL extends TABLE {
    
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }
    
    public function getScoreDistribution($model,$startDt="",$endDt=""){
        try{
            $sql = "SELECT * from test.ANALYTIC_SCORE_POOL WHERE MODEL = :MODEL";
            $res = $this->db->prepare($sql);
            $res->bindValue(":MODEL",$model,PDO::PARAM_STR);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
