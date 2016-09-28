<?php
/**
* Contact Elements Enum class
* Holds elements required by {@link getElements()} function
*Below is the demonstration on how to use this class
 * <code>
 * <br />
  * CONTACT_ELEMENTS::VerifyInput('COUNTLOGIC')
  * <Br/>
  * return true is exist<BR/>
  * return false if not
 * <br />
 * </code>
 * </p>
* @package jeevansathi
* @subpackage contacts
 */
class CONTACT_ELEMENTS
{
	const COUNTLOGIC="COUNTLOGIC";
	const CLICKSOURCE="CLICKSOURCE";
	const MATCHALERT_MIS_VARIABLE="MATCHALERT_MIS_VARIABLE";
	const SUGGEST_PROFILE="SUGGEST_PROFILE";
	const PR_VIEW="PR_VIEW";
	const STYPE="STYPE";
	const FILTER_PROFILE="FILTER_PROFILE";
	const STATUS="STATUS";
	const BOTH_USERS="BOTH_USERS";
	const MESSAGE="MESSAGE";
	const DRAFT_WRITE="DRAFT_WRITE";
	const DRAFT_NAME="DRAFT_NAME";
	const DROP_DOWN="DROP_DOWN";
	const CALL_NOW="CALL_NOW";
	const CALL_DIRECTLY="CALL_DIRECTLY";
	const CONTACT_DETAIL="CONTACT_DETAIL";
	const MESSAGE_BOX_VISIBLE="MESSAGE_BOX_VISIBLE";
	const VISIBILITY = "VISIBILITY";
	const ALLOWED="ALLOWED";
	const APPLICABLE="APPLICABLE";
	const PROFILECHECKSUM="PROFILECHECKSUM";
	const MULTI="MULTI";
	const ACTIVE_BUTTON="ACTIVE_BUTTON";
	const RESPONSETRACKING="RESPONSETRACKING";
	
	//new constant for tracking
	const CALL_DIRECTLY_TRACKING="D";
	const ACCEPTANCE_TRACKING="A";
	const EVALUE_TRACKING="E";
	const EVALUE_LIMIT=15;
	const EVALUE_SHOW=1;
	const EVALUE_STOP=2;
	const EVALUE_PCS=4;
	const EVALUE_NO=3;
	//% profile completion score to be checked for not allowing evalue contacts limit
	const PCS_CHECK_VALUE=60;
	//new constant for shoing contacts Alloted Message
	const MIN_CONTACT_ALLOTED=10000;
	const PAGINATION_LIMIT=21;
	
  /**
   * 
   * Get variable existence.
   * 
   * <p>
   * This function returns true/false depending upon variable existence in const declared in CONTACT_ELEMENTS
   * </p>
   *
   * @param String $type
   * @access public
   * @return boolean
   */	
 public static function VerifyInput($type)
 {
		if(defined("CONTACT_ELEMENTS::$type"))
			return true;
		else
			return false;
 }
}
?>
