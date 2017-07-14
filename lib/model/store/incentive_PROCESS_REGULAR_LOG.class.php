<?php
class incentive_PROCESS_REGULAR_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	public function insertCount($date,$filter,$filteredProfiles=0,$count=0,$max_dt,$latest_reg_filtered=0,$latest_reg_cnt=0, $process)
        {
                try
                {
                        $sql = "INSERT INTO incentive.PROCESS_REGULAR_LOG (DATE,FILTER,FILTERED_PROFILES,COUNT,LATEST_REG_DT,LATEST_REG_FILTERED_PROFILES,LATEST_REG_COUNT, PROCESS) VALUES(:DATE,:FILTER,:FILTERED_PROFILES,:COUNT,:LATEST_REG_DT,:LATEST_REG_FILTERED_PROFILES,:LATEST_REG_COUNT,:PROCESS)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
                        $prep->bindValue(":FILTER",$filter,PDO::PARAM_STR);
                        $prep->bindValue(":FILTERED_PROFILES",$filteredProfiles,PDO::PARAM_INT);
                        $prep->bindValue(":COUNT",$count,PDO::PARAM_INT);
                        $prep->bindValue(":LATEST_REG_DT",$max_dt,PDO::PARAM_STR);
                        $prep->bindValue(":LATEST_REG_FILTERED_PROFILES",$latest_reg_filtered,PDO::PARAM_INT);
                        $prep->bindValue(":LATEST_REG_COUNT",$latest_reg_cnt,PDO::PARAM_INT);
                        $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function getLatestDate($process)
        {
                try
                {
			$sql = 'SELECT MAX(`DATE`) AS MAX_DATE FROM incentive.`PROCESS_REGULAR_LOG`';
                        if($process){
                            $sql .= " WHERE PROCESS =:PROCESS";
                        }
                        $prep = $this->db->prepare($sql);
                        if($process){
                            $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        }
                        $prep->execute();
	                $row=$prep->fetch(PDO::FETCH_ASSOC);
			$res = $row['MAX_DATE'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $res;
        }
	public function getAllDataForGivenDate($date,$process)
        {
                try
                {
			$sql = 'SELECT * FROM incentive.`PROCESS_REGULAR_LOG` WHERE `DATE` = :DATE and PROCESS = :PROCESS ORDER BY `ID` ASC';
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
                        $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        $prep->execute();
	                while($row=$prep->fetch(PDO::FETCH_ASSOC))
                               $res[]=$row;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $res;
        }
}
?>
