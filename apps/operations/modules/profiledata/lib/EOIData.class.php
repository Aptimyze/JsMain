<?php

class EOIData
{
	private $dbObj1;
	private $dbObj2;
	
	function __construct($pid)
	{
		$this->dbName = JsDbSharding::getShardNo($pid);
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
			if($flag[$key1] == $key2)
			{
				
				$eoiArr[$key2][$key1][] = array("SENDER"=>$val['SENDER_USERNAME'],"RECEIVER"=>$val['RECEIVER_USERNAME'],"DATE"=>$val['DATE'],"TYPE"=>$val['TYPE'],"IP"=>$val['IP'],"MESSAGE"=>$val['MESSAGE']);
			}
			else
			{
				if(count($eoiArr[$key1][$key2])>0 && $val['TYPE']=="Contact Initiated")
						$val['TYPE']='EOI Reminder';
						
				$eoiArr[$key1][$key2][] = array("SENDER"=>$val['SENDER_USERNAME'],"RECEIVER"=>$val['RECEIVER_USERNAME'],"DATE"=>$val['DATE'],"TYPE"=>$val['TYPE'],"IP"=>$val['IP'],"MESSAGE"=>$val['MESSAGE']);
				$flag[$key2] = $key1;
			}
			
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
			
		for($i=0;$i<count($result);$i++)
		{
			$ids[]=$result[$i]["ID"];
		}
		if($ids)
		{
			$idstr=implode("','",$ids);	
			$res1 = $this->dbObj1->Messages($idstr);
			$res2 = $this->dbObj2->Messages($idstr);
			
			
			for($i=0;$i<count($result);$i++)
			{
				$result[$i]["MESSAGE"] = $res1[$result[$i][ID]];
				if(!$result[$i]["MESSAGE"])
					$result[$i]["MESSAGE"] = $res2[$result[$i][ID]];
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
		$this->profileDetail =$multipleProfileObj->getResultsBasedOnJprofileFields($pid,'','',"PROFILEID,USERNAME","JPROFILE","newjs_master");
		
	
		foreach($this->profileDetail as $key =>$profileObj)
		{
			$username = $profileObj->getUSERNAME();
			$profileid=$profileObj->getPROFILEID();
			foreach($result as $key=>$val)
			{
				if($val['SENDER']==$profileid)
					$result[$key]['SENDER_USERNAME']=$username;
				if($val['RECEIVER']==$profileid)
					$result[$key]['RECEIVER_USERNAME']=$username;	
			}
			
			
		}
	
	
		return $result;
	}
}

?>
