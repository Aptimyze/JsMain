<?php

/**
 * jsexclusive actions.
 *
 * @package    jeevansathi
 * @subpackage jsexclusive
 */
class jsexclusiveActions extends sfActions
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function executeIndex(sfWebRequest $request)
	{
		$this->forward('default', 'module');
	}

	/*ScreenRBInterests - screen RB interests for clients assigned to logged in RM
    * @param : $request
    */
	public function executeScreenRBInterests(sfWebRequest $request){
		$this->name = $request->getAttribute('name');

		$exclusiveObj = new billing_EXCLUSIVE_MEMBERS("newjs_slave");
		$assignedClients = $exclusiveObj->getExclusiveMembers("DISTINCT PROFILEID",true,"",$this->name,"",false);
		if(!is_array($assignedClients) || count($assignedClients)==0){
			$this->infoMsg = "No assigned clients corresponding to logged in RM found..";
		}
		else{
			$this->clientId = $assignedClients[0];
			$pogRBInterestsPids = array(82666,9397643,9061321,134640);
			$this->pogRBInterestsPool = array();
			foreach ($pogRBInterestsPids as $key => $pid) {
				$profileObj = new Operator;
				$profileObj->getDetail($pid,"PROFILEID","PROFILEID,USERNAME,YOURINFO,HAVEPHOTO,GENDER");
				if($profileObj){
					$this->pogRBInterestsPool[$pid]['USERNAME'] = $profileObj->getUSERNAME();
					$this->pogRBInterestsPool[$pid]['ABOUT_ME'] = $profileObj->getYOURINFO();
					$profilePic = $profileObj->getHAVEPHOTO();

					$oppGender = $profileObj->getGENDER();
            		if (!empty($profilePic) && $profilePic != 'U'){
            			$pictureServiceObj=new PictureService($profileObj);
                		$profilePicObj = $pictureServiceObj->getProfilePic();
                		$photoArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getProfilePic120Url(),'ProfilePic120Url','',$oppGender,true);
                		if($photoArray[label] == '' && $photoArray["url"] != null){
                       		$this->pogRBInterestsPool[$pid]['PHOTO_URL'] = $photoArray['url'];
                		}
                		
                		unset($photoArray);
                		unset($profilePicObj);
                		unset($pictureServiceObj);
            		}
            		if(empty($this->pogRBInterestsPool[$pid]['PHOTO_URL'])){
            			if($oppGender=="M"){
            				$this->pogRBInterestsPool[$pid]['PHOTO_URL'] = sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoMaleProfilePic120Url');
            			}
            			else{
            				$this->pogRBInterestsPool[$pid]['PHOTO_URL'] = sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoFemaleProfilePic120Url');
            			}
            		}
				}
				unset($profileObj);
			}

			print_r($this->pogRBInterestsPool);
		}
	}

    /*forwards the request to given module action
    * @param : $module,$action
    */
	public function forwardTo($module,$action)
	{
		$url="/operations.php/$module/$action";
		$this->redirect($url);
	}
}
?>