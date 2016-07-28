<?php

/**
 * class Duplicate
 * 
 */
class Duplicate
{


   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private $rawDuplicateObj=array();
  private $commentsImplode="----";
  private $reasonImplode=",";

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function insertDuplicates( ) {
	  //var_dump($this->rawDuplicateObj[0]);die;
	  $cnt=count($this->rawDuplicateObj);
	  
	  if(is_array($this->rawDuplicateObj))
	  {
		foreach($this->rawDuplicateObj as $profile=>$rawObj)
		{
			if($rawObj->getIsDuplicate()==IS_DUPLICATE::YES)
				DuplicateHandler::HandleDuplicatesInsert($rawObj);
			if($rawObj->getIsDuplicate()==IS_DUPLICATE::PROBABLE)
			{
				$rawObj->setCurrentState(CURRENT_STATE::PROBABLE);	
				DuplicateHandler::HandleProbableDuplicatesInsert($rawObj);
			}		
		}	
	  }
  } // end of member function insertDuplicates

  /**
   * 
   *
   * @param RawDuplicate rawduplicate 

   * @return 
   * @access public
   */
  public function addRawDuplicateObj(RawDuplicate $rawduplicate) {
	  
	  $dnt_update=0;
	  
	  //if 2 profiles are already marked as not duplicate, they  won't be marked as probable or duplicates
	  if(DuplicateHandler::AlreadyMarkedNotDuplicate($rawduplicate))
		$dnt_update=1;
	  
	  //Checks if entry is already present but reason is different.
	  if(isset($this->rawDuplicateObj[$rawduplicate->getProfileid2()]) && $dnt_update==0)
	  {
		  
		 $alreadyPresetObj=$this->rawDuplicateObj[$rawduplicate->getProfileid2()];
		  
		 if(($rawduplicate->getIsDuplicate()==IS_DUPLICATE::PROBABLE && $alreadyPresetObj->getIsDuplicate()==IS_DUPLICATE::PROBABLE) || ($rawduplicate->getIsDuplicate()==IS_DUPLICATE::YES && $alreadyPresetObj->getIsDuplicate()==IS_DUPLICATE::YES))
		 {
			 $comments=$rawduplicate->getComments().$this->commentsImplode.$alreadyPresetObj->getComments();
			 $rawduplicate->setComments($comments);
			 $reason=$rawduplicate->getReason().$this->reasonImplode.$alreadyPresetObj->getReason();
			 $rawduplicate->setReason($reason);
			 
		 }
		 else if($rawduplicate->getIsDuplicate()==IS_DUPLICATE::PROBABLE && $alreadyPresetObj->getIsDuplicate()==IS_DUPLICATE::YES)
		 {
			 $dnt_update=1;
		 }
		 
	  }
	  
		
	  if($dnt_update==0)
		{
			if(!$rawduplicate->getScreenAction())
				$rawduplicate->setScreenAction(SCREEN_ACTION::NONE);
				
			$this->rawDuplicateObj[$rawduplicate->getProfileid2()]=$rawduplicate;
		}		
	  
	 
  } // end of member function addRawDuplicateObj



// implements the new logic for duplicate profiles added by Palash based on phone verification updation
public static function logIfDuplicate($profileObj,$phoneNumVerified){

if(!$profileObj || !$phoneNumVerified) return;
$startDate=date('Y-m-d H:i:s',(strtotime ( '-90 days'  ) ));
$endDate=date('Y-m-d H:i:s');
$profileId=$profileObj->getPROFILEID();    
if($phoneVerRow=(new PHONE_VERIFIED_LOG())->getLogForOtherNumberVerified($profileId,$phoneNumVerified,$startDate,$endDate))
{
	$profileObj2=new Profile();
	foreach ($phoneVerRow as $key => $value) {
		# code...

		$profileObj2->getDetail($value['PROFILEID'],'PROFILEID','PROFILEID,ACTIVATED,INCOMPLETE,GENDER,ENTRY_DT');
		if( ($profileObj2->getACTIVATED() != 'D') && ($profileObj->getACTIVATED() != 'D') && ($profileObj2->getINCOMPLETE() != 'Y') && ($profileObj2->getGENDER()==$profileObj->getGENDER()))
		{
			$rawDuplicateObj=new RawDuplicate();
			$timeStamp1=JSstrToTime($profileObj->getENTRY_DT());
			$timeStamp2=JSstrToTime($profileObj2->getENTRY_DT());
			
			if($timeStamp1 > $timeStamp2)
			{
				$rawDuplicateObj->setProfileid2($profileId); //profile found as a duplicate
				$rawDuplicateObj->setProfileid1($value['PROFILEID']); 
			}
			else 
			{	
				$rawDuplicateObj->setProfileid2($value['PROFILEID']); //profile found as a duplicate
				$rawDuplicateObj->setProfileid1($profileId); 
			}	
			$rawDuplicateObj->setReason(REASON::PHONE); 
			$rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::YES); 
			$rawDuplicateObj->addExtension('MARKED_BY','SYSTEM');
	  		$rawDuplicateObj->setScreenAction(SCREEN_ACTION::NONE);
	  		$rawDuplicateObj->addExtension('IDENTIFIED_ON',date('Y-m-d H:i:s'));
	  		$rawDuplicateObj->setComments("None");
			DuplicateHandler::HandleDuplicatesInsert($rawDuplicateObj);
//			duplicateProfilesMail::sendEmailToDuplicateProfiles($profileId);

		}
	

	}



}


}


} // end of Duplicate
?>
