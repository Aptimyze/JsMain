<?php
//This class is used to execute queries on MIS_RELATION_EXEC_PAGE table
class MIS_RELATION_EXEC_PAGE extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insertRecord($profileid,$source)
	{
		if(!$profileid)
                        throw new jsException("","error in hits of relation executive page ");
		$date=date("Y-m-d H:i:s");
		try
		{
			$sql = "INSERT INTO MIS.RELATION_EXEC_PAGE(PROFILEID,DATE,SOURCE) VALUES(:profileid,:date,:source)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":source", $source, PDO::PARAM_STR);
                        $res->bindValue(":date", $date, PDO::PARAM_STR);
                        $res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
			$res->execute();

		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
