<?php
/**
 * @class negativeTreatment 
 * @brief  this class negative treatment for spam profiles registered on Jeevansathi
 */

class negativeTreatment{

	/**
    	 * @fn __construct
    	 * @brief Constructor function
    	 */
    	public function __construct(){
		$this->profileArr 		=array();
		$this->profileNegArrForPhone 	=array();
		$this->phoneNegArr 		=array();
		$this->emailNegArr 		=array();
		$this->profileNegArrForEmail 	=array();
		$this->fields 			='PROFILEID';

		$this->phoneLogObj    		=new PHONE_VERIFIED_LOG('newjs_local111');
		$this->oldEmailObj    		=new newjs_OLDEMAIL('newjs_local111');
		$this->cnt=1;
    	}

	public function getProfileId($username)
	{
		$jProfileObj =new JPROFILE('newjs_local111');
        	$jProfileDetails=$jProfileObj->get($username,"USERNAME",$this->fields);
                $profileid      =$jProfileDetails['PROFILEID'];
		return $profileid;
        }

    	public function addProfileToNegative($profileidArr,$type=''){

		$this->cnt++;
		//echo "Main Profile List: ";
		if($type && is_array($profileidArr)){
			foreach($profileidArr as $key=>$profileid){
				$this->profileNegArrForPhone[] 	=$profileid;
				$this->profileNegArrForEmail[] 	=$profileid;
			}
		}
		//print_r($profileidArr);

		// Phone Number handling
		if(count($profileidArr)>0){
			unset($phoneArr);
			unset($phoneArrNew);
			$phoneArr =$this->phoneLogObj->getVerifiedPhoneNumbers($profileidArr);
			if(is_array($phoneArr))
				$phoneArrNew 	=array_diff($phoneArr,$this->phoneNegArr);
			if(is_array($phoneArrNew)){
				$this->addPhoneToNegative($phoneArrNew);	
			}
		}

		// Email handling
		if(count($profileidArr)>0){
			unset($emailArr);
			unset($emailArrNew);
			$emailArr =$this->oldEmailObj->getEmailList($profileidArr);
			if(is_array($emailArr))
				$emailArrNew 	=array_diff($emailArr,$this->emailNegArr);
			if(is_array($emailArrNew)){
				$this->addEmailToNegative($emailArrNew);
			}	
		}
	}
	 
        public function addPhoneToNegative($phoneNumberArr){
		// Add phone number to negative
		/*echo "Phone Number List: ";
		print_r($phoneNumberArr);*/
		foreach($phoneNumberArr as $key=>$phoneValue){
			$this->phoneNegArr[] =$phoneValue;
		}

		// find profiles for verified phone number
		if(count($phoneNumberArr)>0){
			unset($profileArr);
			unset($profileArrNew);
			$profileArr =$this->phoneLogObj->getVerifiedProfiles($phoneNumberArr);
			if(is_array($profileArr))
				$profileArrNew 	=array_diff($profileArr,$this->profileNegArrForPhone);
			/*echo "Profiles for Phone: ";
			print_r($profileArrNew);*/
			if(is_array($profileArrNew)){
				foreach($profileArrNew as $key=>$pid){
					$this->profileNegArrForPhone[] =$pid;
				}
				$this->addProfileToNegative($profileArrNew);
			}
		}
        }
        public function addEmailToNegative($emailArr){
		// Add email to negative list
		/*echo "Email list: ";
		print_r($emailArr);*/
		foreach($emailArr as $key=>$emailVal){
			$this->emailNegArr[] =$emailVal;	
		}

                // find profiles for email
		if(count($emailArr)>0){
			unset($profileArr);
			unset($profileArrNew);
	                $profileArr =$this->oldEmailObj->getEmailProfiles($emailArr);
			if(is_array($profileArr))
				$profileArrNew 	=array_diff($profileArr,$this->profileNegArrForEmail);
			/*echo "Profiles for Email:"; 
			print_r($profileArrNew);*/
			if(is_array($profileArrNew)){
				foreach($profileArrNew as $key=>$pid){
					$this->profileNegArrForEmail[] =$pid;
				}
				$this->addProfileToNegative($profileArrNew);
			}
		}
        }
	public function addToNegative($type,$value,$comments)
	{
		$submitObj 	= new incentive_NEGATIVE_SUBMISSION_LIST();
		$this->submitID =$submitObj->insert($type, $value, $comments);

		switch($type){
			case 'PHONE_NUM':
				$this->addPhoneToNegative(array($value));
				break;
			case 'EMAIL':
				$this->addEmailToNegative(array($value));
				break;
			case 'PROFILEID':
				$this->addProfileToNegative(array($value),$type);
				break;
			default:
				break;
		}
		$this->profileNegArrForPhone 	=array_unique($this->profileNegArrForPhone);
		$this->profileNegArrForEmail 	=array_unique($this->profileNegArrForEmail);
		$this->profileArr 		=array_merge($this->profileNegArrForPhone,$this->profileNegArrForEmail);
		$this->profileArr		=array_unique($this->profileArr);
		$this->phoneNegArr 		=array_unique($this->phoneNegArr);
		$this->emailNegArr 		=array_unique($this->emailNegArr);
		$insertArr 			=array("PROFILEID"=>$this->profileArr,"PHONE_NUM"=>$this->phoneNegArr,"EMAIL"=>$this->emailNegArr);
		$this->insertIntoNegative($insertArr);

		// Delete the profile	
		if(is_array($this->profileArr)){
			$DeleteProfileObj =new DeleteProfile();
			foreach($this->profileArr as $key=>$profileid){
                                $DeleteProfileObj->delete_profile($profileid,$delete_reason,$specify_reason,$username);
                                $DeleteProfileObj->callDeleteCronBasedOnId($profileid);
                        }
		}

	}
	
	// Add negative values in incentive_NEGATIVE_LIST
	public function insertIntoNegative($insertArr)
	{
		$negativeListObj =new incentive_NEGATIVE_LIST();
		if(is_array($insertArr)){
			foreach($insertArr as $type=>$value){
				if(is_array($value)){
				foreach($value as $key1=>$val1){
					$negativeListObj->insert($type,$val1,$this->submitID);
				}}
			}
		}
	}




}
?>
