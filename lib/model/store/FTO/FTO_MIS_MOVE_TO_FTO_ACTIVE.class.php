<?php
//This class is used to execute queries on FTO.MIS_MOVE_TO_FTO_ACTIVE table
class FTO_MIS_MOVE_TO_FTO_ACTIVE extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array having days and their correspounding counts
	*/
	public function insertRecord($date,$dataArr)
	{
		if(!$date || !$dataArr)
                        throw new jsException("","DATE OR DATA ARRAY IS BLANK IN insertRecord() OF FTO_MIS_MOVE_TO_FTO_ACTIVE.class.php");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				$paramArr[] = "(:DATE,:DAYS".$i.",:COUNT".$i.")";
				$i++;
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_MOVE_TO_FTO_ACTIVE(DATE,GAP_DAYS,COUNT) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
                                $res->bindValue(":DAYS".$i,$v["DAYS"], PDO::PARAM_INT);
                                $res->bindValue(":COUNT".$i,$v["C"], PDO::PARAM_INT);
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
