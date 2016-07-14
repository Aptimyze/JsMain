<?php

//This lib function calls the store to get profiles that match the search criteria
class nameAgeSearch
{
	public function getProfilesForLegal($nameArr,$age,$addressArr)
	{
		$jprofileObj = new JPROFILE();
		$legalDataArr = $jprofileObj->getDataForLegal($nameArr,$age,$addressArr);
		return($legalDataArr);
	}
}