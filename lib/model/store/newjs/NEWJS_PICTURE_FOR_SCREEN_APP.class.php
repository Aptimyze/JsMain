<?php
//This class is used to execute queries on newjs.PICTURE_FOR_SCREEN_APP table

class NEWJS_PICTURE_FOR_SCREEN_APP extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


        /**
        This function is used to insert data in PICTURE_FOR_SCREEN_APP table.
        * @param paramArr array contains key value pair for insertion
        * @returns true on sucess
        **/
        public function ins($paramArr=array())
        {
		foreach($paramArr as $key=>$val)
		        ${$key} = $val;
                //Exception handling
                if(!$PROFILEID)
                       throw new jsException("","PROFILEID IS BLANK IN ins() of NEWJS_PICTURE_FOR_SCREEN_APP.class.php");
                //Exception handling

                try
                {
                        $sql = "INSERT INTO newjs.PICTURE_FOR_SCREEN_APP (PROFILEID,MainPicUrl,AlgoPicUrl,SCREENED_PICTUREID) VALUES (:PROFILEID,:MainPicUrl,:AlgoPicUrl,:SCREENED_PICTUREID)";
                        $res = $this->db->prepare($sql);
			$res->bindParam(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			$res->bindParam(":MainPicUrl", $MainPicUrl, PDO::PARAM_STR);
			$res->bindParam(":AlgoPicUrl", $AlgoPicUrl, PDO::PARAM_STR);
			$res->bindParam(":SCREENED_PICTUREID", $SCREENED_PICTUREID, PDO::PARAM_INT);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

	/*
	This function is used to fetch data from newjs.PICTURE_FOR_SCREEN_APP table
	@param - profileid
	@return - resultset
	*/
	public function get($profileid)
	{
		if(!$profileid)
                       throw new jsException("","PROFILEID IS BLANK IN get() of NEWJS_PICTURE_FOR_SCREEN_APP.class.php");

		try
		{
			$sql = "SELECT * FROM newjs.PICTURE_FOR_SCREEN_APP WHERE PROFILEID = :PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $row;
                        }
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
               	return $detailArr;
	}

	/** This function is created by Reshu Rajput. This is used to update data in PICTURE_FOR_SCREEN_APP table.
        * @param paramArr array contains key value pair[ PICTUREID and AlgoPicUrl pair] for insertion
        **/
        public function update($paramArr)
        {
                //Exception handling
                if(!is_array($paramArr))
                       throw new jsException("","Array IS BLANK IN update() of NEWJS_PICTURE_FOR_SCREEN_APP.class.php");
                //Exception handling
                try
                {
                        $sql = "UPDATE newjs.PICTURE_FOR_SCREEN_APP SET AlgoPicUrl = CASE PICTUREID";
			$i=0;
			$pidString="";
                        foreach($paramArr as $id)
                        {
                                $sql.=" WHEN :pid".$i." THEN :url".$i;
				$pidArr[]=":pid".$i;
				$i++;
                        }
			$sql.=" END WHERE PICTUREID IN (".implode(",",$pidArr).")";
                        $res = $this->db->prepare($sql);
			$j=0;
			foreach($paramArr as $id=>$url)
                        {
				$res->bindValue(":pid".$j, $id, PDO::PARAM_INT);
				$res->bindValue(":url".$j, $url, PDO::PARAM_STR);
				$j++;
			}
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

	/** This function is created by Reshu Rajput. This is used to get data from PICTURE_FOR_SCREEN_APP table for executing algo.
        * @return result array contains key value for executing algo
        **/
        public function getDataForAlgo($totalScript,$currentScript,$limit)
        {
                try
                {
                        $sql = "SELECT PICTUREID,PROFILEID,MainPicUrl FROM newjs.PICTURE_FOR_SCREEN_APP WHERE COALESCE(AlgoPicUrl, '') = '' AND PICTUREID%:TOTALSCRIPT=:CURRENTSCRIPT LIMIT 0,:LIMIT ";
			$res = $this->db->prepare($sql);
			$res->bindValue(":TOTALSCRIPT", $totalScript, PDO::PARAM_INT);
                	$res->bindValue(":CURRENTSCRIPT", $currentScript, PDO::PARAM_INT);
			$res->bindValue(":LIMIT", $limit, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $result[$row["PICTUREID"]]["MainPicUrl"] = $row["MainPicUrl"];
				 $result[$row["PICTUREID"]]["PROFILEID"] = $row["PROFILEID"];
                        }
			return $result;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }


	 public function getTempDataForAlgo($page=0)
        {
                try
                {
			$limit = $page*10;
                        $sql = "SELECT PICTUREID,AlgoPicUrl FROM newjs.PICTURE_FOR_SCREEN_APP WHERE AlgoPicUrl LIKE 'JS%' LIMIT ". $limit." , 10";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				$algoPic = $row["AlgoPicUrl"];
                                $result[$row["PICTUREID"]]["MainPicUrl"] = str_replace("mobileAppPic","mainPic",$algoPic);
				$result[$row["PICTUREID"]]["FaceDetected"] = str_replace("mobileAppPic","faceDetected",$algoPic);
                                 $result[$row["PICTUREID"]]["AlgoPicUrl"] = $algoPic;
                        }
                        return $result;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }

	/*
	This function is used to get the latest pictureid corresponding to a profile
	$param - profileid
	$return - pictureid
	*/
	public function getLatestPictureIdForProfile($profileid)
	{
		if(!$profileid)
                       throw new jsException("","PROFILEID IS BLANK IN getLatestPictureIdForProfile() of NEWJS_PICTURE_FOR_SCREEN_APP.class.php");

		try
		{
			$sql = "SELECT PICTUREID FROM newjs.PICTURE_FOR_SCREEN_APP WHERE PROFILEID = :PROFILEID ORDER BY UPDATED_TIMESTAMP DESC LIMIT 1";
			$res = $this->db->prepare($sql);
                        $res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$pictureid = $row["PICTUREID"];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $pictureid;
	}

	/*
	This function is used to delete entries from newjs.PICTURE_FOR_SCREEN_APP table
	@param - profileid and pictureid
	*/
	public function del($profileid,$pictureid)
	{
		if(!$profileid || !$pictureid)
                       throw new jsException("","PROFILEID OR PICTUREID IS BLANK IN del() of NEWJS_PICTURE_FOR_SCREEN_APP.class.php");

                try
                {
                        $sql = "DELETE FROM newjs.PICTURE_FOR_SCREEN_APP WHERE PROFILEID = :PROFILEID AND PICTUREID = :PICTUREID";
                        $res = $this->db->prepare($sql);
                        $res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->bindParam(":PICTUREID", $pictureid, PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/*
	This function is used to get the number of rows in newjs.PICTURE_FOR_SCREEN_APP table
	@return - row count
	*/
	public function getCountFromAppTable()
	{
		try
                {
                        $sql = "SELECT count(*) AS C FROM newjs.PICTURE_FOR_SCREEN_APP";
                        $res = $this->db->prepare($sql);
                        $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
                        $output = $row["C"];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $output;
	}

	/*
	This function inserts legacy profiles to be screened for app photo
	@param - array of profiles
	*/
	public function insertBulkForLegacyProfiles($profileArr)
	{
		if(!$profileArr || !is_array($profileArr))
                       throw new jsException("","PROFILE ARRAY IS BLANK IN insertBulkForLegacyProfiles() of NEWJS_PICTURE_FOR_SCREEN_APP.class.php");

		foreach($profileArr as $k=>$v)
                {
                        $paramArr[] = ":PROFILE".$k;
                }
		try
		{
			$sql = "INSERT INTO newjs.PICTURE_FOR_SCREEN_APP(PROFILEID,MainPicUrl,SCREENED_PICTUREID) SELECT PROFILEID,MainPicUrl,PICTUREID FROM newjs.PICTURE_NEW WHERE ORDERING = :ORDERING AND PROFILEID IN (".implode(",",$paramArr).") AND (MobileAppPicUrl IS NULL OR MobileAppPicUrl = '') ORDER BY FIELD(PROFILEID,".implode(",",$paramArr).")";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":ORDERING", 0, PDO::PARAM_INT);
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
