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
        $callBackResponse = $request->getParameter("rcbResponse");
        $internal = $request->getParameter('INTERNAL');

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

        //This part is used when the rcb Response is "N". In this case, the table gets updated and response is sent.
        if($callBackResponse == "N")
        {
            $this->insertRCBResponse($callBackResponse);
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
            $responseData['status'] = 'success';
            $responseData['successMsg'] = 'Never mind. You still can reach out to us later whenever you want. We will remind you about this after two weeks.';       
            $responseData['rcbResponse'] = $callBackResponse;
            // Sending API Response
            $apiResponseHandlerObj->setResponseBody($responseData);
            $apiResponseHandlerObj->generateResponse();
            if($internal == 1){
                return sfView::NONE;
            } else {
                die;
            }
        }
        $dayDropDown = CommonFunction::getRCBDayDropDown();
        $startTimeDropDown = CommonFunction::getRCBStartTimeDropDown();
        $endTimeDropDown = CommonFunction::getRCBEndTimeDropDown();
        // Base response with pre-filled data for layout
        $responseData = array('title'=>'Request Call Back',
        		'top_placeholder'=>'We will call you at the earliest after you submit the request',
        		'phone_text'=>"Your Phone no",
                'phone_autofill'=>$phone,
                'email_text'=>"Your email id",
                'email_autofill'=>$email,
                'query_question'=>"What type of query do you have?",
                'query_options'=>$query_options,
                'date_text'=>'Date',
                'date_option'=>$dayDropDown,
                'startTime_text'=>'Schedule Time(IST)',
                'startTime_option'=>$startTimeDropDown,
                'submit_placeholder'=>"Submit Request");
        // Request Parameter Holder
        $arrRequest = $request->getParameterHolder()->getAll();
        //$internal = $arrRequest['INTERNAL'];
        // Request Processing
        if ($arrRequest['processQuery'] == 1) {
            // Parsing Request Parameters
            $arrValidQuery = array("P","M");
            $arrValidDevice = array("desktop","mobile_website","Android_app","iOS_app");
            $arrValidChannel = array("JSMS","JSPC","JSAA","JSIA");
            $email = strtolower($arrRequest['email']);
            $phone = $arrRequest['phone'];
            $query = $arrRequest['query_type'];
            $date = $arrRequest['date'];
            $startTime = str_replace("_", ":", $arrRequest['startTime']);
            $endTime = str_replace("_", ":", $arrRequest['endTime']);
            $device = $arrRequest['device'];
            $channel = $arrRequest['channel'];
            $callbackSource = $arrRequest['callbackSource'];
            $rcbResponse = $arrRequest['rcbResponse'];
            $orgTZ = date_default_timezone_get();
            date_default_timezone_set("Asia/Calcutta");
            $currentTime = time();
            $cutoffTimeEnd = strtotime(date("Y-m-d 21:00:00"));
            $cutoffTimeStart = strtotime(date("Y-m-d 09:00:00"));
            if(empty($date) || !isset($date)) {
                if ($currentTime < $cutoffTimeEnd) {
                    $date = date("Y-m-d", time());
                } else {
                    $date = date("Y-m-d", strtotime('+1 day', time()));
                }
            }
            if (date("H", strtotime($currentTime)) >= 20) {
                $date = date("Y-m-d", strtotime('+1 day', time()));
            }
            if(empty($startTime) || !isset($startTime)) {
                if (($cutoffTimeStart < $currentTime) && ($currentTime < $cutoffTimeEnd) || (date("H", strtotime($currentTime)) < 20 && date("H", strtotime($currentTime)) >= 9)) { 
                    $startTime = date("H:i:s", time()+3600);
                } else {
                    $startTime = "09:00:00";
                }
            }
            if(empty($endTime) || !isset($endTime)) {
                $endTime = "21:00:00";
            }
            $responseTime = strtotime($date." ".$startTime);
            date_default_timezone_set($orgTZ);
            // assigning respose data with recieved params and returning to sender
            $responseData['phone_autofill'] = $phone;
            $responseData['email_autofill'] = $email;
            $responseData['query_type'] = $query;
            $responseData['date'] = $date;
            $responseData['startTime'] = $startTime;
            $responseData['endTime'] = $endTime;
            $responseData['device'] = $device;
            $responseData['channel'] = $channel;
            $responseData['callbackSource'] = $callbackSource;
            $responseData['rcbResponse'] = $rcbResponse;
            if (!empty($email) && !empty($phone) && !empty($query) && !empty($device) && !empty($channel) && !empty($callbackSource) && !empty($date) && !empty($startTime) && !empty($endTime)) {
            // end assignment
                if (!CommonUtility::validateEmail($email)) { // Validating Email
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $apiResponseHandlerObj->setResponseMessage("Please enter a valid Email");
                    $responseData['status'] = 'invalidEmail';
                } elseif (!CommonUtility::validatePhoneNo($phone)) { // Validating Phone No
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $apiResponseHandlerObj->setResponseMessage("Please enter a valid Phone No.");
                    $responseData['status'] = 'invalidPhoneNo';
                } elseif (!in_array($device, $arrValidDevice)) { // Validating Email
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $apiResponseHandlerObj->setResponseMessage("Invalid Device selected");
                    $responseData['status'] = 'invalidDevice';
                } elseif (!in_array($channel, $arrValidChannel)) { // Validating Email
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $apiResponseHandlerObj->setResponseMessage("Invalid Channel selected");
                    $responseData['status'] = 'invalidChannel';
                } elseif ($currentTime > $responseTime) { // Validating Time                    
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $apiResponseHandlerObj->setResponseMessage("Please select a valid Date/Time");
                    $responseData['status'] = 'invalidTime';
                } elseif (in_array($query, $arrValidQuery)) { // Validating Query Type
                    if ($query == "P") {
                        //Send Email
                        $to = "services@jeevansathi.com";
                        $from = "info@jeevansathi.com";//To Do Aliase Jeevansathi Support  Reply-to $email
                        $subject = "$email(".$userName.") has requested a callback for assistance with his/her account";
                        $msgBody = "<html><body>Dear Support Team,<br> $email(".$userName.") has requested a callback from the support team for resolution of a service related issue. Please contact at $email,or $phone as requested on $date @ $startTime<br> Regards<br> Team Jeevansathi</body></html>";
                        if (JsConstants::$whichMachine == 'prod') {
                            SendMail::send_email($to,$msgBody,$subject,$from,"","","","","","","1",$email,"Jeevansathi Support");
                        }
                        $objExecCallBack = new billing_EXC_CALLBACK;
                        $objExecCallBack->addRecord($iProfileId,$phone,$email,$device,$channel,$callbackSource,$date,$startTime,$endTime,"JP");
                        unset($objExecCallBack);
                    } 
                    else if ($query == "M") { //Do membership
                        $objExecCallBack = new billing_EXC_CALLBACK;
                        $memHandlerObj = new MembershipHandler();
                        $objExecCallBack->addRecord($iProfileId,$phone,$email,$device,$channel,$callbackSource,$date,$startTime,$endTime);
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
                        $reqTime =date('g:i A',strtotime($startTime));
                        $msgBody = "<html><body>$userName is interested in knowing more about Membership Plans. Please contact at ".$email." or ".$phone." as requested on $date @ $reqTime</body></html>";
                        if (JsConstants::$whichMachine == 'prod') {
                            SendMail::send_email($to,$msgBody,$subject,$from);
                        }
                    }
                    //Update RCB Status if form is submit(optional)
                    if (isset($rcbResponse) && $rcbResponse) {
                      $this->insertRCBResponse($rcbResponse);
                    }
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                    $responseData['status'] = 'success';
                    $responseData['successMsg'] = 'We shall call you at the earliest';
                } else {
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                    $apiResponseHandlerObj->setResponseMessage("Please enter a valid Query Type");
                    $responseData['status'] = 'invalidQueryType';
                }
            } else {
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                $apiResponseHandlerObj->setResponseMessage("Missing Parameters");
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

    /**
     * insertRCBResponse
     * @param type $bStatus
     */
    private function insertRCBResponse($bStatus)
    {
        $loggedInProfileObj = LoggedInProfile::getInstance();
        $rcbObject          = new RequestCallBack($loggedInProfileObj);
        $rcbObject->updateThis($bStatus);
        unset($rcbObject);
    }
}