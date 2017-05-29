<?php
class FTOStateHandler

{
	/**
	 *
	 *
	 *@param: profileid
	 *
	 *@return current state and substate
	 */
	public static function getFTOCurrentState($profileid)
	{
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		$currentStateRow = $ftoCurrentStateObj->getFTOCurrentStateRow($profileid);
		$currentStateID = $currentStateRow['STATE_ID'];
		if ($currentStateRow)
		{
			$ftoStatesObj = new FTO_FTO_STATES;
			$currentStateArray = $ftoStatesObj->getFTOState($currentStateID);
			$currentStateArray['FTO_EXPIRY_DATE'] = $currentStateRow['FTO_EXPIRY_DATE'];
			$currentStateArray['FTO_ENTRY_DATE'] = $currentStateRow['FTO_ENTRY_DATE'];
			$currentStateArray['FLAG'] = $currentStateRow['FLAG'];
			$currentStateArray['INBOUND_LIMIT'] = $currentStateRow['INBOUND_LIMIT'];
			$currentStateArray['OUTBOUND_LIMIT'] = $currentStateRow['OUTBOUND_LIMIT'];
			$currentStateArray['TOTAL_LIMIT'] = $currentStateRow['TOTAL_LIMIT'];
			return $currentStateArray;
		}
	}

	/**
	 *
	 *
	 *@param: profileid
	 *
	 *@return fto expirydate of profile
	 */
	public static function getFTOExpiryDate($profileid)
	{
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		$FTOExpiryDate = $ftoCurrentStateObj->getFTOExpiryDate($profileid);
		return $FTOExpiryDate;
	}

	public static function getFTOEntryDate($profileid)
	{
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		$FTOEntryDate = $ftoCurrentStateObj->getFTOEntryDate($profileid);
		return $FTOEntryDate;
	}
	public static function calculateRemainingDays($FTOExpiryDate)
	{
		$remainingDays = JsCommon::dateDiff(JsCommon::currentTime(),$FTOExpiryDate);
		return (($remainingDays < 0) ? 0 : $remainingDays);
	}

	public static function logFTOCurrentState($profileid, $currentState, $currentSubState = '', $comment = '', $FTOEntryDate = '', $FTOExpiryDate = '', $FTOContactDetailsArr = '')
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$stateID = $ftoStatesObj->getFTOStateID($currentState, $currentSubState);
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		if ($comment == FTOStateUpdateReason::REGISTER) $ftoCurrentStateObj->insertFTOCurrentState($profileid, $stateID, $FTOEntryDate, $FTOExpiryDate, $FTOContactDetailsArr);
		else $ftoCurrentStateObj->updateFTOCurrentState($profileid, $stateID, $FTOEntryDate, $FTOExpiryDate);
		$ftoStateLogObj = new FTO_FTO_STATE_LOG;
		$ftoStateLogObj->logFTOState($profileid, $stateID, $comment);
	}

	public static function profileNeverInThisFTOState($profileid, $state)
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$stateIDArray = $ftoStatesObj->getFTOStateID($state);
		$ftoStateLogObj = new FTO_FTO_STATE_LOG;
		$result = $ftoStateLogObj->profileNeverInThisFTOStateID($profileid, $stateIDArray);
		return $result;
	}

	public static function removeFTOStateOnMembership($profileid, $comment = '')
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$stateID = $ftoStatesObj->getFTOStateID(FTOStateTypes::PAID);
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		$ftoCurrentStateObj->deleteFTOCurrentState($profileid);
		$ftoStateLogObj = new FTO_FTO_STATE_LOG;
		$ftoStateLogObj->logFTOState($profileid, $stateID, $comment);
	}

	public static function getNonCompleteExpiredProfilesWithExpiryNow()
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$expiredStateIDArray = $ftoStatesObj->getFTOStateIDArrayForSubStates(array(
			FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED,
			FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED,
			FTOSubStateTypes::DUPLICATE
		)); //not considering paid as paid profiles are deleted from current state table
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		$detailArray = $ftoCurrentStateObj->getNonExpiredProfilesWithExpiryNow($expiredStateIDArray);
		return $detailArray;
	}

	public static function updateStateOfAllProfiles($profileArray, $stateToBeUpdated, $substateToBeUpdated, $comment)
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$stateID = $ftoStatesObj->getFTOStateID($stateToBeUpdated, $substateToBeUpdated);
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		$ftoCurrentStateObj->updateProfilesCurrentState($profileArray, $stateID);
		$ftoStateLogObj = new FTO_FTO_STATE_LOG;
		$ftoStateLogObj->insertProfilesInStateLog($profileArray, $stateID, $comment);
	}
	public static function profileNeverPerformAction($profileid,$action)
	{
		$ftoStateLogObj = new FTO_FTO_STATE_LOG;
		return $ftoStateLogObj->profileNeverPerformAction($profileid, $action);
	}
	public static function profileExistsInFTOStateLog($profileid)
	{
                $ftoStateLogObj = new FTO_FTO_STATE_LOG;
		return $ftoStateLogObj->profileExistsInFTOStateLog($profileid);
	}
	public static function getProfilesInState($state,$subState='')
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$stateIDArray = $ftoStatesObj->getFTOStateID($state,$subState);
		$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
		return $ftoCurrentStateObj->getProfilesInState($stateIDArray);
	}
	public static function getProfilesInSubstateArray(array $substateArray, $expiry_cond='')
	{
                $ftoStatesObj = new FTO_FTO_STATES;
                $stateIDArray = $ftoStatesObj->getFTOStateIDArrayForSubStates($substateArray); //no	
				$ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
				if(!$expiry_cond)
					return $ftoCurrentStateObj->getProfilesInState($stateIDArray);
				else
					return $ftoCurrentStateObj->getProfilesHavingSubStatesWithGivenExpiry($stateIDArray,$expiry_cond);
	}
	public static function getProfilesInStateOnDate($state,$subState='',$date)
	{

		$profilesInState = FTOStateHandler::getProfilesInState($state,$subState);
		if($profilesInState)
		{
			foreach($profilesInState as $k=>$profileArray)
				$profileidInState[] = $profileArray['PROFILEID'];
			$ftoStatesObj = new FTO_FTO_STATES;
			$stateIDArray = $ftoStatesObj->getFTOStateID($state,$subState);


			$orgTZ = date_default_timezone_get();
			date_default_timezone_set("Asia/Calcutta");
			$date = JSstrToTime($date);
			$startDateIndia=date("Y-m-d H:i:s",mktime(00,00,00,date("m", $date),date("d", $date),date("Y", $date)));
			$endDateIndia=date("Y-m-d H:i:s",mktime(23,59,59,date("m", $date),date("d", $date),date("Y", $date)));
			$startTime = JSstrToTime($startDateIndia);
			$endTime = JSstrToTime($endDateIndia);
			date_default_timezone_set($orgTZ);
			$startDate = date('Y-m-d H:i:s', $startTime);
			$endDate = date('Y-m-d H:i:s', $endTime);
			$ftoStateLogObj = new FTO_FTO_STATE_LOG;
			return $ftoStateLogObj->getProfilesInStateBetweenDates($profileidInState,$stateIDArray,$startDate,$endDate);
		}
		
	}
	public static function checkPaid($profileid)
	{
		$ftoStatesObj = new FTO_FTO_STATES;
		$stateID = $ftoStatesObj->getFTOStateID(FTOStateTypes::PAID, FTOSubStateTypes::PAID);
		$ftoStateLogObj = new FTO_FTO_STATE_LOG;
		return $ftoStateLogObj->checkPaid($profileid,$stateID);
	}
}
?>
