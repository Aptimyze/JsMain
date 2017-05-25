<?php
class FTO_MIS_FIRST_PHOTO_UPLOAD extends TABLE
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
                        throw new jsException("","dateVal or dataArr IS BLANK IN function insertRecords() OF FTO_MIS_FIRST_PHOTO_UPLOAD.class.php ");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				$paramArr[] = "(:DATE,:NO_OF_DAYS".$i.",:NO_OF_USERS".$i.")";
				$i++;
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_FIRST_PHOTO_UPLOAD(DATE,NO_OF_DAYS,NO_OF_USERS) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
                                $res->bindValue(":NO_OF_DAYS".$i,$k, PDO::PARAM_INT);
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
