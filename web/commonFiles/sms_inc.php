<?php
/************************************************************************************************************************
*    FILENAME           : sms_inc.php
*    DESCRIPTION        : 1)validation of mobile
			  2)correct formatting of mobile number.
			  3)To send sms.
*    CREATED BY         : Tanu Gupta
***********************************************************************************************************************/

if(!$_SERVER["DOCUMENT_ROOT"])
	$_SERVER["DOCUMENT_ROOT"] = JsConstants::$docRoot;
include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");

// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
function mobile_correct_format($mobile)
//This function convert the given mobile number into proper format of 12 digit mobile no.
{
	$mobile=str_replace(' ','',$mobile);
	$mobile=str_replace('-','',$mobile);

	if( substr($mobile,0,1)=='0' || substr($mobile,0,1)=='+' )	
	{
		if(strlen($mobile)==11)
			$mobile=substr_replace($mobile,'91',0,1);
		elseif(strlen($mobile)==13)
			$mobile=substr($mobile,1,12);
	}
	elseif(strlen($mobile)==10)
		$mobile='91'.$mobile;

	return $mobile;
}

function send_sms($message,$from,$mobile,$profileid,$table='',$encode_message='',$sms_type='',$sms_key='')
//message should be less than 160 characters.
//from is sender mobile number.
//$mobile is receiver mobile no.
{
	$mobile=mobile_correct_format($mobile);

	if(ifValidNumber($mobile))
		$rec_is_correct=1;

        if($encode_message=='Y')
                $message=urlencode($message);
	
	/*if(!$from)
		$from=getFromMobile($mobile);*/

	$checkmobile = smsinc_checkmphone($mobile);
														     
	if($rec_is_correct && !$checkmobile)
	{
		if($message && $mobile && $profileid)
		{
                        if($from=="JSSRVR"){
				$xml_head="%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
				$xml_content.="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22$message%22%20PROPERTY=%220%22%20ID=%22$profileid%22%3E%3CADDRESS%20FROM=%22$from%22%20TO=%22$mobile%22%20SEQ=%22$profileid%22%20TAG=%22%22/%3E%3C/SMS%3E";
				$xml_end="%3C/MESSAGE%3E";
				$xml_code=$xml_head.$xml_content.$xml_end;
				$fd=@fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send","rb");
				if($fd)
				{
					$response = '';
					while (!feof($fd))
					{
						$response.= fread($fd, 4096);
					}
					fclose($fd);
				}
			}
			else{
				include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
				$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
				$xmlData = $smsVendorObj->generateXml($profileid,$mobile,$message);
				$smsVendorObj->send($xmlData,"transaction");
			}
			/*$sql="SET SESSION wait_timeout=500";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$xml_content="";
			$i = 0;

			if($from=="JSSRVR")
			else

			$xml_content.="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22$message%22%20PROPERTY=%220%22%20ID=%22$profileid%22%3E%3CADDRESS%20FROM=%22$from%22%20TO=%22$mobile%22%20SEQ=%22$profileid%22%20TAG=%22%22/%3E%3C/SMS%3E";
															     
			$xml_end="%3C/MESSAGE%3E";
			$xml_code=$xml_head.$xml_content.$xml_end;

			$fd=@fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send","rb");                       

			if($fd)
			{
				$response = '';
				while (!feof($fd))
				{
					$response.= fread($fd, 4096);
				}
				fclose($fd);
				$ts=time();
				$today=date('Y-m-d',$ts);
			}

			//--added by prinka for sms response tracking--
			$messageType="priority";
			$guid = getValues($response,'GUID=',' ID=');
			$xml_decode=urldecode($xml_code);
			$receiver = getValues($xml_decode,'TO=',' ID=');
			insertValues($guid,$receiver,$messageType);
			//--added by prinka for sms response tracking--

			// added for sms sent tracking
			if($sms_key && $sms_type)
				messageSendDetailsTracking($profileid,$sms_type,$sms_key,$message,$mobile);		
			// end - sms sent tracking 

			if(strpos($response,"Expired"))
			{
				$msg=$response;
				$subject="SMS quota full";
				$to="vikas.jayna@jeevansathi.com";
				//send_email($to,$msg,$subject);
				$to="aman.sharma@jeevansathi.com";
				//send_email($to,$msg,$subject);
				return 0;
			}
				

			$ts=time();
			$today=date('Y-m-d H:i:s',$ts);
			if($table)
			{
				if($table=="newjs.SENT_VERIFICATION_SMS")
					$sql="INSERT INTO $table VALUES('','$profileid','$response','$today','$mobile')";
				else
					$sql="INSERT INTO $table VALUES('$profileid','$response','$today')";
					mysql_query_decide($sql) or die($sql.mysql_error_js());
			}
			*/
			if($sms_key && $sms_type)
				messageSendDetailsTracking($profileid,$sms_type,$sms_key,$message,$mobile);		
			return 1;//Valid mobile.
		}
	}
	return 0;//invalid mobile.
}

function messageSendDetailsTracking($profileid,$sms_type,$sms_key,$message,$mobile)
{
	$message =urldecode($message);
        $message =mysql_real_escape_string($message);
	$sql="INSERT INTO newjs.SMS_DETAIL(`PROFILEID`,`SMS_TYPE`,`SMS_KEY`,`MESSAGE`,`ADD_DATE`,`PHONE_MOB`,`SENT`) VALUES('$profileid','$sms_type','$sms_key','$message',now(),'$mobile','Y')";
	mysql_query($sql) or die($sql.mysql_error_js()); 
}

function smsinc_checkmphone($phone)     // returns 1 if phone no. is not valid
{
        $flag=0;
        if( trim($phone) =='')
        {
                $flag=1;
        }

        elseif (!ereg("^[+]?[0-9]+$", $phone))
        {
                $flag=1;
        }

        return $flag;
}

/*************New******************/

/**
 * Send message $message as SMS to the mobile no/nos. in $to, from sender name $from
 *
 * @param string $message
 * @param string $from
 * @param array/string $to
 * @return $log data to encode
 */

function sendSMS($receiverXmlData,$messageType){
	try {

		//If Mobile Number not Passed: do nothing
		if (! $receiverXmlData)
		return 0;
		include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
		$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
		$smsVendorObj->send($receiverXmlData,"transaction");			
		//$messageType = "promotional";
		//$messageType = "promotional";
		/*$credentials = getCredentials($messageType);
		$xmldata = <<<XML
<?xml version="1.0" encoding="ISO-8859-1"?>
<MESSAGE VER="1.2">
<USER USERNAME="$credentials[username]" PASSWORD="$credentials[password]"/>
XML;

		$xmldata = $xmldata . $receiverXmlData;
		$xmldata .= "</MESSAGE>";
		//echo $xmldata;die;
		$data = 'data=' . urlencode ( $xmldata ) . '&action=send';
		$url = getURL();
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$retval = curl_exec ( $ch );
		curl_close ( $ch );
		//echo $log = $retval."\n||X||".urlencode ( $xmldata );

		//--added by prinka for sms response tracking--
		$guid = getValues($retval,'GUID=',' ID=');
		$receiver = getValues($xmldata,'TO=',' ID=');
		insertValues($guid,$receiver,$messageType);
		//--added by prinka for sms response tracking--
		*/
		return $retval;
	}
	catch ( messageGatewayException $ex ) {
		throw $ex;
	}

	}

	function wrapperSendSms($mobData, $messageType)
	{
		$final = array_chunk($mobData,$this->chunksOf);
		$count = count($mobData);
		$availableChunks = count($final);
		for($i=0;$i<$availableChunks;$i++)
		{
			$xmlData = getXmlData($final[$i]);
			sendSMS($xmlData,$messageType);
		}
	}


function updateSmsDetail($smsDetail, $db_master)
{
	if($smsDetail)
	{
		$smsId = "";
		foreach($smsDetail as $key=>$val)
		{
			$smsId = $smsId."'".$val["ID"]."',";
		}
		if($smsId)
		{
			$smsIdComma = substr($smsId,0,-1);
			$sql = "UPDATE SMS_DETAIL SET SENT='Y' WHERE ID IN($smsIdComma)";
			mysql_query($sql, $db_master) or trackSmsError($sql, $db_master, "Sent Instant SMS");
		}
	}
}

	function getCredentials($messageType)
	{
		if($messageType == "priority")
		{
			$credentials["username"] = "naukari";
			$credentials["password"] = "na21s8api";
		}
		elseif($messageType == "scrub")
		{
			$credentials["username"] = "naukriscrub";
			$credentials["password"] = "nauk05scub09";
		}
		elseif($messageType == "promotional")
		{
			$credentials["username"] = "jeevansathi";
			$credentials["password"] = "jsapi1103";
		}
		return $credentials;
	}

	function validateMessage($message)
	{
                $message = htmlentities ( $message, ENT_QUOTES );
                $message = str_replace ( "\n\r", "&#010;", $message );
                $message = str_replace ( "\n", "&#010;", $message );
		return $message;
	}
	
	function getXmlData($mobData)
	{
		global $whichMachine;
		$xmldata = "";
		foreach($mobData as $key=>$val)
		{
			$to = $val['number'];
			if($whichMachine=='test')
			{
				$testProfileArr = array(3738965, 3738968, 3738971, 3738972, 3738973, 3738974, 3648716, 3738981, 3738413);
				if(!in_array($val['uniqueId'], $testProfileArr))
					$to = false;
			}
			if($to)
			{
				$from = getFromMobile($to);
				$xmldata = $xmldata . generateReceiverXmlData($val['uniqueId'], $val['message'], $from, $to);
			}
		}
		return $xmldata;
	}

        function generateReceiverXmlData($uniqueId, $messageTxt, $fromAddress, $destAddress, $scheduleTime=""){
		$fromAddress="Jeevan";
                $messageTxt = htmlspecialchars($messageTxt,ENT_NOQUOTES); //Message text in vendor requested format
                if(strlen($destAddress) == 10) $destAddress = "91".$destAddress; //Mobile format in vendor requested format
                if($scheduleTime){
                $scheduleTime = date("Y/m/d/H/i",JSstrToTime($scheduleTime)); //Schedule time in vendor requested format
                $xmldata = <<<XML
<messageList>
<fromAddress>$fromAddress</fromAddress>
<destAddress>$destAddress</destAddress>
<messageTxt>$messageTxt</messageTxt>
<custref>$uniqueId</custref>
<scheduleTime>$scheduleTime</scheduleTime>
</messageList>
XML;
                }
                else
                $xmldata = <<<XML
<messageList>
<fromAddress>$fromAddress</fromAddress>
<destAddress>$destAddress</destAddress>
<messageTxt>$messageTxt</messageTxt>
<custref>$uniqueId</custref>
</messageList>
XML;
        return $xmldata;
        }

	/*
        function generateReceiverXmlData($id, $message, $from="", $to, $sendTime="")
        {
                $message = htmlspecialchars($message);
                if(!trim($from))
                        $from=getFromMobile($to);
                if($sendTime){
                        $xmldata = <<<XML
<SMS UDH="0" CODING="1" TEXT="$message" SEND_ON="$sendTime" ID="$id">
<ADDRESS FROM="$from" TO="$to" SEQ="1" TAG="sms" />
</SMS>
XML;
                }
                else{
                        $xmldata = <<<XML
<SMS  UDH="0" CODING="1" TEXT="$message" PROPERTY="0" ID="$id">
<ADDRESS FROM="$from" TO="$to" SEQ="1" TAG="sms" />
</SMS>
XML;
                }
                return $xmldata;
        }
	*/

	function validateMobilePhone($mobile)
	{
		$mobile = getMobileCorrectFormat($mobile);
		if(checkMobilePhone($mobile))
		{
			if(ifValidNumber($mobile))
			{
				return $mobile;
			}
			else
				return false;
		}
		else
			return false;
	}

	function getMobileCorrectFormat($mobile)
	//This function convert the given mobile number into proper format of 12 digit mobile no.
	{
		$mobile=str_replace(' ','',$mobile);
		$mobile=str_replace('-','',$mobile);
		if( substr($mobile,0,1)=='0' || substr($mobile,0,1)=='+' )
		{
			if(strlen($mobile)==11)
				$mobile=substr_replace($mobile,'91',0,1);
			elseif(strlen($mobile)==13)
				$mobile=substr($mobile,1,12);
		}
		elseif(strlen($mobile)==10)
			$mobile='91'.$mobile;
		return $mobile;
	}
	function ifValidNumber($mobile)
	{
		if( strlen($mobile)!=12 || !(ctype_digit($mobile)) )
			return false;
		else
		{
			$two_digits=substr($mobile,2,2);

			if(($two_digits>=90 && $two_digits<=99) || ($two_digits>=70 && $two_digits<=79) || ($two_digits>=80 && $two_digits<=81) || ($two_digits>=87 && $two_digits<=89))
				return $mobile;
			else
				return false;
		}
	}
	function checkMobilePhone($phone)     // returns 1 if phone no. is not valid
	{
		if( trim($phone) =='')
			return false;
		elseif (!ereg("^[+]?[0-9]+$", $phone))
			return false;
		elseif(strlen($phone)<10)
			return false;
		else
			return $phone;
	}

	function getURL()
	{
		return "http://api.myvaluefirst.com/psms/servlet/psms.Eservice2";
	}

	function getFromMobile($mobile)
	{
		if(substr($mobile,2,4)=='9877')
			$from = '9911328109';	
		else
			$from = 'Jeevan';

		return $from;
	}

        function getMobValidity($profileArr)
        {
		//$mobile = $profileArr["PHONE_MOB"];
		$profileId = $profileArr["PROFILEID"];
		$profileArr["MOB_VERIFIED"] = false;
		$mobStatus = getPhoneStatus('',$profileId,'M');
		if($mobStatus =='Y')
			$profileArr["MOB_VERIFIED"] ='1';
                return $profileArr;
        }

        function getMobValidityArr($mobileArr)
        {
                //$validPhone = array();
                foreach($mobileArr as $profileid=>$mob)
                {
			$mobile_status =$mob['MOB_STATUS'];
			if($mobile_status=='Y')				
				$mobileArr[$profileid]["MOB_VERIFIED"] ='1';
					
                }
                return $mobileArr;
        }

	function trackSmsError($sql, $db, $type)
	{
		$msg="TYPE:".$type;
		if($db)
			$msg = $msg."\nSQL:".$sql."\nDB:".$db."\nERROR:".mysql_error($db);
		mail("tanu.gupta@jeevansathi.com","SMS Cron: $type",$msg);
	}

	function getIndianTime()
	{
		$orgTZ = date_default_timezone_get();
		date_default_timezone_set("Asia/Calcutta");
		$retval = date("H");
		date_default_timezone_set($orgTZ);
		return $retval;
	}

/**
* This function is used to parse an xml string and returns the values in an array.
* @param response - xml response of the smses that were sent.
* @param $value1 and $value2 - values to be searched in the xml
* @return array $arr - an array of values that were found.
*/

function getValues($response,$value1,$value2)
{
        $len1=strlen($value1);
        $len2=strlen($value2);
        $pos1=0;
        $pos2=0;
        $pos3=0;
        $pos4=0;

        do
        {
                unset($id);
                $pos1 = strpos($response, $value1,$pos2+1);
                $pos2 = strpos($response,'"',$pos1+$len1+1);
                $pos3 = strpos($response, $value2,$pos4+1);
                $pos4 = strpos($response,'"',$pos3+$len2+1);

                if($pos1 && $pos2)
                        $id = substr($response,$pos3+$len2+1,$pos4-$pos3-$len2-1);
                if($id)
                        $arr[$id] = substr($response,$pos1+$len1+1,$pos2-$pos1-$len1-1);
        }
        while($pos1);

        return $arr;
}

/**
* This function is used to insert all the sms details in the table MIS.MOB_VERIFY.
* @param $guidArr - array of GUIDs for whose status is captured correspomding to profileid.
* @param $receiver - array containing mobile no's correspomding to profileid
*/

function insertValues($guidArr,$receiver,$messageType)
{
        $dbM=connect_db();

        foreach($guidArr as $id => $guid)
        {
                $date=date("Y-m-d");
                if($receiver[$id]!='')
                {
                $sql1="INSERT INTO MIS.MOB_VERIFY(GUID,PROFILEID,MOBILE_NO,DATE_OF_ENTRY,MESSAGE_TYPE) VALUES('$guid','$id','$receiver[$id]','$date','$messageType')";
                $res1 = mysql_query($sql1,$dbM) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception(mysql_error($dbM)));
                }
        }
}

/**
* This function is used to check the status of delivery of an sms and correspondingly marks the number as valid/invalid in MIS.MOB_VERIFY
* @param $guid - array of GUIDs for which the status has to be checked.
* @param $dbM - mysql database connection of master db.
* @param - $messageType - type of message sent, (it helps in finding the username and password).
*/
function getResponse($guid,$dbM,$messageType)
{

        $credentials = getCredentials($messageType);
        $user=$credentials[username];
        $password=$credentials[password];

        $status_request1 = <<<XML
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE STATUSREQUEST SYSTEM "http://127.0.0.1/psms/dtd/requeststatusv12.dtd">
<STATUSREQUEST VER="1.2">
<USER USERNAME="$user" PASSWORD="$password"/>
XML;
        foreach($guid as $id => $guid)
        {
                $status_request2= <<<XML
<GUID GUID="$guid">
<STATUS SEQ="1" />
</GUID>
</STATUSREQUEST>
XML;
                $status_request=$status_request1.$status_request2;
                $xml_encode = urlencode($status_request);

                $file=@fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_encode&action=status","rb");
                if($file)
                {
                        $status_resp = '';
                        while (!feof($file))
                        {
                               $status_resp.=fread($file, 4096);
                        }
                fclose($file);

                $status=getValues($status_resp,'ERR=','GUID=');
                $responseArr[$status[$guid]][]=$guid;

                $status=getValues($status_resp,'REASONCODE=','GUID=');
                $reasonCodeArr[$status[$guid]][]=$guid;
		//$aaaaaa[$status[$guid]]=$status_resp;
              }
        }
/*
echo "<br>---1--<br>";
print_r($responseArr);
echo "<br>---2--<br>";
print_r($reasonCodeArr);
echo "<br>---3--<br>";
print_r($aaaaaa);
die;
*/
        $date=date("Y-m-d");

	mysql_ping($dbM);
        foreach($responseArr as $k=>$v)
        {
		if($k=='')
			$invalid='B';
                elseif($k==8448)
                        $invalid='N';
                else
                        $invalid='Y';
		

                $str="'".implode("','",$v)."'";
                $sql2="UPDATE MIS.MOB_VERIFY SET INVALID='$invalid',DATE_OF_VERIF='$date' WHERE GUID IN ($str)";
                $res2 = mysql_query($sql2,$dbM) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception(mysql_error($dbM)));
        }

        foreach($reasonCodeArr as $k=>$v)
        {
                $str="'".implode("','",$v)."'";
                $sql2="UPDATE MIS.MOB_VERIFY SET REASONCODE='$k' WHERE GUID IN ($str)";
                mysql_query($sql2,$dbM) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception(mysql_error($dbM)));
        }

}
?>
