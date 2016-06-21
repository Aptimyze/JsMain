<?php

/**
 * settings actions.
 *
 * @package    jeevansathi
 * @subpackage settings
 * @author     Avneet Singh Bindra
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class settingsActions extends sfActions
{
	
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */

    public function executeJspcSettings(sfWebRequest $request) {
        
    	$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
    	//print_r($loggedInProfileObj);die;
    	//$loggedInProfileObj->getDetail("","","PROFILEID,USERNAME");
    	$profileid = $loggedInProfileObj->getPROFILEID();
    	$username = $loggedInProfileObj->getUSERNAME();
    	//print_r($username);
    	if($request->getParameter("visibility"))
    	{
    		//var_dump($loggedInProfileObj);die;
    		
    		$this->page="visibility";
    		if($loggedInProfileObj)
    		{
    			$privacyObj = new JPROFILE;
    				if($request->getParameter("privacy"))
    	   			{
    					$privacyValue = $loggedInProfileObj->getPRIVACY();
    					if($privacyValue=="")
    						$privacyValue="A";
    					$privacyObj->UpdatePrivacy($request->getParameter("privacy"),$profileid);
    					//$loggedInProfileObj->edit()
    					$privacyArray[0]=$request->getParameter("privacy");
    					$privacyArray[1]=$privacyValue;
    					ob_end_clean();
    					print_r(implode("",$privacyArray));die;
    				}
    				else
    				{
    					$privacyValue = $loggedInProfileObj->getPRIVACY();
    					//print_r($privacyValue);die;
    					if($privacyValue=="")
    						$privacyValue="A";
    					$this->privacyValue=$privacyValue;

    				}
    			}  
    			
    		}

    	if($request->getParameter("hideDelete"))
    	{
    		if(MobileCommon::isNewMobileSite())
    		{
    			$url=JsConstants::$siteUrl.'/static/deleteOption';
				header('Location: '.$url);
				exit;
    		}
    		$option=$request->getParameter("option");
    		//$request=$this->getRequest();
    		//$this->loginData=$data=$request->getAttribute("loginData");
    		$this->page="hideDelete";
    		$hideDeleteObj = new JPROFILE;
    		$DeleteProfileObj = new DeleteProfile;
    		//echo "string";die;
    		if(!$option)
    		{
    			
    			//$hideDeleteValue= $hideDeleteObj->SelectHide($profileid);
            //print_r($hideDeleteValue);die;
    			$email = $loggedInProfileObj->getEMAIL();
    			$ActivateValue = $loggedInProfileObj->getACTIVATED();
    			$ActivateDate = $loggedInProfileObj->getACTIVATE_ON();
    			//var_dump($ActivateDate);die;
    			if($ActivateValue=="H")
    			{
    				$this->UNHIDE=1;
    				

    				list($year,$month,$day)=explode("-",$ActivateDate);
    				
    				$this->UNHIDE_DATE=my_format_date($day,$month,$year,4);
    				//print_r($this->UNHIDE_DATE);die;
    			}
    			else
    				$this->UNHIDE=0;
                //print_r($this->UNHIDE);
    		}
    		if($option=="Hide")
    		{
    			$privacy="H";
                	//echo"sdh";
    			$hideDays=$request->getParameter("hideDays");
    			$hideDeleteObj->UpdateHide($privacy,$profileid,$hideDays);
    			$DeleteProfileObj->callDeleteCronBasedOnId($profileid);
                    //code cookie
    			$webAuthObj = new WebAuthentication;
    			$webAuthObj->loginFromReg();

    			$dateStamp=mktime(0,0,0,date("m"),date("d")+$hide_duration,date("Y"));
    			$date=date("Y-m-d",$dateStamp);
    			list($year,$month,$day)=explode("-",$date);
    			$unhide_date=my_format_date($day,$month,$year,4);
    			$DeleteProfileObj->callDeleteCronBasedOnId($profileid);
    			print_r("HIDE SUCCESS");die;
    		}

    		elseif($option=="Show")
            {
            $privacy="Y";
            $hideUpdate= $hideDeleteObj->UpdateUnHide($privacy,$profileid);
            $hideUpdate= $hideDeleteObj->SelectActicated($profileid);

                    //code cookie
            $webAuthObj = new WebAuthentication;
    			$webAuthObj->loginFromReg();
            print_r("SHOW SUCCESS");die;

            
        }
        elseif($option=="Delete")
        {
        	$delete_reason=$request->getParameter("deleteReason");
        	$specify_reason=$request->getParameter("specifyReason");
            $offerConsent=$request->getParameter("offerConsent");
        	if($delete_reason=="I found my match on other website")
        		$specify_reason="www.".trim($specify_reason).".com";
        	
        	if($delete_reason=="I am unhappy about services")
        	{
        		$msg.="Date  :  ".date("d-m-Y")."<br>";
        		$msg.="Username  :  ".$data["USERNAME"]."<br>";
        		$msg.="Email id  :  ".$email."<br>";
        		if(trim($specify_reason))
        			$msg.="Reason   : ".trim($specify_reason)."<br>";
        		else
        			$msg.="Reason   : No reason specified<br>";
        		SendMail::send_email("feedback@jeevansathi.com",$msg,"Unhappy user deletes profile","info@jeevansathi.com");
        	}
        	if($delete_reason=="I found my match on Jeevansathi.com")
        	{
        		
        		print_r("success redirect");die;
        	}
        	$DeleteProfileObj->delete_profile($profileid,$delete_reason,$specify_reason,$username);
        	$DeleteProfileObj->callDeleteCronBasedOnId($profileid);
        
            //// tracking of offer consent  added by Palash Chordia
            if($offerConsent=='Y')
            (new NEWJS_OFFER_CONSENT())->insertConsent($profileid);
            ////////////////

        	sfContext::getInstance()->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMyJSPageUrl);
        	print_r("skipDelete");die;
        }
    }

    
    if($request->getParameter("changePassword"))
    {
    	$pObj = LoggedInProfile::getInstance();
    	$pObj->getDetail($profileid, "PROFILEID","PASSWORD,EMAIL");
    	$this->emailStr=$pObj->getEMAIL();
    	$this->page="changePassword";
    }
    
}

public function executeAlertManager(sfWebRequest $request){
	$request->setParameter('INTERNAL', 1);
	ob_start();
	$data = sfContext::getInstance()->getController()->getPresentationFor('settings', 'AlertManagerV1');
	$output = ob_get_contents();
	ob_end_clean();
	$data = json_decode($output, true);
	$this->alertManagerData = $data;
    $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
    $profileid = $loggedInProfileObj->getPROFILEID();
    if($profileid)
    {
        $notificationObj = new NotificationConfigurationFunc();
        $toggleOutput = $notificationObj->showNotificationToggleLayer($profileid);
        $this->showNotificationToggleLayer = $toggleOutput["showToggleLayer"];
        unset($toggleOutput);
        unset($notificationObj);
    }
}


}


