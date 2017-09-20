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
		if(!in_array($photoDisplay,array("A","C")))
			$photoDisplay = "A";
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
		$PhotoPrivacyObj = new JPROFILE();
		$editJprofileArray = array("PHOTO_DISPLAY"=>$photoDisplay,"MOD_DT"=>date("Y-m-d G:i:s"));
		$PhotoPrivacyObj->edit($editJprofileArray,$profileid);
                if($photoDisplay == 'C'){
                        PictureFunctions::photoUrlCachingForChat($profileid, array(), "ProfilePic120Url",'', "remove");
                }
		$now = date("Y-m-d H:i:s");
		$editArray = array("PHOTO_DISPLAY"=>$photoDisplay,"PROFILEID"=>$profileid,"MOD_DT"=>$now);
		$editLogObj = new EDIT_LOG();
		$editLogObj->log_edit($editArray, $profileid);
                if($photoDisplay == 'A'){
                     $PhotoPrivacyObj = new PROFILE_VOA_TRACKING();   
                     $PhotoPrivacyObj->insert($profileid);
                }
		die;
	}

}
		?>
