<?php

/**
 * phone actions.
 *
 * @package    jeevansathi
 * @subpackage phone
 * @author     Esha
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class phoneActions extends sfActions
{
  public function executeDisplayV1(sfWebRequest $request)
  {
	$respObj = ApiResponseHandler::getInstance();
	$profileObj = LoggedInProfile::getInstance('newjs_master');
	$profileid = $profileObj->getPROFILEID();

	$sendingDetails['ISD'] = $profileObj->getISD() ? phoneKnowlarity::removeAllSpecialChars($profileObj->getISD()):null;
	$sendingDetails['PHONE1']=$profileObj->getPHONE_MOB() ? trim($profileObj->getPHONE_MOB()) :null;

	$contactNumOb= new ProfileContact();
    $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileid),'','',"ALT_MOBILE");
	$sendingDetails['PHONE2']=$numArray['0']['ALT_MOBILE']?trim($numArray['0']['ALT_MOBILE']):null;

	if(MobileCommon::isApp() || MobileCommon::isNewMobileSite())
	{

	if($sendingDetails['PHONE1']){
		$knowlarityObj=new phoneKnowlarity($profileObj,'M');
		$virtualNumber = $knowlarityObj->getVirtualNumber();
	}
	if($sendingDetails['PHONE2']){
		$knowlarityObj=new phoneKnowlarity($profileObj,'A');
		$virtualNumber = $knowlarityObj->getVirtualNumber();
	}

	$sendingDetails['DIAL_NUMBER'] = $virtualNumber;
	}
	else 
	$sendingDetails['DIAL_NUMBER'] = null;
	$sendingDetails['BUTTON_TEXT'] = PhoneApiFunctions::$phoneButtonIndiaText;
	$sendingDetails['MESSAGE'] = PhoneApiFunctions::phoneVerifyProcessMessage($virtualNumber,$sendingDetails['ISD']);
	$sendingDetails['INTERVAL'] = PhoneApiFunctions::$interval;
	$sendingDetails['LABEL1'] = PhoneApiFunctions::$labelMobile;
	$sendingDetails['LABEL2'] = PhoneApiFunctions::$labelAlternateMobile;
	$sendingDetails['TITLE'] = PhoneApiFunctions::$phoneTitle;
	$sendingDetails['HEADING'] = PhoneApiFunctions::$phoneMessage;
	$sendingDetails['NO_OF_TIMES']= PhoneApiFunctions::$frequency;
	
	if($sendingDetails['ISD']=='91')
		$sendingDetails['HELPLINE']=CommonConstants::HELP_NUMBER_INR;
	else
		$sendingDetails['HELPLINE']=CommonConstants::HELP_NUMBER_NRI;

	
	$sendingDetails['HELP_EMAIL']=PhoneApiFunctions::$helpEmail;
	$sendingDetails['WORKING_MESSAGE']=PhoneApiFunctions::$errorBottomMessage;
	$sendingDetails['TOLL_FREE_NRI']=CommonConstants::HELP_NUMBER_NRI;
	$sendingDetails['TOLL_FREE_INR']=CommonConstants::HELP_NUMBER_INR;

 // for showing consent message on app
	        
	$memObject=JsMemcache::getInstance();
	$showConsentMsg=$memObject->get('showConsentMsg_'.$profileid);
	if(!$showConsentMsg) 
	{
		$showConsentMsg = JsCommon::showConsentMessage($profileid) ? 'Y' : 'N';
	    $memObject->set('showConsentMsg_'.$profileid,$showConsentMsg);
	}
	
	if ($showConsentMsg!='Y')
		$sendingDetails['showconsentmessage']=null;
		
	else 
	{
	    $sendingDetails['showconsentmessage']='Y';
	    $sendingDetails['consentmessage1']=PhoneApiFunctions::$consentMessage1;
	    $sendingDetails['consentmessage2']=PhoneApiFunctions::$consentMessage2;
	}
	
	////////////////////////////////////////
	$respObj->setHttpArray(ResponseHandlerConfig::$DISPLAY_PHONE_SCREEN);
	$respObj->setPhoneDetails($sendingDetails);
	$respObj->generateResponse();
	
	if(MobileCommon::isApp()==null)
		return sfView::NONE;
	die;
  }

  public function executeSaveV1(sfWebRequest $request)
  {
  	$respObj = ApiResponseHandler::getInstance();

	$number = $request->getParameter('NUMBER');
	$isd = $request->getParameter('ISD');
	$type = $request->getParameter('TYPE');

	$isd = phoneKnowlarity::removeAllSpecialChars($isd);
	$number = phoneKnowlarity::removeAllSpecialChars($number);
        $profileObj = LoggedInProfile::getInstance('newjs_master');
        $profileid = $profileObj->getPROFILEID();
        
	$phoneType=NULL;
	if($type=="PHONE1")
		$phoneType = "M";
	elseif($type=="PHONE2")
		$phoneType = "A";
	else
		$respObj->setHttpArray(ResponseHandlerConfig::$PHONE_INVALID_INPUT);
	//As the new api will be only running for PC and Mobile, we are only calling it internally for phoneType 'M' . phoneType 'A' stands for android.
	if($phoneType == "M")
	{ 
	$editFieldArr =array();
	$PHONE_MOB['mobile'] = $number;	
	$PHONE_MOB['isd'] = $isd;
	$editFieldArr["PHONE_MOB"] = $PHONE_MOB;
	$request->setParameter("editFieldArr",$editFieldArr);
	$request->setParameter('internally',1);
	//var_dump($request->getParameter('editFieldArr'));

	ob_start();
	sfContext::getInstance()->getController()->getPresentationFor("profile", "ApiEditSubmitV1");
    $data = ob_get_contents();
    ob_end_clean();
	$data = json_decode($data);
	if(!is_array($data->error))	
	    $errorArr=get_object_vars($data->error);//print_r($data);die('0000');
    else 
    	$errorArr=$data->error;
	$arrKeys=array_keys($errorArr);
	
    if($data->responseStatusCode != 0)
    {
	$data->responseMessage=$errorArr[$arrKeys[0]];
        
    }
        $memObject=JsMemcache::getInstance();
        $memObject->delete('showConsentMsg_'.$profileid);		
        $memObject->delete($profileid.'_PHONE_VERIFIED');			  			
        $knowlarityObj=new phoneKnowlarity($profileObj,$phoneType);
        $data->DIAL_NUMBER =$knowlarityObj->getVirtualNumber();
	$data = json_encode($data);
	echo $data;
	die;
	}

	else if($phoneType == 'A')
	{
		if($isd=='')
			$respObj->setHttpArray(ResponseHandlerConfig::$ISD_BLANK);
		elseif(!($isd = phoneKnowlarity::getIsdInFormat($isd)))
			$respObj->setHttpArray(ResponseHandlerConfig::$ISD_INVALID);
		elseif($number==''&& $phoneType!="A")
			$respObj->setHttpArray(ResponseHandlerConfig::$PHONE_BLANK);
		elseif($number!="" && ($numberValid=phoneKnowlarity::checkMobileNumber($number,'','',$isd))=="N")
			$respObj->setHttpArray(ResponseHandlerConfig::$PHONE_INVALID);
		elseif((new incentive_NEGATIVE_LIST())->checkEmailOrPhone("PHONE_NUM",$isd.$number))
                         $respObj->setHttpArray(ResponseHandlerConfig::$PHONE_JUNK);
		else
		{
			$phoneVerObject=new PhoneVerification($profileObj,$phoneType);
			$phoneVerObject->savePhone($number,'',$isd);
                        $memObject=JsMemcache::getInstance();
			$memObject->delete('showConsentMsg_'.$profileid);		
			$memObject->delete($profileid.'_PHONE_VERIFIED');			  			
 			
			$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$knowlarityObj=new phoneKnowlarity($profileObj,$phoneType);
			$response[DIAL_NUMBER] =$knowlarityObj->getVirtualNumber();
			$respObj->setResponseBody($response);
		}
			$respObj->generateResponse();
			die;
	}

  }
  public function executeVerifiedV1(sfWebRequest $request)
  {
		$respObj = ApiResponseHandler::getInstance();
        $profileObj = LoggedInProfile::getInstance('newjs_master');
        $profileid = $profileObj->getPROFILEID();
        $phoneType=$request->getParameter('phoneType');
        if($phoneType=='L' || $phoneType=='A' || $phoneType=='M') {
		$phoneVerObject=new PhoneVerification($profileObj,$phoneType);
		$phoneVerified=$phoneVerObject->isVerified();
		}

        else 
        {
        $phoneVerified = unserialize(JsMemcache::getInstance()->get($profileid."_PHONE_VERIFIED"));
		
		if(!$phoneVerified)
		{
		$phoneVerified = phoneVerification::hidePhoneVerLayer($profileObj);
		JsMemcache::getInstance()->set($profileid."_PHONE_VERIFIED",$phoneVerified);
		}

		}
	
	$result['FLAG']=$phoneVerified;
	$result['PHOTO']= null;
	if($phoneVerified=="Y")
	{

		$pictureServiceObj = new PictureService($profileObj);
		$result['PHOTO']=$pictureServiceObj->isProfilePhotoPresent();
		if($result['PHOTO']!='Y')
			$result['PHOTO']= "N";
	}

	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	$respObj->setResponseBody($result);
	$respObj->generateResponse();
	die;
  }

//this method is used for reporting a phone number as invalid
	public function executeReportInvalid(sfWebRequest $request)
	{
   		$reasonNumber = $request->getParameter('reasonCode'); 
   		$reason = phoneEnums::$mappingArrayReportInvalid[$reasonNumber-1];
   		$otherReason = "";
   		if($reasonNumber == 5)
   		$otherReason = $request->getParameter('otherReasonValue');	
		$respObj = ApiResponseHandler::getInstance();
		$profileChecksum=$request->getParameter('profilechecksum');
		$phone=$request->getParameter('phone');
		$mobile=$request->getParameter('mobile');
   		
   		if(!$profileChecksum) {
   			$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
	$respObj->setResponseBody($result);
	$respObj->generateResponse();
	die;
  }
     	if(!$reasonNumber)
     	{
     			$respObj->setHttpArray(ResponseHandlerConfig::$PHONE_INVALID_NO_OPTION_SELECTED);
	$respObj->setResponseBody($result);
	$respObj->generateResponse();
	die;
     	}

     	$reportInvalidObj=new JSADMIN_REPORT_INVALID_PHONE();
     	$selfProfileID=LoggedInProfile::getInstance()->getPROFILEID();
     	$profileid = JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
     	$ReportInvalidLibObj = new ReportInvalid();

     	$anotherMarkInvalid = $ReportInvalidLibObj->entryAlreadyExists($selfProfileID,$profileid,$phone,$mobile);

     	if($anotherMarkInvalid)
     	{
     		$respObj->setHttpArray(ResponseHandlerConfig::$SAME_NUMBER_INVALID_TWICE);
     		$result['message'] = ResponseHandlerConfig::$SAME_NUMBER_INVALID_TWICE['message'];
     		$result['heading'] = "Cannot report invalid";
			$respObj->setResponseBody($result);
			$respObj->generateResponse();
			die;
     	}

   		$profile2=new Profile();
   		$increaseQuotaImmediate = ReportInvalid::increaseQuotaImmediately($selfProfileID,$profileid);
   		$reportInvalidObj->insertReport($selfProfileID,$profileid,$phone,$mobile,'',$reason,$otherReason);   		

		if($reasonNumber == 3)
			{  
				$sendingObject = new RequestUserToDelete();
				$sendingObject->deleteRequestedByOther($profileid);
				$loggingObj = new MIS_REQUEST_DELETIONS_LOG();
                $loggingObj->logThis(LoggedInProfile::getInstance()->getUSERNAME(),$profileid,'Other');
			}

			$ReportInvalidLibObj->sendExtraNotification($selfProfileID,$profileid,$reasonNumber);
			
	if($increaseQuotaImmediate == true)
	{
		$result['message']='Thanks for helping us make Jeevansathi better matchmaking platform. We have credited one contact to your quota, and will investigate this further';
	}
	else { 

    	$result['message']='Thank you for helping us . If our team finds this number invalid we will remove this number and credit you with a contact as compensation.';
	}
    $respObj->setHttpArray(ResponseHandlerConfig::$PHONE_INVALID_SUCCESS);
	$respObj->setResponseBody($result);
	$respObj->generateResponse();
	die;
		
	}
	


  

// this method sends the response for the consent message
	public function executeConsentMessage(sfWebRequest $request)
	{
		$detail=$request->getAttribute('loginData');
		$this->username=$detail['USERNAME']; 
		if (MobileCommon::isNewMobileSite()) {
		$this->setTemplate("mobileConsentMessage");
		}
		
		else {
		$this->setTemplate("desktopConsentMessage");
		}   
	}
	

	public function executeConsentConfirm(sfWebRequest $request)
	{	
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$profileid=$loggedInProfileObj->getPROFILEID();
       	JsCommon::insertConsentMessageFlag($profileid);
        die();
		}





  public function executeJsmsDisplay(sfWebRequest $request)
  {
	if($request->getParameter('fromReg'))
		$this->fromReg = 1;
	$this->groupname = $request->getParameter('groupname');
	$this->loginData=$request->getAttribute("loginData");
	$this->loginProfile=LoggedInProfile::getInstance();
	$loginProfileid = $this->loginData[PROFILEID];
	$this->loginProfile->getDetail($loginProfileid,"PROFILEID","*");


	// to check if the current profile's primary number is duplicate or not
	if (JsCommon::showDuplicateNumberConsent($loginProfileid))
		$this->showDuplicateConsentMsg = 'Y' ;
	else 
		$this->showDuplicateConsentMsg = 'N' ;

	//Pixel code to run only when coming from mobile registration page 4
	 //Unset the Family Cookie
  if(isset($_COOKIE['reg_family']))
  {
    unset($_COOKIE['reg_family']);
    setcookie('reg_family',null,-1,"/");          
  }
	if($request->getParameter('fromReg')==1)
	{
		//If coming directly from registration, used for google pixel code
		if (trim($request->getParameter('groupname'))) {
			$this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$this->loginProfile);
                        $this->groupname = $request->getParameter('groupname');
                        $this->sourcename =$request->getParameter('source');
		}
	}
	ob_start();
	$jsonData = sfContext::getInstance()->getController()->getPresentationFor("phone", "displayV1");
	$output = ob_get_contents();
	ob_end_clean();
	$this->showConsentMsg=$request->getParameter("showConsentMsg");
	$this->apiData=json_decode($output,true);
	$this->apiData[phoneDetails][fromReg]=$this->fromReg;
	$this->apiData[phoneDetails][groupname]=$this->groupname;
  }




//action for pc phone verification ....... By Palash Chordia 
  public function executePhoneVerificationPcDisplay(sfWebRequest $request)
		{
	$this->loginData=$request->getAttribute("loginData");
	$loginProfileid = $this->loginData[PROFILEID];
	$this->loginProfile=LoggedInProfile::getInstance();

	// to check if the current profile's primary number is duplicate or not
	if (JsCommon::showDuplicateNumberConsent($loginProfileid))
		$this->showDuplicateConsentMsg = 'Y' ;
	else 
		$this->showDuplicateConsentMsg = 'N' ;

	//incomplete check
	if ($this->loginProfile->getINCOMPLETE()=='Y') 
		sfContext::getInstance()->getController()->redirect("/register/page2?incompleteUser=1");



   	$phoneVerified = JsMemcache::getInstance()->get($loginProfileid."_PHONE_VERIFIED");
	if(!$phoneVerified)
	{
		$phoneVerified = phoneVerification::hidePhoneVerLayer($this->loginProfile);
		JsMemcache::getInstance()->set($loginProfileid."_PHONE_VERIFIED",$phoneVerified);
	}


  	$fromReg=0;
	if ($request->getParameter('fromReg')==1)
	{
    $groupname = $request->getParameter('groupname');
	if($phoneVerified=='Y') $this->redirect('/register/page6?groupname='.$groupname); 
	$this->requestUri='/register/page6?groupname='.$groupname;
	$fromReg=1;	
	$this->groupname = $request->getParameter('groupname');
	$this->sourcename = $this->loginProfile->getSOURCE();

			//If coming directly from registration, used for google pixel code
		if (trim($request->getParameter('groupname'))) {
			$this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$this->loginProfile);
		}

	} 
	else 
	$this->requestUri=$_SERVER[REQUEST_URI];

	$this->fromReg = $fromReg ;
	$this->username=$this->loginData[USERNAME];
	
	
	ob_start();
	$jsonData = sfContext::getInstance()->getController()->getPresentationFor("phone", "displayV1");
	$output = ob_get_contents();
	ob_end_clean();
	$this->showConsentMsg=$request->getParameter("showConsentMsg");
	$this->apiData=json_decode($output,true);
	$this->apiData[phoneDetails][fromReg]=$this->fromReg;
  }



  	public function executeSMSContactsToMobile(sfWebRequest $request)
	{

	    include_once(JsConstants::$docRoot."/profile/InstantSMS.php");

		$respObj = ApiResponseHandler::getInstance();
		$fail=0;
		 if(!$profileChecksum= $request->getParameter('profileChecksum') ?  $request->getParameter('profileChecksum') : $request->getParameter('profilechecksum'))
			throw new Exception("no profilechecksum passed as parameter in request", 1);
		$selfProfileObj=LoggedInProfile::getInstance();
		$arr=explode("i",$profileChecksum);
		$otherProfileid=$arr[1];
		if(!$selfProfileObj->getPROFILEID() || !$selfProfileObj->getPHONE_MOB() || !$otherProfileid || ($selfProfileObj->getMOB_STATUS())!='Y' )
		{
			$fail=1;
		}

		if($fail!=1)
		{
		$otherProfileObj= new Profile('',$otherProfileid);
		$otherProfileObj->getDetail('','','*');
		
		if(!(new JSADMIN_VIEW_CONTACTS_LOG())->contactViewedOrNot($selfProfileObj->getPROFILEID(),
			$otherProfileObj->getPROFILEID()))
			$fail=1;
		}

   	if($fail!=1)
   	{
   	$response['mobile']='+'.$selfProfileObj->getISD().'-'.$selfProfileObj->getPHONE_MOB();	
	$smsReceiver = new InstantSMS("VIEWED_CONTACT_SMS",$selfProfileObj->getPROFILEID(),'',$otherProfileid);//this is the one whos interest is being accepted
    $smsReceiver->send();	
	}

	if($fail==1)
	{ 
   	$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
   	if(!$selfProfileObj->getPHONE_MOB())
   		$response['SMSError']="Please update your primary mobile number.";
   	else if($selfProfileObj->getMOB_STATUS()!='Y')
   		$response['SMSError']="Please verify your primary mobile number.";
   	else 
   		$response['SMSError']="Error Occured.";

   	}
   	else
   	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	$respObj->setResponseBody($response);
	$respObj->generateResponse();
	die;
		
	}


public function executeSendOtpSMS(sfWebRequest $request)
  {
	$phoneType=$request->getParameter('phoneType');
	$respObj = ApiResponseHandler::getInstance();
  	
  	if($phoneType!='A' && $phoneType!='M' && $phoneType!='L')
  	{
 	$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
	$respObj->generateResponse();
	die;
	}

  	$loggedInProfileObj=LoggedInProfile::getInstance();
  
  	if(!$loggedInProfileObj->getPROFILEID())
  	{
  		$respObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
		$respObj->generateResponse();
		die;
	}


  	$loginData=$request->getAttribute('loginData');
  	$verificationObj=new OTPVerification($loggedInProfileObj,$phoneType);
	$response=$verificationObj->sendOtpSMS();
	$response['phone']=$verificationObj->getPhone();
	$response['phoneType']=$verificationObj->getPhoneType();
	if($response['trialsOver']=='Y')
		$response['trialsOverMessage']=PhoneApiFunctions::$OTPTrialsOverMsg;
	$response['serviceTimeText']=PhoneApiFunctions::$serviceTimeText;
	

		if($request->getParameter('PCLayer')=='Y'){
			$this->response=$response;
			if($response['SMSLimitOver']=='Y')
				$this->smsResend='N';
			else $this->smsResend='Y';

			if($response['trialsOver']=='Y') 
				$this->setTemplate('desktopOtpFailedLayer');
			else 
				$this->setTemplate('desktopOTP');
		
		}

		else {
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody($response);
		$respObj->generateResponse();
		die;

		}
  }


public function executeMatchOtp(sfWebRequest $request)
{
	$respObj = ApiResponseHandler::getInstance();
  	$phoneType=$request->getParameter('phoneType');
  	$enteredOtp=$request->getParameter('enteredOtp');
  	if($phoneType!='A' && $phoneType!='M' && $phoneType!='L')
  	{
 	$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
	$respObj->generateResponse();
	die;
	}

  	$loggedInProfileObj=LoggedInProfile::getInstance();

  	if(!$loggedInProfileObj->getPROFILEID())
  	{
  		$respObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
		$respObj->generateResponse();
		die;
	}
   	
   	$verificationObj=new OTPVerification($loggedInProfileObj,$phoneType);
	
	switch ($verificationObj->matchOtp($enteredOtp))
	{
		case 'Y':	
		$response['matched']='true';
		$response['trialsOver']='N';
		break;

		case 'N':
		$response['matched']='false';
		$response['trialsOver']='N';
		break;

		case 'C':
		$response['matched']='false';
		$response['trialsOver']='Y';
		$response['trialsOverMessage']=PhoneApiFunctions::$OTPTrialsOverMsg;

		break;

		default:
		$response['matched']='false';
		$response['trialsOver']='N';
		break;		
	}
		$response['serviceTimeText']=PhoneApiFunctions::$serviceTimeText;
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$respObj->setResponseBody($response);
		$respObj->generateResponse();
		die;
  }





public function executeDesktopOtpFailedLayer(sfWebRequest $request)
  {

  			$this->setTemplate('desktopOtpFailedLayer');
		
		 }

/*
public function desktopOtpFailedLayer(sfWebRequest $request)
  {

  			$this->setTemplate('desktopOtpFailedLayerSuccess');
		
		 }


public function desktopOtpFailedLayer(sfWebRequest $request)
  {

  			$this->setTemplate('desktopOtpFailedLayerSuccess');
		
		 }

*/







}
