<?php
class CRM_DAILY_ALLOT_TRACK extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        public function insertTrackAllocationEntry($paramArr)
        {
                try
                {
                        foreach($paramArr as $key=>$val)
                                ${$key} = $val;

                       if(!$RELAX_DAYS)
                                $RELAX_DAYS =0;
                        if(!$ALLOCATION_DAYS)
                                $ALLOCATION_DAYS =0;

                        $sql = "INSERT INTO incentive.CRM_DAILY_ALLOT_TRACK (PROFILEID,ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT,RELAX_DAYS,ALLOCATION_DAYS) VALUES (:PROFILEID,:ALLOTED_TO,:ALLOT_TIME,:DE_ALLOCATION_DT,:RELAX_DAYS,:ALLOCATION_DAYS)";
                        $res = $this->db->prepare($sql);

                        $res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_STR);
                        $res->bindValue(":ALLOTED_TO", $ALLOTED_TO, PDO::PARAM_STR);
                        $res->bindValue(":ALLOT_TIME", $ALLOT_TIME, PDO::PARAM_STR);
                        $res->bindValue(":DE_ALLOCATION_DT", $DE_ALLOCATION_DT, PDO::PARAM_STR);
                        $res->bindValue(":RELAX_DAYS", $RELAX_DAYS, PDO::PARAM_INT);
                        $res->bindValue(":ALLOCATION_DAYS", $ALLOCATION_DAYS, PDO::PARAM_INT);
                        $res->execute();
                        return true;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function updateDeallocationDt($profileid,$executive,$deAllocationDt)
        {
                try
                {
                        $sql = "UPDATE incentive.CRM_DAILY_ALLOT_TRACK SET REAL_DE_ALLOCATION_DT=:REAL_DE_ALLOCATION_DT WHERE PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO ORDER BY ID DESC LIMIT 1";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $res->bindValue(":ALLOTED_TO",$executive,PDO::PARAM_STR);
                        $res->bindValue(":REAL_DE_ALLOCATION_DT",$deAllocationDt,PDO::PARAM_STR);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }

        public function getAgentAllotedProfileArray($agent, $start_date, $end_date){

		$agentAllotedProfileArray = array();

		try{
			$sql = "SELECT PROFILEID, ALLOT_TIME, DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE ALLOT_TIME>=:DATE1 AND ALLOT_TIME<=:DATE2 AND ALLOTED_TO IN(:AGENT) AND DE_ALLOCATION_DT <> '0000-00-00' ORDER BY ALLOT_TIME ASC";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DATE1", $start_date, PDO::PARAM_STR);
			$prep->bindValue(":DATE2", $end_date, PDO::PARAM_STR);
			$prep->bindValue(":AGENT", $agent, PDO::PARAM_STR);
			$prep->execute();
			while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
				$agentAllotedProfileArray[] = array('PROFILEID'=>$row['PROFILEID'], 'ALLOT_TIME'=>$row['ALLOT_TIME'], 'DE_ALLOCATION_DT'=>($row['DE_ALLOCATION_DT']." 23:59:59"));
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}

		return $agentAllotedProfileArray;
	}
        /*
        This function returns the ALLOT_TIME and DE_ALLOCATION_DT corresponding a profileid assigned to a username
        @param - username and profileid
        @return - result set array
        */
        public function getAllocationDates($username,$profileid)
        {       
                if(!$username || !$profileid)
                        throw new jsException("","USERNAME OR PROFILEID IS BLANK IN getValidAllocationForVisitDone() of incentive_CRM_DAILY_ALLOT_TRACK.class.php");

                try
                {
                        $sql = "SELECT ALLOT_TIME,DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE PROFILEID = :PROFILEID AND ALLOTED_TO = :ALLOTED_TO ORDER BY ID DESC LIMIT 1";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":ALLOTED_TO",$username,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $result;
        }
        public function getAllotedAgentToTransaction($profileid, $billing_dt)
        {
                try{
                        $sql = "SELECT ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE ALLOT_TIME<=:BILLING_DT AND IF(REAL_DE_ALLOCATION_DT, REAL_DE_ALLOCATION_DT, DE_ALLOCATION_DT)>=:BILLING_DT AND PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $prep->bindValue(":BILLING_DT", $billing_dt, PDO::PARAM_STR);
                        $prep->execute();
                        $row=$prep->fetch(PDO::FETCH_ASSOC);
                        $res = $row['ALLOTED_TO'];
                }
                catch(Exception $e){
                        throw new jsException($e);
                }

                return $res;
        }
} 
