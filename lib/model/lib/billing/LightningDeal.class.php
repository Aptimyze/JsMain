<?php

/*This class provides functionality for Lightning Deal offer
* @author: Ankita
*/

class LightningDeal 
{
	private $dealConfig;
	private $debug;

	public function __construct($debug=0){
		$this->dealConfig = VariableParams::$lightningDealOfferConfig;
		$this->debug = $debug;
	}

	/*Pool 1-all currently free users who have logged-in in the last 30 days*/
	public function fetchDealPool1(){
        $lastLoggedInOffset = $this->dealConfig["lastLoggedInOffset"] - 1;
        $todayDate = date("Y-m-d");
		$offsetDate = date("Y-m-d",strtotime("$todayDate -".$lastLoggedInOffset." days"));

		//use billing.DISCOUNT_HISTORY to get last logged in pool within offset time
        $discTrackingObj = new billing_DISCOUNT_HISTORY("newjs_slave");
        $lastLoggedInArr = $discTrackingObj->getLastLoginProfilesAfterDate("",$offsetDate);
        unset($discTrackingObj);
        if($this->debug == 1){
	        echo "last logged in pool.."."\n";
	        print_r($lastLoggedInArr);
	    }

	    //use newjs.JPROFILE to get currently free pool from $lastLoggedInArr
	    if(is_array($lastLoggedInArr)){
		    $jprofileObj = new JPROFILE("newjs_slave");
		    $lastLoggedInFreePool1 = $jprofileObj->getProfileSelectedDetails($lastLoggedInArr,"PROFILEID",array("activatedKey"=>1,"SUBSCRIPTION"=>''));
		    unset($jprofileObj);
		    if(is_array($lastLoggedInFreePool1)){
		    	$pool1 = array_keys($lastLoggedInFreePool1);
		    }
		    if($this->debug == 1){
		        echo "after last login and currently free filter,pool1.."."\n";
		        print_r($pool1);
		    }
		}
	    return $pool1;
	}

	/*Pool 2-Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed)*/
	public function fetchDealPool2($pool1=null){
		if(is_array($pool1)){
			$lastViewedOffset = $this->dealConfig["lastLightningDiscountViewedOffset"] - 1;
			$todayDate = date("Y-m-d");
			$lastViewedDt = date("Y-m-d",strtotime("$todayDate -".$lastViewedOffset." days"));
			$lightningDiscObj = new billing_LIGHTNING_DEAL_DISCOUNT("newjs_slave");
			$lastViewedPool = $lightningDiscObj->filterDiscountActivatedProfiles($pool1,'Y',$lastViewedDt);
			if(is_array($lastViewedPool)){
				$pool2 = array_diff($pool1, $lastViewedPool);
			}
			else{
				$pool2 = $pool1;
			}
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
			if(is_array($pool2) && n>0){
				$finalPool = array_slice($pool2, 0,$n);
			}
		}
		var_dump($n);
		var_dump($pool2);
		var_dump($finalPool);die;
		if($this->debug == 1){
	        echo "final pool with n= ".$n." count..."."\n";
	        print_r($finalPool);
	    }
		return $finalPool;
	}

	public function generateDealEligiblePool(){
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
		return $finalPool;
	}

	public function storeDealEligiblePool($finalPool=null){
		
	}
}
?>