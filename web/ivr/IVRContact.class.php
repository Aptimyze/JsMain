<?php
class IVRContact{
	public $phoneNumber;
	public $dialCode;
	public $caller = array();
	public $receiver = array();
	public $patchedCallDetail = array();
	private $phoneOwnerArr = array();
	public $shortCode = 5664441;
	private $dialcodeGenerateTime = "";
	private $dialCodeValidity = 168;//In hours
	private $availableCallCount = 3;
	private $initiated = "I";//Call intiated
	private $patched = "R";//Call patched
	private $invalidPhone = "N";//Receiver number invalid
	private $error = "E";//Receiver number error
	private $errorInDialer = "E";//Error in request status by third party
	private $contactStatus="U"; //contact status of user , default 'U' undefined
	private $dncFlag=false; //false for not checking dnc, true for checking dnc

	function __construct($phoneNumber="", $dialCode=""){
		if($phoneNumber && $dialCode){
		$this->phoneNumber = $phoneNumber;
		$this->dialCode = $dialCode;
		$this->getPhoneOwner();
		}
	}

	private function getPhoneOwner(){
		$profileid = array();
		$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB IN('".$this->phoneNumber."','0".$this->phoneNumber."')";
		$res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_array($res)){
			$profileid[] =$row['PROFILEID'];
		}
		if(!$profileid){
			$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_WITH_STD IN('".$this->phoneNumber."','0".$this->phoneNumber."')";
			$res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($row=mysql_fetch_array($res))
				$profileid[] =$row['PROFILEID'];
		}
		if(!$profileid){
                        $sql = "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE IN('".$this->phoneNumber."','0".$this->phoneNumber."')";
                        $res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        while($row=mysql_fetch_array($res))
	                        $profileid[] =$row['PROFILEID'];
		}

		$this->phoneOwnerArr = $profileid;
		return $profileid;
	}

	private function getDNCDetail(){
		$dnc = array();
		$mobDNC = "";
		$altMobDNC = "";
		$landlineDNC = "";
		$mobiles = "";
		if($this->dncFlag){
			$db_dnc=connect_db();//If dncFlag enable
			if($this->receiver["PHONE_MOB"] || $this->receiver["ALT_MOBILE"] || $this->receiver["PHONE_WITH_STD"]){
				if($this->receiver["PHONE_MOB"]) $mobile[] = $this->receiver["PHONE_MOB"];
				if($this->receiver["ALT_MOBILE"]) $mobile[] = $this->receiver["ALT_MOBILE"];
				if($this->receiver["PHONE_WITH_STD"])$mobile[] = $this->receiver["PHONE_WITH_STD"];
				if ($mobile) $mobiles = implode("','",$mobile);
				$sql="SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN('$mobiles')";
				$res=mysql_query($sql,$db_dnc) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				while($row=mysql_fetch_array($res))
				{       
					if($this->receiver["PHONE_MOB"] && strstr($row["PHONE"],$this->receiver["PHONE_MOB"]))
						$mobDNC = true;
					if($this->receiver["ALT_MOBILE"] && strstr($row["PHONE"],$this->receiver["ALT_MOBILE"]))
						$altMobDNC = true;
					if($this->receiver["PHONE_WITH_STD"] && strstr($row["PHONE"],$this->receiver["PHONE_WITH_STD"]))
						$landlineDNC = true;
				}       
			}
		}
		$dnc["PHONE_MOB"] = $mobDNC;
		$dnc["ALT_MOBILE"] = $altMobDNC;
		$dnc["PHONE_WITH_STD"] = $landlineDNC;
		return $dnc;
	}


	private function getDNCStatus(){
		if($this->dncFlag){
			if($this->getReceiverPhoneNumber()) return false;
			else return true;
		}
		else return false;
		/*if($this->getReceiverLandline()) return false;
		if($this->receiver["PHONE_MOB"] && $this->receiver["ALT_MOBILE"]){
			if($this->receiver["DNC"]["PHONE_MOB"] && $this->receiver["DNC"]["ALT_MOBILE"])
				return true;
		}else{
			if($this->receiver["PHONE_MOB"] && $this->receiver["DNC"]["PHONE_MOB"])
				return true;
			elseif($this->receiver["ALT_MOBILE"] && $this->receiver["DNC"]["ALT_MOBILE"])
				return true;
		}
		return false;*/
	}

	function callerPhonePresent(){
		if($this->caller["PHONE_MOB"] || $this->caller["PHONE_RES"] || $this->caller["ALT_MOBILE"] || $this->caller["PHONE_WITH_STD"])
			return true;
		return false;
	}

	function receiverPhonePresent(){
		if($this->receiver["PHONE_MOB"] || $this->receiver["PHONE_RES"] || $this->receiver["ALT_MOBILE"] || $this->receiver["PHONE_WITH_STD"])
			return true;
		return false;
	}
 
	function getDialerDetail(){
		if($this->dialCode){
			$row = array();
			$sql = "SELECT * FROM DIALCODE_GENERATE WHERE DIALCODE = '$this->dialCode'";
			$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row = mysql_fetch_array($res);
			if($row)
				$this->dialcodeGenerateTime = $row["ADD_TIME"];
			return $row;
		}
	}

	function setCallerReceiverDetail($caller="", $receiver=""){
		$sql ="select PROFILEID,USERNAME,GENDER,INCOMPLETE,ACTIVATED,ISD,PRIVACY,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_MOB,PHONE_RES,STD,SUBSCRIPTION,SHOWPHONE_MOB,SHOWPHONE_RES,PHONE_WITH_STD,PRIVACY,MOBILE_OWNER_NAME,MOBILE_NUMBER_OWNER,PHONE_OWNER_NAME,PHONE_NUMBER_OWNER,PHONE_FLAG from newjs.JPROFILE where PROFILEID in('$caller','$receiver')";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_assoc($result)){
			if($row["PROFILEID"]==$caller)
				$this->caller = $row;
			elseif($row["PROFILEID"]==$receiver)
				$this->receiver = $row;
		}
                //echo $sql ="select ALT_MOBILE, SHOWALT_MOBILE,ALT_MOBILE_OWNER_NAME, ALT_MOBILE_NUMBER_OWNER, CALL_ANONYMOUS from newjs.JPROFILE_CONTACT where PROFILEID in('$caller','$receiver')";
                $sql ="select * from newjs.JPROFILE_CONTACT where PROFILEID in('$caller','$receiver')";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");                
		while($row=mysql_fetch_assoc($result)){
                        if($row["PROFILEID"]==$caller){
                                $this->caller["ALT_MOBILE"] = $row["ALT_MOBILE"];
                                $this->caller["SHOWALT_MOBILE"] = $row["SHOWALT_MOBILE"];
                                $this->caller["ALT_MOBILE_OWNER_NAME"] = $row["ALT_MOBILE_OWNER_NAME"];
                                $this->caller["ALT_MOBILE_NUMBER_OWNER"] = $row["ALT_MOBILE_NUMBER_OWNER"];
			}
                        elseif($row["PROFILEID"]==$receiver){
                                $this->receiver["ALT_MOBILE"] = $row["ALT_MOBILE"];
                                $this->receiver["SHOWALT_MOBILE"] = $row["SHOWALT_MOBILE"];
                                $this->receiver["ALT_MOBILE_OWNER_NAME"] = $row["ALT_MOBILE_OWNER_NAME"];
                                $this->receiver["ALT_MOBILE_NUMBER_OWNER"] = $row["ALT_MOBILE_NUMBER_OWNER"];
			}
                }
		if($this->receiver){
			$this->receiver["DNC"] = $this->getDNCDetail();
		}
	}

	function getReceiverCallAvailability(){
		$startTime = $this->receiver['TIME_TO_CALL_START'];
		$endTime = $this->receiver['TIME_TO_CALL_END'];
		if(!$startTime && !$endTime){
			$startTime = 8;
			$endTime = 20;
		}
		else{
			$startTime = strftime("%H", JSstrToTime($startTime));
			$endTime = strftime("%H", JSstrToTime($endTime));
			if($startTime<=8 && $endTime<=8){
				$startTime = 8;
				$endTime = 9;
			}
			elseif($startTime>=20 && $endTime>=20){
				$startTime = 19;
				$endTime = 20;
			}
			elseif($startTime>=8 && $endTime<=20){
				$startTime = $startTime;
				$endTime = $endTime;
			}
			elseif($startTime<=8 && $endTime>=20){
				$startTime = 8;
				$endTime = 20;
			}
			elseif($startTime>=8 && $endTime>=20){
				$startTime = $startTime;
				$endTime = 20;
			}
			elseif($startTime<=8 && $endTime<=20){
				$startTime = 8;
				$endTime = $endTime;
			}
		}
		$startTime = date("g A",JSstrToTime("$startTime:00"));
		$endTime = date("g A",JSstrToTime("$endTime:00"));
		return array("startTime"=>$startTime, "endTime"=>$endTime);
		/*if($this->receiver['TIME_TO_CALL_START'] || $this->receiver['TIME_TO_CALL_END']){
			$startTime = $this->receiver['TIME_TO_CALL_START']?strftime("%H", JSstrToTime($this->receiver['TIME_TO_CALL_START'])):8;
			$endTime = $this->receiver['TIME_TO_CALL_END']?strftime("%H", JSstrToTime($this->receiver['TIME_TO_CALL_END'])):20;
		}
		if($startTime<=8 && $endTime<=8) return array("startTime"=>"","endTime"=>"");
		elseif($startTime>=20 && $endTime>=20) return array("startTime"=>"", "endTime"=>"");
		if(!$startTime && !$endTime){
			$startTime = '8 AM';
			$endTime = '8 PM';
		}else{
			if($startTime<=8)$startTime="8 AM";
			else $startTime = $this->receiver['TIME_TO_CALL_START'];
			if($endTime>=20)$endTime="8 PM";
			else $endTime=$this->receiver['TIME_TO_CALL_END'];
		}
		return array("startTime"=>$startTime, "endTime"=>$endTime);*/
	}

	private function checkReceiverAvailability(){
		$availableTimeArr = $this->getReceiverCallAvailability();
		$startTime = $availableTimeArr["startTime"];
		$endTime = $availableTimeArr["endTime"];
		$dateTime =$this->getIST();
		$dateTimeArr =explode(" ",$dateTime);
		$dateArr =explode("-",$dateTimeArr[0]);
		$timeArr =explode(":",$dateTimeArr[1]);
		$currentTime = mktime($timeArr[0],$timeArr[1],0,$dateArr[1],$dateArr[2],$dateArr[0]);
		$addTime =12;

                // start time
                if($startTime){
                        $startTimeArr =explode(" ",$startTime);
                        if($startTimeArr[1] =='PM' || $startTimeArr[1] =='pm')
                                $startTime = $addTime+$startTimeArr[0];
                        else
                                $startTime = $startTimeArr[0];
                        $mktimeStart = mktime($startTime,0,0,$dateArr[1],$dateArr[2],$dateArr[0]);
                }
                // end time
                if($endTime){
                        $endTimeArr =explode(" ",$endTime);
                        if($endTimeArr[1] =='PM' || $startTimeArr[1] =='pm')
                                $endTime = $addTime+$endTimeArr[0];
                        else
                                $endTime = $endTimeArr[0];
                        $mktimeEnd = mktime($endTime,0,0,$dateArr[1],$dateArr[2],$dateArr[0]);
                }
		if(($currentTime>=$mktimeStart && $currentTime<=$mktimeEnd) || ($mktimeStart==$mktimeEnd))
			return true;
		return false;
	}

	private function receiverInvalid(){
		if($this->receiver["PHONE_FLAG"]=="I")
			return true;
		return false;
	}

	function getReceiverPhoneNumber(){
		$phone = "";
		if($this->receiver['PHONE_MOB'] || $this->receiver['PHONE_RES'] || $this->receiver['ALT_MOBILE']){
			$phone = $this->getReceiverMob();
			if(!$phone) $phone = $this->getReceiverLandline();
			if(!$phone) $phone = $this->getReceiverAlternativeMob();
		}
		return $phone;
	}

	private function showReceiverPhone(){
		$callnowSetting = $this->getCallNowSetting();
		if($callnowSetting) return true;
		else{
			if(($this->receiver['PHONE_MOB'] && $this->receiver["SHOWPHONE_MOB"]=="Y") || ($this->receiver['PHONE_WITH_STD'] && $this->receiver["SHOWPHONE_RES"]=="Y") || ($this->receiver['ALT_MOBILE'] && $this->receiver["SHOWALT_MOBILE"]=="Y"))
			return true;
		}
		return false;
	}

	private function getReceiverMob(){
		if($this->receiver['PHONE_MOB'] && !$this->receiver['DNC']['PHONE_MOB'])// && $this->receiver["SHOWPHONE_MOB"]=="Y")
			return "0".substr($this->receiver['PHONE_MOB'],-10);
	}

        private function getReceiverAlternativeMob(){
                if($this->receiver['ALT_MOBILE'] && !$this->receiver['DNC']['ALT_MOBILE'])
                        return "0".substr($this->receiver['ALT_MOBILE'],-10);
        }

	private function getReceiverLandline(){
		if($this->receiver['PHONE_WITH_STD'] && !$this->receiver['DNC']['PHONE_WITH_STD'])
			return $this->receiver['PHONE_WITH_STD'];
	}

	public function showReceiverRelationship(){
                $str = "";
                if($this->receiver['PHONE_MOB'] || $this->receiver['PHONE_RES']){
			global $NUMBER_OWNER;
                        if($this->getReceiverMob()){ 
				if($this->receiver["MOBILE_OWNER_NAME"]){
					$str.="to ".$this->receiver["MOBILE_OWNER_NAME"];
					if($this->receiver["MOBILE_NUMBER_OWNER"])
						$str.=" (".$NUMBER_OWNER[$this->receiver["MOBILE_NUMBER_OWNER"]].")";
				}
			}
			elseif($this->getReceiverAlternativeMob()){
                                if($this->receiver["ALT_MOBILE_OWNER_NAME"]){
                                        $str.="to ".$this->receiver["ALT_MOBILE_OWNER_NAME"];
                                        if($this->receiver["ALT_MOBILE_NUMBER_OWNER"])
                                                $str.=" (".$NUMBER_OWNER[$this->receiver["ALT_MOBILE_NUMBER_OWNER"]].")";
                                }
			}
                        elseif($this->getReceiverLandline()){ 
				if($this->receiver["PHONE_OWNER_NAME"]){
					$str.="to ".$this->receiver["PHONE_OWNER_NAME"];
					if($this->receiver["PHONE_NUMBER_OWNER"])
						$str.=" (".$NUMBER_OWNER[$this->receiver["PHONE_NUMBER_OWNER"]].")";
				}
			}
                }
                return $str;
	}

	/*
        private function callerPhoneRegisterdWithDialer(){
		if($this->timeDifference($this->dialcodeGenerateTime)<=$this->dialCodeValidity){
			if(in_array($this->caller['PROFILEID'],$this->phoneOwnerArr))
				return true;
			else return false;
		}
		return true;
        }
	*/

	private function callerPhoneRegisterdWithDialer(){
		if(in_array($this->caller['PROFILEID'],$this->phoneOwnerArr))
			return true;
		return false;
	}

	private function phoneRegistered(){
		if($this->phoneOwnerArr)
			return true;
		return false;
	}

	private function directCallQuota(){
		include_once "../profile/common_functions.inc";
		$subscription = $this->getSubscription();
		if($subscription['receiver']) return 1; 
		if($subscription['caller']){
			$viewedLeft = 0;
			$viewedContactDetail = contacts_left_to_view($this->caller['PROFILEID']);
			$viewedLeft = $viewedContactDetail["ALLOTED"] - $viewedContactDetail["VIEWED"];
			return $viewedLeft;
		}else return true;
	}

	private function getSubscription(){
		$subs = array();
		include_once "../profile/connect_functions.inc";
		$caller = isPaid($this->caller['SUBSCRIPTION']);
		$receiver = isEvalueMember($this->receiver['SUBSCRIPTION']);
		$subs = array("caller"=>$caller,"receiver"=>$receiver);
		return $subs;
	}

	function callNowSubscription(){
		$subscription = $this->getSubscription();
		if($subscription['receiver'] || $subscription["caller"]) 
			return true;
		return false;
	}	

	// function to check profile is blocked/ignored  
	private function isCallerIgnored()
	{
		$COUNT =array();
		$sql="SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='".$this->receiver[PROFILEID]."' AND IGNORED_PROFILEID = '".$this->caller[PROFILEID]."'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow=mysql_fetch_array($result))
		{
			$COUNT[$myrow[0]]=$myrow[0];
		}
		if(count($COUNT) >0)
			return true;
		return false;
	}

	// function returns the call records(call between the caller and receiver)
	function getCallDetail($status="")
	{
		$dataArr =array();
		$sql ="SELECT * from newjs.CALLNOW WHERE CALLER_PID='".$this->caller[PROFILEID]."' AND RECEIVER_PID='".$this->receiver[PROFILEID]."'";
		if($status)
			$sql .=" AND CALL_STATUS='$status' ORDER BY CALLNOWID desc";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row =mysql_fetch_array($result))
		{
			$dataArr[] =$row;
		}
		return $dataArr;
	}

	// function check the indian phone number
	private function isIndianPhoneNumber($isd="")
	{
		if($isd){
		$isd = trim($isd);
		if($isd =='91' || $isd=='+91')
			return true;
		}else return true;
		return false;
	}

        private function isCallerIndianPhoneNumber()
        {
		return $this->isIndianPhoneNumber($this->caller["ISD"]);
        }

        private function isReceiverIndianPhoneNumber()
        {
		return $this->isIndianPhoneNumber($this->receiver["ISD"]);
        }

	private function callerFiltered(){
		include_once "../profile/contact.inc";
		if($this->contactStatus=="U")
		{
			$this->contactStatus=get_contact_status($this->receiver['PROFILEID'],$this->caller['PROFILEID']);
		}
		if(!in_array($this->contactStatus,array("I","A","RA")))
			$filtered = getFilteredContact($this->caller['PROFILEID'],$this->receiver['PROFILEID']);
		if($filtered) return true;
		return false;
	}

	private function getContactStatus(){
		include_once "../profile/contact.inc";
		if($this->contactStatus=="U")
		{
			$this->contactStatus = get_contact_status($this->receiver['PROFILEID'],$this->caller['PROFILEID']);
		}
		return $this->contactStatus;
	}

	function captureCallnowStatus($callFlag="",$requestStatus="")
	{
		$time = $this->getIST();
		if($callFlag == $this->initiated){
			$sql ="INSERT INTO newjs.CALLNOW(`CALLER_PID`,`RECEIVER_PID`,`CALLER_PHONE`,`CALL_DT`,`CALL_STATUS`,`DIALCODE`) value('".$this->caller[PROFILEID]."','".$this->receiver[PROFILEID]."','$this->phoneNumber','$time','$callFlag','$this->dialCode')";
			$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$callID = mysql_insert_id_js();
			if($callID)
				return $callID;
		}elseif($callFlag == $this->errorInDialer){
                        $sql ="INSERT INTO newjs.CALLNOW(`CALLER_PID`,`RECEIVER_PID`,`CALLER_PHONE`,`CALL_DT`,`CALL_STATUS`,`DIALCODE`,`ERROR_CODE`) value('".$this->caller[PROFILEID]."','".$this->receiver[PROFILEID]."','$this->phoneNumber','$time','$callFlag','$this->dialCode','$requestStatus')";
                        $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $callID = mysql_insert_id_js();
                        if($callID)
                                return $callID;
		}
		else{
			$callDetail = $this->getCallDetail($this->initiated);
			$callId = $callDetail[0]['CALLNOWID'];
			$sql ="update newjs.CALLNOW set CALL_STATUS='$callFlag',CALL_DT='$time' WHERE CALL_STATUS='".$this->initiated."' AND CALLNOWID='$callId'";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if($callFlag==$this->patched){ 
				$this->reduceDirectCallCount();
				$this->sendBlockSms($time);
			}
			//elseif($callFlag==$this->invalidPhone) $this->captureInvalidPhone();
			
		}
		return;
	}

	function captureInvalidPhone(){
		echo "Capture invalid phone";
	}

	function reduceDirectCallCount(){
		$sub = $this->getSubscription();
		if($sub["caller"]){
			$sql1 = "SELECT count(*) cnt FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWER='".$this->caller[PROFILEID]."' AND VIEWED='".$this->receiver[PROFILEID]."'";
			$res1 = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row1 = mysql_fetch_array($res1);
			if(!$row1[0])
			{
				$patchedCalls = $this->getCallDetail($this->patched);
				if(count($patchedCalls)){
					$sql="update jsadmin.CONTACTS_ALLOTED set VIEWED=VIEWED+1,LAST_VIEWED=now() where PROFILEID='".$this->caller[PROFILEID]."'";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$sql="insert ignore into jsadmin.VIEW_CONTACTS_LOG (`VIEWER`,`VIEWED`,`DATE`,`SOURCE`) values('".$this->caller[PROFILEID]."','".$this->receiver[PROFILEID]."',now(),'CNW')";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				}
			}
		}
	}

	function sendBlockSms($callDate){
		$callTime = $this->getTimeFormat($callDate,'time');
		$callerMob = $this->caller["PHONE_MOB"]?$this->caller["PHONE_MOB"]:$this->caller["ALT_MOBILE"];
		$receiverMob = $this->receiver["PHONE_MOB"]?$this->receiver["PHONE_MOB"]:$this->receiver["ALT_MOBILE"];
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
		$message = 'You just received a call from '.$this->caller['USERNAME'].' at '.$callTime.'. If you don\'t want to receive more calls from '.$this->caller['USERNAME'].', reply \'BLOCK\' followed by '.$this->caller['USERNAME'].' to 9870803838 within next 24 hours.';
		$xmlData = generateReceiverXmlData($this->receiver["PROFILEID"], $message, '', $receiverMob);
		//send_sms($message,'',$receiverMob,$this->receiver["PROFILEID"]);
		sendSMS($xmlData,"priority");
		$message = 'You just initiated a call to '.$this->receiver['USERNAME'].' at '.$callTime.'. If you don\'t want to contact '.$this->receiver['USERNAME'].' in future, reply \'BLOCK\' followed by '.$this->receiver['USERNAME'].' to 9870803838 within next 24 hours.';
		$xmlData = generateReceiverXmlData($this->caller["PROFILEID"], $message, '', $callerMob);
		sendSMS($xmlData,"priority");
		//send_sms($message,'',$callerMob,$this->caller["PROFILEID"]);
	}

	function verifyContact($IVR_errorCodeArr){
//		include_once "ivr_errorcodes.php";
		if($this->dialCode){
			$dialer = $this->getDialerDetail();
			if($dialer["DIALCODE"]){
				$this->setCallerReceiverDetail($dialer["CALLER"],$dialer["RECEIVER"]);
				if(!$this->receiverPhonePresent()) return $IVR_errorCodeArr['RECEIVER_NO_PHONE'];
				elseif($this->getDNCStatus()) return $IVR_errorCodeArr['RECEIVER_DNC'];
				elseif($this->caller['ACTIVATED']=="D") return $IVR_errorCodeArr['CALLER_DELETED'];
				elseif($this->caller['ACTIVATED']=="H" || $this->caller['ACTIVATED']=="D" || $this->caller['ACTIVATED']=="U" || $this->caller['INCOMPLETE']=="Y") return $IVR_errorCodeArr['CALLER_NOT_AVAILABLE'];
				elseif(!$this->isReceiverIndianPhoneNumber()) return $IVR_errorCodeArr['RECEIVER_NRI'];
				elseif(!$this->showReceiverPhone()) return $IVR_errorCodeArr['RECEIVER_PHONE_HIDDEN'];
				elseif($this->receiver['ACTIVATED']=="H") return $IVR_errorCodeArr['RECEIVER_HIDDEN'];
				elseif($this->receiver['ACTIVATED']=="D") return $IVR_errorCodeArr['RECEIVER_DELETED'];
				elseif($this->receiver['ACTIVATED']=="U") return $IVR_errorCodeArr['RECEIVER_SCREENING'];
				elseif($this->receiver['INCOMPLETE']=="Y") return $IVR_errorCodeArr['RECEIVER_INCOMPLETE'];
				elseif($this->receiverInvalid()) return $IVR_errorCodeArr['RECEIVER_INVALID'];
				elseif($this->callerFiltered()) return $IVR_errorCodeArr['CALLER_FILTERED'];
				elseif(strstr($this->getContactStatus(),"RD")) return $IVR_errorCodeArr['CALLER_DECLINED'];
				elseif($this->isCallerIgnored()) return $IVR_errorCodeArr['CALLER_IGNORED'];
				elseif($this->receiver['GENDER']==$this->caller['GENDER']) return $IVR_errorCodeArr['RECEIVER_SAME_GENDER'];
				elseif(!$this->callNowSubscription()) return $IVR_errorCodeArr['CALLER_UNPAID_MEMBER'];
				elseif($this->directCallQuota() < 1) return $IVR_errorCodeArr['CALLER_DIRECT_CALL_QUOTA_EXPIRED'];
				elseif(!$this->phoneRegistered()) return $IVR_errorCodeArr['CALLER_PHONE_NOT_REGISTERED'];
				elseif(!$this->isCallerIndianPhoneNumber()) return $IVR_errorCodeArr['CALLER_NRI'] ;
				elseif(!$this->checkReceiverAvailability()) return $IVR_errorCodeArr['CALLER_CALLTIME'] ;
				elseif(!$this->callerPhoneRegisterdWithDialer()) return $IVR_errorCodeArr['CALLER_PHONE_NOT_REGISTERED_WITH_DIALER'];
				elseif(count($this->getCallDetail($this->patched))>=$this->availableCallCount) return $IVR_errorCodeArr['CALLER_ALREADY_CALLED'];
				else return 1;
			}				
			else return $IVR_errorCodeArr['ERROR_DIALCODE']; 
		}
		else return $IVR_errorCodeArr['ERROR_DIALCODE']; 
	}

	function JeevansathiVerifyContact($caller, $receiver){
		$error = "";
		if(!$this->caller['PROFILEID'] && !$this->receiver["PROFILEID"])
			$this->setCallerReceiverDetail($caller,$receiver);
		if($this->getDNCStatus()) $error = 'RECEIVER_DNC';
		elseif(!$this->isReceiverIndianPhoneNumber()) $error = 'RECEIVER_NRI' ;
		elseif($this->receiverInvalid()) $error = 'RECEIVER_INVALID';
		elseif($this->callerFiltered()) $error = 'CALLER_FILTERED';
		elseif(strstr($this->getContactStatus(),"RD")) $error = 'CALLER_DECLINED';
		elseif($this->isCallerIgnored()) $error = 'CALLER_IGNORED';
		elseif($this->receiver['GENDER']==$this->caller['GENDER']) $error = 'RECEIVER_SAME_GENDER';
		elseif(!$this->callNowSubscription()) $error = 'CALLER_UNPAID_MEMBER';
		elseif($this->directCallQuota() < 1) $error = 'CALLER_DIRECT_CALL_QUOTA_EXPIRED';
		elseif(!$this->callerPhonePresent()) $error = 'CALLER_NO_PHONE';
		elseif(!$this->isCallerIndianPhoneNumber()) $error = 'CALLER_NRI' ;
		//elseif(!$this->showReceiverPhone()) $error = 'RECEIVER_PHONE_HIDDEN';
		else{
			$callDetail = $this->getCallDetail($this->patched);
			$this->patchedCallDetail=$callDetail;
			if($this->getPatchedCallsCount()>=$this->availableCallCount) $error = 'CALLER_ALREADY_CALLED';
		}
		if(!$error){
			return "SUCCESS";
		}
		return $error;
	}

	function getPatchedCalls(){
		$patchedTime = array();
		foreach ($this->patchedCallDetail as $key=>$val){
			$patchedTime[]=array("date"=>$this->getTimeFormat($val["CALL_DT"]),"time"=>$this->getTimeFormat($val["CALL_DT"],"time"),"caller"=>$val["CALLER_PHONE"],"year"=>$this->getYearFormat($val["CALL_DT"]),"monthtime"=>$this->getTimeMonthFormat($val["CALL_DT"]));
			//$patchedTime[]=array("date"=>$this->getTimeFormat($val["CALL_DT"]),"time"=>$this->getTimeFormat($val["CALL_DT"],"time"),"caller"=>$val["CALLER_PHONE"]);
		}
		return $patchedTime;
	}
	private function getTimeMonthFormat($date){
                $time = JSstrToTime($date);
                return strtolower(date('jS F',$time));
        }
	private function getTimeFormat($date,$timeFormat=""){
		$time = JSstrToTime($date);
		if($timeFormat) return date('h:i A',$time);
		else return date('jS M Y',$time);
	}
	private function getYearFormat($date,$time=""){
                $time = JSstrToTime($date);
                if($time) return date('Y',$time);
                else return date('Y',$time);
        }

	function getPatchedCallsCount(){
		return count($this->patchedCallDetail);
	}

	function getCallNowSetting(){
		include_once "../profile/connect_functions.inc";
		return getCallNowSetting($this->receiver["PROFILEID"]);
	}

	function getDialCode(){
		$today = date("Y-m-d h:i:s");
		$sql = "SELECT * FROM newjs.DIALCODE_GENERATE WHERE CALLER='".$this->caller[PROFILEID]."' AND RECEIVER='".$this->receiver[PROFILEID]."'";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=array();
		while($row=mysql_fetch_array($res)){
			if($this->timeDifference($row['ADD_TIME'])>=$this->dialCodeValidity){
				return $this->editDialCode($row['DIALCODE']);
			}
			return $row['DIALCODE'];
		}
		if(!$row)return $this->generateDialCode();
	}

	private function editDialCode($oldDialCode){
                $time = $this->getIST();
                $newDialCode = 0;
                $sql = "SELECT DIALCODE FROM newjs.DIALCODE_GENERATE WHERE ADD_TIME=(SELECT MAX(ADD_TIME) FROM DIALCODE_GENERATE)";
                $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row = mysql_fetch_array($res);
                $newDialCode = $row["DIALCODE"]+1;
                $fiveDigitDialCode = $this->get5DigitNumber($newDialCode);
		$sql="SELECT DIALCODE FROM newjs.DIALCODE_GENERATE WHERE DIALCODE='$fiveDigitDialCode'";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row=mysql_fetch_array($res))
			return 0;
		$sql = "UPDATE newjs.DIALCODE_GENERATE SET DIALCODE='$fiveDigitDialCode',ADD_TIME='$time' WHERE DIALCODE='$oldDialCode'";
		mysql_query_decide($sql) or ($fiveDigitDialCode=0);
		return $fiveDigitDialCode;
	}

	private function timeDifference($time){
		$diff = time()-JSstrToTime($time);
		$hours = $diff/(60*60);
		return $hours;
	}

	private function generateDialCode(){
		$db = connect_db();
		$time = $this->getIST();
		$oldDialCode = 0;
                $sql = "SELECT MAX(DIALCODE) DIALCODE FROM newjs.DIALCODE_GENERATE WHERE ADD_TIME=(SELECT MAX(ADD_TIME) FROM DIALCODE_GENERATE)";
                $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row = mysql_fetch_array($res);
                $newDialCode = $row["DIALCODE"]+1;
                $fiveDigitDialCode = $this->get5DigitNumber($newDialCode);
                $sql="SELECT DIALCODE FROM newjs.DIALCODE_GENERATE WHERE DIALCODE='$fiveDigitDialCode'";
                $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                if($row=mysql_fetch_array($res))
                        return 0;
		$sql = "INSERT INTO newjs.DIALCODE_GENERATE(`DIALCODE`,`CALLER`,`RECEIVER`,ADD_TIME) values('$fiveDigitDialCode','".$this->caller[PROFILEID]."','".$this->receiver[PROFILEID]."','$time')";
		mysql_query($sql,$db) or ($fiveDigitDialCode=0);
		return $fiveDigitDialCode;
	}

	private function get5DigitNumber($dialCode){
		if(strlen($dialCode)!=5){
			$reminder = $dialCode%100000;
			$dialCode = 10000+$reminder;
		}
		return $dialCode;
	}

	function showActionLink($error,$type,$who,$index){
		
	        
		$link="";
		if($error=="CALLER_UNPAID_MEMBER")
			$link="<a class='blink b' href='$SITE_URL/profile/mem_comparison.php'>Buy premium membership now</a>";
		elseif($error=="CALLER_NRI" || $error=="RECEIVER_NRI" || $error=="CALLER_ALREADY_CALLED"){
			if(isPaid($this->caller["SUBSCRIPTION"]) || isPaid($this->receiver["SUBSCRIPTION"]))
				$show_contacts = true;
			$profilechecksum=md5($this->receiver["PROFILEID"]) ."i". $this->receiver["PROFILEID"];
			if($type==""){
				//$link="<a  class=\"crscall b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi','$show_contacts');}\">Express Interest</a>";
				if($_GET[FROM_VIEW])
					$link = "<a  class=\"blink crscall b\" onclick=\"show_layer('show_express','show_contact','expr_layer','con_layer',1,'show_callnow','callnow_layer')\" style=\"cursor:pointer\">Express Interest</a>";
				else
					$link = "<a  class=\"blink crscall b\" href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum\" >Express Interest</a>";
			}
			elseif($who=="SENDER" && $type=="I"){
				//$link="<a  class=\"crscall b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'reminder','$show_contacts');}\">Send Reminder</a>";
				if($_GET[FROM_VIEW])
					$link = "<a  class=\"blink crscall b\" onclick=\"show_layer('show_express','show_contact','expr_layer','con_layer',1,'show_callnow','callnow_layer')\" style=\"cursor:pointer\">Send Reminder</a>";
				else
					$link = "<a  class=\"blink crscall b\" href=\"$SITE_URL/profile/viewprofile.php?profilechecksum=$profilechecksum\">Send Reminder</a>";
			}
		}
		return $link;
	}

	function getErrorMessage($error,$type,$who,$index){
		switch($error){
			case 'ERROR':                        
				$errorMessage="Sorry. You cannot call the person. Please try later";
				break;
			case 'RECEIVER_DNC':                        
				$errorMessage="You cannot call this uers since this user's number is in Do Not Call registry.";
				break;
			case 'RECEIVER_NRI':       
				$errorMessage="You cannot call this user since  this user does not have an Indian number. You can call only Indian numbers.";
				if($this->showActionLink($error,$type,$who,$index))
					$errorMessage.=" To contact this profile";
				break;
			case 'RECEIVER_INVALID':                        
				$errorMessage="You cannot call this uers since this user's number is invalid.";
				break;
			case 'CALLER_FILTERED':                 
				$errorMessage="You cannot call this user as you do not meet the filter criteria set by this user.";
				break;
			case 'CALLER_DECLINED':              
				$errorMessage="You cannot call this uers since this user has already declined your expression of interest request.";
				break;
			case 'CALLER_IGNORED':         
				$errorMessage="You cannot call this user since the user has declined to receive any further communication from you.";
				break;
			case 'RECEIVER_SAME_GENDER':                  
				$errorMessage="You cannot contact people of same gender";
				break;
			case 'RECEIVER_PHONE_HIDDEN':                  
				$errorMessage="You cannot call this uers since this user does not want to receive any communication";
				break;
			case 'CALLER_UNPAID_MEMBER':          
				$errorMessage="Please become a paid member to contact other members.";
				break;
			case 'CALLER_DIRECT_CALL_QUOTA_EXPIRED':                  
				$errorMessage="You cannot call this member as you have 0 contacts left";
				break;
			case 'CALLER_NO_PHONE':
				$errorMessage="Please enter your mobile number in your profile.";
				break;
			case 'CALLER_ALREADY_CALLED':                 
				$errorMessage="You have already called the user thrice using this service and cannot call this person anymore.";
				if($this->showActionLink($error,$type,$who,$index))
					$errorMessage.=" In case you wish to contact this profile";
				break;
			case 'CALLER_NRI':                   
				$errorMessage="You can try this only from Indian number.";
				if($this->showActionLink($error,$type,$who,$index))
					$errorMessage.=" To contact this profile";
				break;
		}
		return $errorMessage;
	}

	public function getSuccessMessage(){
		$message= "";
		$patchedCallsCount = $this->getPatchedCallsCount();
		if($patchedCallsCount==1 || $patchedCallsCount==2)
			$message = "You have made $patchedCallsCount of $this->availableCallCount calls to this member.";
		if($_GET["FROM_VIEW"] && $message)
			$message.=' Calls Details';
		return $message;
	}

        public function getIST($dateTime='')
        {
                if($dateTime=='')
                        $dateTime =date("Y-m-d H:i:s");
                $sql="SELECT CONVERT_TZ('$dateTime','SYSTEM','right/Asia/Calcutta')";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                if($row=mysql_fetch_array($res))
                        $dateTime=$row[0];
                return $dateTime;
        }

	function getDialcodeLock(){
		$sql = "SELECT GET_LOCK('dialCode',10)";
		$res = mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		if($row[0]==1) return 1;
		return 0;
	}

        function releaseDialcodeLock(){
		$sql="SELECT RELEASE_LOCK('dialCode')";
		mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        }
}

?>
