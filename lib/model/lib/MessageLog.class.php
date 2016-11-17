<?php
class MessageLog
{
    
    public function getMessageLogCount($where, $group = '', $select = '', $skipProfile = '')
    {
        if (!$where["RECEIVER"] && !$where["SENDER"]) {
            throw new jsException("", "No Sender or reciever is specified in funcion getContactsCount OF Contacts.class.php");
        } else {
            if ($where["RECEIVER"])
                $profileid = $where["RECEIVER"];
            else
                $profileid = $where["SENDER"];
        }
        $dbName        = JsDbSharding::getShardNo($profileid);
        $messageLogObj = new newjs_MESSAGE_LOG($dbName);
        $count         = $messageLogObj->getCustomMessageLogCount($where, $group, $select, $skipProfile);
        if ($count) {
            foreach ($count as $key => $value) {
                $arr[$value["SENDER"]] = $value["SEEN"];
            }
            $totalCount = count($arr);
            $value      = @array_count_values($arr);
            if ($value["Y"])
                $totalCountNew = $totalCount - $value["Y"];
            else
                $totalCountNew = $totalCount;
        }
        $messageCount["TOTAL"]     = $totalCount ? $totalCount : 0;
        $messageCount["TOTAL_NEW"] = $totalCountNew ? $totalCountNew : 0;
        return $messageCount;
    }
    
    public function getMessageLogContactCount($where, $group = '', $select = '', $skipProfile = '')
    {
        if (!$where["RECEIVER"] && !$where["SENDER"]) {
            throw new jsException("", "No Sender or reciever is specified in funcion getContactsCount OF Contacts.class.php");
        } else {
            if ($where["RECEIVER"])
                $profileid = $where["RECEIVER"];
            else
                $profileid = $where["SENDER"];
        }
        $dbName        = JsDbSharding::getShardNo($profileid);
        $messageLogObj = new newjs_MESSAGE_LOG($dbName);
        $count         = $messageLogObj->getMessageLogCount($where, $group, $select, $skipProfile);
        
        return $count;
    }
    public function getMessageLogProfile($profileId, $condition,$skipArray)
	{
		$dbName = JsDbSharding::getShardNo($profileid);
		$messageLogObj = new newjs_MESSAGE_LOG($dbName);
		$profileArray = $messageLogObj->getMessageLogProfile($condition,$skipArray);
		return $profileArray;
	}
	
	public function getMessageHistory($viewer,$viewed)
	{
		$dbName = JsDbSharding::getShardNo($viewer);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getMessageHistory($viewer,$viewed);
		return $messageArray;
	}
	public function getMessageHistoryPagination($viewer,$viewed,$limit="",$msgId="")
	{
		$dbName = JsDbSharding::getShardNo($viewer);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getMessageHistoryPagination($viewer,$viewed,$limit,$msgId);
		return $messageArray;
	}
	public function markMessageSeen($viewer,$viewed)
	{
		$dbName = JsDbSharding::getShardNo($viewer);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$count = $messageLogObj->markMessageSeen($viewer,$viewed);
		return $count;
	}
	public function getEOIMessages($loginProfile,$profileArray)
	{
		
		$dbName = JsDbSharding::getShardNo($loginProfile);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getEOIMessages(array($loginProfile),$profileArray);
		foreach($messageArray as $key=>$value)
		{
			$breaks = array("&lt;br&gt;","<br>","</br>","<br/>");
			$value["MESSAGE"] = str_ireplace($breaks,"\r\n",$value["MESSAGE"]);
			$message[$key] = $value;
		}
		return $message;
	}
	public function getEOIMessagesForChat($loginProfile,$profileArray)
	{

		$dbName = JsDbSharding::getShardNo($loginProfile);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getEOIMessagesForChat(array($loginProfile),$profileArray);
		foreach($messageArray as $key=>$value)
		{
			$breaks = array("&lt;br&gt;","<br>","</br>","<br/>");
			$value["MESSAGE"] = str_ireplace($breaks,"\r\n",$value["MESSAGE"]);
			$message[$key] = $value;
		}
		return $message;
	}
	public function getMessageListing($loginProfile,$condition,$skipArray)
	{
		$dbName = JsDbSharding::getShardNo($loginProfile);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$profileArray = $messageLogObj->getMessageListing($condition,$skipArray);
                $chatLogObj = new NEWJS_CHAT_LOG($dbName);
                $profileChatArray =  $chatLogObj->getMessageListing($condition,$skipArray);
                $profileArray = $this->mergeChatsAndMessages($profileArray,$profileChatArray,$condition['LIMIT']);
		$breaks = array("&lt;br&gt;","<br>","</br>","<br/>");
		foreach($profileArray as $profileid=>$value)
        {
			$array[$profileid]["LAST_MESSAGE"] = str_ireplace($breaks,"\r\n",$value[0]["MESSAGE"]);
			$array[$profileid]["TIME"] = $value[0]["DATE"];
			$array[$profileid]["COUNT"] = 0;
			
			$array[$profileid]["SEEN"] = ($value[0]["SR"] == "R") ? $value[0]["SEEN"] : "Y";
                        
			foreach($value as $key=>$val){
				if($val["SR"] == "R" && $val["SEEN"]!='Y')
					$array[$profileid]["COUNT"]++;
			}
		}//print_r($array);die;
		return $array;
	}
	public function getMessageReceivedListing($loginProfile,$condition,$skipArray)
	{
		$dbName = JsDbSharding::getShardNo($loginProfile);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$profileArray = $messageLogObj->getMessageReceivedListing($condition,$skipArray);
		$breaks = array("&lt;br&gt;","<br>","</br>","<br/>");
		foreach($profileArray as $profileid=>$value)
        {
        	$array[$profileid]["LAST_MESSAGE"] = str_ireplace($breaks,"\r\n",$value[0]["MESSAGE"]);
			$array[$profileid]["TIME"] = $value[0]["DATE"];
			$array[$profileid]["COUNT"] = 0;
			
			$array[$profileid]["SEEN"] = ($value[0]["SR"] == "R") ? $value[0]["SEEN"] : "Y";
                        
			foreach($value as $key=>$val){
				if($val["SR"] == "R" && $val["SEEN"]!='Y')
					$array[$profileid]["COUNT"]++;
			}
		}//print_r($array);die;
		return $array;
	}
	
	public function getCommunicationHistory($viewer,$viewed)
	{
		
		$dbName = JsDbSharding::getShardNo($viewer);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getCommunicationHistory($viewer,$viewed);
		return $messageArray;
	}

	public static function makeAllMessagesSeen($profileid)
	{
		$dbName = JsDbSharding::getShardNo($profileid);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageLogObj->makeAllMessagesSeen($profileid);
	}
        
        private function mergeChatsAndMessages($messageArr,$chatArr,$limit){
	if(!$limit)
		$limit = 1000000;

            $count = 0;
            $skip = array();
            $finalArr=array();
            if(count($messageArr)>0)
            foreach ($messageArr as $key=>$val){
                if(in_array($key, $skip))
                   continue;
                  
                if(count($chatArr)>0)
                foreach($chatArr as $k=>$v){
		     if(count($finalArr)>=$limit)
                        return $finalArr;
                    if($key == $k){
                        $finalArr[$key] = $this->sortInnerArr($val,$v);
                        break;
                    }
                    
                    elseif($val[0]['DATE']>$v[0]['DATE']){
                        $finalArr[$key] = $val;
                        unset($messageArr[$key]);
                        break;
                    }
                    
                    else{
                        if(array_key_exists($k, $messageArr)){
                          $finalArr[$k] = $this->sortInnerArr($messageArr[$k],$v);
                          $skip[] = $k; 
                          unset($messageArr[$k]);
                        }
                        else if(array_key_exists($k, $finalArr)){
                          $finalArr[$k] = $this->sortInnerArr($finalArr[$k],$v);
                          $skip[] = $k;
                        }
                        else
                          $finalArr[$k] = $v;
                        unset($chatArr[$k]);
                    }
                }
                else if(count($finalArr)<$limit){
                    $finalArr[$key] = $val;
                }
            }
            if(count($messageArr)==0 && count($finalArr) < $limit){
                foreach ($chatArr as $key=>$val){
                    if(count($finalArr)>=$limit)
                        break;
                    
                    $finalArr[$key] = $val;
                }
            }
/***/
if($limit == 1000000)
{
	$pid = LoggedInProfile::getInstance()->getPROFILEID();
	JsMemcache::getInstance()->set("message_count_".$pid,count($finalArr),18000);
	$finalArr = array_splice($finalArr,0,$ankit);
}
/***/
            return $finalArr;
        }
        
        private function sortInnerArr($messageInnerArr,$chatInnerArr){
            if(count($messageInnerArr)>0)
            foreach ($messageInnerArr as $key=>$val){
                if(count($chatInnerArr)>0)
                foreach($chatInnerArr as $k=>$v) {
                    if($val['DATE'] > $v['DATE']){
                        $finalInnerArr[] = $val;
                        unset($messageInnerArr[$key]);
                        break;
                    }
                    else{
                        $finalInnerArr[] = $v;
                        unset($chatInnerArr[$k]);
                    }
                }
            }
            if(count($messageInnerArr)>0){
                foreach ($messageInnerArr as $key=>$val)
                    $finalInnerArr[] = $val;
            }
            if(count($chatInnerArr)>0){
                foreach ($chatInnerArr as $key=>$val)
                    $finalInnerArr[] = $chatInnerArr[$val];
            }
            return $finalInnerArr;
        }
}
?>
