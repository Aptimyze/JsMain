<?php

class PICTURE_INCORRECT_PICTURE_DATA extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}

	public function insertIncorrectPicDetail($profileId,$pictureId,$ordering,$reason)
	{
		try
		{
			$sql = "INSERT IGNORE into PICTURE.INCORRECT_PICTURE_DATA VALUES (:PICTUREID,:PROFILEID,:ORDERING,:REASON)";
			$prep=$this->db->prepare($sql);
			$prep->bindParam(":PICTUREID", $pictureId, PDO::PARAM_INT);
			$prep->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
			$prep->bindParam(":ORDERING", $ordering, PDO::PARAM_INT);
			$prep->bindParam(":REASON", $reason, PDO::PARAM_STR);
			$prep->execute();
    	}
    	catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
	}
        public function getPicUrlArr($lowerLimit,$upperLimit)
        {
                try
                {
                        $sql = " SELECT P.PROFILEID,P.PICTUREID,P.ORDERING,P.PICFORMAT,P.MainPicUrl,P.OriginalPicUrl,P.ProfilePic120Url,
                                                P.ProfilePic235Url,P.ProfilePicUrl,P.ProfilePic450Url,
                                                P.MobileAppPicUrl,P.Thumbail96Url,P.ThumbailUrl,P.SearchPicUrl FROM newjs.PICTURE_NEW AS P JOIN PICTURE.INCORRECT_PICTURE_DATA as S  ON S.PROFILEID = P.PROFILEID AND S.PICTUREID=P.PICTUREID LIMIT :LOWERLIMIT, :UPPERLIMIT";
                        $prep=$this->db->prepare($sql);
            $prep->bindParam(":LOWERLIMIT", $lowerLimit, PDO::PARAM_INT);
            $prep->bindParam(":UPPERLIMIT", $upperLimit, PDO::PARAM_INT);
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
        public function deleteIncorrectPicDetail($pictureIdArr)
        {
                try
                {
                        foreach($pictureIdArr as $k=>$v)
                        {
                                $queryArr[]= ":PICTUREID".$k;
                        }
                        $queryStr="(".implode(",",$queryArr).")";
                    echo    $sql = "DELETE from PICTURE.INCORRECT_PICTURE_DATA where PICTUREID IN ".$queryStr;die;
                        $prep=$this->db->prepare($sql);
                        foreach($pictureIdArr as $k=>$v)
                        {
                                $prep->bindParam(":PICTUREID".$k, $v, PDO::PARAM_INT);
                        }
                        $prep->execute();
                }       
        catch(PDOException $e)
                {
                        /** echo the sql statement and error message **/
                         throw new jsException($e);
                }
        }


}
?>
