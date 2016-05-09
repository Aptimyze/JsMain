<?php

class SendMessage
{
var $chunksOf = 500;
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
                $smsVendorObj->send($receiverXmlData,$messageType);

	}
	catch ( messageGatewayException $ex ) {
		throw $ex;
	}

	}

	function wrapperSendSms($fileId, $fileName, $messageType)
	{
		$messageId = self::insertMessageDetail($fileId);
		$fileName['name'] = 'sms_'.$messageId.'.csv';
		$destination = $fileName['name'];
		self::uploadFile($fileName);
		$mobData = self::getCSV($destination);
		$final = array_chunk($mobData,$this->chunksOf);
		$count = count($mobData);
		$availableChunks = count($final);
		for($i=0;$i<$availableChunks;$i++)
		{
			$xmlData = self::getCsvData($final[$i]);
			self::sendSMS($xmlData,$messageType);
		}
		self::updateUploadedFileDetail($messageId, $destination, $count);
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
	
	function checkDuplicateMobileNumbers($mobileArray)
	{
		$uniqueMobileArr = array_unique($mobileArray);
		return $uniqueMobileArr;
	}

	function insertInMessageLogTable($messageId, $from, $to, $profileId, $message, $limit)
	{
		$sql = "INSERT INTO SMS_LOG(ID, SMSID, PHONE_MOB, FROM, PROFILEID, LIMIT, MESSAGE) VALUES ('', '$messageId', '$to', '$from', '$profileId', '$message')";
		mysql_query($sql);
		return true;
	}
	
	function insertFileDetail($query)
	{
		$today = date('Y-m-d');
		$query = addslashes($query);
		$sql = "INSERT INTO SMS_FILE_DETAIL(FILEID, QUERY, ADD_DATE) VALUES ('', '$query', '$today')";
		$res = mysql_query($sql) or die(mysql_error());
		$fileId = mysql_insert_id();
		return $fileId;
	}

	function updateFileDetail($fileId, $fileName, $count)
	{
                $sql = "UPDATE SMS_FILE_DETAIL SET FILENAME = '$fileName', RESULT_COUNT = '$count' WHERE FILEID='$fileId'";
                mysql_query($sql);
                return true;
	}

	function updateCompleted($fileId)
	{
                $sql = "UPDATE SMS_FILE_DETAIL SET COMPLETED = 'Y' WHERE FILEID='$fileId'";
                mysql_query($sql);
                return true;
	}
	
	function updateUploadedFileDetail($messageId, $fileName, $sentCount)
	{
                $sql = "UPDATE SMS_DETAIL SET UPLOADED_FILE = '$fileName', SENT_COUNT = '$sentCount' WHERE SMSID='$messageId'";
                mysql_query($sql);
                return true;
	}

	function updateMessageDetailInTable($messageId, $message, $from, $limit, $title, $sentCount, $uploadedFile)
	{
		$today = date('Y-m-d');
                $sql = "UPDATE SMS_DETAIL SET MESSAGE = '$message', `FROM` = '$from', `LIMIT` = '$limit', TITLE = '$title', UPLOADED_FILE = '$uploadedFile', SENT='Y', SENT_COUNT = '$sentCount', SENT_DATE='$today' WHERE SMSID='$messageId'";
                mysql_query($sql) or die(mysql_error());
                return true;
	}

	function updateMessageTable($messageId, $profileId)
	{
		$sql = "UPDATE SMS_LOG SET SENT = 'Y' WHERE SMSID='$messageId' AND PROFILEID = '$profileId'";
		mysql_query($sql);
		return true;
	}

	function insertMessageDetail($fileId)
	{
                $today = date('Y-m-d');
                $query = addslashes($query);
                $sql = "INSERT INTO SMS_DETAIL(SMSID, FILEID, ADD_DATE, SENT_DATE) VALUES ('', '$fileId', '$today', '$today')";
                $res = mysql_query($sql) or die(mysql_error());
                $messageId = mysql_insert_id();
                return $messageId;		
	}

	//function insertResentMessage($messageId, $message, $from, $limit, $title, $sentCount)

	function createCSV($dataArray, $fileName, $filePath)
	{
		$message = "";
		$data[] = 'ProfileId,Mobile Number,Message';
                foreach($dataArray as $key=>$val)
                {
                        $data[] .= $key.','.$val.','.$message;
                }
		$fp = fopen($filePath.'/'.$fileName,"w");
		if($fp)
		{
			foreach ($data as $line) 
			{
				fputcsv($fp, preg_split('/,/', $line));
			}
			fclose($fp);
			return true;
		}
		else
			return false;
	}

	function getCSV($fileName)
	{
		$handle = fopen(JsConstants::$alertDocRoot.'/msmjs/finalCSV/'.$fileName, "r");
		$fileError = self::getFileError($handle,$fileName,JsConstants::$alertDocRoot.'/msmjs/finalCSV');
		if ($fileError)
			die($fileError);
		$i = 0;
                while (($result = fgetcsv($handle, "", ",")) !== FALSE)
                {
			if($i!=0)
			{
				$j=$i-1;
				$mobData[$j] = $result;
				if($i==1)
					$messageError = self::getMessageError($mobData[$j][2], $i+1);
				if($mobData[$j][2])
				{
					$message = $mobData[$j][2];
					$messageContent = self::validateMessage($message);
					$messageError = self::getMessageError($message, $i+1);
				}
				if($messageError)
					die($messageError);
				$mobData[$j][2] = $messageContent;
				//$final[$mobData[$j][1]]['profileId'] = $mobData[$j][0];
				$final[$mobData[$j][1]]['message'] = $mobData[$j][2];
				$final[$mobData[$j][1]]['number'] = $mobData[$j][1];
				$final[$mobData[$j][1]]['uniqueId'] = $j;
			}
			$i++;
		}
		fclose($handle);
		return $final;
	}


	function getCsvData($mobData)
	{
		$xmldata = "";
		foreach($mobData as $key=>$val)
		{
			$to = $val['number'];
			$from = self::getFromMobile($to);
			$xmldata = $xmldata . self::generateReceiverXmlData($val['uniqueId'], $val['message'], $from, $to);
		}
		return $xmldata;
	}

	/*function generateReceiverXmlData($id, $message, $from, $to)
	{
		$xmldata = <<<XML
<SMS  UDH="0" CODING="1" TEXT="$message" PROPERTY="0" ID="$id">
<ADDRESS FROM="$from" TO="$to" SEQ="1" TAG="sms" />
</SMS>
XML;
		return $xmldata;
	}*/

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

	function getFileError($handle,$fileName="",$location="")
	{
		$error = false;
		if(!$handle)
			$error = "ERROR: Uploaded file ".$fileName." is not present at location ".$location.". Please go back and try to upload the file again.";
		return $error;
	}

	function getMessageError($message, $rowNum)
	{
		$error = false;
		if($message)
		{
			if(strlen($message) > 160)
				$error = "ERROR: Length of the message - '".$message."' is exceeding from 160 in the uploaded file. Please go back and upload the file again.";
		}
		else
			$error = "ERROR: Please enter the message at row number $rowNum in the uploaded file. Please go back and upload the file again.";
		return $error;
	}

	function validateMobilePhone($mobile)
	{
		$mobile = self::getMobileCorrectFormat($mobile);
		if(self::checkMobilePhone($mobile))
		{
			if(self::ifValidNumber($mobile))
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
			if(substr($mobile,2,1)=='9')
				return $mobile;
			else
				return false;
		}
	}
	function checkMobilePhone($phone)     // returns 1 if phone no. is not valid
	{
		if( trim($phone) =='')
			return false;
		elseif (!preg_match("/^[+]?[0-9]+$/", $phone))
			return false;
		else
			return $phone;
	}

	function getFileDetail($fileId)
	{
		$sql = "SELECT * FROM SMS_FILE_DETAIL WHERE FILEID = '$fileId'";
		$res = mysql_query($sql);
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			$file[$i]['fileId'] = $row['FILEID'];
			$file[$i]['count'] = $row['RESULT_COUNT'];
			$file[$i]['addDate'] = $row['ADD_DATE'];
			$file[$i]['fileName'] = $row['FILENAME'];
			$file[$i]['sql'] = $row['QUERY'];
			$i++;
		}
		return $file;
	}

	function getSmsDetail($fileId)
	{
                $sql = "SELECT * FROM SMS_DETAIL WHERE FILEID = '$fileId'";
                $res = mysql_query($sql);
                $i = 0;
                while($row = mysql_fetch_array($res))
                {
                        $sms[$i]['messageId'] = $row['SMSID'];
                        $sms[$i]['fileId'] = $row['FILEID'];
                        $sms[$i]['addDate'] = $row['ADD_DATE'];
                        $sms[$i]['sentDate'] = $row['SENT_DATE'];
                        $sms[$i]['from'] = $row['FROM'];
                        $sms[$i]['message'] = $row['MESSAGE'];
                        $sms[$i]['sentCount'] = $row['SENT_COUNT'];
                        $sms[$i]['uploadedFile'] = $row['UPLOADED_FILE'];
                        $i++;
                }
                return $sms;
	}

	function getURL()
	{
		return "http://api.myvaluefirst.com/psms/servlet/psms.Eservice2";
	}

        public function serveFile($filePath, $fileName = '')
        {//before calling this function check user access and existance of file at $filePath.
                // $fileName: the file name user will get at his end.
                // $filePath: the actual path of the file on server.
                // $serveType: has two values 'attachment' and 'inline'.
		$file = $filePath.'/'.$fileName;
                header("Content-type: text/csv");
		header('Content-Disposition: attachment; filename="'.$fileName.'";');
                ob_clean();
                flush();
                return readfile("$file");
                flush();
                return true;
        }

	function uploadFile($fileArr)
	{
		$target_path = JsConstants::$alertDocRoot.'/msmjs/finalCSV';
		$target_path = $target_path.'/'.$fileArr['name']; 
		if(move_uploaded_file($fileArr['tmp_name'], $target_path)) 
			return true;
		else
			return false;
	}

	function copyFileToFinalCsvPath($source, $destination)
	{
		$sourceFile = JsConstants::$alertDocRoot."/msmjs/tempCSV/".$source;
		$targetFile = JsConstants::$alertDocRoot."/msmjs/fianlCSV/".$destination;
		return copy($sourceFile,$targetFile);
	}

	function getFromMobile($mobile)
	{
		if(substr($mobile,2,4)=='9877')
                        $from = '9911328109';  
                else
                        $from = 'Jeevan';

                return $from;
	}
}
?>
