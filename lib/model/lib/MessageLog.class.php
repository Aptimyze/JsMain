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
	public function getMessageListing($loginProfile,$condition,$skipArray)
	{
		$dbName = JsDbSharding::getShardNo($loginProfile);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$profileArray = $messageLogObj->getMessageListing($condition,$skipArray);
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
	
	public function getCommunicationHistory($viewer,$viewed,$limitArray)
	{
		
		$dbName = JsDbSharding::getShardNo($viewer);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getCommunicationHistory($viewer,$viewed,$limitArray);
		return $messageArray;
	}

	public static function makeAllMessagesSeen($profileid)
	{
		$dbName = JsDbSharding::getShardNo($profileid);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageLogObj->makeAllMessagesSeen($profileid);
	}
}
?>
