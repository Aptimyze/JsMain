<?php

/** 
 * @author Sriram Viswanathan, Aman Sharma & Ankit Aggarwal
 * @copyright Copyright 2008, Infoedge India Ltd.
 */

if (JsConstants::$whichMachine != 'matchAlert') {
	include_once (JsConstants::$docRoot . "/billing/comfunc_sums.php");
	include_once (JsConstants::$docRoot . "/P/pg/functions.php");
}

class Services
{   
    /**
     * This function is used to find all the active services.
     *
     * @param string $online flag to find all or only online(shown on site) services.
     * @return array $services which contains details of all the active services.
     */
    
    private $offer_discount = 10;
    
    public function getAllServices($online = "",$profileid="") {
        if(!empty($online)){

            if(empty($profileid)){
                $online = "-1";
            }
            else{
                $profileObj = LoggedInProfile::getInstance('newjs_slave',$profileid);
                $profileObj->getDetail($profileid, 'PROFILEID', 'MTONGUE');
                if($profileObj != null){
                    $online = $profileObj->getMTONGUE();
                }
                if(empty($online)){
                    $online = "-1";
                }
                unset($profileObj);
                var_dump($profileObj);die;
            }
        }
    	$billingServicesObj = new billing_SERVICES();
        $services = $billingServicesObj->getAllServiceDataForActiveServices($online);
        return $services;
    }
    
    /**
     * This function is used in crm to find all the active services without multiple matri-profile
     *
     * @return array $services which contains details of all the active services.
     */
    public function getAllServices_crm() {
		$billingServicesObj = new billing_SERVICES();
        $services = $billingServicesObj->getAllServiceDataForActiveServices();
        foreach ($services as $key=>$val) {
            if (strpos($val["SERVICEID"], 'M') && $val["SERVICEID"] != 'M3' && $val["SERVICEID"] != 'M') {
            	unset($services[$key]);
            }
        }
        return $services;
    }
    
    /**
     * This function is used to find the rights associated with serviceid(s).
     *
     * @param string $serviceid coma separated string/single serviceid.
     * @return array $rights array with all the rights for the specified serviceid(s)
     */
    public function getRights($serviceid) {
    	if(!empty($serviceid)){
	        $serviceid_arr = @explode(",", $serviceid);
	        unset($rights);
	        $billingCompObj = new billing_COMPONENTS();
	        for ($i = 0; $i < count($serviceid_arr); $i++) {
	            $packRights = $billingCompObj->getPackRights($serviceid_arr[$i]);
	            if(empty($packRights)){
	            	$rights[] = $billingCompObj->getRights($serviceid_arr[$i]);
	            } else {
	            	$rights[] = $packRights;
	            }
	        }
	    }
        return $rights;
    }
    
    /**
     * This function is used to find the price corresponding to a service.
     *
     * @param string $serviceid coma separated string/single serviceid.
     * @param string $curtype currency type, can be RS or DOL.
     * @return array $services_amount associateive array with PRICE for each service.
     */
    public function getServicesAmount($serviceid, $curtype, $device='desktop') {
        $serviceid = @explode(",", $serviceid);
        $serviceid = @implode("','", $serviceid);
        
		if(!empty($serviceid)){
			$serviceid = "'".$serviceid."'";
			$billingServicesObj = new billing_SERVICES();
			$allServiceDetails = $billingServicesObj->fetchAllServiceDetails($serviceid);
        }
        foreach ($allServiceDetails as $key=>$val) {
            $id = $val["SERVICEID"];
            $services_amount[$id]['SERVICEID'] = $id;
            if ($curtype == "DOL"){
            	$services_amount[$id]['PRICE'] = $val[$device."_DOL"];
            } else {
            	$services_amount[$id]['PRICE'] = $val[$device."_RS"];
            }
            $services_amount[$id]['PRICE_RS'] = $val[$device."_RS"]*(1-(billingVariables::TAX_RATE/100));
            $services_amount[$id]['PRICE_DOL'] = $val[$device."_DOL"]*(1-(billingVariables::TAX_RATE/100));
            $services_amount[$id]['NAME'] = $val["NAME"];
        }
        
        return $services_amount;
    }
    
    /**
     * This function is used to find total price corresponding to all services -- without tax.
     *
     * @param string $serviceid coma separated string/single serviceid.
     * @param string $curtype currency type, can be RS or DOL.
     * @return $price with TOTAL PRICE .
     */
    public function getTrueService($serviceid) {
        if (strstr($serviceid, 'M')) {
            $service = @explode(',', $serviceid);
            for ($i = 0; $i < count($service); $i++) {
                if (strstr($service[$i], 'P') || strstr($service[$i], 'C')) {
                    $main = $service[$i];
                    $a = $i;
                }
                if (strstr($service[$i], 'M')) {
                    $matri = $service[$i];
                    $b = $i;
                }
            }
            if (strstr($serviceid, 'P') || strstr($serviceid, 'C')) {
                if (strstr($service[$a], 'L') || strstr($service[$a], '9')) {
                    if (strstr($service[$b], '9')) {
                        return $serviceid;
                    } 
                    else {
                        $service[$b] == "M9";
                        $serviceid = @implode(',', $service);
                        return $serviceid;
                    }
                }
                if (substr($service[$a], 1, 1) == substr($service[$b], 1, 1)) {
                    return $serviceid;
                } 
                else {
                    $service[$b] = 'M' . substr($service[$a], 1, 1);
                    $serviceid = @implode(',', $service);
                    return $serviceid;
                }
            } 
            else {
                if ($matri == 'M') return $serviceid;
                else {
                    $matri = "M";
                    $service[$b] = $matri;
                    $serviceid = @implode(',', $service);
                    return $serviceid;
                }
            }
        } 
        else {
            return $serviceid;
        }
    }

    public function getTotalPrice($serviceid, $curtype, $device = 'desktop') {  
        $serviceid = @explode(",", $serviceid);
        $serviceid = @implode("','", $serviceid);

        if(!empty($serviceid)){
			$serviceid = "'".$serviceid."'";
			$billingServicesObj = new billing_SERVICES('newjs_slave');
			$allServiceDetails = $billingServicesObj->fetchAllServiceDetails($serviceid);
        }
        $price = 0;
        foreach ($allServiceDetails as $key=>$val) {
        	if ($curtype == "DOL"){
        		$price += $val[$device."_DOL"];	
        	} else {
        		$price += $val[$device."_RS"];
        	}
        }
        return $price;
    }
    
    /**
     * This function is used to find price corresponding to a service -- without tax.
     *
     * @param string $serviceid coma separated string/single serviceid.
     * @param string $curtype currency type, can be RS or DOL.
     * @return array $services_amount associateive array with PRICE for each service.
     */
    public function getServicesAmountWithoutTax($serviceid, $curtype, $device='desktop') {
        $serviceid = @explode(",", $serviceid);
        $serviceid = @implode("','", $serviceid);
        
        if(!empty($serviceid)){
			$serviceid = "'".$serviceid."'";
			$billingServicesObj = new billing_SERVICES();
			$allServiceDetails = $billingServicesObj->fetchAllServiceDetails($serviceid);
        }
        foreach ($allServiceDetails as $key=>$val) {
        	$id = $val["SERVICEID"];
        	$services_amount[$id]['SERVICEID'] = $id;
        	if ($curtype == "DOL"){
        		$services_amount[$id]['PRICE'] = $val[$device."_DOL"];	
        	} else {
        		$services_amount[$id]['PRICE'] = $val[$device."_RS"];
        	}
        }
        
        return $services_amount;
    }
    
    /**
     * This function is used to find the previous expiry date (if any) for a service
     *
     * @param string $profileid unique id corresponding to a profile.
     * @param string $rights_str rights of the service(s).
     * @return array $previous_expiry_date if it exists, false otherwise.
     */
    public function getPreviousExpiryDate($profileid, $rights_str) {
        $rightsArr = explode(",", $rights_str);
        if (in_array("F", $rightsArr) && (in_array("D", $rightsArr) || in_array("X", $rightsArr))) {
        	$rights_str = 'F';
        }
        $billingServicesObj = new billing_SERVICES();
        $previous_expiry_date = $billingServicesObj->getPreviousExpiryDate($profileid, $rights_str);
        if(!empty($previous_expiry_date)){
        	return $previous_expiry_date; 
        } else {
        	return false;
        }
    }
    
    /**
     * This function is used to find the duration in days for a service.
     *
     * @param string $serviceid id of the service and $period is either 'D' for days or 'M' for months
     * @return int $duration in days or months
     */
    public function getDuration($serviceid, $period = 'D') {
        $days_per_month = 30;
    	$billingCompObj = new billing_COMPONENTS();
    	$tempDurArr = array();
    	if(is_array($serviceid)){
    		$duration1 = $billingCompObj->getDurationForServiceArrWithoutJoin($serviceid);
    		foreach($serviceid as $key=>$val){
    			if(empty($duration1[$val])){
		    		$tempDurArr[] = $val;
		    	} 
		    }
		    $duration2 = $billingCompObj->getDurationForServiceArrWithJoin(array_values($tempDurArr));
		    $duration = array_merge_recursive($duration1, $duration2);
		    foreach($serviceid as $key=>$val){
		    	if ($period == 'D') {
		    		$finalDuration[$val] = $duration[$val] *  $days_per_month;
		    	} else {
		    		$finalDuration[$val] = $duration[$val];
		    	}		
    		}
    	} else {
    		$duration = $billingCompObj->getDurationForServiceArrWithoutJoin($serviceid);
	    	if(empty($duration)){
	    		$duration = $billingCompObj->getDurationForServiceArrWithJoin($serviceid);
	    	}
	    	if ($period == 'D') {
	    		$finalDuration = $duration *  $days_per_month;
	    	} else {
	    		$finalDuration = $duration;
	    	}	
    	}
    	return $finalDuration;
    }
    
    /**
     * This function is used to find the name of components under the package
     *
     * @param string $serviceid id of the service(s)
     * @return array $comp_ar name of each components corresponding to each serviceid(s)
     */
    public function getPackServices($serviceid) {
        $billingCompObj = new billing_COMPONENTS();
        $comp_ar = $billingCompObj->getPackServices($serviceid);
        return $comp_ar;
    }
    
    /**
     * This function is used to find the name of service
     *
     * @param string $serviceid id of the service(s)
     * @return array $service_name name of each service corresponding to each serviceid(s)
     */
    public function getServiceName($serviceid) {
    	
    	if(is_array($serviceid) && !empty($serviceid)){
    		$serviceid = @implode(",", $serviceid);
    	}

        $serviceid = str_replace('P1188', 'PL', $serviceid);
        $serviceid = str_replace('C1188', 'CL', $serviceid);
        $serviceid = str_replace('A0.5', 'A2W', $serviceid);
        $serviceid = str_replace('B0.5', 'B2W', $serviceid);
        $serviceid = str_replace('P0.5', 'P2W', $serviceid);
        $serviceid = str_replace('C0.5', 'C2W', $serviceid);
        $serviceid = str_replace('P1.5', 'P6W', $serviceid);
        $serviceid = str_replace('C1.5', 'C6W', $serviceid);
        $serviceid = str_replace('P0.07', 'P1W', $serviceid);
        $serviceid = str_replace('C0.07', 'C1W', $serviceid);
        $serviceid_arr = @explode(",", $serviceid);
        $serviceid_str = "'".@implode("','", $serviceid_arr)."'";
        
        $billingServicesObj = new billing_SERVICES('newjs_slave');
        $serviceDetails = $billingServicesObj->fetchAllServiceDetails($serviceid_str);
        foreach($serviceid_arr as $key=>$val){
        	foreach($serviceDetails as $kk=>$vv){
        		if($val == $vv['SERVICEID']){
        			$id = $vv["SERVICEID"];		
        			$service_name[$id]["NAME"] = $vv["NAME"];
        		}
        	}
        }
        
        return $service_name;
    }

    public function getServiceInfo($id, $cur_type = 'RS', $offer = 0, $renew = '', $profileid = '', $device='desktop', $userObj,$fetchOnline=true,$fetchOffline=false) {

        global $user_disc;
        $search_id = "";

        if(is_array($id)){
        	foreach($id as $key=>$val){
        		if($key == 0){
        			$search_id .= "SERVICEID LIKE '{$val}%'";
        		} else {
        			$search_id .= " OR SERVICEID LIKE '{$val}%'";
        		}
        	}
        } else {
        	$search_id = $id . '%';
        }
        
        $billingServicesObj = new billing_SERVICES('newjs_slave');
        $discountOfferObj = new billing_DISCOUNT_OFFER('newjs_slave');
        
        if ($cur_type == 'DOL') {
        	$price_str = $device."_DOL";
        } else {
        	$price_str = $device."_RS";
        }

        $mtongue = "-1";
        if(!empty($userObj) && $userObj!=""){
            $mtongue = $userObj->mtongue;
        }
        else if($profileid != ""){
            error_log("ankita check why in this case");
            $profileObj = LoggedInProfile::getInstance('newjs_slave',$profileid);
            $profileObj->getDetail($profileid, 'PROFILEID', 'MTONGUE');
            if($profileObj != null){
                $mtongue = $profileObj->getMTONGUE();
            }
            if($mtongue == null || $mtongue == ""){
                $mtongue = "-1";
            }
            unset($profileObj);
        }
        
        $row_services = $billingServicesObj->getServiceInfo($search_id,$id,$offer,$price_str,$fetchOnline,$fetchOffline,$mtongue);
        
        $i = 0;
        
        if (!empty($userObj)){
        	if($userObj->getFestInfo()){
        		$fest = 1;
	            $festiveOfferLookupObj = new billing_FESTIVE_OFFER_LOOKUP('newjs_slave');
	            $festiveDetailsArr = $festiveOfferLookupObj->retrieveCurrentLookupTable();
        	}
        } else if ($this->getFestive()) {
            $fest = 1;
            $festiveOfferLookupObj = new billing_FESTIVE_OFFER_LOOKUP('newjs_slave');
            $festiveDetailsArr = $festiveOfferLookupObj->retrieveCurrentLookupTable();
        }
        
        if ($renew) {
        	$user_disc = $renew;
        }

        $billingDccObj = new billing_DIRECT_CALL_COUNT('newjs_slave');
        $direct_call = $billingDccObj->getDirectCallCountForServiceArr(array_keys($row_services));

        $cashDiscountArr = $discountOfferObj->getDiscountOfferForServiceArr(array_keys($row_services));

        $componentsDurArr = $this->getDuration(array_keys($row_services), 'M');
        
        foreach ($row_services as $key=>$val) {
            $serviceid = $key;
            
            if ($direct_call[$serviceid]) {
            	$services[$serviceid]['CALL'] = $direct_call[$serviceid];
            } else {
            	$services[$serviceid]['CALL'] = "";
            }

            if($fest = 1) {
            	$festiveDuration = $festiveDetailsArr[$serviceid]['DISCOUNT_DURATION'];
            	$festiveDiscountPercent = $festiveDetailsArr[$serviceid]['DISCOUNT_PERCENT']; 
            	if ($festiveDiscountPercent > 0){ 
            		$disc_str = '&nbsp;&nbsp;+ ' . $festiveDiscountPercent . '% DISCOUNT';
            	} elseif ($festiveDuration > 0) {
	                $disc_str = "&nbsp;&nbsp;+ " . $festiveDuration . " month FREE offer";
	            }
            }
            
            $services[$serviceid]['PRICE'] = $row_services[$serviceid]["PRICE"];
            $services[$serviceid]['FESTIVE_PRICE'] = $row_services[$serviceid]["PRICE"];
            $services[$serviceid]['DISCOUNT_PRICE'] = $row_services[$serviceid]["PRICE"];
            $services[$serviceid]['SPECIAL_DISCOUNT_PRICE'] = $row_services[$serviceid]["PRICE"];
            $services[$serviceid]['SHOW_ONLINE'] = $row_services[$serviceid]["SHOW_ONLINE"];
            if (strpos($serviceid, "ESP") !== false || strpos($serviceid, "NCP") !== false) {
                $durd = substr($serviceid, strlen($serviceid) - 1);
            } 
            else {
            	$durd = substr($serviceid, 1);
            }
            
            if ($fest) {
                $festiveDiscountPercent = $festiveDetailsArr[$serviceid]['DISCOUNT_PERCENT'];
                if ($festiveDiscountPercent > 0) {
                	$services[$serviceid]['FESTIVE_PRICE'] = $services[$serviceid]['PRICE'] - round(($services[$serviceid]['PRICE'] * $festiveDiscountPercent) / 100, 2);
                }
            }
            
            $discountSrvc = $cashDiscountArr[$serviceid];
            $services[$serviceid]['DISCOUNT_PRICE'] = $services[$serviceid]['PRICE'] - round(($services[$serviceid]['PRICE'] * $discountSrvc) / 100, 2);
            
            $services[$serviceid]['DURATION'] = $componentsDurArr[$serviceid];
            unset($festiveDuration);
            unset($festiveDiscountPercent);
        }
        return $services;
    }
    
    public function getDiscountedPrice($discount_type, $service_price, $serviceid = '', $profileid = '', $festFlag=NULL, $festDisc=NULL) {
        if ($discount_type == 1 || $discount_type == 5 || $discount_type == 10 || $discount_type == 11) {
            global $renew_discount_rate;
            if (!$renew_discount_rate) {
                $memHandlerObj = new MembershipHandler();
                $renew_discount_rate = $memHandlerObj->getVariableRenewalDiscount($profileid);
            }
            return round(($renew_discount_rate / 100) * $service_price, 2);
        } 
        else if ($discount_type == 6) {
        	// when having internal call within this file
        	if($festFlag != NULL){
        		return round(($festDisc / 100) * $service_price, 2);
        	} elseif ($this->getFestive()) {
                if ($serviceid) {
                    $festiveOfferLookupObj = new billing_FESTIVE_OFFER_LOOKUP();
                    $festiveDiscountPercent = $festiveOfferLookupObj->getPercDiscountOnService($serviceid);
                    if ($festiveDiscountPercent > 0) return round(($festiveDiscountPercent / 100) * $service_price, 2);
                }
            } 
            else return 0;
        }
    }
    
    public function get_matri_duration($SERVICE) {
        if (in_array('M9', $SERVICE)) {
            foreach ($SERVICE as $k => $v) {
                if (strstr($v, 'C') || strstr($v, 'P')) {
                    $dur = substr($v, -1);
                    break;
                }
            }
            if ($dur) {
                foreach ($SERVICE as $k => $v) if ($v == 'M9' && is_numeric($dur)) $services[] = 'M' . $dur;
                else $services[] = $v;
            } 
            else foreach ($SERVICE as $k => $v) if ($v == 'M9') $services[] = 'M';
            else $services[] = $v;
        } 
        else $services = $SERVICE;
        return $services;
    }
    
    public function get_servicename($sid) {
        $sid_arr = explode(",", $sid);
        $sid_str = implode("','", $sid_arr);
        $billingServicesObj = new billing_SERVICES();
        $service_arr = $billingServicesObj->getServices($sid_str);
        $services = implode(",", $service_arr);
        return $services;
    }
    
    public function populate_service_duration() {
        $billingCompObj = new billing_COMPONENTS();
        $allServiceData = $billingCompObj->getServiceDurationData();

        foreach ($allServiceData as $key=>$val) {
            if (!@in_array($val['DURATION'], $val_arr)) {
                $dur = $val['DURATION'];
                $exp = explode("-", $val['NAME']);
                $val_arr[] = $dur;
                $dur_arr[$dur] = "For " . trim($exp[1]);
            }
        }
        
        foreach ($allServiceData as $key=>$val) {
            $right = $val['RIGHTS'];
            $dur = $val['DURATION'];
            $service_duration[$right][$dur] = $dur_arr[$dur];
        }
        return $service_duration;
    }
    
    public function populate_service_count() {
        $billingCompObj = new billing_COMPONENTS();
        $allSerCountData = $billingCompObj->getServiceCountData();
        foreach ($allSerCountData as $key=>$val) {
            $count = $val['ACC_COUNT'];
            $val = $count . " Calls";
            $service_count[$count] = $val;
        }
        return $service_count;
    }
    
    public function getServiceType($service) {
        $billingCompObj = new billing_COMPONENTS();
        $type = $billingCompObj->getServiceType($service);
        return $type;
    }
    
    public function getCount($service) {
    	$billingCompObj = new billing_COMPONENTS();
        $count = $billingCompObj->getAccCount($service);
        return $count;
    }
    
    public function getServiceDirectCalls($service) {
    	$billingDccObj = new billing_DIRECT_CALL_COUNT();
    	$count = $billingDccObj->getDirectCallCountForServiceArr($service);
        $count = $count[$service];
        if ($count) return $count;
        else return 0;
    }
    
    public function getDiscountStr($service) {
        $str = "";
        if ($this->getFestive()) {
            $festiveOfferLookupObj = new billing_FESTIVE_OFFER_LOOKUP();
            $festiveDiscountPercent = $festiveOfferLookupObj->getPercDiscountOnService($service);
            $festiveDuration = $festiveOfferLookupObj->getDurationDiscountOnService($service);
            if ($festiveDiscountPercent > 0) $str = '&nbsp;&nbsp;+ ' . $festiveDiscountPercent . '% DISCOUNT';
            elseif ($festiveDuration > 0) {
                $str = "&nbsp;&nbsp;+ " . $festiveDuration . " month FREE offer";
            }
        }
        return $str;
    }
    
    public function getOfferPrice($price, $festiveDiscountPercent = '', $festFlag = NULL) {
        // when calling this function locally within file
        if($festFlag != NULL){
        	if ($festiveDiscountPercent) $dis_price = ceil((1 - ($festiveDiscountPercent / 100)) * $price);
        } elseif ($this->getFestive()) {
            if ($festiveDiscountPercent) $dis_price = ceil((1 - ($festiveDiscountPercent / 100)) * $price);
        } 
        else $dis_price = $price;
        return $dis_price;
    }
    
    public function getFestive() {
        $billingFestObj = new billing_FESTIVE_LOG_REVAMP('newjs_slave');
        $isFestive = $billingFestObj->getFestiveFlag();
        return $isFestive;
    }
    
    public function getFestivalBanner() {
    	$billingFestObj = new billing_FESTIVE_LOG_REVAMP();
        $festId = $billingFestObj->getFestivalBanner();
        return $festId;
    }
    
    public function getDiscountMsg($flag = '', $per = '') {
        if ($flag == '5') $msg = $per . "% 'Special User Discount'";
        elseif ($flag == '6') $msg = $per . "% Festival Discount";
        else $msg = '15% Renewal Discount';
        return $msg;
    }
    
    public function OfferMapping($serviceid) {
        if ($serviceid) {
            if ($this->getFestive()) {
                $serviceidArr = explode(',', $serviceid);
                $lookupObj = new billing_FESTIVE_OFFER_LOOKUP();
                
                foreach ($serviceidArr as $k => $serv) {
                    $offered_serviceid = $lookupObj->fetchOfferedServiceId($serv);
                    if ($offered_serviceid) $serviceid = str_replace($serv, $offered_serviceid, $serviceid);
                }
                unset($lookupObj);
            }
            return $serviceid;
        }
    }
    public function getLowestActiveMainMembership($serviceArr = "", $device='desktop',$mtongue="-1") {
        $billingServicesObj = new billing_SERVICES('newjs_slave');
        $output = $billingServicesObj->getLowestActiveMainMembership($serviceArr, $device,$mtongue);
        return $output;
    }
    
    public function getMostPopular() {
        $billingServicesObj = new billing_SERVICES();
        $most_popular = $billingServicesObj->getMostPopularMembershipList();
        return $most_popular;
    }
    
    public function getActiveServices() {
        $billingServicesObj = new billing_SERVICES('newjs_slave');
        $serviceTabs = $billingServicesObj->getEnabledServices();
        return $serviceTabs;
    }

    public function getAddOnInfo($cur_type = 'RS', $offer = 0, $device='desktop') {
        if ($cur_type == 'DOL') {
        	$price_str = $device."_DOL";
        } else {
        	$price_str = $device."_RS";
        }

        $billingServicesObj = new billing_SERVICES('newjs_slave');
        $addon = $billingServicesObj->getAddOnInfo($price_str,$offer);
        
        return $addon;
    }
}
?>
