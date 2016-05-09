<?php
/* 
 * Class for allotment of profiles for Photo Screening.
 * @package    jeevansathi
 * @subpackage photoScreening
 * @author     Akash Kumar
  */
class PhotoScreenProfileAllot
{
        const SEMAPHORE_ALLOTMENT = 1234;
        const SEMAPHORE_ALLOTMENT_OVER = 5678;

        public function getAllotedProfile($paramArr)
	{
                $source=$paramArr["SOURCE"];
                $interface=$paramArr["INTERFACE"];
                $name=$paramArr["NAME"];
                
                
                $photoScreeningServiceObj = new photoScreeningService();
		
                $flag = ($source=='new' ? PictureStaticVariablesEnum::$HAVE_PHOTO_STATUS["UNDERSCREENING"] : ($source=='edit' ? PictureStaticVariablesEnum::$HAVE_PHOTO_STATUS["YES"]:''));
                
                $paramArr["FLAG"]=$flag;
                $paramArr["STATUS"]= $interface==ProfilePicturesTypeEnum::$INTERFACE["2"]?PictureStaticVariablesEnum::PROCESSING_ALLOT_STATUS:"";
                
                $appAllotTime = PictureStaticVariablesEnum::APP_ALLOT_TIME_INTERVAL;
                $paramArr["minTimeToAllot"]=date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -".$appAllotTime." minutes"));
                
		$allottedProfile = $this->userAlloted($paramArr); //gets new/edit/mail photo profile that is allotted to the user but whose screening hasnt been done
		if($allottedProfile && $allottedProfile['PROFILEID']) //profile is assigned to a screening user but screening is not done.
		{ 
                        $paramArr["PROFILEID"]=$allottedProfile["PROFILEID"];
                        $this->reallotProfile($paramArr);
			$details=$photoScreeningServiceObj->showAllottedProfile($paramArr);
		}
		else
		{
			//Get A Lock
			$lockingObj = new LockingService;
			$key = $lockingObj->semgetLock(self::SEMAPHORE_ALLOTMENT);
			//Get A Lock

			$allottedProfile = $this->allottedProfilesToOthers($paramArr);//gets a profile which was allotted to some user atleast 30 min back and hasn't been screened yet.
			if($allottedProfile)
			{
                                if ($paramArr["SOURCE"] == PictureStaticVariablesEnum::$SOURCE["MASTER"])
                                        return "alreadyAlloted";
                                
				$paramArr["PROFILEID"]=$allottedProfile['PROFILEID'];
				$this->reallotProfile($paramArr);//assign profile to a screening user by updating alloted date and username.
                                $lockingObj->semreleaseLock($key);
				//Release Lock   
                                
				if($allottedProfile['PROFILEID'])
                                {
                                        $paramArr["PROFILEID"]=$allottedProfile["PROFILEID"];
                                        $details=$photoScreeningServiceObj->showAllottedProfile($paramArr);
                                }
			}
			else
			{
				//Release Lock                  
				$lockingObj->semreleaseLock($key);
				$lockingObj = new LockingService;
				$key2 = $lockingObj->semgetLock(self::SEMAPHORE_ALLOTMENT_OVER);
				//Get A Lock

                                $newProfile = $this->unallottedProfiles($paramArr);
				$screen_time = sfConfig::get("app_screentime");
                        
				if($newProfile && ($source == 'new' || $source == 'edit' || $source='master'))
				{
                                        $paramArr["RECEIVE_TIME"]=$newProfile['PHOTODATE'];
					$paramArr["USERNAME"]=$newProfile['USERNAME'];
                                        $paramArr["PROFILEID"]=$newProfile["PROFILEID"];
				        $details=$photoScreeningServiceObj->showAllottedProfile($paramArr);
                                }
				else
				{
					$details = "noProfileFound";
					return $details;
				}
				$paramArr["SUBMIT_TIME"]=timeFunctions::newtime($receivetime,0,$screen_time,0);
                                
                                if($details!="noProfileFound"){
                                        $this->allotProfile($paramArr);
                                }

				//Release Lock                  
				$lockingObj->semreleaseLock($key2);
				//Release Lock   

			}
		}
		return $details;
        }
        /**
	 * This function is used to get the list of profiles which have been allotted to a specific screening Executive and havent been screened yet.
	**/
        public function userAlloted($paramArr)
	{
                $mainAdmin = new MAIN_ADMIN();
                $profile = $mainAdmin->userAllottedProfiles($paramArr);
                
                if($profile["PROFILEID"]){
                        $photoScreeningServiceObj= new photoScreeningService();
                        $status = $photoScreeningServiceObj->pictureScreenStatus($profile["PROFILEID"],"CHECK");
                        if($status==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["UPLOAD_COMPLETED"]){
                                $this->reNewProfileForPreprocess($profile["PROFILEID"]);
                                return;
                        }
                        else{ 
                                return $profile;     
                        }
                }
                return;
        }
        /**
	 * List of profiles which have been allotted to some user atleast 30 min back and havent been screened yet.
	**/
        public function allottedProfilesToOthers($paramArr)
	{
                $mainAdmin = new MAIN_ADMIN();
                $profile = $mainAdmin->allottedProfiles($paramArr);
                
                if($profile["PROFILEID"]){
                        $photoScreeningServiceObj= new photoScreeningService();
                        $status = $photoScreeningServiceObj->pictureScreenStatus($profile["PROFILEID"],"CHECK");
                        if($status==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["UPLOAD_COMPLETED"]){
                                $this->reNewProfileForPreprocess($profile["PROFILEID"]);
                                return;
                        }
                        else{ 
                                return $profile;     
                        }
                }
                return;
	}
        /**
	 * List of profiles which are under screening and haven't been allotted to any screening user yet.
	**/
        public function unallottedProfiles($paramArr)
	{
                if ($paramArr["SOURCE"] == PictureStaticVariablesEnum::$SOURCE["MASTER"]) {
                        $profileObj = Operator::getInstance("",$paramArr["PROFILEID"]);
                        $profileObj->getDetail("","","USERNAME,PROFILEID,PHOTODATE");
                        
                        $arr["PHOTODATE"]=$profileObj->getPHOTODATE();
                        $arr["USERNAME"]=$profileObj->getUSERNAME();
                        $arr["PROFILEID"]=$profileObj->getPROFILEID();
                        return $arr;
                        
                } else {
                        $mainAdmin = new MAIN_ADMIN();
                        $nonScreenedPictureObj = new NonScreenedPicture();
                        $paramArr["noOperationPerformed"] = $nonScreenedPictureObj->screenBitCheck("NoOperation");

                        return $mainAdmin->unallottedProfiles($paramArr);
                }
        }
       /**
	 * List of profiles which are under screening uploaded a new photo so, renewing their process.
	**/
        public function reNewProfileForPreprocess($profileId)
	{ 
                $mainAdmin = new MAIN_ADMIN();
                return $mainAdmin->deleteEntryAfterScreening($profileId);
		
	}
        /**
	 * Returns the column RECEIVE_TIME from jsadmin.MAIN_ADMIN for a particular profile under screening.
	**/
        public function getAllotTime($profileid)
	{
		$mainAdmin = new MAIN_ADMIN();
                return $mainAdmin->getAllotTime($profileid);
	}
       /**
	 * Inserts the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
        public function allotProfile($paramArr)
	{
		$mainAdmin = new MAIN_ADMIN();
                if ($paramArr["SOURCE"] == PictureStaticVariablesEnum::$SOURCE["MASTER"])
                {
                        if($this->updateAlreadyAllotedProfile($paramArr)==1)
                                return;
                        else
                                return $mainAdmin->allotProfile($paramArr);
                }
                        
                return $mainAdmin->allotProfile($paramArr);
	}
        /**
	 * Update the entry of a photo profile under screening after 30min in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
        public function updateAlreadyAllotedProfile($paramArr)
	{
		$mainAdmin = new MAIN_ADMIN();
                return $mainAdmin->updateAlreadyAllotedProfile($paramArr);
	}
        /**
	 * Update the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
        public function reallotProfile($paramArr)
	{
		$mainAdmin = new MAIN_ADMIN();
                return $mainAdmin->reallotProfile($paramArr);
	}
        /**
	 * This function is used allot picture alloted for processing to change interface
	**/
        public function switchAlloted($paramArr,$profileId)
	{
                $mainAdmin = new MAIN_ADMIN();
                $mainAdmin->unallocateAlloted($paramArr,$profileId);
                $output = $mainAdmin->switchAlloted($paramArr,$profileId);
                
                $paramArr["PROFILEID"]=$profileId;
                $photoScreeningServiceObj = new photoScreeningService();
                return $photoScreeningServiceObj->showAllottedProfile($paramArr);
                
        }
       /**
	 * This function is used to allot or switch profile
	**/
        public function getProfileToScreen($allotParam,$switchProfile="")
	{ 
                if($switchProfile && $switchProfile!=""){
                        return $this->switchAlloted($allotParam,$switchProfile);
                }
                else{
                        return $this->getAllotedProfile($allotParam);
                }
        }
        
        
}
?>


