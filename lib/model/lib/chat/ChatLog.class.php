<?php
class ChatLog
{
    
public static function makeAllChatsSeen($profileid)
	{
		$dbName = JsDbSharding::getShardNo($profileid);
		$chatLogObj = new NEWJS_CHAT_LOG($dbName);
		$chatLogObj->makeAllChatsSeen($profileid);
	}

public function markChatSeen($viewer,$viewed,$id)
	{
		$dbName = JsDbSharding::getShardNo($viewer);
		$chatLogObj = new NEWJS_CHAT_LOG($dbName);
		$count = $chatLogObj->markChatSeen($viewer,$viewed,$id);

		$dbName1 = JsDbSharding::getShardNo($viewed);
		$chatLogObj = new NEWJS_CHAT_LOG($dbName1);
		$count = $chatLogObj->markChatSeen($viewer,$viewed,$id);
		
		return $count;
	}
	
}
