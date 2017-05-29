<?php
/************************************************************************************************************************
*    FILENAME           : jsPhoneVerify.php 
*    DESCRIPTION        : This file consist of the function used to send phone-no verification request to third party(Cellcast)
 			: Hit the third-party url along with the passed parameters and get in return the hit status  
***********************************************************************************************************************/

if(!$_SERVER['DOCUMENT_ROOT'])
	$_SERVER['DOCUMENT_ROOT'] =realpath(dirname(__FILE__))."/..";	
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsProfileVerify.class.php");

/*
* Function called for phone No. Verification, called from JS website
* Hit third party URL with the passed parameters in the function
*/
function ivrPhoneVerification($profileid,$phone,$std="",$action="",$vcode="",$isd='')
{
	$dailyCallCap = 50;
	$isd = getIsdInFormat($isd);
	$action =trim($action);
	if($vcode)
		$vcode=trim($vcode);
	if($std){
		$type ="L";
		$NUMBER_VALID=checkLandlineNumber($phone,$std,'','',$isd);
		$phone=landlineformat($phone,$std,'ivr');
	}
	else{
		$type ="M";
		$NUMBER_VALID=checkMobileNumber($phone,'','',$isd);
		$phone=mobileformat($phone,'ivr');
	}
	if($NUMBER_VALID != 'Y')
		return;
	if($phone)
	{	
                if(substr($phone,0,1)=='0')
                        $numb = substr($phone,1);
		else
			$numb = $phone;
	}
	$callsInitiatedToday = getCallsInitiatedToday($numb,$isd);
	if($dailyCallCap<=$callsInitiatedToday)
		return;
        if($numb)
	{
		$number = "%2B".$isd.$numb;
		$resArrPhone		="";
		$phoneStatus		="";
		$phoneMsg		="";
		if($vcode)
			$dataPhone = "ivr_type=codeverification&caller=".$number."&profile_id=".$profileid."&vcode=".$vcode;
		else
			$dataPhone = "ivr_type=profileverification&caller=".$number."&profile_id=".$profileid;
                $resArrPhone	        = getIvrResponse($dataPhone);
                $phoneStatus            = $resArrPhone['status'];
                $phoneMsg               = $resArrPhone['msg'];   
        }
	if($phoneStatus){
		$ivrJsProfileObj = new jsProfileVerify;
		$sentState = $ivrJsProfileObj->phoneNumberSentStatus($profileid,$isd.$numb,$phoneMsg,$phoneStatus,$type,$action);
		if($sentState)
			return true;
	}
	return false;	
}

function getIvrResponse($dataUrl)
{
	$dataArr	=array();
	$dataUrl	="/webapi/jeevansathi/api/outcall?".$dataUrl;
	$urlHit		="int.kapps.in";
	$urlHit		= $urlHit.$dataUrl;
/*
$f = fopen("/tmp/knw.txt","a+");
fwrite($f,$urlHit);
*/

	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $urlHit );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
	if(php_sapi_name() != 'cli'){
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	}
	$response = curl_exec ( $ch );
//fwrite($f,$response);
	curl_close($ch);
                
                
	$responseMsg =trim($response);
	if(stristr($responseMsg,'call_id')){
			$status ="Y";   // request successful
			$message="Success";
	}
	else{
			$status ="I";   // request invalid
			if($responseMsg)
					$message =$responseMsg;
			else
					$message ="Invalid Request";
                }
	$dataArr =array("status"=>$status,"msg"=>$message);
	return $dataArr;
}  
	
function parseHttpResponse($content=null) {
    if (empty($content)) { return false; }
    // split into array, headers and content.
    $hunks = explode("\r\n\r\n",trim($content));
    if (!is_array($hunks) or count($hunks) < 2) {
        return false;
        }
    $header  = $hunks[count($hunks) - 2];
    $body    = $hunks[count($hunks) - 1];
    $headers = explode("\n",$header);
    unset($hunks);
    unset($header);
    if (!validateHttpResponse($headers)) { return false; }
    if (in_array('Transfer-Coding: chunked',$headers)) {
        return trim(unchunkHttpResponse($body));
        } else {
        return trim($body);
        }
    }

function validateHttpResponse($headers=null) {
    if (!is_array($headers) or count($headers) < 1) { return false; }
    switch(trim(strtolower($headers[0]))) {
        case 'http/1.0 100 ok':
        case 'http/1.0 200 ok':
        case 'http/1.1 100 ok':
        case 'http/1.1 200 ok':
            return true;
        break;
        }
    return false;
    }

function unchunkHttpResponse($str=null) {
    if (!is_string($str) or strlen($str) < 1) { return false; }
    $eol = "\r\n";
    $add = strlen($eol);
    $tmp = $str;
    $str = '';
    do {
        $tmp = ltrim($tmp);
        $pos = strpos($tmp, $eol);
        if ($pos === false) { return false; }
        $len = hexdec(substr($tmp,0,$pos));
        if (!is_numeric($len) or $len < 0) { return false; }
        $str .= substr($tmp, ($pos + $add), $len);
        $tmp  = substr($tmp, ($len + $pos + $add));
        $check = trim($tmp);
        } while(!empty($check));
    unset($tmp);
    return $str;
    }

?>
