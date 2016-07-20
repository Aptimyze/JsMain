<?php

/**
 * photoScreening actions.
 *
 * @package    operation
 * @subpackage uploadCropperScreening
 * @author     Esha Jain
 * @version    GIT: 2016-07-20 
 */
class uploadProcessCropperScreeningAction extends sfActions {

        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
        public function executeUploadProcessCropperScreening(sfWebRequest $request) {
                ini_set('memory_limit',"512M");

		$formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getParameter("cid");
                $this->profileid = $formArr['profileid'];
                $this->source = "mail";
                $this->username = $formArr['username'];
                $this->emailAdd = $formArr['emailAdd'];
		$this->name= $request->getAttribute('name'); 
		$this->interface = ProfilePicturesTypeEnum::$INTERFACE["2"];
       
		$profileObj = Operator::getInstance("", $formArr["profileid"]);
		$profileObj->getDetail($this->profileid, "PROFILEID", "HAVEPHOTO,PHOTO_DISPLAY,USERNAME");
       
                $cropImageSource = $request->getParameter('imageSource');
                $cropBoxDimensionsArr = $request->getParameter("cropBoxDimensionsArr");
                $imgPreviewTypeArr = $request->getParameter('imgPreviewTypeArr');
       
                $cropperProcessObj = new CropperProcess($profileObj);
                $picturesToUpdate = $cropperProcessObj->process($cropImageSource,$cropBoxDimensionsArr,$imgPreviewTypeArr);
		$photoScreeningServiceObj = new photoScreeningService($profileObj);
		$picDataForTracking = $photoScreeningServiceObj->pictureScreenStatus($this->profileid);

		$pictureServiceObj =new PictureService($profileObj,'SCREENING');
		$pictureObj = new NonScreenedPicture('SCREENING');

		$pictureServiceObj->setPicProgressBit(ProfilePicturesTypeEnum::$INTERFACE["2"],$picturesToUpdate);
		$profileScreened = "0";
		$statusArr =$pictureObj->profilePictureStatusArr($profileObj->getPROFILEID());

                                if($photoScreeningServiceObj->isProfileScreened() == 1) //
                                {

                                        $moveArr = array("DELETE" => $statusArr["DELETED"], "APPROVED" => $statusArr["APPROVED"], "ProfilePicId" => $statusArr["profilePic"], "TYPE" => "N");
                                        $photoScreeningServiceObj->moveImageAfterScreened($moveArr);
                                        $profileScreened = "1";
                                        if($statusArr["DELETED"])
                                        {
                                                $deleteNonScreenedObj = new PICTURE_DELETE_NEW;
                                                $statusArr["REJECT_REASON"] = $deleteNonScreenedObj->getDeletionReason($statusArr["DELETED"]["0"]);
                                        }
                                        else
                                                $statusArr["REJECT_REASON"]="";
                                }
                        $returnArray = array();
                        $returnArray["message"]= "Success";
                        $returnArray["count"] = 1;
                        $returnArray["notify"] = $profileScreened;
			$statusArr['APPROVED']=1;
                        $returnArray["statusArr"] = $statusArr;
			$output = $returnArray;
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
		
                $respObj = ApiResponseHandler::getInstance();
                if($response == "Success")
                        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                else
                        $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                $respObj->generateResponse();
                die;
		}
}
