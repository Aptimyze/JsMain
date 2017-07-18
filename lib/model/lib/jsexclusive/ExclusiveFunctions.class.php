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
}
?>