<?php
$flag_using_php5 = 1;
$_SERVER["DOCUMENT_ROOT"] = "/var/www/html/web/";
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$mobileNumberArr = array("9910244159","9650879575","9818424749","8989931104","9868673709","9711304800","9873639543");

$pid = 7902447;
$serverUrlArray = array("http://10.10.18.104:8190","http://10.10.18.75:8190","http://10.10.18.72:8190");
foreach($serverUrlArray as $k=>$v){
        $status = javaService($pid,$v);
        if($status!='200'){
                $status = javaService($pid,$v);
               
                if($status!=200)
                {
					$status = javaService($pid,$v);
					 if($status!='200'){						
                        mail ("reshu.rajput@jeevansathi.com,lavesh.rawat@gmail.com,pankaj139@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com","Error in listing api @".$v,"Please check");
						foreach($mobileNumberArr as $n=>$no)
						{
							sms($no);
						}
					}
                }
        }       
}

function javaService($pid,$url)
{
        global $mobileNumberArr;
        /*$WebAuthentication = new WebAuthentication;
        $x = $WebAuthentication->setPaymentGatewayAuthchecksum($pid);
        $auth = $x["AUTHCHECKSUM"];
        $url = JsConstants::$chatListingWebServiceUrl["dpp"]."?type=CHATDPP";
        */
        $url = $url."/listings/v1/discover?type=CHATDPP";
        $header = array("JB-Profile-Identifier:".$pid);
        
        $response = CommonUtility::sendCurlGetRequest($url,"",$header);
       
        $data = (Array)json_decode($response);
        $x = $data["header"]->status;
        return $x;
        
}
function sms($mobile)
{
        $date = date("Y-m-d h");
        $message        = "Mysql Error Count have reached jslisting $date within 5 minutes";
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
}
