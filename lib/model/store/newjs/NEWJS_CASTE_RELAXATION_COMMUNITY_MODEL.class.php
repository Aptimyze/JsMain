<?php
/*This class is used to handle the queries to newjs.CASTE_RELAXATION_COMMUNITY_MODEL table */
class NEWJS_CASTE_RELAXATION_COMMUNITY_MODEL extends TABLE
{ 
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches the relaxed castes for a given caste
	@param - comma separated caste values
	@return - array of relaxed caste values or null
	*/
	public function getRelaxedCasteList($caste)
	{
		if(!$caste)
			throw new jsException("","CASTE IS BLANK IN getRelaxedCasteList() OF NEWJS_CASTE_RELAXATION_COMMUNITY_MODEL.class.php");

		try
		{
			$casteArr = explode(",",$caste);
			foreach($casteArr as $k=>$v)
			{
				$paramArr[] = ":CASTE".$k;
			}
			$sql = "SELECT SQL_CACHE DISTINCT RELAXED_CASTE FROM newjs.CASTE_RELAXATION_COMMUNITY_MODEL WHERE CASTE IN (".implode(",",$paramArr).")";
			$res = $this->db->prepare($sql);
			foreach($casteArr as $k=>$v)
			{
                        	$res->bindValue($paramArr[$k], $v, PDO::PARAM_INT);
			}
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row["RELAXED_CASTE"];
                        }
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $output;
	}
}
?>
