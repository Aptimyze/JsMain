<?php
/*This class is used to handle functionalities related to variable discount */
class VariableDiscount
{
    public function __construct()
    {
    }

    /*
    This function is used to fetch the discount details of a given profileid or comma separated profileids
    @param - profileid or comma separated profileids
    @return - array having discount details of each profileid or blank
    */
    public function getDiscDetails($profileid)
    {
        $vdObj = new billing_VARIABLE_DISCOUNT;
        $output = $vdObj->getDiscountDetails($profileid);
        unset($vdObj);
        return $output;
    }
    
    public function getSlabForProfile($profileid)
    {
        $variableObj = new newjs_ANALYTICS_VARIABLE_DISCOUNT;
        $output = $variableObj->getSlabForProfile($profileid);
        return $output;
    }

    // get Maximum Discount
    public function getMaxVdDiscount($profileid)
    {
        $discountArr =$this->getAllDiscountForProfile($profileid);
        $maxDiscount =max($discountArr);
        return $maxDiscount;

    }
    
    // check Flat discount
    public function checkFlatVdDiscount($profileid)
    {
        $discountArr =$this->getAllDiscountForProfile($profileid);
        $discountUniqueArr =array_values(array_unique($discountArr));
        $totCount =count($discountUniqueArr);   
        if($totCount==1)
            return true;
        return false;
    }
	
	// get All discount with service plans
	public function getAllDiscountWithService($profileid)
	{
		$discountNewArr =array();
		$vdOfferDurationObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION('newjs_slave');
		$discountDetails =$vdOfferDurationObj->getDiscountDetailsForProfile($profileid);
		if(count($discountDetails)>0){
                	foreach($discountDetails as $key=>$val){
                	        $discountArr =$val;
				$service =$discountArr['SERVICE'];
                	        unset($discountArr['PROFILEID']);
                	        unset($discountArr['SERVICE']);
                	        $discountNewArr[$service] =$discountArr;
                	}
		}
		return $discountNewArr;
	}

    // get VD Discount Labels 
    public function getDiscountWithMemType($profileid,$discountArr=array())
    {
	if($profileid)
	        $discountArr =$this->getAllDiscountWithService($profileid);
	if(!is_array($discountArr))
		return;
	foreach($discountArr as $service=>$discount){
		$memName = VariableParams::$mainMembershipNamesArr[$service];
		foreach($discount as $duration=>$disVal)
			$memArr[] =$duration."M -".$disVal."%";
		
		$memStr =implode(", ", $memArr);
		unset($memArr);
		$discountArrNew[] =$memName.' :: '.$memStr;
		unset($memStr);
	}
	if(is_array($discountArr))
		$discountStr =implode(' , ',$discountArrNew);	
	return $discountStr;
    }
    // get VD Discount Labels 
    public function getPreviousVdLogDetails($profileid)
    {
	$vdLogObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION_LOG('newjs_slave');
        $vdLogDetailsArr =$vdLogObj->getDiscountDetails($profileid);
        $lastVdExpiryDate =$vdLogDetailsArr[0]['EDATE'];
	if($lastVdExpiryDate){
		foreach($vdLogDetailsArr as $key=>$val){
			$discountArr =$val;
			$service =$discountArr['SERVICE'];
			unset($discountArr['PROFILEID']);
			unset($discountArr['SERVICE']);
			unset($discountArr['EDATE']);
			$discountNewArr[$service] =$discountArr;
			unset($discountArr);
		}
		$discountStr =$this->getDiscountWithMemType('',$discountNewArr);
	}	
	return array("EDATE"=>$lastVdExpiryDate,"DISCOUNT"=>$discountStr);	

    }
    // get discount details 
    public function getDiscountDetails($profileid)
    {
        $discountArr =$this->getAllDiscountForProfile($profileid);                      
        $maxDiscount =max($discountArr);
        $discountUniqueArr =array_values(array_unique($discountArr));
        $totCount =count($discountUniqueArr);
        if($totCount==1)
            $flatDiscount=true;
        else
            $flatDiscount=false;
        $data =array("MAX_DISCOUNT"=>$maxDiscount,"FLAT_DISCOUNT"=>$flatDiscount);
        return $data;
    }

    // get All discount values          
    public function getAllDiscountForProfile($profileid)
    {
        $vdOfferDurationObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION('newjs_slave');
        $discountDetails =$vdOfferDurationObj->getDiscountDetailsForProfile($profileid);
	if(is_array($discountDetails)){
        foreach($discountDetails as $key=>$val){
            $discountArr =$val;
            unset($discountArr['PROFILEID']);
            unset($discountArr['SERVICE']);
            foreach($discountArr as $key1=>$val1)
                $discountNewArr[] =$val1;
        }
	}
        return $discountNewArr;
    }

    public function updateVdMailerStatus($profileid,$status)
    {
        $vdObj =new billing_VARIABLE_DISCOUNT();
        $vdObj->updateVDMailerStatus($profileid,$status);
    }

    public function getVdProfilesForMailer(){
    	$vdObj =new billing_VARIABLE_DISCOUNT('newjs_slave');
        $profilesArr =$vdObj->getVdProfilesForMailer();
        return $profilesArr;
    }
    public function getVDProfilesActivatedForDate($profileStr,$startDate=''){
        $vdObj =new billing_VARIABLE_DISCOUNT('newjs_slave');
        $profilesArr =$vdObj->getDiscount($profileStr,$startDate);
        return $profilesArr;
    }
	public function getVdDisplayText($profileid, $caseType)
	{
            	$flatDiscount   	=$this->checkFlatVdDiscount($profileid);
            	$discountLimitText 	=VariableParams::$discountLimitText;
		$discountTextVal 	=$this->getDiscountCaseTypeText($caseType, $flatDiscount);
		return $discountTextVal;
	}

	public function getDiscountCaseTypeText($caseType, $flatDiscount='')
	{
		$discountLimitText      =VariableParams::$discountLimitText;
                if($caseType=='small')
                        $caseType='Small';
                else if($caseType=='cap')
                        $caseType='Cap';
                if($flatDiscount){
                        $type ='flat'.$caseType;
                        $discountTextVal =$discountLimitText[$type];
                }
                else{
                        $type ='upto'.$caseType;
                        $discountTextVal =$discountLimitText[$type];
		}
                return $discountTextVal;
	}
    
    public function getCashDiscountDispText($profileid='',$caseType)
    {
		$cashDiscountOfferObj 	=new billing_DISCOUNT_OFFER('newjs_slave');
        $flatDiscount           =$cashDiscountOfferObj->checkFlatDiscount();
        //$discountLimitText      =VariableParams::$discountLimitText;
		$discountTextVal        =$this->getDiscountCaseTypeText($caseType, $flatDiscount);
        return $discountTextVal;
    }
    public function getCashDiscount()
    {
    	$cashDiscountOfferObj   =new billing_DISCOUNT_OFFER();
       	$discount           	=$cashDiscountOfferObj->getDiscountUpto();
       	return $discount;
    }

    /**
       * Function to activate VD for profile if not activated earlier
       *
       * @param  $profileid,$discountDetails,$serviceArr,$sendSms
       * @return none
       */ 
    public function activateVDForProfile($profileid,$discountDetails,$serviceArr,$sendMail=false,$sendSMS=false)
    {
        $vdObj1 = new billing_VARIABLE_DISCOUNT('newjs_slave');
        $SENT_MAIL = 'Y';  //$SENT_MAIL = 'Y' specifies no mail to be sent
        $SENT_SMS = 'Y'; //$SENT_SMS = 'Y' specifies no sms to be sent
        if($sendMail==true)
            $SENT_MAIL='N'; // $SENT_MAIL='N' specifies mail to be sent
        if($sendSMS == true)
            $SENT_SMS = 'N'; //$SENT_SMS = 'N' specifies sms to be sent
        //add entry in tables if no entry in VARIABLE_DISCOUNT table earlier for profile
        if(!$vdObj1->getProfileidWithDiscount($profileid))
        {
	    $vdObj = new billing_VARIABLE_DISCOUNT();
            $vdObj->addVDProfile($profileid,$discountDetails["discountPercent"],$discountDetails["startDate"],$discountDetails["endDate"],$discountDetails["entryDate"],$SENT_MAIL,$SENT_SMS);
            $durationObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
            $params = array("PROFILEID"=>$profileid,"SERVICE"=>$serviceArr,"DISC2"=>$discountDetails["DISC2"],"DISC3"=>$discountDetails["DISC3"],"DISC6"=>$discountDetails["DISC6"],"DISC12"=>$discountDetails["DISC12"],"DISCL"=>$discountDetails["DISCL"]);
            $durationObj->addVDOfferDurationServiceWise($params);
        }
	unset($vdObj1);
	unset($vdObj);
	unset($durationObj);
    }

    /**
     * @fn getDiscountEligibleProfiles
     * @param $params--conditions,$discountType(optional----key/name for temp table)
     * @return array of resulting rows
     */

    public function getDiscountEligibleProfiles($params,$discountType="")
    {
        $resultProfiles = array();
        if($discountType=="WELCOME_DISCOUNT")
        {
            $offsetDate = date('Y-m-d H:i:s', strtotime($params["offset"]));
            $registerDate = date('Y-m-d', strtotime($params["registerOffset"]));
            
            $recentlyMobVerifiedProfiles = array();
            $recentPhotoScreenedProfiles = array();

            //get registered profiles within defined offset
            $jprofileObj = new JPROFILE("newjs_slave");
            $profilesPool = $jprofileObj->getRegisteredProfilesAfter($registerDate,"PROFILEID,VERIFY_ACTIVATED_DT,HAVEPHOTO,ENTRY_DT");
            unset($jprofileObj);
          
            $profilesID = array_keys($profilesPool);
            
            //get recently mob verified profiles with already screened photo within defined offset
            $conditionArr = array("registerDate"=>$registerDate,"offsetDate"=>$offsetDate,"profilesPool"=>$profilesID);
            unset($profilesID);
            $index=0;
            foreach($profilesPool as $profileid=>$detailsArr)
            {
                if($detailsArr["VERIFY_ACTIVATED_DT"]>=$offsetDate && $detailsArr["HAVEPHOTO"]=='Y')
                    $recentlyMobVerifiedProfiles[$index++] = $profileid;
            }
            
            //get recently photo screened profiles with already mobile verified within defined offset
            $photoObj = new PHOTO_FIRST();  
            $recentPhotoScreenedProfiles = $photoObj->getPhotoScreenedProfilesAfter($conditionArr);
            unset($conditionArr);
            unset($photoObj);      
            foreach($recentPhotoScreenedProfiles as $key=>$profileid)
            {
                if((!$profilesPool[$profileid]["VERIFY_ACTIVATED_DT"] || $profilesPool[$profileid]["VERIFY_ACTIVATED_DT"]=="0000-00-00 00:00:00"))
                    unset($recentPhotoScreenedProfiles[$key]);
            }
            $recentPhotoScreenedProfiles = array_values($recentPhotoScreenedProfiles);

            //merge both arrays
            if($recentlyMobVerifiedProfiles || $recentPhotoScreenedProfiles)
            { 
                $resultProfiles = array_unique(array_merge($recentlyMobVerifiedProfiles,$recentPhotoScreenedProfiles)); 
            }
            unset($recentPhotoScreenedProfiles);
            unset($recentlyMobVerifiedProfiles);

            foreach($resultProfiles as $key=>$profileid)
            {
                $resultProfiles[$key] = array("PROFILEID"=>$profileid,"ENTRY_DT"=>$profilesPool[$profileid]["ENTRY_DT"]);
            }
            unset($profilesPool);
            //print_r($resultProfiles);
        }
        return $resultProfiles;
    }   

    
    public function generateVDImpactReport()
    {
        $variableDiscountObj = new billing_VARIABLE_DISCOUNT("newjs_slave");
        $vdData = $variableDiscountObj->getVariableDiscountProfilesEndingYesterday();
        if(count($vdData) > 150000)
        {
            $startDate = $vdData[0]["SDATE"]." 00:00:00";
            $endDate = $vdData[0]["EDATE"]." 23:59:59";
            foreach($vdData as $key=>$vdDetail)
            {
                $profiles[$vdDetail["PROFILEID"]] = $vdDetail["DISCOUNT"] ;
                $discountOffered[$vdDetail["DISCOUNT"]]++;
            }
            $paymentDetailsObj = new BILLING_PAYMENT_DETAIL();
            $paymentData = $paymentDetailsObj->getPaymentsDuringVariableDiscountPeriod($startDate, $endDate);
            foreach($paymentData as $key=>$purchaseDetail)
            {
                if($profiles[$purchaseDetail["PROFILEID"]])
                {
                    $discount = $profiles[$purchaseDetail["PROFILEID"]];
                    $discountAvailed[$discount]++;
                    $paymentCollected[$discount]+=$purchaseDetail["AMOUNT"];
                }
            }
            $tableMsg = $this->insertIntoVariableDiscountReport($discountOffered, $discountAvailed, $paymentCollected);
            
            $msg = "Hi,<br><br>Variable Discount Impact Report has been generated.<br><br>";
            $msg.="<table><tr><th>Discount Up To | </th><th>People Offered Discount | </th><th>People Paid | </th><th>Avg. Ticket Size</th></tr>".$tableMsg."</table>";
            $msg.="<br>You can also download the report by clicking on the following url.<br><br>";
            $url = JsConstants::$siteUrl."/operations.php/csvGenerationProcess/generateCsv?processName=VDImpactReport&date=".date("Y-m-d");
            $msg.="<a href='".$url."'>".$url."</a><br>";
            $subject = "Variable Discount Impact Report";
            $to = "jsprod@jeevansathi.com";
            $cc = "nitish.sharma@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
            $from = "info@jeevansathi.com";
            $from_name = "Jeevansathi Info";
            SendMail::send_email($to,$msg, $subject, $from,$cc,"","","","","","1","",$from_name);
        }
    }
    
    public function insertIntoVariableDiscountReport($discountOffered, $discountAvailed, $paymentCollected)
    {
        $vdReportObj = new billing_VARIABLE_DISCOUNT_REPORT();
        ksort($discountOffered);
        $msg = "";
        foreach($discountOffered as $key=>$val)
        {
            $discount = $key;
            $offered = $val;
            $availed = $discountAvailed[$discount] ? $discountAvailed[$discount] : 0;
            $avgToken = ($availed > 0) ? $paymentCollected[$discount]/$availed : 0;
            $vdReportObj->insertData($discount, $offered, $availed, $avgToken);
            $msg.= "<tr><td align='right'>".$discount."%</td><td align='right'>".$offered."</td><td align='right'>".$availed."</td><td align='right'>".$avgToken."</td><tr>";
        }
        unset($vdReportObj);
        return $msg;
    } 

    public function updatePreviousVDRecords($entryDate)
    {
        $VDLogObj = new billing_VARIABLE_DISCOUNT_LOG();
        $VDLogObj->insertDataFromVariableDiscount($entryDate);
        $VDLogObj->insertDataFromVariableDiscountOfferDuration();
        unset($VDLogObj);

        $VDObj = new billing_VARIABLE_DISCOUNT();
        $VDObj->deleteVariableDiscountEndingYesterday();
        unset($VDObj);

        $VDDurationObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        $VDDurationObj->deleteDiscountRecord();
        unset($VDDurationObj);
    }

    public function populateRecordsFromVDTemp($entryDate,$limit,$sendAlert=false)
    {
        $VDTempObj = new billing_VARIABLE_DISCOUNT_TEMP();
        $VDDuartionObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        $VDObj = new billing_VARIABLE_DISCOUNT();
        $profileArr =array();

        $count = $VDTempObj->getCountOfRecords();
        for($i=0;$i<$count;$i+=$limit)
        {
           
            $rows = $VDTempObj->fetchAllRecords("*",$limit,$i);
            if(is_array($rows))
            foreach ($rows as $key => $details) 
            {
                
                $pid =$details['PROFILEID'];
                if($pid)
                {
                    $service  =explode(",",$details['SERVICE']);
                    $discL    =$details['12'];
		    $disc2 = $details['2'];	
                    $disc3 = $details['3'];
                    $disc6 = $details['6'];
                    $disc12 = $details['12'];
                    if(!isset($dateArr)){
                      $sdate  =$details['SDATE'];
                      $edate  =$details['EDATE'];
                      $dateArr=array("$sdate","$edate");
                    }
                    $discMax  =max($disc2,$disc3,$disc6,$disc12,$discL);
                    if(!isset($profileArr[$pid]))
                    {
                        $profileArr[$pid] = 0;
                    }
                    
                    if($discMax>$profileArr[$pid])
                    {        
                        $profileArr[$pid] =$discMax;
                    }
                  
                    //add entry into VD Offer Duration table
                    $params = array("PROFILEID"=>$pid,"SERVICE"=>$service,"DISC2"=>$disc2,"DISC3"=>$disc3,"DISC6"=>$disc6,"DISC12"=>$disc12,"DISCL"=>$discL);
                    $VDDuartionObj->addVDOfferDurationServiceWise($params,$sendAlert);
                }
            }
            unset($rows); 
        }
        
        if(count($dateArr)>0){
          $sdate =$dateArr[0];
          $edate =$dateArr[1];
        }
        
        if(is_array($profileArr)) 
        foreach($profileArr as $profileid=>$discount){
            $VDObj->addVDProfile($profileid,$discount,$sdate,$edate,$entryDate,"","",$sendAlert); 
        }
        unset($VDObj);
        unset($VDDurationObj);
        unset($VDTempObj);
    }

    /*transfer VD entries from test.VD_UPLOAD_TEMP to billing.VARIABLE_DISCOUNT_TEMP table
    * @param: $limit,$offset
    * @return : status(success-true/failure-false)
    */
    public function transferVDRecordsToTemp($limit,$offset)
    {
        $tempObj = new billing_VARIABLE_DISCOUNT_TEMP();
        $uploadObj = new test_VD_UPLOAD_TEMP('newjs_local111');
        $count = $uploadObj->getCountOfRecords();
        if($count==0)
           return uploadVD::EMPTY_SOURCE; 
        //fetch rows from start of user table or from last inserted row onwards
        for($i=$offset;$i<$count;$i+=$limit)
        {
            $rows = $uploadObj->fetchSelectedRecords("*",$limit,$i);
            foreach ($rows as $key => $value) 
            {
                $tempObj->addVDRecordsInTemp($value);
            }
        }
     
        unset($tempObj);
        unset($uploadObj);
        return uploadVD::COMPLETE_UPLOAD;
    }
}
?>
