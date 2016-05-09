<?php
//This class is used to execute queries on FTO.MIS_FTO_EXPIRED_USER_COUNT_ACCEPTANCE table
class FTO_MIS_FTO_EXPIRED_USER_COUNT_ACCEPTANCE extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	* This function inserts records in the table
	* @param - date and an array having gender,acceptnce and their correspounding counts
	*/
	public function insertRecord($date,$dataArr)
	{
		if(!$date || !$dataArr)
                        throw new jsException("","DATE OR DATA ARRAY IS BLANK IN insertRecord() OF FTO_MIS_FTO_EXPIRED_USER_COUNT_ACCEPTANCE.class.php");

		try
		{
			$i=0;
			foreach($dataArr as $k=>$v)
			{
				foreach($v as $kk=>$vv)
				{
					$paramArr[] = "(:DATE,:GENDER".$i.",:ACCEPTANCE".$i.",:COUNT".$i.")";
					$i++;
				}
			}
			$sql = "INSERT IGNORE INTO FTO.MIS_FTO_EXPIRED_USER_COUNT_ACCEPTANCE(DATE,GENDER,ACCEPTANCE,COUNT) VALUES ".implode(",",$paramArr);
			$res = $this->db->prepare($sql);
			$i=0;
                        foreach($dataArr as $k=>$v)
                        {
				foreach($v as $kk=>$vv)
				{
                                	$res->bindValue(":GENDER".$i,$k, PDO::PARAM_STR);
                                	$res->bindValue(":ACCEPTANCE".$i,$kk, PDO::PARAM_INT);
                                	$res->bindValue(":COUNT".$i,$vv, PDO::PARAM_INT);
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
