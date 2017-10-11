<?php
/**
 * @brief This class is used to store functions related to partner profile of multiple profiles.
 * @author Prinka Wadhwa
 * @created 2013-01-14
 */
class PartnerProfileArray
{
	public function getDppForMultipleProfiles($profileIdArr,$dbName,$fieldsStr="*")
	{
		$jpartnerObj = new newjs_JPARTNER($dbName);
		$dppArr = $jpartnerObj->getDataForMultipleProfiles($profileIdArr,$fieldsStr.", PROFILEID");
		$revampCasteFunction = new RevampCasteFunctions();
		if(is_array($dppArr))
		{
			foreach($dppArr as $profileId=>$dpp)
			{
				$dppArr[$profileId]["PARTNER_CASTE"] = $revampCasteFunction->getAllCastes($dpp["PARTNER_CASTE"],"0"); 
			}
		}
		return $dppArr;
	}
}
?>
