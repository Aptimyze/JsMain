<?php
class CommonCrmInterfaceFunctions
{
	public function getNext15DaysForDropDown(){
		$curDate = date("Y-m-d");
		$display_date['select'] = "Select";
		for($i=0;$i<=15;$i++){
			$formatter = date("Y-m-d 23:59:59", strtotime($curDate ." +$i day"));
			$display_date[$formatter] = date("d M Y", strtotime($curDate ." +$i day"));
		}
		return $display_date;
	}

	public function getCouponApplicableServiceArray(){
		$service_names = array('P'=>'eRishta','C'=>'eValue','NCP'=>'eAdvantage','ESP'=>'eSathi');
		$service_dur_names = array('3'=>'3 Months','6'=>'6 Months','12'=>'12 Months','L'=>'Unlimited Months');
		$serviceid = array();
		foreach($service_names as $key => $value) {
			foreach($service_dur_names as $k => $v){
				$serviceid[$key][$key.$k] = $service_dur_names[$k];
			}
		}
		return array($serviceid,$service_dur_names,$service_names);
	}
	public function extendVdOfferDate($endDate, $startDate)
	{
		$vdObject =new billing_VARIABLE_DISCOUNT();		
		if($endDate && $startDate)
			$vdObject->extendVdExpiryDate($endDate,$startDate);
	}
	public function getVdExpiryDate()
	{
		$vdObject =new billing_VARIABLE_DISCOUNT();
		$expiryDate =$vdObject->getVdExpiryDate();
		return $expiryDate;
	}
        public function getVdStartDates()
        {
                $vdObject =new billing_VARIABLE_DISCOUNT();
                $startDateArr =$vdObject->getVdStartDate();
		foreach($startDateArr as $key=>$dateVal)
			$display_date[$dateVal] = date("d M Y", strtotime($dateVal));
                return $display_date;
        }
	/* general function to generate date dropdown
	arguments: startDate,daysNo */
	public function getDateDropDown($startDate,$daysNo){
		for($i=0;$i<=$daysNo;$i++){
			$formatter = date("Y-m-d", strtotime($startDate ." +$i day"));
			$display_date[$formatter] = date("d M Y", strtotime($startDate ." +$i day"));
		}
		return $display_date;
	}
	public function startVdOffer($vdStartDate, $vdEndDate,$scheduleDate)
	{
		$vdDurationObject =new billing_VARIABLE_DISCOUNT_DURATION();
		$resultSuccess =$vdDurationObject->setVdOfferDates($vdStartDate, $vdEndDate,$scheduleDate);
		/*
		if($resultSuccess){
			// Execute VD Process in background
			$filePath =JsConstants::$cronDocRoot."/crontabs/crm/vd_discount.php >/dev/null &";
			$cmd 	  =JsConstants::$php5path." -q ".$filePath;	
			$cmd = preg_replace('/[^A-Za-z0-9\. -_>&]/', '', $cmd);
			passthru($cmd);
		}*/
	}
        public function getCashDiscountExpiryDate()
        {
                $cdObject =new billing_DISCOUNT_OFFER_LOG();
                $activeOfferId =$cdObject->checkDiscountOffer();
		if($activeOfferId)
			$expiryDate =$cdObject->getExpiryDate($activeOfferId);
		if($expiryDate)
	                return $expiryDate;
		return;
        }
        public function startCashDiscountOffer($startDate, $endDate, $executive)
        {
                $cdDurationObject 	=new billing_DISCOUNT_OFFER_LOG();
		$cdDurationObject->deActivateOffer();
                $resultSuccess 		=$cdDurationObject->setDiscountOfferDates($startDate, $endDate, $executive);
                if($resultSuccess){
                        // Memcache server flush 
			$memHandlerObject =new MembershipHandler();
			$memHandlerObject->flushMemcacheForMembership();
                }
        }
	public function getActiveMainMembershipDetailsArr(){
		$billMemObj = new billing_MEMBERSHIPS();
		$billServObj = new billing_SERVICES();
		$service_names = $billMemObj->getServiceNameByStatus('Y');
		// Sorting main services
		$mainServices = array_keys($service_names);
		// End Sorting main services
		foreach($mainServices as $key=>$val){
			$serviceArr = $billServObj->getActivedOnlineServicesForID($val);
			$tempArr[$key] = array($val=>$serviceArr);
		}
		foreach($tempArr as $key=>$val){
			foreach($val as $kk=>$vv){
				foreach($vv as $k=>$v){
					$duration = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $v);
					if(strpos($duration[0], "L")){
						$durVal = "L";
						$dur = "Unlimited";
						$dur = "Unlimited Months";
					} else {
						$durVal = $duration[1];
						$dur = $duration[1];
						$dur = $duration[1]." Months";
					}
					$servArr[$v] = 0;
					$durArr[$durVal] = $dur; 
					$newArr[$kk][$v] = $dur;
				}
				// Sorting the array as we are creating it
				natsort($newArr[$kk]);
			}
		}
		foreach($mainServices as $key=>$val){
			$names[$val] = $service_names[$val];
		}
		// Sorting Duration array
		$this->knatsort($durArr);
		// End Sorting Duration array
		return array($newArr,$durArr,$names,$servArr);	
	}

	public function knatsort(&$array){
		$array_keys=array_keys($array);
		natsort($array_keys);
		$new_natsorted_array=array();
		$array_keys_2='';
		foreach($array_keys as $array_keys_2){
			$new_natsorted_array[$array_keys_2]=$array[$array_keys_2];
		}
		$array=$new_natsorted_array;
		return true;
	}

	public function getFestiveOfferMappingDetails(){
		$services = array();
		$discDurations = array();
		$discPerc = array();
		$festOffrLookObj = new billing_FESTIVE_OFFER_LOOKUP();
		$curLookupTable = $festOffrLookObj->retrieveCurrentLookupTable();
		$memHandlerObject = new MembershipHandler();
		$service_names = VariableParams::$mainMembershipNamesArr;
		foreach ($curLookupTable as $key => $value) {
			$tempID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);	
			$servID = $tempID[0];
			$servDur = $tempID[1];
			if(strpos($tempID[0], "L")){
				$servDur = "Unlimited";
				$servID = substr($tempID[0], 0, -1);
			}
			if($servDur == "Unlimited" || $servDur == 12){
				$allowDur = "N";
				$allowPerc = "Y";
			} else {
				$allowDur = "Y";
				$allowPerc = "N";
			}
			$servDurArr[] = array();
			if(!in_array($servDur, $servDurArr)){
				$servDurArr[] = $servDur;
				$displayOverall = 'Y';
			} else {
				$displayOverall = 'N';
			}
			$servName = $service_names[$servID];
			//$dispName = $servName." - ".$servDur." Months"; //temporarily disabling display of all services
			$dispName = $servDur." Months";
			$perc = $value['DISCOUNT_PERCENT'];
			$offrDur =  $value['DISCOUNT_DURATION'];
			$output[$key] = array('NAME'=>$dispName,'DUR'=>$offrDur,"PERC"=>$perc,"PERC_ENABLE"=>$allowPerc,"DUR_ENABLE"=>$allowDur,"DISPLAY_FLAG"=>$displayOverall,"MONTHS"=>$servDur);
		}
		$output = array_reverse($output);
		return $output;
	}

	public function getMonthDropDown($startDt, $endDt){
		$display_month['select'] = "Select";
		$start = strtotime($startDt);
		$end = strtotime($endDt);
		$month = $start;
		$months[] = date('Y-m', $start);
		while($month <= $end) {
		  $month = strtotime("+1 month", $month);
		  $display_month[date('Y-m', $month)] = date('F', $month);
		}
		return $display_month;
	}

	public function getDaysInMonthDropDown($startDt, $endDt){
		$start = strtotime($startDt);
		$end = strtotime($endDt);
		$month = $start;
		$months[] = date('Y-m', $start);
		while($month <= $end) {
		  $month = strtotime("+1 month", $month);
		  $display_month[date('Y-m', $month)]['select'] = "Select";
		  $maxDays = date('t', $month);
		  $i = 1;
		  while($i <= $maxDays) {
		  	$display_month[date('Y-m', $month)][$i] = $i;
		  	$i++;
		  }
		}
		return $display_month;
	}
}
