<?php
class CommunicationHistory
{
	private static $RESULTS_PER_PAGE_APP=30;
	private $loginProfile;
	private $otherProfile;
	private $nextPage;
	private $pageNo;
	public function __construct($loginProfile, $otherProfile)
	{
		$this->loginProfile = $loginProfile;
		$this->otherProfile = $otherProfile;
	}

	public function getNextPage(){
		return $this->nextPage;
	}
	public function getHistory($page)
	{
		$gender        = $this->loginProfile->getGENDER();
		$heshe         = "They";
		$himher        = "them";


		if($page)
		{
			$memObject=JsMemcache::getInstance();
			$CON_HISTORY=$memObject->get('commHistory_'.$this->otherProfile->getPROFILEID().'_'.$this->loginProfile->getPROFILEID());
		}

	if(!$CON_HISTORY || !$page)
	{
		$messagelogObj = new MessageLog();
		$messagelog    = $messagelogObj->getCommunicationHistory($this->loginProfile->getPROFILEID(), $this->otherProfile->getPROFILEID());
		if (!empty($messagelog))
			foreach ($messagelog as $key => $value) {
				if($value[OBSCENE] == 'Y' && !$value['MESSAGE'] && $value['TYPE'] == 'R')
                {
                    unset($messagelog[$key]);
                    continue;
                }
				$ids = $value["ID"];
				if ($value[OBSCENE] == 'N')
					$id_array[] = $ids;
				if (!$previousStatus) {
					$type = "I";
				} //!$previousStatus
				elseif ($value['TYPE'] == $previousStatus) {
					if ($value['TYPE'] == 'I')
						$type = "R";
					elseif ($previousStatus == 'A' || $previousStatus == 'D' || $previousStatus == 'R')
						$type = "M";
				} //$value['TYPE'] == $previousStatus
					elseif ($value['TYPE'] != $previousStatus) {
					if ($value['TYPE'] == 'A' || $value['TYPE'] == 'R')
						$type = "A";
					if ($value['TYPE'] == 'R')
						$type = "M";
					if ($value['TYPE'] == 'C')
						$type = "C";
					if ($value['TYPE'] == 'D')
						$type = "D";
					if ($value["TYPE"] == "E")
						$type = "E";
					if ($value["TYPE"] == "I")
						$type = "I";
				} //$value['TYPE'] != $previousStatus
				if ($value["SENDER"] == $this->loginProfile->getPROFILEID()) {
					$who  = "You";
					$side = "S";
				} //$value["SENDER"] == $logged_pid
				else {
					$who  = "They";
					$side = "R";
				}
				$previousStatus                            = $value['TYPE'];
 				if($message_log[$value['DATE']]){
 					$dtObj = new DateTime($value['DATE']);
 					$dtObj->modify('+1 second');
 					$newDate = $dtObj->format("Y-m-j H:i:s");
 					$messagelog[$key]['DATE']=$newDate;
 					$value = $messagelog[$key];
 				}
				$message_log[$value['DATE']]["type"]  = $type . $side;
				$message_log[$value['DATE']]["who"]     = $who;
				if($value['MESSAGE']){
					if((strpos($value['MESSAGE'],"||")!==false || strpos($value['MESSAGE'],"--")!==false ) && $value['TYPE']=="I")
					{
						$messageArr=explode("||",$value['MESSAGE']);
						$eoiMsgCount = count($messageArr);
						$i=0;
						for($j=0;$j<$eoiMsgCount;$j++)
						{
							$splitmessage = explode("--",$messageArr[$j]);
							if($i==0)
								$eoiMessages=$splitmessage[0];
							else{
								if(!MobileCommon::isApp())
										$eoiMessages.="</br>".$splitmessage[0];
								else
										$eoiMessages.="\n".$splitmessage[0];
							}
							$i++;							
						}
						if($eoiMessages)
							$value['MESSAGE']=$eoiMessages;
						else
							$value['MESSAGE']="";
					}
					$message_log[$value['DATE']]["message"] = $value['MESSAGE'];
				}
				else
					$message_log[$value['DATE']]["message"] = ""; //inserting space to prevent null exception in various channels
			} //$messagelog as $key => $value
			
		$senderDetails["INCOMPLETE"] = $this->loginProfile->getINCOMPLETE();
		$senderDetails["ACTIVATED"]  = $this->loginProfile->getACTIVATED();
		$tempParam                   = $this->temporaryInterestSuccess($senderDetails["INCOMPLETE"], $senderDetails["ACTIVATED"]);
		if ($tempParam) {
			$contactObj  = new ContactsRecords();
			$tempContact = $contactObj->getTempContact($this->loginProfile->getPROFILEID(), $this->otherProfile->getPROFILEID());
			if (!empty($tempContact)) {
				$ids                                            = $tempContact[0]['CONTACTID'];
				$message_log[$tempContact[0]['DATE']]["header"] = "I Sent";
				$profilechecksum                                = createchecksumforsearch($logged_pid);
				if ($tempParam == 'incomplete')
					$message_log[$tempContact[0]['DATE']]["message"] = "You had expressed interest in this profile and the same will be delivered once your profile is complete.";
				else
					$message_log[$tempContact[0]['DATE']]["message"] = "You had expressed interest in this profile and the same will be delivered once your profile goes live";
				$message_log[$tempContact[0]['DATE']]["who"] = "You";
				$message_log[$tempContact[0]['DATE']]["type"] = "TIS";
			} //!empty($tempContact)
		} //$tempParam
		$bookmarkObj = new Bookmarks();
		$bookmarks   = $bookmarkObj->getBookmarkDetails($this->loginProfile->getPROFILEID(), array(
			$this->otherProfile->getPROFILEID()
		));
		if (!empty($bookmarks)) {
			$message_log[$bookmarks[0]['BKDATE']]["who"]     = "You";
			$message_log[$bookmarks[0]['BKDATE']]["type"]  = "S";
			$message_log[$bookmarks[0]['BKDATE']]["message"] = $bookmarks[0]['BKNOTE'];
		}
		$eoiViewedLogObj = new EoiViewLog();
		$viewed          = $eoiViewedLogObj->getEoiViewed($this->otherProfile->getPROFILEID(), $this->loginProfile->getPROFILEID());
		if ($viewed) {
			$message_log[$viewed]["type"]  = "IV";
			$message_log[$viewed]["who"]     = $heshe;
			$message_log[$viewed]["message"] = $heshe . " viewed your Expression of Interest";
		}
		$horoscopeRequestObj = new Horoscope();
		$output              = $horoscopeRequestObj->getHoroscopeCommunication($this->loginProfile->getPROFILEID(), $this->otherProfile->getPROFILEID());
		if (!empty($output))
			foreach ($output as $key => $value) {
				if ($value['PROFILEID_REQUEST_BY'] == $this->loginProfile->getPROFILEID()) {
					$message_log[$value['DATE']]["type"]  = "HR";
					$message_log[$value['DATE']]["who"]     = $heshe;
					//$message_log[$value['DATE']]["message"] = $heshe . " requested you for your Horoscope"
				} else {
					$message_log[$value['DATE']]["type"]  = "HS";
					$message_log[$value['DATE']]["who"]     = "You";
					//$message_log[$value['DATE']]["message"] = "You requested " . $himher . " for Horoscope";
				}
			}
		$photoRequestObj = new PhotoRequest();
		$output          = $photoRequestObj->getPhotoRequestCommunication($this->loginProfile->getPROFILEID(), $this->otherProfile->getPROFILEID());
		if (!empty($output))
			foreach ($output as $key => $value) {
				if ($value['PROFILEID_REQ_BY'] == $this->loginProfile->getPROFILEID()) {
					$message_log[$value['DATE']]["type"]  = "PR";
					$message_log[$value['DATE']]["who"]     = $heshe;
					//$message_log[$value['DATE']]["message"] = $heshe . " requested you for your Photo";
				} else {
					$message_log[$value['DATE']]["type"]  = "PS";
					$message_log[$value['DATE']]["who"]     = "You";
					//$message_log[$value['DATE']]["message"] = "You requested " . $himher . " for Photo";
				}
			}
			
		$dbName = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
		$chatLogObj = new NEWJS_CHAT_LOG($dbName);
		$chatDetailsArr = $chatLogObj->getMessageHistory($this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID());
		//print_r($chatDetailsArr);die;
		if(is_array($chatDetailsArr)){
			foreach($chatDetailsArr as $key=>$val)
			{
				if ($val["SENDER"] == $this->loginProfile->getPROFILEID()) {
						$who  = "You";
						$side = "S";
					} //$value["SENDER"] == $logged_pid
					else {
						$who  = "They";
						$side = "R";
					}
				//	$previousStatus                            = 'A';
				if(array_key_exists($val['DATE'],$message_log)){
					if($val['MESSAGE']){
						if(!MobileCommon::isApp())
								$message_log[$val['DATE']]["message"] = $message_log[$val['DATE']]["message"]."</br> ".$val['MESSAGE'];
						else
								$message_log[$val['DATE']]["message"] = $message_log[$val['DATE']]["message"]."\n".$val['MESSAGE'];

					}
					else
						$message_log[$val['DATE']]["message"] = ""; //inserting space to prevent null exception in various channels
				}
				else
				{
					$message_log[$val['DATE']]["type"]  = 'O'. $side;
					$message_log[$val['DATE']]["who"]     = $who;
					if($val['MESSAGE'])
						$message_log[$val['DATE']]["message"] = $val['MESSAGE'];
					else
						$message_log[$val['DATE']]["message"] = ""; //inserting space to prevent null exception in various channels
				}
					
			}
		}	
		if (is_array($message_log))
			krsort($message_log);
		//print_r($message_log);die;
		$start = 0;
		if (is_array($message_log))
			foreach ($message_log as $key => $val) {
				$date_time                   = explode(" ", $key);
				$date                        = explode("-", $date_time[0]);
				$time                        = explode(":", $date_time[1]);
				$format_time                 = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
				$time                        = date("D", $format_time) . ", " . date("d", $format_time) . " " . date("F", $format_time) . ", " . date("y", $format_time);
				$CON_HISTORY[$start]         = $val;
				$CON_HISTORY[$start]["time"] = $key;
				$start++;
			} else {
			return false;
		}
		//print_r($CON_HISTORY);die;
		$CON_HISTORY = array_reverse($CON_HISTORY);
		if($page && (count($CON_HISTORY)>self::$RESULTS_PER_PAGE_APP))
			$memObject->set('commHistory_'.$this->otherProfile->getPROFILEID().'_'.$this->loginProfile->getPROFILEID(),$CON_HISTORY);
	}

//// trimming result if page asked for API

		if($page)
		{
		    if(MobileCommon::IsApp()!="A")
			{
				$CON_HISTORY = array_reverse($CON_HISTORY);
			}
			$this->pageNo=$page;
			$offset=(intval($page)-1)*self::$RESULTS_PER_PAGE_APP;
			$limit=self::$RESULTS_PER_PAGE_APP;
			if(count($CON_HISTORY)>$page*self::$RESULTS_PER_PAGE_APP)
				$this->nextPage='true';
			else 
				$this->nextPage='false';
	
			$CON_HISTORY = array_slice($CON_HISTORY, $offset,$limit);
			if(MobileCommon::IsApp()=="I")
			{
				$CON_HISTORY = array_reverse($CON_HISTORY);
			}
		}
		else $this->nextPage="";
//print_r($CON_HISTORY);die;
/////////////////////////////////////


		return $CON_HISTORY;
	}
	public function temporaryInterestSuccess($incomplete, $activated)
	{
		$success = false;
		if ($incomplete == "Y")
			$success = "incomplete";
		elseif ($activated == "N" || $activated == "U" || $activated == "P")
			$success = "underScreened";
		return $success;
	}
	
	
	public function getResultSetApi($history,$myGender='',$otherGender='')
	{

		$count = 0;
		if($otherGender)
		{
			$hisher = "their";
			$himher        = "them";
		}
		else
		{
			$hisher = $this->loginProfile->getGENDER() == 'F' ? 'his' : 'her';
			$himher        = $this->loginProfile->getGENDER() == 'F' ? "him" : "her";
		}
		foreach ($history as $key=>$value)
		{
			switch ($value["type"]){
				case "S":
					$result[$count]["header"] = $value["who"]." added ".$himher." to shortlist";
					$result[$count]["message"] = $value["who"]." added ".$himher." to shortlist";
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				
				case "IR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." expressed interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." expressed interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "RR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." sent a reminder";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." sent a reminder";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "AR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." accepted your interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." accepted your interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "DR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." declined your interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." declined your interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$count++;
					break;
				case "CR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." cancelled $hisher interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] =$value["message"];// $value["who"]." cancelled $hisher interest";
						$result[$count]["header"] =$value["who"]." cancelled $hisher interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
                                case "ER":
                                        
                                        if(!$value["message"]){
											$result[$count]["message"]=$value["who"]." cancelled $hisher interest";
											$result[$count]["header"] = " ";
										}
										else{
											$result[$count]["message"] = $value["message"];//$value["who"]." cancelled $hisher interest";
											$result[$count]["header"] = $value["who"]." cancelled $hisher interest";
										}
                                        $result[$count]["time"] = $value["time"];
                                        $result[$count]["ismine"] = $value["who"]=="You"?true:false;
                                        $result[$count]["button"] = null;
                                        $count++;
                                        break;
				case "HR":
					$result[$count]["header"] = $value["who"]." requested horoscope";
					$result[$count]["message"] = $value["who"]." have requested for your horoscope. To take it further, Upload your horoscope.";
					$result[$count]["button"]  = array(
						"label" => "Upload",
						"action" => "HOROSCOPE_UPLOAD",
						"value" => null
					);
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$count++;
					break;
				case "PR":
					$result[$count]["header"] = $value["who"]." requested photo";
					$result[$count]["message"] = $value["who"]." have requested for your photo. To take it further, Upload a photo.";
					$result[$count]["button"]  = array(
						"label" => "Upload",
						"action" => "PHOTO_UPLOAD",
						"value" => null
					);$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$count++;
					break;
				case "RS":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." sent a reminder";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." sent a reminder";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "IS":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." expressed interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." expressed interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "TIS":
                                        
                                        if(!$value["message"]){
											$result[$count]["message"]=$value["who"]." expressed interest";
											$result[$count]["header"] = " ";
										}
										else{
											$result[$count]["message"] = $value["message"];
											$result[$count]["header"] = $value["who"]." expressed interest";
										}
                                        $result[$count]["time"] = $value["time"];
                                        $result[$count]["ismine"] = $value["who"]=="You"?true:false;
                                        $result[$count]["button"] = null;
                                        $count++;
                                        break;
				case "AS":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." accepted $hisher interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." accepted $hisher interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "DS":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." declined $hisher interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." declined $hisher interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "CS":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." cancelled interest";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];//$value["who"]." cancelled interest";
						$result[$count]["header"] = $value["who"]." cancelled interest";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
                                case "ES":
                                        
                                        if(!$value["message"]){
											$result[$count]["message"]=$value["who"]." cancelled interest";
											$result[$count]["header"] = " ";
										}
										else{
											$result[$count]["message"] =$value["message"];// $value["who"]." cancelled interest";
											$result[$count]["header"] = $value["who"]." cancelled interest";
										}
                                        $result[$count]["time"] = $value["time"];
                                        $result[$count]["ismine"] = $value["who"]=="You"?true:false;
                                        $result[$count]["button"] = null;
                                        $count++;
                                        break;
				case "HS":
					$result[$count]["header"] = $value["who"]." requested for $hisher horoscope";
					$result[$count]["message"] = $value["who"]." requested for $hisher horoscope";
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "PS":
					$result[$count]["header"] = $value["who"]." requested for $hisher photo";
					$result[$count]["message"] = $value["who"]." requested for $hisher photo";
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "MR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." sent a Message";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." sent a Message";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "MS":
					
					if(!$value["message"]){
						$result[$count]["message"]="You sent a Message";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = "You sent a Message";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "OR":
					
					if(!$value["message"]){
						$result[$count]["message"]=$value["who"]." sent a Message";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = $value["who"]." sent a Message";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "OS":
					
					if(!$value["message"]){
						$result[$count]["message"]="You sent a Message";
						$result[$count]["header"] = " ";
					}
					else{
						$result[$count]["message"] = $value["message"];
						$result[$count]["header"] = "You sent a Message";
					}
					$result[$count]["time"] = $value["time"];
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "IV":
                                       
                                       if(!$value["message"]){
											$result[$count]["message"]=$value['who']." viewed your interest";
											$result[$count]["header"] = " ";
										}
										else{
											$result[$count]["message"] = $value['message'];
											$result[$count]["header"] = $value['who']." viewed your interest";
										}
                                       $result[$count]["time"] = $value["time"];
                                       $result[$count]["ismine"] = $value["who"]=="You"?true:false;
                                       $result[$count]["button"] = null;
                                       $count++;
                                       break;
				default:
					break;
			}
            //Decorate the timeStamp 
            $result[$count-1]["time"] = date("Y-m-d",strtotime($result[$count-1]["time"]));
		    $result[$count-1]["time"]= strtotime($result[$count-1]["time"]);
    		 $result[$count-1]["time"] = date('d M Y', $result[$count-1]["time"]);
            //$result[$count-1]["time"] =  date("jS F, Y", strtotime($count-1]["time"])); 
           
		}
		return $result;
	}
}
