<?php
/* 
 * Class for allotment of profiles for Photo Screening.
 * @package    jeevansathi
 * @subpackage photoScreening->mailAllotment
 * @author     Akash Kumar
  */
class PhotoScreenMailAllot
{
        const SEMAPHORE_ALLOTMENT = 1234;
        const SEMAPHORE_ALLOTMENT_OVER = 5678;

         public function getAllotedProfile($paramArr)
	{
                $source=$paramArr["SOURCE"];
                $name=$paramArr["NAME"];
                
                $photoScreeningServiceObj = new photoScreeningService();
		
                $paramArr["FLAG"]='';
		
                $appAllotTime = PictureStaticVariablesEnum::APP_ALLOT_TIME_INTERVAL;
                $paramArr["minTimeToAllot"]=date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i")." -".$appAllotTime." minutes"));
                
                $allottedProfile = $this->userAlloted($paramArr); //gets new/edit/mail photo profile that is allotted to the user but whose screening hasnt been done
		if($allottedProfile && !$allottedProfile['PROFILEID']) //profile is assigned to a screening user but screening is not done.
		{
                        $details[0] = 'assigned';
                        $mailDetails = new PHOTOS_FROM_MAIL();
                        $details[1] = $mailDetails->getMailDetails($allottedProfile['MAILID']);
                        $this->id = $allottedProfile['MAILID'];
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
				$paramArr["MAILID"]=$allottedProfile['MAILID'];
				$this->reallotProfile($paramArr);//assign profile to a screening user by updating alloted date and username.
                                $lockingObj->semreleaseLock($key);
				//Release Lock   
                                
				if($allottedProfile['MAILID'])
                                {
                                        $details[0]='assigned';
					$mailDetails = new PHOTOS_FROM_MAIL();
					$details[1] = $mailDetails->getMailDetails($allottedProfile['MAILID']);
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
                        
				if($newProfile && $source == 'mail')
				{
					$paramArr["MAILID"] = $newProfile['ID'];
					$receivetime=$newProfile['DATE'];
					$details[0]='assigned';
					$mailDetails = new PHOTOS_FROM_MAIL();
					$details[1] = $mailDetails->getMailDetails($paramArr['MAILID']);
				}
                                else
				{
					$details = "noProfileFound";
					return $details;
				}
				$paramArr["SUBMIT_TIME"]=timeFunctions::newtime($receivetime,0,$screen_time,0);
                                $paramArr["RECEIVE_TIME"]=$receivetime;
        
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
                $mainAdmin = new SCREEN_PHOTOS_FROM_MAIL();
                return $mainAdmin->userAllottedProfiles($paramArr);
        }
        /**
	 * List of profiles which have been allotted to some user atleast 30 min back and havent been screened yet.
	**/
        public function allottedProfilesToOthers($paramArr)
	{
                $mainAdmin = new SCREEN_PHOTOS_FROM_MAIL();
                return $mainAdmin->allottedProfiles($paramArr);
	}
        /**
	 * List of profiles which are under screening and haven't been allotted to any screening user yet.
	**/
        public function unallottedProfiles($paramArr)
	{
                $mainAdmin = new SCREEN_PHOTOS_FROM_MAIL();
                return $mainAdmin->unallottedProfiles($paramArr);
		
	}

       /**
	 * Inserts the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
        public function allotProfile($paramArr)
	{
		$mainAdmin = new SCREEN_PHOTOS_FROM_MAIL();
                return $mainAdmin->allotProfile($paramArr);
	}
        /**
	 * Update the entry of a photo profile under screening in the table jsadmin.MAIN_ADMIN and assign it to a screening user.
	**/
        public function reallotProfile($paramArr)
	{
		$mainAdmin = new SCREEN_PHOTOS_FROM_MAIL();
                return $mainAdmin->reallotProfile($paramArr);
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


