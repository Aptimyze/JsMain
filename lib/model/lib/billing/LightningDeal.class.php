<?php

/*This class provides functionality for Lightning Deal offer
* @author: Ankita
*/

class LightningDeal 
{
	private $dealConfig;
	private $debug;

	public function __construct(){
		$this->dealConfig = VariableParams::$lightningDealOfferConfig;
		$this->debug = 1;
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
	    
	    if(is_array($lastLoggedInArr)){
		    //use newjs.JPROFILE to get currently free pool from $lastLoggedInArr
		    $jprofileObj = new JPROFILE("newjs_slave");
		    $lastLoggedInFreePool1 = $jprofileObj->getProfileSelectedDetails($lastLoggedInArr,"PROFILEID",array("activatedKey"=>1,"SUBSCRIPTION"=>''));
		    unset($jprofileObj);
		    if($this->debug == 1){
		        echo "after membership filter,currently free last logged in pool.."."\n";
		        print_r($lastLoggedInFreePool1);
		    }
		}
	    return $lastLoggedInFreePool1;
	}

	/*Pool 2-Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed)*/
	public function fetchDealPool2($pool1=null){

	}

	/*Final Pool: Pick n number of users from pool in point 2 where n is 10% of the number of users in pool 1*/
	public function fetchDealFinalPool($pool2=null){

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
			$finalPool = $this->fetchDealFinalPool($pool2);
		}
		return $finalPool;
	}

	public function storeDealEligiblePool($finalPool=null){
		
	}
}
?>