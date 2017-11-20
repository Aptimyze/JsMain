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
    
    public function getMessageLogContactCount($where, $group = '', $select = '', $skipProfile = '',$considerProfile='')
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
        $count         = $messageLogObj->getMessageLogCount($where, $group, $select, $skipProfile,$considerProfile);
        
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
				$messageForRB = $this->getRBMessage($value['SENDER'],$receiverObj,$profileObj,$value['COUNT']);
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
	public function getMessageListing($loginProfile,$condition,$skipArray='',$inArray='')
	{
		$dbName = JsDbSharding::getShardNo($loginProfile);

		$pid = $loginProfile;
		$memccKey = $pid."_cc_myMessage";
		$profileArray = JsMemcache::getInstance()->get($memccKey);
		if($profileArray && array_key_exists("pageNo", $condition) && $condition["pageNo"]==1)
		{
			//JsMemcache::getInstance()->set($memccKey,'',0);
			JsMemcache::getInstance()->delete($memccKey);
		}
		else
		{
			$messageLogObj = new NEWJS_MESSAGE_LOG($dbName);
			if(InboxEnums::$messageLogInQuery)
				$profileArray = $messageLogObj->getMessageListing($condition,'',$inArray);
			else
				$profileArray = $messageLogObj->getMessageListing($condition,$skipArray);
                        if(!array_key_exists("pageNo", $condition))
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
			$rbData=$this->EOIFromRB($value['SENDER'],$value['RECEIVER']);
			if($value['TYPE']=='I' && $value['MESSAGE'] == NULL && $rbData)
			{ 
                          $receiverObj = new Profile('',$value['RECEIVER']);
                          $profileObj = new Profile('',$value['SENDER']);
			  $message =$this->getRBMessage($value['SENDER'],$receiverObj,$profileObj,$rbData['COUNT']);	
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
	   $rbData = $dbObj->isRBContact($sender,$receiver);
	   if(isset($rbData) && $rbData['MSG_DEL'] == 'Y')
	   {  
	   		return $rbData;
	   }
	   return "";
	}

	public function getRBMessage($sender,$receiverObj,$profileObj,$count=1)
	{

		if($this->isJsDummyMember($sender) || MembershipHandler::isEligibleForRBHandling($profileObj->getPROFILEID()))
				{
					if($receiverObj->getHAVEPHOTO()=="N" || $receiverObj->getHAVEPHOTO()==""){
						if($count<=1)
							$message=Messages::getMessage(Messages::JSExNoPhoMes,array("EMAIL"=>$profileObj->getEMAIL()));
						else
							$message=Messages::getMessage(Messages::JSExReminderNoPhoMes);
						}
					else
					{
					    /* $draftsObj = new ProfileDrafts($profileObj);
					     $message=ProfileDrafts::getMessage($draftsObj->getEoiDrafts(),'');
					     unset($draftsObj); */
					    
					    $message= Messages::getMessage(Messages::AP_MESSAGE_RM,array('USERNAME'=>$profileObj->getUSERNAME(), 'RMNUMBER'=>$this->getRMNumber($profileObj)));
					}
				}
				else{
					$message= Messages::getMessage(Messages::AP_MESSAGE,array('USERNAME'=>$profileObj->getUSERNAME()));
				}

		return $message;		
	}
	
	private function getRMNumber($profileObj){
	    if($profileObj == null)
	        return null;
	        
	        $exclusiveFunctionsObj=new ExclusiveFunctions();
	        $execDetails=$exclusiveFunctionsObj->getRMDetails($profileObj->getPROFILEID());
	        $rmPhone = $execDetails["PHONE"];
	        if($rmPhone){
	            return "+91-".$rmPhone;
	        }
	        return null;
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
