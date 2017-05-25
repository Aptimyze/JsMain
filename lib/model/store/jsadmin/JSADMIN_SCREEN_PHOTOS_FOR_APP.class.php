<?php
/**
This class is used to execute queries on jsadmin.SCREEN_PHOTOS_FOR_APP table
**/
class JSADMIN_SCREEN_PHOTOS_FOR_APP extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	 * This function is used to get the list of profiles which have been allotted to a specific screening user and havent been screened yet.
	**/
	public function userAllottedProfiles($flag,$user)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="SELECT PROFILEID FROM jsadmin.SCREEN_PHOTOS_FOR_APP WHERE ALLOTED_TO=:USER AND STATUS IN ('SCREENING','ASSIGNED') AND ALLOTED_DATE >= DATE_SUB('$now', INTERVAL ".sfConfig::get("app_allot_time")." MINUTE) LIMIT 1";
		$res=$this->db->prepare($sql);
		$res->bindValue(":USER", $user, PDO::PARAM_STR);
		$res->execute();

		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else
			return NULL;
	}

	/**
	 * This function is used to get the list of profiles which have been allotted to some user atleast 30 min back and havent been screened yet.
	**/
	public function allottedProfiles($flag)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="SELECT PROFILEID FROM jsadmin.SCREEN_PHOTOS_FOR_APP WHERE STATUS IN ('SCREENING','ASSIGNED') AND ALLOTED_DATE < DATE_SUB('$now', INTERVAL ".sfConfig::get("app_allot_time")." MINUTE) LIMIT 1";
		$res=$this->db->prepare($sql);
		$res->execute();

		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else
			return NULL;
	}

	/**
	 * This function is used to get the list of profiles which are under screening and haven't been allotted to any screening user yet.
	**/
	public function unallottedProfiles($flag)
	{
		$sql="SELECT P.PROFILEID AS PROFILEID,J.USERNAME AS USERNAME,P.UPDATED_TIMESTAMP AS PHOTODATE FROM ((newjs.PICTURE_FOR_SCREEN_APP P LEFT JOIN jsadmin.SCREEN_PHOTOS_FOR_APP S ON P.PROFILEID = S.PROFILEID) INNER JOIN newjs.JPROFILE J ON P.PROFILEID = J.PROFILEID) WHERE S.PROFILEID IS NULL AND P.AlgoPicUrl IS NOT NULL ORDER BY P.UPDATED_TIMESTAMP LIMIT 1";
		$res=$this->db->prepare($sql);
		$res->execute();

		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else
			return NULL;
	}

	/**
	 * This function is used to insert the entry of a photo profile under screening in the table jsadmin.SCREEN_PHOTOS_FOR_APP and assign it to a screening user.
	**/
	public function allotProfile($profileid,$username,$receivetime,$submittime,$name)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="INSERT IGNORE INTO jsadmin.SCREEN_PHOTOS_FOR_APP(ALLOTED_TO,RECEIVE_DATE,ALLOTED_DATE,SUBMIT_DATE,STATUS,PROFILEID,USERNAME,SUBMITED_DATE) values(:NAME,:RECEIVETIME,:ALLOTED_DATE,:SUBMITTIME,:STATUS,:PROFILEID,:USERNAME,:SUBMITTED_DATE)";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $name, PDO::PARAM_STR);
		$res->bindValue(":RECEIVETIME", $receivetime, PDO::PARAM_STR);
		$res->bindValue(":SUBMITTIME", $submittime, PDO::PARAM_STR);
		$res->bindValue(":ALLOTED_DATE", $now, PDO::PARAM_STR);
		$res->bindValue(":STATUS", "ASSIGNED", PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":USERNAME", $username, PDO::PARAM_STR);
		$res->bindValue(":SUBMITTED_DATE", "0000-00-00 00:00:00", PDO::PARAM_STR);
		$res->execute();
	}

	/**
	 * This function is used to update the entry of a photo profile under screening in the table jsadmin.SCREEN_PHOTOS_FOR_APP and assign it to a screening user.
	**/
	public function reallotProfile($name,$profileid)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql = "UPDATE jsadmin.SCREEN_PHOTOS_FOR_APP SET ALLOTED_TO=:NAME,ALLOTED_DATE=:ALLOTED_DATE WHERE PROFILEID=:PROFILEID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $name, PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":ALLOTED_DATE", $now, PDO::PARAM_STR);
		$res->execute();
	}

	/**
	 * This function is used to return the column RECEIVE_TIME from jsadmin.SCREEN_PHOTOS_FROM_MAIL for a particular profile under screening.
	**/
	public function getReceiveTime($profileid)
	{
		$sql = "SELECT RECEIVE_DATE FROM jsadmin.SCREEN_PHOTOS_FOR_APP WHERE PROFILEID=:PROFILEID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row['RECEIVE_DATE'];
	}

	/**
         * This function is used to delete the entry of a photo profile after screening from the table jsadmin.SCREEN_PHOTOS_FOR_APP.
        **/
        public function deleteEntryAfterScreening($profileid)
        {
                $sql="DELETE FROM jsadmin.SCREEN_PHOTOS_FOR_APP WHERE PROFILEID=:PROFILEID" ;
                $res=$this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->execute();
        }
}

?>
