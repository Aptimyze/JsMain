<?php
class billing_VARIABLE_DISCOUNT_DURATION extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	public function setVdOfferDates($startDate, $endDate,$scheduleDate)
	{
		try
		{
			$todayDate =date("Y-m-d");
			$status ='Y';
			$sql="insert into billing.VARIABLE_DISCOUNT_DURATION(`SDATE`,`EDATE`,`ENTRY_DT`,`SCHEDULE_DATE`,`STATUS`) VALUES(:SDATE,:EDATE,:ENTRY_DT,:SCHEDULE_DATE,:STATUS)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":SDATE", $startDate, PDO::PARAM_STR);
			$prep->bindValue(":EDATE", $endDate, PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT", $todayDate, PDO::PARAM_STR);
			$prep->bindValue(":SCHEDULE_DATE", $scheduleDate, PDO::PARAM_STR);
			$prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
			$prep->execute();
			return true;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
        public function getVdOfferDates()
        {
                try
                {
                        $sql="select * from billing.VARIABLE_DISCOUNT_DURATION ORDER BY ENTRY_DT DESC LIMIT 1";
                        $res=$this->db->prepare($sql);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

}	
?>
