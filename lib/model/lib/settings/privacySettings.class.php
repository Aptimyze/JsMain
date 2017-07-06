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
			if($field == privacySettingsEnums::$ProfileVisibilityLabel)
			{
				if($privacy != $profileDetail["PRIVACY"])
				{
					$this->UpdatePrivacy($privacy,$profileid);
					PictureFunctions::photoUrlCachingForChat($profileid, array(), "ProfilePic120Url",'', "remove");
					return 1;
				}
				else
					return 0;				
			}
			elseif(array_key_exists($field, privacySettingsEnums::$jprofileFieldsToUpdate))
			{				
				if($field == privacySettingsEnums::$PhotoSettingLabel)
				{
					if(!in_array($privacy,privacySettingsEnums::$validPhotoPrivacyValues))
						$privacy = "A";

					if($privacy == 'C')
					{
						PictureFunctions::photoUrlCachingForChat($profileid, array(), "ProfilePic120Url",'', "remove");
					} 
				}

				if($privacy != $profileDetail[privacySettingsEnums::$jprofileFieldsToUpdate[$field]])
				{
					$this->updateSetting(privacySettingsEnums::$jprofileFieldsToUpdate[$field],$profileid,$privacy);
					return 1;
				}
				else
				{
					return 0;
				}
			}
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

	public function updateSetting($field,$profileid,$privacy)
	{
		$privacyObj = new JPROFILE();
		$editLogObj = new EDIT_LOG();

		$editJprofileArray = array($field=>$privacy,"MOD_DT"=>date("Y-m-d G:i:s"));
		$privacyObj->edit($editJprofileArray,$profileid);
		unset($editJprofileArray);
		$now = date("Y-m-d H:i:s");
		$editArray = array($field=>$privacy,"PROFILEID"=>$profileid,"MOD_DT"=>$now);				
		$editLogObj->log_edit($editArray, $profileid);
		unset($editArray);
		unset($privacyObj);
		unset($editLogObj);
	}

	public function UpdatePrivacy($privacy,$profileid)
	{
		$privacyObj = new JPROFILE();
		$privacyObj->UpdatePrivacy($privacy,$profileid);
		unset($privacyObj);
	}
}