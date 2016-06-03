<?php
/**
 * This table is used to store entries of all profiles that are under screening. These entries are deleted once the screening is done.
**/
class MAIN_ADMIN extends TABLE
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
                $flag=$paramArr["FLAG"];
                $interface=$paramArr["INTERFACE"];
                $name=$paramArr["NAME"];
                if($paramArr["SOURCE"]==PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        $where="jsadmin.MAIN_ADMIN.PROFILEID=:PROFILEID";
                else
                        $where="PHOTOSCREEN=0 AND SKIP_FLAG<>'Y' AND HAVEPHOTO=:FLAG";
                
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql = "SELECT jsadmin.MAIN_ADMIN.PROFILEID,jsadmin.MAIN_ADMIN.USERNAME,ALLOT_TIME,SUBMIT_TIME from jsadmin.MAIN_ADMIN,newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID AND STATUS = '".$paramArr["STATUS"]."' AND ALLOTED_TO=:NAME AND jsadmin.MAIN_ADMIN.SCREENING_TYPE='P' AND jsadmin.MAIN_ADMIN.SUBMITED_TIME='0000-00-00 00:00:00' AND ".$where." AND ALLOT_TIME >= '".$paramArr["minTimeToAllot"]."' LIMIT 1";
		$res=$this->db->prepare($sql);
		if($paramArr["SOURCE"]==PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        $res->bindValue(":PROFILEID", $paramArr["PROFILEID"], PDO::PARAM_STR);
                else
                        $res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
                $res->bindValue(":NAME", $name, PDO::PARAM_STR);
		$res->execute();
                
//die;
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
                $flag=$paramArr["FLAG"];
                $interface=$paramArr["INTERFACE"];
                
                if($paramArr["SOURCE"]==PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        $where="jsadmin.MAIN_ADMIN.PROFILEID=:PROFILEID AND  ALLOT_TIME > '".$paramArr["minTimeToAllot"]."'";
                else
                        $where="PHOTOSCREEN=0 AND SKIP_FLAG<>'Y' AND HAVEPHOTO=:FLAG AND  ALLOT_TIME < '".$paramArr["minTimeToAllot"]."'";
                
                $time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql = "SELECT jsadmin.MAIN_ADMIN.PROFILEID FROM jsadmin.MAIN_ADMIN,newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID AND STATUS = '".$paramArr["STATUS"]."' AND SCREENING_TYPE='P' AND ".$where." ORDER BY RECEIVE_TIME ASC LIMIT 1";
		$res=$this->db->prepare($sql);
		if($paramArr["SOURCE"]==PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        $res->bindValue(":PROFILEID", $paramArr["PROFILEID"], PDO::PARAM_INT);
                else
                        $res->bindValue(":FLAG", $flag, PDO::PARAM_STR);//print_r($sql);die;
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
                $bitStatus = ProfilePicturesTypeEnum::$SCREEN_BITS;
                
                $flag=$paramArr["FLAG"];
                $interface=$paramArr["INTERFACE"];
                if($interface==ProfilePicturesTypeEnum::$INTERFACE["1"])
		{
			$tableQuery = "SELECT PROFILEID, ORDERING, UPDATED_TIMESTAMP,SCREEN_BIT, GROUP_CONCAT(IF(ORDERING='0',IF((CHAR_LENGTH(SCREEN_BIT)>2 AND SCREEN_BIT NOT LIKE '$bitStatus[FACE]%') OR OriginalPicUrl='', 0, IF(CHAR_LENGTH(SCREEN_BIT)=1, '1144444', SCREEN_BIT)), IF(OriginalPicUrl='',0,IF(CHAR_LENGTH(SCREEN_BIT)>2, SUBSTRING( SCREEN_BIT, 2, 1 ), SCREEN_BIT) )) ORDER BY ORDERING ASC SEPARATOR ' ') AS BITS FROM PICTURE_FOR_SCREEN_NEW GROUP BY PROFILEID HAVING (SCREEN_BIT NOT IN ('0000000','0100000') AND BITS NOT LIKE '%0%' AND ((BITS LIKE '$bitStatus[FACE]%$bitStatus[FACE]%' AND ORDERING=0) OR (BITS LIKE '%$bitStatus[FACE]%' AND ORDERING!=0)))";
			//NMIT ISSUE $tableQuery = "SELECT PROFILEID, ORDERING, UPDATED_TIMESTAMP, GROUP_CONCAT(IF(ORDERING='0',IF((CHAR_LENGTH(SCREEN_BIT)>2 AND SCREEN_BIT NOT LIKE '$bitStatus[FACE]%') OR OriginalPicUrl='', 0, IF(CHAR_LENGTH(SCREEN_BIT)=1, '1144444', SCREEN_BIT)), IF(OriginalPicUrl='',0,IF(CHAR_LENGTH(SCREEN_BIT)>2, SUBSTRING( SCREEN_BIT, 2, 1 ), SCREEN_BIT) )) ORDER BY ORDERING ASC SEPARATOR ' ') AS BITS FROM PICTURE_FOR_SCREEN_NEW GROUP BY PROFILEID HAVING (BITS NOT LIKE '%0%' AND ((BITS LIKE '$bitStatus[FACE]%$bitStatus[FACE]%' AND ORDERING=0) OR ((BITS='1' OR SUBSTRING(BITS,2,1)='1') AND ORDERING!=0)))";
                        //$where="(((BITS LIKE '%".$bitStatus["RESIZE"]."%' AND BITS NOT LIKE '%".$bitStatus["DEFAULT"]."%') AND ORDERING!=0) OR ((BITS LIKE '".$bitStatus["FACE"]."%".$bitStatus["RESIZE"]."%' AND BITS NOT LIKE '".$bitStatus["FACE"]."%".$bitStatus["DEFAULT"]."%')  AND ORDERING=0))";
                	//$tableQuery="SELECT *,GROUP_CONCAT(DISTINCT CASE WHEN (SCREEN_BIT=".$paramArr["noOperationPerformed"]." AND OriginalPicUrl !='' AND ORDERING!=0) THEN 1 WHEN (CHAR_LENGTH(SCREEN_BIT) >1 AND ORDERING !=0) THEN SUBSTRING( SCREEN_BIT, 2, 1 ) ELSE SCREEN_BIT END ORDER BY ORDERING ASC) AS BITS FROM (SELECT * FROM newjs.PICTURE_FOR_SCREEN_NEW ORDER BY ORDERING ASC) AS A GROUP BY PROFILEID HAVING ".$where."";
		}
                elseif($interface==ProfilePicturesTypeEnum::$INTERFACE["2"])
		{
			$tableQuery = "SELECT PROFILEID, UPDATED_TIMESTAMP,SCREEN_BIT, GROUP_CONCAT(IF(ORDERING='0',IF(SCREEN_BIT NOT LIKE '$bitStatus[FACE]%' OR SCREEN_BIT LIKE '$bitStatus[FACE]%$bitStatus[FACE]%' OR OriginalPicUrl='', 0, SCREEN_BIT), IF(OriginalPicUrl='' OR SCREEN_BIT LIKE '%$bitStatus[DEFAULT]%',0,IF(CHAR_LENGTH(SCREEN_BIT)>2, SUBSTRING( SCREEN_BIT, 2, 1 ), SCREEN_BIT)) ) ORDER BY ORDERING ASC SEPARATOR ' ') AS BITS FROM PICTURE_FOR_SCREEN_NEW GROUP BY PROFILEID HAVING (BITS NOT LIKE '%0%' AND BITS LIKE '%$bitStatus[EDIT]%')";
                        //$where="(BITS LIKE '%".$bitStatus["EDIT"]."%' AND (BITS NOT LIKE '%".$bitStatus["FACE"]."%' AND  BITS NOT LIKE '%".$bitStatus["DEFAULT"]."%') AND ORDERING!=0) OR (BITS LIKE '".$bitStatus["FACE"]."%".$bitStatus["EDIT"]."%' AND (BITS NOT LIKE '".$bitStatus["FACE"]."%".$bitStatus["FACE"]."%' AND BITS NOT LIKE '".$bitStatus["FACE"]."%".$bitStatus["DEFAULT"]."%') AND ORDERING=0)";
                	//$tableQuery="SELECT *,GROUP_CONCAT(DISTINCT CASE WHEN (SCREEN_BIT=".$paramArr["noOperationPerformed"]." AND OriginalPicUrl !='' AND ORDERING!=0) THEN 1 WHEN (CHAR_LENGTH(SCREEN_BIT) >1 AND ORDERING !=0) THEN SUBSTRING( SCREEN_BIT, 2, 1 ) ELSE SCREEN_BIT END ORDER BY ORDERING ASC) AS BITS FROM (SELECT * FROM newjs.PICTURE_FOR_SCREEN_NEW ORDER BY ORDERING ASC) AS A GROUP BY PROFILEID HAVING ".$where."";
		}
                if($paramArr["SOURCE"]!=PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        $whereCond = "J.PHOTOSCREEN =0 AND J.HAVEPHOTO =  :FLAG AND P.SCREEN_BIT NOT IN ('0000000','0100000') ";
                $time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql = "SELECT DISTINCT J.PROFILEID AS PROFILEID, J.USERNAME AS USERNAME, J.PHOTODATE AS PHOTODATE FROM ((newjs.JPROFILE J INNER JOIN (".$tableQuery.") AS P ON J.PROFILEID = P.PROFILEID) LEFT JOIN jsadmin.MAIN_ADMIN M ON J.PROFILEID = M.PROFILEID AND M.SCREENING_TYPE =  :SCREENING_TYPE) WHERE M.PROFILEID IS NULL AND ".$whereCond." AND J.PHOTODATE < '".$paramArr["minTimeToAllot"]."' ORDER BY P.UPDATED_TIMESTAMP ASC LIMIT 1";
		$res=$this->db->prepare($sql);
		$res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
		$res->bindValue(":SCREENING_TYPE", "P", PDO::PARAM_STR);
                $res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else
			return NULL;
	}

	/**
	 * This function is used to insert the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
	public function allotProfile($paramArr)
	{
                $sql = "INSERT INTO jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, STATUS, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE) values(:PROFILEID,:USERNAME,:RECEIVETIME,:SUBMITTIME,'".$paramArr["STATUS"]."','".date("Y-m-d H:i")."', :NAME,'P')";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $paramArr["NAME"], PDO::PARAM_STR);
		$res->bindValue(":USERNAME", $paramArr["USERNAME"], PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $paramArr["PROFILEID"], PDO::PARAM_INT);
		$res->bindValue(":RECEIVETIME", $paramArr["RECEIVE_TIME"], PDO::PARAM_STR);
		$res->bindValue(":SUBMITTIME", $paramArr["SUBMIT_TIME"], PDO::PARAM_STR);
		$res->execute();
	}
        /**
	 * This function is used to update the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
	public function updateAlreadyAllotedProfile($paramArr)
	{
                $sql = "UPDATE jsadmin.MAIN_ADMIN SET STATUS=:STATUS, ALLOT_TIME='".date("Y-m-d H:i")."', ALLOTED_TO=:NAME WHERE PROFILEID=:PROFILEID AND SCREENING_TYPE='P'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $paramArr["NAME"], PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $paramArr["PROFILEID"], PDO::PARAM_INT);
		$res->bindValue(":STATUS", $paramArr["STATUS"], PDO::PARAM_INT);
		$res->execute();
                if($res->rowCount()>0)
                        return 1;
                else
                        return 0;
	}

	/**
	 * This function is used to update the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
	public function reallotProfile($paramArr)
	{
                $profileid=$paramArr["PROFILEID"];
                $name=$paramArr["NAME"];
                
		$time=time();
		$now=date('Y-m-d H:i:s',$time);
		$sql="UPDATE jsadmin.MAIN_ADMIN set ALLOTED_TO=:NAME, ALLOT_TIME='$now' WHERE PROFILEID=:PROFILEID AND SCREENING_TYPE='P'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $name, PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($res->rowCount()==0)
			return "noRowsUpdated";
	}

	/**
	 * This function is used to delete the entry of a photo profile after screening from the table jsadmin.MAIN_ADMIN.
	**/
	public function deleteEntryAfterScreening($profileid)
	{
		$sql="DELETE FROM jsadmin.MAIN_ADMIN WHERE SCREENING_TYPE='P' AND PROFILEID=:PROFILEID AND SKIP_FLAG='N'" ;
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
	}

	/**
	 * This function is used to return the column RECEIVE_TIME from jsadmin.MAIN_ADMIN for a particular profile under screening.
	**/
	public function getReceiveTime($profileid)
	{
		$sql="SELECT DATE(RECEIVE_TIME) AS RECEIVE_TIME FROM jsadmin.MAIN_ADMIN WHERE PROFILEID=:PROFILEID and SCREENING_TYPE='P'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row['RECEIVE_TIME'];
	}

	/**
	 * This function is used to return the column RECEIVE_TIME from jsadmin.MAIN_ADMIN for a particular profile under screening.
	**/
	public function getAllotTime($profileid)
	{
		$sql="SELECT ALLOT_TIME,ALLOTED_TO FROM jsadmin.MAIN_ADMIN WHERE PROFILEID=:PROFILEID and SCREENING_TYPE='P'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$r[0]=$row['ALLOT_TIME'];
			$r[1]=$row['ALLOTED_TO'];
		}
		if($row)
			return $r;
		else
			return NULL;
	}

	/**
	* This function is used to set the column SKIP to 'Y'.
	**/
	public function skipProfile($profileid,$mailid,$comments)
	{
		$sql = "UPDATE jsadmin.MAIN_ADMIN SET SKIP_FLAG='Y',SKIP_COMMENTS=:COMMENTS where PROFILEID=:PROFILEID and SCREENING_TYPE='P'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":COMMENTS", $comments, PDO::PARAM_STR);
		$res->execute();
	}
        /**
	 * This function is used to unallocate al profiles except one.
	**/
	public function unallocateAlloted($paramArr,$profileId)
	{
                $name=$paramArr["NAME"];
                
                $now=date('Y-m-d H:i:s',$time);
		$sql="UPDATE jsadmin.MAIN_ADMIN set ALLOTED_TO='' WHERE PROFILEID!=:PROFILEID AND ALLOTED_TO=:NAME";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $name, PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
		$res->execute();
		
	}
        /**
	 * This function is used to update the entry of a photo profile under screening in interface2 to interface1.
	**/
	public function switchAlloted($paramArr,$profileId)
	{
                $name=$paramArr["NAME"];
		$time=time();
                $now=date('Y-m-d H:i:s',$time);
		$sql="UPDATE jsadmin.MAIN_ADMIN set STATUS='',ALLOT_TIME='$now' WHERE PROFILEID=:PROFILEID AND SCREENING_TYPE='P' AND ALLOTED_TO=:NAME";
		$res=$this->db->prepare($sql);
		$res->bindValue(":NAME", $name, PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
		$res->execute();
		if($res->rowCount()==0)
			return "noProfileFound";
                else
                        return $profileId;
	}


	/** This function is added by Reshu for selecting details for a profile in jsadmin.MAIN_ADMIN
	*@param whereArray : array
	*@return result
	*/
	public function  getDetails($whereArr,$fields="*")
	{
		try
                {
			foreach($whereArr as $key=>$val)
				$whereCondition[]= $key."=:".$key;
			
			$sql = "SELECT $fields FROM jsadmin.MAIN_ADMIN WHERE ". implode(" AND ",$whereCondition);
			$res= $this->db->prepare($sql);
			foreach($whereArr as $key=>$val)
			{
				switch($key)
				{
					case "PROFILEID":
						$res->bindValue(":PROFILEID", $val, PDO::PARAM_INT);
						break;
					case "SCREENING_TYPE":
						$res->bindValue(":SCREENING_TYPE", $val, PDO::PARAM_STR);
						break;
					case "SKIP_FLAG":
						$res->bindValue(":SKIP_FLAG", $val, PDO::PARAM_STR);
						break;
				}
			}
			$res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				$output[$row["PROFILEID"]] = $row;
			}
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	public function getAllotedTo($profileid)
	{
		$sql="SELECT ALLOTED_TO FROM jsadmin.MAIN_ADMIN WHERE PROFILEID=:PROFILEID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$r['ALLOTED_TO']=$row['ALLOTED_TO'];
		}
		return $r;
	}


}
?>
