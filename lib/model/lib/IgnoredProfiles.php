<?php
/**
 * @brief This class is used to handle ignore functionality between users
 * @author Lavesh Rawat
 * @created 2012-08-14
 */

class IgnoredProfiles
{
        /**
        *  This function list the two way ignored profile ie.
        1) ignored by user       2) users ignored
	@param pid profileid of userfor which two way ignore need to be find.
        */
        public function listIgnoredProfile($pid,$seperator='')
        {
		$NEWJS_IGNOREObj = new newjs_IGNORE_PROFILE;
		return $NEWJS_IGNOREObj->listIgnoredProfile($pid,$seperator);
        }

	public function ignoreProfile($profileid, $ignoredProfileid)
	{
		$ignObj = new newjs_IGNORE_PROFILE();
		$ignObj->ignoreProfile($profileid,$ignoredProfileid);
		
		JsMemcache::getInstance()->remove($profileid);
		JsMemcache::getInstance()->remove($ignoredProfileid);
		
	}

	public function undoIgnoreProfile($profileid, $ignoredProfileid)
	{
		$ignObj = new newjs_IGNORE_PROFILE();
		$ignObj->undoIgnoreProfile($profileid,$ignoredProfileid);
		JsMemcache::getInstance()->remove($profileid);
		JsMemcache::getInstance()->remove($ignoredProfileid);
		
	}

	public function ifProfilesIgnored($profileIdStr, $viewer, $key='')
	{
		$ignObj = new newjs_IGNORE_PROFILE();
		$ignProfile = $ignObj->getIgnoredProfiles($profileIdStr,$viewer,$key);
		return $ignProfile;
	}
	public function getIgnoredProfile($profileid,$condition='',$skipArray='')
	{
		$ignObj = new newjs_IGNORE_PROFILE();
		$ignoredProfile = $ignObj->getIgnoredProfilesList($profileid,$condition,$skipArray);
		return $ignoredProfile;
	}

	public function ifIgnored($profileid,$otherProfileId)
	{
		$ignoreObj = new newjs_IGNORE_PROFILE();
		return $ignoreObj->isIgnored($profileid,$otherProfileId);
	}
}
?>
