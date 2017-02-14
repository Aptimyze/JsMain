<?php
/*
This class has the logic og handling duplicate profiles since FTO went live, i.e. the class determines the profileid which needs to be inserted in NEGATIVE_TREATMENT_LIST table
*/
class HandleFtoDuplicate
{
	private $typeOf = "Fto_Duplicate";
	private $entry_by = "System";

	public function __construct()
        {
        }

	/*
	*This function finds the profileid which is to be inserted in NEGATIVE_TREATMENT_LIST table
	*$param - profileid1 and profileid2
	*/
	public function ftoDuplicateLogic($profileid1,$profileid2)
	{
		if($profileid1 && $profileid2)
		{
			$fto_duplicate_arr = FieldMap::getFieldLabel("Fto_Duplicate",1,1);
			$fto_live_date = FTOLiveFlags::FTO_LIVE_DATE;

			$profArrObj = new ProfileArray;
			$profileIdArr["PROFILEID"] = $profileid1.",".$profileid2;
			$detailArr = $profArrObj->getResultsBasedOnJprofileFields($profileIdArr,'','',"ENTRY_DT,PROFILEID,SUBSCRIPTION");
			unset($profileIdArr);
			unset($profArrObj);

			if($detailArr && is_array($detailArr))
			{
				foreach($detailArr as $k=>$v)
					$entry_dt_arr[$v->getPROFILEID()] = $v->getENTRY_DT();

				$id = $this->compareDates($entry_dt_arr[$profileid1],$entry_dt_arr[$profileid2],$profileid1,$profileid2);
				if($id)
				{
					$fto_duplicate_arr["PROFILEID"] = $id;
					$fto_duplicate_arr["TYPE"] = $this->typeOf;
					$fto_duplicate_arr["ENTRY_BY"] = $this->entry_by;
					foreach($detailArr as $k=>$v)
					{
						if($v->getPROFILEID()==$id)
						{
                                                    
							if(!strstr($v->getSUBSCRIPTION(),"F"))
							{
								$IntlObj =  new INCENTIVE_NEGATIVE_TREATMENT_LIST;
								$lastInsertedId=$IntlObj->addRecord($fto_duplicate_arr,1);

								if($fto_duplicate_arr["FLAG_VIEWABLE"]=="N")	//Delete entry from search tables
								{
									$NsmObj = new NEWJS_SEARCH_MALE;
									$NsmObj->deleteRecord($id);
									unset($NsmObj);
									$NsfObj = new NEWJS_SEARCH_FEMALE;
									$NsfObj->deleteRecord($id);
									unset($NsfObj);
								}
							}
                                                        
                                                        try {
                                                      //send mail to the second profile marked as duplicate
                                                      if($lastInsertedId)
                                                            duplicateProfilesMail::sendEmailToDuplicateProfiles($id);
                                                    } 
                                                    catch (Exception $ex) {
                                                    }
							break;
						}
					}
					
					foreach($detailArr as $k=>$v)			//THIS LOOP IS TO MARK STATE AS 'G' AS TOLD BY ESHA
					{
						if($v->getPROFILEID()==$id)
						{
							$action = FTOStateUpdateReason::MARK_DUPLICATE;
							$v->getPROFILE_STATE()->updateFTOState($v, $action);
							break;
						}
					}

				}
			}
			unset($detailArr);
		}
	}

	/*
	*This function compares the entry dates of the 2 duplicate profiles with the fto live date
	*@param fto live date, entry date of profile 1, entry date of profile 2, profileid1, profileid2
	*/
	private function compareDates($entry_dt1,$entry_dt2,$profileid1,$profileid2)
	{
		$timestamp1 = JSstrToTime($entry_dt1);
		$timestamp2 = JSstrToTime($entry_dt2);
		
			if($timestamp1>=$timestamp2)
				return $profileid1;
			else
				return $profileid2;
		
		
	}
}
?>
