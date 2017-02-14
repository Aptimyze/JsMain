<?php 

/**
* 
*/
class HideProfileV1Action extends sfActions
{
	
	public function execute($request)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$profileid = $loggedInProfileObj->getPROFILEID();
		$hideOption = $request->getParameter("hide_option");
    	$privacy="H";

    	$hideDeleteObj = new JPROFILE;
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
    	
    	$hideDeleteObj->UpdateHide($privacy,$profileid,$hideDays);
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
	    print_r("HIDE SUCCESS");die;
	}
}

?>