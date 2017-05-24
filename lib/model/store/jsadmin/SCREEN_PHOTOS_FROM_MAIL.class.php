<?php

/**
 * This table is used to keep a log for the photo profile which is under screening.
 * This includes only those users who have mailed their photos to the address photos@jeevansathi.com.
**/
class SCREEN_PHOTOS_FROM_MAIL extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	 * This function is used to get the list of profiles which have been allotted to a specific screening user and havent been screened yet.
	**/
	public function userAllottedProfiles($paramArr)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="SELECT MAILID,PROFILEID FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL WHERE ASSIGNED_TO=:USER AND STATUS IN ('SCREENING','ASSIGNED') AND SKIP IS NULL AND ALLOTED_DATE >= '".$paramArr["minTimeToAllot"]."'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":USER", $paramArr["NAME"], PDO::PARAM_STR);
		$res->execute();

		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else
			return NULL;
	}

	/**
	 * This function is used to get the list of profiles which have been allotted to some user atleast 30 min back and havent been screened yet.
	**/
	public function allottedProfiles($paramArr)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="SELECT MAILID,PROFILEID FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL WHERE STATUS IN ('SCREENING','ASSIGNED') AND SKIP IS NULL AND ALLOTED_DATE < '".$paramArr["minTimeToAllot"]."'";
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
	public function unallottedProfiles($paramArr)
	{
		$sql="SELECT jsadmin.PHOTOS_FROM_MAIL.SENDER,jsadmin.PHOTOS_FROM_MAIL.SUBJECT,MESSAGE,DATE,jsadmin.PHOTOS_FROM_MAIL.ID FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON jsadmin.PHOTOS_FROM_MAIL.ID = jsadmin.SCREEN_PHOTOS_FROM_MAIL.MAILID WHERE (SCREEN_PHOTOS_FROM_MAIL.MAILID IS NULL) AND ATTACHMENT='Y' ORDER BY DATE";
		$res=$this->db->prepare($sql);
		$res->execute();

		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else
			return NULL;
	}
	
	/**
	 * This function is used to get the list of all the profiles which are under screening and haven't been allotted to any screening user yet.
	**/
	public function getAllUnallottedProfiles($paramArr)
	{
		$sql="SELECT jsadmin.PHOTOS_FROM_MAIL.SENDER,jsadmin.PHOTOS_FROM_MAIL.SUBJECT,MESSAGE,DATE,jsadmin.PHOTOS_FROM_MAIL.ID FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON jsadmin.PHOTOS_FROM_MAIL.ID = jsadmin.SCREEN_PHOTOS_FROM_MAIL.MAILID WHERE (SCREEN_PHOTOS_FROM_MAIL.MAILID IS NULL) AND ATTACHMENT='Y' ORDER BY DATE";
		if(is_array($paramArr) && array_key_exists("LIMIT",$paramArr))
			$sql .= " LIMIT :LIMIT";
		$res=$this->db->prepare($sql);
		
		if(is_array($paramArr) && array_key_exists("LIMIT",$paramArr))
			$res->bindValue(":LIMIT", $paramArr["LIMIT"], PDO::PARAM_INT);
		$res->execute();

		while($row = $res->fetch(PDO::FETCH_ASSOC))
                {
                        $result[] = $row;
                }
                
               	return $result;
	}


	/**
	 * This function is used to insert the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
	public function allotProfile($paramArr)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="INSERT IGNORE INTO jsadmin.SCREEN_PHOTOS_FROM_MAIL(MAILID,ASSIGNED_TO,RECEIVE_DATE,ALLOTED_DATE,SUBMIT_DATE,STATUS,SUBMITED_DATE) values(:MAILID,:NAME,:RECEIVETIME,'$now',:SUBMITTIME,'ASSIGNED','0000-00-00 00:00:00')";
		$res=$this->db->prepare($sql);
		$res->bindValue(":MAILID", $paramArr["MAILID"], PDO::PARAM_INT);
		$res->bindValue(":NAME", $paramArr["NAME"], PDO::PARAM_STR);
		$res->bindValue(":RECEIVETIME", $paramArr["RECEIVE_TIME"], PDO::PARAM_STR);
		$res->bindValue(":SUBMITTIME", $paramArr["SUBMIT_TIME"], PDO::PARAM_STR);
		$res->execute();
	}

	/**
	 * This function is used to update the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
	public function reallotProfile($paramArr)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql = "UPDATE jsadmin.SCREEN_PHOTOS_FROM_MAIL SET ASSIGNED_TO=:NAME,ALLOTED_DATE='$now' WHERE MAILID=:MAILID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $paramArr["NAME"], PDO::PARAM_STR);
		$res->bindValue(":MAILID", $paramArr["MAILID"], PDO::PARAM_INT);
		$res->execute();
	}

	/**
	 * After the screening user submits the username of a profile, this function updates the details of that mail.
	**/
	public function updateScreeningStatus($name,$mailid,$profileid)
	{
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql = "UPDATE jsadmin.SCREEN_PHOTOS_FROM_MAIL SET STATUS='SCREENING',PROFILEID=:PROFILEID WHERE MAILID=:MAILID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":MAILID", $mailid, PDO::PARAM_INT);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
	}

	/**
	 * This function is used to update the status of a profile after its screening has been done.
	**/
	public function logScreeningAction($profileid,$mailid,$approvedPhotoCount,$deletedPhotoCount,$countNA="")
	{
		$status="APPROVED-$approvedPhotoCount,DELETED-$deletedPhotoCount";
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		if($approvedPhotoCount!=0 || $deletedPhotoCount!=0 || $countNA!=0)
		{
			$sql = "UPDATE jsadmin.SCREEN_PHOTOS_FROM_MAIL SET STATUS=:STATUS,SKIP='',SUBMITED_DATE='$now' WHERE PROFILEID=:PROFILEID AND MAILID=:MAILID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":MAILID", $mailid, PDO::PARAM_INT);
			$res->bindValue(":STATUS", $status, PDO::PARAM_STR);
			$res->execute();
		}
	}

	/**
	 * This function is used to return the column RECEIVE_TIME from jsadmin.SCREEN_PHOTOS_FROM_MAIL for a particular profile under screening.
	**/
	public function getReceiveTime($profileid,$mailid)
	{
		$sql = "SELECT RECEIVE_DATE FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL WHERE PROFILEID=:PROFILEID AND MAILID=:MAILID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":MAILID", $mailid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row['RECEIVE_DATE'];
	}

	/**
	 * This function is used to set the column SKIP to 'Y'.In new implementation this is changed to delete
	**/
	public function skipProfile($profileid,$mailid,$comments)
	{
		$sql = "UPDATE jsadmin.SCREEN_PHOTOS_FROM_MAIL SET STATUS='DELETED',SKIP='Y',SKIP_COMMENTS=:COMMENTS WHERE COALESCE(PROFILEID, '') = :PROFILEID AND MAILID=:MAILID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":MAILID", $mailid, PDO::PARAM_INT);
		$res->bindValue(":COMMENTS", $comments, PDO::PARAM_STR);
		$res->execute();
	}

}

?>
