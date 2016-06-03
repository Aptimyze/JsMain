<?php
class Duplicates
{
	private $dbObj;
	
	function duplicateProfiles($pid)
	{
		$screenObj = new DuplicateProfileScreen();
		$duplicates=$screenObj->fetchDuplicate($profile->getPROFILEID());

		if(is_array($duplicates))
		{
			foreach($duplicates as $key=>$val)
			{
				if($profile->getPROFILEID() != $val)
				{
					$profile->getDetail($val,'PROFILEID',"USERNAME","RAW");

					$dup[$val]=$profile->getUSERNAME();
				}
			}
		}
		
		return $dup;
	}
	
}
?>
