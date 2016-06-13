<?php
/**
 * Class commonComponents
 * 
 * Common Components for Across Site
 *
 * @package    jeevansathi
 * @subpackage common
 * @author     Kunal Verma
 * @version    12th July 2015
 */
class commonComponents extends sfComponents{
    /*
     * executeHelpWidget
     * Common Component for Help Widget Across Site
     * @param $request : Request Param
     */
	public function executeHelpWidget($request){
        
        $loginData = $request->getAttribute('loginData');
        $login = $request->getAttribute('login')?1:0;
        $subscription = $loginData['SUBSCRIPTION'];
        $iProfileId = $loginData['PROFILEID'];
        
        $phoneNumber = "";
        $bIsNRI = $request->getAttribute('currency');
        $this->defaultEmail = "";
        if($login)
        {
            $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
            $this->defaultEmail = $loggedInProfileObj->getEMAIL();
            if($loggedInProfileObj->getMOB_STATUS() == "Y"){
                $phoneNumber = $loggedInProfileObj->getPHONE_MOB();
            }
            else if($loggedInProfileObj->getLANDL_STATUS() == "Y"){
                $phoneNumber = $loggedInProfileObj->getPHONE_WITH_STD();
            }
            else{//Check Alternate Phone numbmer
                $objAlternate = new NEWJS_JPROFILE_CONTACT;
                $arrResult = $objAlternate->getProfileContacts($iProfileId);
                if($arrResult["ALT_MOB_STATUS"] == "Y"){
                    if(trim($arrResult["ALT_MOBILE_ISD"]))
                        $phoneNumber = "+".trim($arrResult["ALT_MOBILE_ISD"])."-";
                    $phoneNumber .= trim($arrResult["ALT_MOBILE"]);
                }
            }
        }
        $this->defaultPhone = $phoneNumber ;
        
        $scriptname=$_SERVER['PHP_SELF'];
		$scriptname=str_replace("/P/","/profile/",$scriptname);
        $moduleName = sfContext::getInstance()->getModuleName();
        
        $this->showExpandMode = 0;
        // if(stripos($scriptname,'mainmenu.php')!==false || stripos($scriptname,'membership')!==false || stripos($moduleName,'membership')!==false)
        // {
        //    $this->showExpandMode = 1; 
        // }
        
        $this->mobileNumber = CommonConstants::HELP_NUMBER_INR;
        if($bIsNRI != "RS")
        {
            $this->mobileNumber = CommonConstants::HELP_NUMBER_NRI;
        }
	}

	/*
     * executeJsmsReqCallback
     * Common Component for Request Callback Across Site JSMS
     * @param $request : Request Param
     */
	public function executeJsmsReqCallback($request){
        
        $loginData = $request->getAttribute('loginData');
        $this->profileid = $loginData['PROFILEID'];
        
        if(MobileCommon::isApp()){
        	$this->device = "Android_app";
        } else {
        	$this->device = "mobile_website";
        }

        $data['device'] = $this->device;
        $this->currency = $request->getAttribute('currency');
        
        if ($this->currency == "RS") {
            $data['topHelp'] = array(
                "title" => "Help",
                "phone_number" => "1800-419-6299",
                "call_text" => "Call Us (Toll Free India)",
                "value" => "18004196299",
                "or_text" => "OR",
                "request_callback" => "Request Callback",
                "params" => "processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=" . $this->profileid . "&device=" . $this->device . "&channel=JSMS&callbackSource="
            );
        } 
        else {
            $data['topHelp'] = array(
                "title" => "Help",
                "phone_number" => "+911204393500",
                "call_text" => "Call Us (India)",
                "value" => "+911204393500",
                "or_text" => "OR",
                "request_callback" => "Request Callback",
                "params" => "processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=" . $this->profileid . "&device=" . $this->device . "&channel=JSMS&callbackSource="
            );
        }
        $this->data = $data;
	}

	/*
     * NotificationLayerJsms
     * Common Component for showing Notification Layer JSMS
     * @param $request : Request Param
     */
	public function executeNotificationLayerJsms($request){
        
        $loginData = $request->getAttribute('loginData');
        $this->profileid = $loginData['PROFILEID'];
       	$notificationObj = new NotificationConfigurationFunc();
        $this->showLayer = $notificationObj->showEnableNotificationLayer($this->profileid);
        unset($notificationObj);
	}
}
?>
