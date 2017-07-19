<?php
class ExclusiveFunctions{

	/*formatScreenRBInterestsData - format screened RB interests for clients assigned to logged in RM and filtered by RM
    * @param :$clientParams=array(),$pogRBInterestsPids=array()
    */
	public function formatScreenRBInterestsData($clientParams=array(),$pogRBInterestsPids=array()){
		$pogRBInterestsPool = array();

		if(count($pogRBInterestsPids)>0 && $clientParams["HoroscopeMatch"] == 'Y'){
			$gunaScoreObj = new gunaScore();
			$gunaScoreArr = $gunaScoreObj->getGunaScore($clientParams['PROFILEID'],$clientParams['clientCaste'],implode(",", $pogRBInterestsPids),$clientParams["gender"],1);
			unset($gunaScoreObj);
			
			foreach ($gunaScoreArr as $key => $valueArr) {
				foreach ($valueArr as $k => $v) {
					$formattedGunaScoreArr[$k] = $v;
				}
			}
			unset($gunaScoreArr);
		} 
		foreach ($pogRBInterestsPids as $key => $pid) {
			$profileObj = new Operator;
			$profileObj->getDetail($pid,"PROFILEID","PROFILEID,USERNAME,YOURINFO,HAVEPHOTO,GENDER,HOROSCOPE_MATCH");
			if($profileObj){
				$pogRBInterestsPool[$key]['PROFILEID'] = $pid;
				$pogRBInterestsPool[$key]['USERNAME'] = $profileObj->getUSERNAME();
				$pogRBInterestsPool[$key]['ABOUT_ME'] = $profileObj->getYOURINFO();
				if(!empty($pogRBInterestsPool[$key]['ABOUT_ME'])){
					$pogRBInterestsPool[$key]['ABOUT_ME'] = substr($pogRBInterestsPool[$key]['ABOUT_ME'], 0,1000);
				}
				$profilePic = $profileObj->getHAVEPHOTO();
				$oppGender = $profileObj->getGENDER();
				
				if($oppGender!=$clientParams["gender"]){
	        		if (!empty($profilePic) && $profilePic != 'N'){
	        			$pictureServiceObj=new PictureService($profileObj);
	            		$profilePicObj = $pictureServiceObj->getProfilePic();
	            		if(!empty($profilePicObj)){
		            		$photoArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getProfilePic120Url(),'ProfilePic120Url','',$oppGender,true);
		            		if($photoArray[label] == '' && $photoArray["url"] != null){
		                   		$pogRBInterestsPool[$key]['PHOTO_URL'] = $photoArray['url'];
		            		}
		            		unset($photoArray);
		            	}
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
	        		if(is_array($formattedGunaScoreArr) && $formattedGunaScoreArr[$pid]){
	        			$pogRBInterestsPool[$key]['GUNA_SCORE'] = $formattedGunaScoreArr[$pid];
	        		}
	        	}
			}
			unset($profileObj);
		}
		//print_r($pogRBInterestsPool);die;
		return $pogRBInterestsPool;
	}

	/*formatRabbitmqData - format screened RB data for rabbitmq
    * @param :$inputArr=""
    */
	public function formatRabbitmqData($inputArr=""){
		if(is_array($inputArr)){
			$outputArr = array('process' =>'RBSendInterests','data'=>array('type' => 'RB_EOI_SCREENING','body'=>array("MEMBERSHIP"=>"JsExclusive","SENDER"=>$inputArr["clientId"],"RECEIVER"=>$inputArr["acceptArr"],"SCREENED_DT"=>date("Y-m-d H:i:s"))), 'redeliveryCount'=>0);
			return $outputArr;
		}
		else{
			return null;
		}
	}

	/*processScreenedEois - process screened accepted and declined RB eois
    * @param :$params=""
    */
	public function processScreenedEois($params=""){
		if(is_array($params) && $params["clientId"] && $params["agentUsername"]){
			if(is_array($params["acceptArr"]) && count($params["acceptArr"])>0){
				$mqData = $this->formatRabbitmqData($params);
			}
			$exMappingObj = new billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING();
			if(is_array($mqData)){
				$producerObj = new Producer();
				if($producerObj->getRabbitMQServerConnected()){
					$producerObj->sendMessage($mqData);
					
					foreach ($params["acceptArr"] as $key => $value) {
						$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"Y"));
					}
				} 
				else{
					foreach ($params["acceptArr"] as $key => $value) {
						$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"PY"));
					}
				}
				unset($producerObj);
				
			}
			
			if(is_array($params["discardArr"]) && count($params["discardArr"])>0){
				$assistedEoiObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
				$assistedEoiObj->deleteEntry($params["clientId"],$params["discardArr"]);
				unset($assistedEoiObj);
				foreach ($params["discardArr"] as $key => $value) {
					$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"N"));
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