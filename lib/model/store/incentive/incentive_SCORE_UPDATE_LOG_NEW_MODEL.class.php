<?php
class incentive_SCORE_UPDATE_LOG_NEW_MODEL extends TABLE{
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }

    /*func getScoreDistribution
    *get score distribution
    *@param : $startDt,$endDt,$model
    */
    public function getScoreDistribution($model,$startDt,$endDt)
    {
        try{
            $sql = "SELECT * from incentive.SCORE_UPDATE_LOG_NEW_MODEL WHERE MODEL = :MODEL AND ENTRY_DT BETWEEN :START_ENTRY_DT AND :END_ENTRY_DT";
            $res = $this->db->prepare($sql);
            $res->bindValue(":MODEL",$model,PDO::PARAM_STR);
            $res->bindValue(":START_ENTRY_DT",$startDt,PDO::PARAM_STR);
            $res->bindValue(":END_ENTRY_DT",$endDt,PDO::PARAM_STR);
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
?>
