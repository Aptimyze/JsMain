<?php
//This class is used to execute queries on MIS.LTF table
class MIS_LTF extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function getLtfProfilesForAgent($agentsStr)
	{
		if(!$agentsStr)
                        throw new jsException("","agents blank passed in");
		try
		{
			$sql = "select distinct PROFILEID from MIS.LTF where EXECUTIVE IN($agentsStr) AND TYPE='ACT'";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($result = $res->fetch(PDO::FETCH_ASSOC))
				$profilesArr[] =$result['PROFILEID'];
			return $profilesArr;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
