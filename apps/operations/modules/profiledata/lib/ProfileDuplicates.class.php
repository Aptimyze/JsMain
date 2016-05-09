<?php
class ProfileDuplicates
{
	private $dbObj;
	private $profile;
	
	
	function __construct($profileObj)
	{
		$this->profile = $profileObj;
	}
	
	function duplicateProfiles()
	{
		$screenObj = new DuplicateProfileScreen();
		$duplicates=$screenObj->fetchDuplicate($this->profile->getPROFILEID());
		$otherProfile=new Profile();
		if(is_array($duplicates))
		{
			foreach($duplicates as $key=>$val)
			{	
				if($this->profile->getPROFILEID() != $val)
				{
					$otherProfile->getDetail($val,'PROFILEID',"USERNAME","RAW");
		
					$dup[$val]=$otherProfile->getUSERNAME();
				}
			}
		}
		
		return $dup;
	}
	
}
?>
