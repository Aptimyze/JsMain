<?php
class incentive_CRM_DISPOSITION extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}
	public function getDisposition()
	{
		try
		{
			$sql="select *  from incentive.CRM_DISPOSITION where ACTIVE='Y'";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$resultArr[] =$result;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $resultArr;
	}

}
?>
