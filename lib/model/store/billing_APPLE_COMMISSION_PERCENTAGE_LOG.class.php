<?php

class billing_APPLE_COMMISSION_PERCENTAGE_LOG extends TABLE
{
		/**
		 * @fn __construct
		 * @brief Constructor function
		 * @param $dbName - Database to which the connection would be made
		*/

		public function __construct($dbname="")
		{
		parent::__construct($dbname);
		}

	public function getActiveAppleCommissionPercentage($date)
	{
		try
		{
			$sql = 'SELECT PERCENTAGE FROM billing.APPLE_COMMISSION_PERCENTAGE_LOG WHERE ENTRY_DT<=:END ORDER BY ENTRY_DT DESC LIMIT 1';
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":END", $date, PDO::PARAM_STR);
			$prep->execute();
			$row = $prep->fetch(PDO::FETCH_ASSOC);
			$perc = $row['PERCENTAGE'];
			return $perc;
		}
		catch(PDOException $e){
			throw new jsException($e);
		}
	}

	public function addNewCommissionPercentage($perc, $newDate)
	{
		try
		{
			$sql = 'INSERT INTO billing.APPLE_COMMISSION_PERCENTAGE_LOG VALUES (:PERC, :NEWDATE)';
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PERC", $perc, PDO::PARAM_INT);
			$prep->bindValue(":NEWDATE", $newDate, PDO::PARAM_STR);
			$prep->execute();

		}
		catch(PDOException $e){
			throw new jsException($e);
		}
	}

}
?>
