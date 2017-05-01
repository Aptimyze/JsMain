<?php

class PICTURE_ADD_PHOTO_MAILER extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}

	//truncate the table
	public function truncatePhotoMailerData()
	{
		try
		{
			$sql="TRUNCATE TABLE PICTURE.ADD_PHOTO_MAILER";
			$prep = $this->db->prepare($sql);			
			$prep->execute();
		}
		catch(PDOException $ex)
		{
			jsException::nonCriticalError($ex);
		}
	}

	//insert data into ADD_PHOTO_MAILER from the given data
	public function insertnoPhotoMailerData($receiverData)
	{
		try
		{
			$sql = "INSERT INTO PICTURE.ADD_PHOTO_MAILER (SNO,PROFILEID,TYPE,SENT) VALUES ";
			$count=1;
			foreach($receiverData as $key=>$value)
			{
				$sqlAppend .= "($count,:PROFILEID".$count.",:TYPE".$count.",".noPhotoMailerEnum::NOSENTFLAG."), ";
				$count++;
			}
			$sqlAppend = rtrim($sqlAppend,", ");
            $sql .=$sqlAppend;
            
            $prep = $this->db->prepare($sql);
            $i=1; 
            foreach($receiverData as $key=>$val)
            {                
                $prep->bindValue(":PROFILEID$i", $val["PROFILEID"], PDO::PARAM_INT);
                $prep->bindValue(":TYPE$i", $val["TYPE"], PDO::PARAM_INT);
                $i++;
            }                       
            $prep->execute();
		}
		catch(PDOException $ex)
		{
			jsException::nonCriticalError($ex);
		}
	}

	//select profiles from ADD_PHOTO_MAILER with profiles that satisfy the condition
	public function getaddPhotoMailerProfiles($fields,$totalScript,$script,$limit)
	{
		try
		{
			$defaultFields ="SNO,PROFILEID,TYPE";

            $selectfields = $fields?$fields:$defaultFields;
            $sql = "SELECT $selectfields FROM PICTURE.ADD_PHOTO_MAILER where SENT IN (".noPhotoMailerEnum::NOSENTFLAG.") AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
            if($limit)
                $sql.= " limit 0,:LIMIT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
            $prep->bindValue(":SCRIPT",$script,PDO::PARAM_INT);
            if($limit)
                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
            $prep->execute();
            
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                if(!$fields)
                {
                    $fieldsArray = explode(",",$defaultFields);
                    foreach($fieldsArray as $k=>$v)
                    {
                        $result[$row["SNO"]][$v]=$row[$v];
                    }
                }
                else
                    $result[] = $row;
                unset($result[$row["SNO"]]["SNO"]);
            }
            return $result;
		}
		catch(PDOException $ex)
		{
			jsException::nonCriticalError($ex);
		}
	}

	//update flag upon mail being sent
	public function updateAddPhotoUsersFlag($sno,$flag,$pid)
	{
		try
		{
			$sql = "UPDATE PICTURE.ADD_PHOTO_MAILER SET SENT=:FLAG WHERE SNO=:SNO AND PROFILEID =:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SNO",$sno,PDO::PARAM_INT);
                        $prep->bindValue(":FLAG",$flag,PDO::PARAM_STR);
                        $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                        $prep->execute();
		}
		catch(PDOException $ex)
		{
			jsException::nonCriticalError($ex);
		}
	}
}