<?php
/**
 * @brief This class is used to handle all functionalities related to Horoscope and horoscope requests
 * @author Prinka Wadhwa
 * @created 2012-08-16
 */

class Horoscope
{

	public function ifHoroscopeRequested($senders, $receiver, $key)
	{
		$dbName = JsDbSharding::getShardNo($receiver);
		$horoscopeRequestObj = new newjs_HOROSCOPE_REQUEST($dbName);
		$horoscopeRequested = $horoscopeRequestObj->getIfHoroscopeRequestSent($senders,$receiver,$key);
		return $horoscopeRequested;
	}

	public function ifHoroscopePresent($profileId) {
		$astroObj = ProfileAstro::getInstance();
		$result = $astroObj->getIfAstroDetailsPresent($profileId);
		if($result == 1)
			return 'Y';
		/*else
		{
			$horoscope = new newjs_HOROSCOPE();
			$result = $horoscope->getIfHoroscopePresent($profileId);
			if($result == 1)
				return 'Y';
		}*/
		return 'N';
	}
	
	public function getHoroscopeRequestProfile($profileId,$condition, $skipArray)
	{
		$dbName = JsDbSharding::getShardNo($profileId);
		$horoscopeRequestObj =  new newjs_HOROSCOPE_REQUEST($dbName);
		$profileArray = $horoscopeRequestObj->getHoroscopeRequestProfileForCCDesktop($condition,$skipArray);
		return $profileArray;
	}

	/*This function is added by Reshu Rajput for Astro Details in profileCommunication
	*@param profileObjArray : array of profileids to find astro details
	*@return result : array of  profileIds and correspondig astro details
	*/

	public function getMultipleAstroDetails($profileObjArray)
	{	
		$astroObj = ProfileAstro::getInstance();
		$fields = "PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL";
		foreach($profileObjArray as $key=>$profileObj)
		{
			$pid=$profileObj->getPROFILEID();
			$profileIdArray[]=$pid;
			$profileGenderArray[$pid]=$profileObj->getGENDER();
		}
		$astroResult = $astroObj->getAstroDetails($profileIdArray,$fields,"Y");
		if(is_array($astroResult))
		{
		foreach($profileGenderArray as $pid=>$v)
		{
			if(!array_key_exists($pid,$astroResult))
                                $result[$pid]="";
			else
			{
				$lagna=$astroResult[$pid]['LAGNA_DEGREES_FULL'];
				$sun=$astroResult[$pid]['SUN_DEGREES_FULL'];
				$mo=$astroResult[$pid]['MOON_DEGREES_FULL'];
				$ma=$astroResult[$pid]['MARS_DEGREES_FULL'];
				$me=$astroResult[$pid]['MERCURY_DEGREES_FULL'];
				$ju=$astroResult[$pid]['JUPITER_DEGREES_FULL'];
				$ve=$astroResult[$pid]['VENUS_DEGREES_FULL'];
				$sa=$astroResult[$pid]['SATURN_DEGREES_FULL'];
				$gender=$v;
				if($gender == "M")
					$g=1;
				else
					$g=2;
				$result[$pid]="$pid:$g:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
			}
		}
		return $result;
		}
		return null;
	}
    /*
	 * function to get the horoscote request received count for given profile id
	 * it will return in array as total count and unseen count
	 * @param: profileid skipcontact type in array
	 * @return: horoscope count
	 * */
	public function getHoroscopeRequestCount($profileid,$skipProfile='') {
		$dbName = JsDbSharding::getShardNo($profileid);
		$horoscopeRequestObj = new newjs_HOROSCOPE_REQUEST($dbName);
		$horoscopeRequestedCount = $horoscopeRequestObj->getHoroscopeRequestCount($profileid,$skipProfile);
		return $horoscopeRequestedCount;
	}

	/*
	 * function to get the horoscote request sent count for given profile id
	 * it will return in array as total count and unseen count
	 * @param: profileid skipcontact type in array
	 * @return: horoscope count
	 * */
	public function getHoroscopeRequestSentCount($profileid,$skipProfile='') {
		$dbName = JsDbSharding::getShardNo($profileid);
		$horoscopeRequestObj = new newjs_HOROSCOPE_REQUEST($dbName);
		$horoscopeRequestedSentCount = $horoscopeRequestObj->getHoroscopeRequestSentCount($profileid,$skipProfile);
		return $horoscopeRequestedSentCount;
	}
	
	public function getHoroscopeCommunication($viewer,$viewed){
		$dbName = JsDbSharding::getShardNo($viewer);
		$horoscopeRequestObj = new newjs_HOROSCOPE_REQUEST($dbName);
		$output = $horoscopeRequestObj->getHoroscopeCommunication($viewer,$viewed);
		return $output;
	}
  
  /**
   * isHoroscopeUnderScreen
   * @param type $profileid
   * @return string
   */
  public function isHoroscopeUnderScreen($profileid){
    $store = new NEWJS_HOROSCOPE_FOR_SCREEN();
    $result = $store->getHoroscopeIfNotDeleted($profileid);
    if(false === $result){
      return 'N';
    }
    return 'Y';
  }
  
  /**
   * isHoroscopeExist
   * @param Object $Var : Need to be Profile Object or LoggedInProfile Object or Operator Object
   * @return string
   */
  public function isHoroscopeExist($Var)
  {
		$horoExist = null;
		$checkForScreened = false;
	
		if ($Var instanceof LoggedInProfile) {
			$checkForScreened = true;
		}
		
		$iReligion = $Var->getRELIGION();
    $arrAllowedReligion = array(Religion::HINDU,Religion::SIKH,Religion::JAIN,Religion::BUDDHIST);
    $iProfileID = $Var->getPROFILEID();
    
    if (in_array($iReligion, $arrAllowedReligion)) {
			$horoExist = $this->ifHoroscopePresent($iProfileID);
			/*if ($checkForScreened && 'N' == $horoExist) {
				$horoExist = $this->isHoroscopeUnderScreen($iProfileID);
			}*/
	
		}
	
		return $horoExist;
	}
}
?>
