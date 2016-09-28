<?php
class incentive_SCORE_UPDATE_LOG_NEW_MODEL extends TABLE{
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }

    /*func getScoreDistribution
    *get score distribution
    *@param : $startDt,$endDt,$scoreRange=array("start"=>0,"end"=>0,"increment"=>1),$model
    */
    public function getScoreDistribution($startDt,$endDt,$scoreRange=array("start"=>0,"end"=>0,"increment"=>1),$model="")
    {
        try
        {
        	if($scoreRange["start"] < $scoreRange["end"]){
        		$initial = $scoreRange["start"];
        		$final = $scoreRange["start"]+$scoreRange["increment"];
        		$ifStr = "SELECT MODEL,IF(SCORE < 0 ,(NO SCORE)";
    			for($i =$scoreRange["start"];$i<=$scoreRange["end"];$i+=$scoreRange["increment"]){
        			$initial = $i;
        			$final = $i+$scoreRange["increment"];
        			$ifStr .= ",IF(SCORE >= ".$initial." AND SCORE <= ".$final.","."(".$initial."-".$final.")";
    			}
        		$ifStr .= ",IF(SCORE > ".$final.",(MORE THAN ".$final." :INVALID))) AS SCORE,COUNT(*) AS PROFILE COUNT";
				$sql = $ifStr." WHERE ENTRY_DT BETWEEN :START_ENTRY_DT AND :END_ENTRY_DT";
				if($model != ""){
					$sql .= " AND MODEL = :MODEL";
				}
				$sql .= " GROUP BY SCORE";
				echo "\n".$sql."\n";die;
				$res=$this->db->prepare($sql);
				$res->bindValue(":START_ENTRY_DT",$startDt,PDO::PARAM_STR);
				$res->bindValue(":END_ENTRY_DT",$endDt,PDO::PARAM_STR);
				if($model != ""){
					$res->bindValue(":MODEL",$model,PDO::PARAM_STR);
				}
				$res->execute();
				$i=0;
				while($row=$res->fetch(PDO::FETCH_ASSOC))
					$output[$i++] = $row;
				if($output && is_array($output)){
					return $output;
				}
				else{
					return null;
				}
			}
			else{
				return null;
			}
        }
        catch(PDOException $e)
        {
                throw new jsException($e);
        }
    }
}
?>
