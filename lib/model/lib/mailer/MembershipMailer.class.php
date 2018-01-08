<?php 
class MembershipMailer {
    
    public function sendMembershipMailer($mailid, $profileid, $dataArr='',$attachment='',$attachmentName=''){

        $mailerServiceObj = new MailerService();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
	    $mailerLinks = $mailerServiceObj->getLinks();

        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();
        $smartyObj->assign("mailerLinks",$mailerLinks);
        $protect_obj = new protect;
        $profilechecksum = md5($profileid)."i".$profileid;
        $profileObj = LoggedInProfile::getInstance('newjs_slave',$profileid);
        $echecksum = $protect_obj->js_encrypt($profilechecksum,$profileObj->getEMAIL());
        $autoLoginLink = JsConstants::$siteUrl."/membership/jspc?CMGFRMMMMJS=1&checksum=$profilechecksum&profilechecksum=$profilechecksum&echecksum=$echecksum&enable_auto_loggedin=1&from_source=FP_RB_PROMO_MAILER";
        $smartyObj->assign("membershipAutoLoginLink", $autoLoginLink);

        if(is_array($dataArr))
                $smartyObj =$this->setSmartyParams($mailid,$smartyObj,$mailerServiceObj, $dataArr);

	if($attachment){	
		$email_sender->setAttachment($attachment);
		$email_sender->setAttachmentName($attachmentName);
		$email_sender->setAttachmentType('application/octet-stream');
	}
        // if($mailid == '1836'){
        //     $email_sender->send('','','rupali.srivastava@jeevansathi.com');
        // } else {
            $email_sender->send();
        // }
        $deliveryStatus =$email_sender->getEmailDeliveryStatus();
        return $deliveryStatus;

    }
    private function setSmartyParams($mailid, $smartyObj, $mailerServiceObj, $dataArr='')
    {
        $mailerTypeArr =VariableParams::$membershipMailerArr;
        $mailerType =$mailerTypeArr[$mailid];

        switch($mailerType){

                case 'REGISTRATION_BASED':
                        $priceArr       =$dataArr['priceArr'];
                        $RequestCallbackURL = JsConstants::$siteUrl."/membership/jsexclusiveDetail#placeRequestForm";
                        if($priceArr!='') {
                                $smartyObj->assign("currency",$priceArr['CUR']);
                                $smartyObj->assign("eRishta",$priceArr[0]);
                                $smartyObj->assign("eValue",$priceArr[1]);
                                $smartyObj->assign("eValuePlus",$priceArr[2]);
                        }
                        $smartyObj->assign("RequestCallbackURL",$RequestCallbackURL);
                        $smartyObj->assign("membershipMsg",$dataArr['membershipMsg']);
                        break;
                case 'VD':
                        $discountEndDate=date("j-S-F-Y",strtotime($dataArr['eDate']));
                        $datesParamArr =explode("-",$discountEndDate);
                        $mailerLinks = $mailerServiceObj->getLinks();
                        $unsubscribeLink = $mailerLinks['UNSUBSCRIBE'];
                        $smartyObj->assign("day",$datesParamArr[0]);
                        $smartyObj->assign("suffix",$datesParamArr[1]);
                        $smartyObj->assign("month",$datesParamArr[2]);
                        $smartyObj->assign("year",$datesParamArr[3]);
                        $smartyObj->assign("discount",$dataArr['discount']);
                        $smartyObj->assign("profileid",$dataArr['profileid']);
                        $smartyObj->assign("vdDisplayText",$dataArr['vdDisplayText']);
                        $smartyObj->assign("fromEmailId","membership@jeevansathi.com");
                        $smartyObj->assign("unsubscribeLink",$unsubscribeLink);
                        $smartyObj->assign("instanceID",$dataArr['instanceID']);
						break;
		case 'NEW_MEMBERSHIP_PAYMENT':
                        $mailerLinks = $mailerServiceObj->getLinks();
                        $unsubscribeLink = $mailerLinks['UNSUBSCRIBE'];
                        $smartyObj->assign("profileid",$dataArr['profileid']);
                        $smartyObj->assign("PROFILEID",$dataArr['profileid']);
                        $smartyObj->assign("fromEmailId","membership@jeevansathi.com");
                        $smartyObj->assign("unsubscribeLink",$unsubscribeLink);
                        $smartyObj->assign("benefits",$dataArr['benefits']);
                        $smartyObj->assign("servMain",$dataArr['servMain']);
                        $smartyObj->assign("username",$dataArr['username']);
                        $smartyObj->assign("dppLink",$mailerLinks['MY_DPP']);
			break;
		case 'JS_EXCLUSIVE_FEEDBACK':
			$currency =$dataArr['currency'];
                        $username = $dataArr['username'];
                        
                        if(!empty($username)) {
                            $smartyObj->assign('username', $username);
                        }
                        
		        if(!empty($currency)){
        			$smartyObj->assign('currency',$currency);
        		}
			break;
                case 'MEM_EXPIRY_CONTACTS_VIEWED':
                        $mailerLinks = $mailerServiceObj->getLinks();
                        $unsubscribeLink = $mailerLinks['UNSUBSCRIBE'];
                        $smartyObj->assign("profileid",$dataArr['profileid']);
                        $smartyObj->assign("PROFILEID",$dataArr['profileid']);
                        $smartyObj->assign("unsubscribeLink",$unsubscribeLink);
                        break;
                default:
			break;
        }
        return $smartyObj;
    }
    public static function sendEmailAfterRegistration($mailid, $profileid, $membershipMsg, $priceArr=''){

        $mailerServiceObj = new MailerService();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
        $mailerLinks = $mailerServiceObj->getLinks();

        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);

        $smartyObj = $emailTpl->getSmarty();
        $smartyObj->assign("membershipMsg",$membershipMsg);
        
        //Request to call back link in mailer
        $RequestCallbackURL = JsConstants::$siteUrl."/membership/jsexclusiveDetail#placeRequestForm";
        if($priceArr!='') {
            $smartyObj->assign("currency",$priceArr['CUR']);
            $smartyObj->assign("eRishta",$priceArr[0]);
            $smartyObj->assign("eValue",$priceArr[1]);
            $smartyObj->assign("eValuePlus",$priceArr[2]);
        } 
        $smartyObj->assign("RequestCallbackURL",$RequestCallbackURL);
        $smartyObj->assign("mailerLinks",$mailerLinks);
        $email_sender->send();
    }

    /* Code to be removed
    public static function sendEmailForMonthlyFeedback($mailid, $profileid){

        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();
        $email_sender->send();
    }
    public static function sendEmailForFPRBPromotion($mailid, $profileid){

        $mailerServiceObj = new MailerService();
        $mailerLinks = $mailerServiceObj->getLinks();

        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);

        $smartyObj = $emailTpl->getSmarty();         
        $smartyObj->assign("mailerLinks",$mailerLinks);

        $email_sender->send();
    }
    */

    /**
       * Function to send generate auto-login link for payment for given service
       *
       * @param   $paymentDetailsArr
       * @return  $URL
       */ 
    private function generateAutoLoginLinkForPayment($paymentDetailsArr)
    {
        //Update details in PAYMENT_COLLECT to get last inserted row id for link generation
        $paymentObj=new incentive_PAYMENT_COLLECT();
        $req_id= $paymentObj->updatePaymentDetails($paymentDetailsArr); 
        $SITE_URL=JsConstants::$siteUrl;        
        $req_id1=md5($req_id).i.$req_id;
        // extendedTime signifies that mailer link is valid for 3 days
        $URL="$SITE_URL/profile/membership_redirect.php?id=$req_id1&extendedTime=3";
        return $URL;
    } 

    /**
       * Function to add entry for sent mail into table BACKEND_LINK_MAILER
       *
       * @param   $mailArr
       * @return  none
       */ 
    private function logSentMailDetails($mailArr)
    {
        $mailObj=new incentive_BACKEND_LINK_MAILER();
        $mailObj->addSentMailDetails($mailArr);  
    }  
    
    /**
       * Function to send email to selected profile users for 1 month offer plan for given service
       *
       * @param   $mailid,$profileDetails,$main_service,$servicePriceArr
       * @return  none
       */ 
    public function sendServiceBasedEmail($mailid, $profileDetails,$main_service,$servicePriceArr, $device='desktop')
    {
        $profileid = $profileDetails['PROFILEID'];
        $email= $profileDetails['EMAIL'];
        $countryRes = $profileDetails['COUNTRY_RES'];
        $username = $profileDetails['USERNAME'];
        if($countryRes=='51')
        {
            $currencyType='RS';
            $price=$servicePriceArr['PRICE_RS_TAX']; 
        }          
        else
        {
            $currencyType='DOL';
            $price=$servicePriceArr['PRICE_DOL']; 
        }  

        $paymentDetailsArr=array("PROFILEID"=>$profileid,"USERNAME"=>$username,"EMAIL"=>$email,"BYUSER"=>'Y',"CONFIRM"=>'N',
                             "ENTRY_DT"=>date('Y-m-d H:i:s'),"ENTRYBY"=>'',"SERVICE"=>$main_service,"ADDON_SERVICEID"=>'',"DISPLAY"=>'N',
                             "PICKUP_TYPE"=>'ONLINE',"REQ_DT"=>date('Y-m-d H:i:s'));     

        //Link generation      
        $URL = $this->generateAutoLoginLinkForPayment($paymentDetailsArr);
                
        //send e-mail 
        $mailerServiceObj = new MailerService();
        $mailerLinks = $mailerServiceObj->getLinks();
        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();         
        $smartyObj->assign("mailerLinks",$mailerLinks);
        $smartyObj->assign("PRICE",$price);
        $smartyObj->assign("CURRENCYTYPE",$currencyType);
        $smartyObj->assign("URL",$URL);
        $email_sender->send();

        //add entry for sent mail in table BACKEND_LINK_MAILER
        $mailDetailsArr=array('PROFILEID'=>$profileid,'SENT_DATE'=>date('Y-m-d'));
        $this->logSentMailDetails($mailDetailsArr);
    }   

    public function isExclusive($profileid) {
        $ssObj = new BILLING_SERVICE_STATUS('newjs_slave');
        return($ssObj->isExclusiveActive($profileid));
    }

    public function isFeaturedProfile($profileid) {
        $ssObj = new BILLING_SERVICE_STATUS();
        return ($ssObj->isFeaturedProfileActive($profileid));
    }

    public function isResponseBooster($profileid) {
        $ssObj = new BILLING_SERVICE_STATUS();
        return ($ssObj->isResponseBoosterActive($profileid));
    }

    public function getAllPaidProfiles($purchase_dt) {
        $pObj = new BILLING_PURCHASES('newjs_slave');
        return($pObj->getPaidProfiles($purchase_dt));
    }
    public function getJsExclusiveProfiles() {
        $serviceStatusObj = new BILLING_SERVICE_STATUS('newjs_slave');
        $profiles =$serviceStatusObj->getProfilesServiceBased('X');
	return $profiles;
    }

   /**
   * Function to fetch profiles of users satisfying offer plan based conditions: 
   *
   * @param  $lastLoginOffset,$lastRegistrationOffset,$neverPaidFlag,$requestCallbackFlag,$acceptanceLowerLimit
   * @return array of profiles: $profileDetailsArr
   */
    public function fetchOfferConditionsBasedProfiles($lastLoginOffset,$lastRegistrationOffset,$neverPaidFlag,$requestCallbackFlag='',$acceptanceLowerLimit='') 
    {
        $jprofileObj = new JPROFILE('newjs_slave');
        if($requestCallbackFlag=='')
            $profileDetailsArr = $jprofileObj->fetchProfilesConditionBased($lastLoginOffset,$lastRegistrationOffset);      
        else
        {
            $logindDtStart = date('Y-m-d',strtotime(date('Y-m-d') . $lastLoginOffset));
            $loginDtEnd = date('Y-m-d H:i:s');
            $profileDetailsArr = $jprofileObj->getLoggedInProfilesForDateRange($logindDtStart, $loginDtEnd);   
        }
        if($neverPaidFlag==true)
        {
            $billingObj = new BILLING_PURCHASES("newjs_slave");
            $diffProfileIDArr = array();
            $smsSentProfileArr = array();
            $neverPaidProfileIDArr = array();
            $everPaidProfileIDArr=$billingObj->fetchEverPaidPool(); 
            $profileIDArr=array_map(function ($arr) { return $arr['PROFILEID']; }, $profileDetailsArr);     
            $neverPaidProfileIDArr=array_diff($profileIDArr, $everPaidProfileIDArr);
            unset($everPaidProfileIDArr);
            unset($profileIDArr);
            if($requestCallbackFlag!='')
            {
                $smsSentProfileArr = $this->filterProfiles($neverPaidProfileIDArr);
                if(count($smsSentProfileArr)>0)
                    $diffProfileIDArr = array_diff($neverPaidProfileIDArr, $smsSentProfileArr);   
                else
                    $diffProfileIDArr = $neverPaidProfileIDArr;
            }
            else
                $diffProfileIDArr = $neverPaidProfileIDArr;
            unset($smsSentProfileArr);
            unset($neverPaidProfileIDArr);
            $diffProfileIDArr =array_values($diffProfileIDArr);
            foreach($profileDetailsArr as $key=>$val)
            {
                $pid = $val["PROFILEID"];
                $profileIdIndexedArray_temp1[$pid]= $val;
            }
	        unset($profileDetailsArr);	
            foreach($diffProfileIDArr as $key=>$pid)
            {
		        $profileIdIndexedArray[$pid] =$profileIdIndexedArray_temp1[$pid];
                //if(!in_array($row['PROFILEID'], $diffProfileIDArr))
                //    unset($profileDetailsArr[$key]);
                if($requestCallbackFlag!='')
                {
                    $dbName = JsDbSharding::getShardNo($pid,"slave");
                    $contactsObj = new newjs_CONTACTS($dbName);
                    $count = $contactsObj->getContactAcceptanceCount($pid);
                    if($count < $acceptanceLowerLimit)
                    {
                        unset($profileIdIndexedArray[$pid]);
                    }
                    else{   
                        $profileIdIndexedArray[$pid]["acceptanceCount"]=$count;
                    }
                }
            }
            unset($diffProfileIDArr);
	        unset($profileIdIndexedArray_temp1);
    
        }
        return $profileIdIndexedArray; 
     
    }
       
    public function getJsExclusiveMessage($profileid) {

        $memHandlerObj = new MembershipHandler();
        $userObj=new memUser($profileid);
        list($ipAddress,$currency) = $memHandlerObj->getUserIPandCurrency();
        $userObj->setMemStatus();
        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($currency);
        list($discountType,$discountActive,$discount_expiry,$discountPercent,$specialActive,$variable_discount_expiry,$discountSpecial,$fest,$festEndDt,$festDurBanner,$renewalPercent,$renewalActive,$expiry_date,$discPerc,$code) = $memHandlerObj->getUserDiscountDetailsArray($userObj);

        if(!$fest && $discPerc){
	    $vdodObj = new VariableDiscount();	
            if($specialActive == 1) {
		$vdDisplayText = $vdodObj->getVdDisplayText($profileid,'cap');
                $msg = "Buy before ".date("jS M",strtotime($expiry_date))." & get <strong>".$vdDisplayText." ".$discPerc."% Discount</strong>";
            } else {
		$discountDisplayText =$vdodObj->getCashDiscountDispText($profileid,'cap');
                $msg = "Buy before ".date("jS M",strtotime($expiry_date))." & get <strong>".$discountDisplayText." ".$discPerc."% Discount</strong>";
            }
        }
        return $msg;
    }

    public function getMembershipDurationsAndPrices($profileid,$userCur = 'RS') {
        $memHandlerObj = new MembershipHandler();
        $userObj=new memUser($profileid);
        list($ipAddress,$currency) = $memHandlerObj->getUserIPandCurrency();
        $userObj->setMemStatus();
        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($userCur);

        list($allMainMem, $minPriceArr) = $memHandlerObj->getMembershipDurationsAndPrices($userObj,'',1);        
        
        $eRishta = $minPriceArr['P']['OFFER_PRICE'];
        $eValue = $minPriceArr['C']['OFFER_PRICE'];
        $eValuePlus = $minPriceArr['NCP']['OFFER_PRICE'];
        
        return array($eRishta, $eValue, $eValuePlus);
    }
    
    function filterProfiles($profileArray=array())
    {
        $smsSentProfileIDArr = array();
        $filterObj = new billing_SMS_REQUEST_CALLBACK("newjs_slave");
        $smsDate = date('Y-m-d',strtotime($row["SMS_DATE"] . '- 3 month'));
        $smsSentProfileIDArr = $filterObj->getFilterdProfiles($profileArray,$smsDate);
        return $smsSentProfileIDArr;
    }
   
    // function to send VD Mailer	
    function sendVdMailer($mailId, $profileid, $discount, $expiryDate, $vdDisplayText, $instanceID){
	
        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailId);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();

        $discountEndDate=date("j-S-F-Y",strtotime($expiryDate));
        $datesParamArr =explode("-",$discountEndDate);
        $mailerServiceObj = new MailerService();
        $mailerLinks = $mailerServiceObj->getLinks();
        $unsubscribeLink = $mailerLinks['UNSUBSCRIBE'];
        $smartyObj->assign("day",$datesParamArr[0]);
        $smartyObj->assign("suffix",$datesParamArr[1]);
        $smartyObj->assign("month",$datesParamArr[2]);
        $smartyObj->assign("year",$datesParamArr[3]);
	$smartyObj->assign("discount",$discount);
	$smartyObj->assign("profileid",$profileid);
	$smartyObj->assign("vdDisplayText",$vdDisplayText);
    	$smartyObj->assign("fromEmailId","membership@jeevansathi.com");
    	$smartyObj->assign("unsubscribeLink",$unsubscribeLink);    
	$smartyObj->assign("instanceID",$instanceID);
        $email_sender->send();
        $deliveryStatus =$email_sender->getEmailDeliveryStatus();
        return $deliveryStatus;
    } 

    /*send service activation mail
    * @params: $mailId, $profileDetails
    * @return: none
    */
    function sendServiceActivationMail($mailId, $profileDetails){
        //$username = $profileDetails["USERNAME"];
        $profileid = $profileDetails["PROFILEID"];
        $mailerServiceObj = new MailerService();
        $mailerLinks = $mailerServiceObj->getLinks();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailId);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();         
        $smartyObj->assign("mailerLinks",$mailerLinks);
        foreach ($profileDetails as $key => $value) {
            $smartyObj->assign($key,$value);
        }  
        $ccList = '';
        if($profileDetails["CC_EMAIL"]){
            $ccList = $profileDetails["CC_EMAIL"]; //mail copy sent to this email
        }
        $status = $email_sender->send('','',$ccList);
        return $status;
    }   		
    function getContactsViewedList($profileid,$startDate,$endDate){
	
	$contactViewObj 	=new JSADMIN_VIEW_CONTACTS_LOG('newjs_local111');
	$profileViewedArr 	=$contactViewObj->countContactsViewForDates($profileid,$startDate,$endDate);

	if(is_array($profileViewedArr)){
		foreach($profileViewedArr as $key=>$data){
			$profileidArr[] 	=$key;
			$detailsArr[$key] 	=$data['DATE'];
		}
		$profileStr =implode(",", $profileidArr);

		// jprofile details
		$hiddenContact ='Contact hidden';
		$jprofileObj =new JPROFILE('newjs_local111');
		$fields ='PROFILEID,GENDER,USERNAME,EMAIL,PHONE_OWNER_NAME,MOBILE_OWNER_NAME,PHONE_MOB,PHONE_WITH_STD,MOBILE_NUMBER_OWNER,PHONE_NUMBER_OWNER,SHOWPHONE_RES,SHOWPHONE_MOB';
		$valueArray['PROFILEID'] =$profileStr;
		$excludeArray =array("ACTIVATED"=>"'D'");
		$resDetails =$jprofileObj->getArray($valueArray,$excludeArray,'',$fields);	

		// jprofile Contact
	        $jprofileContactObj    = new ProfileContact('newjs_local111');
        	$valueArr['PROFILEID']  =$profileStr;
        	$result                 =$jprofileContactObj->getArray($valueArr,'','','PROFILEID,ALT_MOBILE,ALT_MOBILE_OWNER_NAME,ALT_MOBILE_NUMBER_OWNER,SHOWALT_MOBILE');
		if(is_array($result)){
			foreach($result as $key=>$val){
				$pid =$val['PROFILEID'];
				$altContactArr[$pid]['ALT_MOBILE'] =$val['ALT_MOBILE'];
				$altContactArr[$pid]['ALT_OWNER_NAME'] =$val['ALT_MOBILE_OWNER_NAME'];
				$altContactArr[$pid]['ALT_MOBILE_NUMBER_OWNER'] =$val['ALT_MOBILE_NUMBER_OWNER'];
				$altContactArr[$pid]['SHOWALT_MOBILE'] =$val['SHOWALT_MOBILE'];
			}
		}
		// data formatting
		$id=0;
		foreach($resDetails as $key=>$dataArr){
			$pid 				=$dataArr['PROFILEID'];
			$viewedDate			=$detailsArr[$pid];
			$gender				=$dataArr['GENDER'];
			if($gender=='M')
				$relation		='number_owner_male';
			else
				$relation		='number_owner_female';	

			$showMob			=$dataArr['SHOWPHONE_MOB'];	
			$showPhone			=$dataArr['SHOWPHONE_RES'];
			$showAlt			=$altContactArr[$pid]['SHOWALT_MOBILE'];
	
			$dataSet[$id]['USERNAME']	=$dataArr['USERNAME'];
			$dataSet[$id]['VIEWED_DATE'] 	=date("d/m/Y", strtotime($viewedDate));
			$phoneMob			=$dataArr['PHONE_MOB'];
			$phoneLandline			=$dataArr['PHONE_WITH_STD'];
			$phoneAlt			=$altContactArr[$pid]['ALT_MOBILE'];
			if($showMob=='Y' && $phoneMob){
				$relationMob		=FieldMap::getFieldLabel($relation,$dataArr['MOBILE_NUMBER_OWNER']);
				$mobileArr      	=array($phoneMob,$dataArr['MOBILE_OWNER_NAME'],$relationMob);
				$mobileArrNew           =array_filter($mobileArr);
				$mobileData		=implode(",", $mobileArrNew);
			}
			elseif($showMob!='Y' && $phoneMob){
				$mobileData		=$hiddenContact;
			}
			else	
				$mobileData		='';
			if($showPhone=='Y' && $phoneLandline){
				$relationLandline  	=FieldMap::getFieldLabel($relation,$dataArr['PHONE_NUMBER_OWNER']);
				$landlineArr            =array($phoneLandline,$dataArr['PHONE_OWNER_NAME'],$relationLandline);
				$landlineArrNew         =array_filter($landlineArr);
				$landlineData		=implode(",", $landlineArrNew);
			}
			elseif($showPhone!='Y' && $phoneLandline){
				$landlineData		=$hiddenContact;
			}
			else
				$landlineData		='';
			if($showAlt=='Y' && $phoneAlt){
				$relationAlt            =FieldMap::getFieldLabel($relation,$altContactArr[$pid]['ALT_MOBILE_NUMBER_OWNER']);
				$alternateArr          =array($phoneAlt,$altContactArr[$pid]['ALT_OWNER_NAME'],$relationAlt);
				$alternateArrNew       	=array_filter($alternateArr);
				$altData		=implode(",", $alternateArrNew);	
			}
			elseif($showAlt!='Y' && $phoneAlt){
				$altData		=$hiddenContact;	
			}
			else
				$altData		='';
                        $dataSet[$id]['MOBILE']         =$mobileData;
                        $dataSet[$id]['LANDLINE']       =$landlineData;
                        $dataSet[$id]['ALT']            =$altData;
			$dataSet[$id]['EMAIL']          =$dataArr['EMAIL'];	
			$id++;
		}
		return $dataSet;
	}
	return;
    }
    public function getExcelData($data,$dataHeader){
        $retval = "";
        if (is_array($data)  && !empty($data)){
                $row = 0;
                foreach(array_values($data) as $_data){
                        if (is_array($_data) && !empty($_data)){
                                foreach($dataHeader as $key1=>$val1){
                                        if($row==0)
                                                $headerVal[] =$val1;

                                        $values[] =$_data[$key1];
                                }
                                if($row==0){
                                        $retval =implode("\t",$headerVal);
                                        $retval .= "\n\n";
                                }
                                $retval .=implode("\t",$values);
                                $retval .= "\n";
                                unset($values);
                                //increment the row so we don't create headers all over again
                                $row++;
                        }
                }
        }
        return $retval;
    }
    public function getCsvData($data, $dataHeader)
    {
        $retval  = "";
        $filepath = "/var/www/html/web/uploads/csv_files/";
        $filename = $filepath."tempCsvDataMemMailerContent.csv";
        unlink($filename);
        $csvData = fopen("$filename", "w") or print_r("Cannot Open");
        fputcsv($csvData, array_values($dataHeader));
        foreach($dataHeader as $key=>$val) {
            $blankRow[] = "";
        }
        fputcsv($csvData, array_values($blankRow));
        foreach ($data as $key => &$val) {
            fputcsv($csvData, array_values($val));
        }
        fclose($csvData);
        $csvAttachment = file_get_contents($filename);
        unlink($filename);
        return $csvAttachment;
    }
    public function sendWelcomeMailerToPaidUser($mailid, $profileid, $attachment, $services){

        $mailerServiceObj = new MailerService();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
		$mailerLinks = $mailerServiceObj->getLinks();
		$memHandlerObj = new MembershipHandler();
		$services = explode(",",$services);
		$servMain = NULL;
		$vasNames = array();
		$astroFlag=0;
		foreach($services as $keyMain=>$valMain){
			$tempId = $memHandlerObj->retrieveCorrectMemID($valMain);
			$benefitMsg = VariableParams::$newApiPageOneBenefits;
        	$benefitArr = VariableParams::$newApiPageOneBenefitsVisibility;
			$vasArr = VariableParams::$newApiVasNamesAndDescription;
			if($tempId=="A"){
			    $astroFlag=1;
			}
			if ($tempId == "X") {
				$servMain = $tempId;
            	$benefits = VariableParams::$newApiPageOneBenefitsJSX;
        	} else {
        		if ($tempId == "P" || $tempId == "C" || $tempId == "D" || strstr($tempId, "ES") || strstr($tempId, "NCP")){
        			$servMain = $tempId;
        		}
        		foreach ($benefitArr as $key => $value) {
	                if ($key == $tempId) {
	                    foreach ($value as $kk => $vv) {
	                        if ($vv == 1) {
	                            $benefits[$kk] = $benefitMsg[$kk];
	                        }
	                    }
	                }
				}
        	}
        	if(in_array($tempId,array_keys($vasArr))){
				$vasNames[] = $vasArr[$tempId]['name'];
			}
			unset($tempId);
		}
		if (empty($benefits)) {
			$benefits = array();
		}
		if (empty($vasNames)) {
			$vasNames = array();
		}
		$currentBenefitsMessages = array_values(array_merge($benefits , $vasNames));
        $dataArr['benefits'] = $currentBenefitsMessages;
        $dataArr['servMain'] = $memHandlerObj->getUserServiceName($servMain);
        $profileDetails = $memHandlerObj->getUserData($profileid);
        $dataArr['username'] = $profileDetails['USERNAME'];
        $dataArr['profileid'] = $profileid;
        
        if(!empty($servMain)){
        	$subject = "Congratulations! We welcome you as an " . $memHandlerObj->getUserServiceName($servMain) . " member on Jeevansathi";
        }
       
        
        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();
        $emailTpl->setSubject($subject);
        $smartyObj->assign("mailerLinks",$mailerLinks);
        
        if (is_array($dataArr)) {
            $smartyObj =$this->setSmartyParams($mailid,$smartyObj,$mailerServiceObj,$dataArr);
        }
        if ($attachment) {
        	$email_sender->setAttachment($attachment);
        	$email_sender->setAttachmentName("Jeevansathi-Invoice.pdf");
        	$email_sender->setAttachmentType('application/pdf');
        }
        
        $email_sender->send();
        $deliveryStatus =$email_sender->getEmailDeliveryStatus();
        if($astroFlag){
            $mailerSubject= " Congratulations on purchasing Astro compatibility. Important information you need to know";

            $mailer = new EmailSender(MailerGroup::ASTRO_COMPATIBILTY, 1839);
            $mailerTpl = $mailer->setProfileId($profileid);
             $obj = $mailerTpl->getSmarty();
             $mailerTpl->setSubject($mailerSubject);
             $obj->assign("mailerLinks",$mailerLinks);
             $obj->assign("profileid",$profileid);
             $mailer->send();
        }
        return $deliveryStatus;

    }

    /*sendExclusiveServiceIIMailer
    * function to send exclusive servicing phase II mailer
    * @params: $profileDetails
    * @return :$mailSent(1/0)
    */
    public function sendExclusiveServiceIIMailer($profileDetails){
        $mailId = '1837';
        $mailSent = 0;
        if(is_array($profileDetails) && is_array($profileDetails["usernameListArr"])){
            $stype = SearchTypesEnums::EXCLUSIVE_SERVICE2_MAILER_STYPE;
            $rtype = JSTrackingPageType::EXCLUSIVE_SERVICE2_MAILER_RTYPE;
            //print_r($profileDetails["usernameListArr"]);die;
            foreach ($profileDetails["usernameListArr"] as $key => $username) {
                if($username){
                    //validate profile in username list
                    $otherProfileObj = new Operator;
                    $otherProfileObj->getDetail($username,"USERNAME",'PROFILEID');
                    $otherPid = $otherProfileObj->getPROFILEID();
                    unset($otherProfileObj);
                    //map profileid to view profile links
                    if($otherPid){
                        $profilePageLinkArr[$username] = JsConstants::$siteUrl."/profile/viewprofile.php?profilechecksum=".JsAuthentication::jsEncryptProfilechecksum($otherPid)."&stype=".$stype."&responseTracking=".$rtype;
                    }
                }
            }
            unset($profileDetails["usernameListArr"]);
        }
        if($profilePageLinkArr && is_array($profilePageLinkArr) && count($profilePageLinkArr)>0){
            $profileDetails["USERNAMELIST"] = $profilePageLinkArr;
            $profileDetails["CURR_MAIL_DATE"] = date("d-M-Y");
            $mailSent = 1;
            $profileDetails["CC_EMAIL"] = $profileDetails["SENDER_EMAIL"];
            $this->sendServiceActivationMail($mailId, $profileDetails);
        }
        return $mailSent;
    }

}

?>
