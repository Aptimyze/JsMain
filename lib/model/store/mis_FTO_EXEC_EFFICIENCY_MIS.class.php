<?php
class FTO_EXEC_EFFICIENCY_MIS extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	public function fetchProfiles($allotedTo,$incentiveEligible,$st_date,$end_date)
	{
		try
		{
			if($incentiveEligible=='Y')
				$time="FTO_INCENTIVE_DT";
			else
				$time="ALLOT_TIME";
			$sql="SELECT * FROM MIS.FTO_EXEC_EFFICIENCY_MIS WHERE EXECUTIVE=:ALLOTED_TO AND $time > :START_TIME AND $time<:END_TIME ORDER BY ALLOT_TIME ASC";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
			$prep->bindValue(":START_TIME",$st_date." 00:00:00",PDO::PARAM_STR);
			$prep->bindValue(":END_TIME",$end_date." 23:59:59",PDO::PARAM_STR);
			$prep->execute();
			$i=0;
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
        			$dataArr[$i]['PROFILEID'] = $result['PROFILEID'];
        			$dataArr[$i]['EXECUTIVE']=$result['EXECUTIVE'];
				$dataArr[$i]['PHOTO_DT']=$result['PHOTO_DT'];
				$dataArr[$i]['ALLOT_TIME']=$result['ALLOT_TIME'];
				$dataArr[$i]['PHONE_VERIFY_DT']=$result['PHONE_VERIFY_DT'];
				$dataArr[$i]['FTO_OFFER_DT']=$result['FTO_OFFER_DT'];
				$dataArr[$i]['FTO_ACTIVATION_DT']=$result['FTO_ACTIVATION_DT'];
				$dataArr[$i]['FTO_INCENTIVE_DT']=$result['FTO_INCENTIVE_DT'];
				$dataArr[$i]['FIRST_EOI_DT']=$result['FIRST_EOI_DT'];
				$dataArr[$i]['DEALLOCATION_DT']=$result['DEALLOCATION_DT'];
				$i++;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $dataArr;
	}
	public function getMaxDateValue($profileid,$column,$allotTime)
	{
		try
		{
			$sql="SELECT $column FROM MIS.FTO_EXEC_EFFICIENCY_MIS WHERE PROFILEID=:PROFILEID AND ALLOT_TIME <=:ALLOT_TIME ORDER BY $column DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->bindValue(":ALLOT_TIME",$allotTime,PDO::PARAM_STR);
                        $prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				return $result[$column];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function getProfilesAllottedInDateRange($stDt,$endDt,$agents)
        {
                try
                {
                        $count = count($agents);
                        $in_params = trim(str_repeat('?, ', $count), ', ');
                        $sql="SELECT COUNT(*) AS ALLOCATIONS,DATE(ALLOT_TIME) AS ALLOT_DT FROM MIS.FTO_EXEC_EFFICIENCY_MIS WHERE ALLOT_TIME >'$stDt' AND ALLOT_TIME < '$endDt' AND EXECUTIVE IN({$in_params}) GROUP BY ALLOT_DT";
                        $prep=$this->db->prepare($sql);
                        $prep->execute($agents);
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $allotCount[$result['ALLOT_DT']]=$result['ALLOCATIONS'];
                        }
                }
                catch(Exception $e)
                {
                         throw new jsException($e);
                }
                return $allotCount;
        }
	public function getProfilesActivatedInDateRange($stDt,$endDt)
        {
                try
                {
                        $sql="SELECT PROFILEID,FTO_ACTIVATION_DT FROM MIS.FTO_EXEC_EFFICIENCY_MIS WHERE FTO_ACTIVATION_DT> :START_DATE AND FTO_ACTIVATION_DT < :END_DATE";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$stDt,PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDt,PDO::PARAM_STR);
                        $res->execute();
                        while($result=$res->fetch(PDO::FETCH_ASSOC))
                        {
                                $activateDate=date("Y-m-d",JSstrToTime($result['FTO_ACTIVATION_DATE']));
                                $profiles[$activateDate][]=$result['PROFILEID'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
	public function getProcessEfficiency($stDt,$endDt,$agents)
	{
		try
                {
                        $sql=" SELECT count(*) AS ACTIVATIONS, DATE(ALLOT_TIME) AS ALLOT_DT,DATEDIFF(FTO_ACTIVATION_DT,ALLOT_TIME) AS DAY FROM MIS.FTO_EXEC_EFFICIENCY_MIS WHERE ALLOT_TIME>:START_DATE AND ALLOT_TIME <:END_DATE  GROUP BY ALLOT_DT,DAY";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$stDt,PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDt,PDO::PARAM_STR);
                        $res->execute();
                        while($result=$res->fetch(PDO::FETCH_ASSOC))
                        {
                                $conversionCount[$result['ALLOT_DT']][$result['DAY']]=$result['ACTIVATIONS'];
				$conversionCount[$result['ALLOT_DT']]['ALLOCATIONS']+=$result['ACTIVATIONS'];
				if($result['DAY']!="")
					$conversionCount[$result['ALLOT_DT']]['ACTIVATIONS']+=$result['ACTIVATIONS'];
				$conversionOnDay[$result['DAY']]+=$result['ACTIVATIONS'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return array($conversionCount,$conversionOnDay);
	}
}	
?>
