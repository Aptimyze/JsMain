<?php
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$mobileNumberArr = array("9910244159","9873639543","8989931104","9810300513","9868673709");
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
function sendPresenceRequest()
{
        $url = JsConstants::$presenceServiceUrl2."/jspresence/v1/presence?pfids=9061321";
        $res = CommonUtility::sendCurlPostRequest($url,'',.1);
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
        CommonUtility::logTechAlertSms($message, $mobile);
}
