<?php

/**
 * This task checks for PayU Payments that were not completed in the last 24 hours
 * using th PayU API and updated their statuses
 * It is scheduled to run every 2 minuted and pick the records of last 24 hours
 */

class checkPayUPaymentStatusTask extends sfBaseTask
{
    protected function configure()
    {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));

        $this->namespace        = 'billing';
        $this->name             = 'checkPayUPaymentStatus';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [billing|INFO] task does things.
        Call it with:
        [php symfony billing:checkPayUPaymentStatus|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {   
        // SET BASIC CONFIGURATION
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        // Set date past which all data is to be archived
        $startDt = date('Y-m-d H:i:s', time() - 12*3600 );

        $_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;

        include_once(JsConstants::$docRoot."/profile/connect_db.php");
        include_once(JsConstants::$docRoot."/classes/Services.class.php");
        include_once(JsConstants::$docRoot."/classes/Membership.class.php");

        connect_db();

        $serObj = new Services();
        //$membershipObj = new Membership();
        //$memHandlerObj = new MembershipHandler();

        $billingOrdersObj = new BILLING_ORDERS('newjs_masterRep');
        //$billingPaymentStatusLogObj = new billing_PAYMENT_STATUS_LOG();

        $timeCheck = date('Y-m-d H:i:s', time() - 12*2600);

        $ordersArray = $billingOrdersObj->getFailedPayUOrders($timeCheck);

        if (is_array($ordersArray) && !empty($ordersArray) && count($ordersArray) > 0) {
            
            foreach ($ordersArray as $key => $value) {

		$membershipObj = new Membership();
		$memHandlerObj = new MembershipHandler();

                $profileid = $value['PROFILEID'];
                $currency = $value['CURTYPE'];
                $Order_Id = $value['ORDERID']."-".$value['ID'];

                if ($currency == "RS") {
                    if(JsConstants::$whichMachine == 'test') {
                        $endpoint = gatewayConstants::$PayUTestPullReqUrl;
                        $merchantID = gatewayConstants::$PayUTestRsMerchantId;
                        $salt = gatewayConstants::$PayUTestRsSalt;
                    } else {
                        $endpoint = gatewayConstants::$PayULivePullReqUrl;
                        $merchantID = gatewayConstants::$PayULiveRsMerchantId;
                        $salt = gatewayConstants::$PayULiveRsSalt;
                    }
                } 
                elseif ($currency == "DOL") {
                    if(JsConstants::$whichMachine == 'test') {
                        $endpoint = gatewayConstants::$PayUTestPullReqUrl;
                        $merchantID = gatewayConstants::$PayUTestDolMerchantId;
                        $salt = gatewayConstants::$PayUTestDolSalt;
                    } else {
                        $endpoint = gatewayConstants::$PayULivePullReqUrl;
                        $merchantID = gatewayConstants::$PayULiveDolMerchantId;
                        $salt = gatewayConstants::$PayULiveDolSalt;
                    }   
                }

                $command = 'verify_payment';
                $hashText = "$merchantID|$command|$Order_Id|$salt";
                $hash = hash("sha512", $hashText);
                $requestParams = array('key' => $merchantID,'command' => $command,'hash' => $hash, 'var1' => $Order_Id);

                $ch = curl_init($endpoint);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestParams);

                // This will contain the actual response returned from PayU 
                $response = curl_exec($ch);
                $response = json_decode($response);
                $errno    = curl_errno($ch);
                $errmsg   = curl_error($ch);
                curl_close($ch);

                if ($errno != 0) {
                	// this is in case the curl request failed, we continue with processing other orders !
                    continue; 
                }

                $gatewayRespObj = new billing_GATEWAY_RESPONSE_LOG();
                list($order_str, $order_num) = explode("-", $Order_Id);
                $responseMsg = serialize($response);
                $gatewayRespObj->insertResponseMessage($profileid, $order_num, $order_str, 'PAYU_POLLING', $responseMsg);

                $orderDetails = $response->transaction_details->$Order_Id;
                $status = $orderDetails->status;

                if ($status=="success") {
                    $AuthDesc = "Y";
                    $ret_status="S";
                    //if(!$billingPaymentStatusLogObj->checkIfEntryExists($Order_Id)){
                    	$membershipObj->log_payment_status($Order_Id, $ret_status, 'PAYU', 'polled-'.$status);
                    //}
                } else if ($status=="failure") {
                    $AuthDesc = "N";        
                    $ret_status="F";
                    //if(!$billingPaymentStatusLogObj->checkIfEntryExists($Order_Id)){
                    	$membershipObj->log_payment_status($Order_Id, $ret_status, 'PAYU', 'polled-'.$status);
                    //}
                }

                $dup = false;

                if ($AuthDesc=="Y") {
                    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
                    if (!$dup && $ret) {
                        $membershipObj->startServiceOrder($Order_Id);
                    }
                } 
                else if ($AuthDesc == "N") {
                    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
                }

                unset($AuthDesc, $profileid, $Order_Id, $currency);
		unset($membershipObj,$memHandlerObj);
            }
        }
    }
}
