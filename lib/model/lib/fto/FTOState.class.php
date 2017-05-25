<?php

// @Author:Esha

/*****************************************************************
  class FTOState
  Description: handles calculationg, updating and returning fto states
  Properties:
  $state- fto state of user
  $subState- fto substate of user
  $expiryDate- the date on which fto expires
  $remainingDays- no of days remaining for fto to expire
  $comment- reason or comment for change of state or substate
 *****************************************************************/
class FTOState

{
  private $state;
  private $subState;
  private $entryDate;
  private $expiryDate;
  private $remainingDays;
  private $inboundAcceptLimit;
  private $outboundAcceptLimit;
  private $totalAcceptLimit;
  private $acceptanceFlag;
  private $comment;
  public function __construct(Profile $profile)
  {
    try
    {
      $this->initialize($profile);
    }

    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

  /***********************************
    getters for state, subState, expiryDate and remainingDays
   ***********************************/
  public function getState()
  {
    return $this->state;
  }

  public function getSubState()
  {
    return $this->subState;
  }

  public function getRemainingDays()
  {
    return $this->remainingDays;
  }

  public function getInboundAcceptLimit()
  {
    return $this->inboundAcceptLimit;
  }

  public function getOutBoundAcceptLimit()
  {
    return $this->outboundAcceptLimit;
  }

  public function getTotalAcceptLimit()
  {
    return $this->totalAcceptLimit;
  }

  public function getAcceptanceFlag()
  {
    return $this->acceptanceFlag;
  }
  public function getEntryDate()
  {
    $orgTZ = date_default_timezone_get();
    date_default_timezone_set("Asia/Calcutta");
    $entryDate =JSstrToTime($this->entryDate);
    $entryDate = date("Y-m-d H:i:s", $entryDate);
    date_default_timezone_set($orgTZ);
    return $entryDate;
  }

  //Input n/null Output expiry date in IST
  //Input y Output expiry date in js M, Y format
  //Input s Output expiry date in EST
  public function getExpiryDate($viewFormat = 'n')
  {
    if($viewFormat == 's') return $this->expiryDate;
    $orgTZ = date_default_timezone_get();
    date_default_timezone_set("Asia/Calcutta");
    $expiryDate =JSstrToTime($this->expiryDate);
    $expiryDate = date("Y-m-d H:i:s", $expiryDate);
    if ('y' === $viewFormat)
    {
      $expiryDate = date("jS M, Y", JSstrToTime($expiryDate));
    }
    date_default_timezone_set($orgTZ);

    return $expiryDate;
  }

  public function getEntryDay($mailerDisplay='')
  {
    if($mailerDisplay)
      return date("j",JSstrToTime($this->getEntryDate()));
    else
      return date("d",JSstrToTime($this->getEntryDate()));
  }
  public function getEntryDaySuffix()
  {
    return date("S",JSstrToTime($this->getEntryDate()));
  }
  public function getEntryMonth()
  {
    return date("M",JSstrToTime($this->getEntryDate()));
  }
  public function getEntryYear()
  {
    return date("Y",JSstrToTime($this->getEntryDate()));
  }
  public function getExpiryDay($mailerDisplay='')
  {
    if($mailerDisplay)
      return date("j",JSstrToTime($this->getExpiryDate()));
    else
      return date("d",JSstrToTime($this->getExpiryDate()));
  }

  public function getExpiryDaySuffix()
  {
    return date("S",JSstrToTime($this->getExpiryDate()));
  }
  public function getExpiryMonth($mailerDisplay='')
  {
    if($mailerDisplay)
      return strtoupper(date("M",JSstrToTime($this->getExpiryDate())));
    else
      return date("M",JSstrToTime($this->getExpiryDate()));
  }
  public function getExpiryYear()
  {
    return date("Y",JSstrToTime($this->getExpiryDate()));
  }
  public function getExpiryDays()
  {
    $today = date('Y-m-d H:m:s');
    $days = floor(abs(JSstrToTime($today) - JSstrToTime($this->getExpiryDate())) / (60 * 60 * 24));
    return $days;
  }
  public function getFTOdays()
  {
    $days = date_create($this->getEntryDate())->diff(date_create($this->getExpiryDate()));
    return $days->format('%a');
    //      return $days = JsCommon::dateDiff($this->getEntryDate(),$this->getExpiryDate());
  }

  private	function initialize(Profile $profile)
  {
    /*if(!(FTOLiveFlags::IS_FTO_LIVE))
    {
      $this->state = FTOStateTypes::NEVER_EXPOSED;
      $this->subState = FTOSubStateTypes::NEVER_EXPOSED;
      return;
    }*/
    $profileid = $profile->getPROFILEID();
    $stateArray = FTOStateHandler::getFTOCurrentState($profileid);
    if ($stateArray)
    {
      $this->state = $stateArray['STATE'];
      $this->subState = $stateArray['SUBSTATE'];
      $this->entryDate = $stateArray['FTO_ENTRY_DATE'];
      $this->expiryDate = $stateArray['FTO_EXPIRY_DATE'];
      $this->inboundAcceptLimit = $stateArray['INBOUND_LIMIT'];
      $this->outboundAcceptLimit = $stateArray['OUTBOUND_LIMIT'];
      $this->totalAcceptLimit = $stateArray['TOTAL_LIMIT'];
      $this->acceptanceFlag = $stateArray['FLAG'];
    }

    if ($this->state == '') 
    {
      $this->state = FTOStateTypes::NEVER_EXPOSED;
      $this->subState = FTOSubStateTypes::NEVER_EXPOSED;
    }
    else $this->remainingDays = FTOStateHandler::calculateRemainingDays($this->expiryDate);
  }

  /***********************************
function :calculateState
calculate current state of profile--complete state and sub state calculation logic in this
   ***********************************/
  private function calculateState(Profile $profile, $action = '')
  {
    try {

      // echo "ACTION: ".$action. "\n";

      if 
        ( ($this->state == FTOStateTypes::NEVER_EXPOSED && $action != FTOStateUpdateReason::REGISTER) ||

          ($this->state == FTOStateTypes::FTO_EXPIRED && in_array($this->subState,array( FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED, FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED))) ||

          ($this->state == FTOStateTypes::DUPLICATE && $action!=FTOStateUpdateReason::MARK_NON_DUPLICATE) )
          return;
      $expire = 0;
      if((FTOLiveFlags::IS_FTO_LIVE))
      {
	if($this->expiryDate && $this->expiryDate<=JsCommon::currentTime() && $profile->getINCOMPLETE()!='Y'&& $profile->getACTIVATED()!='U'&& $action!=FTOStateUpdateReason::SCREEN && $action!=FTOStateUpdateReason::INCOMPLETE_TO_COMPLETE)
		$expire = 1;
      }
      else
      {
	if($this->expiryDate && $this->expiryDate<=JsCommon::currentTime() && $profile->getACTIVATED()!='U'&& $action!=FTOStateUpdateReason::SCREEN)
		$expire = 1;
      }
      if($expire == 1)
      {
        $this->state = FTOStateTypes::FTO_EXPIRED;
        $diff = JsCommon::dateDiff($this->entryDate,$this->expiryDate);
        if(in_array($diff,array(FTO_PERIOD::BEFORE_ACTIVE,FTO_PERIOD::BEFORE_ACTIVE+1)))
          $this->subState = FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED;
        else
          $this->subState = FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED;
/*
        try {
          $emailSender = new EmailSender(MailerGroup::EXPIRY, 1739);
          $emailSender->bulkSend(array($profile->getPROFILEID()), array(array("jeevansathi_contact_address", "jeevansathi_contact_address")));
        }
        catch (Exception $e) {
          jsException::log($e->getMessage()."\n".$e->getTraceAsString());
        }
*/
        return;
      }
      $havePhoto = $this->getHavePhoto($profile);
      $havePhoneVerified = JsCommon::isContactVerified($profile);

      /*
         echo "HAVE PHOTO: ".$profile->getHAVEPHOTO()."\n";
         $phoneVerified=($havePhoneVerified)?'YES':'NO';
         echo "PHONE VERIFIED: ".$phoneVerified."\n";
       */
      if ($havePhoto && $havePhoneVerified)
      {
        $this->state = FTOStateTypes::FTO_ACTIVE;
      }

      elseif ($havePhoto)
      {
        $this->state = FTOStateTypes::FTO_ELIGIBLE;
        $this->subState = FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO;
        return;
      }

      elseif ($havePhoneVerified)
      {
        $this->state = FTOStateTypes::FTO_ELIGIBLE;
        $this->subState = FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO;
        return;
      }
      elseif (!$havePhoto && !$havePhoneVerified)
      {
        $this->state = FTOStateTypes::FTO_ELIGIBLE;
        $this->subState = FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO;
        return;
      }

      if (($this->state == FTOStateTypes::FTO_ACTIVE || $this->state == FTOStateTypes::FTO_EXPIRED)&& $profile->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() != 'Y')
      {
        $ftoContactDetailsObj	=	new FTOContactDetails($profile);
        $numberOfAcceptanceReceived = $ftoContactDetailsObj->getInBoundCount();
        $numberOfAcceptanceMade = $ftoContactDetailsObj->getOutBoundCount();
        $totalNumberOfAcceptances = $ftoContactDetailsObj->getTotalCount();
        /*
           echo "NUMBER OF ACCEPTANCES RECEIVED:".$numberOfAcceptanceReceived."\n";
           echo "NUMBER OF ACCEPTANCES MADE:".$numberOfAcceptanceMade."\n";
           echo "NUMBER OF TOTAL ACCEPTANCES :".$totalNumberOfAcceptances."\n";
           echo "INBOUND ACCEPTANCES LIMIT: ".$ftoContactDetailsObj->getInBoundLimit()."\n";
           echo "OUTBOUND ACCEPTANCES LIMIT: ".$ftoContactDetailsObj->getOutBoundLimit()."\n";
           echo "TOTAL ACCEPTANCES LIMIT: ".$ftoContactDetailsObj->getTotalAcceptanceLimit()."\n";
         */
        if ($numberOfAcceptanceReceived >= $ftoContactDetailsObj->getInBoundLimit())
        {
          $this->state = FTOStateTypes::FTO_EXPIRED;
          $this->subState = FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT;
        }

        if ($ftoContactDetailsObj->getFTOFlag()=='T' && $numberOfAcceptanceMade >= $ftoContactDetailsObj->getOutBoundLimit())
        {
          $this->state = FTOStateTypes::FTO_EXPIRED;
          $this->subState = FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT;
        }

        if ($ftoContactDetailsObj->getFTOFlag()=='T' && $totalNumberOfAcceptances >= $ftoContactDetailsObj->getTotalAcceptanceLimit())
        {
          $this->state = FTOStateTypes::FTO_EXPIRED;
          $this->subState = FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT;
        }

        if ($this->state == FTOStateTypes::FTO_EXPIRED) return;
      }

      $profileMemcacheServiceObj=new ProfileMemcacheService($profile);
      $numberOfEoi = $profileMemcacheServiceObj->get("TOTAL_CONTACTS_MADE");
      /*
         echo "NUMBER OF EOI'S MADE: ".$numberOfEoi."\n";
         echo "EOI LOW THRESHOLD: ".THRESHOLD::LOW_THRESHOLD."\n";
         echo "EOI HIGH THRESHOLD: ".THRESHOLD::HIGH_THRESHOLD."\n";
       */
      if ($this->state == FTOStateTypes::FTO_ACTIVE)
      {
        if ($numberOfEoi == THRESHOLD::LEAST_THRESHOLD)
        {
          $this->subState = FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD;
          return;
        }
        elseif ($numberOfEoi < THRESHOLD::LOW_THRESHOLD)
        {
          $this->subState = FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD;
          return;
        }
        elseif ($numberOfEoi < THRESHOLD::HIGH_THRESHOLD)
        {
          $this->subState = FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD;
          return;
        }
        elseif ($numberOfEoi >= THRESHOLD::HIGH_THRESHOLD)
        {
          $this->subState = FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD;
          return;
        }
      }
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }

  private function checkSameStateAndSubState($oldState, $oldSubState = '')
  {
    if ($oldState == $this->state && $oldSubState == $this->subState) return true;
    else return false;
  }

  public function updateState(Profile $profile, $action = '')
  {
    try
    {
      if((!(FTOLiveFlags::IS_FTO_LIVE))&&$this->state==FTOStateTypes::NEVER_EXPOSED)
      {
        $this->state = FTOStateTypes::NEVER_EXPOSED;
        $this->subState = FTOSubStateTypes::NEVER_EXPOSED;
        return;
      }
      $this->comment = $action;
      $profileExistsInFTOStateLog = FTOStateHandler::profileExistsInFTOStateLog($profile->getPROFILEID());
      if ($action == FTOStateUpdateReason::TAKE_MEMBERSHIP&& !(in_array($this->subState,array(FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED,FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED))))
      {
        if ($this->state != FTOStateTypes::NEVER_EXPOSED)
        {
          $this->state = FTOStateTypes::NEVER_EXPOSED;
          $this->subState = FTOSubStateTypes::NEVER_EXPOSED;
          FTOStateHandler::removeFTOStateOnMembership($profile->getPROFILEID() , $this->comment);
        }
      }
      elseif ($action == FTOStateUpdateReason::MARK_DUPLICATE)
      {
        if ($profileExistsInFTOStateLog)
        {
		if($this->state!=FTOStateTypes::DUPLICATE)
		{
        $this->state = FTOStateTypes::DUPLICATE;
        $this->subState = FTOSubStateTypes::DUPLICATE;
        FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , FTOStateTypes::DUPLICATE, '', $this->comment);
		}
		}

      }
      else
      {
        $oldState = $this->state;
        $oldSubState = $this->subState;
        /*
           echo "OLD STATE: ".$oldState."\n";
           echo "OLD SUBSTATE: ".$oldSubState."\n";
         */
        $this->calculateState($profile, $action);
        if ($this->state == FTOStateTypes::NEVER_EXPOSED) return;
        /*
           echo "NEW STATE: ".$this->state."\n";
           echo "NEW SUBSTATE: ".$this->subState."\n";
           die;
         */
        $profileNeverScreenedBefore= FTOStateHandler:: profileNeverPerformAction($profile->getPROFILEID(),FTOStateUpdateReason::SCREEN);
        if($this->state==FTOStateTypes::DUPLICATE)
        {
	  if($this->getHavePhoto($profile) && JsCommon::isContactVerified($profile))
	  {
	          $this->expiryDate = $this->calculateFTOExpiryDate($profile, $this->entryDate);
        	  FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , $this->state, $this->subState, $this->comment, '', $this->expiryDate);
	  }
        }
	elseif($action == FTOStateUpdateReason::INCOMPLETE_TO_COMPLETE)
	{
          $this->entryDate = JsCommon::currentTime();
          $this->expiryDate = $this->calculateFTOExpiryDate($profile, $this->entryDate);
          FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , $this->state, $this->subState, $this->comment, $this->entryDate, $this->expiryDate);
	}
        elseif (($action == FTOStateUpdateReason::SCREEN && $profileNeverScreenedBefore)|| ($action == FTOStateUpdateReason::REGISTER && !$profileExistsInFTOStateLog))
        {
          $this->entryDate = JsCommon::currentTime();
          $this->expiryDate = $this->calculateFTOExpiryDate($profile, $this->entryDate);
          if($action == FTOStateUpdateReason::SCREEN)
          {
            FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , $this->state, $this->subState, $this->comment, $this->entryDate, $this->expiryDate);
          }
          elseif($action == FTOStateUpdateReason::REGISTER)
          {
            $FTOContactDetailsObj = new FTOContactDetails($profile);
            $FTOContactDetailsArr = $FTOContactDetailsObj->getFTOContactDetailsArr($profile);
            FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , $this->state, $this->subState, $this->comment, $this->entryDate, $this->expiryDate, $FTOContactDetailsArr);
            $this->inboundAcceptLimit = $FTOContactDetailsArr["INBOUND_LIMIT"];
            $this->outboundAcceptLimit = $FTOContactDetailsArr["OUTBOUND_LIMIT"];
            $this->totalAcceptLimit = $FTOContactDetailsArr["TOTAL_LIMIT"];
            $this->acceptanceFlag = $FTOContactDetailsArr["FLAG"];
            $this->remainingDays = FTOStateHandler::calculateRemainingDays($this->expiryDate);
          }
        }
        else
        {
          $same = $this->checkSameStateAndSubState($oldState, $oldSubState); //use id to check if the state is same or different
          if (!$same)
          {
            /*
               echo "NEW STATE: ".$this->state."\n";
               echo "NEW SUBSTATE: ".$this->subState."\n";
             */
	    if($oldState == FTOStateTypes::DUPLICATE && $this->comment!=FTOStateUpdateReason::MARK_NON_DUPLICATE)
		{
			$f = fopen("/tmp/ftoState.txt","a+");
			fwrite($fp,"\n\nDATE:".date('y-m-d h:i:s')."\n\n");
			fwrite($fp,"\nNEXT STATE:".$this->state."\t NEXT SUBSTATE:".$this->subState."\n");
			foreach($_SERVER as $key => $value)
			{
				fwrite($fp,$key."=>".$value."\n");
			}
			fclose($f);
			return;
		}
            $oldExpiryDate = $this->expiryDate;
            if ($this->state == FTOStateTypes::FTO_ACTIVE && $oldState == FTOStateTypes::FTO_ELIGIBLE)
            {
              $this->expiryDate = $this->calculateFTOExpiryDate($profile, $this->entryDate);
              /*
                 echo "OLD EXPIRY DATE: ".$oldExpiryDate."\n";
                 echo "NEW EXPIRY DATE: ".$this->expiryDate."\n";
               */
	      
              FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , $this->state, $this->subState, $this->comment, '', $this->expiryDate);

            }
            else FTOStateHandler::logFTOCurrentState($profile->getPROFILEID() , $this->state, $this->subState, $this->comment);

          }
        }
      }
    }

    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

  public function calculateFTOExpiryDate(Profile $profile, $FTOEntryDate)
  {
    try
    {
      if ($this->state == FTOStateTypes::FTO_ELIGIBLE && FTOStateHandler::profileNeverInThisFTOState($profile->getPROFILEID() , FTOStateTypes::FTO_ACTIVE)) 
        $days = FTO_PERIOD::BEFORE_ACTIVE;
      else
        $days = FTO_PERIOD::AFTER_ACTIVE;

      $FTOEntryTime =JSstrToTime($FTOEntryDate);
      $orgTZ = date_default_timezone_get();
      date_default_timezone_set("Asia/Calcutta");
      $FTOEntryDateIndia = date("Y-m-d H:i:s", $FTOEntryTime);
      $expiryTimeIndia = JSstrToTime($FTOEntryDateIndia . "+" . $days . " days");
      $expiryDate = date("Y-m-d H:i:s",mktime(21,29,59,date("m", $expiryTimeIndia),date("d", $expiryTimeIndia),date("Y", $expiryTimeIndia)));
      $expiryTime = JSstrToTime($expiryDate);
      date_default_timezone_set($orgTZ);
      $returnTime = date('Y-m-d H:i:s', $expiryTime);//JsCommon::getTimeForTimeZone($returnTime, JsCommon::getUsTimeZoneConst());
      return $returnTime;
    }

    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
  public function getHavePhoto($profile)
  {
    return ($profile->getHAVEPHOTO() == 'Y'||$profile->getHAVEPHOTO()=='U') ? true : false;
  }
}

?>
