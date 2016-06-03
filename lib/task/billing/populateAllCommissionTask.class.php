<?php

class populateAllCommissionTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'billing';
		$this->name             = 'populateAllCommission';
		$this->briefDescription = 'Populate Franchisee/Apple Comissions for successful payments';
		$this->detailedDescription = <<<EOF
		The [populateAllCommission|INFO] task does things.
		Call it with:
		[php symfony billing:populateAllCommission|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    // SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		
		if (!$_SERVER['DOCUMENT_ROOT']) {
			$_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;
		}

		// Fetch Comissions Percentage 
		$franchiseeCommissionPercentage = franchiseeCommission::FRANCHISEE;

		$appleCommissionObj = new billing_APPLE_COMMISSION_PERCENTAGE_LOG('newjs_slave');

		// Fetch all Agents which Franchisee Priviledge
		$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
		$agentArr1 = $jsadminPswrdsObj->fetchAgentsWithPriviliges("%ExcFSD%");
		if(empty($agentArr1)){
			$agentArr1 = array();
		}
		$agentArr2 = $jsadminPswrdsObj->fetchAgentsWithPriviliges("%ExcFID%");
		if(empty($agentArr2)){
			$agentArr2 = array();
		}
		$franchiseeAgentsArr = array_merge($agentArr1, $agentArr2); 
		
		//echo "Active Franchisee Agent"; print_r($franchiseeAgentsArr); echo "\n\n";

		// Pick all Payments within last 1 hour
		$startDt = date('Y-m-d H:i:s', time()-3600);
		$endDt = date('Y-m-d H:i:s', time());

		$billingPaymentDetailObj = new BILLING_PAYMENT_DETAIL();
		$profilesArr = $billingPaymentDetailObj->getProfilesWithinDateRange($startDt, $endDt);

		//echo "Picked Profiles"; print_r($profilesArr); echo "\n\n";

		$incentiveCrmDailyAllotObj = new CRM_DAILY_ALLOT('newjs_slave');
		$billingOrdersDeviceObj = new billing_ORDERS_DEVICE('newjs_slave');

		$comissionsArr = array();

		if(!empty($profilesArr)){
			
			foreach($profilesArr as $key=>$details) {
				
				$paymentSource = $billingOrdersDeviceObj->getPaymentSourceFromBillid($details['BILLID']);

				if($paymentSource == 'iOS_app') {
					$appleCommissionPercentage = $appleCommissionObj->getActiveAppleCommissionPercentage($details['ENTRY_DT']);
					$appleComm = ($appleCommissionPercentage/100)*($details['AMOUNT']);
				}
				else
					$appleComm = 0;
				
                                $allotedAgent = $incentiveCrmDailyAllotObj->getAllotedAgentToTransaction($details['PROFILEID'], $details['ENTRY_DT']);

                                if(!empty($franchiseeAgentsArr) && in_array($allotedAgent, $franchiseeAgentsArr)){
                                        $franComm = ($franchiseeCommissionPercentage/100)*($details['AMOUNT'] - ((billingVariables::NET_OFF_TAX_RATE)*$details['AMOUNT']) - $appleComm);
                                        $franComm = round($franComm, 2);
                                }

				
				$billingPaymentDetailObj->updateComissions($details['PROFILEID'],$details['BILLID'],$appleComm,$franComm);
				
				unset($allotedAgent);
				unset($franComm);
				unset($paymentSource);
				unset($appleCommissionPercentage);
				unset($appleComm);
			}
		}


	}
}
