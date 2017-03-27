<?php
class NEWJS_IGNORE extends TABLE
{
        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

        public function isIgnored($ignored_by,$ignored_to)
        {
		try 
		{
			if($ignored_by!="" && $ignored_to!="")
			{
				$sql="SELECT PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID=:INGBY AND IGNORED_PROFILEID=:INGTO";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":INGBY", $ignored_by, PDO::PARAM_INT);
				$prep->bindValue(":INGTO", $ignored_to, PDO::PARAM_INT);
				$prep->execute();
				if($result = $prep->fetch(PDO::FETCH_ASSOC))
					return true;
				return false;
			}	
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	/**
	  * This function enters the entry of a user and the ignored profile in newjs.IGNORE_PROFILE.
	**/

	public function ignoreProfile($profileid,$ignoredProfileid)
	{
		$sql="REPLACE INTO newjs.IGNORE_PROFILE(PROFILEID, IGNORED_PROFILEID, DATE,UPDATED) VALUES (:PROFILEID, :IGNORED_PROFILEID, NOW(),'Y')";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":IGNORED_PROFILEID", $ignoredProfileid, PDO::PARAM_INT);
		$res->execute();
	}		
	
	/**
	  * This function deletes the entry of a user and the ignored profile in newjs.IGNORE_PROFILE.
	**/

	public function undoIgnoreProfile($profileid,$ignoredProfileid)
	{
		$sql="DELETE FROM newjs.IGNORE_PROFILE WHERE PROFILEID=:PROFILEID AND IGNORED_PROFILEID=:IGNORED_PROFILEID ";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":IGNORED_PROFILEID", $ignoredProfileid, PDO::PARAM_INT);
		$res->execute();
	}		

	/**
	* author : Lavesh Rawat
	*  This function list the two way ignored profile ie.
	1) ignored by user 
	2) users ignored
	*/
	public function listIgnoredProfile($profileid,$seperator='')
	{
		$sql = "SELECT PROFILEID AS PID FROM newjs.IGNORE_PROFILE WHERE IGNORED_PROFILEID=:PID UNION SELECT IGNORED_PROFILEID AS PID FROM newjs.IGNORE_PROFILE WHERE PROFILEID=:PID";
		$res=$this->db->prepare($sql);
		$res->bindValue(":PID", $profileid, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			if($seperator == 'spaceSeperator')
				$ignoreArr.= $row["PID"]." ";
			else
				$ignoreArr[] = $row["PID"];
		}
		return $ignoreArr;
	}
		public function getIgnoredProfiles($ignoredProfiles,$profileid,$key='')
		{
			$sql = "SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID = :PROFILEID AND IGNORED_PROFILEID IN ($ignoredProfiles) ";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($key == 1)
					$result[$row['IGNORED_PROFILEID']] = 1;
				else
					$result[]=$row['IGNORED_PROFILEID'];
			}
			return $result;
		}
		
		public function getIgnoredProfilesList($profileid)
		{
			try{
				if(!$profileid)
					throw new jsException("","PROFILEID IS BLANK IN getIgnoredProfilesList() OF newjs_IGNORED_PROFILE.class.php");
				$sql = "SELECT IGNORED_PROFILEID as PROFILEID, DATE AS TIME, SEEN FROM newjs.IGNORE_PROFILE WHERE PROFILEID = :PROFILEID ";
				$res=$this->db->prepare($sql);
				$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
					$output[$row["PROFILEID"]]["COUNT"] = $row["COUNT"];
					$output[$row["PROFILEID"]]["SEEN"] = $row["SEEN"];
				}
			}
			catch(PDOException $e)
	        {
	           throw new jsException($e);
	        }
	        return $output;
		}
                
                
                public function getCountIgnoredProfiles($profileid)
               {
                       try{
                               if(!$profileid)
                                       throw new jsException("","PROFILEID IS BLANK IN getCountIgnoredProfiles() OF newjs_IGNORED_PROFILE.class.php");
                               $sql = "SELECT count(*) AS CNT FROM newjs.IGNORE_PROFILE WHERE PROFILEID = :PROFILEID ";
                               $res=$this->db->prepare($sql);
                               $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                               $res->execute();
                               if($row = $res->fetch(PDO::FETCH_ASSOC))
                               {
                               return $row;
                               }
                                
                                return null;
                       }
                       catch(PDOException $e)
               {
                  throw new jsException($e);
               }
               return $output;
               }

}
?>
