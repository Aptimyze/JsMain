<?php
/*
This class is used to send query to ANALYTICS_VARIABLE_DISCOUNT table in newjs database
*/
class newjs_ANALYTICS_VARIABLE_DISCOUNT extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	
	public function getSlabForProfile($profileid)
	{
		if(!$profileid)
			throw new jsException("","PROFILEID IS BLANK IN getSlabForProfile() OF newjs_ANALYTICS_VARIABLE_DISCOUNT.class.php");

		try
		{
			$sql = "SELECT SLAB  FROM newjs.ANALYTICS_VARIABLE_DISCOUNT WHERE PROFILEID = :PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$res = $result['SLAB'];
		}
		catch(Exception $e)
		{
				throw new jsException($e);
		}
		return $res;
	}	
}
