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
		$chatRequested = new userplane_LOG_CHAT_REQUEST($this->dbname);
		$chatRequests = $chatRequested->getIfChatRequestSent($senders, $receiver, $key);
		return $chatRequests;
	}

	public function getIfUserIsOnlineInGtalk($profileIdStr,$key)
	{
		$userOnline = new USER_ONLINE($this->dbname);
		$gtalkUsers = $userOnline->get($profileIdStr,$key);
		return $gtalkUsers;
	}

	public function getIfUserIsOnlineInJSChat($profileIdStr,$key)
	{
		$userplane_USERS = new USERPLANE_USERS($this->dbname);
		$jsChatUsers = $userplane_USERS->get($profileIdStr,$key);
		return $jsChatUsers;
	}
	public function findOnlineProfiles($key='',$SearchParamtersObj='')
	{
		$userplane_USERS = new USERPLANE_USERS($this->dbname);
		$onlineUsers = $userplane_USERS->get('',$key,$SearchParamtersObj);
		unset($userplane_USERS);
		$userOnline = new USER_ONLINE('');
		$onlineUsers.= $userOnline->get('',$key,$SearchParamtersObj);
		return $onlineUsers;
	}
	
	public function getChatRequestProfiles($profileid)
	{
		$userplace_CHATObj = new userplace_CHAT_REQUEST($this->dbname);
		$chatRequestedProfile = $userplace_CHATObj->getChatRequestProfiles($profileid);
		return $chatRequestedProfile;
	}
	public function getChatRequestCount($profileid)
	{
		$userplane_CHATObj = new userplane_CHAT_REQUEST($this->dbname);
		$chatRequestsCount = $userplane_CHATObj->getChatRequestCount($profileid);
		return $chatRequestsCount;
	}
}
?>
