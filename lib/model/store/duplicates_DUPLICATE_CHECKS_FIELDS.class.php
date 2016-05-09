<?php
class duplicates_DUPLICATE_CHECKS_FIELDS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function getProfilesForDuplicationCheck($profileType,$totalScript,$currentScript)
	{
		$sql="SELECT PROFILEID, FIELDS_TO_BE_CHECKED, TIMESTAMP FROM duplicates.DUPLICATE_CHECKS_FIELDS WHERE TYPE=:PROFILETYPE AND PROFILEID%:TOTALSCRIPT=:CURRENTSCRIPT AND FIELDS_TO_BE_CHECKED<>0 ORDER BY CASTE,MTONGUE";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILETYPE", $profileType, PDO::PARAM_STR);
		$res->bindValue(":TOTALSCRIPT", $totalScript, PDO::PARAM_INT);
		$res->bindValue(":CURRENTSCRIPT", $currentScript, PDO::PARAM_INT);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result[]=$row;
		}
		return $result;
        }

	public function deleteEntryAfterDuplicationCheck($profileType,$currentScript,$totalScripts)
	{
		$sql = "DELETE FROM duplicates.DUPLICATE_CHECKS_FIELDS WHERE FIELDS_TO_BE_CHECKED = '0' AND TYPE=:PROFILETYPE AND PROFILEID%:TOTALSCRIPT=:CURRENTSCRIPT";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILETYPE", $profileType, PDO::PARAM_STR);
		$res->bindValue(":TOTALSCRIPT", $totalScripts, PDO::PARAM_INT);
		$res->bindValue(":CURRENTSCRIPT", $currentScript, PDO::PARAM_INT);
		$res->execute();
	}

	public function updateEntryAfterDuplicationCheck($profileid,$newFlagValue)
	{
		$sql = "UPDATE duplicates.DUPLICATE_CHECKS_FIELDS SET FIELDS_TO_BE_CHECKED=:FLAGVAL WHERE PROFILEID=:PROFILEID";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->bindValue(":FLAGVAL", $newFlagValue, PDO::PARAM_INT);
		$res->execute();
	}

	public function getFlagValue($profileid,$profileType)
	{
		$sql = "SELECT FIELDS_TO_BE_CHECKED FROM duplicates.DUPLICATE_CHECKS_FIELDS WHERE PROFILEID=:PROFILEID AND TYPE=:PROFILETYPE";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILETYPE", $profileType, PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result=$row['FIELDS_TO_BE_CHECKED'];
		}
		return $result;
	}

	public function getProfileTimestamp($profileid,$profileType)
	{
		$sql = "SELECT TIMESTAMP FROM duplicates.DUPLICATE_CHECKS_FIELDS WHERE PROFILEID=:PROFILEID AND TYPE=:PROFILETYPE";
                $res=$this->db->prepare($sql);
		$res->bindValue(":PROFILETYPE", $profileType, PDO::PARAM_STR);
		$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$result=$row['TIMESTAMP'];
		}
		return $result;
		
	}
	
	/** Function deleteInactiveEntry added by Reshu Rajput
        This function is used to delete profiles from DUPLICATE_CHECKS_FIELDS table having ACTIVATED value as 'N'or 'D' in newjs.JPROFILE .
        * @param profileId according to which entry will be deleted.
        **/

	public function deleteInactiveEntry($profileId)
	{
           
		if(!$profileId)
                       throw new jsException("","PROFILEID IS BLANK IN deleteInactiveEntry() of duplicates_DUPLICATE_CHECKS_FIELDS.class.php");
                try
                {
			$sql="DELETE FROM duplicates.DUPLICATE_CHECKS_FIELDS WHERE PROFILEID=:PROFILEID";
			$res=$this->db->prepare($sql);
      			$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_STR);
               		$res->execute();
     
		}	
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	
	}

	/** Function insertRetrievedEntry added by Reshu Rajput
        This function is used to insert profile into DUPLICATE_CHECKS_FIELDS table from newjs.JPROFILE which is retrieved after being in inactive, incomplete or deleted.
        * @param profile which can either be profile id or profile object according to which entry will be inserted.
        **/

        public function insertRetrievedEntry($profile,$type,$flagVal='')
        {
		
                if(!$profile)
                       throw new jsException("","PROFILEID or profile object  IS BLANK IN insertRetrievedEntry() of duplicates_DUPLICATE_CHECKS_FIELDS.class.php");
                try
                {
		//	$argumentType=get_class($profile);
			if(is_object($profile))
			{
				$profileObj=$profile;
				$profileid=$profileObj->getPROFILEID();
			}
			else
			{
				$profileObj = Profile::getInstance('newjs_master',$profile);
				$profileObj->getDetail("","","CASTE,MTONGUE");

			}
			
			if(!$flagVal)
	                        $flagVal= FieldMap::getFieldLabel("flagval","sum","0"); 
			$sql="REPLACE INTO duplicates.DUPLICATE_CHECKS_FIELDS (PROFILEID,TYPE,FIELDS_TO_BE_CHECKED,CASTE,MTONGUE) VALUES (:PROFILEID,:TYPE,:FLAG,:CASTE,:MTONGUE)";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$profileObj->getPROFILEID(), PDO::PARAM_STR);
			$res->bindValue(":TYPE", $type, PDO::PARAM_STR);
			$res->bindValue(":FLAG", $flagVal, PDO::PARAM_STR);
			$res->bindValue(":CASTE", $profileObj->getCASTE(), PDO::PARAM_STR);
			$res->bindValue(":MTONGUE",$profileObj->getMTONGUE(), PDO::PARAM_STR);

                        $res->execute();

                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }


        }

	function get_from_duplication_check_fields($profileid)
	{
		$sql="SELECT FIELDS_TO_BE_CHECKED,TYPE from duplicates.DUPLICATE_CHECKS_FIELDS where PROFILEID=:profileid";
		$res=$this->db->prepare($sql);
		$res->bindValue(":profileid",$profileid, PDO::PARAM_STR);
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC))
                        return $row;
                else
			return 0;
	}

}
?>
