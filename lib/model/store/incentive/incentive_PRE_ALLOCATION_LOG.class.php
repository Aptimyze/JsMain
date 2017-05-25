<?php

class incentive_PRE_ALLOCATION_LOG extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function getProfileIdsScoreForDate($entryDt){
        try{
            $sql="SELECT PROFILEID,SCORE,ALLOTED_TO FROM incentive.PRE_ALLOCATION_LOG WHERE ALLOT_DT = :ALLOT_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ALLOT_DT", $entryDt, PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profilesArr[$result["PROFILEID"]] = $result;
            }
            return $profilesArr;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
