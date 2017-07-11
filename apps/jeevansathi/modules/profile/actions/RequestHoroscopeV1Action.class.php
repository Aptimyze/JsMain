<?php

class RequestHoroscopeV1Action extends sfAction{
	public function execute($request) {
                $loggedInProfileObj = LoggedInProfile::getInstance();
                $profileid = $loggedInProfileObj->getPROFILEID();
                
                $profilechecksum = $request->getParameter("profilechecksum");
                $requestedId = JsCommon::getProfileFromChecksum($profilechecksum);
                
                if(!$requestedId || !$profileid){
                        $statusArr = RequestHoroscopeEnum::getErrorByField("PROFILE_NOT_EXISTS");
                        $this->sendResponse($request, $statusArr);
                }
                $msg = array();
                if ($loggedInProfileObj->getSHOW_HOROSCOPE() != "Y") {
                        $Errmsg = RequestHoroscopeEnum::getErrorByField("ADD_YOUR_HOROSCOPE");
                        $msg["ADD_HORO"] = $Errmsg["message"];
                }
                $jprofileObj = new JPROFILE("newjs_masterRep");
                $fields = 'PROFILEID,USERNAME,GENDER';
                $valArray = array("PROFILEID"=>$requestedId,"activatedKey" => "1");
                $resDetails = $jprofileObj->getArray($valArray, "", '', $fields);

                if (!$resDetails) {
                        $statusArr = RequestHoroscopeEnum::getErrorByField("PROFILE_NOT_EXISTS");
                        $this->sendResponse($request, $statusArr);
                }
                $username = $resDetails[0]["USERNAME"];
                if ($resDetails[0]["GENDER"] == $loggedInProfileObj->getGENDER()) {
                        $statusArr = RequestHoroscopeEnum::getErrorByField("SAMEGENDER_ERROR","#USERNAME#",$username);
                        $this->sendResponse($request, $statusArr);
                }
                $filtered = false;
                $isCheckFilter = $this->isCheckFilter($profileid, $requestedId);
                if ($isCheckFilter==true) {
                        $filtered = $this->isFiltered($loggedInProfileObj, $requestedId);
                }
                if ($filtered) {
                        $statusArr = RequestHoroscopeEnum::getErrorByField("FILTERED_ERROR","#USERNAME#",$username);
                        $this->sendResponse($request, $statusArr);
                }
                $selfAstroDetails = $this->selfAstroDetails($loggedInProfileObj);
               
                if($selfAstroDetails['ASTRO_DETAILS'] == 0){
                        $statusArr = RequestHoroscopeEnum::getErrorByField("UPLOAD_HOROSCOPE_DETAILS");
                        $this->sendResponse($request, $statusArr);
                }
                if($selfAstroDetails['COMPATIBILITY_SUBSCRIPTION'] == 0){
                        $Errmsg = RequestHoroscopeEnum::getErrorByField("BUY_ASTRO_SERVICE");
                        $msg["BUY_ASTRO_SERVICE"] = $Errmsg["message"];
                }
                $horoscopeUploaded=$this->horoscopeRequestSent($profileid,$requestedId);
                if($horoscopeUploaded === "E"){
                        $statusArr = RequestHoroscopeEnum::getErrorByField("ALREADY_REQUESTED","#USERNAME#",$username);
                        $this->sendResponse($request, $statusArr);
                }elseif($horoscopeUploaded == true){
                        $statusArr = RequestHoroscopeEnum::getErrorByField("REQUEST_SENT","#USERNAME#",$username);
                        $this->sendResponse($request, $statusArr,$msg);
                }else{
                        $statusArr = RequestHoroscopeEnum::getErrorByField("FAILURE");
                        $this->sendResponse($request, $statusArr);
                }
        }
        private function horoscopeRequestSent($profileid,$requestedId){
                $dt=date("Y-m-d H:i:s");
                $horoscopeObj = new Horoscope();
                $requested = $horoscopeObj->ifHoroscopeRequested(array($profileid), $requestedId);
                if(!empty($requested) && $requested != NULL){
                        return 'E'; // Already requested
                }
                $dbName1 = JsDbSharding::getShardNo($profileid);
		$horoscopeRequestQuery= new NEWJS_HOROSCOPE_REQUEST($dbName1);
                $param = array("PROFILEID"=>$profileid,"PROFILEID_REQUEST_BY"=>$requestedId,"DATE"=>$dt);
                $horoscopeRequestQuery->insertRequest($param);
                
                $dbName = JsDbSharding::getShardNo($requestedId);
                if($dbName1 != $dbName){
                        $horoscopeRequestQuery= new NEWJS_HOROSCOPE_REQUEST($dbName);
                        $horoscopeRequestQuery->insertRequest($param);
                }
                return true;
        }
        private function selfAstroDetails($loggedInProfileObj){
                $profileid = $loggedInProfileObj->getPROFILEID();
                $astroObj = new Horoscope();
                $astroData = $astroObj->getMultipleAstroDetails(array($loggedInProfileObj));
                if(!isset($astroData[$profileid]) || empty($astroData[$profileid])){
                        $astro_details = 0;
                }else{
                        if($astroData[$profileid]['MOON_DEGREES_FULL'] && $astroData[$profileid]['MARS_DEGREES_FULL'] && $astroData[$profileid]['VENUS_DEGREES_FULL'] && $astroData[$profileid]['LAGNA_DEGREES_FULL']){
                                $astro_details=1; //check if the person has filled his ASTRO DETAILS
                        }
                        else{
                                $astro_details=0;//if NO then show him to fill his a button Astro Details and a button which takes him to PAYMENT PAGE
                        }
                }
                
                if(in_array('A',explode(",",$loggedInProfileObj->getSUBSCRIPTION())))//check if the person has taken COMPATIBILITY SUBSCRIPTION or not
                        $compatibility_subscription=1;
                else
                        $compatibility_subscription=0;

                return array("ASTRO_DETAILS"=>$astro_details,"COMPATIBILITY_SUBSCRIPTION"=>$compatibility_subscription);
        }
        private function isFiltered($loggedInProfileObj, $requestedId) {
                $requestedObj = Profile::getInstance("newjs_masterRep",$requestedId);
                $userFilter= UserFilterCheck::getInstance($loggedInProfileObj,$requestedObj);
                $is_filter=$userFilter->getFilteredContact();
                return $is_filter;
        }

        private function isCheckFilter($profileid, $requestedId) {
                $shardNo = JsDbSharding::getShardNo($profileid);
                $dbObj = new newjs_CONTACTS($shardNo);
                $resArray = $dbObj->getContactRecord($profileid, $requestedId);
                $contact_status = $resArray['TYPE'];
                if(empty($resArray) || (($resArray['SENDER'] == $profileid && $contact_status != "A") || ($resArray['SENDER'] == $requestedId && $contact_status!='I' && $contact_status!='A' && $contact_status!='D'))){
                        return true;
                }
                return false;
        }
        private function sendResponse($request,$statusArr,$msgs=array()){
                $respObj = ApiResponseHandler::getInstance();
                $respObj->setHttpArray($statusArr);
                if(!empty($msgs))
                        $respObj->setResponseBody(array("error"=>$msgs));
                else
                    $respObj->setResponseBody(array("error"=>""));    
                $respObj->generateResponse();
                if($request->getParameter('internally'))
                        sfView::NONE;
                die;
        }
}
