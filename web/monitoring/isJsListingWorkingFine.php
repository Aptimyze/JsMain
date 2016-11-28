<?php
$flag_using_php5 = 1;
$_SERVER["DOCUMENT_ROOT"] = "/var/www/html/web/";
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$mobileNumberArr = array("9910244159","9650879575","9818424749","8989931104","9868673709","9711304800");

$pid = 9061321;
javaService($pid);

function javaService($pid)
{
        global $mobileNumberArr;
        $WebAuthentication = new WebAuthentication;
        $x = $WebAuthentication->setPaymentGatewayAuthchecksum($pid);
        $auth = $x["AUTHCHECKSUM"];
        $url = JsConstants::$chatListingWebServiceUrl["dpp"]."?type=CHATDPP";
        $header = array("JB-Profile-Identifier:".$auth);
        $start_tm=microtime(true);
        $response = CommonUtility::sendCurlPostRequest($url,"","",$header);
        $diff=microtime(true)-$start_tm;
        $data = (Array)json_decode($response);
        $x = $data["header"]->status;
        if($x!=200)
        {
                foreach($mobileNumberArr as $k=>$v)
                {
                        sms($v);
                }
        }
}
function sms($mobile)
{
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached jslisting $date within 5 minutes";
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
}
