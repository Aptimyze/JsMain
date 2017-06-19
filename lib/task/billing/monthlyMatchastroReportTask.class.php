<?php

class monthlyMatchastroReportTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
        ));

        $this->namespace        = 'billing';
        $this->name             = 'monthlyMatchastroReport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [monthlyMatchastroReport|INFO] task does things.
        Call it with:
        [php symfony billing:monthlyMatchastroReport|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit', -1);
        $currentMonth = date("n", time());
        $currentYear = date("Y", time());

        if($currentMonth == 1){
        	$currentMonth = 12;
        	$currentYear = $currentYear - 1;
        } else {
        	$currentMonth = $currentMonth - 1;
        }

        $startDt = $currentYear."-".str_pad($currentMonth, 2, '0', STR_PAD_LEFT)."-01 00:00:00";
        $endDt = $currentYear."-".str_pad($currentMonth, 2, '0', STR_PAD_LEFT)."-31 23:59:59";

        // Fetch all billing with status DONE in the past month
        $billingPurchasesObj = new BILLING_PURCHASES('newjs_slave');
        $totalBillings = $billingPurchasesObj->getProfilesWithinDateRange($startDt, $endDt);

        // Array to store BILLID's of those billing which have Astro Compatibility in them as a service
        $relevantBillings = array();

        foreach($totalBillings as $key=>$val){
        	$serviceArr = @explode(",", $val['SERVICEID']);
        	foreach($serviceArr as $k=>$sid){
        		$ssid = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $sid);
        		if($ssid[0] == "A" || $ssid[0] == "ESP" || $ssid[0] == "ES" || $ssid[0] == "ESJA" || $ssid[0] == "ESPL" || $ssid[0] == "ESL"){
        			$relevantBillings[] = $val['BILLID'];
        		}
        		unset($ssid);
        	}
        	unset($serviceArr);
        	unset($sid);
        }

        //Fetch Details for $relevantBillings from PURCHASE_DETAIL
        $billingPurchaseDetObj = new billing_PURCHASE_DETAIL('newjs_slave');
        if(!empty($relevantBillings)){
        	$finalBillings = $billingPurchaseDetObj->getAllDetailsForBillidArr($relevantBillings);
        } else {
        	$finalBillings = NULL;
        }

        //Fetch Details for $relevantBillings from PURCHASE_DETAIL
        $billingPaymentDetObj = new BILLING_PAYMENT_DETAIL('newjs_slave');
        if(!empty($relevantBillings)){
        	$payDetArr = $billingPaymentDetObj->getAllDetailsForBillidArr($relevantBillings);
        } else {
        	$payDetArr = NULL;
        }
        
        $countAstroSold = 0;
        $totalRevenue = 0;
        $totalServiceTax = 0;
        $netRevenue = 0;
        $revenueShareForMatchAstro = 0;

        foreach($finalBillings as $key=>$val){
        	$sid = $val['SERVICEID'];
        	$ssid = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $sid);
        	if($ssid[0] == "A" && $val['NET_AMOUNT']>0){
        		$countAstroSold++;
        		if($val['CUR_TYPE'] == "RS") {
	        		$totalRevenue += $val['NET_AMOUNT'];
	        		$totalServiceTax += $val['NET_AMOUNT']*(1-(1/(1+($totalBillings[$val['BILLID']]['TAX_RATE']/100))));
	        	} else {
	        		$totalRevenue += $val['NET_AMOUNT']*$payDetArr[$val['BILLID']]['DOL_CONV_RATE'];
	        		$totalServiceTax += $val['NET_AMOUNT']*$payDetArr[$val['BILLID']]['DOL_CONV_RATE']*(1-(1/(1+($totalBillings[$val['BILLID']]['TAX_RATE']/100))));
	        	}
        	}
        	unset($sid, $ssid);
        }

        $totalRevenue = round($totalRevenue, 2);
        $totalServiceTax = round($totalServiceTax, 2);
        $netRevenue = $totalRevenue - $totalServiceTax;
        $revenueShareForMatchAstro = round($netRevenue*(50/100), 2);

        $to = "jsprod@jeevansathi.com,avneet.bindra@jeevansathi.com";
        $from = "js-sums@jeevansathi.com";
        $subject = "Monthly MatchAstro Report : {$startDt} - {$endDt}";
        $msgBody .= "<br><strong>Number of Astro Compatibility Tickets Sold</strong> :: {$countAstroSold}";
        $msgBody .= "<br><strong>Revenue from Astro Compatibility Tickets Sold</strong> :: Rs {$totalRevenue}/-";
        $msgBody .= "<br><strong>Service Tax</strong> :: Rs {$totalServiceTax}/-";
        $msgBody .= "<br><strong>Net Revenue</strong> :: Rs {$netRevenue}/-";
        $msgBody .= "<br><strong>Revenue share for MatchAstro</strong> :: Rs {$revenueShareForMatchAstro}/-";

        SendMail::send_email($to, $msgBody, $subject, $from);
    }
}
