<?php

/**
 * ProfileData actions.
 *
 * @package    jeevansathi
 * @subpackage ProfileData
 * @author     Hemant Agrawal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProfileDataActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public $controller="/operations.php/";
	
	public function preExecute()
	{
		$request=sfContext::getInstance()->getRequest();
		$this->cid=$request->getParameter(cid);
		$this->success=$request->getParameter("success");	
		$this->user=JsOpsCommon::getcidname($this->cid);
		$generatedUrl=($this->generateUrl()!='/')?$this->generateUrl():$this->controller;
		$this->moduleurl=$generatedUrl.$this->getModuleName();
		$this->profileid=$request->getParameter("profileid1");
		

	}
	public function executeAllPdf(sfWebRequest $request)
	{
		$response = $this->getResponse();
		//$site_url=sfConfig::get("app_site_url");
		if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'https://www.jeevansathi.com'){
			$site_url = 'https://crm.jeevansathi.com';
		} else {
			$site_url = JsConstants::$siteUrl;
		}

		$action_call[]=$site_url.$this->controller."profiledata/Login?actiontocall=1&cid=".$this->cid."&profileid1=".$this->profileid;
		$action_call[]=$site_url.$this->controller."profiledata/Logout?actiontocall=1&cid=".$this->cid."&profileid1=".$this->profileid;
		$action_call[]=$site_url.$this->controller."profiledata/ProfileLog?actiontocall=1&cid=".$this->cid."&profileid1=".$this->profileid;
		$action_call[]=$site_url.$this->controller."profiledata/EditLog?actiontocall=1&cid=".$this->cid."&profileid1=".$this->profileid;
		$action_call[]=$site_url.$this->controller."profiledata/EOILog?actiontocall=1&cid=".$this->cid."&profileid1=".$this->profileid;
		$action_call[]=$site_url.$this->controller."profiledata/PaymentLog?actiontocall=1&cid=".$this->cid."&profileid1=".$this->profileid;
		$action_call[]=$site_url.$this->controller."photoScreening/getAlbum?actiontocall=1&cid=".$this->cid."&profileid=".$this->profileid;
		
		$filename=$this->profileid."_getAll.pdf";
		$file=PdfCreation::PdfFile($action_call,1);
		PdfCreation::setResponse($filename,$file);
		return sfView::NONE;
		
	}
	public function executePdf(sfWebRequest $request)
	{
		if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'https://www.jeevansathi.com'){
			$site_url = 'https://crm.jeevansathi.com';
		} else {
			$site_url = JsConstants::$siteUrl;
		}

		$response = $this->getResponse();
		$url=$site_url.$_SERVER[REQUEST_URI];
		$actionName=$request->getParameter('actiontocall');
		$url=str_replace("pdf",$actionName,$url);
		if($actionName=='getAlbum')
			$url=str_replace('profiledata','photoScreening',$url);

		$file=PdfCreation::PdfFile($url);
		$filename=$this->profileid."_".$request->getParameter('actiontocall').".pdf";
		PdfCreation::setResponse($filename,$file);
		
		return sfView::NONE;
	}
        /**
         * This function generates the csv for contact details actions
         * @param sfWebRequest $request
         */
        public function executeCsv(sfWebRequest $request)
	{
                // CSV data headers
                $headerArray = array(
                    "'Logout'" => array(array("Logout Data"),array("IP Address","Time")),
                    'Login' => array(array("Login Data"),array("IP Address","Time")),
                    "EditLog" => array(array("Modification Log : MOD_INDEXVAL"),array("MOD_INDEXVAL","Mod_Date","IP")),
                    "EOILog" => array(array("Contact History"),array("Sender","Receiver","Sender_ProfileId","Receiver_ProfileId","EmailId","Date (IST)","TYPE","IP","MESSAGE","Phone Mobile","Phone Res","Phone Alternate","Contact")),
                    "PaymentLog" => array(array("Payment Data"),array("Bill Id","Payment Mode","Cheque No","Cheque Date(EST)","Cheque City","Bank","IP","Status","Entry Date(EST)*","Service Names","Gateway*","TXN Ref No*","RRN*")),
                );
                
                //get data from respective actions
		$actionName=$request->getParameter('actiontocall');
                ob_start();
                $request->setParameter('useSfViewNone','1');
                $request->setParameter('profileid1',$this->profileid);
                sfContext::getInstance()->getController()->getPresentationFor('profiledata',$actionName);
                $pageResponse = ob_get_contents(); //we can also get output from above command.
                ob_end_clean();
                
                $pageResponse = json_decode($pageResponse);
                if($actionName == 'Login' || $actionName == "'Logout'" || $actionName == "PaymentLog"){
                        foreach($headerArray[$actionName] as $header){
                            $csvData .= implode(',',$header)."\n";
                        }
                        if(!empty($pageResponse)){
                                foreach($pageResponse as $pageData){
                                   $pageData = (array)$pageData;
                                   $csvData .= implode(',',$pageData)."\n";    
                                }
                        }else{
                                $csvData .= "No Record(s) Found";   
                        }
                        if($actionName == "'Logout'"){
                                $actionName = 'Logout';
                        }
                }elseif($actionName == 'EditLog'){
                       $pageResponse = (array)$pageResponse;
                       $csvData = '';
                       if(!empty($pageResponse)){
                                foreach($pageResponse as $indexVal => $pageModData){
                                         $csvHeader = '';
                                         foreach($headerArray[$actionName] as $header){
                                             $csvHeader .= implode(',',$header)."\n";
                                         } 
                                         $csvHeader = str_replace('MOD_INDEXVAL', $indexVal, $csvHeader);
                                         $csvData .= $csvHeader;
                                         if(!empty($pageModData)){
                                                 foreach($pageModData as $pageData){
                                                         $csvData .= implode(',',$pageData)."\n";    
                                                 }
                                         }else{
                                                 $csvData .= "No Record(s) Found";   
                                         }
                                 }
                       }else{
                                $csvData .= "Modification Log:\n";   
                                $csvData .= "No Record(s) Found";   
                       }
                }elseif($actionName == 'EOILog'){
                        $pageResponse = (array)$pageResponse; 
                        foreach($headerArray[$actionName] as $header){
                            $csvData .= implode(',',$header)."\n";
                        }
                        if(!empty($pageResponse)){
                                foreach($pageResponse as $indexVal1 => $pageModData){
                                        $pageModData = (array)$pageModData;
                                        foreach($pageModData as $indexVal2 => $pageData){
                                                $pageData = (array)$pageData;
                                                foreach($pageData as $indexVal3 => $messageDetails){
                                                   $messageDetails = (array)$messageDetails;
                                                   $csvData .= $messageDetails['SENDER'].",";

                                                   $csvData .= $messageDetails['RECEIVER'].",";
                                                   $csvData .= $indexVal1.",";
                                                   $csvData .= $indexVal2.",";
                                                   $csvData .= $messageDetails['EMAIL'].",";
                                                   $csvData .= $messageDetails['DATE'].",";
                                                   $csvData .= $messageDetails['TYPE'].",";
                                                   $csvData .= $messageDetails['IP'].",";
                                                   $csvData .= '"'.$messageDetails['MESSAGE'].'"'.",";
                                                   $csvData .= $messageDetails['PHONE_MOB'].",";
                                                   $csvData .= $messageDetails['PHONE_RES'].",";
                                                   $csvData .= $messageDetails['PHONE_ALT'].",";
                                                   $csvData .= '"'.$messageDetails['CONTACT'].'"'."\n";
                                                }
                                        }
                                }  
                        }else{
                                $csvData .= "No Record(s) Found";   
                        }
                }
                header("Content-Type: application/vnd.csv");
                header("Content-Disposition: attachment; filename=$actionName.csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $csvData;
                die;
	}
	public function executeIndex(sfWebRequest $request)
	{
		
		
	}

	public function executeLinkPage(sfWebRequest $request)
	{
		$this->username=$request->getParameter("username");
		$profileStatus=array("A"=>"Activated","Y"=>"Activated","D"=>"Deleted","H"=>"Hidden","N"=>"Not Activated","U"=>"Under Screening");

		if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'http://www.jeevansathi.com'){
			$this->pdfUrl = 'http://crm.jeevansathi.com'."/operations.php/profiledata";
		} else {
			$this->pdfUrl = JsConstants::$siteUrl."/operations.php/profiledata";
		}
		
		if($this->username)
		{
			$profObj=Operator::getInstance();
			$profObj->getDetail($this->username,"USERNAME","PROFILEID,ACTIVATED,INCOMPLETE");
			$this->profileid = $profObj->getPROFILEID();
			$this->activated=$profileStatus[$profObj->getACTIVATED()];
			if($profObj->getINCOMPLETE()=='Y')
					$this->activated="Incomplete";
			$this->profileid1=$this->profileid;
		}
		
		if($this->profileid)
		{
			$profObj=Operator::getInstance();
			
			$profObj->getDetail($this->profileid,"PROFILEID","USERNAME,ACTIVATED,INCOMPLETE");
			$this->username = $profObj->getUSERNAME();
			$this->activated=$profileStatus[$profObj->getACTIVATED()];
			if($profObj->getINCOMPLETE()=='Y')
					$this->activated="Incomplete";
			//$this->profileid1=$this->profileid;
		}
		else
		{
			$this->error = "Please enter a valid username!";
			$this->setTemplate("index");
		}
		
		
	}
	
	public function executeLogin(sfWebRequest $request)
	{
		$loginObj = new LoginData($this->profileid);
		$this->loginArr = $loginObj->login($this->profileid);
		if($request->getParameter("useSfViewNone") == 1){
                        echo json_encode($this->loginArr);
                        return sfView::NONE;
                        die;
                }
	}
	
	public function executeLogout(sfWebRequest $request)
	{
		$loginObj = new LoginData($this->profileid);
		$this->logoutArr = $loginObj->logout($this->profileid);
                if($request->getParameter("useSfViewNone") == 1){
                        echo json_encode($this->logoutArr);
                        return sfView::NONE;
                        die;
                }
	}
	
	public function executeProfileLog(sfWebRequest $request)
	{

		// Get Profile Data
		$nameObj = new incentive_NAME_OF_USER();
		$this->nameofuser = $nameObj->getName($this->profileid);	
		$this->profile=Operator::getInstance();
		$this->profileDetailArr = $this->profile->getDetail($this->profileid,"PROFILEID","PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,CONVERT_TZ(MOD_DT,'SYSTEM','right/Asia/Calcutta') as MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,CONVERT_TZ(SUBSCRIPTION_EXPIRY_DT,'SYSTEM','right/Asia/Calcutta') as SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,CONVERT_TZ(LAST_LOGIN_DT,'SYSTEM','right/Asia/Calcutta') as LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,CONVERT_TZ(SORT_DT,'SYSTEM','right/Asia/Calcutta') as SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN,CONVERT_TZ(ENTRY_DT,'SYSTEM','right/Asia/Calcutta') as ENTRY_DT");		
		
		foreach($this->profileDetailArr as $key=>$val)
		{
			if($key == 'CITY_RES')
				$val=FieldMap::getFieldLabel("city_india", $val);
			else if($key == 'COUNTRY_RES')
				$val=FieldMap::getFieldLabel("country", $val);
			else if($key == "EMAIL")
			{
					if(strpos($val,"_deleted") !== false)
					{
						$val = substr($val,0,strpos($val,"_deleted"));
					}
					$deleteRemovedEmail = $val;
			}
			if(!$val)
				$val="Not present";
			$this->profileDetailArr[$key] = $val;
		}
		$profObj1 = new ProfileInfo1();
		$deletedProfileArr = $profObj1->profileDelete($this->profileid);
		$this->deletedArr = $deletedProfileArr[0];
		$this->retrievedArr = $deletedProfileArr[1];
		//print_r($this->deletedArr);die;
		//Get Duplicate profiles
		$dupObj = new ProfileDuplicates($this->profile);
		$this->duplicateArr = $dupObj->duplicateProfiles();

//		var_dump($this->profile->getJpartner());die;
		$profObj1->setPageInformation($this,$this->profile);
		$this->EMAIL=$deleteRemovedEmail;
		//Getting partner details of viewer
		$jpartnerObj=$profObj1->getDpp($this->profile->getPROFILEID());
		$dbObj = new ProfileContact("newjs_slave");
		$mobArr = $dbObj->getProfileContacts($this->profileid);
		$this->AltMob = $mobArr['ALT_MOBILE'];
		
		//Getting loginned profile desired partner data and setting object as well.
		$this->profile->setJpartner($jpartnerObj);
		//var_dump($this->profile->getJpartner()->getDecoratedLAGE());die;
	}
	
	public function executeEOILog(sfWebRequest $request)
	{
		//Get EOI data
		$EOIObj = new EOIData($this->profileid);
		$this->EOIArr = $EOIObj->getEOIData($this->profileid);
                if($request->getParameter("useSfViewNone") == 1){
                        echo json_encode($this->EOIArr);
                        return sfView::NONE;
                        die;
                }
	}
	
	public function executeEditLog(sfWebRequest $request)
	{
	   
		//Get Edit logs of the profile ID
		$profObj=new ProfileInfo1($this->profileid);
	
		$this->modArr=$profObj->getAllModified();
		if($request->getParameter("useSfViewNone") == 1){
                        echo json_encode($this->modArr);
                        return sfView::NONE;
                        die;
                }		
	}
	
	public function executePaymentLog(sfWebRequest $request)
	{
	   //Get Payment History
		$paymentObj = new Payment();
		$this->paymentArr = $paymentObj->paymentDetails($this->profileid);
                if($request->getParameter("useSfViewNone") == 1){
                        echo json_encode($this->paymentArr);
                        return sfView::NONE;
                        die;
                }
	}
		
	/**
   * 
   * @param sfWebRequest $request
   * @return type
   */ 
  public function executeChatDetail(sfWebRequest $request)
	{
		$objChatDetail = new ChatDetail($this->profileid);
    $this->arrDetails = $objChatDetail->getDetails();

    if ($request->getParameter("useSfViewNone") == 1) {
      echo json_encode($this->arrDetails);
      return sfView::NONE;
      die;
    }
  }
}
