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
		$count = $chatLogObj->markChatSeen($viewer,$viewed);

		$dbName1 = JsDbSharding::getShardNo($viewed);
		$chatLogObj = new NEWJS_CHAT_LOG($dbName1);
		$count = $chatLogObj->markChatSeen($viewer,$viewed);
		
		return $count;
	}

public function getChatLogContactCount($receiver, $skipProfile = '',$considerProfile='')
    {
        if (!$receiver) {
            throw new jsException("", "No  reciever is specified in funcion getChatLogContactCount OF ChatLog.class.php");
        }
        $dbName        = JsDbSharding::getShardNo($receiver);
        $chatLogObj = new newjs_CHAT_LOG($dbName);
        $count         = $chatLogObj->getChatLogCount($receiver,  $skipProfile,$considerProfile);
        
        return $count;
    }
	
}
