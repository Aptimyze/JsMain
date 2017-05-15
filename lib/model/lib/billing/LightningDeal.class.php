<?php

/*This class provides functionality for Lightning Deal offer(fetching eligible pool, storing * pool)
* @author: Ankita
*/

class LightningDeal 
{
	private $dealConfig;
	private $debug;

	public function __construct($debug=0){
		$this->dealConfig = VariableParams::$lightningDealOfferConfig;
		$this->debug = $debug;
		$this->sqlSelectLimit = 500;
	}

	/*Pool 1-all currently free users who have logged-in in the last 30 days*/
	public function fetchDealPool1(){
        $lastLoggedInOffset = $this->dealConfig["lastLoggedInOffset"] - 1;
        $todayDate = date("Y-m-d");
		$offsetDate = date("Y-m-d",strtotime("$todayDate -".$lastLoggedInOffset." days"));
		$start = 0;
		$pool1 = array();

		//use billing.DISCOUNT_HISTORY to get last logged in pool within offset time
        $discTrackingObj = new billing_DISCOUNT_HISTORY("crm_slave");
        $totalCount = $discTrackingObj->getLastLoginProfilesAfterDateCount($offsetDate);
        $serviceStObj = new billing_SERVICE_STATUS("crm_slave");
        while($start<$totalCount){
        	$lastLoggedInArr = $discTrackingObj->getLastLoginProfilesAfterDate("",$offsetDate,$this->sqlSelectLimit,$start);
     		
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
	        echo "after last 30 days login and currently free filter,pool 1.."."\n";
	        print_r($pool1);
	    }
	    return $pool1;
	}

	/*Pool 2-Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed)*/
	public function fetchDealPool2($pool1=null){
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
		if($this->debug == 1){
	        echo "after last 30 days lightning discount activated and viewed filter,pool 2.."."\n";
	        print_r($pool2);
	    }
		return $pool2;
	}

	/*Final Pool: Pick n number of users from pool in point 2 where n is 10% of the number of users in pool 1*/
	public function fetchDealFinalPool($pool1=null,$pool2=null){
		if(is_array($pool1)){
			$n = round(($this->dealConfig["pool2FilterPercent"] * count($pool1))/100);
			if(is_array($pool2) && $n>0){
				$finalPool = array_slice($pool2, 0,$n);
			}
		}
		
		if($this->debug == 1){
	        echo "final pool with n= ".$n." count..."."\n";
	        print_r($finalPool);
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
        if($this->debug == 1){
	        echo "\nfinal pool with discount \n";
	        print_r($result);
	    }
        return $result;
    }

	public function storeDealEligiblePool($finalPool=null){
		if(is_array($finalPool)){
            print_r($finalPool);
            $lightningDiscObj = new billing_LIGHTNING_DEAL_DISCOUNT();
            foreach($finalPool as $key => $val){
                $params["PROFILEID"] = $val["PROFILEID"];
                $params["DISCOUNT"] = max($val["P_MAX"],$val["C_MAX"],$val["NCP_MAX"],$val["X_MAX"]) + 5;
                $params["STATUS"] = "N";
                $params["ENTRY_DT"] = date('Y-m-d H:i:s');
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
        if($profileid && CommonFunction::getMembershipName($profileid) == "Free"){
            $dateGreaterThan = date('Y-m-d H:i:s', strtotime('-1 day', strtotime(date('Y-m-d H:i:s'))));
            $lightningObj = new billing_LIGHTNING_DEAL_DISCOUNT("crm_slave");
            $data = $lightningObj->getLightningDealDiscountData($profileid,$dateGreaterThan);
            
            $memHandlerObj = new MembershipHandler();
            $hamburgerMsg = $memHandlerObj->fetchHamburgerMessage($request);
            $currentMaxDisc = $hamburgerMsg["maxDiscount"];
            if($data && ($currentMaxDisc <= $data["DISCOUNT"])){
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
        $data = $this->getLightningDealCalData($request);
        if($data){
            $loginData = $request->getAttribute("loginData");
            $profileid = $loginData["PROFILEID"];
            $endTime = $this->activateLightningDealForProfile($profileid);
            $data['endTimeInSec'] = strtotime($endTime) - strtotime(date('Y-m-d H:i:s'));
            $memHandler = new MembershipHandler();
            $memHandler->clearMembershipCacheForProfile($profileid); 
        }
        return $data;
    }
}
?>