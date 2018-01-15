<?php
class PICTURE_NEW extends TABLE
{
	private $validSet;
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
                 $this->validSet = array_merge(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS, Array("TITLE","KEYWORD","PICTUREID","ORDERING","PROFILEID","PICFORMAT"));

        }

	//Three function for innodb transactions
	public function startTransaction()
	{
		$this->db->beginTransaction();
	}
	public function commitTransaction()
	{
		$this->db->commit();
	}

	public function rollbackTransaction()
	{
		$this->db->rollback();
	}
	//Three function for innodb transactions


        /**
        This function is used to get pictures info (profile pic/album) from screened table (PICTURE_NEW table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array picture(s) info. Return null in case of no matching rows found.
        **/
	public function get($paramArr=array())
	{
		foreach($paramArr as $key=>$val)
                	${$key} = $val;

                if(!@$PROFILEID && !@$PICTUREID)
                        throw new jsException("","PROFILEID & PICTUREID IS BLANK IN get() of PICTURE_NEW.class.php");
                try
		{
			$fields="*";
			$detailArr='';
			$fields = $fields?$fields:$this->getFields();
                        $sql = "SELECT $fields FROM newjs.PICTURE_NEW WHERE ";
			if(@$PROFILEID)
				$sql.="PROFILEID = :PROFILEID";
                        if(@$PICTUREID)
                        {
                                if($PROFILEID)
                                        $sql.=" AND ";
                                $sql.="PICTUREID = :PICTUREID";
                        }

			if(@$ORDERING || @$ORDERING=='0')
				$sql.=" AND ORDERING= :ORDERING";

                        $res = $this->db->prepare($sql);
			if(@$PROFILEID)
                        	$res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			if(@$PICTUREID)
                        	$res->bindValue(":PICTUREID", $PICTUREID, PDO::PARAM_INT);
			if(@$ORDERING || @$ORDERING=='0')
                        	$res->bindValue(":ORDERING", $ORDERING, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $row;
                        }
                        return $detailArr;
                }
                catch(PDOException $e)
		{
                        throw new jsException($e);
                }
                return NULL;
	}
	
        /**
        This function is used to edit/alter pictures info (profile pic/album) of screened table (PICTURE_NEW table).
        * @param paramArr array contains where condition on basis of which entry will be fetched.
        * @param pictureId int update is only only by pictureId.
        * @returns true on sucess
        **/
        public function edit($paramArr=array(),$pictureId,$profileId)
	{
		try 
		{
			foreach($paramArr as $key=>$val)
			{
				if(in_array($key,$this->validSet))
					$set[] = $key." = :".$key;
                        }
                        if(count($set)<1){
                                return true;
                        }
                        $setValues = implode(",",$set);

                        $sql = "UPDATE newjs.PICTURE_NEW SET $setValues WHERE PICTUREID = :PICTUREID AND PROFILEID = :PROFILEID";
                        $res = $this->db->prepare($sql);
                        foreach($paramArr as $key=>$val)
			{
				if(in_array($key,$this->validSet))
					$res->bindValue(":".$key, $val);
                        }
                        $res->bindValue(":PICTUREID",$pictureId,PDO::PARAM_INT);
			$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
                        throw new jsException($e);
		}
        }

        /**
        This function is used to insert pictures info (profile pic/album) of screened table (PICTURE_NEW table).
        * @param paramArr array contains key value pair for insertion
        * @returns true on sucess
        **/
        public function ins($paramArr=array())
        {
		foreach($paramArr as $key=>$val)
		{
			if(in_array($key,$this->validSet))
			{
				$set[] = ":".$key;
				$fieldsSet[]= $key;
				${$key} = $val;
			}
		}
		$setValues = implode(",",$set);
                $fieldsSetString = implode(",",$fieldsSet);
		
                try
                {
                        $sql = "REPLACE INTO newjs.PICTURE_NEW ($fieldsSetString) VALUES ($setValues)";
                        $res = $this->db->prepare($sql);
                        $pdoIntSet = array("PROFILEID","PICTUREID","ORDERING");
			foreach($fieldsSet as $index=>$field)
			{
				$pdoType = in_array($field,$pdoIntSet)?PDO::PARAM_INT:PDO::PARAM_STR;
				$res->bindParam(":".$field, ${$field},$pdoType);
			}
                        
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }


        /**
        This function is used to delete a photo from PICTURE_NEW table.
	If PICTUREID is found then it deletes photo based on PICTUREID as its a primary key else a combination of PROFILEID & ORDERING (a unique key) is used to delete        the entry.
        * @param  paramArr array contains where condition on basis of which entry will get deleted.
        * @return rowCount int numbers of rows get deleted.
        **/
        public function del($paramArr=array())
        {
                $PICTUREID=@$paramArr["PICTUREID"];
                $PROFILEID=@$paramArr["PROFILEID"];
                $ORDERING=@$paramArr["ORDERING"];

                $sql = "DELETE FROM newjs.PICTURE_NEW WHERE";
                if($PROFILEID && $PICTUREID)
			$sql.=" PICTUREID = :PICTUREID AND PROFILEID = :PROFILEID";
		elseif($PROFILEID && $ORDERING)
			$sql.=" PROFILEID = :PROFILEID AND ORDERING = :ORDERING";
                $res = $this->db->prepare($sql);
                if($PROFILEID && $PICTUREID)
		{
                        $res->bindParam("PICTUREID", $PICTUREID, PDO::PARAM_INT);
                        $res->bindParam("PROFILEID", $PROFILEID, PDO::PARAM_INT);
		}
                elseif($PROFILEID && $ORDERING)
                {       
                        $res->bindParam("PROFILEID", $PROFILEID, PDO::PARAM_INT);
                        $res->bindParam("ORDERING", $ORDERING, PDO::PARAM_STR);
                }
                $res->execute();
		return $res->rowCount();
        }

	public function	updateOrdering($paramArr=array())
	{
                $PROFILEID=$paramArr["PROFILEID"];
                $ORDERING=$paramArr["ORDERING"];

		$sql = "UPDATE newjs.PICTURE_NEW SET ORDERING=ORDERING-1 WHERE PROFILEID = :PROFILEID ";
		if(isset($ORDERING))
		{
			$sql.= " AND ORDERING > :ORDERING";
                        $sql.=" ORDER BY ORDERING ASC";//TEST
		}
			
		$res = $this->db->prepare($sql);
		foreach($paramArr as $key=>$val)
		{
			$res->bindValue(":".$key, $val);
		}
		$res->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
		if(isset($ORDERING))
			$res->bindValue(":ORDERING",$ORDERING,PDO::PARAM_INT);
		$res->execute();
	}

        /**
        This function is used to get maximum ordering in the table for a particular profileId in order to check next available ordering value.
        * @param  profileId int unique value for which records need to be fetched.
        * @return MAX int maximum ordering of the table 
        **/
	public function getMaxOrdering($profileId)
	{
                if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN getMaxOrdering() of PICTURE_NEW.class.php");

		$sql = "SELECT MAX(ORDERING) as MAX FROM newjs.PICTURE_NEW WHERE PROFILEID = :PROFILEID ";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['MAX'];
	}

	public function  pictureidExist($picId,$pid)
	{
		$sql="SELECT COUNT(*) C FROM newjs.PICTURE_NEW WHERE PICTUREID = :PICTUREID ";
		if($pid)
			$sql.=" AND PROFILEID =:PROFILEID";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PICTUREID",$picId,PDO::PARAM_INT);
		if($pid)
			$res->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['C'];
	}

	public function deleteRowsBasedOnPicId($picIdArr)
	{ 
                if(!is_array($picIdArr)){
                        $picIdArrString = str_replace("\'","",$picIdArr);
                        $picIdArray = explode(",",$picIdArrString);
                }
                else
                        $picIdArray=$picIdArr;
                for($i=0;$i<sizeof($picIdArray) ;$i++)
                {
                        $picIds.=":PICTUREID$i";
                        if($i != (sizeof($picIdArray)-1))
                                $picIds.=',';
                }
		$sql = "DELETE FROM newjs.PICTURE_NEW WHERE PICTUREID IN ($picIds)";
		$res = $this->db->prepare($sql);
		for($i=0;$i<sizeof($picIdArray) ;$i++)
                { 
                        $res->bindValue(":PICTUREID$i", $picIdArray[$i], PDO::PARAM_INT);
                }

		$res->execute();
	}

	public function updateScreenedPhotosOrdering($profileid)
	{
		$sql="SELECT PICTUREID FROM newjs.PICTURE_NEW WHERE PROFILEID=:PROFILEID";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$picId[]=$row['PICTUREID'];
		}

		if (count($picId)>0)
		{
			$idStr=implode(",",$picId);
			$sql2="UPDATE newjs.PICTURE_NEW SET ORDERING = CASE PICTUREID";
			$i=0;
			foreach($picId as $id)
			{
				$sql2.=" WHEN $id THEN ".$i++;
			}
			$sql2.=" END WHERE PICTUREID IN ($idStr) ORDER BY FIELD( PICTUREID, $idStr)";
			$res2 = $this->db->prepare($sql2);
			$res2->execute();
		}
	}

        /**
        This function is used to make bulk entry in the PICTURE_NEW table when the screening of a particular profile is successful.
        * @param  profileid,picId array, title array, keywords array and first ordering for insertion. 
        * @return Success if entries made successfully else the error message.
        **/
	public function insertBulkScreen($profileId,$picId,$title,$keywords,$ins_ordering,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$MobileAppPicUrl='',$ProfilePic120Url,$ProfilePic235Url,$ProfilePic450Url,$OriginalPicUrl,$PicFormat,$SearchPicUrl)
	{
		try
		{
			$sql = "INSERT INTO newjs.PICTURE_NEW (PICTUREID,PROFILEID,ORDERING,TITLE,KEYWORD,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,PICFORMAT,MobileAppPicUrl ,ProfilePic120Url,ProfilePic235Url,ProfilePic450Url,OriginalPicUrl,SearchPicUrl) VALUES ";
			for ($i=0;$i<count($picId);$i++)
			{
				$param[] = "(:PICTUREID".$i.", :PROFILEID".$i.", :ORDERING".$i.", :TITLE".$i.", :KEYWORD".$i.", :MainPicUrl".$i.", :ProfilePicUrl".$i.", :ThumbailUrl".$i.", :Thumbail96Url".$i.", :PICFORMAT".$i.", :MOBILEAppPicURL".$i.", :PIC120URL".$i.", :PIC235URL".$i.", :PIC450URL".$i.", :ORIGINALPICURL".$i.", :SearchPicUrl".$i.")";
			}
			$paramStr = implode(",",$param);
			$sql = $sql.$paramStr;
			$res = $this->db->prepare($sql);
			for ($i=0;$i<count($picId);$i++)
			{
				$orderingValue[] = $ins_ordering+$i;
                        	$res->bindParam(":PROFILEID".$i, $profileId, PDO::PARAM_INT);
                        	$res->bindParam(":PICTUREID".$i, $picId[$i], PDO::PARAM_INT);
                        	$res->bindParam(":ORDERING".$i, $orderingValue[$i], PDO::PARAM_INT);
                        	$res->bindParam(":KEYWORD".$i, $keywords[$i], PDO::PARAM_STR);
                        	$res->bindParam(":TITLE".$i, $title[$i] , PDO::PARAM_STR);
				$res->bindParam(":MainPicUrl".$i, $MainPicUrl[$i], PDO::PARAM_STR);
                        	$res->bindParam(":ProfilePicUrl".$i, $ProfilePicUrl[$i], PDO::PARAM_STR);
                        	$res->bindParam(":ThumbailUrl".$i, $ThumbailUrl[$i], PDO::PARAM_STR);
                        	$res->bindParam(":Thumbail96Url".$i, $Thumbail96Url[$i], PDO::PARAM_STR);
                        	$res->bindParam(":MOBILEAppPicURL".$i, $MobileAppPicUrl[$i], PDO::PARAM_STR);
                        	$res->bindParam(":PIC120URL".$i, $ProfilePic120Url[$i], PDO::PARAM_STR);
                        	$res->bindParam(":PIC235URL".$i, $ProfilePic235Url[$i], PDO::PARAM_STR);
                        	$res->bindParam(":PIC450URL".$i, $ProfilePic450Url[$i], PDO::PARAM_STR);
                                $res->bindParam(":ORIGINALPICURL".$i, $OriginalPicUrl[$i], PDO::PARAM_STR);
                        	$res->bindParam(":PICFORMAT".$i, $PicFormat[$i], PDO::PARAM_STR);
				$res->bindParam(":SearchPicUrl".$i, $SearchPicUrl[$i], PDO::PARAM_STR);
                        }
			$res->execute();
			return "Success";
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	
        /**
        This function is used to make bulk update the screening titles.
        * @param  picid array of screen pics and corresponding title arr
        * @return Success if update successfully else the error message.
        **/
	public function updateScreenTitles($picId,$screenTitleArr)
	{
		try
		{
			$sql="UPDATE newjs.PICTURE_NEW SET TITLE = CASE PICTUREID";
                	$idStr=implode(",",$picId);

                	foreach($picId as $k=>$v)
                	{
                        	$sql.=" WHEN ".$v." THEN :TITLE".$k;
                	}
			$sql.=" END, UNSCREENED_TITLE = CASE PICTUREID";
			foreach($picId as $k=>$v)
                        {
                                $sql.=" WHEN ".$v." THEN null";
                        }
                	$sql.=" END WHERE PICTUREID IN (".$idStr.")";
                	$res = $this->db->prepare($sql);
			
			foreach($picId as $k=>$v)
			{
				$res->bindParam(":TITLE".$k, $screenTitleArr[$k] , PDO::PARAM_STR);
			}

                	$res->execute();
			return "Success";
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
	/**
	This function checks if a profile has a screened profile main photo.
	@param profileid
	@return Y if true , NULL if false
	**/
	public function	hasScreenedMainPhoto($profileId)
	{
                if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN hasScreenedProfilePic() of PICTURE_NEW.class.php");

		$sql = "SELECT COUNT(*) as CNT FROM newjs.PICTURE_NEW WHERE PROFILEID = :PROFILEID AND ORDERING=0";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if($row["CNT"]>0)
			return 'Y';
		return NULL;
	}

	/**
        This function checks if any photo of a profile has an unscreened title
        * @param  profileid
        * @return true if unscreened title is present else false
        **/
	public function hasUnscreenedTitle($profileId)
	{
		$sql = "SELECT COUNT(UNSCREENED_TITLE) AS CNT FROM PICTURE_NEW WHERE PROFILEID = :PROFILEID AND UNSCREENED_TITLE!=\"\"";
		$res = $this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if($row["CNT"]>0)
                        return true;
                return false;
	}

	/**
        This function returns all the pics with ORDERING = 0 for a given list of profileids
        * @param  wherecondition array
        * @return 2Darray having pics url and the profileid.
        **/
	public function getMultipleUserProfilePics($whereCondition)
	{
		if(!$whereCondition)
			throw new jsException("","WHERE CONDITION IS BLANK IN getMultiple() of PICTURE_NEW.class.php");
		foreach($whereCondition as $key =>$value)
		{
			if(in_array($key,$this->validSet))
			{
				if(is_array($value))
				{
					foreach($value as $k=>$v)
						$valueStr[]=":v$k";
					$whereConditionSql[]= "$key IN (".(implode(",",$valueStr)).")";
				}
				else
				{
					$whereConditionSql[]= "$key = :$key";
				}
			}
		}
		$whereConditionStr = implode(" AND ",$whereConditionSql);
		try
		{
			$sql = "SELECT * FROM newjs.PICTURE_NEW WHERE ".$whereConditionStr;
			$res=$this->db->prepare($sql);
			foreach($whereCondition as $key =>$value)
	                {
        	                if(is_array($value))
                	        {
                        	        foreach($value as $k=>$v)
                                	      $res->bindValue(":v$k",$v, PDO::PARAM_INT); 
                        	}
                        	else
					$res->bindValue(":$key",$value, PDO::PARAM_INT);
                	}
			$res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
                                $output[$row["PROFILEID"]]["PICTUREID"] = $row["PICTUREID"];
                                foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
                                {
					$output[$row["PROFILEID"]][$key] = $row[$key];
				}
                                
                        }
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}



	/**
        * This function reutrns the count of screened photos for a given set of profileids
        * @param  profileidArr  array containing profileids
        * @return array with index as profileid and value as count of pics.
        **/
	public function getScreendPictureCountByPid($profileidArr)
	{
		if(!$profileidArr)
                        throw new jsException("","WHERE CONDITION IS BLANK IN getMultiple() of PICTURE_NEW.class.php");
		try
		{

			foreach($profileidArr as $key=>$val)
				$pidArr[] = ":PROFILEID".$key;
			
			$pidStr = implode(",",$pidArr);
			$sql = "SELECT count(*) AS C,PROFILEID FROM newjs.PICTURE_NEW WHERE PROFILEID IN ($pidStr) GROUP BY PROFILEID";
			$res=$this->db->prepare($sql);

			foreach($profileidArr as $key=>$val)
	                        $res->bindValue(":PROFILEID".$key, $val, PDO::PARAM_INT);

                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[$row["PROFILEID"]] = $row["C"];
                        }
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}



	/**
        This function reutrns the count of screened photos for a given set of profileids
        * @param  where condition array
        * @return array with index as profileid and value as count of pics.
        **/
	public function getMultipleUserPicsCount($whereCondition)
	{
		if(!$whereCondition)
                        throw new jsException("","WHERE CONDITION IS BLANK IN getMultiple() of PICTURE_NEW.class.php");
		foreach($whereCondition as $key =>$value)
                {
                        if(is_array($value))
                        {
                                foreach($value as $k=>$v)
                                        $valueStr[]=":v$k";
                                $whereConditionSql[]= "$key IN (".(implode(",",$valueStr)).")";
                        }
                        else
                        {
                                $whereConditionSql[]= "$key = :$key";
                        }
                }
                $whereConditionStr = implode(" AND ",$whereConditionSql);
		try
		{
			$sql = "SELECT count(*) AS C,PROFILEID FROM newjs.PICTURE_NEW WHERE ".$whereConditionStr." GROUP BY PROFILEID";
			$res=$this->db->prepare($sql);
			foreach($whereCondition as $key =>$value)
                        {
                                if(is_array($value))
                                {
                                        foreach($value as $k=>$v)
                                              $res->bindValue(":v$k",$v, PDO::PARAM_INT);
                                }
                                else
                                        $res->bindValue(":$key",$value, PDO::PARAM_INT);
                        }

                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[$row["PROFILEID"]] = $row["C"];
                        }
			return $output;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
        /**
        This function Moves Approved pics from PICTURE_FOR_SCREEN_NEW to PICTURE_NEW
        * @param  where condition array
        * @return array with index as profileid and value as count of pics.
        **/
        public function insertApprovedPhotos($paramArr)
	{
                try
		{
                        $loop = 0;
                        $comma = "";
                        $pictureID = "";
                        foreach ($paramArr["PICTUREID"] as $key => $value) {
                                $pictureID.=$comma . ":PICTUREID" . $loop;
                                $picture[":PICTUREID" . $loop] = $value;
                                $comma = ",";
                        }
                        $sql = "INSERT IGNORE INTO newjs.PICTURE_NEW ( PROFILEID , ORDERING , TITLE, KEYWORD,".implode(",",ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS)." ) SELECT PROFILEID , ORDERING , TITLE, KEYWORD, ".implode(",",ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS)." FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID=:PROFILEID AND PICTUREID IN (" . $pictureID . ")";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        foreach ($picture as $pictureKey => $pictureValue) {
                                $res->bindValue($pictureKey, $pictureValue, PDO::PARAM_INT);
                        }
                        $res->execute();
                }
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		
	}
        /**
        This function checks if any photo size of mobile pic is not recorded
        * @param  limit
        **/
	public function getProfilesNotRecorded($limit,$totalScript,$currentScript)
	{
                $output="";
		$sql = "SELECT A.`PICTUREID`, CASE WHEN A.`MobileAppPicUrl`='' THEN A.`MainPicUrl` ELSE A.`MobileAppPicUrl` END AS MobileAppPicUrl FROM `newjs`.`PICTURE_NEW` AS A LEFT JOIN `PICTURE`.`MobAppPicSize` AS B ON A.`PICTUREID`=B.`PICTUREID` WHERE B.`PICTUREID` IS NULL AND A.`PICTUREID`%".$totalScript."=".$currentScript."  AND (A.`MobileAppPicUrl`!='' AND A.`MainPicUrl`!='') LIMIT ".$limit;
		$res = $this->db->prepare($sql);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
                {
                        $output[$row["PICTUREID"]] = $row["MobileAppPicUrl"];
                }
                return $output;
	} 
    /*
     * This function fetches the required url or list of url's from the PICTURE_NEW table from a given back date.
     * @param  $requriedDate(how many days old data is required), $picUrl(which pic url is requried) 
     * @return returns an array with the picture_id and the required url's.
     * @author Sanyam Chopra
     */

    public function getRequiredUrl($requiredDate,$picUrl)
    {
       if(!$requiredDate)
                        throw new jsException("","WHERE CONDITION IS BLANK IN getMultiple() of PICTURE_NEW.class.php");
       try 
		{	
			$detailArr = '';
			$sql = "SELECT PICTUREID,$picUrl FROM newjs.PICTURE_NEW WHERE UPDATED_TIMESTAMP >= :REQUIRED_DATE";
			$prep=$this->db->prepare($sql);
            $prep->bindParam(":REQUIRED_DATE", $requiredDate, PDO::PARAM_INT);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
            	$detailArr[] = $row;
            }
	        return $detailArr;
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
	}

	//This function is made to be used in a one time cron named checkPhotoUrlTask.class.php
	public function getPicUrlArr($tableName,$lowerLimit,$upperLimit)
	{
		try
		{
			$sql = " SELECT P.PROFILEID,P.PICTUREID,P.ORDERING,P.PICFORMAT,P.MainPicUrl,P.OriginalPicUrl,P.ProfilePic120Url,
						P.ProfilePic235Url,P.ProfilePicUrl,P.ProfilePic450Url,
						P.MobileAppPicUrl,P.Thumbail96Url,P.ThumbailUrl,P.SearchPicUrl FROM newjs.PICTURE_NEW AS P JOIN newjs.".$tableName." as S  ON S.PROFILEID = P.PROFILEID WHERE S.HAVEPHOTO = 'Y' LIMIT ".$lowerLimit.",".$upperLimit;
			$prep=$this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
            	$detailArr[] = $row;
            }
	        return $detailArr;
		}
		catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
	}
}
?>
