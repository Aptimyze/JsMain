<?php
/**
 * CLASS ProfileDivorcedDocumentScreening
 * This class is responsible to handle operations related to profile divorced document upload.
 * @author Bhavana Kadwal
 * @package jeevansathi
*/
class ProfileDivorcedDocumentScreening
{
	private $maxTimeToScreenAfterAllocation = 30; // in minutes
	private $minTimeToScreenAfterUpdate = 30; //in minutes

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
		$date = new DateTime();
		$date->sub(new DateInterval('PT'.$this->maxTimeToScreenAfterAllocation.'M'));
		$time = $date->format('Y-m-d H:i:s');

		/* profile which have been allotted to a specific screening user and havent been screened yet and has not crossed maximum time. */
		$CRITICAL_INFO_DOC_ASSIGNED = new CRITICAL_INFO_DOC_ASSIGNED;
		$infoArr = $CRITICAL_INFO_DOC_ASSIGNED->userAllottedProfiles($time,'greater',$name);
		$updateAllotTime = NULL;

		if(is_null($infoArr))
		/* profile which have been allotted to screening user(including loggedin) and havent been screened yet in a max time */
		{
			$infoArr = $CRITICAL_INFO_DOC_ASSIGNED->userAllottedProfiles($time,'less');
			$updateAllotTime = 1;
		}
		if(is_null($infoArr))
		/* allot profile to user based on oldest 1st. */
		{
			$date = new DateTime();
			$date->sub(new DateInterval('PT'.$this->minTimeToScreenAfterUpdate.'M'));
			$time = $date->format('Y-m-d H:i:s');
			$PROFILE_VERIFICATION_DOCUMENTS = new CriticalInfoChangeDocUploadService;
			$infoArr = $PROFILE_VERIFICATION_DOCUMENTS->allottProfile($time);
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