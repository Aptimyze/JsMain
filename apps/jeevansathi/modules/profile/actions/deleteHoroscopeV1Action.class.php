<?php

/*
 * API created to delete entries from astro details
 * 
 */
class deleteHoroscopeV1Action extends sfAction{
    
    public function execute($request){
        $profileid = CommonFunction::getProfileFromChecksum($request->getParameter("profilechecksum"));
        $apiResponseHandlerObj=ApiResponseHandler::getInstance();
        if($profileid){
                //DELETE CREATED HOROSCOPE
                $deleteAstroDetailsObj = ProfileAstro::getInstance();
                $deleteAstroDetailsObj->deleteEntry($profileid);
                //DELETE TIME ,PLACE OF BIRTH
                $deleteTimePlaceOfBirthObj = new JPROFILE();
                $fieldsArr = array('BTIME'=>'','COUNTRY_BIRTH'=>'','CITY_BIRTH'=>'');
                $deleteTimePlaceOfBirthObj->edit($fieldsArr,$profileid);
                    $msg["Success"] = "Successfull";
                    $apiResponseHandlerObj->setResponseBody($msg);
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        }
        else{
                    $msg["Error"] = "Profile id is required";
                    $apiResponseHandlerObj->setResponseBody($msg);
                    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }
        $apiResponseHandlerObj->generateResponse();
        die;
    }
}
