<?php

class dailyPaidMemberReportTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
        ));

        $this->namespace        = 'billing';
        $this->name             = 'dailyPaidMemberReport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [dailyPaidMemberReport|INFO] task does things.
        Call it with:
        [php symfony billing:dailyPaidMemberReport|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit', '-1');
        $start_time = date("Y-m-d 00:00:00", (time()-86400));
        $end_time = date("Y-m-d 23:59:59", (time()-86400));

        $billPaymentDet = new BILLING_PAYMENT_DETAIL('newjs_slave');
        $billPurDet = new billing_PURCHASE_DETAIL('newjs_slave');
        $billPurObj = new BILLING_PURCHASES('newjs_slave');
        $billOrdDev = new billing_ORDERS_DEVICE('newjs_slave');
        $billOrd = new BILLING_ORDERS('newjs_slave');
        $jprofileObj = new JPROFILE('newjs_slave');
        $mtongueObj = new NEWJS_MTONGUE('newjs_slave');
        $billServObj = new billing_SERVICES('newjs_slave');
        $newjsContactObj1 = new newjs_CONTACTS('shard1_slave');
        $newjsContactObj2 = new newjs_CONTACTS('shard2_slave');
        $newjsContactObj3 = new newjs_CONTACTS('shard3_slave');
	    $newjsJpartnerObj1 = new newjs_JPARTNER('shard1_slave');
	    $newjsJpartnerObj2 = new newjs_JPARTNER('shard2_slave');
	    $newjsJpartnerObj3 = new newjs_JPARTNER('shard3_slave');

        $genderArr = array("F"=>'Female',"M"=>'Male');
        $finalArr = array();

        // Fetching All payments within last 1 day
        $paidProfilesArr = $billPaymentDet->getProfilesWithinDateRange($start_time, $end_time);

        if(is_array($paidProfilesArr)){
	        foreach($paidProfilesArr as $key=>$val){
	            $billidArr[] = $val['BILLID'];
	            $profileArr[] = $val['PROFILEID'];
        	}

        	$billidStr = implode(",", $billidArr);

        	// Fetch JPOFILE Data
        	$profileDet = $jprofileObj->getAllSubscriptionsArr($profileArr);
        	foreach($paidProfilesArr as $key=>&$val){
        		$val['GENDER'] = $genderArr[$profileDet[$val['PROFILEID']]['GENDER']];
        		$val['AGE'] = $profileDet[$val['PROFILEID']]['AGE'];
        		$val['USERNAME'] = $profileDet[$val['PROFILEID']]['USERNAME'];
        		$val['COMMUNITY'] = $mtongueObj->getMtongue($profileDet[$val['PROFILEID']]['MTONGUE'])['SMALL_LABEL'];
        		$val['CITY'] = FieldMap::getFieldLabel("city", $profileDet[$val['PROFILEID']]['CITY_RES']);
        	}

        	// Fetching Payment Source(Channels)
	        $orderDetArr = $billOrdDev->getPaymentSourceFromBillidStr($billidStr);
	       
	        foreach($orderDetArr as $k1=>$v1){
	            $orderIdArr[] = $v1['ID'];
	        }
	      
	        $orderIdStr = "'".implode("','", $orderIdArr)."'";

	        // Fetching Payment Gateway
	        $orderGatewayArr = $billOrd->getOrderDetailsForIdStr($orderIdStr);

	        // Setting Gateway for All Payments
	        foreach($orderDetArr as $key=>&$val){
	            if(in_array($val['ID'], array_keys($orderGatewayArr))){
	                $val['GATEWAY'] = $orderGatewayArr[$val['ID']]['GATEWAY'];
	            }
	        }
	  
	        // Setting Sources(Channels) for All Payments
	        foreach($paidProfilesArr as $key=>&$val){
	            // Setting proper Channel, if not found i.e. backend billind default Desktop
	            if(in_array($val['BILLID'], array_keys($orderDetArr))){
	            	$val['CHANNEL'] = $orderDetArr[$val['BILLID']]['SOURCE'].", ".$orderDetArr[$val['BILLID']]['GATEWAY'];
	            } else {
	                $val['CHANNEL'] = 'desktop, Backend';
	            }
	        }
	     
	        $purArr = $billPurObj->fetchAllDataForBillidArr($billidArr);
	        $purDetArr = $billPurDet->getAllDetailsForBillidArr($billidArr);
	        $serviceIdArr = array();
	        foreach($paidProfilesArr as $k=>&$v){
	        	foreach($purArr as $key=>&$val){
	        		if($v['BILLID'] == $val['BILLID']){
	        			$v['SERVICES'] = $val['SERVICEID'];
	        			$v['Paid earlier'] = $billPurObj->fetchPaymentCount($v['PROFILEID'],$v['BILLID']);
	        			$services = @explode(",",$val['SERVICEID']);
	        			foreach($services as $temp=>$sid)
	        			if(!in_array($sid, $serviceIdArr)){
	        				$serviceIdArr[] = $sid;
	        			}
	        		}
        		}
        	}
       
        	$servNameArr = $billServObj->getServiceNameArr($serviceIdArr);
        	$skipBillId = array();
        	//print_r($paidProfilesArr);
			foreach($paidProfilesArr as $k=>&$v){
	        	foreach($purDetArr as $key=>&$val){
	        		$skipFlag = false;
	        		if($v['PROFILEID'] == $val['PROFILEID'] && $v['BILLID'] == $val['BILLID'] && !in_array($val['BILLID'], $skipBillId)){
	        			if(strpos($purArr[$val['BILLID']]['SERVICEID'],'NCP') !== false){
	        				$skipFlag = true;
	        				$skipBillId[] = $val['BILLID'];
	        			}

	        			$incrementID = count($finalArr);

	        			$finalArr[$incrementID]['USERNAME'] = $v['USERNAME'];
	        			$finalArr[$incrementID]['AGE'] = $v['AGE'];
	        			$finalArr[$incrementID]['GENDER'] = $v['GENDER'];
	        			$finalArr[$incrementID]['CITY'] = $v['CITY'];
	        			$finalArr[$incrementID]['COMMUNITY'] = $v['COMMUNITY'];
	        			$finalArr[$incrementID]['CHANNEL'] = $v['CHANNEL'];
	        			$finalArr[$incrementID]['PAYMENT_DATE'] = $v['ENTRY_DT'];
	        			$finalArr[$incrementID]['SERVICE_ACTIVATION_DATE'] = $val['START_DATE'];

	        			
	        			if($skipFlag){
	        				$finalArr[$incrementID]['MEMBERSHIP_NAME'] = $servNameArr[@explode(",",$purArr[$val['BILLID']]['SERVICEID'])[0]];
	        				if($purArr[$val['BILLID']]['MEM_UPGRADE'] == "MAIN" && !empty($finalArr[$incrementID]['MEMBERSHIP_NAME'])){
	        					$finalArr[$incrementID]['MEMBERSHIP_NAME'] .= " Upgrade";
	        				}
	        				if($v['TYPE'] == 'DOL'){
		        				$finalArr[$incrementID]['NET_AMOUNT'] = round((1-billingVariables::NET_OFF_TAX_RATE) * $v['DOL_CONV_RATE'] * $v['AMOUNT'] , 2);
		        			} else {
		        				$finalArr[$incrementID]['NET_AMOUNT'] = round((1-billingVariables::NET_OFF_TAX_RATE) * $v['AMOUNT'] , 2);
		        			}
	        			} else {
	        				$finalArr[$incrementID]['MEMBERSHIP_NAME'] = $servNameArr[$val['SERVICEID']];
	        				if($purArr[$val['BILLID']]['MEM_UPGRADE'] == "MAIN" && !empty($finalArr[$incrementID]['MEMBERSHIP_NAME'])){
	        					$finalArr[$incrementID]['MEMBERSHIP_NAME'] .= " Upgrade";
	        				}
		        				if($v['TYPE'] == 'DOL'){
		        				$finalArr[$incrementID]['NET_AMOUNT'] = round((1-billingVariables::NET_OFF_TAX_RATE) * $v['DOL_CONV_RATE'] * $val['NET_AMOUNT'] , 2);
		        			} else {
		        				$finalArr[$incrementID]['NET_AMOUNT'] = round((1-billingVariables::NET_OFF_TAX_RATE) * $val['NET_AMOUNT'] , 2);
		        			}
	        			}
	        			
	        			if($val['PRICE']!=0){
	        				$finalArr[$incrementID]['DISCOUNT'] = round(($val['DISCOUNT']/$val['PRICE'])*100,2)."%";
	        			}
	        			else{
	        				$finalArr[$incrementID]['DISCOUNT'] = 0;
	        			}

	        			if((($val['PROFILEID'] % 3) + 1) == 1){
	        				$finalArr[$incrementID]['ACCEPTANCES'] = $newjsContactObj1->getContactAcceptanceCount($val['PROFILEID'],'BOTH');
	        				$status = $newjsJpartnerObj1->isDppSetByUser($val['PROFILEID']);
	        			} else if((($val['PROFILEID'] % 3) + 1) == 2){
	        				$finalArr[$incrementID]['ACCEPTANCES'] = $newjsContactObj2->getContactAcceptanceCount($val['PROFILEID'],'BOTH');
	        				$status = $newjsJpartnerObj2->isDppSetByUser($val['PROFILEID']);
	        			} else if((($val['PROFILEID'] % 3) + 1) == 3){
	        				$finalArr[$incrementID]['ACCEPTANCES'] = $newjsContactObj3->getContactAcceptanceCount($val['PROFILEID'],'BOTH');
	        				$status = $newjsJpartnerObj3->isDppSetByUser($val['PROFILEID']);
	        			}
	        			
	        			if($status == "E"){
	        				$finalArr[$incrementID]['DPP_SETTINGS'] = "Yes";
	        			} else {
	        				$finalArr[$incrementID]['DPP_SETTINGS'] = "No";
	        			}
	        			$finalArr[$incrementID]['Paid earlier'] = $v['Paid earlier'];
	        			//print_r($finalArr);
	        			unset($status, $incrementID);
	        		}
	        	}
	        }
        }
       
        $filepath = JsConstants::$docRoot."/uploads/csv_files/";
		$filename = $filepath."dailyMailerReport.csv";
		unlink($filename);
		$csvData = fopen("$filename", "w") or print_r("Cannot Open");

		fputcsv($csvData, array('Username','Age','Gender','City','Community','Channel','Payment Date','Service Activation Date (EST)','Membership Name','Net Amount','Discount %','Acceptances','DPP Settings','Paid earlier'));
		foreach($finalArr as $key=>&$val) {
		    fputcsv($csvData, $val);
		}
		$file_size = filesize($filename);
		fclose($csvData);

		$csvAttachment = file_get_contents($filename);
		//print_r($csvAttachment);
		$to = "jsprod@jeevansathi.com";
		//$to = "ankita.g@jeevansathi.com";
		$cc = "ankita.g@jeevansathi.com,nitish.sharma@jeevansathi.com";
		$from = "js-sums@jeevansathi.com";
		$subject = "Daily Report on details of paying users";
		$msgBody = "PFA attached CSV report containing data, Note : For Pack Services like e-Advantage/e-Sathi discount percentages may be slightly incorrect !";

        SendMail::send_email($to, $msgBody, $subject, $from, $cc, '', $csvAttachment, '', 'dailyMailerReport.csv');
    }
}
