<?php

class ABUSE_PHONE extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	public function getAbusiveStatus($phone)
	{
		try	 	
		{	
			$sql = "SELECT PHONE_WITH_STD from newjs.ABUSIVE_PHONE WHERE PHONE_WITH_STD=:PHONE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PHONE",$phone,PDO::PARAM_INT);
            		$prep->execute();
	            	if($row=$prep->fetch(PDO::FETCH_ASSOC)){
				return true;
			}
			return false;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}
}

?>

