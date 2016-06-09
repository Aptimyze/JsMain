<?php
class incentive_RCB_LOG extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}
	public function insertData($profileid,$entryDt)
	{
		try
		{
			$sql= "INSERT INTO incentive.RCB_LOG(PROFILEID,ENTRY_DATE) VALUES(:PROFILEID,:ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ENTRY_DATE",$entryDt,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
}
?>
