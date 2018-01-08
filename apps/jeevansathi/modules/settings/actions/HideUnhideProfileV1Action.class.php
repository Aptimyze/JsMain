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
        $data = json_decode($request->getParameter("data"), true);
        if($data["actionHide"] == 1)
        {
            $this->hideAction($data);
        }
        else if($data["actionHide"] == 0)
        {
            $this->unHideAction();
        }

    }

    private function hideAction($hideData)
    {
        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
        $profileid = $loggedInProfileObj->getPROFILEID();
        $privacy = "H";

        $hideObj = new JPROFILE;
        $DeleteProfileObj = new DeleteProfile;
        if(isset($hideData['hideDays']))
        {
            $hideDays = $hideData['hideDays'];
        }
        else
        {
            $hideOption = $hideData['hide_option'];
            if($hideOption == 1)
            {
                $hideDays = HideUnhideEnums::OPTION1;
            }
            elseif ($hideOption == 2)
            {
                $hideDays = HideUnhideEnums::OPTION2;
            }
            elseif ($hideOption == 3)
            {
                $hideDays = HideUnhideEnums::OPTION3;
            }
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

    private function unHideAction()
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

        $response = array('success' => 1);
        $respObj = ApiResponseHandler::getInstance();
        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $respObj->setResponseBody($response);
        $respObj->generateResponse();
        die;
    }
}

?>