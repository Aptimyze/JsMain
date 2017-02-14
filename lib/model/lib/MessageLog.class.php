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
	public function getEOIMessages($loginProfile,$profileArray,$arrayForRB = '')
	{  
		$request = sfContext::getInstance()->getRequest();
		$infoTypeId = $request->getParameter('infoTypeId');
		$dbName = JsDbSharding::getShardNo($loginProfile);
		$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
		$messageArray = $messageLogObj->getEOIMessages(array($loginProfile),$profileArray);

		foreach($messageArray as $key=>$value)
		{	
			$breaks = array("&lt;br&gt;","<br>","</br>","<br/>");
			$value["MESSAGE"] = str_ireplace($breaks,"\r\n",$value["MESSAGE"]);
			$message[$key] = $value;
		}

		if(!is_array($message)){
		$message = array();
		}
		if(is_array($arrayForRB))
		{	
		foreach ($arrayForRB as $key => $value) {
			if($value['MSG_DEL'] == 'Y' && $this->toUpdateRB($message,$value['SENDER'],$value['RECEIVER']))
			{
				$RBmessage['SENDER'] = $value['SENDER'];
				$RBmessage['RECEIVER'] = $value['RECEIVER'];
				$RBmessage['TYPE'] = $value['TYPE'];
 				$RBmessage['DATE'] = $value['TIME'];
                                $profileObj = new Profile('',$value['SENDER']);
                                $receiverObj = new Profile('',$value['RECEIVER']);
				$messageForRB = $this->getRBMessage($value['SENDER'],$receiverObj,$profileObj);
				unset($profileObj);
				unset($receiverObj);
				$RBmessage['MESSAGE'] = $messageForRB;
				array_push($message, $RBmessage);
			}

		}
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

		$pid = $loginProfile;
		$memccKey = $pid."_cc_myMessage";
		$profileArray = JsMemcache::getInstance()->get($memccKey);
		if($profileArray)
		{
			//JsMemcache::getInstance()->set($memccKey,'',0);
			JsMemcache::getInstance()->delete($memccKey);
		}
		else
		{
			$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
			$profileArray = $messageLogObj->getMessageListing($condition,$skipArray);
			JsMemcache::getInstance()->set($memccKey,$profileArray);
		}



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
		foreach ($messageArray as $key => $value) {

			if( $key=='0' && $value['TYPE']=='I' && $value['MESSAGE'] == NULL && $this->EOIFromRB($value['SENDER'],$value['RECEIVER']))
			{ 
                          $receiverObj = new Profile('',$value['RECEIVER']);
                          $profileObj = new Profile('',$value['SENDER']);
			  $message =$this->getRBMessage($value['SENDER'],$receiverObj,$profileObj);	
			  $messageArray[$key]['MESSAGE'] = $message;	
			}
		}
		unset($receiverObj);
		unset($profileObj);
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
		else
		   break;
            }
            if(count($messageArr)==0 && count($finalArr) < $limit){
                foreach ($chatArr as $key=>$val){
                    if(count($finalArr)>=$limit)
                        break;
                    
                    $finalArr[$key] = $val;
                }
            }
	    if(count($finalArr)<$limit && count($messageArr!=0) && count($chatArr)==0){
		foreach ($messageArr as $key=>$val){
                    $finalArr[$key] = $val;
		    if(count($finalArr) >=$limit)
			break;
		}
            }
/***/
if($limit == 1000000)
{
	$pid = LoggedInProfile::getInstance()->getPROFILEID();
	JsMemcache::getInstance()->set("message_count_".$pid,count($finalArr),18000);
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

      private function isJsDummyMember($profileid)
	{
		if($this->isDummy[0]==$profileid)
			return $this->isDummy[1];

		$dbObj=new jsadmin_PremiumUsers;
		$this->isDummy[0]=$profileid;
		if($dbObj->isDummy($profileid))
		{
			$this->isDummy[1]=true;
			return true;
		}
		$this->isDummy[1]=false;
		return false;
	}

	public function EOIFromRB($sender,$receiver)
	{

           $dbName = JsDbSharding::getShardNo($sender,'');
	   $dbObj = new newjs_CONTACTS($dbName);
	   $isRB = $dbObj->isRBContact($sender,$receiver);

	   if($isRB == 1)
	   {  
	   		return 1;
	   }
	   return 0;
	}

	public function getRBMessage($sender,$receiverObj,$profileObj)
	{

		if($this->isJsDummyMember($sender))
				{
					if($receiverObj->getHAVEPHOTO()=="N" || $receiverObj->getHAVEPHOTO()=="")
							$message=Messages::getMessage(Messages::JSExNoPhoMes,array("EMAIL"=>$profileObj->getEMAIL()));
					else
					{
							$draftsObj = new ProfileDrafts($profileObj);
							$message=ProfileDrafts::getMessage($draftsObj->getEoiDrafts(),'');
							unset($draftsObj);
					}
				}
				else{
					$message= Messages::getMessage(Messages::AP_MESSAGE,array('USERNAME'=>$profileObj->getUSERNAME()));
				}

		return $message;		
	}

	public function toUpdateRB($messageArr,$sender,$receiver)
	{    
		
		foreach ($messageArr as $key => $value) {
			# code...
			if($value['SENDER'] == $sender && $value['RECEIVER'] == $receiver && ($value['MESSAGE']!=''|| $value['MESSAGE'] != NULL) )
				return 0;
		}

		return 1;
	}
}
?>
