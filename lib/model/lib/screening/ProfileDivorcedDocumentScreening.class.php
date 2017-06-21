<?php
/**
 * CLASS ProfileDivorcedDocumentScreening
 * This class is responsible to handle operations related to profile divorced document upload.
 * @author Bhavana Kadwal
 * @package jeevansathi
*/
class ProfileDivorcedDocumentScreening
{
	/**
	* This function allocates a profile to screening user.
	* @param pid profileid to be assigned.
	* @param name screening user to which profile need to be assigned.
	*/
	public function allotProfile($pid,$name)
	{
		$CRITICAL_INFO_DOC_ASSIGNED = new CRITICAL_INFO_DOC_ASSIGNED;
		$CRITICAL_INFO_DOC_ASSIGNED->insertDocuments($pid,$name);
	}
	
	/**
	* This function decides the profile to be assigned to screening user.
	* @param name screening user to which profile need to be assigned.
	* @return array containing profileid and status(updateAllotTime) to tell if we need to update allocation time
	*/
	public function fetchProfileToAllot($name)
	{
		/* profile which have been allotted to a specific screening user and havent been screened yet and has not crossed maximum time. */
		$CRITICAL_INFO_DOC_ASSIGNED = new CRITICAL_INFO_DOC_ASSIGNED;
		$infoArr = $CRITICAL_INFO_DOC_ASSIGNED->userAllottedProfiles($name);
		if(is_null($infoArr))
		/* allot profile to user based on oldest 1st. */
		{
			$PROFILE_VERIFICATION_DOCUMENTS = new CriticalInfoChangeDocUploadService;
			$infoArr = $PROFILE_VERIFICATION_DOCUMENTS->allottProfile();
			$updateAllotTime = 1;
		}
		if(is_array($infoArr))
		{
			$returnArr['PROFILEID'] = $infoArr["PROFILEID"];
			$returnArr['updateAllotTime'] = $updateAllotTime;
			return $returnArr;
		}
		return NULL;
	}

	public function trackScreening($name,$arr1)
	{
		$CRITICAL_INFO_DOC_ASSIGNED_TRACKING = new CRITICAL_INFO_DOC_ASSIGNED_TRACKING;
		foreach($arr1 as $k=>$v)
		{
			$arr[$k]["DOCUMENT_ID"] = $v["DOCUMENT_ID"]; 
			$arr[$k]["SCREENED_TIME"] = date("Y-m-d H:i:s");
			$arr[$k]["SCREENED_BY"] = $name;
		}
		$CRITICAL_INFO_DOC_ASSIGNED_TRACKING->insertDocuments($arr);
	}
        public function del($pid,$name){
                $CRITICAL_INFO_DOC_ASSIGNED = new CRITICAL_INFO_DOC_ASSIGNED;
		$CRITICAL_INFO_DOC_ASSIGNED->del($pid,$name);
        }
}
?>