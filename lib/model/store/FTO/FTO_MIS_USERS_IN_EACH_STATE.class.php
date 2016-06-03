<?php
//This class is used to execute queries on FTO.MIS_USERS_IN_EACH_STATE table
class FTO_MIS_USERS_IN_EACH_STATE extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array having state and their correspounding counts and count of converted paid users
	*/
	public function insertRecord($date,$dataArr)
	{
		if(!$date || !$dataArr)
                        throw new jsException("","DATE OR DATA ARRAY IS BLANK IN insertRecord() OF FTO_MIS_USERS_IN_EACH_STATE.class.php");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				$paramArr[] = "(:DATE,:STATE_ID".$i.",:TOTAL_USERS".$i.",:PAID_USERS".$i.")";
				$i++;
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_USERS_IN_EACH_STATE(DATE,STATE_ID,TOTAL_USERS,TOTAL_USERS_WHO_PAID) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
                                $res->bindValue(":STATE_ID".$i,$v["STATE_ID"], PDO::PARAM_INT);
                                $res->bindValue(":TOTAL_USERS".$i,$v["C"], PDO::PARAM_INT);
				if(!$v["PAID_COUNT"])
					$v["PAID_COUNT"] = 0;
                                $res->bindValue(":PAID_USERS".$i,$v["PAID_COUNT"], PDO::PARAM_INT);
				$i++;
                        }
                        $res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
