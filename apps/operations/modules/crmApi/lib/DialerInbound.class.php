<?php
class DialerInbound
{

        public function __construct(){
        }

	public function getAbusiveStatus($phone)
	{
		$abusiveObj =new ABUSE_PHONE('newjs_slave');
		$status =$abusiveObj->getAbusiveStatus($phone);
		if($status)
			return true;
		return false;
	}
	public function getProfileDetails($phone)
	{
		if(!$phone)
			return;
		$phoneArr =array("$phone","0$phone");
		
		$jprofileObj = NEWJS_JPROFILE::getInstance('newjs_slave');		
		$fields = 'PROFILEID,USERNAME,MTONGUE,SUBSCRIPTION,GENDER,RELIGION,DTOFBIRTH,MSTATUS';
		$profileArr = $jprofileObj->getDetailsForPhone($phoneArr,$fields);
		$totProfiles = count($profileArr);
		if($totProfiles==0)
			return;
		return $profileArr;
	}
	public function formatResponseData($dataArr)
	{
		$gender 	= FieldMap::getFieldLabel('gender',$dataArr['GENDER']);
		$mstatus 	= FieldMap::getFieldLabel('mstatus',$dataArr['MSTATUS']);
		$religion 	= FieldMap::getFieldLabel('religion',$dataArr['RELIGION']);
		$community 	=$this->getCommunityMapping($dataArr['MTONGUE']);
		$dataArr['GENDER'] 	= $gender;
		$dataArr['MSTATUS'] 	= $mstatus;
		$dataArr['RELIGION'] 	= $religion;
		$dataArr['MTONGUE']    	= $community;
		$subscription = $dataArr['SUBSCRIPTION'];
		if((strstr($subscription, "F") != false) || (strstr($subscription, "D") !=  false))
			$dataArr['SUBSCRIPTION'] ='Y';
		else
			$dataArr['SUBSCRIPTION'] ='N';
		return $dataArr;
	}
	public function getMembershipDetails($profileid)
	{
            	$displayPage = 1;
            	$device = "desktop";
            	$ignoreShowOnlineCheck = false;

		$memHandlerObj = new MembershipHandler();
            	$userObj = new memUser($profileid);
            	$userObj->setMemStatus();

            	list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code) = $memHandlerObj->getUserDiscountDetailsArray($userObj, "L");
            	list($allMainMem, $minPriceArr) = $memHandlerObj->getMembershipDurationsAndPrices($userObj, $discountType, $displayPage, $device, $ignoreShowOnlineCheck);
		$mostPopular =$memHandlerObj->getMostPopular();
		$mostPopular =array_keys($mostPopular);
		$mainMemArr =array("allMainMem"=>$allMainMem,"expiry_date"=>$expiry_date,"renewalActive"=>$renewalActive,"mostPopular"=>$mostPopular);
		return $mainMemArr;
	}
	public function getDiscountDetails($memPriceArr)
	{
        	if(is_array($memPriceArr)){
        	    foreach($memPriceArr as $service => $val){
        	        //$servDisc[$service] = 0;
        	        foreach($val as $servDur => $details){
        	            $disc = $details["PRICE"] - $details["OFFER_PRICE"];
        	            if($disc > 0){
        	                $per = ($disc/$details["PRICE"])*100;
        	                //if($per>$servDisc[$service]){
        	                    $servDisc[$servDur] = intval($per);
        	                //}
        	            }
        	        }
        	    }
        	}
        	return $servDisc;
    	}
        public function formatPlanDetails($mainMemArr,$mostPopular='')
        {
                // get Popular plan logic
		$unsetArr =array('PRICE','FESTIVE_PRICE','DISCOUNT_PRICE','SPECIAL_DISCOUNT_PRICE','SHOW_ONLINE');
		$unsetMainArr =array('ESP','X','E');
                $planNamesArr = VariableParams::$mainMembershipNamesArr;

		foreach($unsetMainArr as $keyM=>$valM){
			unset($mainMemArr[$valM]);
			unset($mostPopular[array_search($valM, $mostPopular)]);
		}
		$mostPopular =array_values($mostPopular);
                foreach($mainMemArr as $key=>$dataArr){
                        foreach($dataArr as $dur=>$val){
                                $mainMemArr[$key][$dur]['NAME'] =$planNamesArr[$key];
				foreach($unsetArr as $unsetKey=>$unsetVal)
                                	unset($mainMemArr[$key][$dur][$unsetVal]);
                        }
                }
		$resultArr =array_replace(array_flip($mostPopular), $mainMemArr);
                return $resultArr;
        }
        public function getMembershipResponseData($profileid)
        {
		$discText	='';
		$discountVal 	=0;
		$daysDiff	=0;

                $mainMemArr 	=$this->getMembershipDetails($profileid);
		$allMainMem	=$mainMemArr['allMainMem'];
		$expiryDate	=$mainMemArr['expiry_date'];
		$renewalActive	=$mainMemArr['renewalActive'];
		$mostPopular	=$mainMemArr['mostPopular'];
                if(is_array($allMainMem))
                        $planDetails =$this->formatPlanDetails($allMainMem,$mostPopular);

		// Discount Info
                $discountArr	=$this->getDiscountDetails($allMainMem);
		if(is_array($discountArr)){
			$min =min($discountArr);
			$max =max($discountArr);
			if($min==$max)
				$discText ='flat';
			else
				$discText ='upto';
			$discountVal =$max;	
		}
		if($discountVal>0)
			$discountActive ='Y';
		else
			$discountActive ='N';
		$memData['DISCOUNT_ACTIVE'] 	=$discountActive;
		$memData['DISCOUNT_TEXT'] 	=$discText;	
		$memData['DISCOUNT_PERCENT'] 	=$discountVal;

		// Renewal Info 
		if($renewalActive){
			$curTime	=time();
			$expiryTime	=strtotime($expiryDate);
			if($expiryTime>=$curTime){
				$daysDiff =floor(($expiryTime-$curTime)/(60*60*24))+1;
			}
			$renewalActive ='Y';
		}
		else
			$renewalActive ='N';
		$memData['RENEWAL_DAYS'] =$daysDiff;
		$memData['RENEWAL_ACTIVE'] =$renewalActive;			
		$memData['MEMBERSHIP'] =$planDetails;	

                return $memData;
        }
        public function getCommunityMapping($mtongue)
        {
		$englishArr 	=array(1,37);
		$hindiArr	=array(5,4,7,9,13,14,10,19,33,15,22,34,24,18,21,23,2,25,27,28,29,39,32,35,36);
		$bengaliArr	=array(6);
		$gujaratiArr	=array(12);
		$kannadaArr	=array(16);
		$malyalamArr	=array(17);
		$marathiArr	=array(20);
		$tamilArr	=array(31);
		$teluguArr	=array(3);
		$newCommunityMap =array("Hindi","English","Bengali","Gujarati","Kannada","Malyalam","Marathi","Tamil","Telugu");
		if(in_array("$mtongue", $hindiArr))
			$setCommunity =$newCommunityMap[0];
                elseif(in_array("$mtongue", $englishArr))
                        $setCommunity =$newCommunityMap[1];
                elseif(in_array("$mtongue", $bengaliArr))
                        $setCommunity =$newCommunityMap[2];
                elseif(in_array("$mtongue", $gujaratiArr))
                        $setCommunity =$newCommunityMap[3];
                elseif(in_array("$mtongue", $kannadaArr))
                        $setCommunity =$newCommunityMap[4];
                elseif(in_array("$mtongue", $malyalamArr))
                        $setCommunity =$newCommunityMap[5];
                elseif(in_array("$mtongue", $marathiArr))
                        $setCommunity =$newCommunityMap[6];
                elseif(in_array("$mtongue", $tamilArr))
                        $setCommunity =$newCommunityMap[7];
                elseif(in_array("$mtongue", $teluguArr))
                        $setCommunity =$newCommunityMap[8];
		else
			$setCommunity =$newCommunityMap[0];
                return $setCommunity;
        }


}

?>
