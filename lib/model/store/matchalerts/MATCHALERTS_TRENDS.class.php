<?php
//This class is used to handle queries on matchalerts.TRENDS table

class MATCHALERTS_TRENDS extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/*
        This function is used to get all data for a profile
        @param - profileid
        @return - result set row
        */
	public function getData($profileId)
	{
		if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN getData() of MATCHALERTS_TRENDS.class.php");

		try
		{
			$sql = "SELECT SQL_CACHE * FROM matchalerts.TRENDS WHERE PROFILEID = :PROFILEID";
			$res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $row;
	}
}
?>
