<?php
//This class is used to execute queries on MIS.NEWSEARCH_PAGEVIEW table
class MIS_HITS extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insertRecord($source,$date,$pagename,$ip)
	{
		if(!$source || !$date || !$pagename || !$ip)
                        throw new jsException("","error in hits ");
		
		try
		{
			$sql = "INSERT INTO MIS.HITS(SourceID,Date,PageName,IPADD) VALUES(:source,:date,:pagename,:ip)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":source", $source, PDO::PARAM_STR);
                        $res->bindValue(":date", $date, PDO::PARAM_STR);
                        $res->bindValue(":pagename", $pagename, PDO::PARAM_STR);
                        $res->bindValue(":ip", $ip, PDO::PARAM_STR);
			$res->execute();

		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
