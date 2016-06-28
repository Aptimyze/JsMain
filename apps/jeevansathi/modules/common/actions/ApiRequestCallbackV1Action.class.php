<?php
/**
 * ApiRequestCallback
 * @package    jeevansathi
 * @subpackage api
 * @author     Avneet Singh Bindra
 * @date       24 June 2016
 */
class ApiRequestCallbackV1Action extends sfActions
{ 
    //Member Variables
    public function execute($request)
    {
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData=$request->getAttribute("loginData");
        $iProfileId = isset($loginData['PROFILEID']) ? $loginData['PROFILEID'] : '';
        $userName = " "; 
        // Query options
        $query_options = array("N"=>"Not filled in",
        	"P"=>'Questions or feedback regarding jeevansathi profile',
            "M"=>'Query regarding jeevansathi membership plans');    
        if($iProfileId){
            // Assign defaults if user is logged in
            $userName = $loginData["USERNAME"];
            $phone = $loginData["PHONE_MOB"];
            $email = $loginData["EMAIL"];
        }
        // Base response with pre-filled data for layout
        $responseData = array('top_placeholder'=>'We will call you at the earliest after you submit the request',
        		'phone_text'=>"Your Phone no",
                'phone_autofill'=>$phone,
                'email_text'=>"Your email id",
                'email_autofill'=>$email,
                'query_question'=>"What type of query do you have?",
                'query_options'=>$query_options,
                'submit_placeholder'=>"Submit Request");
        // Request Parameter Holder
        $arrRequest = $request->getParameterHolder()->getAll();
        $internal = $arrRequest['INTERNAL'];
        // Request Processing
        if ($arrRequest['processQuery'] == 1) {
            // Parsing Request Parameters
            $arrValidQuery = array("P","M");
            $email = $arrRequest['email'];
            $phone = $arrRequest['phone'];
            $query = $arrRequest['query_type'];
            $device = $arrRequest['device'];
            $channel = $arrRequest['channel'];
            $callbackSource = $arrRequest['callbackSource'];
            $rcbResponse = $arrRequest['rcbResponse'];
            // assigning respose data with recieved params and returning to sender
            $responseData['phone_autofill'] = $phone;
            $responseData['email_autofill'] = $email;
            $responseData['query_type'] = $query_type;
            $responseData['device'] = $device;
            $responseData['channel'] = $channel;
            $responseData['callbackSource'] = $callbackSource;
            $responseData['rcbResponse'] = $rcbResponse;
            // end assignment
            if (!empty($email) && !empty($phone) && !empty($query) && !empty($device) && !empty($channel) && !empty($callbackSource)) {
                if (!CommonUtility::validatePhoneNo($phone)) { // Validating Phone No
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $responseData = array('status'=>'invalidPhoneNo');
                } elseif (!CommonUtility::validateEmail($email)) { // Validating Email
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $responseData = array('status'=>'invalidEmail');
                } elseif (in_array($query, $arrValidQuery)) { // Validating Query Type
                    if ($query == "P") {
                        //Send Email
                        $to = "services@jeevansathi.com";
                        $from = "info@jeevansathi.com";//To Do Aliase Jeevansathi Support  Reply-to $email
                        $subject = "$email(".$userName.") has requested a callback for assistance with his/her account";
                        $msgBody = "<html><body>Dear Support Team,<br> $email(".$userName.") has requested a callback from the support team for resolution of a service related issue. Please contact at $email,or $phone.<br> Regards<br> Team Jeevansathi</body></html>";
                        SendMail::send_email($to,$msgBody,$subject,$from,"","","","","","","1",$email,"Jeevansathi Support");
                    } 
                    else if ($query == "M") { //Do membership
                        $objExecCallBack = new billing_EXC_CALLBACK;
                        $memHandlerObj = new MembershipHandler();
                        $objExecCallBack->addRecord($iProfileId,$phone,$email,$device,$channel,$callbackSource);
                        unset($objExecCallBack);
                        $from = "webmaster@jeevansathi.com";
                        $to   = "inbound@jeevansathi.com";
                        //Send Email
                        if($iProfileId){
                            $userName   =   $loginData["USERNAME"];
                            $subject    =   "$userName is interested in Membership Plans";
                            $emailSend  =   $memHandlerObj->checkEmailSendForDay($iProfileId, $email);
                            $profileAllotedExecEmail = $memHandlerObj->getAllotedExecEmail($iProfileId);
                            if(!$emailSend && $profileAllotedExecEmail){
                                $to = $profileAllotedExecEmail;
                            }
                        } else {
                            $subject = "Callback Request for Membership Plans";
                            $userName= "Someone";
                        }
                        $msgBody = "<html><body>$userName is interested in knowing more about Membership Plans. Please contact at ".$email." or ".$phone.".</body></html>";
                        SendMail::send_email($to,$msgBody,$subject,$from);
                    }
                    //Update RCB Status if form is submit(optional)
                    if (isset($rcbResponse) && $rcbResponse) {
                      $this->updateRCBResponse($rcbResponse);
                    }
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                    $responseData['status'] = 'success';
                    $responseData['successMsg'] = 'We shall call you at the earliest';
                } else {
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $responseData['status'] = 'invalidQueryType';
                }
            } else {
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                $responseData['status'] = 'missingParameters';
            }
        } else {
            $responseData['status'] = 'ready';
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        }
        // Sending API Response
        $apiResponseHandlerObj->setResponseBody($responseData);
        $apiResponseHandlerObj->generateResponse();
        if($internal == 1){
        	return sfView::NONE;
        } else {
			die;
        }
    }
}