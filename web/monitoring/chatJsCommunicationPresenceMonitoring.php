<?php
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$mobileNumberArr = array("9910244159","9650879575","9818424749","8989931104","9810300513","9868673709");
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");
$status = sendPresenceRequest();
if($status!='200')
{
        $status = sendPresenceRequest();
        if($status!=200)
        {
                foreach($mobileNumberArr as $k=>$v)
                {
                        sms($v);
                }
        }
}
$serverUrlArray = array("http://192.168.120.67:8290","http://192.168.120.75:8290");
foreach($serverUrlArray as $k=>$v){
        $status = sendPresenceRequest($v);
        if($status!='200'){
                $status = sendPresenceRequest($v);
                if($status!=200)
                {
                        mail ("lavesh.rawat@gmail.com,pankaj139@gmail.com,nsitankita@gmail.com,nitishpost@gmail.com","Error in presence api @".$v,"Please check");
                }
        }       
}
function sendPresenceRequest($url)
{
        $url = JsConstants::$communicationServiceUrl."/profile/v1/presence?pfids=9061321";
        $res = CommonUtility::sendCurlPostRequest($url,'',10);
        $res = (array) json_decode($res);
        $res = (array) $res["header"];
        $status = $res["status"];
        return $status;
}
function sms($mobile)
{
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached jscommunicationPresence $date within 5 minutes";
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
}
