<?php

/**
 * photoScreening actions.
 *
 * @package    operation
 * @subpackage photoScreening
 * @author     Reshu Rajput
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z 
 */
class uploadProcessScreeningAction extends sfActions {


        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
        public function executeUploadProcessScreening(sfWebRequest $request) {
			$startTime = time();
		$formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getParameter("cid");
                $this->profileid = $formArr['profileid'];
                $this->source = $formArr['source'];
                $this->username = $formArr['username'];
                $this->emailAdd = $formArr['emailAdd'];
		if($formArr['ops']=="false" || $formArr['ops']==''||$formArr['ops']==false)
			$ops = false;
		else
			$ops = true;
		$formArr['ops']=$ops;
		$this->name= $request->getAttribute('name'); 
		$this->interface = ProfilePicturesTypeEnum::$INTERFACE["2"];
                if ($formArr['Skip'])
		{   //If User presses skip
                        $this->mailid = $formArr['mailid'];
                        $this->setTemplate('skipComments');
                        $this->comp = 1;
                }
                else
		{  
			$profileObj = Operator::getInstance("", $formArr["profileid"]);
			$profileObj->getDetail($this->profileid, "PROFILEID", "HAVEPHOTO,PHOTO_DISPLAY,USERNAME");
			if($ops)
			{
				$cropImageSource = $formArr['imageSource'];
				$cropBoxDimensionsArr = json_decode($formArr['cropBoxDimensionsArr'],true);
				$formArr['cropBoxDimensionsArr']=$cropBoxDimensionsArr;
				$imgPreviewTypeArr = json_decode($formArr['imgPreviewTypeArr'],true);
				$formArr['imgPreviewTypeArr']=$imgPreviewTypeArr;
				$cropperProcessObj = new CropperProcess($profileObj);
				$filesGlobArr = $cropperProcessObj->process($cropImageSource,$cropBoxDimensionsArr,$imgPreviewTypeArr,$ops);
			}
			$photoScreeningServiceObj = new photoScreeningService($profileObj);
			$picDataForTracking = $photoScreeningServiceObj->pictureScreenStatus($this->profileid);

			$output = $photoScreeningServiceObj->processUpload($formArr,$ops,$filesGlobArr);

			if(is_array($output))
			{	
				$response= $output["message"];	
				$trackingArray["count"] = $output["count"];
				$trackingArray["name"] = $this->name;
				$trackingArray["source"] = $this->source;
				$trackingArray["notify"] = $output["notify"];
				$trackingArray["statusArr"] = $output["statusArr"];
				$trackingArray["picDataForTracking"] = $picDataForTracking;
				$photoScreeningServiceObj->trackProcessInterface($trackingArray);
			}
			else
				$response = $output;
			$timeConsumed = time()-$startTime;
			file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/pictureUploadSubmitTime.txt",var_export($timeConsumed,true)."\n",FILE_APPEND);
	
			
			if($response!="Success")
			{
                        	$this->messageFlag = 0;
                        	$this->errMessage = $response;
                	}
			else
				 $this->messageFlag = 1;

                	$this->setTemplate('outputTemplate');
		}
        }
}
