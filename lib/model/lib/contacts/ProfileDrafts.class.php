<?php
/**
 *CLASS ProfileDrafts
 * ProfileDrafts class fetch all the drafts that user 
 * has saved while sending EOI or while accepting/declining
 * user.
 * 
 *  * Code to fetch user's drafts
 * <code>
 * $draftsObj=new ProfileDrafts(Profile $profileObj);
 * //to fetch accepted drafts
 * $draftsObj->getAcceptDrafts();
 * // to fetch decline drafts
 * $draftsObj->getDeclineDrafts();
 * // to fetch Eoi drafts
 * $draftsObj->getEoiDrafts();
		* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   contacts
 * @author    Nikhil Dhiman <nikhil.dhiman@jeevansathi.com>
 * @copyright 2012 Nikhil Dhiman
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */
class ProfileDrafts
{
	/**
	 * Attributes
	 */
	 
	private $profileObj;
	private $allDrafts;
	public static $instance;
	
	/**
	 * constant variables
	 * 
	 */
	const PRESET_DRAFTNAME="Preset Message";
	const PRESET_DRAFTID="PRE_1";
	const PRESET_ACCEPT_DRAFTID="PRE_2";
	const PRESET_DECLINE_DRAFTID="D1";
	const WRITENEW_DRAFTNAME='Write New Message';
	const WRITENEW_DRAFTID='WNM';
	const REPLACE_DRAFTNAME = "Replace With";
	const REPLACE_DRAFTID = "REP_1";
	
	/**
 * Initialize allDrafts, profileObj.
 * @param Profile $profileObj 
 * @throws JsException
 */
	function __construct($profileObj)
	{
		if(!($profileObj instanceof  Profile))
			throw new JsException("",Messages::NO_PROFILE_OBJ);
			
		$this->profileObj=$profileObj;

		//Fetch all paid drafts if paid member.
		if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
		{
			$draftsObj=new NEWJS_DRAFTS();
			
			$this->allDrafts=$draftsObj->getAllDrafts($this->profileObj->getPROFILEID());
		}
		else
			$this->allDrafts=Array();
	}
	
	public static function getInstance($profileObj)
	{
	
			if($profileObj instanceof Profile || $profileObj instanceof LoggedInProfile)
			{
				if(ProfileDrafts::$instance[$profileObj->getPROFILEID()])
				{
					return $instance[$profileObj->getPROFILEID()];
				}
				else
				{
					$instance[$profileObj->getPROFILEID()]=new ProfileDrafts($profileObj);
				}
				return $instance[$profileObj->getPROFILEID()];
			}
			else
			{
				throw new JsException("",Messages::NO_PROFILE_OBJ);
			}	
	}
/**
 * return array having drafts format:<pre>array(0=>DraftName,1=>Draft_Message,2=>Draft_Id)</pre>
 * 
 * @return Array
 */	
	public function getAcceptDrafts($acceptPost='N')
	{
		$acceptArr=$this->getDraftsBasedOnType("N");
		$st=count($acceptArr);
		if($acceptPost == 'Y')
		{
			if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
				$acceptArr[$st]=array(ProfileDrafts::REPLACE_DRAFTNAME,'',"",1);			
		}
		else
		{
			if(!($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID() && MobileCommon::isMobile())){
				$pMessage=PresetMessage::getAcceptMes($this->profileObj);
				$acceptArr[$st]=array(ProfileDrafts::PRESET_DRAFTNAME,$pMessage,ProfileDrafts::PRESET_ACCEPT_DRAFTID,1);
				$st++;
			}
			if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
				$acceptArr[$st]=array(ProfileDrafts::WRITENEW_DRAFTNAME,'',ProfileDrafts::WRITENEW_DRAFTID);				
		}		
		return $acceptArr;	
	}
	/**
 * return array having drafts format:<pre>array(0=>DraftName,1=>Draft_Message,2=>Draft_Id)</pre>
 * 
 * @return Array
 */	
	public function getWriteDrafts()
	{
		$acceptArr=$this->getDraftsBasedOnType("N");
		$st=count($acceptArr);
		//$st++;
		if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
			$acceptArr[$st]=array(ProfileDrafts::WRITENEW_DRAFTNAME,'',ProfileDrafts::WRITENEW_DRAFTID,1);
		return $acceptArr;	
	}
/**
 * return array having drafts format:<pre>array(0=>DraftName,1=>Draft_Message,2=>Draft_Id)</pre>
 * @return Array
 */		
	public function getDeclineDrafts($acceptPost = 'N')
	{
		$declineArr=$this->getDraftsBasedOnType("Y");
		$st=count($declineArr);
		if($acceptPost == 'Y')
		{
			if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
				$declineArr[$st]=array(ProfileDrafts::REPLACE_DRAFTNAME,'',"",1);			
		}
		else
		{
			if(!($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID() && MobileCommon::isMobile())){
				$pMessage=PresetMessage::getDeclineMes($this->profileObj);
				$declineArr[$st]=array(ProfileDrafts::PRESET_DRAFTNAME,$pMessage,ProfileDrafts::PRESET_DECLINE_DRAFTID,1);
				$st++;
			}
			if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
				$declineArr[$st]=array(ProfileDrafts::WRITENEW_DRAFTNAME,'',ProfileDrafts::WRITENEW_DRAFTID);
		}
		return $declineArr;
	}
	/**
 * return array having drafts format:<pre>array(0=>DraftName,1=>Draft_Message,2=>Draft_Id)</pre>
 * @return Array
 */		
	public function getEoiDrafts($eoiPost="N")
	{
		$eoiArr=$this->getDraftsBasedOnType("N");
		$st=count($eoiArr);
		if($eoiPost == 'Y')
		{
			if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
				$eoiArr[$st]=array(ProfileDrafts::REPLACE_DRAFTNAME,'',"",1);			
		}
		else
		{
			if(!($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID() && MobileCommon::isMobile())){
			$pMessage=PresetMessage::getEoiMes($this->profileObj);
				$eoiArr[$st]=array(ProfileDrafts::PRESET_DRAFTNAME,$pMessage,ProfileDrafts::PRESET_DRAFTID,1);
				$st++;
			}
			if($this->profileObj->getPROFILE_STATE()->getPaymentStates()->isPAID())
				$eoiArr[$st]=array(ProfileDrafts::WRITENEW_DRAFTNAME,'',ProfileDrafts::WRITENEW_DRAFTID);
		}
		return $eoiArr;	
	}
	/**
 * return array having drafts in format:<pre>array(0=>DraftName,1=>Draft_Message,2=>Draft_Id)</pre>
 * based on type passed format of type:("Y","N")
 * @return Array
 */		
	private function getDraftsBasedOnType($type)
	{
		$st=0;
		$retArr=array();
		for($i=0;$i<count($this->allDrafts);$i++)
		{
			if($this->allDrafts[$i][DECLINE_MES]==$type)
			{
					
					$retArr[$st][0]=stripslashes(htmlspecialchars($this->allDrafts[$i][DRAFTNAME],ENT_QUOTES));
					$retArr[$st][1]=html_entity_decode(preg_replace("/\\r\\n|\\n|\\r/","#n#",htmlspecialchars($this->allDrafts[$i][MESSAGE],ENT_QUOTES)),ENT_QUOTES);
					$retArr[$st][2]=$this->allDrafts[$i][DRAFTID];
					$st++;
			}
		}
		return $retArr;
	}
	/** returns array with proper key format required for search
	 * drafts object
	 */
	 public static function UpdateDraftsKey($drafts)
	 {
		 
		 foreach($drafts as $key=>$val)
		 {
					$tempArr[$key][MESSAGE]=$val[1];
					$tempArr[$key][DRAFTID]=$val[2];
					$tempArr[$key][DRAFTNAME]=$val[0];
		 }
		 return $tempArr;
	 }
	 /*
	  * return appropriate message
	  */
	public static function getMessage($drafts,$index,$updateDrafts=0)
	{
		if(!$index)
		{
			foreach($drafts as $key=>$val)
			{
					if($val[3]==1)
						return $val[1];
			}
		}
		if($updateDrafts)
			$drafts=ProfileDrafts::UpdateDraftsKey($drafts);
		if(is_array($drafts))
		foreach($drafts as $key=>$val)
		{
				if($val[DRAFTID]==$index)
					return $val[MESSAGE];
		}
		if(!$updateDrafts)
			return $drafts[0][1];
	}
	  
}
