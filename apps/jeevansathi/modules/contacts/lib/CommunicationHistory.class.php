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
				$message_log[$value['DATE']]["type"]  = $type . $side;
				$message_log[$value['DATE']]["who"]     = $who;
				if($value['MESSAGE'])
					$message_log[$value['DATE']]["message"] = $value['MESSAGE'];
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
		if (is_array($message_log))
			krsort($message_log);
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
		$CON_HISTORY = array_reverse($CON_HISTORY);
		if($page && (count($CON_HISTORY)>self::$RESULTS_PER_PAGE_APP))
			$memObject->set('commHistory_'.$this->otherProfile->getPROFILEID().'_'.$this->loginProfile->getPROFILEID(),$CON_HISTORY);
	}

//// trimming result if page asked for API

		if($page)
		{
		    
			$this->pageNo=$page;
			$offset=(intval($page)-1)*self::$RESULTS_PER_PAGE_APP;
			$limit=self::$RESULTS_PER_PAGE_APP;
			if(count($CON_HISTORY)>$page*self::$RESULTS_PER_PAGE_APP)
				$this->nextPage='true';
			else 
				$this->nextPage='false';
	
			$CON_HISTORY = array_slice($CON_HISTORY, $offset,$limit);
		}
		else $this->nextPage="";

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
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				
				case "IR":
					$result[$count]["header"] = $value["who"]." expressed interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "RR":
					$result[$count]["header"] = $value["who"]." sent a reminder";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "AR":
					$result[$count]["header"] = $value["who"]." accepted your interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "DR":
					$result[$count]["header"] = $value["who"]." declined your interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$count++;
					break;
				case "CR":
					$result[$count]["header"] = $value["who"]." cancelled $hisher interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] =$value["message"];// $value["who"]." cancelled $hisher interest";
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
                                case "ER":
                                        $result[$count]["header"] = $value["who"]." cancelled $hisher interest";
                                        if(!$value["message"])
											$result[$count]["message"]=$result[$count]["header"];
										else
											$result[$count]["message"] = $value["message"];//$value["who"]." cancelled $hisher interest";
                                        $result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
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
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
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
					);$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$count++;
					break;
				case "RS":
					$result[$count]["header"] = $value["who"]." sent a reminder";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "IS":
					$result[$count]["header"] = $value["who"]." expressed interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "TIS":
                                        $result[$count]["header"] = $value["who"]." expressed interest";
                                        if(!$value["message"])
											$result[$count]["message"]=$result[$count]["header"];
										else
											$result[$count]["message"] = $value["message"];
                                        $result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
                                        $result[$count]["ismine"] = $value["who"]=="You"?true:false;
                                        $result[$count]["button"] = null;
                                        $count++;
                                        break;
				case "AS":
					$result[$count]["header"] = $value["who"]." accepted $hisher interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "DS":
					$result[$count]["header"] = $value["who"]." declined $hisher interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "CS":
					$result[$count]["header"] = $value["who"]." cancelled interest";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];//$value["who"]." cancelled interest";
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
                                case "ES":
                                        $result[$count]["header"] = $value["who"]." cancelled interest";
                                        if(!$value["message"])
											$result[$count]["message"]=$result[$count]["header"];
										else
											$result[$count]["message"] =$value["message"];// $value["who"]." cancelled interest";
                                        $result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
                                        $result[$count]["ismine"] = $value["who"]=="You"?true:false;
                                        $result[$count]["button"] = null;
                                        $count++;
                                        break;
				case "HS":
					$result[$count]["header"] = $value["who"]." requested for $hisher horoscope";
					$result[$count]["message"] = $value["who"]." requested for $hisher horoscope";
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "PS":
					$result[$count]["header"] = $value["who"]." requested for $hisher photo";
					$result[$count]["message"] = $value["who"]." requested for $hisher photo";
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "MR":
					$result[$count]["header"] = $value["who"]." sent a Message";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "MS":
					$result[$count]["header"] = "You sent a Message";
					if(!$value["message"])
						$result[$count]["message"]=$result[$count]["header"];
					else
						$result[$count]["message"] = $value["message"];
					$result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
					$result[$count]["ismine"] = $value["who"]=="You"?true:false;
					$result[$count]["button"] = null;
					$count++;
					break;
				case "IV":
                                       $result[$count]["header"] = $value['who']." viewed your interest";
                                       if(!$value["message"])
											$result[$count]["message"]=$result[$count]["header"];
										else
											$result[$count]["message"] = $value['message'];
                                       $result[$count]["time"] = JsCommon::ESItoIST($value["time"]);
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
