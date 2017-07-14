<?php

/*This class provides functionality for Lightning Deal offer(fetching eligible pool, storing * pool)
* @author: Ankita
*/

class LightningDeal 
{
	private $dealConfig;
	private $debug;

	public function __construct($debug=0,$logFilePath=""){
		$this->dealConfig = VariableParams::$lightningDealOfferConfig;
		$this->debug = $debug;
		$this->sqlSelectLimit = 5000;
        $this->logFilePath = $logFilePath;
	}

	/*Pool 1-all currently free users who have logged-in in the last 30 days*/
	public function fetchDealPool1(){
        if($this->debug == 1){
            error_log("pool1 generation started"."\n",3,$this->logFilePath);
        }
        $lastLoggedInOffset = $this->dealConfig["lastLoggedInOffset"] - 1;
        $todayDate = date("Y-m-d");
		$offsetDate = date("Y-m-d",strtotime("$todayDate -".$lastLoggedInOffset." days"));
		$start = 0;
		$pool1 = array();

		//use billing.DISCOUNT_HISTORY to get last logged in pool within offset time
        $discTrackingObj = new billing_DISCOUNT_HISTORY_MAX();
        $totalCount = $discTrackingObj->getLastLoginProfilesAfterDateCountMax($offsetDate);
       
        $serviceStObj = new billing_SERVICE_STATUS("crm_slave");
        while($start<$totalCount){
            if($this->debug == 1){
                error_log("pool1 generation: ".$start."-".($start+$this->sqlSelectLimit-1)."\n",3,$this->logFilePath);
            }
            $startQueryTime = microtime(true);
        	$lastLoggedInArr = $discTrackingObj->getLastLoginProfilesMaxAfterDate("",$offsetDate,$this->sqlSelectLimit,$start);
     		$endQueryTime = microtime(true);
            if($this->debug==1)
                error_log("diff- ".($endQueryTime-$startQueryTime)."\n",3,$this->logFilePath);
		    if(is_array($lastLoggedInArr) && count($lastLoggedInArr) > 0){
		    	//use billing.SERVICE_STATUS to get currently paid pool from $lastLoggedInArr
		    	$lastLoggedInProfiles = array_keys($lastLoggedInArr);
			    $lastLoggedInPaidPool = $serviceStObj->getCurrentlyPaidProfiles($lastLoggedInProfiles);
			    unset($lastLoggedInProfiles);

			    //remove currently paid pool from $lastLoggedInArr
			    if(is_array($lastLoggedInPaidPool)){
				    foreach ($lastLoggedInPaidPool as $key => $profileid) {
				    	if($lastLoggedInArr[$profileid]){
				    		unset($lastLoggedInArr[$profileid]);
				    	}
				    }
				}
			    unset($lastLoggedInPaidPool);
			    $pool1 = $pool1 + $lastLoggedInArr;
			}
			unset($lastLoggedInArr);
			$start += $this->sqlSelectLimit;
        }
        unset($serviceStObj);
        unset($discTrackingObj);
        if($this->debug == 1){
	        //echo "after last 30 days login and currently free filter,pool 1.."."\n";
	        //print_r($pool1);
            error_log("pool1 generation end"."\n",3,$this->logFilePath);
	    }
	    return $pool1;
	}

	/*Pool 2-Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed)*/
	public function fetchDealPool2($pool1=null){
        if($this->debug == 1){
            error_log("pool2 generation started"."\n",3,$this->logFilePath);
        }
		if(is_array($pool1) && count($pool1) > 0){
			$pool1Pids = array_keys($pool1);
			$lastViewedOffset = $this->dealConfig["lastLightningDiscountViewedOffset"] - 1;
			$todayDate = date("Y-m-d");
			$lastViewedDt = date("Y-m-d",strtotime("$todayDate -".$lastViewedOffset." days"));

			//use billing.LIGHTNING_DEAL_DISCOUNT to get list of profiles who have viewed lightning deal in past 30 days
			$lightningDiscObj = new billing_LIGHTNING_DEAL_DISCOUNT("crm_slave");
			$lastViewedPool = $lightningDiscObj->filterDiscountActivatedProfiles('','V',$lastViewedDt);
			unset($lightningDiscObj);

			//filter out above pool from pool1 to get pool2
			if(is_array($lastViewedPool)){
				$pool2 = array_diff($pool1Pids, $lastViewedPool);
			}
			else{
				$pool2 = $pool1Pids;
			}
			unset($lastViewedPool);
			unset($pool1Pids);
		}
		/*if($this->debug == 1){
	        echo "after last 30 days lightning discount activated and viewed filter,pool 2.."."\n";
	        print_r($pool2);
	    }*/
        if($this->debug == 1){
            error_log("pool2 generation end"."\n",3,$this->logFilePath);
        }
		return $pool2;
	}

	/*Final Pool: Pick n number of users from pool in point 2 where n is 10% of the number of users in pool 1*/
	public function fetchDealFinalPool($pool1=null,$pool2=null){
        if($this->debug == 1){
            error_log("final pool generation started"."\n",3,$this->logFilePath);
        }
		if(is_array($pool1)){
            if(count($pool1)<$this->dealConfig["pool2FilterPercent"]){
                $n = count($pool1);
            }
            else{
			    $n = round(($this->dealConfig["pool2FilterPercent"] * count($pool1))/100);
            }
			if(is_array($pool2) && $n>0){
				$finalPool = array_slice($pool2, 0,$n);
			}
		}
		
		/*if($this->debug == 1){
	        echo "final pool with n= ".$n." count..."."\n";
	        print_r($finalPool);
	    }*/
        if($this->debug == 1){
            error_log("final pool generation end"."\n",3,$this->logFilePath);
        }
		return $finalPool;
	}

	public function generateDealEligiblePool(){
		try{
			/*Pool 1-all currently free users who have logged-in in the last 30 days*/
			$pool1 = $this->fetchDealPool1();
            
			/*Pool 2-Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed)*/
			if(is_array($pool1)){
				$pool2 = $this->fetchDealPool2($pool1);
			}

			/*Final Pool: Pick n number of users from pool in point 2 where n is 10% of the number of users in pool 1*/
			if(is_array($pool2)){
				$finalPool = $this->fetchDealFinalPool($pool1,$pool2);
			}
            
            /*Storing discount in final pool from existing data of pool 1*/
            if(is_array($finalPool)){
                $finalPoolWithDiscount = $this->fetchFinalPoolWithDiscount($finalPool,$pool1);
            }
			unset($pool1);
			unset($pool2);
            unset($finalPool);
			return $finalPoolWithDiscount;
		}
		catch(Exception $e){
			$message = "Error in generateDealEligiblePool in LightningDeal class-".$e->getMessage();
            CRMAlertManager::sendMailAlert($message,"default");
			return null;
		}
	}
    
    /*Adding discount to final pool array*/
    public function fetchFinalPoolWithDiscount($finalPool=null,$pool1=null){
        if(is_array($finalPool) && is_array($pool1)){
            foreach($finalPool as $key => $val){
                $result[$val] = $pool1[$val];
            }
        }
        /*if($this->debug == 1){
	        echo "\nfinal pool with discount \n";
	        print_r($result);
	    }*/
        return $result;
    }

	public function storeDealEligiblePool($finalPool=null){
        if($this->debug == 1){
            error_log("store deal eligible pool"."\n",3,$this->logFilePath);
        }
		if(is_array($finalPool)){
            //print_r($finalPool);
            $lightningDiscObj = new billing_LIGHTNING_DEAL_DISCOUNT();
            foreach($finalPool as $key => $val){
                $params["PROFILEID"] = $val["PROFILEID"];
                $params["DISCOUNT"] = $val['MAX_DISCOUNT'] + 5;
                $params["STATUS"] = "N";
                $params["ENTRY_DT"] = date('Y-m-d H:i:s');
                $params["DEAL_DATE"] = date('Y-m-d');
                $params["DISCOUNT"] = min($params["DISCOUNT"],80);
                $lightningDiscObj->insertInLightningDealDisc($params);
            }
            unset($lightningDiscObj);
        }
	}
    
    /*Get lightning deal eligibility for  CAL and data*/
    public function getLightningDealCalData($request){
        $loginData = $request->getAttribute("loginData");
        $profileid = $loginData["PROFILEID"];
        if($profileid){
            $dateGreaterThan = date('Y-m-d H:i:s', strtotime('-1 day', strtotime(date('Y-m-d H:i:s'))));
            $lightningObj = new billing_LIGHTNING_DEAL_DISCOUNT("crm_slave");
            $data = $lightningObj->getLightningDealDiscountData($profileid,$dateGreaterThan);
            
            $memHandlerObj = new MembershipHandler();
            $hamburgerMsg = $memHandlerObj->fetchHamburgerMessage($request);
            $currentMaxDisc = $hamburgerMsg["maxDiscount"];
            if($data && ($data['STATUS'] == 'V' || $currentMaxDisc <= $data["DISCOUNT"])){
                $memHandlerObj = new MembershipHandler();
                list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency();
                $minActualPrice = $hamburgerMsg["startingPlan"]["origStartingPrice"];
                $minDiscountedPrice = $minActualPrice*((100-$data["DISCOUNT"])/100);
                if($currency == 'RS')
                    $symbol = '&#8377;';
                else if ($currency == 'DOL')
                    $symbol = '$';
                $result['line1'] = "Don't miss this rare opportunity!";
                $result['line2'] = $data["DISCOUNT"]."% OFF";
                $result['line3'] = "on all memberships";
                $result['line4'] = "Plan starts @$symbol{strikeoutPrice} $symbol$minDiscountedPrice";
                $result['strikeoutPrice'] = $minActualPrice;
                $result['discountedPrice'] = $minDiscountedPrice;
                $result['currencySymbol'] = $symbol;
                if($data['STATUS'])
                    $result['STATUS'] = $data['STATUS'];
                if($data['EDATE'])
                    $result['EDATE'] = $data['EDATE'];
                return $result;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    /*Activate Lighning Deal*/
    public function activateLightningDealForProfile($profileid){
        
        if($profileid){
            $lightningDuration = VariableParams::$lightningDealDuration;
            $params["PROFILEID"] = $profileid;
            $params["SDATE"] = date('Y-m-d H:i:s');
            $params["EDATE"] = date('Y-m-d H:i:s', strtotime("$lightningDuration minutes",  strtotime($params["SDATE"])));
            $params["STATUS"] = "V";
            $lightningObj = new billing_LIGHTNING_DEAL_DISCOUNT();
            $lightningObj->activateLightningDeal($params);
            return $params["EDATE"];
        }
    }
    
    /*Get lightning deal eligibility and data, activate offer and clear membership cache*/
    public function lightningDealCalAndOfferActivate($request){
        if(!VariableParams::$lightningDealOfferConfig["activeOfferFlag"]){
            return false;
        }
        $data = $this->getLightningDealCalData($request);
        if($data && $data['STATUS'] == 'V' && (strtotime($data['EDATE']) < strtotime(date('Y-m-d H:i:s'))))
            return false;
        if($data){
            $loginData = $request->getAttribute("loginData");
            $profileid = $loginData["PROFILEID"];
            if($data['STATUS'] == 'V')
                $endTime = $data['EDATE'];
            else
                $endTime = $this->activateLightningDealForProfile($profileid);
            $data['endTimeInSec'] = strtotime($endTime) - strtotime(date('Y-m-d H:i:s'));
            $memHandler = new MembershipHandler();
            $memHandler->clearMembershipCacheForProfile($profileid); 
            $appApiVersion = $request->getParameter('API_APP_VERSION');
            $memCacheObject = JsMemcache::getInstance();
            if (isset($appApiVersion) && is_numeric($appApiVersion)){
                $memCacheObject->delete($profileid."_MEM_OCB_MESSAGE_API".$appApiVersion);
            }
            MyJsMobileAppV1::deleteMyJsCache(array($profileid));            
            $memCacheObject->delete(myjsCachingEnums::PREFIX . $profileid . '_MESSAGE_BANNER');
        }
        return $data;
    }
    
    public function generateRenewalPoolWithDiscount(){
        $profiles = $this->generateRenewalProfilesPool();        
        if(is_array($profiles)){
            $profileDiscountPool = $this->getRenewalProfilesDiscount($profiles);
            return $profileDiscountPool;
        }
    }
    
    public function getRenewalProfilesDiscount($profiles){
        if(is_array($profiles)){
            $renewalDiscObj = new billing_RENEWAL_DISCOUNT("crm_slave");
            $count = count($profiles);
            $limit = 5000;
            $counter = 0;
            while($counter <= $count){
                unset($tempPool,$lastViewedPool);
                $tempPool = array_slice($profiles, $counter,$counter+$limit);
                $profileStr = implode(",", $tempPool);
                $discountArr = $renewalDiscObj->getProfilesDiscount($profileStr,date('Y-m-d'));
                if(is_array($discountArr)){
                    foreach($discountArr as $key=>$val){
                        $resultSet[$val["PROFILEID"]]["PROFILEID"] = $val["PROFILEID"];
                        $resultSet[$val["PROFILEID"]]["MAX_DISCOUNT"] = $val["DISCOUNT"];
                    }
                }
                $counter+=$limit;
                if($this->debug == 1){
                    error_log("getRenewalProfilesDiscount iteration".count($resultSet)."\n",3,$this->logFilePath);
                }
            }
            
            if($this->debug == 1){
                error_log("getRenewalProfilesDiscount final set".count($resultSet)."\n",3,$this->logFilePath);
            }
            return $resultSet;
        }
    }
    
    public function generateRenewalProfilesPool(){
        
        $expDate1 = date('Y-m-d');
        $expDate2 = date('Y-m-d',  strtotime('+2 Days'));
        $serviceStatusObj = new BILLING_SERVICE_STATUS("crm_slave");
        $data = $serviceStatusObj->getRenewalProfilesForDates($expDate1, $expDate2);
        if(is_array($data)){
            foreach($data as $key => $val){
                $profiles[$val["PROFILEID"]] = $val;
            }
        }
        $count = count($data);
        $limit = 5000;
        $counter = 0;
        $lightningDiscObj = new billing_LIGHTNING_DEAL_DISCOUNT("crm_slave");
        $lastViewedOffset = $this->dealConfig["lastLightningDiscountViewedOffset"] - 1;
        $todayDate = date("Y-m-d");
        $lastViewedDt = date("Y-m-d",strtotime("$todayDate -".$lastViewedOffset." days"));
        if(is_array($profiles)){
            $profileidArr = array_keys($profiles);
        }
        if(is_array($profileidArr)){
            while($counter <= $count){
                unset($tempPool,$lastViewedPool);
                $tempPool = array_slice($profileidArr, $counter,$counter+$limit);
                $lastViewedPool = $lightningDiscObj->filterDiscountActivatedProfiles($tempPool,array('V','A'),$lastViewedDt);
                if(is_array($lastViewedPool)){
                    foreach($lastViewedPool as $key => $profileid){
                        unset($profiles[$profileid]);
                    }
                }
                $counter+=$limit;
                if($this->debug == 1){
                    error_log("removed in renewal iteration ".count($tempPool)."\n",3,$this->logFilePath);
                }
            }
        }
        unset($lightningDiscObj);
        if(is_array($profiles))
            $profileArr = array_keys($profiles);
        if($this->debug == 1){
            error_log("After generateRenewalProfilesPool ".count($profileArr)."\n",3,$this->logFilePath);
        }
        return $profileArr;
    }
}
?>