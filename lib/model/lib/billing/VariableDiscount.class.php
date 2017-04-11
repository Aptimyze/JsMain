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
        $vdObj = new billing_VARIABLE_DISCOUNT();
        $output = $vdObj->getDiscountDetails($profileid);
        unset($vdObj);
        return $output;
    }
    
    public function getSlabForProfile($profileid)
    {
        $variableObj = new newjs_ANALYTICS_VARIABLE_DISCOUNT();
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
		$vdOfferDurationObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION('newjs_masterRep');
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
    public function getPreviousVdLogDetails($profileid, $raw = null)
    {
	$vdLogObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION_LOG('newjs_masterRep');
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
        if (empty($raw)) {
	       $discountStr =$this->getDiscountWithMemType('',$discountNewArr);
        } else {
            $discountStr = $discountNewArr;
        }
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
    public function getAllDiscountForProfile($profileid, $durationArr='')
    {
	if(!is_array($durationArr)){
		$durationArr =$this->getActiveDurations($profileid);
	}
        $vdOfferDurationObj =new billing_VARIABLE_DISCOUNT_OFFER_DURATION('newjs_masterRep');
        $discountDetails =$vdOfferDurationObj->getDiscountDetailsForProfile($profileid);
	if(is_array($discountDetails)){
        foreach($discountDetails as $key=>$val){
            $discountArr =$val;
            unset($discountArr['PROFILEID']);
            unset($discountArr['SERVICE']);
            foreach($discountArr as $key1=>$val1){
		if(in_array($key1, $durationArr))
	                $discountNewArr[] =$val1;
	    }	
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
    	$vdObj =new billing_VARIABLE_DISCOUNT('newjs_masterRep');
        $profilesArr =$vdObj->getVdProfilesForMailer();
        return $profilesArr;
    }
    public function getVDProfilesActivatedForDate($profileStr,$startDate=''){
        $vdObj =new billing_VARIABLE_DISCOUNT('newjs_masterRep');
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
		$cashDiscountOfferObj 	=new billing_DISCOUNT_OFFER('newjs_masterRep');
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
        $vdObj1 = new billing_VARIABLE_DISCOUNT('newjs_masterRep');
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
        $vdData = $variableDiscountObj->getVDProfilesEndingYesterday();
        //$vdData = $variableDiscountObj->getVariableDiscountProfilesEndingYesterday();
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

    }

    public function populateRecordsFromVDTemp($entryDate,$limit,$sendAlert=false)
    {
        $VDTempObj = new billing_VARIABLE_DISCOUNT_TEMP();
        $VDDuartionObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        $VDObj = new billing_VARIABLE_DISCOUNT();
        $profileArr =array();
        $vdDurationArr =uploadVD::$vdDurationArr;
        $variable ='disc';
	$todayDate =date("Y-m-d");

        $count = $VDTempObj->getCountOfRecords($entryDate);
        for($i=0;$i<$count;$i+=$limit)
        {
            $rows = $VDTempObj->fetchAllRecords("*",$limit,$i, $entryDate);
            if(is_array($rows))
            foreach ($rows as $key => $details) 
            {
                $pid =$details['PROFILEID'];
                if($pid){

		    $startDate  =$details['SDATE'];	
		    if(strtotime($todayDate) != strtotime($startDate))
			continue;		
		    $isPaid =$this->checkPaidProfile($pid);	
		    if($isPaid)
			continue; 	
                    $service  =explode(",",$details['SERVICE']);
                    foreach($vdDurationArr as $key=>$val){
                        if($val=='L')
				$netVal ='12';
			else
				$netVal=$val;
			${$variable.$val} =$details[$netVal];
                    }
                    if(!isset($dateArr)){
                      $sdate  =$details['SDATE'];
                      $edate  =$details['EDATE'];
                      $dateArr=array("$sdate","$edate");
                    }
                    // Start : Code to pick last discount capping by executive
                    $discNegLogObj = new incentive_DISCOUNT_NEGOTIATION_LOG('newjs_masterRep');
                    $lastNegDet = $discNegLogObj->getLastNegotiatedDiscountDetails($pid);
                    if (!empty($lastNegDet) && strtotime($lastNegDet['ENTRY_DT']) < time() && strtotime($lastNegDet['EXPIRY_DT']) > time()) {
                        $disc2 = min($disc2, $lastNegDet['DISCOUNT']);
                        $disc3 = min($disc3, $lastNegDet['DISCOUNT']);
                        $disc6 = min($disc6, $lastNegDet['DISCOUNT']);
                        $disc12 = min($disc12, $lastNegDet['DISCOUNT']);
                        $discL = min($discL, $lastNegDet['DISCOUNT']);
                    }
                    unset($discNegLogObj, $lastNegDet);
                    // End : Code to pick last discount capping by executive
                    $discMax  =max($disc2,$disc3,$disc6,$disc12,$discL);
                    if(!isset($profileArr[$pid])){
                        $profileArr[$pid] = 0;
                    }
                    if($discMax>$profileArr[$pid]){
                        $profileArr[$pid] =$discMax;
                    }
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
    public function populateRemainingRecordsFromVDTemp($entryDate,$sendAlert=false)
    {
        $VDTempObj = new billing_VARIABLE_DISCOUNT_TEMP();
        $VDDuartionObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        $VDObj = new billing_VARIABLE_DISCOUNT();
        $profileArr =array();
	$vdDurationArr =uploadVD::$vdDurationArr;
        $variable ='disc';

            $rows = $VDTempObj->fetchActiveRecords($entryDate);
            if(is_array($rows))
            foreach ($rows as $key => $details)
            {
                $pid =$details['PROFILEID'];
                if($pid){
                    $isPaid =$this->checkPaidProfile($pid);
                    if($isPaid) 
                        continue;
                    $service  =explode(",",$details['SERVICE']);
		    foreach($vdDurationArr as $key=>$val){
                        if($val=='L')
                                $netVal ='12';
                        else    
                                $netVal=$val;
                        ${$variable.$val} =$details[$netVal];
		    }
                    if(!isset($dateArr)){
                      $sdate  =$details['SDATE'];
                      $edate  =$details['EDATE'];
                      $dateArr=array("$sdate","$edate");
                    }
                    // Start : Code to pick last discount capping by executive
                    $discNegLogObj = new incentive_DISCOUNT_NEGOTIATION_LOG('newjs_masterRep');
                    $lastNegDet = $discNegLogObj->getLastNegotiatedDiscountDetails($pid);
                    if (!empty($lastNegDet) && strtotime($lastNegDet['ENTRY_DT']) < time() && strtotime($lastNegDet['EXPIRY_DT']) > time()) {
                        $disc2 = min($disc2, $lastNegDet['DISCOUNT']);
                        $disc3 = min($disc3, $lastNegDet['DISCOUNT']);
                        $disc6 = min($disc6, $lastNegDet['DISCOUNT']);
                        $disc12 = min($disc12, $lastNegDet['DISCOUNT']);
                        $discL = min($discL, $lastNegDet['DISCOUNT']);
                    }
                    unset($discNegLogObj, $lastNegDet);
                    // End : Code to pick last discount capping by executive
                    $discMax  =max($disc2,$disc3,$disc6,$disc12,$discL);
                    if(!isset($profileArr[$pid])){
                        $profileArr[$pid] = 0;
                    }
                    if($discMax>$profileArr[$pid]){
                        $profileArr[$pid] =$discMax;
                    }
                    $params = array("PROFILEID"=>$pid,"SERVICE"=>$service,"DISC2"=>$disc2,"DISC3"=>$disc3,"DISC6"=>$disc6,"DISC12"=>$disc12,"DISCL"=>$discL);
                    $VDDuartionObj->addVDOfferDurationServiceWise($params,$sendAlert);
                }
            }
            unset($rows);

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
        unset($uploadObj);
        if($count==0)
           return uploadVD::EMPTY_SOURCE; 

        for($i=$offset;$i<$count;$i+=$limit)
        {
	    $uploadObj = new test_VD_UPLOAD_TEMP('newjs_local111');	
            $rows = $uploadObj->fetchSelectedRecords("*",$limit,$i);
	    unset($uploadObj);
            foreach ($rows as $key => $value){ 
                $tempObj->addVDRecordsInTemp($value);
            }
        }
        unset($tempObj);
        return uploadVD::COMPLETE_UPLOAD;
    }

    // Check Paid Condition
    public function checkPaidProfile($profileid)
    {
        $jprofileObj = new JPROFILE('newjs_masterRep');
	$subscription =$jprofileObj->getProfileSubscription($profileid);	
        if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
		$paid =true;
	else
		$paid =false;
	unset($jprofileObj);
	return $paid;	
    }
    public function getActiveDurations($profileid="")
    {
	$keyMain='MAIN_MEM_DURATION';
	$memCacheObject = JsMemcache::getInstance();
        if($memCacheObject->get($keyMain)){
        	$durationsArr =unserialize($memCacheObject->get($keyMain));
        }
	 else{
        	$serviceObj = new billing_SERVICES('newjs_masterRep'); 
            if(!empty($profileid)){
                $profileObj = LoggedInProfile::getInstance('newjs_slave',$profileid);
                $profileObj->getDetail($profileid, 'PROFILEID', 'MTONGUE');
                if($profileObj != null){
                    $mtongue = $profileObj->getMTONGUE();
                }
                if(empty($online)){
                    $mtongue = "-1";
                }
                unset($profileObj);
            }
            else{
                $mtongue = "-1";
            }
		$durationsArr =$serviceObj->getOnlineActiveDurations($mtongue);
		foreach($durationsArr as $key=>$val){
			if($val=='1188'){
				unset($durationsArr[$val]);
				$val='L';
				$durationsArr[$val] =$val;
			}
		}
		$memCacheObject->set($keyMain, serialize($durationsArr));
	}
	return $durationsArr;	
    }
    // get All discount values          
    public function getDiscountArrFromPoolTech($profileid, $durationArr='')
    {
        if(!is_array($durationArr)){
                $durationArr =$this->getActiveDurations($profileid);
        }
        $vdOfferDurationObj =new billing_VARIABLE_DISCOUNT_DURATION_POOL_TECH('newjs_masterRep');
        $discountDetails =$vdOfferDurationObj->getDiscountArr($profileid);
        if(is_array($discountDetails)){
        foreach($discountDetails as $key=>$val){
            $discountArr =$val;
            unset($discountArr['PROFILEID']);
            unset($discountArr['SERVICE']);
            foreach($discountArr as $key1=>$val1){
		$key1 =strstr("_DISCOUNT","",$key1);
                if(in_array($key1, $durationArr))
                        $discountNewArr[] =$val1;
            }
        }
        }
        return $discountNewArr;
    }

        // pre-process Mini-VD Data
        public function preProcessMiniVdData()
        {
                $vdClusterObj   =new billing_VD_CLUSTER();
                $clusterDetails =$vdClusterObj->getClusterDetails();
                $fields         ='PROFILEID,GENDER,AGE,SUBSCRIPTION';
                $jprofileData   =array();
		$profileArray	=array();

                if(is_array($clusterDetails)){
                foreach($clusterDetails as $clusterName=>$fieldArr){
			unset($greaterArray);unset($valueArray);unset($lessArray);
			unset($expiryDt);unset($analyticScore);
			unset($everPaid);unset($neverPaid);unset($discount);

			//loop start
			foreach($fieldArr as $key=>$data){
				$val1 =$data['VALUE1'];
				$val2 =$data['VALUE2'];

				if($key=='LAST_LOGIN_DT'){
					$greaterArray[$key] =$val1;
				}
				if($key=='ACTIVATED'){
					 $valueArray[$key]="'Y'";
					 $valueArray['MOB_STATUS']="'Y'";
				}
				if($key=='ENTRY_DT'){
					$greaterArray[$key] =$val1;
					$lessArray[$key]=$val2." 23:59:59";
				}
				if($key=='MTONGUE')
					$valueArray[$key]=$val1;
				if($key=='NEVER_PAID')
					$neverPaid=1;
				if($key=='EVER_PAID')
					$everPaid=1;
				if($key=='ANALYTIC_SCORE'){
					$analyticScore=1;
					$scoreMin =$val1;
					$scoreMax =$val2;
				}
				if($key=='EXPIRY_DT'){
					$expiryDt =1;
					$expiryDt1 =$val1;
					$expiryDt2 =$val2." 23:59:59";	
				}
				if($key=='VD_OFFER_DATE'){
					$startDate      =$val1;
					$endDate        =$val2;
				}
				if($key=='DISCOUNT'){
					$discount       =$val1;
				}
			}
			// loop end

			// jprofile data
			$jprofileObj =new JPROFILE('newjs_local111');
			$mainAdminPoolObj= new incentive_MAIN_ADMIN_POOL('newjs_local111');	
			$jprofileData =$jprofileObj->getArray($valueArray,'',$greaterArray,$fields,$lessArray);
			//print_r($jprofileData);		
			foreach($jprofileData as $key=>$val){
				$profileid      =$val['PROFILEID'];
				$gender         =$val['GENDER'];
				$age            =$val['AGE'];
				$subscription   =$val['SUBSCRIPTION'];

				if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
					continue;
				if(($gender=='M' && $age<23) || ($gender=='F' && $age<20))
					continue;
				if($analyticScore){
					$eligible =$mainAdminPoolObj->getEligibileProfile($profileid,$scoreMin,$scoreMax);
					if(!$eligible)
						continue;
				}
				$isRenewal =$this->isProfileRenewable($profileid);
                                if($isRenewal && ($isRenewal!=1)){
	                                continue;
                                }
				$profileArr[] =$profileid;
			}
			//print_r($profileArr);
			if($expiryDt){
				$servicesObj =new BILLING_SERVICE_STATUS('newjs_local111');
				$expiryProfiles =$servicesObj->getMaxExpiryProfilesForDates($expiryDt1, $expiryDt2);
				if(is_array($expiryProfiles))
					$profileArr =array_intersect($profileArr, $expiryProfiles);
			}
			// get ever paid profiles 
			if($everPaid || $neverPaid){
				$purchasesObj =new BILLING_PURCHASES('newjs_local111');
				$everPaidArr =$purchasesObj->fetchEverPaidPool();
			}
			if($everPaid){
				if(is_array($everPaidArr))
					$profileArr =array_intersect($profileArr, $everPaidArr);
			}
			if($neverPaid){
				if(is_array($everPaidArr))
					$profileArr =array_diff($profileArr, $everPaidArr);
			}
			$profileArr =array_values($profileArr);
			$profileArr =array_unique($profileArr);

			// Add in pool
			$this->addMiniVdDataInTemp($profileArr,$startDate,$endDate,$discount);

			// Send Mail for Cluster Count
			$totalCount =count($profileArr);
			$countArr[] =array('cluster'=>$clusterName,'count'=>$totalCount);

			// Delete Cluster
			//$vdClusterObj->deleteCluster($clusterName);
			unset($profileArr);
			unset($everPaidArr);unset($jprofileData);unset($expiryProfiles);
		}
		foreach($countArr as $key=>$dataArr){
			$cluster 	=$dataArr['cluster'];
			$total 		=$dataArr['count'];
			$message 	.="\n".$cluster."=".$total;
		}
	        $subject	= "VD Cluster Details";
            	$to 		= "manoj.rana@naukri.com,rohan.mathur@jeevansathi.com";
        	$from 		= "info@jeevansathi.com";
		SendMail::send_email($to,$message, $subject, $from,$cc,"","","","","","1","","");
		}
		
	}

	// process Mini-VD Data
        public function addMiniVdDataInTemp($profileArr,$startDate,$endDate,$discount)
        {
		$services ='P,C,NCP,X';	
                $uploadTempObj =new test_VD_UPLOAD_TEMP('newjs_local111');

                if(is_array($profileArr)){
                foreach($profileArr as $key=>$profileid){
                        $uploadTempObj->addVDRecordsInUploadTemp($profileid,$startDate,$endDate,$discount,$services);
                }}
        }
	function isProfileRenewable($profileid) {
		$purchasesObj = new BILLING_PURCHASES('newjs_local111');
		$serviceStatusObj = new BILLING_SERVICE_STATUS('newjs_local111');

		$myrow = $purchasesObj->getPurchaseCount($profileid);
		if ($myrow['COUNT'] > 0) {
		    $row = $serviceStatusObj->getLastActiveServiceDetails($profileid);
		    if ($row['EXPIRY_DT']) {
			if ($row['SERVICEID'] == "PL" || $row['SERVICEID'] == "CL" || $row['SERVICEID'] == "DL" || $row['SERVICEID'] == "ESPL" || $row['SERVICEID'] == "NCPL") {
			    return 1;
			}
			else {
			    if ($row['DIFF'] > - 11 && $row['DIFF'] < 30) {
				list($yy, $mm, $dd) = explode('-', $row["EXPIRY_DT"]);
				$ts = mktime(0, 0, 0, $mm, $dd + 10, $yy);
				$expiry_date = date("j-M-Y", $ts);
				return $expiry_date;
			    }
			    else if ($row['DIFF'] > - 11) return 1;
			    else return 0;
			}
		    }
		    else return 0;
		}
		else return 0;
	}

}
?>
