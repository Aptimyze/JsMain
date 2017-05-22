<?php
/**
 * class DuplicateHandler
 * 
 */
class DuplicateHandler
{


   /*** Attributes: ***/

    
  /**
   * 
   *
   * @param RawDuplicate duplicateDataObj 

   * @return 
   * @static
   * @access public
   */
   
  public static function HandleDuplicatesInsert( rawDuplicate $rawDuplicateObj,$no_log=0 ) {
	  $dupCon=new DUPLICATES_PROFILES();
	  
	  $first=$rawDuplicateObj->getProfileid1();
	  $second=$rawDuplicateObj->getProfileid2();
	  $groupids=$dupCon->getDuplicateID($rawDuplicateObj);

// added by Palash to ensure that once if a pair is marked confirmed duplicate then it cant be marked again as duplicate
	  $alreadyLogged=(new DUPLICATE_PROFILE_LOG())->fetchResultForAPair($first,$second);
	  if($alreadyLogged['IS_DUPLICATE']=='YES') return;
//////////////////////////////////////////////	

	//Added by Anand to handle duplicate profiles for fto
	$HfdObj = new HandleFtoDuplicate;
       	$HfdObj->ftoDuplicateLogic($first,$second);	  
	//Added by Anand ends

	///////////////////////////////////
	
	  if(!is_array($groupids)) //No profile found
	  {
		  $dup_id=new DUPLICATES_GROUPID();
		  $groupids[0]=$dup_id->createGROUPID();
	  }
	  
	  if(count($groupids)==1)//1 profile is already present in db.
	  {
		  $dupCon->updateProfileGroupID($groupids[0],$first,$second);
	  }
	  else if(count($groupids)>1)//Both have their entry in duplicate profiles.
	  {
		  $dupCon->updateGroupID($groupids[0],$groupids[1]);
	  }
	  
	if($no_log==0)
		DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);

  } // end of member function HandleDuplicatesInsert

  /**
   * 
   *
   * @param RawDuplicate duplicateDataObj 

   * @return 
   * @static
   * @access public
   */
  public static function HandleProbableDuplicatesInsert( RawDuplicate $rawDuplicateObj ) {
	  
	  $rawDuplicateObj->setEntryDt(date(RawDuplicate::current_date));
	  $probRes=new PROBABLE_DUPLICATES();
	  
	  //If entry is already present with different reason, then update the current table 
		$prevReason=$probRes->ReasonPresent($rawDuplicateObj);
		if($prevReason)
		{
			//Update reason with current reason
			$rawDuplicateObj->setReason($rawDuplicateObj->getReason().",".$prevReason);
			$probRes->updatePreviousProbable($rawDuplicateObj);
		}
		else
		{
			$probRes->insertProbable($rawDuplicateObj);
		}
		DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
	  
	  
  } // end of member function HandleProbableDuplicatesInsert

  /**
   * 
   *
   * @param RawDuplicate duplicateDataObj 

   * @return 
   * @static
   * @access public
   */
  public static function HandleProbableUpdates( RawDuplicate $rawDuplicateObj) {
	  
	  $ProbableRes=new PROBABLE_DUPLICATES();
	  if($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::YES) //profiles marked as duplicate
	  {
		$ProbableRes->removeProbable($rawDuplicateObj);
		$ProbableRes->unsetPriority($rawDuplicateObj);
		DuplicateHandler::HandleDuplicatesInsert($rawDuplicateObj);
	  }
	  elseif($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::NO) //profiles marked as non duplicate
	  {
		  $ProbableRes->removeProbable($rawDuplicateObj);
		  $ProbableRes->unsetPriority($rawDuplicateObj);
		  DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
		  DuplicateHandler::MarkNotDuplicate($rawDuplicateObj);
	  }
	  elseif($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::CANTSAY) //profile marked cant say by supervisor
	  {
		$ProbableRes->removeProbable($rawDuplicateObj);
		DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
          }
	  elseif($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::PROBABLE) //profiles marked cant say by executive
	  {
		$ProbableRes->updateProbable($rawDuplicateObj);
		$ProbableRes->unsetPriority($rawDuplicateObj);
		DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
	  }
  } // end of member function HandleProbableUpdates
  /**
   * 
   *
   * @param RawDuplicate rawDuplicateObj 

   * @return 
   * @static
   * @access public
   */
  public static function MarkNotDuplicate(RawDuplicate $rawDuplicateObj) {
	  return 1;
	  $obj=new MARK_NOT_DUPLICATE();
	  $obj->MarkNotDuplicates($rawDuplicateObj);
  } // end of member function MarkNotDuplicate
  
  
  public static function MarkPermanentNotDuplicate(RawDuplicate $rawDuplicateObj) {
	  $obj=new PERMANENT_NOT_DUPLICATE();
	  $obj->MarkPermanentNotDuplicates($rawDuplicateObj);
  } // end of member function MarkPermanentNotDuplicate
  
    
  
public static function IsPermanentDuplicate(RawDuplicate $rawDuplicateObj){
	$obj=new PERMANENT_NOT_DUPLICATE();
	return $obj->IsEntryPresent($rawDuplicateObj);
}
  /**
   * 
   *
   * @param RawDuplicate duplicateDataObj 

   * @return 
   * @static
   * @access public
   */
  public static function HandleProfileNotDuplicate( RawDuplicate $rawDuplicateObj ) {
	  $dp=new DUPLICATES_PROFILES();
	  $dp->removeProfileAsDuplicate($rawDuplicateObj->getProfileid1());
  } // end of member function HandleProfileNotDuplicate

  /**
   * 
   *
   * @param int profileid 

   * @return RawDuplicate
   * @static
   * @access public
   */
  public static function getProfileDuplicates( $profileid ) {
	  $dupCon=new DUPLICATES_PROFILES();
	  return $dupCon->getProfileDuplicates($profileid);
	  
  } // end of member function getProfileDuplicates

  /**
   * 
   *
   * @param int profileid 

   * @return RawDuplicate
   * @static
   * @access public
   */
  public static function getProfileProbableDuplicates( $profileid ) {
		
  } // end of member function getProfileProbableDuplicates

  /*e
   * 
   * @param 
   * @return RawDuplicate
   * @static
   * @access public
   */
  public static function getProbableDuplicate(RawDuplicate $rawDuplicateObj,$sup=0) {
		$alreadyExist = 0;
		$ProbableRes=new PROBABLE_DUPLICATES();
		$ProbableLogRes=new DUPLICATE_PROFILE_LOG();
		if($sup)
		{
			$rawDuplicateObj->setScreenAction(SCREEN_ACTION::OUT);
			if($rawDuplicateObj->getProfileid1())
			        $result=$ProbableRes->fetchProbableDuplicateOfProfileForSupervisor($rawDuplicateObj);	
			else
			{
				$totalIn=$ProbableRes->fetchProbableDuplicateOut($rawDuplicateObj);
				
                                for($i=0;$i<count($totalIn);$i++)
                                {
                                        $rawDuplicateObj->setProfileid1($totalIn[$i][PROFILE1]);
                                        $logResult = $ProbableLogRes->fetchDuplicateProfileLog($rawDuplicateObj);
                                        if($logResult)
                                        {
                                                $alreadyExist = 1;
                                                $result[PROFILE1]=$totalIn[$i][PROFILE1];
                                                $result[PROFILE2]=$totalIn[$i][PROFILE2];
                                                $result[REASON]=$totalIn[$i][REASON];
                                                $result[ENTRY_DATE]=$totalIn[$i][ENTRY_DATE];
                                                $result[CURRENT_STATE]=$totalIn[$i][CURRENT_STATE];
                                                $result[SCREEN_ACTION]=$totalIn[$i][SCREEN_ACTION];
                                                break;
                                        }
                                }
			}
			if(!$result&&!$logResult)
			{
				$totalOut=$ProbableRes->fetchProbableDuplicateOutForSupervisor($rawDuplicateObj);				
				for($i=0;$i<count($totalOut);$i++)
				{	
					$rawDuplicateObj->setProfileid1($totalOut[$i][PROFILE1]);
					$logResult = $ProbableLogRes->fetchDuplicateProfileLogForSup($rawDuplicateObj);				
//					echo $rawDuplicateObj->getProfileid1();
//					print_r($logResult);
//					die("oteri");				
					if(!$logResult)
					{
						$result[PROFILE1]=$totalOut[$i][PROFILE1];
                                                $result[PROFILE2]=$totalOut[$i][PROFILE2];
                                                $result[REASON]=$totalOut[$i][REASON];
                                                $result[ENTRY_DATE]=$totalOut[$i][ENTRY_DATE];
                                                $result[CURRENT_STATE]=$totalOut[$i][CURRENT_STATE];
                                                $result[SCREEN_ACTION]=$totalOut[$i][SCREEN_ACTION];
						break;
					}
				}
			}
		}
		else
		{
			$rawDuplicateObj->setScreenAction(SCREEN_ACTION::IN);
			if($rawDuplicateObj->getProfileid1())
				$result=$ProbableRes->fetchProbableDuplicateOfProfile($rawDuplicateObj);			
			else
			{	
				$totalIn=$ProbableRes->fetchProbableDuplicateIn($rawDuplicateObj);
				for($i=0;$i<count($totalIn);$i++)
				{
					$rawDuplicateObj->setProfileid1($totalIn[$i][PROFILE1]);
                                	$logResult = $ProbableLogRes->fetchDuplicateProfileLog($rawDuplicateObj);				
					if($logResult)
					{	
						$alreadyExist = 1;
						$result[PROFILE1]=$totalIn[$i][PROFILE1];
						$result[PROFILE2]=$totalIn[$i][PROFILE2];
						$result[REASON]=$totalIn[$i][REASON];
						$result[ENTRY_DATE]=$totalIn[$i][ENTRY_DATE];
						$result[CURRENT_STATE]=$totalIn[$i][CURRENT_STATE];
						$result[SCREEN_ACTION]=$totalIn[$i][SCREEN_ACTION];
						break;
					}
				}
			}
			if(!$result&&!$logResult)
			{
				$rawDuplicateObj->setScreenAction(SCREEN_ACTION::NONE);
				$result=$ProbableRes->fetchProbableDuplicate($rawDuplicateObj);	
			}
		}
		
		if(is_array($result))
		{	
			$rawDuplicateObj->setProfileid1($result[PROFILE1]);
			$rawDuplicateObj->setProfileid2($result[PROFILE2]);
			$rawDuplicateObj->setReason($result[REASON]);
			$rawDuplicateObj->addExtension('IDENTIFIED_ON',$result[ENTRY_DATE]);
			$rawDuplicateObj->setCurrentState($result[CURRENT_STATE]);
			$rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::PROBABLE);
			if($sup)
			{	
				$logResult = $ProbableLogRes->fetchDuplicateProfileLog($rawDuplicateObj,1);
				$rawDuplicateObj->setComments($logResult[COMMENTS]);
				$rawDuplicateObj->addExtension('MARKED_BY','SUPERVISOR');
			}
			else
				$rawDuplicateObj->addExtension('MARKED_BY','EXECUTIVE');
			
			//Need a entry in log stating coming in screening
			if(!$alreadyExist)
			{
				if($rawDuplicateObj->getScreenAction()!='OUT')
				{
					$rawDuplicateObj->setScreenAction(SCREEN_ACTION::IN);	
					$ProbableRes->screenIn($rawDuplicateObj);
					DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
				}
				else
					DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);
			}

			//Update back to original values.
			$rawDuplicateObj->setEntryDt($result[ENTRY_DATE]);
			$rawDuplicateObj->setScreenAction($result[SCREEN_ACTION]);
		}
		
		return $rawDuplicateObj;
	  
  } // end of member function getProbableDuplicate
/**
   * 
   *
   * @param RawDuplicate rawDuplicatebj 

   * @return 
   * @static
   * @access public
   */
  public static function DuplicateProfileLog( RawDuplicate $rawDuplicateObj ) {
	  
	  $dpl=new DUPLICATE_PROFILE_LOG();
	  $rawDuplicateObj->setEntryDt(date(RawDuplicate::current_date));
	 if(!$rawDuplicateObj->getExtension('IDENTIFIED_ON'))
   	  	$rawDuplicateObj->addExtension('IDENTIFIED_ON',"0000-00-00");
	 if(!$rawDuplicateObj->getExtension('MARKED_BY'))
   	  	$rawDuplicateObj->addExtension('MARKED_BY',"SYSTEM");
	 if(!$rawDuplicateObj->getComments())
                $rawDuplicateObj->setComments("comments##OPS#comments_bi##OPS#comments_ti##OPS#comments_mi#");
	  $dpl->insertDuplicateProfileLog($rawDuplicateObj);
	  
  } // end of member function HandleProbableDuplicatesInsert
  
/*
   * 
   *
   * @param RawDuplicate rawDuplicatebj 

   * @return bool
   * @static
   * @access public
   */
  public static function AlreadyMarkedNotDuplicate( RawDuplicate $rawDuplicateObj ) {
	  
	$obj=new MARK_NOT_DUPLICATE();
	if($obj->IsEntryPresent($rawDuplicateObj))
		return true;
		
	unset($obj);
	$obj=new PERMANENT_NOT_DUPLICATE();
	if($obj->IsEntryPresent($rawDuplicateObj))
		return true;
		
	unset($obj);
	$obj=new DUPLICATES_PROFILES();
	$groupArr=$obj->getDuplicateID($rawDuplicateObj);
	if(is_array($groupArr))
	{
		if(count($groupArr)>1)
		{
			if($groupArr[0]==$groupArr[1])
				return true;
		}
	}

	unset($obj);
	if($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::PROBABLE)
	{
		$obj=new PROBABLE_DUPLICATES();
		if($obj->IsEntryPresent($rawDuplicateObj))
			return true;
	}
	
	unset($obj);
	if($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::PROBABLE)
	{
			
		//Getting last login date of both users, shud not exceed 6 months..
		$obj=new JPROFILE();
		$valueArray=array("PROFILEID"=>$rawDuplicateObj->getProfileid1().",".$rawDuplicateObj->getProfileid2());
		$now=date("Y-m-d");
		$noOfMonths = CrawlerConfig::$greaterThanConditions["LAST_LOGIN_DT"];
		$dateValue = CommonUtility::makeTime(date("Y-m-d", JSstrToTime("- $noOfMonths months",JSstrToTime(date("Y-m-d")))));
		$greaterThanArray['LAST_LOGIN_DT'] = $dateValue;
		if($result=$obj->getArray($valueArray,"excludeArray",$greaterThanArray,"PROFILEID,SOURCE"))
		{
			//if any profile is not found.
			if(!(($result[0][PROFILEID]==$rawDuplicateObj->getProfileid1() || $result[0][PROFILEID]==$rawDuplicateObj->getProfileid2())&& ($result[1][PROFILEID]==$rawDuplicateObj->getProfileid1() || $result[1][PROFILEID]==$rawDuplicateObj->getProfileid2())))
				return true;
			
			//If both profile belongs to shoolgloo source, then update screen actino to skip and update comments.
			if(count($result)>1)
                        {
				$sourceArr=array("shoogloo","mailer_adc");
				$firstSource=in_array(strtolower($result[0][SOURCE]),$sourceArr);
				$secondSource=in_array(strtolower($result[1][SOURCE]),$sourceArr);
//				if(strtolower($result[0][SOURCE])=="shoogloo" && strtolower($result[1][SOURCE])=="shoogloo")
				if($firstSource && $secondSource)
	                        {
					$rawDuplicateObj->setScreenAction("SKIP");
					$rawDuplicateObj->setComments($rawDuplicateObj->getComments()." shoogloo or mailer_adc");
				}
			}
		}
		else
			return true;
	}
	 //Check for incomplete
        if(!$result)
        {
                $obj=new JPROFILE();
                $valueArray=array("PROFILEID"=>$rawDuplicateObj->getProfileid1().",".$rawDuplicateObj->getProfileid2());
                $result=$obj->getArray($valueArray,"",'',"PROFILEID,INCOMPLETE");
        }
        if($result)     
        {       
                if($result[0][INCOMPLETE]=='Y' || $result[1][INCOMPLETE]=='Y')
               		return true;
        }
	
	return false;

	  
	  
  } // end of member function HandleProbableDuplicatesInsert

/*****************
 @param: $profileid, $reason( PHONE,PHOTO etc)
 @return array of profiles duplicate to profileid given for the reason given
*********************/
public static function getProbableDuplicateOfProfileByReason($profileid,$reason) 
{
	$duplicateProfiles=new PROBABLE_DUPLICATES();
        return $duplicateProfiles->getProbableDuplicateOfProfileByReason($profileid, $reason);
} /////end of function getProbableDuplicateByReason

} // end of DuplicateHandler
?>
