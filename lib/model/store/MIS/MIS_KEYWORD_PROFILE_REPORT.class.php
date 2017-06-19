<?php
//This class is used to execute queries on MIS.KEYWORD_PROFILE_REPORT table
class MIS_KEYWORD_PROFILE_REPORT extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	public function selectRecord($date)
	{
		if(!$date)
                        throw new jsException("","error in KEYWORD_PROFILE_REPORT store ");

		try
		{
			$sql = "Select Source,Sec_Source, `Group`, Adnetwork, Account, Campaign, Adgroup, Keyword, `Match`, LMD, Entry_Date, Profileid, Activated, Age, Gender, Character_Length, Posted_By, Photo, Country, City, Community, Income, n_sum, Incomplete from MIS.KEYWORD_PROFILE_REPORT where Entry_Date=:date";
			$res = $this->db->prepare($sql);
            $res->bindValue(":date", $date, PDO::PARAM_STR);
            $res->execute();
            while($rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC))
            {
				$row[]=$rowSelectDetail;
			}
			return $row;

		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
