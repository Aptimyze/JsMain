<?php
class billing_VARIABLE_DISCOUNT_DURATION extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	public function setVdOfferDates($startDate, $endDate)
	{
		try
		{
			$todayDate =date("Y-m-d");
			$sql="insert into billing.VARIABLE_DISCOUNT_DURATION(`SDATE`,`EDATE`,`ENTRY_DT`) VALUES(:SDATE,:EDATE,:ENTRY_DT)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":SDATE", $startDate, PDO::PARAM_STR);
			$prep->bindValue(":EDATE", $endDate, PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT", $todayDate, PDO::PARAM_STR);
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
