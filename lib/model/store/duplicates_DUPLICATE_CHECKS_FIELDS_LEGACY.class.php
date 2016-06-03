<?php
class duplicates_DUPLICATE_CHECKS_FIELDS_LEGACY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function getProfilesForDuplicationCheck($totalScript,$currentScript)
	{
		$sql="SELECT PROFILEID, TIMESTAMP FROM duplicates.DUPLICATE_CHECKS_FIELDS_LEGACY WHERE PROFILEID%:TOTALSCRIPT=:CURRENTSCRIPT AND FLAG='N' ORDER BY CASTE,MTONGUE";
                $res=$this->db->prepare($sql);
		$res->bindValue(":TOTALSCRIPT", $totalScript, PDO::PARAM_INT);
		$res->bindValue(":CURRENTSCRIPT", $currentScript, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result[]=$row;
		}
		return $result;
        }

	public function updateEntryAfterDuplicationCheck($profileid,$newFlagValue)
	{
		$sql = "UPDATE duplicates.DUPLICATE_CHECKS_FIELDS_LEGACY SET FLAG=:FLAGVAL WHERE PROFILEID=:PROFILEID";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":FLAGVAL", $newFlagValue, PDO::PARAM_STR);
		$res->execute();
	}

	public function getProfileTimestamp($profileid)
	{
		$sql = "SELECT TIMESTAMP FROM duplicates.DUPLICATE_CHECKS_FIELDS_LEGACY WHERE PROFILEID=:PROFILEID";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result=$row['TIMESTAMP'];
		}
		return $result;
		
	}

}
?>
