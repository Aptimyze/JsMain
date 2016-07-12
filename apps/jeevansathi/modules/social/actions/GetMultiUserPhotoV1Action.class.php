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
		$pidArr["PROFILEID"] ='5547372,8914646,8953994,1,2,3,4';
		$photoType = 'MainPicUrl';
		$profileObj=LoggedInProfile::getInstance('newjs_master');
	        $multipleProfileObj = new ProfileArray();

		$pidArrTemp = explode(",",$pidArr["PROFILEID"]);

 	        $profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pidArr);

                $multiplePictureObj = new PictureArray($profileDetails);
		$photosArr = $multiplePictureObj->getProfilePhoto();

		foreach($pidArrTemp as $profileId)
		{
			$photoObj = $photosArr[$profileId];
			if($photoObj)
			{
				eval('$temp =$photoObj->get'.$photoType.'();');
				$finalArr[$profileId]['PHOTO'] = $temp;
				unset($temp);
			}
			else
			{
				$finalArr[$profileId]['PHOTO'] = '';
			}
		}

		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody($finalArr);
		$respObj->generateResponse();
		die;
	}
}
