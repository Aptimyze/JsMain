<?php

class MissedCallVerification extends PhoneVerification
{
	
private $virtualNo;
private $virtualNoId;
private $knowlarityObj;
public 	$tempText="";
public function __construct($phone,$virtualNo)
{

		try
		{
		if(!$phone || !$virtualNo){
			
			throw new jsException('',"wrong or null : phone or VirtualNo", 1);}

			$profileId=phoneKnowlarity::getProfileFromPhoneVNo($phone,$virtualNo);
			if(!$profileId){ 
				$this->tempText.="no profileid";
			throw new jsException('',"wrong or null : phone or VirtualNo", 1);}
			$this->profileObject=new Profile('',$profileId);
			$this->profileObject->getDetail("","","*");
			$this->isd=$this->profileObject->getISD();
			
			switch($phone)
			{

			case $this->isd.$this->profileObject->getPHONE_MOB():
			$this->isVerified=$this->profileObject->getMOB_STATUS();
			$this->phoneType='M';
			break;

			case $this->isd.$this->profileObject->getPHONE_WITH_STD():
			$this->isVerified=$this->profileObject->getLANDL_STATUS();
			$this->phoneType='L';
			break;

			default:
			$contactArray= (new ProfileContact())->getArray(array('PROFILEID'=>$profileId),'','',"ALT_MOBILE,ALT_MOB_STATUS");
			if($this->isd.$contactArray['0']['ALT_MOBILE']==$phone){
				$this->phoneType='A'; 		
				$this->isVerified=$contactArray['0']['ALT_MOB_STATUS']=='Y'?'Y':'N';
				}
			break;
			}

					if(!$this->phoneType){
										$this->tempText.=("no phoneTYpe with profileid ".$profileId);

					 throw new jsException('',"The phone is not saved for any profile", 1);
					}
					else $this->phone=$phone;
			}
			catch(Exception $e){
				return null;
			}
		


}




public function phoneUpdateProcess() {
$verified = parent::phoneUpdateProcess('KNW');
if($verified)
$this->clearEntry();
return $verified;
}



private function clearEntry()
{
	(new newjs_KNWLARITYVNO())->clearProfilePhoneEntry($this->profileObject->getPROFILEID(),$this->phone);
}

public function getTempText()
{
return $this->tempText;
}


}

