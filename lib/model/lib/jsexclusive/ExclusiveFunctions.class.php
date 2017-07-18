<?php
class ExclusiveFunctions{

	public function formatScreenRBInterestsData($clientParams=array(),$pogRBInterestsPids=array()){
		$pogRBInterestsPool = array();
		foreach ($pogRBInterestsPids as $key => $pid) {
			$profileObj = new Operator;
			$profileObj->getDetail($pid,"PROFILEID","PROFILEID,USERNAME,YOURINFO,HAVEPHOTO,GENDER,HOROSCOPE_MATCH");
			if($profileObj){
				$pogRBInterestsPool[$key]['PROFILEID'] = $pid;
				$pogRBInterestsPool[$key]['USERNAME'] = $profileObj->getUSERNAME();
				$pogRBInterestsPool[$key]['ABOUT_ME'] = $profileObj->getYOURINFO();
				$profilePic = $profileObj->getHAVEPHOTO();
				$oppGender = $profileObj->getGENDER();
				
				if($oppGender!=$clientParams["gender"]){
	        		if (!empty($profilePic) && $profilePic != 'N'){
	        			$pictureServiceObj=new PictureService($profileObj);
	            		$profilePicObj = $pictureServiceObj->getProfilePic();
	            		$photoArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getProfilePic120Url(),'ProfilePic120Url','',$oppGender,true);
	            		if($photoArray[label] == '' && $photoArray["url"] != null){
	                   		$pogRBInterestsPool[$key]['PHOTO_URL'] = $photoArray['url'];
	            		}
	            		
	            		unset($photoArray);
	            		unset($profilePicObj);
	            		unset($pictureServiceObj);
	        		}
	        		if(empty($pogRBInterestsPool[$key]['PHOTO_URL'])){
	        			if($oppGender=="M"){
	        				$pogRBInterestsPool[$key]['PHOTO_URL'] = sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoMaleProfilePic120Url');
	        			}
	        			else{
	        				$pogRBInterestsPool[$key]['PHOTO_URL'] = sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoFemaleProfilePic120Url');
	        			}
	        		}
	        	}
			}
			unset($profileObj);
		}
		return $pogRBInterestsPool;
	}

	public function formatRabbitmqData($inputArr=""){
		if(is_array($inputArr)){
			$outputArr = array('process' =>'RBSendInterests','data'=>array('type' => 'RB_EOI_SCREENING','body'=>array("MEMBERSHIP"=>"JsExclusive","SENDER"=>$inputArr["clientId"],"RECEIVER"=>$inputArr["acceptArr"],"SCREENED_DT"=>date("Y-m-d H:i:s"),"agentEmail"=>$inputArr["agentEmail"])), 'redeliveryCount'=>0);
			return $outputArr;
		}
		else{
			return null;
		}
	}

	public function processScreenedEois($params=""){
		if(is_array($params) && $params["clientId"] && $params["agentUsername"]){
			if(is_array($params["acceptArr"] && count($params["acceptArr"])>0)){
				$mqData = $this->formatRabbitmqData($params);
			}
			$exMappingObj = new billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING();
			if(is_array($mqData)){
				$producerObj = new Producer();
				if($producerObj->getRabbitMQServerConnected()){
					$producerObj->sendMessage($mqData);
				} 
				foreach ($params["acceptArr"] as $key => $value) {
					$exMappingObj->addClientMemberEntry($params["clientId"],$value,"Y");
				}
				unset($producerObj);
			}
			if(is_array($params["discardArr"]) && count($params["discardArr"])>0){
				$assistedEoiObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
				$assistedEoiObj->deleteEntry($params["clientId"],implode(",", $params["discardArr"]));
				unset($assistedEoiObj);
				foreach ($params["discardArr"] as $key => $value) {
						$exMappingObj->addClientMemberEntry($params["clientId"],$value,"N");
					}
			}
			unset($exMappingObj);
			$exServicingObj = new billing_EXCLUSIVE_SERVICING();
			$exServicingObj->updateScreenedStatus($params["agentUsername"],$params["clientId"],'Y');
			unset($exServicingObj);
		}
	}
}
?>