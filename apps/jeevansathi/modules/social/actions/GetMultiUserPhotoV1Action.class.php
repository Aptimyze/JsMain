<?php
/**
 * social actions.
 * get ........................
 * @package    jeevansathi
 * @subpackage social
 * @author     Lavesh Rawat
 */
class GetMultiUserPhotoV1Action extends sfActions
{ 
	public function execute($request)
	{
		/*
		$pidArr["PROFILEID"] ='5547372,8914646,8953994,1,2,3,4';
		$photoType = 'MainPicUrl';
		*/
		$pid = $request->getParameter("pid");
		$photoType =  $request->getParameter("photoType");

		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$inputValidateObj->validateGetMultiUserPhotoV1Action($request);
		$finalArr = $inputValidateObj->getResponse();
		if($finalArr["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
		{
			$profileObj=LoggedInProfile::getInstance('newjs_master');
			if($pid)
				$pidArr["PROFILEID"] =  $pid;
			else
			{
				$pidArr["PROFILEID"] =  $profileObj->getPROFILEID();
				$selfPicture=1;
			}

			$multipleProfileObj = new ProfileArray();

			$pidArrTemp = explode(",",$pidArr["PROFILEID"]);

			$profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pidArr);

			$multiplePictureObj = new PictureArray($profileDetails);
			$photosArr = $multiplePictureObj->getProfilePhoto();

			foreach($pidArrTemp as $profileId)
			{
				$photoObj = $photosArr[$profileId];
				if($selfPicture)
					$profileId=0;
				if($photoObj)
				{
					$photoTypeArr = explode(",",$photoType);
					foreach($photoTypeArr as $k=>$v)
					{
						eval('$temp =$photoObj->get'.$v.'();');
						if($temp)
							$finalArr['profiles'][$profileId]['PHOTO'][$v] = $temp;
						else
							$finalArr['profiles'][$profileId]['PHOTO'][$v] = '';
						unset($temp);
					}
				}
				else
				{
					$finalArr['profiles'][$profileId]['PHOTO'] = '';
				}
			}
		}
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody($finalArr);
		$respObj->generateResponse();
		die;
	}
}
