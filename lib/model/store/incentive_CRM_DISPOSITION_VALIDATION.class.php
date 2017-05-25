<?php
class incentive_DISPOSITION_VALIDATION extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}
	public function getdisValidation($value)
	{
		try
		{
			$sql="SELECT VALIDATION_VALUE,VALIDATION_LABEL from incentive.CRM_DISPOSITION_VALIDATION where DISPOSITION=:DISPOSITION";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DISPOSITION",$value,PDO::PARAM_INT);
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
