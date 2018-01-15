<?php
/**
 * class PhoneDuplicateDetector
 * 
 */
class PhoneDuplicateDetector extends DuplicateDetector
{


   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private  $phoneDuplicateDetector;

  /**
   * 
   * @access private
   */
  private $rawPhoneDuplicate;
  
  private $TYPE="PHONE";
  private $mobileNumber;
  private $landlineNumber;
  private $alternativeNumber;
  private $duplicateProfiles;
  private $validMobile;
  private $validLandline;
  private $validAlternate;
  private $isd;
  private $landline;
  private $std;
  private $validArray=array();
  private $noneValid;
  private $mobileVerificationDate;
  private $landlineVerificationDate;
  private $alternateVerificationDate;
  private $mobileVerified;
  private $landlineVerified;
  private $alternateVerified;
  //private $duplicateProfiles;
  
  public function __construct(DuplicateDetector $duplicateDetector, $changedBits='')
  {
	  $this->phoneDuplicateDetector=$duplicateDetector;
	  $this->rawPhoneDuplicate=new RawDuplicate();
	 // $this->rawPhoneDuplicate->setProfileid1($duplicateDetector->profile->getPROFILEID());
	  $this->rawPhoneDuplicate->setProfileid1(LoggedInProfile::getInstance()->getPROFILEID());
	  $this->rawPhoneDuplicate->setReason($this->TYPE);
          if((!$changedBits)|| $changedBits['phone_mob'])
	  {
	  	$this->mobileNumber = LoggedInProfile::getInstance()->getPHONE_MOB();
	  	$this->mobileVerified=LoggedInProfile::getInstance()->getMOB_STATUS();
		if($this->mobileVerified=='')
			$this->mobileVerified='N';
	  }
	  if((!$changedBits)|| $changedBits['phone_res'])
	  {
		  $this->std =LoggedInProfile::getInstance()->getSTD();
		  $this->landline =LoggedInProfile::getInstance()->getPHONE_RES();
		  $this->landlineNumber = LoggedInProfile::getInstance()->getPHONE_WITH_STD();
		  $this->landlineVerified=LoggedInProfile::getInstance()->getLANDL_STATUS();
		  if($this->landlineVerified=='')
			$this->landlineVerified='N';
	  }
	  if((!$changedBits)|| $changedBits['alt_mobile'])
	  {
		  $this->alternativeNumber = LoggedInProfile::getInstance()->getExtendedContacts()->ALT_MOBILE;
		  $this->alternateVerified=LoggedInProfile::getInstance()->getExtendedContacts()->ALT_MOB_STATUS;
		  if($this->alternateVerified=='')
			$this->alternateVerified='N';
	  }
	  $this->isd = LoggedInProfile::getInstance()->getISD();
  }

  /**
   * 
   *
   * @return Duplicate
   * @access public
   */
  public function checkDuplicate() {
	 
	  $duplicateObj=$this->phoneDuplicateDetector->checkDuplicate();
	  $this->setDuplicate();
    	  if(is_array($this->duplicateProfiles))
	  foreach($this->duplicateProfiles as $key=>$val){
		  //Patch added by Tanu for marking bulk duplicate issue
		  if(strlen($val[PHONE_NUMBER])<5){
			//print_r($this->duplicateProfiles);
		  }
		  else{
		  $phoneDuplicateObj = 'PhoneDuplicate'.$key;
		  $this->phoneDuplicateObj = clone($this->rawPhoneDuplicate);
		  $this->phoneDuplicateObj->setComments("Parameters__$val[PHONE_TYPE]:$val[PHONE_NUMBER]");
		  $this->phoneDuplicateObj->setProfileid2($key);
		  $duplicateType=$this->getDuplicateType($val['PHONE_NUMBER'],$key);
		  $this->phoneDuplicateObj->setIsDuplicate($duplicateType);
		  $duplicateObj->addRawDuplicateObj($this->phoneDuplicateObj);
		}
	  }
//	 print_r($duplicateObj);
	  return $duplicateObj;
	  
  } // end of member function checkDuplicate

public function setValidNumberArray()
{
	if($this->validMobile!='N'&& $this->mobileNumber)
		$this->validArray[]=$this->mobileNumber;
	if($this->validLandline!='N'&& $this->landlineNumber)
	{
		$this->validArray[]=$this->landlineNumber;
		$this->validArray[]=$this->std."-".$this->landline;
	}
	if($this->validAlternate!="N"&& $this->alternativeNumber)
		$this->validArray[]=$this->alternativeNumber;
	if($this->validMobile=='N' && $this->validLandline=='N' && $this->validAlternate=='N')
		$this->noneValid='Y';
}

public function setDuplicate()
{
	$this->checkInvalid();
	$this->setValidNumberArray();
	if($this->noneValid!='Y')
	{
		$this->getInitialVerificationDate();
		$this->checkProfile();
		$this->checkProfileContacts();
		$this->checkArchived();
//		$this->checkInLead();
		$this->checkFollowUp();
	}
}

public function getInitialVerificationDate()
{
	$profileid=LoggedInProfile::getInstance()->getPROFILEID();
	if($this->validMobile!='N'&& $this->mobileVerified=='Y')
		$this->mobileVerificationDate=$this->getVerificationDates($profileid,$this->mobileNumber);
	if($this->validLandline!='N'&& $this->landlineVerified=='Y')
		$this->landlineVerificationDate=$this->getVerificationDates($profileid,$this->std."-".$this->landline);
	if($this->validAlternate!="N" && $this->alternateVerified=='Y')
		$this->alternateVerificationDate=$this->getVerificationDates($profileid,$this->alternativeNumber);
}
public function getVerificationDates($profileid, $number)
{
	$verificationObj=new PHONE_VERIFIED_LOG;
	return $verificationObj->getVerificationDate($profileid, $number);
}
public function getDuplicateType($number, $profileid)
{
	if($number && $profileid)
	{
		$profileVerificationDate='';
		$number=substr($number,-10);
		if($this->mobileNumber && (strstr($number,$this->mobileNumber)|| strstr($this->mobileNumber,$number)) && $this->mobileVerified=='Y')
			$profileVerificationDate=$this->mobileVerificationDate;
		elseif($this->landline &&(strstr($number,$this->landline)|| strstr($this->landline,$number))&& $this->landlineVerified=='Y')
			$profileVerificationDate=$this->landlineVerificationDate;
		elseif($this->alternativeNumber && (strstr($number,$this->alternativeNumber)|| strstr($this->alternativeNumber,$number)) && $this->alternateVerified=='Y')
			$profileVerificationDate=$this->alternateVerificationDate;
	}
//echo $profileVerificationDate;
	if($profileVerificationDate)
	{
		$duplicateProfileVerificationdate=$this->getVerificationDates($profileid, $number);
		if($duplicateProfileVerificationdate)
			$diffDays= $this->dateDiff($duplicateProfileVerificationdate,$profileVerificationDate);
	}	
	if($diffDays<90 && $profileVerificationDate && $duplicateProfileVerificationdate)
		return IS_DUPLICATE::YES;
	else
		return IS_DUPLICATE::PROBABLE;
}
private function dateDiff($start, $end) {
$start_ts = JSstrToTime($start);
$end_ts = JSstrToTime($end);
$diff = $end_ts - $start_ts;
return abs(round($diff / 86400));
}
private function removeAllSpecialChars($number)
{
         return ltrim(preg_replace("/[^0-9]/","",$number),0);//remove everything except numbers
}

private function checkInvalid()
{
	$invalidObj=new INVALID_PHONE;
	
	if($invalidObj->existInINVALID_PHONE(LoggedInProfile::getInstance()->getPROFILEID())=='Y')
	{
		$this->validMobile="N";
		$this->validLandline="N";
		$this->validAlternate="N";
		return;
	}
	$junkObj=new PHONE_JUNK;
	if($this->mobileNumber=='')
		$this->validMobile="N";
	if($this->landline=='')
		$this->validLandline='N';
	if($this->alternativeNumber=='')
		$this->validAlternate="N";
		
	if($this->isd=='91' || $this->isd=='')
	{
		if($this->mobileNumber!='')
		{
			$this->mobileNumber=substr($this->removeAllSpecialChars(trim($this->mobileNumber)),-10);
			$this->validMobile=$this->checkMobileNumber($this->mobileNumber);
			if($junkObj->checkJunk($this->mobileNumber)=='Y')
				$this->validMobile="N";
		}
		if($this->landline!='')
		{
			$this->std=ltrim($this->removeAllSpecialChars($this->std),'0');
			$mob2=substr($this->std.$this->removeAllSpecialChars(trim($this->landline)),-10);
			if($this->checkMobileNumber($mob2)=='Y')
			{
				$this->validLandline='Y';
				$this->landlineNumber=$mob2;
			}
			else
			{
				if($this->std=='')
				{
					$this->landline=$this->removeSpecialCharsExceptHyphen(trim($this->landline));
					$numberArr=explode("-",$this->landline);
					$this->landline=ltrim(trim($numberArr[1]),'0');
					$this->std=ltrim(trim($numberArr[0]),'0');
				}
				else
				{
					$this->landline=$this->removeAllSpecialChars(trim($this->landline));
				}
				$this->landlineNumber=ltrim($this->std,'0').ltrim($this->landline,'0');
				$this->landlineNumber=substr($this->landlineNumber,-10);
				if($this->std!='')
					$this->validLandline=$this->checkLandlineNumber($this->landlineNumber,$this->std,$this->landline);
				else
					$this->validLandline=($this->lengthCheckMobile($this->landlineNumber)?'Y':'N');
			}
			if($junkObj->checkJunk($this->landlineNumber)=='Y')
				$this->validLandline="N";
		}
		if($this->alternativeNumber!='')
		{
			$this->alternativeNumber=substr($this->removeAllSpecialChars(trim($this->alternativeNumber)),-10);
			$this->validAlternate=$this->checkMobileNumber($this->alternativeNumber);
			if($junkObj->checkJunk($this->alternativeNumber)=='Y')
				$this->validAlternate="N";
		}
	}
	else
	{
		if($this->mobileNumber!='')
		{
			$this->mobileNumber=$this->removeAllSpecialChars(trim($this->mobileNumber));
			$this->validMobile=($this->lengthCheckInternational($this->mobileNumber)?'Y':'N');
			if($this->validMobile=='Y' && $junkObj->checkJunk($this->mobileNumber)=='Y')
				$this->validMobile='N';
		}
		if($this->landlineNumber!='')
		{
			$this->landlineNumber=$this->removeAllSpecialChars(trim($this->landlineNumber));
			$this->validLandline=($this->lengthCheckInternational($this->landlineNumber)?'Y':'N');
			if($this->validLandline=='Y' &&$junkObj->checkJunk($this->landlineNumber)=='Y')
				$this->validLandline='N';
		}
		if($this->alternativeNumber!='')
		{
			$this->alternativeNumber=$this->removeAllSpecialChars(trim($this->alternativeNumber));
			$this->validAlternate=($this->lengthCheckInternational($this->alternativeNumber)?'Y':'N');
			if($this->validAlternate=='Y' && $junkObj->checkJunk($this->alternativeNumber)=='Y')
				$this->validAlternate="N";
		}
	}
}
private function removeSpecialCharsExceptHyphen($number)
{
         return $number=ltrim(preg_replace("/[^0-9\-]/","",$number),0);//remove everything except numbers and hyphen(-)
}

private function checkLandlineNumber($numberWithStd,$std='',$number='')
{
	if($number==''|| $std=='')
		return $this->lengthCheckMobile($numberWithStd)?'Y':'N';
	else
		return ($this->lengthCheckMobile($numberWithStd) && $this->checkIndianLandlineFormat($number))?'Y':'N';
}
private function checkIndianLandlineFormat($number)
{
        return (in_array(substr($number,0,1),array(2,3,4,5,6)))?true:false;
}

private function lengthCheckMobile($number)
{
        return (strlen($number)==10)?true:false;
}
private function lengthCheckInternational($number)
{
	return (strlen($number)>=6)?true:false;
}
private function checkIndianMobileFormat($number)
{
        return (in_array(substr($number,0,1),array(7,8,9)))?true:false;
}

private function checkMobileNumber($number)
{
	return ($this->lengthCheckMobile($number) && $this->checkIndianMobileFormat($number))?'Y':'N';	
}

private function checkInLead()
{
	$leadArr = array();
	$leadIdArr = array();
	$leadsObj = new sugarcrm_leads;
	if($this->validArray)
	{
		$leadIdArr = $leadsObj->checkByPhoneType($this->validArray,$this->isd);
	}
	if($leadIdArr)
	{
		$userName=array();
		$leadsCstmObj = new sugarcrm_leads_cstm;
		$userName= $leadsCstmObj->getUsernameFromLead($leadIdArr);
		foreach($userName as $k=>$userDetails)
		{
			$profile = new Profile;
			$profile->getDetail($userDetails['USERNAME'],'USERNAME',"PROFILEID");	
			if($profile->getPROFILEID() && (LoggedInProfile::getInstance()->getPROFILEID()!=$profile->getPROFILEID()))
			{
				$this->duplicateProfiles[$profile->getPROFILEID()]["PHONE_TYPE"] = $userDetails['TYPE'];
				$this->duplicateProfiles[$profile->getPROFILEID()]["PHONE_NUMBER"] = $userDetails['NUMBER'];
			}
		}
	}
}

private function checkArchived()
{
	$profileArr=array();
	$profileObj	=new CONTACT_ARCHIVE_INFO;
	if($this->validArray)
	{
		$profileArr= $profileObj->checkPhone($this->validArray,$this->isd);
		if($profileArr)
		{
			foreach($profileArr as $key=>$profileDetails)
			{
				if($profileDetails['PROFILEID'] && (LoggedInProfile::getInstance()->getPROFILEID()!=$profileDetails['PROFILEID']))
				{
					$this->duplicateProfiles[$profileDetails['PROFILEID']]["PHONE_TYPE"] = $profileDetails['TYPE'];
					$this->duplicateProfiles[$profileDetails['PROFILEID']]["PHONE_NUMBER"] = $profileDetails['NUMBER'];
				}
			}
		}
	}
}

private function checkFollowUp()
{
	$profileArr=array();
	$profileObj=new PROFILE_ALTERNATE_NUMBER;
	if($this->validArray)
	{
		$profileArr= $profileObj->checkPhone($this->validArray, $this->isd);
		if($profileArr)
		{
			foreach($profileArr as $key=>$profileDetails)
			{
				if($profileDetails['PROFILEID'] && (LoggedInProfile::getInstance()->getPROFILEID()!=$profileDetails['PROFILEID']))
				{
					$this->duplicateProfiles[$profileDetails['PROFILEID']]["PHONE_TYPE"] = $profileDetails['TYPE'];
					$this->duplicateProfiles[$profileDetails['PROFILEID']]["PHONE_NUMBER"] = $profileDetails['NUMBER'];
				}
			}
		}
	}
}

private function checkProfile()
{
        //Verification date greater than 3 months
	$profileArr=array();
	$profileObj= new JPROFILE;
	if($this->validArray)
	{
		$profileArr= $profileObj->checkPhone($this->validArray,$this->isd);
		if($profileArr)
		{
			foreach($profileArr as $key=>$profileDetails)
			{
				if($profileDetails['PROFILEID'] && (LoggedInProfile::getInstance()->getPROFILEID()!=$profileDetails["PROFILEID"]))
				{
					$this->duplicateProfiles[$profileDetails["PROFILEID"]]["PHONE_TYPE"] = $profileDetails["TYPE"];
					$this->duplicateProfiles[$profileDetails["PROFILEID"]]["PHONE_NUMBER"] = $profileDetails["NUMBER"];
				}
			}
		}
	}
}

private function checkProfileContacts()
{
        $profileArr = array();
        $profileContactsObj = new ProfileContact();
        if($this->validArray)
        {
                $profileArr = $profileContactsObj->checkPhone($this->validArray,$this->isd);
		if($profileArr)
		{
	                foreach($profileArr as $key=>$profileDetails)
        	        {
				if($profileDetails['PROFILEID'] && (LoggedInProfile::getInstance()->getPROFILEID()!=$profileDetails['PROFILEID']))
				{
					$this->duplicateProfiles[$profileDetails['PROFILEID']]["PHONE_TYPE"] = $profileDetails['TYPE'];
					$this->duplicateProfiles[$profileDetails['PROFILEID']]["PHONE_NUMBER"] = $profileDetails['NUMBER'];
				}
	                }
		}
        }
}

} // end of PhoneDuplicateDetector
?>
