<?php
/*
include("connect.inc");
connect_db();
$data=authenticated($checksum);*/
class processCropperV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
                ini_set('memory_limit',"512M");
                $profileObj=LoggedInProfile::getInstance('newjs_master');
                $profileid = $profileObj->getPROFILEID();
                $profileObj->getDetail("","","HAVEPHOTO");

                $cropImageSource = $request->getParameter('imageSource');
                $cropBoxDimensionsArr = $request->getParameter("cropBoxDimensionsArr");
                $imgPreviewTypeArr = $request->getParameter('imgPreviewTypeArr');
                $cropperProcessObj = new CropperProcess($profileObj);
                $profilesUpdate = $cropperProcessObj->process($cropImageSource,$cropBoxDimensionsArr,$imgPreviewTypeArr);
                $pictureServiceObj =new PictureService($profileObj);
                if(is_array($profilesUpdate))
                        $output = $pictureServiceObj->setPicProgressBit("FACE",$profilesUpdate);
                else
                        $output = -1;
                unset($pictureServiceObj);
            // Flush memcache for header picture
                $memCacheObject = JsMemcache::getInstance();
                $memCacheObject->remove($profileid . "_THUMBNAIL_PHOTO");

                $respObj = ApiResponseHandler::getInstance();
                if($output == 1)
                        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                else
                        $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                $respObj->setResponseBody($profilesUpdate);  //response to be decided and failure case:LATER
                $respObj->generateResponse();
                unset($profilesUpdate);
		die;
	}

}
