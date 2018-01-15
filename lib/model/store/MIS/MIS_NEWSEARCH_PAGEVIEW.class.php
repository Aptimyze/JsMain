<?php
//This class is used to execute queries on MIS.NEWSEARCH_PAGEVIEW table
class MIS_NEWSEARCH_PAGEVIEW extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insertRecord($pageNo,$sid)
	{

//JSM-881---temp stop
return 1;
		if(!$pageNo || !$sid)
                        throw new jsException("","PAGE NO OR SEARCH ID IS BLANK IN insertRecord() OF MIS_NEWSEARCH_PAGEVIEW.class.php");
		
		try
		{
			$sql = "INSERT INTO MIS.NEWSEARCH_PAGEVIEW(DATE,SEARCH_ID,PAGE_NO) VALUES (NOW(),:SID,:PAGE)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":SID", $sid, PDO::PARAM_INT);
                        $res->bindValue(":PAGE", $pageNo, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
