<?php
/* This class saves the values of privacy settings into appropriate tables
*/
class privacySettings
{
	public function updatePrivacySettings($field,$privacy)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$profileid = $loggedInProfileObj->getPROFILEID();
		$profileDetail = $loggedInProfileObj->getDetail($profileid,"PROFILEID","*");		
		if(in_array($field,privacySettingsEnums::$jprofileFields))
		{
			$privacyObj = new JPROFILE();
			$editLogObj = new EDIT_LOG();
			if($field == privacySettingsEnums::$ProfileVisibilityLabel)
			{
				if($privacy != $profileDetail["PRIVACY"])
				{
					$privacyObj->UpdatePrivacy($privacy,$profileid);	// edit log entry?	
					return 1;
				}
				else
					return 0;				
			}
			elseif($field == privacySettingsEnums::$PhotoSettingLabel)
			{
				if(!in_array($privacy,privacySettingsEnums::$validPhotoPrivacyValues))
					$privacy = "A";

				if($privacy == 'C')
                {
                    PictureFunctions::photoUrlCachingForChat($profileid, array(), "ProfilePic120Url",'', "remove");
                }                
                if($privacy != $profileDetail["PHOTO_DISPLAY"])
                {
                	$this->updateSetting("PHOTO_DISPLAY",$profileid,$privacyObj,$editLogObj,$privacy);
                	return 1;
                }
                else
                {
                	return 0;
                }
				
			}
			elseif($field == privacySettingsEnums::$MobileSettingLabel)
			{
				if($privacy != $profileDetail["SHOWPHONE_MOB"])
				{
					$this->updateSetting("SHOWPHONE_MOB",$profileid,$privacyObj,$editLogObj,$privacy);
					return 1;
				}
				else
				{
					return 0;
				}				
			}
			elseif($field == privacySettingsEnums::$LandlineSettingLabel)
			{
				if($privacy != $profileDetail["SHOWPHONE_RES"])
				{
					$this->updateSetting("SHOWPHONE_RES",$profileid,$privacyObj,$editLogObj,$privacy);
					return 1;
				}
				else
				{
					return 0;
				}
			}
			unset($privacyObj);
			unset($editLogObj);
		}
		elseif(in_array($field,privacySettingsEnums::$jprofileContactFields))
		{
			$jprofileContactArr = array();
			$jprofileContactArr["SHOWALT_MOBILE"] = $privacy;
			if($privacy != $loggedInProfileObj->getExtendedContacts()->SHOWALT_MOBILE)
			{
				$loggedInProfileObj->editCONTACT($jprofileContactArr);
				return 1;

			}
			else
			{
				return 0;
			}
			unset($jprofileContactArr);
		}
	}

	public function updateSetting($field,$profileid,$privacyObj,$editLogObj,$privacy)
	{
		$editJprofileArray = array($field=>$privacy,"MOD_DT"=>date("Y-m-d G:i:s"));
		$privacyObj->edit($editJprofileArray,$profileid);
		unset($editJprofileArray);
		$now = date("Y-m-d H:i:s");
		$editArray = array($field=>$privacy,"PROFILEID"=>$profileid,"MOD_DT"=>$now);				
		$editLogObj->log_edit($editArray, $profileid);
		unset($editArray);
	}
}