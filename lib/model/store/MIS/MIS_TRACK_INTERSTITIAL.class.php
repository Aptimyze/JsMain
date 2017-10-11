<?php
//This class is used to execute queries on MIS.LTF table
class MIS_TRACK_INTERSTITIAL extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insert($date,$pid)
	{
		if(!$date)
                        throw new jsException("","agents blank passed in");
		try
		{
			$sql = "insert into MIS.TRACK_INTERSTITIAL(DATE,PID) values(:date,:pid)";
			$res = $this->db->prepare($sql);
			$res->bindParam(":date", $date, PDO::PARAM_STR);
			$res->bindParam(":pid", $pid, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return 1;
	}
}
?>
