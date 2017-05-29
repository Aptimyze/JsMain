<?php
/**
 * matchAlert Toggle
 * matchAlertToggleApiV1
 * Controller to send search form data to app
 * @package    jeevansathi
 * @subpackage search
 * @author     Akash Kumar
 */

//This class performs the save search functioning 
class matchAlertToggleApiV1Action extends sfActions {

	public function execute($request) {
			//logout case handling
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			if($loggedInProfileObj->getPROFILEID()!='')
			{
				$loggedInProfileObj->getDetail("","","USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,HANDICAPPED,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE");
			}
			else{
				$this->forward("static", "logoutPage");
			}
                        $inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
                        $respObj = ApiResponseHandler::getInstance();	
                        $inputValidateObj->validatePopulateDefaultValues($request);
			$resp = $inputValidateObj->getResponse();
			if($resp["statusCode"] == ResponseHandlerConfig::$MATCHALERT_TOGGLE["statusCode"])
			{
                                $newjsMatchLogicObj = new newjs_MATCH_LOGIC();
                
                                //Logic Code setting
                                if ($request->getParameter("logic") == "dpp") 
                                        $newOrOld = MailerConfigVariables::$oldMatchAlertLogic;
                                else
                                        $newOrOld = MailerConfigVariables::$newMatchAlertLogic;

                                $newjsMatchLogicObj->setNewOrOldLogic($loggedInProfileObj->getPROFILEID(),$newOrOld);

                                $successMsg = array("matchAlertLogic"=>$newOrOld);
				$outputArr["successMessage"] = $successMsg;
			}

			$respObj->setHttpArray($resp);
			$respObj->setResponseBody($outputArr);
			$respObj->generateResponse();
                        return sfView::NONE;
                        die;
	}
}
?>
