<?php
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$mobileNumberArr = array("vibhor"=>"9868673709","manoj"=>"9999216910","nitish"=>"8989931104","ankita"=>"9650879575");
//$mobileNumberArr = array("nitish"=>"8989931104");
$authChecksum = sendLoginRequest();
$urlArray = array(JsConstants::$siteUrl."/api/v1/notification/poll?".$authChecksum);
//$url = JsConstants::$siteUrl."/api/v1/notification/poll?".$authChecksum;

while(1){
    sleep(180);
    foreach($urlArray as $kk => $vv){
        $status1 = sendPresenceRequest($vv);
        if(!array_key_exists("notifications", $status1)){
            foreach($mobileNumberArr as $k=>$v){
                sms($v);
                mail ("vibhor.garg@jeevansathi.com,manoj.rana@naukri.com,nitishpost@gmail.com","Error in notification api","Please check");
                //mail ("nitishpost@gmail.com","Error in notification api","Please check");
            }
        }
    }
}

function sendPresenceRequest($url)
{
//    $url = JsConstants::$presenceServiceUrl."/profile/v1/presence?pfids=9061321";
//    $res = CommonUtility::sendCurlPostRequest($url,'');
    $res = sendCurlPostRequest($url,'',5000,true);
    $res = (array) json_decode($res);
    return $res;
}

function sendLoginRequest()
{
    $url = JsConstants::$siteUrl."/api/v1/api/login?&captcha=0&fromPc=1&rememberme=1&email=vibhor_grg@yahoo.com&password=vibhor1234&remember=1";
    $res = sendCurlPostRequest($url,'','');
    $result = preg_split("/\n/",$res);
    foreach($result as $val) {
        if(strstr($val, 'AUTHCHECKSUM') !== false) {
                $authChecksum = str_replace('; path=/','',str_replace('Set-Cookie: ','',$val));
        }
    }
    return $authChecksum;
}

function sendCurlPostRequest($urlToHit,$postParams,$timeout='',$headerArr="")
{
    if(!$timeout)
        $timeout = 50000;
    $ch = curl_init($urlToHit);
	if($headerArr)
		curl_setopt($ch, CURLOPT_HTTPHEADER, 0);
	else{
        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
        curl_setopt($ch, CURLOPT_HEADER, $header);
    }
    	//curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
	if($postParams)
        curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if($postParams)
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
    curl_setopt($ch,CURLOPT_NOSIGNAL,1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    if(!$headerArr)
    {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerStr = substr($output, 0, $header_size);
        $output = substr($output, $header_size);
    }
    return $output;

}


function sms($mobile)
{
        $t = time();
        $message        = "Mysql Error Count have reached NotificationAPI aftr 3 attempts $t";
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        $date = date("Y-m-d h");
}
?>
