<?php
//This class is used to execute queries on FTO.MIS_LAST_WEEK_LOGIN table
class FTO_MIS_LAST_WEEK_LOGIN extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array having gender,mtongue,fto state and their correspounding counts
	*/
	public function insertRecord($date,$dataArr)
	{
		if(!$date || !$dataArr)
                        throw new jsException("","DATE OR DATA ARRAY IS BLANK IN insertRecord() OF FTO_MIS_LAST_WEEK_LOGIN.class.php");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				$paramArr[] = "(:DATE,:GENDER".$i.",:MTONGUE".$i.",:STATE_ID".$i.",:COUNT".$i.")";
				$i++;
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_LAST_WEEK_LOGIN(WEEK_ENDING_DATE,GENDER,MTONGUE,STATE_ID,COUNT) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
                                $res->bindValue(":GENDER".$i,$v["GENDER"], PDO::PARAM_INT);
                                $res->bindValue(":MTONGUE".$i,$v["MTONGUE"], PDO::PARAM_INT);
                                $res->bindValue(":STATE_ID".$i,$v["STATE_ID"], PDO::PARAM_INT);
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
