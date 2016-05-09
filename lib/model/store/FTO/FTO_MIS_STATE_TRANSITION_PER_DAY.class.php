<?php
//This class is used to execute queries on FTO.MIS_STATE_TRANSITION_PER_DAY table
class FTO_MIS_STATE_TRANSITION_PER_DAY extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array with data of old and new states and their correspounding counts
	*/
	public function insertRecord($date,$dataArr)
	{
		if(!$date || !$dataArr)
                        throw new jsException("","DATE OR DATA ARRAY IS BLANK IN insertRecord() OF FTO_MIS_STATE_TRANSITION_PER_DAY.class.php");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				$paramArr[] = "(:DATE,:OLD_STATE".$i.",:NEW_STATE".$i.",:COUNT".$i.")";
				$i++;
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_STATE_TRANSITION_PER_DAY(DATE,OLD_STATE,NEW_STATE,COUNT) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
                                $res->bindValue(":OLD_STATE".$i,$v["OLD_STATE"], PDO::PARAM_INT);
                                $res->bindValue(":NEW_STATE".$i,$v["NEW_STATE"], PDO::PARAM_INT);
                                $res->bindValue(":COUNT".$i,$v["COUNT"], PDO::PARAM_INT);
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
