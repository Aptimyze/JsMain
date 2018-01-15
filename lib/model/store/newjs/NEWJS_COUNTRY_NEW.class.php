<?php
//This class is used to exectue queries on NEWJS_COUNTRY_NEW table

class NEWJS_COUNTRY_NEW extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/*
	This function fetches ID,LABEL,VALUE,ISD_CODE from newjs.COUNTRY_NEW table
	@return - result set array
	*/
	public function getFullTable()
	{
		try
		{
			$sql = "SELECT SQL_CACHE ID,LABEL,VALUE,ISD_CODE FROM newjs.COUNTRY_NEW ORDER BY ALPHA_ORDER";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
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
