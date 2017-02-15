<?php 

/**
* 
*/
class HideUnhideProfileV1Action extends sfActions
{
	
	public function execute($request)
	{
        /*
        * Action - 
        *   1 is for Hide  
        *   0 is for UnHide 
        */
        $action = $request->getParameter("actionHide");
        if($action == 1)
        {
            $this->hideAction($request);
        }
        else if($action == 0)
        {
            $this->unHideAction($request);
        }

    }

    private function hideAction($request)
    {
        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
        $profileid = $loggedInProfileObj->getPROFILEID();
        $hideOption = $request->getParameter("hide_option");
        $privacy = "H";

        $hideObj = new JPROFILE;
        $DeleteProfileObj = new DeleteProfile;
        
        if($hideOption == 1)
        {
            $hideDays = 7;
        }
        elseif ($hideOption == 2)
        {
            $hideDays = 10;
        }
        elseif ($hideOption == 3)
        {
            $hideDays = 30;
        }
        
        $hideObj->UpdateHide($privacy, $profileid, $hideDays);
        $DeleteProfileObj->callDeleteCronBasedOnId($profileid);
        
        //code cookie
        $webAuthObj = new WebAuthentication;
        $webAuthObj->loginFromReg();

        $producerObj = new Producer();
        if($producerObj->getRabbitMQServerConnected())
        {
            $sendMailData = array('process' =>'USER_DELETE','data' => ($profileid), 'redeliveryCount'=>0 );
            $producerObj->sendMessage($sendMailData);
        }
        $response = array('success' => 1);
        $respObj = ApiResponseHandler::getInstance();
        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->setResponseBody($response);
        $respObj->generateResponse();
        die;
    }

    private function unHideAction($request)
    {
        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
        $profileid = $loggedInProfileObj->getPROFILEID();
        $privacy = "Y";
        $hideObj = new JPROFILE;
        $hideUpdate = $hideObj->UpdateUnHide($privacy,$profileid);
        $hideUpdate = $hideObj->SelectActicated($profileid);
        
        //code cookie
        $webAuthObj = new WebAuthentication;
        $webAuthObj->loginFromReg();

        $this->unhideProfile($profileid);
        $response = array('success' => 1);
        $respObj = ApiResponseHandler::getInstance();
        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->setResponseBody($response);
        $respObj->generateResponse();
        die;
    }

    private function unhideProfile($iProfileID)
    {
        global $noOfActiveServers;
        $argv[1] = $iProfileID;
        include(JsConstants::$docRoot."/profile/retrieveprofile_bg.php");
    }
}

?>