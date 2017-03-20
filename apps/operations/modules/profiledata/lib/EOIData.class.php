<?php

class EOIData
{
	private $dbObj1;
	private $dbObj2;
	
	function __construct($pid)
	{
		$this->dbName = JsDbSharding::getShardNo($pid,true);
	}
	
	function getEOIData($profileID)
	{
		//echo $this->dbName;die;
		$this->dbObj1 = new NEWJS_MESSAGE_LOG($this->dbName);
		
		$res1 = $this->dbObj1->MessageLogAndDeletedLog($profileID);
		//$res2 = $this->dbObj2->EOIMessageLog($profileID);
		$result = $this->my_merge($res1,$res2);
		$result = $this->message($result);		
		$result = $this->eoiDetails($result);
		foreach($result as $key=>$val)
		{
			$key1 = $val['SENDER'];
			$key2 = $val['RECEIVER'];
			
			if($val['TYPE'] == 'I')
				$val['TYPE'] = "Contact Initiated";
			elseif($val['TYPE'] == 'A')
				$val['TYPE'] = "Contact Accepted";
			elseif($val['TYPE'] == 'D')
				$val['TYPE'] = "Contact Declined";
			elseif($val['TYPE'] == 'C')
				$val['TYPE'] = "Contact Cancelled";	
			elseif($val['TYPE'] == 'R')
				$val['TYPE'] = "Message Sent";
			$val['IP'] = self::inet_ntoa($val['IP']);
                        if($key1 == $profileID){
                                $extraFieldsArray = array('EMAIL'=>$val['RECEIVER_EMAIL'],'PHONE_MOB'=>$val['RECEIVER_PHONE_MOB'],'PHONE_RES'=>$val['RECEIVER_PHONE_RES'],"PHONE_ALT"=>$val['RECEIVER_PHONE_ALT'],'CONTACT'=>$val['RECEIVER_CONTACT']);
                        }else{
                                $extraFieldsArray = array('EMAIL'=>$val['SENDER_EMAIL'],'PHONE_RES'=>$val['SENDER_PHONE_RES'],"PHONE_ALT"=>$val['SENDER_PHONE_ALT'],'CONTACT'=>$val['SENDER_CONTACT']);
                        }
			if($flag[$key1] != $key2)
			{
				if(count($eoiArr[$key1][$key2])>0 && $val['TYPE']=="Contact Initiated")
						$val['TYPE']='EOI Reminder';
                                
				$flag[$key2] = $key1;
			}
                        $dataArray = array("SENDER"=>$val['SENDER_USERNAME'],"RECEIVER"=>$val['RECEIVER_USERNAME'],"DATE"=>$val['DATE'],"TYPE"=>$val['TYPE'],"IP"=>$val['IP'],"MESSAGE"=>$val['MESSAGE']);
                        $eoiArr[$key1][$key2][] = array_merge($dataArray,$extraFieldsArray);
			
		}
		//print_r($eoiArr);die;
		return $eoiArr;
		
		
	}
	public static function inet_ntoa($num)
	{
	    $num = trim($num);
	    if ($num == "0") return "127.0.0.1";
	    if(is_numeric($num))
	    {
	    	return long2ip(-(4294967295 - ($num - 1))); 
	    }	
	   	else
	   		return $num;
	}
	
	
	function message($result)
	{
	
		$this->dbObj1= new NEWJS_MESSAGES($this->dbName);
		$this->dbObj2= new NEWJS_DELETED_MESSAGES($this->dbName);
		$this->dbObj3= new NEWJS_DELETED_MESSAGES_ELIGIBLE_FOR_RET($this->dbName);
    
		for($i=0;$i<count($result);$i++)
		{
			$ids[]=$result[$i]["ID"];
		}
		if($ids)
		{
			$idstr=implode("','",$ids);	
			$res1 = $this->dbObj1->Messages($idstr);
			$res2 = $this->dbObj2->Messages($idstr);
			$res3 = $this->dbObj3->Messages($idstr);
			
			for($i=0;$i<count($result);$i++)
			{
				$result[$i]["MESSAGE"] = $res1[$result[$i][ID]];
				if(!$result[$i]["MESSAGE"])
					$result[$i]["MESSAGE"] = $res2[$result[$i][ID]];
        if(!$result[$i]["MESSAGE"])
					$result[$i]["MESSAGE"] = $res3[$result[$i][ID]];
			}
			
		}
		
		return $result;
	}
	
	function my_merge($array1,$array2)
	{
		$returnArray=@array_merge($array1,$array2);
		if(!$returnArray)
			$returnArray=$array1;
		if(!$returnArray)
			$returnArray=$array2;	
		return $returnArray;
	}
	
	
	function eoiDetails($result)
	{
		if(!$result)
			return;
		for($i=0;$i<count($result);$i++)
		{
			$pidSArr[$i] = $result[$i]['SENDER'];
			$pidRArr[$i] = $result[$i]['RECEIVER'];
			
		}
		$pidArr=array_merge($pidSArr,$pidRArr);
		$pid[PROFILEID] =implode(",",$pidArr);
		
		
		$multipleProfileObj = new ProfileArray();
		$this->profileDetail =$multipleProfileObj->getResultsBasedOnJprofileFields($pid,'','',"PROFILEID,USERNAME,EMAIL,PHONE_RES,PHONE_MOB,CONTACT","JPROFILE","newjs_master");
		$contactNumOb= new ProfileContact('newjs_masterRep');
                $altNumArray=$contactNumOb->getArray(array('PROFILEID'=>implode(",",$pidArr)),'','',"PROFILEID,ALT_MOBILE",1);
                
		foreach($this->profileDetail as $key =>$profileObj)
		{
			$username = $profileObj->getUSERNAME();
			$profileid=$profileObj->getPROFILEID();
			$PHONE_MOB=$profileObj->getPHONE_MOB();
			$EMAIL=$profileObj->getEMAIL();
			if(strpos($EMAIL,"_deleted") !== false)
			{
				$EMAIL = substr($EMAIL,0,strpos($EMAIL,"_deleted"));
			}
			$PHONE_RES=$profileObj->getPHONE_RES();
			$CONTACT=$profileObj->getCONTACT();
			foreach($result as $key=>$val)
			{
				if($val['SENDER']==$profileid){
					$result[$key]['SENDER_USERNAME']=$username;
					$result[$key]['SENDER_PHONE_MOB']=$PHONE_MOB;	
					$result[$key]['SENDER_PHONE_RES']=$PHONE_RES;	
					$result[$key]['SENDER_PHONE_ALT']=$altNumArray[$val['SENDER']]['ALT_MOBILE'];	
					$result[$key]['SENDER_CONTACT']=$CONTACT;
					$result[$key]['SENDER_EMAIL']=$EMAIL;	
                                }
				if($val['RECEIVER']==$profileid){
					$result[$key]['RECEIVER_USERNAME']=$username;	
					$result[$key]['RECEIVER_PHONE_MOB']=$PHONE_MOB;	
					$result[$key]['RECEIVER_PHONE_RES']=$PHONE_RES;	
                                        $result[$key]['RECEIVER_PHONE_ALT']=$altNumArray[$val['RECEIVER']]['ALT_MOBILE'];
					$result[$key]['RECEIVER_CONTACT']=$CONTACT;
					$result[$key]['RECEIVER_EMAIL']=$EMAIL;	
                                }
			}
		}
		return $result;
	}
}

?>
