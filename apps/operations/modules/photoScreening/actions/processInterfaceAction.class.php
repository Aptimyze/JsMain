<?php

/**
 * photoScreening actions.
 *
 * @package    operation
 * @subpackage photoScreening
 * @author     Reshu Rajput
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z 
 */
class processInterfaceAction extends sfActions {

	private $uploadLocal = false; // Setting local server call 
	const DEFAULT_AVOID_REFRESH_TIME = 2; // Refresh time 
        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
        public function executeProcessInterface(sfWebRequest $request) {
		$this->cid = $request->getParameter("cid");
                $this->source = $request->getParameter('source');
		$name = $request->getAttribute('name');
                $this->execName = $name;
		$memcache = $_GET["skipMemcache"];
		$this->interface = ProfilePicturesTypeEnum::$INTERFACE["2"];
		photoScreeningService::avoidRefresh($this->execName,$this->interface,$memcache,self::DEFAULT_AVOID_REFRESH_TIME);
		$this->uploadUrl = $this->getUploadFunctionUrl();

		$profileAllotmentFactory = new PhotoScreenProfileAllotmentFactory();
		$allotParam=array("SOURCE"=>$this->source,"INTERFACE"=>ProfilePicturesTypeEnum::$INTERFACE["2"],"NAME"=>$name);
		if($request->getParameter('profileId'))
                        $allotParam["PROFILEID"] = $request->getParameter('profileId');
                $profileAllotedObj = $profileAllotmentFactory->getAllot($allotParam);
                $profileDetails = $profileAllotedObj->getAllotedProfile($allotParam);
		$this->profileid = $profileDetails['profileData']['PROFILEID'];
		if(!is_array($profileDetails))
			$this->noProfileFound = 1;
		else
		{
			$photoScreeningServiceObj = new photoScreeningService($profileDetails["profileObj"]);
			$paramArr["interface"]=ProfilePicturesTypeEnum::$INTERFACE["2"];
			$this->hideCropper = false;
                	//Show in Template 
                	$this->photoArr = $photoScreeningServiceObj->getPicturesToScreen($paramArr);
			$totalPicSizeForScreen = count($this->photoArr['profilePic']['profileType']);
                        if(array_key_exists("nonScreened",$this->photoArr)||$totalPicSizeForScreen==0)
				$this->hideCropper = true;
			$this->mainPicSize = ProfilePicturesTypeEnum::$MAIN_PIC_MAX_SIZE;
                	$this->profileData = $profileDetails["profileData"];
                	if (!$this->photoArr["nonScreened"] && !$this->photoArr["profilePic"]) 
			{ //if no profiles are under screening, show the next profile by redirecting to self
				$arrDevelopersEmail = PictureStaticVariablesEnum::$arrPHOTO_SCREEN_DEVELOPERS;
                               // JsTrackingHelper::sendDeveloperTrackMail($arrDevelopersEmail,"No Photo Found for Process Interface ".$profileDetails["profileData"]["PROFILEID"]);
				$profileAllotedObj->reNewProfileForPreprocess($profileDetails["profileData"]["PROFILEID"]);
				$this->redirect(JsConstants::$siteUrl . "/operations.php/photoScreening/processInterface?cid=$this->cid&name=$name&source=$this->source");
                	}
                }
        }
	
	/*This function is used to get if image is served locally or not
	*@return url
	*/
	public function getUploadFunctionUrl()
	{
		if($this->uploadLocal)
			$url = JsConstants::$localImageUrl."/photo/photo_nonscreened_serve_locally.php?Imagefile=";
		else
			$url = "";
		return $url;
	}
}

?>
