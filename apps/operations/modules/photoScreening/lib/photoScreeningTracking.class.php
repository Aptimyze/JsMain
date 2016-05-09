<?php

class photoScreeningTracking
{

	/**
	 * This function is used to update the columns HAVEPHOTO, PHOTOSCREEN, PHOTODATE
	 * from the table newjs.JPROFILE after photos of a user are screened.
	 * @param: $profileid - profileid for which screening-tracking is to be done.
	 * @param: $source - source (new/mail/edit) from which photoscreening was done.
	**/
	public function updateNewjs($profileid, $source,$noOfPicsApproved)
	{
		$profileObj = Operator::getInstance('newjs_master',$profileid);
                $initialHavephoto = $profileObj->getDetail("","","HAVEPHOTO");
		$pictureServiceObj=new PictureService($profileObj);
                $album = $pictureServiceObj->getAlbum();
		$screenedPics = 0;
		$nonscreenedPics = 0;
		$screenedProfilePic = 0;
		$nonscreenedProfilePic = 0;
		if($album)
		{
//			if($album)
			{
				foreach((array)$album as $val)
				{
					if($val->getPictureType()=='S')
					{
						if($val->getOrdering()==0)
							$screenedProfilePic = 1;
						$screenedPics++;			
					}
					elseif($val->getPictureType()=='N')
					{
						if($val->getOrdering()==0)
							$nonscreenedProfilePic = 1;
						$nonscreenedPics++;
					}
				}
			}
			if($nonscreenedPics>0 && $screenedPics>0)
			{
				if($screenedProfilePic == '1')
					$havephoto = 'Y';
				elseif($nonscreenedProfilePic == '1')
					$havephoto = 'U';
				$photoscreen = 0;
			}
			elseif($screenedPics>0)
			{
				$havephoto = 'Y';
				$photoscreen = 1;
			}
			elseif($screenedPics == 0 && $nonscreenedPics == 0)
			{
				$havephoto = 'N';
				$photoscreen = 1;
			}

			if($source == 'new' || $source == 'edit')
			{
				if($noOfPicsApproved>0)
				{
					$time = time();
					$now = date('Y-m-d H:i:s',$time);
					$profileObj->edit(array("HAVEPHOTO"=>"$havephoto","PHOTOSCREEN"=>$photoscreen,"SORT_DT"=>"$now"));
				}
				else
					$profileObj->edit(array("HAVEPHOTO"=>"$havephoto","PHOTOSCREEN"=>$photoscreen));
			}
			elseif($source == 'mail')
			{
				if($noOfPicsApproved>0)
				{
					$time = time();
					$now = date('Y-m-d H:i:s',$time);
					$profileObj->edit(array("HAVEPHOTO"=>"$havephoto","PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>$photoscreen,"SORT_DT"=>"$now"));
				}
				else
					$profileObj->edit(array("HAVEPHOTO"=>"$havephoto","PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>$photoscreen));
			}


			if($havephoto == 'Y' && $initialHavephoto['HAVEPHOTO']!='Y')
			{
				$photo_first = new PHOTO_FIRST();
				$photo_first->newPhotoEntry($profileid);
			}

			$flag = 1;
		}

		if(($initialHavephoto['HAVEPHOTO'] == 'N' || $initialHavephoto['HAVEPHOTO'] == '') && $flag!=1)
		{
			if($noOfPicsApproved)
			{
				$havephoto='Y';
				$time = time();
				$now = date('Y-m-d H:i:s',$time);
				$profileObj->edit(array("HAVEPHOTO"=>"$havephoto","PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>1,"SORT_DT"=>"$now"));
				//start: added for FTO MIS tracking
				$ftoMisObj = new FTO_MIS_USERS_PHONE_PHOTO_DATA();
				$ftoMisObj->updatePhotoUploadCount();
				//end: added for FTO MIS tracking
				$photo_first = new PHOTO_FIRST();
				$photo_first->newPhotoEntry($profileid);
			}
		}
		elseif(($initialHavephoto['HAVEPHOTO'] == 'U' || $initialHavephoto['HAVEPHOTO'] == 'Y') && !$album)
			$profileObj->edit(array("HAVEPHOTO"=>"N","PHOTOSCREEN"=>1));
			
                if($initialHavephoto['HAVEPHOTO'] != $havephoto && $havephoto=='Y')
                {
                        $PHOTO_REQUEST=new NEWJS_PHOTO_REQUEST('shard1_master');
                        $PHOTO_REQUEST->updateUploadSeen($profileid);
                        $PHOTO_REQUEST=new NEWJS_PHOTO_REQUEST('shard2_master');
                        $PHOTO_REQUEST->updateUploadSeen($profileid);
                        $PHOTO_REQUEST=new NEWJS_PHOTO_REQUEST('shard3_master');
                        $PHOTO_REQUEST->updateUploadSeen($profileid);
                }

		if($initialHavephoto['HAVEPHOTO'] != $havephoto)
		{
			//start: added for changing FTO state
			$action = FTOStateUpdateReason::PHOTO;
			$profileObj->getPROFILE_STATE()->updateFTOState($profileObj, $action);
			//end: added for changing FTO state
			return 1; //call UpdateScore
		}
		else
			return 0;
	}

	/**
	 * This function is used to update the table jsadmin MAIN_ADMIN_LOG to track the screening status of a photo profile.
	 * After logging the screen action in MAIN_ADMN_LOG, it delete the entry of that profile from jsadmin.MAIN_ADMIN in case of 'screen new/edit photos'
	 * or update the entry of that profile from jsadmin.SCREEN_PHOTOS_FROM_MAIL in case of 'screen photos from mail'.
	 * @param: $profileid - profileid for which screening-tracking is to be done.
	 * @param: $source - source (new/mail/edit) from which photoscreening was done.
	 * @param: $appPhotoCount - no of photos approved by the screening user.
	 * @param: $delPhotoCount - no of photos not approved/deleted by the screening user.
	 * @param: $mailid - id corresponding to the mail, from which the attachments were screened.
	 * @return: $rec_time - time at which the photos that are being screened were received in screening.
	**/
	public function updateJsadmin($source,$profileid,$appPhotoCount='',$delPhotoCount='',$mailid='',$appPicStatus='')
	{
		if($source == 'new' || $source == 'edit')
		{
			$main_admin = new MAIN_ADMIN();
			$rec_time = $main_admin->getReceiveTime($profileid);
//			$main_admin->deleteEntryAfterScreening($profileid);
		}
		elseif($source == 'mail')
		{
			$screen_photos_from_mail = new SCREEN_PHOTOS_FROM_MAIL();
			$rec_time = $screen_photos_from_mail->getReceiveTime($profileid,$mailid);
			$screen_photos_from_mail->logScreeningAction($profileid,$mailid,$appPhotoCount,$delPhotoCount);
		}
		elseif($source == "appPic")
		{
			$screen_photos_for_app = new JSADMIN_SCREEN_PHOTOS_FOR_APP;
                        $rec_time = $screen_photos_for_app->getReceiveTime($profileid);
		}

		$main_admin_log = new MAIN_ADMIN_LOG();
		if($appPhotoCount || $delPhotoCount || $appPicStatus)
		{
			$date_time=(int)explode(" ",$rec_time);
			$date_y_m_d=(int)explode("-",$date_time[0]);
			$time_h_m_s=(int)explode(":",$date_time[1]);
			$timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
			$timezone=date("T",$timestamp);
			if($timezone=="EDT")
			$timezone="EST5EDT";

			if($appPicStatus)
				$status = $appPicStatus;
			else
				$status = "APPROVED-$appPhotoCount,DELETED-$delPhotoCount";
			$main_admin_log->logPhotoScreeningAction($profileid,$status,$timezone,$source);
		}
		if($source == 'new' || $source == 'edit')
			$main_admin->deleteEntryAfterScreening($profileid);
		elseif($source == 'appPic')
			$screen_photos_for_app->deleteEntryAfterScreening($profileid);

		return $rec_time;
	}

	/**
	 * This function is used to update the screened photos count in the table mis.SCREEN_EFFICIENCY and mis.PHOTO_SCREEN_STATS.
	 * @param: $source - source (new/mail/edit) from which photoscreening was done.
	 * @param: $source2 - is sent in case of $source = 'mail' and contains the value new if the suer had no screened photos, edit if the user had screened photos, null if the user had no photos.
	 * @param: $profileid - profileid for which screening-tracking is to be done.
	 * @param: $mailid - id corresponding to the mail, from which the attachments were screened.
	 * @param: $user - name of the screening user who has done the screening for this profile.
	 * @param: $mailAppPhotos - no of photos(that were sent by mail) approved by the screening user.
	 * @param: $appPhotos - no of photos(that were uploaded by the user) approved by the screening user.
	 * @param: $mailDelPhotos - no of photos(that were sent by mail) not approved/deleted by the screening user.
	 * @param: $delPhotos - no of photos(that were uploaded by the user) not approved/deleted by the screening user.
	 * @param: $rec_time - time at which the photos that are being screened were received in screening.
	**/
	public function updateMis($source,$source2="",$profileid,$mailid="",$user,$mailAppPhotos,$appPhotos,$mailDelPhotos,$delPhotos,$rec_time)
	{
		//$source2 is sent in case of $source = 'mail' and contains the values new/edit/null
		//appPhotos,delPhotos contain photos app/del from $source --- mailAppPhotos,mailDelPhotos contain photos app/del from $source2

		$photo_screen_stats = new PHOTO_SCREEN_STATS();
		if($source == 'new' || $source == 'edit')
		{
			if($mailAppPhotos)
				$appPhotos = $mailAppPhotos+$appPhotos;
			if($mailDelPhotos)
				$delPhotos = $mailDelPhotos+$delPhotos;
			$photo_screen_stats->updateScreenedPhotoCount($user,$source,$appPhotos,$delPhotos);
		}
		elseif($source == 'mail')
		{
			$photo_screen_stats->updateScreenedPhotoCountMail($user,$source2,$mailAppPhotos,$appPhotos,$mailDelPhotos,$delPhotos);
		}
		elseif($source == "appPic")
		{
			$source = "app_pic";
			$photo_screen_stats->updateScreenedPhotoCountMobileAppPic($user,$source,$appPhotos,$delPhotos);
		}
		$screen_efficiency = new SCREEN_EFFICIENCY();
		$screen_efficiency->updateScreenedProfilesCount($source,$source2,$rec_time);
	}

	/**
	 * This function is used to update the new value of score for the photo profile for which screening was done.
	 * This score is updated in the table incentive.MAIN_ADMIN_POOL.
	 * @param: $profileid - profileid for which score is to be updated.
	**/
	public function updateIncentive($profileid)
	{
		$scoreObj = new UpdateScore();
		$score = $scoreObj->update_score($profileid);
		$main_admin_pool = new incentive_MAIN_ADMIN_POOL();
		$main_admin_pool->updateScore($profileid,$score);
	}

	/**
	 * This function is used to track the profileids whose JPROFILE.PHOTOSCREEN,HAVEPHOTO values indicate the profile's photos are up for screening
	 * but the profile does not have any photos to be screened.
	 * @param: $profileid - profileid for which an invalid entry was encountered.
	**/
	public function trackWrongScreeningEntries($profileid)
	{
		$obj = new INVALID_SCREENING_ENTRIES_TRACKING();
		$obj->trackInvalidScreeningEntries($profileid);
	}

        /*
        * update screened picture-id deatils
        */
        public function updateImageDetails($oldPicId,$newMappedPicId,$profileid)
        {
                $NEWJS_PICTURE_DETAILS = new NEWJS_PICTURE_DETAILS;
                $NEWJS_PICTURE_DETAILS->upd($oldPicId,$newMappedPicId,$profileid);
                $duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION = new duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION;
                $duplicate_SCREENED_PICTUREIDS_FOR_DUPLICATION->ins($profileid,$newMappedPicId);
        }

}

?>
