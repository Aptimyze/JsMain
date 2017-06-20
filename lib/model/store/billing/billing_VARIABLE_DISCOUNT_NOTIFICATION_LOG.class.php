<?php
class billing_VARIABLE_DISCOUNT_NOTIFICATION_LOG extends TABLE{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function checkVDStatus($entry_dt)
	{
		try
		{
			$sql="SELECT COUNT(1) AS CNT FROM billing.VARIABLE_DISCOUNT_NOTIFICATION_LOG WHERE ENTRY_DT=:ENTRY_DT AND STATUS='Y'";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			if($result['CNT']>0)
			{
				return 1;
			} else {
				return 0;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	public function getFrequencyAndTimes($entry_dt)
	{
		try
		{
			$sql="SELECT FREQUENCY, NO_OF_TIMES FROM billing.VARIABLE_DISCOUNT_NOTIFICATION_LOG WHERE ENTRY_DT=:ENTRY_DT AND STATUS='Y'";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->execute();
			$row=$prep->fetch(PDO::FETCH_ASSOC);
			if(!empty($row)){
				return array($row['FREQUENCY'],$row['NO_OF_TIMES']);
			}
			return;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	public function updateStartTime($entry_dt){
		try
		{	
			$sql="UPDATE billing.VARIABLE_DISCOUNT_NOTIFICATION_LOG SET START_TIME=CURRENT_TIMESTAMP() WHERE ENTRY_DT=:ENTRY_DT AND STATUS='Y'";	
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	public function updateEndTime($entry_dt,$flatCount,$uptoCount){
		try
		{	
			$sql="UPDATE billing.VARIABLE_DISCOUNT_NOTIFICATION_LOG SET END_TIME=CURRENT_TIMESTAMP(), STATUS='N',FLAT_COUNT=:FLAT_COUNT,UPTO_COUNT=:UPTO_COUNT WHERE ENTRY_DT=:ENTRY_DT AND STATUS='Y'";	
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->bindValue(":FLAT_COUNT",$flatCount,PDO::PARAM_INT);
			$prep->bindValue(":UPTO_COUNT",$uptoCount,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	public function updateUptoCount($entry_dt, $upto_count){
		try
		{	
			$sql="UPDATE billing.VARIABLE_DISCOUNT_NOTIFICATION_LOG SET UPTO_COUNT=:UPTO_COUNT WHERE ENTRY_DT=:ENTRY_DT AND STATUS='Y'";	
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->bindValue(":UPTO_COUNT",$upto_count,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	public function updateFlatCount($entry_dt, $flat_count){
		try
		{	
			$sql="UPDATE billing.VARIABLE_DISCOUNT_NOTIFICATION_LOG SET FLAT_COUNT=:FLAT_COUNT WHERE ENTRY_DT=:ENTRY_DT AND STATUS='Y'";	
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
			$prep->bindValue(":FLAT_COUNT",$flat_count,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function insertVdNotificationSchedule($entry_dt_arr, $no_of_times){
		try
		{
			foreach($entry_dt_arr as $k => $entry_dt) {
				$sql="INSERT INTO billing.`VARIABLE_DISCOUNT_NOTIFICATION_LOG` VALUES ('', 'Y', :ENTRY_DT, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0', ".$k.", :NO_OF_TIMES)";	
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
				$prep->bindValue(":NO_OF_TIMES",$no_of_times,PDO::PARAM_INT);
				$prep->execute();				
			}	
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function isStatusY(){
		try
		{
			$sql="SELECT ID FROM billing.`VARIABLE_DISCOUNT_NOTIFICATION_LOG` WHERE STATUS='Y'";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			$res = $prep->fetch(PDO::FETCH_ASSOC);
			if($res)
				return true;
			else
				return false;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
}
?>
