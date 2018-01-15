<?php
/*
 * This Class provide functions for IMAGE_SERVER.LOG table
 * @author Reshu Rajput
 * @created 9 MAY 2013
*/
class IMAGE_SERVER_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	 /**
        This function is used to insert  bulk pictures info for image server LOG table.
        * @param $moduleName module name array as per enum defined 
	* @param $moduleId picture id array 
	* @param $imageType image type array as per enum defined
	* @param $status array either is image is on image server, application server or deleted
        * @returns true on sucess
        **/
        public function insertBulk($moduleName,$moduleId,$imageType,$status)
        {
                try
                {
                        $sql = "REPLACE INTO IMAGE_SERVER.LOG (MODULE_NAME,MODULE_ID,IMAGE_TYPE,STATUS,DATE) VALUES ";
                        for ($i=0;$i<count($moduleName);$i++)
                        {
                                $param[] = "(:MODULE_NAME".$i.", :MODULE_ID".$i.", :IMAGE_TYPE".$i.", :STATUS".$i.",NOW())";
                        }
                        $paramStr = implode(",",$param);
                        $sql = $sql.$paramStr;
                        $res = $this->db->prepare($sql);

                        for ($i=0;$i<count($moduleName);$i++)
                        {
                                $res->bindParam(":MODULE_NAME".$i, $moduleName[$i], PDO::PARAM_STR);
                                $res->bindParam(":MODULE_ID".$i, $moduleId[$i], PDO::PARAM_INT);
                                $res->bindParam(":IMAGE_TYPE".$i, $imageType[$i], PDO::PARAM_STR);
                                $res->bindParam(":STATUS".$i, $status[$i], PDO::PARAM_STR);
                        }
			$res->execute();
			
                        return true;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/*
        This function is used to fetch data from the LOG table to be used in the photo transfer cron
	@param - total instance of the cron running, current instance, module name(PICTURE,SUCCESS etc),limit of result
        @return - resultset array
        */
        public function fetchDataForCron($totalInstance,$currentInstance,$module,$limit)
        {
                try
                {
                        if($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("PICTURE"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,P.MainPicUrl AS MainPicUrl,P.ProfilePicUrl AS ProfilePicUrl,P.ThumbailUrl AS ThumbailUrl,P.Thumbail96Url AS Thumbail96Url,P.SearchPicUrl AS SearchPicUrl,P.MobileAppPicUrl AS MobileAppPicUrl, P.ProfilePic120Url AS ProfilePic120Url,P.ProfilePic235Url AS ProfilePic235Url,P.ProfilePic450Url AS ProfilePic450Url,P.OriginalPicUrl AS OriginalPicUrl, P.PROFILEID AS PROFILEID FROM IMAGE_SERVER.LOG I LEFT JOIN newjs.PICTURE_NEW P ON I.MODULE_ID = P.PICTUREID WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
                        elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("INDIVIDUAL_STORY"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,S.HOME_PIC_URL AS HOME_PIC_URL,S.MAIN_PIC_URL AS MAIN_PIC_URL,S.FRAME_PIC_URL AS FRAME_PIC_URL,S.SQUARE_PIC_URL AS SQUARE_PIC_URL,S.SID AS SID FROM IMAGE_SERVER.LOG I LEFT JOIN newjs.INDIVIDUAL_STORIES S ON I.MODULE_ID = S.SID WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
                        elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("SUCCESS_STORY"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,S.PIC_URL AS PIC_URL,S.ID AS ID FROM IMAGE_SERVER.LOG I LEFT JOIN newjs.SUCCESS_STORIES S ON I.MODULE_ID = S.ID WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
                        elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("FIELD_SALES"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,S.PHOTO_URL AS PHOTO_URL,S.RESID AS ID FROM IMAGE_SERVER.LOG I LEFT JOIN jsadmin.PSWRDS S ON I.MODULE_ID = S.RESID WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
			elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("VERIFICATION_DOCUMENTS"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,P.DOCURL AS DOCURL,P.PROFILEID AS ID FROM IMAGE_SERVER.LOG I LEFT JOIN PROFILE_VERIFICATION.DOCUMENTS P ON I.MODULE_ID = P.DOCUMENT_ID WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
			elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("VERIFICATION_DOCUMENTS_BYUSER"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,P.PROOF_VAL AS PROOF_VAL,P.PROFILEID AS ID FROM IMAGE_SERVER.LOG I LEFT JOIN PROFILE.VERIFICATION_DOCUMENTS P ON I.MODULE_ID = P.id WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
			elseif($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("PICTURE_DELETED"))
                                $sql = "SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS,P.MAIN_PHOTO_URL AS MAIN_PHOTO_URL,P.PROFILEID AS PROFILEID FROM IMAGE_SERVER.LOG I LEFT JOIN newjs.PICTURE_DELETE_NEW P ON I.MODULE_ID = P.PICTUREID WHERE I.STATUS = :STATUS AND I.MODULE_NAME = :MODULE_NAME AND I.AUTOID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";

                        if($sql)
                        {
                                $res = $this->db->prepare($sql);
                                $res->bindValue(":STATUS", IMAGE_SERVER_STATUS_ENUM::$onAppServer, PDO::PARAM_STR);
                                $res->bindValue(":TOTAL_INSTANCE",$totalInstance, PDO::PARAM_INT);
                                $res->bindValue(":CURRENT_INSTANCE",$currentInstance, PDO::PARAM_INT);
                                $res->bindValue(":MODULE_NAME",$module, PDO::PARAM_STR);
                                $res->bindValue(":LIMIT",$limit, PDO::PARAM_INT);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $output[] = $row;
                        }
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $output;
        }

	/*
	This function is used to update the table
	@param - id, parameter array where index has the column name to be updated and value has the value to be updated in the column
	*/
	public function update($id,$paramArr)
        {
                if(!$id || !$paramArr || !is_array($paramArr))
                        throw new jsException("","ID OR PARAMETER ARRAY IS BLANK IN updateStatus() OF IMAGE_SERVER_LOG.class.php");

		try
		{
			foreach($paramArr as $key=>$val)
                        {
                                $set[] = $key." = :".$key;
                        }
                        $setValues = implode(",",$set);

                        $sql = "UPDATE IMAGE_SERVER.LOG SET $setValues WHERE AUTOID = :AUTOID";
                        $res = $this->db->prepare($sql);
                        foreach($paramArr as $key=>$val)
                        {
                                $res->bindValue(":".$key, $val,PDO::PARAM_STR);
                        }
                        $res->bindValue(":AUTOID",$id,PDO::PARAM_INT);
                        $res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	/*
	This function is used to get all the picture details which are not yet transfered to the cloud after the certain date
	*@param : days it will take time span beyond which a picture should not be in N status
	*@return : output It will be returned containing array of autoId of the picture details not yet uploaded
	*/
	public function getNotUploadedToCloud($days)
	{
		if(!$days)
                        throw new jsException("","DAYS IS BLANK IN getNotUploadedToCloud() OF IMAGE_SERVER_LOG.class.php");

		try
		{
			$sql = "SELECT AUTOID FROM IMAGE_SERVER.LOG WHERE STATUS=:STATUS AND DATEDIFF(NOW(),DATE) >= :DAYS";
			$res = $this->db->prepare($sql);
                        $res->bindParam(":DAYS", $days, PDO::PARAM_INT);
			$res->bindParam(":STATUS",IMAGE_SERVER_STATUS_ENUM::$onAppServer, PDO::PARAM_STR);
			$res->execute();
			
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                	        $output[] = $row[AUTOID];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		
		return $output;
	}
	
	
	/*
        This function is used to fetch data from the LOG table to be used in the photo transfer cron for archiving
	@param - total instance of the cron running, current instance, module name(PICTURE,SUCCESS etc),limit of result
	@param date - profiles with login date lower to this will be archived
        @return - resultset array
        */
        public function fetchDataForArchiveCron($totalInstance,$currentInstance,$module,$date,$limit)
        {
                try
                {
                        if($module == IMAGE_SERVER_MODULE_NAME_ENUM::getEnum("PICTURE"))
				$sql="SELECT I.AUTOID AS AUTOID,I.MODULE_NAME AS MODULE_NAME,I.MODULE_ID AS MODULE_ID,I.IMAGE_TYPE AS IMAGE_TYPE,I.STATUS AS STATUS, P.MainPicUrl AS MainPicUrl, P.ProfilePicUrl AS ProfilePicUrl, P.ThumbailUrl AS ThumbailUrl, P.Thumbail96Url AS Thumbail96Url, P.SearchPicUrl AS SearchPicUrl, P.MobileAppPicUrl AS MobileAppPicUrl, P.ProfilePic120Url AS ProfilePic120Url, P.ProfilePic235Url AS ProfilePic235Url, P.ProfilePic450Url AS ProfilePic450Url, P.OriginalPicUrl AS OriginalPicUrl, P.PROFILEID AS PROFILEID FROM newjs.JPROFILE AS J INNER JOIN newjs.PICTURE_NEW P ON J.PROFILEID = P.PROFILEID INNER JOIN IMAGE_SERVER.LOG AS I ON P.PICTUREID = I.MODULE_ID WHERE I.STATUS = :STATUS AND DATE(J.LAST_LOGIN_DT) < :DATE AND I.MODULE_NAME=:MODULE_NAME AND P.PICTUREID % :TOTAL_INSTANCE = :CURRENT_INSTANCE LIMIT :LIMIT";
                        if($sql)
                        {
                                $res = $this->db->prepare($sql);
                                $res->bindValue(":STATUS", IMAGE_SERVER_STATUS_ENUM::$onImageServer, PDO::PARAM_STR);
                                $res->bindValue(":TOTAL_INSTANCE",$totalInstance, PDO::PARAM_INT);
                                $res->bindValue(":CURRENT_INSTANCE",$currentInstance, PDO::PARAM_INT);
                                $res->bindValue(":MODULE_NAME",$module, PDO::PARAM_STR);
                                $res->bindValue(":LIMIT",$limit, PDO::PARAM_INT);
                                $res->bindValue(":DATE",$date, PDO::PARAM_STR);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $output[] = $row;
                        }
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $output;
        }


}
?>
