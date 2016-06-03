<?php
$value="";
$value=$_GET['value'];

if($value=='JS'){
	$_SERVER['DOCUMENT_ROOT'] =JsConstants::$docRoot;
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
	ivrPhoneVerification('144111','9999216910','','testing');
}
else
	getIvrResponseTest();

function getIvrResponseTest()
{
        $dataArr        =array();
        $dataUrl        ="/JS/verify.aspx?profileid=144111&msisdn=09999216910&app=JS&vcode=1234";
        $urlHit         ="180.179.85.211";
        $fp             =fsockopen($urlHit,80,$errno,$errstr,4);
        if (!$fp)
        {
                $status ="F";
                $message =$errstr."(".$errno.")";
        }
        else
        {
                $out = "GET /$dataUrl HTTP/1.1\r\n";
                $out .= "Host: 180.179.85.211\r\n";
                $out .= "Connection: Close\r\n\r\n";
                fwrite($fp, $out);
                while (!feof($fp))
                        $fresult.= fgets($fp, 128);
                fclose($fp);
		
		echo "RESPONSE====>".$fresult; 		

		$response = parseHttpResponse($fresult);
		$responseMsg =trim($response);
                if(stristr($responseMsg,'Success')){
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

        }
        $dataArr =array("status"=>$status,"msg"=>$message);
	echo "<br>RESULT ARRAY=====>";print_r($dataArr);	
	//return $dataArr;
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

