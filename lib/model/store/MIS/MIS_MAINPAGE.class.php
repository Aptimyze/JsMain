<?php
//This class is used to execute queries on MIS.LTF table
class MIS_MAINPAGE extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
	public function getMainPageDetails($public='')
	{
		try
		{
			if($public=='Y')
				$sql = "select NAME,MAIN_URL,JUMP_URL,PRIVILEGE from MIS.MIS_MAINPAGE where ACTIVE='Y' AND PUBLIC=:PUBLIC ORDER BY ID";
			else
				$sql = "select NAME,MAIN_URL,JUMP_URL,PRIVILEGE from MIS.MIS_MAINPAGE where ACTIVE='Y' ORDER BY ID";
			$res = $this->db->prepare($sql);
			if($public=='Y')
				$res->bindValue(":PUBLIC", $public, PDO::PARAM_STR);
			$res->execute();
			while($result = $res->fetch(PDO::FETCH_ASSOC))
				$mainPageDetailsArr[] =$result;
			return $mainPageDetailsArr;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
