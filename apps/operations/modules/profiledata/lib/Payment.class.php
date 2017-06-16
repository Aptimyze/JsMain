<?php
class Payment
{
	private $dbObj1;
	private $dbObj2;
	
	function paymentDetails($pid)
	{
		$dbObj1 = new BILLING_PAYMENT_DETAIL("newjs_slave");
		$dbObj2 = new BILLING_PAYSEAL("newjs_slave");
		$dbObj3 = new BILLING_ORDERS("newjs_slave");
		$dbObj4 = new BILLING_PURCHASES("newjs_slave");
		$memHandlerObj = new MembershipHandler;
		$paymentArr = $dbObj1->modeDetails($pid);
		$serviceNames = "";

		if(is_array($paymentArr))
		{
			for($i=0;$i<count($paymentArr);$i++)
			{
				$purDet = $dbObj4->fetchAllDataForBillid($paymentArr[$i]["BILLID"]);
				$services = $memHandlerObj->getServiceName($purDet['SERVICEID']);
				foreach($services as $key=>$val){
					$serviceNames[$i][] = $val['NAME'];
				}
				
				$paymentArr[$i]['SERVICE_NAMES'] = implode(", ", $serviceNames[$i]);

				if($paymentArr[$i]["MODE"] == 'ONLINE')
				{
					
					$orderArr = $dbObj4->orderDetails($paymentArr[$i]["BILLID"]);
					
					$onlinePaymentArr = $dbObj3->onlinePaymentDetails($orderArr[0]["ORDERID"]);
					
					if($onlinePaymentArr[0]['GATEWAY']=='PAYSEAL')
					{
						$OID = $onlinePaymentArr[0]['ORDERID']."-".$onlinePaymentArr[0]["ID"];
						$onlineArr = $dbObj2->tranInfo($OID);	
						
						$onlinePaymentArr[0]['TXNREFNO']=$onlineArr[0]['TXNREFNO'];
						$onlinePaymentArr[0]['RRN']=$onlineArr[0]['RRN'];	
					}
					else
					{
						$onlinePaymentArr[0]['TXNREFNO']='NIL';
						$onlinePaymentArr[0]['RRN']='NIL';
					}
					
					$paymentArr[$i]['ENTRY_DT'] = $onlinePaymentArr[0]['ENTRY_DT'];
					$paymentArr[$i]['GATEWAY'] = $onlinePaymentArr[0]['GATEWAY'];
					$paymentArr[$i]['TXNREFNO'] = $onlinePaymentArr[0]['TXNREFNO'];
					$paymentArr[$i]['RRN'] = $onlinePaymentArr[0]['RRN'];
								
				}
				else
				{
					//$paymentArr[$i]['ENTRY_DT'] ='NIL' ;
					$paymentArr[$i]['GATEWAY'] ='NIL';
					$paymentArr[$i]['TXNREFNO'] = 'NIL';
					$paymentArr[$i]['RRN'] = 'NIL';
				}
					
				
				
			}		
		}
		
		return $paymentArr;
	}
	
	
}


?>
