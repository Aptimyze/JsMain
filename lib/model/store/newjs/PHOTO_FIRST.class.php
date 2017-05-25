<?php
class PHOTO_FIRST extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	* This function check if entry exists for criteria specified.
	* @param param key value pair for array
	* @return 1 if exists else 0
	*/
        public function isExists($param)
        {
                try
                {
			$pid = $param["PROFILEID"];
			if(!$pid)
				throw new jsException("","pid blank In isExists(): PHOTO_FIRST");
                	$sql = "SELECT COUNT(*) AS C FROM  newjs.PHOTO_FIRST WHERE  PROFILEID=:PID";
	                $res=$this->db->prepare($sql);
        	        $res->bindValue(":PID", $pid, PDO::PARAM_INT);
	                $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
	                return $row["C"];
                }
                catch(PDOException $e)
                {
			throw new jsException("","No Select In isExists(): PHOTO_FIRST");
                }

        }

	public function newPhotoEntry($profileid)
	{
//if(PHOTODATE=="0000-00-00 00:00:00") //ADD THIS TO THE PLACE WHERE THIS FUNCTION IS CALLED //to identify if its the frst tym a photo is uploaded
		$sql="INSERT IGNORE INTO newjs.PHOTO_FIRST (PROFILEID, ENTRY_DT) VALUES(:PROFILEID,NOW())";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
        }
	public function profilesUploadedPhotoOnDate($date)
	{
		try
		{
		$date1 =$date." 00:00:00";
		$date2 =$date." 23:59:59";
		$sql = "SELECT PROFILEID FROM  newjs.PHOTO_FIRST WHERE  `ENTRY_DT`>=:ENTRY_DATE1 AND `ENTRY_DT`<=:ENTRY_DATE2";
		$res=$this->db->prepare($sql);
		$res->bindValue(":ENTRY_DATE1", $date1, PDO::PARAM_STR);
		$res->bindValue(":ENTRY_DATE2", $date2, PDO::PARAM_STR);
                $res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
			$return[] = $row["PROFILEID"];
		return $return;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
	public function getProfilesScreenedAfter($time)
	{
		try
		{
			$sql = "SELECT PROFILEID,ENTRY_DT FROM  newjs.PHOTO_FIRST WHERE `ENTRY_DT`>=:ENTRY_DATE";
			$res=$this->db->prepare($sql);
			$res->bindValue(":ENTRY_DATE", $time, PDO::PARAM_STR);
			$res->execute();
			$i=0;
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$return[$i]['PROFILEID']=$row["PROFILEID"];
				$return[$i]['ENTRY_DT'] = $row["ENTRY_DT"];
				$i++;
			}
			return $return;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
	public function getPhotoScreenedProfilesAfter($params)
	{
		try
		{	
			$return = array();
			if($params["profilesPool"] && is_array($params["profilesPool"]))
			{
				$idStr = implode(",", $params["profilesPool"]);
				$sql = "SELECT DISTINCT PROFILEID FROM newjs.PHOTO_FIRST WHERE PROFILEID IN(".$idStr.") AND PHOTO_FIRST.ENTRY_DT>=:SCREEN_DT";
				$res=$this->db->prepare($sql);
				$res->bindValue(":SCREEN_DT", $params["offsetDate"], PDO::PARAM_STR);
				$res->execute();
				$i=0;
		
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$return[$i]=$row["PROFILEID"];
					$i++;
				}
				return $return;
			}
			
		}
        catch(PDOException $e)
        {
                throw new jsException($e);
        }

	}
        public function getProfilesScreenedForNotification($time)
        {
                try
                {
                        $sql = "SELECT PROFILEID,ENTRY_DT FROM  newjs.PHOTO_FIRST WHERE `ENTRY_DT`>:ENTRY_DATE";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":ENTRY_DATE", $time, PDO::PARAM_STR);
                        $res->execute();
                        $i=0;
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $return[$i]['PROFILEID']=$row["PROFILEID"];
                                $return[$i]['ENTRY_DT'] = $row["ENTRY_DT"];
                                $i++;
                        }
                        return $return;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

        }
}
?>
