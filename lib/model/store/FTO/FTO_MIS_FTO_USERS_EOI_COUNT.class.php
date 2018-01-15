<?php
//This class is used to execute queries on FTO.MIS_FTO_USERS_EOI_COUNT table
class FTO_MIS_FTO_USERS_EOI_COUNT extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array having gender,eoi_range and no of users in correspounding range.
	*/
    public function insertRecord($date,$dataArr)
	{
		if(!$date || !$dataArr)
                        throw new jsException("","DATE OR DATA ARRAY IS BLANK IN insertRecord() OF FTO_MIS_FTO_USERS_EOI_COUNT.class.php");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				foreach($v as $kk=>$vv)
				{
					$paramArr[] = "(:DATE,:GENDER".$i.",:EOI_RANGE".$i.",:NO_OF_USERS".$i.")";
					$i++;
				}
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_FTO_USERS_EOI_COUNT(DATE,GENDER,EOI_RANGE,NO_OF_USERS) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
            foreach($dataArr as $k=>$v)
            {
				foreach($v as $kk=>$vv)
				{
                                	$res->bindValue(":GENDER".$i,$k, PDO::PARAM_STR);
                                	$res->bindValue(":EOI_RANGE".$i,$kk, PDO::PARAM_INT);
                                	$res->bindValue(":NO_OF_USERS".$i,$vv, PDO::PARAM_INT);
					$i++;
				}
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
