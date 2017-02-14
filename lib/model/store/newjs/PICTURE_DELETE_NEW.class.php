<?php
/*
 * This Class provide functions for PICTURE_DELETE_NEW table
 * @author Reshu Rajput
 *
*/


class PICTURE_DELETE_NEW extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function trackDeletedPhotoDetails($whereConditions)
	{
		$sql="INSERT IGNORE INTO newjs.PICTURE_DELETE_NEW (PROFILEID, PICTUREID, MAIN_PHOTO_URL) SELECT PROFILEID,PICTUREID,CASE WHEN OriginalPicUrl='' OR OriginalPicUrl IS NULL THEN MainPicUrl ELSE OriginalPicUrl END AS MAIN_PHOTO_URL FROM newjs.PICTURE_NEW WHERE PICTUREID= :PICTUREID AND PROFILEID=:PROFILEID";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $whereConditions["PROFILEID"], PDO::PARAM_INT);
		$res->bindValue(":PICTUREID", $whereConditions["PICTUREID"], PDO::PARAM_INT);
		$res->execute();
        }
        
        public function insertDeletedPhotoDetails($whereConditions)
	{
                if(!$whereConditions["TYPE"])
                        $whereConditions["TYPE"]="UNKNOWN";
                $loop=0;$comma="";$pictureID="";
                foreach ($whereConditions["PICTUREID"] as $key=>$value)
                {
                        $pictureID.=$comma.":PICTUREID".$loop;
                        $picture[":PICTUREID".$loop]=$value;
                        $comma=",";
                        $loop++;
                }
		$sql="INSERT IGNORE INTO newjs.PICTURE_DELETE_NEW (PROFILEID, PICTUREID, TYPE, MAIN_PHOTO_URL, REASON) SELECT PROFILEID,PICTUREID,:TYPE AS TYPE, OriginalPicUrl AS MAIN_PHOTO_URL,:REASON AS REASON FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID=:PROFILEID AND PICTUREID IN (".$pictureID.")";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $whereConditions["PROFILEID"], PDO::PARAM_INT);
		foreach($picture as $pictureKey=>$pictureValue){
                        $res->bindValue($pictureKey, $pictureValue, PDO::PARAM_INT);
                }
                $res->bindValue(":TYPE", $whereConditions["TYPE"], PDO::PARAM_INT);
                $res->bindValue(":REASON", $whereConditions["DELETE_REASON"], PDO::PARAM_INT);
		
                $res->execute();
        }
	
	
	
	 /** Function getDeletedPhotos added by Reshu
        This function is used to get pictures info from deleted  table (PICTURE_DELETE_NEW table).
        * @param  profileId on basis of which entry will be fetched.
        * @return detailArr array picture(s) info. Return null in case of no matching rows found.
        **/
        public function getDeletedPhotos($profileId)
	{
                if(!$profileId)
                        throw new jsException("","PROFILEID  IS BLANK IN getDeletedPhotos() of PICTURE_DELETE_NEW.class.php");
                try
                {
                        $output=NULL;
			// In select query we map MAIN_PHOTO_URL as mainPicUrl to keep sync with other Picture objects
			$sql = "SELECT PROFILEID,PICTUREID, TYPE, REASON, MAIN_PHOTO_URL AS mainPicUrl FROM newjs.PICTURE_DELETE_NEW WHERE ";
                        if($profileId)
                                $sql.="PROFILEID = :PROFILEID";

                        $res = $this->db->prepare($sql);
                        if($profileId)
                                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
		
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				// PICTUREID is used to create array as all the values are for same PROFILEID
                                $output[$row["PICTUREID"]] = $row;
                        }

                        return $output;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
	}
        /** Function getDeletedPhotos added by Akash
        This function is used to get pictures info from deleted  table (PICTURE_DELETE_NEW table).
        * @param  pictureId on basis of which entry will be fetched.
        * @return detailArr array picture(s) info. Return null in case of no matching rows found.
        **/
        public function getDeletionReason($pictureId)
	{ 
                if(!$pictureId)
                        throw new jsException("","PICTUREID  IS BLANK IN getDeletionReason() of PICTURE_DELETE_NEW.class.php");
                try
                {
                        $sql = "SELECT REASON FROM newjs.PICTURE_DELETE_NEW WHERE PICTUREID=:PICTUREID";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PICTUREID", $pictureId, PDO::PARAM_INT);
                        $res->execute();
		
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        		
                        return $row["REASON"];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
	}
	
	
	
	/**
        This function is used to edit/alter pictures info (profile pic/album) of picture_delete_new table).
        * @param paramArr array contains where condition on basis of which entry will be fetched.
        * @param pictureId int update is only only by pictureId.
        * @returns true on sucess
        **/
        public function edit($paramArr=array(),$pictureId,$profileId)
        {
                if(!$pictureId)
                        throw new jsException("","PICTUREID IS BLANK IN edit() of PICTURE_DELETE_NEW.class.php");
                try
                {
												foreach($paramArr as $key=>$val)
												{
													if($key=="MAIN_PHOTO_URL")
														$set[] = $key." = :".$key;
												}

                        $setValues = implode(",",$set);

                        $sql = "UPDATE newjs.PICTURE_DELETE_NEW SET $setValues WHERE PICTUREID = :PICTUREID AND PROFILEID = :PROFILEID";
                        $res = $this->db->prepare($sql);
                        foreach($paramArr as $key=>$val)
                        {
														if($key=="MAIN_PHOTO_URL")
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

        

}
?>
