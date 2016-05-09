<?php
/**
 * page3v1Action Class 
 * Api For Registration Page3(APP3
 * @package Jeevansathi
 * @subpackage Registration
 * @author Kunal Verma
 * @created 22nd July 2015
 */
/**
 * page3v1Action
 * 
 * @module API
 * @author  Kunal Verma
 */

class page3v1Action extends sfAction {
    /** 
     *Executes Registration app page 3
     *Main Function of Action
     * @param $request : SfWebRequest Object
     */
    public function execute($request) {
			
        $apiObj=ApiResponseHandler::getInstance();
        $this->loginData = $request->getAttribute("loginData");
        
        //If not login then Logout Respone
        if(!$this->loginData)
        {
            $apiObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
            $apiObj->generateResponse();
            die;
        }	

        //If not post request or form array is not submitted then POST_PARAM_INVALID response
        $regParams = $request->getParameter('reg');
        if(!$request->isMethod("POST") || !is_array($regParams)){
			$apiObj->setHttpArray(ResponseHandlerConfig::$POST_PARAM_INVALID);
            $apiObj->generateResponse();
            die;
        }
        
        $profileid=$this->loginData[PROFILEID];
        $this->loginProfile = LoggedInProfile::getInstance();
        $this->loginProfile->getDetail($this->loginData['PROFILEID'], "PROFILEID","*");
        
        
        $trackingParams = $regParams["trackingParams"];
        unset($regParams["trackingParams"]);
        
        $this->form = new PageForm('', array("page" => 'APP3'), '');
        $this->form->bind($regParams);
        
        if ($request->isMethod("POST") && $this->form->isValid()) {           

            if($this->form->getValue('familyinfo')){
                RegChannelTrack::insertPageChannel($profileid,PageTypeTrack::_ABOUTFAMILY);
            }
            $this->form->updateData($profileid);
      
            //No need to login again as user is marked as complete 
            $apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
            $apiObj->generateResponse();
        }
        else //Form is Invalid then Error Respone
        {
            //IF new mobile site then mark page as new mobile page 3 (NMP3) for logging else APP3
            $page = "APP3";
            if(MobileCommon::isNewMobileSite())
                $page = "NMP3";
            RegistrationMisc::logServerSideValidationErrors($page,$this->form);

            foreach ($this->form->getFormFieldSchema() as $name => $formField)
            {
                $error=$formField->getError();

                if($error){
                    $errMes=$error->getMessageFormat();
                    $resp[error][]=$errMes;
                }
            }
            $apiObj->setHttpArray(ResponseHandlerConfig::$APP_REG_FAILED);
            $apiObj->setResponseBody($resp);
            $apiObj->generateResponse();
        }
        die;
    }
}
?>

