<?php
class incentive_FP_CSV_LOG extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}
	public function insertData($profileid,$entryDt)
	{
		try
		{
			$sql= "INSERT INTO incentive.FP_CSV_LOG(PROFILEID,CSV_ENTRY_DATE) VALUES(:PROFILEID,:CSV_ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":CSV_ENTRY_DATE",$entryDt,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
}
?>
