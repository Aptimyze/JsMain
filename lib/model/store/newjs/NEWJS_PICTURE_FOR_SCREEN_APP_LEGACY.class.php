<?php
class NEWJS_PICTURE_FOR_SCREEN_APP_LEGACY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches profile from newjs.PICTURE_FOR_SCREEN_APP_LEGACY which are not present in newjs.PICTURE_FOR_SCREEN_APP and status as N
	@param - query limit
	@return - array of profiles
        */
	public function getProfiles($limit)
	{
		if(!$limit)
			throw new jsException("","LIMIT IS BLANK IN getProfiles() of NEWJS_PICTURE_FOR_SCREEN_APP_LEGACY.class.php");
		
		try
                {
                	$sql="SELECT P1.PROFILEID AS PROFILEID FROM newjs.PICTURE_FOR_SCREEN_APP_LEGACY P1 LEFT JOIN newjs.PICTURE_FOR_SCREEN_APP P2 ON P1.PROFILEID = P2.PROFILEID WHERE P2.PROFILEID IS NULL AND P1.STATUS = :STATUS ORDER BY P1.LAST_LOGIN_DT DESC LIMIT :LIMIT";
                	$res=$this->db->prepare($sql);
			$res->bindValue(":STATUS", "N", PDO::PARAM_STR);
			$res->bindValue(":LIMIT", $limit, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $profileArr[] = $row["PROFILEID"];
                        }
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $profileArr;
        }

	/*
	This function updates the status as Y for a given set of profiles
	$param - array of profiles
	*/
	public function updateStatus($profileArr)
	{
		if(!$profileArr || !is_array($profileArr))
                       throw new jsException("","PROFILE ARRAY IS BLANK IN updateStatus() of NEWJS_PICTURE_FOR_SCREEN_APP_LEGACY.class.php");

		foreach($profileArr as $k=>$v)
		{
			$paramArr[] = ":PROFILE".$k;
		}
		try
		{
			$sql = "UPDATE newjs.PICTURE_FOR_SCREEN_APP_LEGACY SET STATUS = :STATUS WHERE PROFILEID IN (".implode(",",$paramArr).")";
			$res = $this->db->prepare($sql);
			$res->bindValue(":STATUS", "Y", PDO::PARAM_STR);
			foreach($profileArr as $k=>$v)
                	{
                        	$res->bindValue(":PROFILE".$k, $v, PDO::PARAM_INT);
                	}
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
