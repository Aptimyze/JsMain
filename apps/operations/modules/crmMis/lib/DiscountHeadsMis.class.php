<?php

// Author : Neha Gupta
// This class handles all the logics related to Discount Heads MIS.

if(!$_SERVER['DOCUMENT_ROOT'])
        $_SERVER['DOCUMENT_ROOT'] =sfConfig::get("sf_web_dir");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");

class DiscountHeadsMis
{
	private $start_dt;              
	private $end_dt;              
	private $trxn;              
	private $allTrxnDetailArr;              
	private $allTrxnDetailAgentWise;              
	private $combinedDiscountHeadsExecWise;              
	private $currency;              

        // Constant Values
        private static $RENEW_DISCOUNT_RATE; 


	public function __construct($start_dt,$end_dt,$cur_type)
	{
                $this->start_dt = $start_dt." 00:00:00";
                $this->end_dt = $end_dt." 23:59:59";
		
		if($cur_type == 'USD')
	                $this->currency = "DOL";
		else
	                $this->currency = "RS";

		$this->trxn = array();
		$this->allTrxnDetailArr = array();
		$this->allTrxnDetailAgentWise = array();
		$this->combinedDiscountHeadsExecWise = array();

		$memObj = new Membership();
		DiscountHeadsMis::$RENEW_DISCOUNT_RATE = $memObj->get_renew_discount_rate();
		unset($memObj);
	}


	public function getAllTrxnDetailArr(){ return $this->allTrxnDetailArr; }
	public function getAllTrxnDetailAgentWise(){ return $this->allTrxnDetailAgentWise; }
	public function getCombinedDiscountHeadsExecWise(){ return $this->combinedDiscountHeadsExecWise; }


	public function displayAllData()
	{
		return array("START_DATE"=>$this->start_dt, "END_DATE"=>$this->end_dt, "TRANSACTION_DETAIL"=>$this->trxn, "ALL_TRANSACTION_DETAIL"=>$this->allTrxnDetailArr, "ALL_TRANSACTION_DETAIL_AGENT_WISE"=>$this->allTrxnDetailAgentWise, "CURRENCY"=>$this->currency, "RENEW_DISCOUNT_RATE"=>DiscountHeadsMis::$RENEW_DISCOUNT_RATE, "COMBINED_DISCOUNT_HEADS"=>$this->combinedDiscountHeadsExecWise);
	}	


	public function calculateDiscountHeads($total_discount=0, $addon_standard_price=0)
	{
		if($total_discount < $addon_standard_price)		
		{
			$this->trxn['ADDON_DISCOUNT'] = $total_discount;
			$this->trxn['ADDON_FREE'] = "N/A";
			$this->trxn['MEMBERSHIP_DISCOUNT'] = 0;
		}

		else if($total_discount == $addon_standard_price)		
		{
			$this->trxn['ADDON_DISCOUNT'] = "N/A";
			$this->trxn['ADDON_FREE'] = $addon_standard_price;
			$this->trxn['MEMBERSHIP_DISCOUNT'] = 0;
		}
		else 		
		{
			$this->trxn['ADDON_DISCOUNT'] = "N/A";
			$this->trxn['ADDON_FREE'] = $addon_standard_price;
			$this->trxn['MEMBERSHIP_DISCOUNT'] = $total_discount-$addon_standard_price;
		}
		if($addon_standard_price <= 0) {
			$this->trxn['ADDON_DISCOUNT'] = "N/A";
			$this->trxn['ADDON_FREE'] = "N/A";
		}
		if($this->trxn['ADDON_DISCOUNT'] != "N/A" && $this->trxn['ADDON_DISCOUNT'] < 0)
			$this->trxn['ADDON_DISCOUNT'] = 0;
		if($this->trxn['ADDON_FREE'] != "N/A" && $this->trxn['ADDON_FREE'] < 0)
			$this->trxn['ADDON_FREE'] = 0;
		if($this->trxn['MEMBERSHIP_DISCOUNT'] != "N/A" && $this->trxn['MEMBERSHIP_DISCOUNT'] < 0)
			$this->trxn['MEMBERSHIP_DISCOUNT'] = 0;

		$this->trxn['EXTRA_RENEWAL_DISCOUNT'] = $this->trxn['MEMBERSHIP_DISCOUNT'];
	}
	
	
	public function getDiscountHeadsValues()
	{
		if($this->trxn['MEMBERSHIP_FREE'] > 0 && $this->trxn['IS_RENEWAL_PERIOD'] == 'Yes')
		{
			$effective_total_disc = $this->trxn['TOTAL_DISCOUNT'] - $this->trxn['MEMBERSHIP_FREE'] - $this->trxn['STD_RENEWAL_DISCOUNT'];
			if($effective_total_disc < 0)
			{
				if($this->trxn['TOTAL_DISCOUNT'] <= $this->trxn['MEMBERSHIP_FREE']) {
					$this->trxn['MEMBERSHIP_FREE'] = $this->trxn['TOTAL_DISCOUNT'];
					$this->trxn['STD_RENEWAL_DISCOUNT'] = 0;
				}
				else
					$this->trxn['STD_RENEWAL_DISCOUNT'] = $this->trxn['TOTAL_DISCOUNT'] - $this->trxn['MEMBERSHIP_FREE'];
			}
		}
		else if($this->trxn['MEMBERSHIP_FREE'] > 0)
		{
			$effective_total_disc = $this->trxn['TOTAL_DISCOUNT'] - $this->trxn['MEMBERSHIP_FREE'];
			if($effective_total_disc < 0)
				$this->trxn['MEMBERSHIP_FREE'] = $this->trxn['TOTAL_DISCOUNT'];
		}
		else if($this->trxn['IS_RENEWAL_PERIOD'] == 'Yes')
		{
			$effective_total_disc = $this->trxn['TOTAL_DISCOUNT'] - $this->trxn['STD_RENEWAL_DISCOUNT'];
			if($effective_total_disc < 0)
				$this->trxn['STD_RENEWAL_DISCOUNT'] = $this->trxn['TOTAL_DISCOUNT'];
		}
		else 
			$effective_total_disc = $this->trxn['TOTAL_DISCOUNT'];

		if($this->trxn['IS_RENEWAL_PERIOD'] == 'Yes') {
			$this->calculateDiscountHeads($effective_total_disc, $this->trxn['ADDON_RENEWAL_PRICE']);
			$this->trxn['MEMBERSHIP_DISCOUNT'] = "N/A";
		}
		else {
			$this->calculateDiscountHeads($effective_total_disc, $this->trxn['ADDON_STD_PRICE']);
			$this->trxn['EXTRA_RENEWAL_DISCOUNT'] = "N/A";
		}
	}
	

	public function fetchPriceForServices($serviceIdArr)
	{
		$this->trxn['MEMBERSHIP_STD_PRICE'] = 0;
		$this->trxn['ADDON_STD_PRICE'] = 0;
		$this->trxn['SERVICES_PURCHASED'] = '';

 		foreach($serviceIdArr as $id)
		{
			$sObj = new billing_SERVICES('newjs_slave');

			if($this->currency == 'DOL')
				$info = $sObj->fetchServiceDetailForDollarTrxn($id, 'desktop');
			else 
				$info = $sObj->fetchServiceDetailForRupeesTrxn($id ,'desktop');

			unset($sObj);
			
			if($info['ADDON'] == 'Y')
				$this->trxn['ADDON_STD_PRICE'] += $info['PRICE']; 
			else 
				$this->trxn['MEMBERSHIP_STD_PRICE'] += $info['PRICE']; 

			$this->trxn['SERVICES_PURCHASED'] .= $info['NAME'].",";
		}
		$this->trxn['TOTAL_STD_PRICE'] = $this->trxn['MEMBERSHIP_STD_PRICE'] + $this->trxn['ADDON_STD_PRICE'];
		if($this->currency == 'DOL')  // If transaction is in dollars
			$this->getDollarTransactionValues();
	}
	
	public function isUnlimitedService($serviceIdArr)
	{
		$this->trxn['IS_UNLIMITED'] = 0;
 		foreach($serviceIdArr as $id)
		{
                        $sObj = new billing_SERVICES('newjs_slave');

                        if($this->currency == 'DOL')
                                $info = $sObj->fetchServiceDetailForDollarTrxn($id, 'desktop');
                        else
                                $info = $sObj->fetchServiceDetailForRupeesTrxn($id, 'desktop');

                        unset($sObj);

			if($info['ADDON'] != 'Y' && strstr($id,'L'))
				$this->trxn['IS_UNLIMITED'] = 1;
		}	
	}	

        public function getFreeTransactionPrices()
	{
		if($this->trxn['ADDON_STD_PRICE'] > 0 && $this->trxn['MEMBERSHIP_STD_PRICE'] > 0) 
		{
			$this->trxn['ADDON_FREE'] = $this->trxn['ADDON_STD_PRICE'];
			$this->trxn['MEMBERSHIP_FREE'] = $this->trxn['MEMBERSHIP_STD_PRICE'];
		}
		else 
		{
			if($this->trxn['MEMBERSHIP_STD_PRICE'] > 0)
				$this->trxn['MEMBERSHIP_FREE'] = $this->trxn['TOTAL_DISCOUNT'];
			else
				$this->trxn['MEMBERSHIP_FREE'] = "N/A";
			
			if($this->trxn['ADDON_STD_PRICE'] > 0)
				$this->trxn['ADDON_FREE'] = $this->trxn['TOTAL_DISCOUNT'];
			else
				$this->trxn['ADDON_FREE'] = "N/A";
		}
		$this->trxn['MEMBERSHIP_RENEWAL_PRICE'] = 'N/A';
		$this->trxn['ADDON_RENEWAL_PRICE'] = 'N/A';
		$this->trxn['STD_RENEWAL_DISCOUNT'] = 'N/A';
		$this->trxn['EXTRA_RENEWAL_DISCOUNT'] = 'N/A';
		$this->trxn['ADDON_DISCOUNT'] = 'N/A';
		$this->trxn['MEMBERSHIP_DISCOUNT'] = 'N/A';

		if($this->isRenewal())  // If Renewal Offer
			$this->trxn['IS_RENEWAL_PERIOD'] = 'Yes';
		else
			$this->trxn['IS_RENEWAL_PERIOD'] = 'No';
	}

        public function getFestivePrices()
	{
		$serviceIdArr = explode(',', $this->trxn['SERVICEID']);
		$this->isUnlimitedService($serviceIdArr);

		if($this->trxn['IS_UNLIMITED']==0)  // If Offer is not unlimited
		{
			$mhObj = new MembershipHandler();
			//for accessing non-symfony function
			connect_slave();
			//end

			$serviceCharged = array();
			foreach($serviceIdArr as $k => $v)
				$serviceCharged[] = $mhObj->OfferReverseMapping($v); // service charged during festive offer
			unset($mhObj);
			
			$serviceIdArr = $serviceCharged;
			$this->fetchPriceForServices($serviceIdArr);
			$this->trxn['MEMBERSHIP_STD_PRICE_CHARGED'] = $this->trxn['MEMBERSHIP_STD_PRICE'];

			$serviceIdArr = explode(',', $this->trxn['SERVICEID']);
			$this->fetchPriceForServices($serviceIdArr);
			$this->trxn['MEMBERSHIP_FREE'] = $this->trxn['MEMBERSHIP_STD_PRICE'] - $this->trxn['MEMBERSHIP_STD_PRICE_CHARGED'];
		}
		else 
			$this->trxn['MEMBERSHIP_FREE'] = "N/A";
	}

	public function isFestive()
	{
		$festiveObj = new billing_FESTIVE_LOG_REVAMP('newjs_slave');
		$res = $festiveObj->getLastActiveServices($this->trxn['BILLING_DT']); 
		if($res)
			return 1;
		return;
	}

	public function isRenewal()
	{
		$memObj = new Membership();
		connect_slave();   //for accessing non-symfony function
		$res = $memObj->isRenewableEver($this->trxn['PROFILEID'], $this->trxn['BILLING_DT']);			
		return $res;
	}

	public function calculateEffectiveTotalDiscount()  // for eg. e-Sathi Classified 6-months Membership
	{
		$pdObj = new billing_PURCHASE_DETAIL('newjs_slave');
		$absoluteTotalPrice = $pdObj->getTotalPriceOfTransaction($this->trxn['BILLID'], $this->trxn['PROFILEID']);	
		$this->trxn['TOTAL_DISCOUNT'] -= $absoluteTotalPrice - $this->trxn['TOTAL_STD_PRICE'];
		unset($pdObj);
	}

	public function getRenewalPrices()
	{
		$this->trxn['IS_RENEWAL_PERIOD'] = 'Yes';
		$this->trxn['ADDON_RENEWAL_PRICE'] = $this->trxn['ADDON_STD_PRICE']*(1 - (DiscountHeadsMis::$RENEW_DISCOUNT_RATE/100));
		
		if($this->trxn['MEMBERSHIP_STD_PRICE_CHARGED'])
		{
			$this->trxn['MEMBERSHIP_RENEWAL_PRICE'] = $this->trxn['MEMBERSHIP_STD_PRICE_CHARGED']*(1 - (DiscountHeadsMis::$RENEW_DISCOUNT_RATE/100));
			$this->trxn['STD_RENEWAL_DISCOUNT'] = (round($this->trxn['MEMBERSHIP_STD_PRICE_CHARGED']) + round($this->trxn['ADDON_STD_PRICE'])) - (round($this->trxn['MEMBERSHIP_RENEWAL_PRICE']) + round($this->trxn['ADDON_RENEWAL_PRICE']));
		}
		else
		{
			$this->trxn['MEMBERSHIP_RENEWAL_PRICE'] = $this->trxn['MEMBERSHIP_STD_PRICE']*(1 - (DiscountHeadsMis::$RENEW_DISCOUNT_RATE/100));
			$this->trxn['STD_RENEWAL_DISCOUNT'] = (round($this->trxn['MEMBERSHIP_STD_PRICE']) + round($this->trxn['ADDON_STD_PRICE'])) - (round($this->trxn['MEMBERSHIP_RENEWAL_PRICE']) + round($this->trxn['ADDON_RENEWAL_PRICE']));
		}
	}

	public function getDollarTransactionValues()
	{
		if($this->trxn['DOL_CONV_RATE']!=0) 
		{
			$this->trxn['MEMBERSHIP_STD_PRICE'] *= $this->trxn['DOL_CONV_RATE'];
			$this->trxn['ADDON_STD_PRICE'] *= $this->trxn['DOL_CONV_RATE'];
		}
	}	

	public function fetchMembershipDetailsTrxnWise()
	{
		$serviceIdArr = explode(',', $this->trxn['SERVICEID']);
		$this->fetchPriceForServices($serviceIdArr);
		$this->calculateEffectiveTotalDiscount();

		if($this->currency != 'DOL')  // If transaction is not in dollars
			$this->trxn['TOTAL_DISCOUNT'] = round(round($this->trxn['TOTAL_DISCOUNT'],1));
		if($this->currency == 'DOL' && $this->trxn['DOL_CONV_RATE']!=0)  // If transaction is in dollars
			$this->trxn['TOTAL_DISCOUNT'] *= $this->trxn['DOL_CONV_RATE'];

		$total_service_price = $this->trxn['MEMBERSHIP_STD_PRICE'] + $this->trxn['ADDON_STD_PRICE'];
		if($this->trxn['TOTAL_DISCOUNT'] >= $total_service_price) // If Free Transaction
		{
			$this->getFreeTransactionPrices();
			return;
		}

		if($this->isFestive()) // If Festive Offer 
			$this->getFestivePrices();
		else 
			$this->trxn['MEMBERSHIP_FREE'] = "N/A";

		trim($this->trxn['SERVICES_PURCHASED'], ",");
		
		if($this->isRenewal())  // If Renewal Offer
			$this->getRenewalPrices();
		else {
			$this->trxn['IS_RENEWAL_PERIOD'] = 'No';
			$this->trxn['MEMBERSHIP_RENEWAL_PRICE'] = 'N/A';
			$this->trxn['ADDON_RENEWAL_PRICE'] = 'N/A';
			$this->trxn['STD_RENEWAL_DISCOUNT'] = 'N/A';
		}
		$this->getDiscountHeadsValues();
	}

	public function compare($a, $b)
	{
		return $a['BILLING_DT']-$b['BILLING_DT']; 
	}

	public function getAllotedAgentTrxnWise()
	{
		foreach($this->allTrxnDetailArr as $key => $trxn)
		{
			$cdaObj = new CRM_DAILY_ALLOT('newjs_slave'); 
			$this->allTrxnDetailArr[$key]['ALLOTED_TO'] = $cdaObj->getAllotedAgentToTransaction($trxn['PROFILEID'], $trxn['BILLING_DT']);	
			unset($cdaObj);
	
			if(!$this->allTrxnDetailArr[$key]['ALLOTED_TO'])
			{
				$cdatObj = new CRM_DAILY_ALLOT_TRACK('newjs_slave'); 
				$this->allTrxnDetailArr[$key]['ALLOTED_TO'] = $cdatObj->getAllotedAgentToTransaction($trxn['PROFILEID'], $trxn['BILLING_DT']);	
				unset($cdatObj);
			}
			
			if(!$this->allTrxnDetailArr[$key]['ALLOTED_TO'])
				$this->allTrxnDetailArr[$key]['ALLOTED_TO'] = 'UNALLOCATED';	
		}
	}	

	public function fetchBillingDetailsTrxnWise($agent='')
	{
		$pObj = new BILLING_PURCHASES('newjs_slave');
		$this->allTrxnDetailArr = $pObj->fetchTransactionInfo($this->start_dt, $this->end_dt, $this->currency);
		unset($pObj);

		$this->getAllotedAgentTrxnWise();

		foreach($this->allTrxnDetailArr as $key => $trxn)
		{
			$this->trxn = $trxn;
			$this->fetchMembershipDetailsTrxnWise();
			if($this->trxn['TOTAL_DISCOUNT'] <= 0)		continue;
			$this->allTrxnDetailAgentWise[$trxn['ALLOTED_TO']][] = $this->trxn;
		}
		if($agent)
			usort($this->allTrxnDetailAgentWise[$agent], "compare"); // sorting transactions data for an agent in ascending order of billing date
	}


	public function fetchCombinedDiscountHeadsExecWise()
	{
		$this->fetchBillingDetailsTrxnWise();
		ksort($this->allTrxnDetailAgentWise);  // sorting agent names alphabetically
		if($this->allTrxnDetailAgentWise['UNALLOCATED'])  // To move UNALLOCATED profiles transactions on the top 
		{
			$unallocatedProfiles = $this->allTrxnDetailAgentWise['UNALLOCATED'];
			unset($this->allTrxnDetailAgentWise['UNALLOCATED']);
			$allocatedProfiles = $this->allTrxnDetailAgentWise;
			unset($this->allTrxnDetailAgentWise);
			
			$this->allTrxnDetailAgentWise['UNALLOCATED'] = $unallocatedProfiles;
			if($allocatedProfiles)
				$this->allTrxnDetailAgentWise = array_merge($this->allTrxnDetailAgentWise, $allocatedProfiles);
		}

		foreach($this->allTrxnDetailAgentWise as $agent => $val)
		{
			$combinedDiscountHeads = array();
			foreach($this->allTrxnDetailAgentWise[$agent] as $trxn)
			{
				$combinedDiscountHeads['TOTAL_DISCOUNT']  += round(round($trxn['TOTAL_DISCOUNT'],1));
				$combinedDiscountHeads['STD_RENEWAL_DISCOUNT']  += round($trxn['STD_RENEWAL_DISCOUNT']);
				$combinedDiscountHeads['EXTRA_RENEWAL_DISCOUNT']  += round($trxn['EXTRA_RENEWAL_DISCOUNT']);
				$combinedDiscountHeads['ADDON_DISCOUNT']  += round($trxn['ADDON_DISCOUNT']);
				$combinedDiscountHeads['ADDON_FREE']  += round($trxn['ADDON_FREE']);
				$combinedDiscountHeads['MEMBERSHIP_DISCOUNT']  += round($trxn['MEMBERSHIP_DISCOUNT']);
				$combinedDiscountHeads['MEMBERSHIP_FREE']  += round($trxn['MEMBERSHIP_FREE']);
			}
			$this->combinedDiscountHeadsExecWise[$agent] = $combinedDiscountHeads;
		}
	}

	public function createExcelFormatOutput($resultArr, $header, $displayDate)
	{
		$header .= "\n\nS.No.\tExecutives_Name\tTotal_Discount_(TD)\tStandard_Renewal_Discount_(SRD)\tExtra_Renewal_Discount_(ERD)\tDiscount_on_Add-on_Services_(AD)\tAdd-On_given_Free_(AF)\tDiscount_on_Membership_Plan_(MD)\tMembership_Duration_for_Free_(MF)\n"; 
		$i=1;
		foreach($resultArr as $k=>$v)
		{
			if(round($v['TOTAL_DISCOUNT']) > 0)
				$message .= $i."\t".$k."\t".round($v['TOTAL_DISCOUNT'])."\t".round($v['STD_RENEWAL_DISCOUNT'])."\t".round($v['EXTRA_RENEWAL_DISCOUNT'])."\t".round($v['ADDON_DISCOUNT'])."\t".round($v['ADDON_FREE'])."\t".round($v['MEMBERSHIP_DISCOUNT'])."\t".round($v['MEMBERSHIP_FREE'])."\n";	
			$i++;
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Discount_Heads_MIS_".$displayDate.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $header."\n".$message;
		die;
	}

}

?>
