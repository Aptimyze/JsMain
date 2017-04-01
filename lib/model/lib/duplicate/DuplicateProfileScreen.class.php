<?php
/**
 * class DuplicateProfileScreen
 * 
 */

class DuplicateProfileScreen 
{
  /**
   * 
   *
   * @return 
   * @access public
   */
  public function fetchProbableDuplicate($exec,$pid) {
	  $rawDuplicateObj=new RawDuplicate();
	  $rawDuplicateObj->setScreenedBy($exec);
	  if($pid)
	  	$rawDuplicateObj->setProfileid1($pid);
	  $rawDuplicateObj->setScreenAction(SCREEN_ACTION::NONE);
	  $rawDuplicateObj->setCurrentState(CURRENT_STATE::PROBABLE);
	  $rawDuplicateObj=DuplicateHandler::getProbableDuplicate($rawDuplicateObj);
	  return  $rawDuplicateObj;  
  } // end of member function fetchProbableDuplicate

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function fetchCantsayDuplicate($exec,$pid) {
          $rawDuplicateObj=new RawDuplicate();
          $rawDuplicateObj->setScreenedBy($exec);
          if($pid)
                $rawDuplicateObj->setProfileid1($pid);
          $rawDuplicateObj->setScreenAction(SCREEN_ACTION::OUT);
          $rawDuplicateObj->setCurrentState(CURRENT_STATE::PROBABLE);
          $rawDuplicateObj=DuplicateHandler::getProbableDuplicate($rawDuplicateObj,1);
          return  $rawDuplicateObj;
  } // end of member function fetchProbableDuplicate

   /**
   * 
   *
   * @return 
   * @access public
   */
  public function fetchCountScreenedProfiles($st_date,$end_date,$flag) {
	  $logObj=new DUPLICATE_PROFILE_LOG('newjs_slave');
          if($flag == 'DUP_pair')
          {
                $cnt_arr = $logObj->fetchCountDuplicateProfileLog($st_date,$end_date,'EXECUTIVE');
                $cnt_arr_id = $logObj->fetchCountDuplicateProfileIdentified($st_date,$end_date);
                $report = $cnt_arr_id;
          }
	  else if($flag == 'SE_exec')
		$cnt_arr = $logObj->fetchCountDuplicateProfileLog($st_date,$end_date);
	  elseif($flag == 'IE_sup')
		$cnt_arr = $logObj->fetchCountDuplicateProfileLog($st_date,$end_date,'SUPERVISOR');
	  else
		$cnt_arr = $logObj->fetchCountDuplicateProfileLog($st_date,$end_date,'EXECUTIVE');
	  for($i=0;$i<count($cnt_arr);$i++)
	  {
                if($flag == 'DUP_pair')
                {
                        $edate=date("Y-m-d",JSstrToTime($cnt_arr[$i]["ENTRY_DATE"]));
                        $report[$edate]["total"]++;

                        $identified_dt=date("Y-m-d",JSstrToTime($cnt_arr[$i]["IDENTIFIED_ON"]));
                        $screened_2Days =date("Y-m-d",JSstrToTime("$edate -1 days"));
                        $screened_3Days =date("Y-m-d",JSstrToTime("$edate -2 days"));
                        if($identified_dt == $edate)
                                $report[$edate]["total_1Day"]++;
                        else if($identified_dt == $screened_2Days)
                                $report[$edate]["total_2Day"]++;
                        else if($identified_dt == $screened_3Days)
                                $report[$edate]["total_3Day"]++;
                }
		else if($flag == 'SE_exec')
		{
			$loggedin=$cnt_arr[$i]["SCREENED_BY"];
			$report[$loggedin]["total"]++;
			if($cnt_arr[$i]["IS_DUPLICATE"]=='YES')
				$report[$loggedin]["dup"]++;
			if($cnt_arr[$i]["IS_DUPLICATE"]=='NO')
				$report[$loggedin]["nodup"]++;
			if($cnt_arr[$i]["IS_DUPLICATE"]=='PROBABLE' || $cnt_arr[$i]["IS_DUPLICATE"]=='CANTSAY')
				$report[$loggedin]["prob"]++;
		}
		else
		{
			$edate=date("Y-m-d",JSstrToTime($cnt_arr[$i]["ENTRY_DATE"]));
			$report[$edate]["total"]++;
			if($cnt_arr[$i]["IS_DUPLICATE"]=='YES')
				$report[$edate]["dup"]++;
			if($cnt_arr[$i]["IS_DUPLICATE"]=='NO')
				$report[$edate]["nodup"]++;
			if($cnt_arr[$i]["IS_DUPLICATE"]=='PROBABLE' || $cnt_arr[$i]["IS_DUPLICATE"]=='CANTSAY')
				$report[$edate]["prob"]++;
		}
	  }
          return  $report;
  } // end of member function fetchCountScreenedProfiles

  
  /**
   * 
   *
   * @param int profileid 
   * @return 
   * @access public
   */
  public function removeDuplicateRelation( $profileid1,$profileid2,$comments,$screened_by ) {
	$rawDuplicateObj=new RawDuplicate();
	$rawDuplicateObj->setProfileid1($profileid1);
	$rawDuplicateObj->setProfileid2($profileid2);
	$rawDuplicateObj->setScreenedBy($screened_by);
	$rawDuplicateObj->setComments($comments);
	$rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::NO);
	$rawDuplicateObj->setScreenAction(SCREEN_ACTION::OUT);
	$rawDuplicateObj->setReason(REASON::NONE);
	if(!(DuplicateHandler::IsPermanentDuplicate($rawDuplicateObj)))
	{

		DuplicateHandler::DuplicateProfilelog($rawDuplicateObj);

		DuplicateHandler::MarkPermanentNotDuplicate($rawDuplicateObj);
	}
	  
  } // end of member function removeDuplicateRelation

  /**
   * 
   *
   * @param int profileid 
   * @return 
   * @access public
   */
  public function removeDuplicate($profileid1 ) {
	$rawDuplicateObj=new RawDuplicate();
	$rawDuplicateObj->setProfileid1($profileid1);
	DuplicateHandler::HandleProfileNotDuplicate($rawDuplicateObj);
  } // end of member function removeDuplicate
  

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function updateProbableDuplicate($param_arr) {
	  $rawDuplicateObj=new RawDuplicate();
	  $rawDuplicateObj->setProfileid1($param_arr["profileid1"]);
	  $rawDuplicateObj->setProfileid2($param_arr["profileid2"]);
	  $rawDuplicateObj->setEntryDt(date(RawDuplicate::current_date));
	  $rawDuplicateObj->setReason($param_arr["reason"]);
	  $rawDuplicateObj->addExtension('IDENTIFIED_ON',$param_arr["identified_on"]);
	  $rawDuplicateObj->setComments($param_arr["comments"]);
	  $rawDuplicateObj->setScreenedBy($param_arr["screened_by"]);
	  $rawDuplicateObj->setIsDuplicate($param_arr["isDuplicate"]);
	  $rawDuplicateObj->setScreenAction($param_arr["screen_action"]);
	  $rawDuplicateObj->addExtension('MARKED_BY',$param_arr["marked_by"]);
	  DuplicateHandler::HandleProbableUpdates($rawDuplicateObj);
  } // end of member function updateProbableDuplicate

  /**
   * 
   *
   * @param int profileid 
   * @return 
   * @access public
   */
  public function fetchDuplicate($profileid)
  {
	  return DuplicateHandler::getProfileDuplicates($profileid);
  } // end of member function fetchDuplicate

  /**
   * 
   *
   * @param int profileid 
   * @return 
   * @access public
   */
   public function addDuplicates($arr,$user,$comments)
   {
	$duplicate=new Duplicate();
	$first=$arr[0];
	$rawDuplicateObj=new RawDuplicate;
	$rawDuplicateObj->setProfileid1($first);
	$rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::YES);
	$rawDuplicateObj->setScreenAction(SCREEN_ACTION::OUT);
	$rawDuplicateObj->setScreenedBy($user);
	$rawDuplicateObj->setReason(REASON::NONE);
	$rawDuplicateObj->setComments($comments);
	for($i=0;$i<count($arr);$i++)
	{
		$first=$arr[$i];
		$rawDuplicateObj->setProfileid1($first);
		for($j=$i+1;$j<count($arr);$j++)
		{
			$second=$arr[$j];
			$rawDuplicateObj->setProfileid2($second);
			DuplicateHandler::HandleDuplicatesInsert($rawDuplicateObj);
		}
	}
  } // end of member function addDuplicates

   /**
   * 
   *
   * @return 
   * @access public
   */
  public function fetchArchiveInfo($pid,$paramArr="") {

        include_once(sfConfig::get("sf_web_dir")."/classes/shardingRelated.php");
        include_once(sfConfig::get("sf_web_dir")."/classes/Mysql.class.php");
        $pidShard=JsDbSharding::getShardNo($pid);
		$dbMessageLogObj=new NEWJS_MESSAGE_LOG($pidShard,'newjs_slave');
        
        $archiveInfoObj         =new ARCHIVE_FOR_DUPLICATE('newjs_slave');
        $contactArchiveInfo     =$archiveInfoObj->getContactsArchive($pid,$paramArr);
        $alternateNumInfo       =$archiveInfoObj->getAlternatePhone($pid);
        $contactIpInfo          =$dbMessageLogObj->getContactIP($pid);
        $paymentIpInfo          =$archiveInfoObj->getPaymentIP($pid);
	$userNameInfo           =$archiveInfoObj->getNameOfUser($pid);
        $archiveInfo            =array("CONTACT"=>$contactArchiveInfo,"ALTERNATE_NUM"=>$alternateNumInfo,"CONTACT_IP"=>$contactIpInfo,"PAYMENT_IP"=>$paymentIpInfo,"USERNAME"=>$userNameInfo);
        return $archiveInfo;

 } // end of member function fetchArchiveInfo 

  public function stringCompValidation($searchForArr,$SearchInArr){

	$flagValArr =array();
	$rep_values =array(" ","-","(", ")","+");
	foreach($searchForArr as $key=>$keyval)
	{
                $keyval =str_replace($rep_values,'',$keyval);
		if($keyval)
		{		
			foreach($SearchInArr as $key1=>$key1val)
			{
				if($key1val)
				{
					$key1val =str_replace($rep_values,'',$key1val);
					$res1 =stristr($keyval,$key1val);
					$res2 =stristr($key1val,$keyval);
					if($res1 || $res2){
						$flagValArr[$key] ='Y';
						break;
					}
				}
			}
		}
	}
	return $flagValArr;
  }		

} // end of DuplicateProfileScreen 
?>
