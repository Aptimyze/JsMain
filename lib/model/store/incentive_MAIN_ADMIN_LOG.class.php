<?php
class incentive_MAIN_ADMIN_LOG extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function replaceProfile($profileid)
	{
		try
		{
			$sql= "REPLACE INTO incentive.MAIN_ADMIN_LOG (ID,PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT ID,PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			$rows_affected=$prep->rowCount();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
		return $rows_affected;

	}

	public function insertProfile($profileid)
	{
		try
		{
			$sql= "INSERT INTO incentive.MAIN_ADMIN_LOG (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			$rows_affected=$prep->rowCount();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
		return $rows_affected;

	}

	public function deleteOnId()
	{
		 try
                 {
                         $sql= "delete a.* FROM incentive.MAIN_ADMIN_LOG a, incentive.MAIN_ADMIN b WHERE a.ID = b.ID";
                         $prep = $this->db->prepare($sql);
                         $prep->execute();
 
                 }
                 catch(Exception $e)
                 {
                         throw new jsException($e);
                 }

	}
	public function replaceForSubMethod($deAllMethodObj)
	{
		try
		{
			$sql = "REPLACE INTO incentive.MAIN_ADMIN_LOG (ID,PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT m.ID,m.PROFILEID,m.ALLOT_TIME,m.CLAIM_TIME,m.ALLOTED_TO,m.STATUS,m.ALTERNATE_NO,m.FOLLOWUP_TIME,m.MODE,m.CONVINCE_TIME,m.COMMENTS,m.RES_NO,m.MOB_NO,m.EMAIL,m.WILL_PAY,m.TIMES_TRIED,m.ORDERS,m.REASON FROM incentive.MAIN_ADMIN m, incentive.CRM_DAILY_ALLOT a WHERE DATE_ADD(m.ALLOT_TIME, INTERVAL a.RELAX_DAYS DAY) < DATE_SUB(CURDATE(), INTERVAL :MAX_DAYS DAY) AND m.STATUS <>'P' AND a.PROFILEID=m.PROFILEID AND a.ALLOT_TIME=m.ALLOT_TIME AND m.ALLOTED_TO NOT IN (:EXECUTIVES)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":MAX_DAYS",$deAllMethodObj->getMaxDays(),PDO::PARAM_STR);
                        $prep->bindValue(":EXECUTIVES",$deAllMethodObj->getExecutives(),PDO::PARAM_STR);
                        $r=$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $r;
	}
	
	public function replcaeForOutbound($deAllMethodObj)
	{
		try
		{
			$sql = "REPLACE INTO incentive.MAIN_ADMIN_LOG (ID,PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT ID,PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN WHERE PROFILEID=:PID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PID",$deAllMethodObj->getProfile(),PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException();
		}
	}	
}
?>
