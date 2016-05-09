<?php
/*
include("connect.inc");
connect_db();
$data=authenticated($checksum);*/
class changePhotoPrivacyV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$profileid = $loggedInProfileObj->getPROFILEID();
		$json = $request->getParameter("json");
		$photoDisplay = $request->getParameter("photo_display");		
		if($json == 1)
		{
			if($profileid){
				$respObj = ApiResponseHandler::getInstance();
				$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$respObj->setResponseBody(array("output"=>$photoDisplay));
				$respObj->generateResponse();
				//print_r($a);die;
			}
			else{
				$respObj = ApiResponseHandler::getInstance();
				  $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				  $a=$respObj->generateResponse();
				  //print_r($a);die;
			}

		}
		else{
		if($profileid)
			echo $photoDisplay; 
		else
		{
			echo 'X';
			die;
		}
		echo $photo_display;
		}
		$ajax_error=2;
		$PhotoPrivacyObj = new NEWJS_PHOTO_PRIVACY();
		$PhotoPrivacyObj->UpdatePrivacy($profileid,$photoDisplay);
		die;
	}

}
		?>
