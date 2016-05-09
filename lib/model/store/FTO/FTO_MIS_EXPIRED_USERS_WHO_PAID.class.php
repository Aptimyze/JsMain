<?php
//This class is used to execute queries on FTO.MIS_LAST_WEEK_LOGIN table
class FTO_MIS_EXPIRED_USERS_WHO_PAID extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array having gender,mtongue,fto state and their correspounding counts
	*/
	public function insertRecords($dateVal,$dataArr)
	{
		if(!$dateVal || !$dataArr)
                        throw new jsException("","dateVal or dataArr IS BLANK IN function insertRecords() OF FTO_MIS_EXPIRED_USERS_WHO_PAID.class.php ");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				$paramArr[] = "(:DATE,:NO_OF_WEEKS".$i.",:NO_OF_USERS".$i.")";
				$i++;
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_EXPIRED_USERS_WHO_PAID (DATE,NO_OF_WEEKS,NO_OF_USERS) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
                                $res->bindValue(":NO_OF_WEEKS".$i,$k, PDO::PARAM_INT);
                                $res->bindValue(":NO_OF_USERS".$i,$v, PDO::PARAM_INT);
				$i++;
                        }
                        $res->bindValue(":DATE", $dateVal, PDO::PARAM_STR);
                        $res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
