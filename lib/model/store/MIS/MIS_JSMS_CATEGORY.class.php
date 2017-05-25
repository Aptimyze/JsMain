<?php
//This class is used to check conversion on new/old jsms site
class MIS_JSMS_CATEGORY extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function insertRecord($whichSite,$uagent, $forced)
	{
		if($whichSite>=0 && $uagent && $forced>=0)
		{
			try
			{
				$sql = "INSERT INTO MIS.JSMS_CATEGORY(SITE,AGENT,FORCED,TIME) VALUES(:site,:agent,:forced,now())";
				$res = $this->db->prepare($sql);
				$res->bindValue(":site", $whichSite, PDO::PARAM_INT);
				$res->bindValue(":forced", $forced, PDO::PARAM_INT);
				$res->bindValue(":agent", $uagent, PDO::PARAM_STR);
				$res->execute();

			}
			catch(PDOException $e)
        	        {
                	        throw new jsException($e);
                	}	
		}
	}
}
?>
