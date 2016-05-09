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





} // end of Duplicate
?>
