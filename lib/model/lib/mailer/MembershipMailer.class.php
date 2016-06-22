<?php 
class MembershipMailer {
    
    public function sendMembershipMailer($mailid, $profileid, $dataArr=''){

        $mailerServiceObj = new MailerService();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
	$mailerLinks = $mailerServiceObj->getLinks();

        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();
        $smartyObj->assign("mailerLinks",$mailerLinks);

        if(is_array($dataArr))
                $smartyObj =$this->setSmartyParams($mailid,$smartyObj,$mailerServiceObj, $dataArr);

        $email_sender->send();
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
					        if(!empty($currency)){
			        			$smartyObj->assign('currency',$currency);
			        		}
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
            $loginDtEnd = date('Y-m-d');
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
    
    //commented as general func "sendServiceActivationMail" used in its place
    /*function sendWeTalkForYouUsageMail($mailId, $profileDetails){
        $username = $profileDetails["USERNAME"];
        $profileid = $profileDetails["PROFILEID"];
        $mailerServiceObj = new MailerService();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
        $mailerLinks = $mailerServiceObj->getLinks();
        $email_sender = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, $mailId);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();         
        $smartyObj->assign("mailerLinks",$mailerLinks);
        $smartyObj->assign("USERNAME",$username);
        $email_sender->send();
    }*/
   
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

    //send RB activation mail
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
        $email_sender->send();
    }   		

    public function sendWelcomeMailerToPaidUser($mailid, $profileid, $attachment, $services){

        $mailerServiceObj = new MailerService();
        sfProjectConfiguration::getActive()->loadHelpers("Partial","global/mailerfooter");
		$mailerLinks = $mailerServiceObj->getLinks();
		$memHandlerObj = new MembershipHandler();
		$services = explode(",",$services);
		$servMain = NULL;
		$vasNames = array();
		
		foreach($services as $keyMain=>$valMain){
			$tempId = $memHandlerObj->retrieveCorrectMemID($valMain);
			$benefitMsg = VariableParams::$newApiPageOneBenefits;
        	$benefitArr = VariableParams::$newApiPageOneBenefitsVisibility;
			$vasArr = VariableParams::$newApiVasNamesAndDescription;			
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
        } else {
        	$subject = implode(', ', $vasNames) . " activated on your account";
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
        return $deliveryStatus;

    }

}

?>
