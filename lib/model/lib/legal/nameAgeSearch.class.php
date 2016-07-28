<?php

//This lib function calls the store to get profiles that match the search criteria
class nameAgeSearch
{
	public function getProfilesForLegal($nameArr,$age,$addressArr,$email)
	{
		$jprofileObj = new JPROFILE("newjs_slave");
		$legalDataArr = $jprofileObj->getDataForLegal($nameArr,$age,$addressArr,$email);
		return($legalDataArr);
	}
}