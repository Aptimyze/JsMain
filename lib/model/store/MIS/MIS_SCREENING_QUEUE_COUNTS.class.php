<?php
//This class is used to execute queries on MIS.NEWSEARCH_PAGEVIEW table
class MIS_SCREENING_QUEUE_COUNTS extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insertRecord($arr,$hr,$date)
	{
		if(!is_array($arr)||!$hr)
                        throw new jsException("","not data send in screening queue count ");
		
		try
		{
			$sql = "REPLACE INTO MIS.SCREENING_QUEUE_COUNTS(`DATE` ,  `AT_HOUR` ,  `PROFILE_NEW` ,  `PROFILE_EDIT` ,  `PHOTO_ACCEPT_REJ_NEW` ,  `PHOTO_ACCEPT_REJ_EDIT` ,  `PHOTO_PROCESS_NEW` , `PHOTO_PROCESS_EDIT`) VALUES
(:DATE ,  :AT_HOUR ,  :PROFILE_NEW ,  :PROFILE_EDIT ,  :PHOTO_ACCEPT_REJ_NEW ,  :PHOTO_ACCEPT_REJ_EDIT ,  :PHOTO_PROCESS_NEW , :PHOTO_PROCESS_EDIT)";
			$res = $this->db->prepare($sql);
			foreach($arr as $k=>$v)
				$res->bindValue(":".$k, $v);
                        $res->bindValue(":AT_HOUR", $hr, PDO::PARAM_INT);
                        $res->bindValue(":DATE",$date,PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function getRecords($date)
	{
		try
		{
			$sql = "SELECT * FROM MIS.SCREENING_QUEUE_COUNTS WHERE DATE>=:DATE ORDER BY DATE,AT_HOUR ASC";
			$res = $this->db->prepare($sql);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res->execute();
			while($ress=$res->fetch(PDO::FETCH_ASSOC))
				$result[] = $ress;
			return $result;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
