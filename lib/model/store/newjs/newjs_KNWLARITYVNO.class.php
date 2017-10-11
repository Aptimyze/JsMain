<?php
class newjs_KNWLARITYVNO extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

	public function deleteInvalidData()
	{
		try
		{
			$sql = "DELETE FROM `KNWLARITYVNO` WHERE LENGTH(PHONENO)<=5";
			$prep=$this->db->prepare($sql);
			$prep->execute();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
	public function getProfilesInTable($totalChunks,$chunk)
	{
		try
		{
			$sql = "SELECT DISTINCT(PROFILEID) FROM `KNWLARITYVNO` WHERE PROFILEID%$totalChunks=$chunk";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
				$return[] = $result['PROFILEID'];
			return $return;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	public function clearProfilePhoneEntry($profileId,$phoneNo)
	{
		try
		{
			$sql = "DELETE FROM newjs.KNWLARITYVNO WHERE PROFILEID=:PROFILEID AND PHONENO=:PHONENO";
			$prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->bindValue(":PHONENO",$phoneNo,PDO::PARAM_INT);
            $prep->execute();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function deleteVnoForProfiles($profiles)
	{
		if(!is_array($profiles))
                        throw new jsException("no profiles to delete entry form knowlarity table");
		try
		{
			foreach($profiles as $k=>$v)
				$arr[]= ":PROFILEID$k";
			$str = implode(",",$arr);
			unset($arr);
			$sql = "DELETE FROM newjs.KNWLARITYVNO WHERE PROFILEID IN ($str)";
                        $prep=$this->db->prepare($sql);
                        foreach($profiles as $k=>$v)
                        $prep->bindValue(":PROFILEID$k",$v,PDO::PARAM_INT);
                        $prep->execute();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	public function getDetailsFromProfileId($profileid)
	{

		if (!$profileid) throw new Exception("no profileid passed in arguements in function getDetailsFromProfileId in newjs_KNWLARITYVNO", 1);
		
		try
		{
			$sql = "SELECT VIRTUALNO,PHONENO FROM newjs.KNWLARITYVNO WHERE PROFILEID=:PROFILEID ORDER BY ID DESC LIMIT 1";
			$prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
				return $result; 
			else 
				return null;
		

		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}

	public function insertNewVno($profileid,$phoneNo,$vNo)
	{
		if (!$profileid || !$phoneNo || !$vNo){
		 throw new jsException('',"either of phoneNo, profileid, virtual No not passed in arguements in function getDetailsFromProfileId in newjs_KNWLARITYVNO", 1);
		}
		try
		{
			$sql = "INSERT IGNORE INTO newjs.KNWLARITYVNO VALUES ('',:PROFILEID,:PHONENO,:VNO)";
			$prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":PHONENO",$phoneNo,PDO::PARAM_STR);
            $prep->bindValue(":VNO",$vNo,PDO::PARAM_STR);
			$prep->execute();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}

public function getVnoFromPhone($phoneNo)
	{

		if (!$phoneNo){
		 throw new jsException('',"no phoneNo passed in arguements in function getVnoFromPhone in newjs_KNWLARITYVNO", 1);
		}
		
		try
		{
			$sql = "SELECT VIRTUALNO FROM newjs.KNWLARITYVNO WHERE PHONENO=:PHONE";
			$prep=$this->db->prepare($sql);
            $prep->bindValue(":PHONE",$phoneNo,PDO::PARAM_STR);
            $prep->execute();
			if($result = $prep->fetchAll(PDO::FETCH_ASSOC))
				return $result; 
			else 
				return null;
		

		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}


public function profileIDFromVnoIdAndPhone($phoneNo,$vNoId)
	{
		try
		{
        	$sql="SELECT PROFILEID FROM newjs.KNWLARITYVNO WHERE PHONENO=:PHONENO AND VIRTUALNO=:VIRTUALNO ORDER BY `ID` DESC LIMIT 1";
			$prep=$this->db->prepare($sql);
            $prep->bindValue(":PHONENO",$phoneNo,PDO::PARAM_STR);
            $prep->bindValue(":VIRTUALNO",$vNoId,PDO::PARAM_INT);
			$prep->execute();
			if($result = $prep->fetchAll(PDO::FETCH_ASSOC))
				return $result; 
			else 
				return null;
		
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}


}
?>
