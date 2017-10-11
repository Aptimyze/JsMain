<?php
class TestingDataCreation

{
        public static function addHardFilter($loggedIn,$other)
        {
                $filterObj = new ProfileFilter;
                $filterObj->setAllFilters($other);
        }
	public static function updateContactDate($loggedIn,$other,$folder)
	{
		$loggedInDb = JsDbSharding::getShardNo($loggedIn,'');
		$otherDb = JsDbSharding::getShardNo($other,'');
		$contactsLoggedInObj = new newjs_CONTACTS($loggedInDb);

		if($folder=="AWAITING_RESPONSE")
		{
			$sender=$other;
			$receiver = $loggedIn;
		}
		else
		{
			$sender=$loggedIn;
			$receiver = $other;
		}
		$contactsLoggedInObj->updateContactDate($sender,$receiver);
		if($loggedInDb!=$otherdb)
		{
			$contactsOtherObj = new newjs_CONTACTS($otherDb);
			$contactsOtherObj->updateContactDate($sender,$receiver);
		}
	}
        public static function createFolderData($loggedIn,$other,$folder)
        {
                switch($folder)
                {
                        case "FILTERED":
                                $profileMemcacheObj = new ProfileMemcacheService($loggedIn);
                                $profileMemcacheObj->update("FILTERED",1);
                                $loggedInDb = JsDbSharding::getShardNo($loggedIn,'');
                                $otherDb = JsDbSharding::getShardNo($other,'');
                                $contactsLoggedInObj = new newjs_CONTACTS($loggedInDb);
                                $success=$contactsLoggedInObj->setFILTERED($other,$loggedIn);
                                if($loggedInDb!=$otherdb && $success)
                                {
                                        $contactsOtherObj = new newjs_CONTACTS($otherDb);
                                        $success=$contactsOtherObj->setFILTERED($other,$loggedIn);
                                }
                                break;
                        case "SHORTLIST":
                                $bookmarkObj = new Bookmarks();
                                $bookmarkObj->addBookmark($loggedIn,$other);
                                $profileMemcacheObj = new ProfileMemcacheService($loggedIn);
                                $profileMemcacheObj->update("BOOKMARK",1);
                                $profileMemcacheObj->updateMemcache();
                                break;
                        case "PHOTO_REQ_RECIEVED":
                                LoggedInProfile::getInstance('newjs_master',$other);
                                $viewerObj = Profile::getInstance("newjs_master",$loggedIn);
                                $viewerObj->getDetail("","","USERNAME,PRIVACY,GENDER");
                                $picServiceObj = new PictureService($viewerObj);
                                $output = $picServiceObj->performPhotoRequest();
                                break;
                        case "PHOTO_REQ_SENT":
                                LoggedInProfile::getInstance('newjs_master',$loggedIn);
                                $viewerObj = Profile::getInstance("newjs_master",$other);
                                $viewerObj->getDetail("","","USERNAME,PRIVACY,GENDER");
                                $picServiceObj = new PictureService($viewerObj);
                                $output = $picServiceObj->performPhotoRequest();
                                break;
                        case "MATCHALERT":
                                $matchalertLogObj = new matchalerts_LOG;
                                $matchalertLogObj->setProfileMatch($loggedIn,$other);
                                break;
                        case "IGNORED":
                                $ignoreProfileObj = new IgnoredProfiles;
                                $ignoreProfileObj->ignoreProfile($loggedIn, $other);
                                break;
                        case "VISITOR":
                                $viewLogTriggerObj = new VIEW_LOG_TRIGGER;
                                $viewLogTriggerObj->updateViewTrigger($other,$loggedIn);
                                break;
                        case "KUNDLI_MATCHES":
                                $kundliMatchObj = new KUNDLI_ALERT_KUNDLI_CONTACT_CENTER;
                                $kundliMatchObj->matchKundliOfProfiles($loggedIn,$other);
                                break;
                        case "VIEWED_MY_DETAILS":
                                break;
                        case "INTRO_CALL";
                                break;
                        case "HOROSCOPE_REQ_RECEIVED":
                                break;
                        case "HOROSCOPE_REQ_SENT":
                                break;
                }
        }
}
