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
		$formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getParameter("cid");
                $this->profileid = $formArr['profileid'];
                $this->source = $formArr['source'];
                $this->username = $formArr['username'];
                $this->emailAdd = $formArr['emailAdd'];
		$this->name= $request->getAttribute('name'); 
		$this->interface = ProfilePicturesTypeEnum::$INTERFACE["2"];
       
                if ($formArr['Skip'])
		{   //If User presses skip
                        $this->mailid = $formArr['mailid'];
                        $this->setTemplate('skipComments');
                        $this->comp = 1;
                }
                else
		{  //If user presses Upload
			$profileObj = Operator::getInstance("", $formArr["profileid"]);
			$photoScreeningServiceObj = new photoScreeningService($profileObj);
            $picDataForTracking = $photoScreeningServiceObj->pictureScreenStatus($this->profileid);
			//$trackingArray["PIC_DATA"] = $photoScreeningServiceObj->pictureScreenStatus($profileObj->getPROFILEID());
			$output = $photoScreeningServiceObj->processUpload($formArr,$request);
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

?>
