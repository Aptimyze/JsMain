<?php
/**
 * @brief This class is used to handle all functionalities related to chat
 * @author Prinka Wadhwa
 * @created 2012-08-16
 */

class ChatLibrary
{
        private $dbname;
        public function __construct($dbname='')
        {
                $this->dbname = $dbname;
        }
	public function getIfChatRequestSent($senders, $receiver, $key)
	{
		/* Function no longed called
		$chatRequested = new userplane_LOG_CHAT_REQUEST($this->dbname);
		$chatRequests = $chatRequested->getIfChatRequestSent($senders, $receiver, $key);
		return $chatRequests;
		*/
	}

	public function getIfUserIsOnlineInGtalk($profileIdStr,$key)
	{
		/* Function no longed called
		$userOnline = new USER_ONLINE($this->dbname);
		$gtalkUsers = $userOnline->get($profileIdStr,$key);
		return $gtalkUsers;
		*/
	}

	/**
	*
	*/	
	public static function getPresenceOfIds($profileIdStr="")
	{
		$url = JsConstants::$presenceServiceUrl."/profile/v1/presence"; 
		if($profileIdStr)
			$url.= "?pfids=$profileIdStr"; 
		$out = CommonUtility::sendCurlPostRequest($url,'',5);
		$out1 = (array)json_decode($out);
		$arr = $out1["data"];
		return $arr;
	}

	public function getIfUserIsOnlineInJSChat($profileIdStr,$key)
	{
		if(JsConstants::$jsChatFlag=='1')
		{
			$arr = self::getPresenceOfIds($profileIdStr);
			foreach($arr as $k=>$v)
				$onlineUsers[$v] = 1;
			return $onlineUsers;
		}
		else
		{
			$userplane_USERS = new USERPLANE_USERS($this->dbname);
			$jsChatUsers = $userplane_USERS->get($profileIdStr,$key);
			return $jsChatUsers;
		}
	}
	public function findOnlineProfiles($key='',$SearchParamtersObj='')
	{
		if(JsConstants::$jsChatFlag=='1')
		{
			$arr = self::getPresenceOfIds();
                        $profileIdStr = implode(" ",$arr);
                        return $profileIdStr;
		}
		else
		{
			$userplane_USERS = new USERPLANE_USERS($this->dbname);
			$onlineUsers = $userplane_USERS->get('',$key,$SearchParamtersObj);
			unset($userplane_USERS);
			$userOnline = new USER_ONLINE('');
			$onlineUsers.= $userOnline->get('',$key,$SearchParamtersObj);
			return $onlineUsers;
		}
	}
	
	public function getChatRequestProfiles($profileid)
	{
		/* Function no longed called
		$userplace_CHATObj = new userplace_CHAT_REQUEST($this->dbname);
		$chatRequestedProfile = $userplace_CHATObj->getChatRequestProfiles($profileid);
		return $chatRequestedProfile;
		*/
	}
	public function getChatRequestCount($profileid)
	{
		/* Function no longed called
		$userplane_CHATObj = new userplane_CHAT_REQUEST($this->dbname);
		$chatRequestsCount = $userplane_CHATObj->getChatRequestCount($profileid);
		return $chatRequestsCount;
		*/
	}
}
?>
