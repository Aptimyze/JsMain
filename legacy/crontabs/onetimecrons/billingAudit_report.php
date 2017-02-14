<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
//connect_db();
connect_slave();

$data ="User Id|Bill id|Bill date|Receipt id|Receipt date|Allotted Quantity|Remaining Quantity|Subscription Start Date|Subscription End Date|Product Name and Description|Net Amount|Discount Amount|Mode of Payment|Currency|Product Price|Product ID|Type of Membership\n";
$expiryDt='2014-01-31';
$expiryDt1='2014-02-01 00:00:00';

$sql ="select DISTINCT BILLID from billing.SERVICE_STATUS where EXPIRY_DT>'$expiryDt'";
$res = mysql_query($sql) or logError($sql);
while($row = mysql_fetch_array($res))
{
        $billId =$row['BILLID'];

	$sqlPUR ="select USERNAME,ENTRY_DT from billing.PURCHASES where BILLID='$billId' and STATUS='DONE'";
	$resPUR = mysql_query($sqlPUR) or logError($sqlPUR);	
	if($rowPUR = mysql_fetch_array($resPUR))
	{
		$username 	=$rowPUR['USERNAME'];
		$entryDtBillId	=$rowPUR['ENTRY_DT'];

		$sqlP ="select RECEIPTID,ENTRY_DT,MODE from billing.PAYMENT_DETAIL WHERE BILLID='$billId' AND STATUS='DONE' AND ENTRY_DT<'$expiryDt1'";
        	$resP = mysql_query($sqlP) or logError($sqlP);
        	if($rowP = mysql_fetch_array($resP))
		{
        		$receiptId 		=$rowP['RECEIPTID'];
			$entryDtReceiptId 	=$rowP['ENTRY_DT'];	 
			$mode			=$rowP['MODE'];	      
	
			$sqlS ="select PRICE,DISCOUNT,NET_AMOUNT,CUR_TYPE,SERVICEID,SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE from billing.PURCHASE_DETAIL where BILLID='$billId' AND STATUS='DONE'";
			$resS=mysql_query($sqlS) or logError($sqlS);
			while($rowS=mysql_fetch_array($resS)){
				$sSate 		=$rowS['SUBSCRIPTION_START_DATE'];
				$eDate		=$rowS['SUBSCRIPTION_END_DATE'];
				$serviceId	=$rowS['SERVICEID'];
				$productPrice 	=$rowS['PRICE'];
				$discount	=$rowS['DISCOUNT'];
				$netAmount	=$rowS['NET_AMOUNT'];
				$currencyType	=$rowS['CUR_TYPE'];
				
				if(!$serviceId)
					continue;

        		        $sqlN ="select SERVICEID,NAME from billing.SERVICES where SERVICEID='$serviceId'";
         		       	$resN=mysql_query($sqlN) or logError($sqlN);
         		       	$rowN=mysql_fetch_array($resN);
               	   		$name =$rowN['NAME'];
				if(!$name)
					continue;

				$sqlC ="select TOTAL_COUNT,USED_COUNT,SERVEFOR from billing.SERVICE_STATUS where BILLID='$billId' and SERVICEID='$serviceId'";
				$resC=mysql_query($sqlC) or logError($sqlC);
				if($rowC=mysql_fetch_array($resC)){
					$totalCnt =$rowC['TOTAL_COUNT'];
					$usedCnt  =$rowC['USED_COUNT'];
					$serveFor =$rowC['SERVEFOR'];
					if(strstr($serviceId,'I')){	
						if($totalCnt>=$usedCnt)
							$remainCnt =$totalCnt-$usedCnt;
					}
					if(strstr($serveFor,'F') || strstr($serveFor,'D') || strstr($serveFor,'X'))
						$membershipType ='Main';
					else
						$membershipType='Vas';
				}	

				// CSV generate
				$data .=$username."|".$billId."|".$entryDtBillId."|".$receiptId."|".$entryDtReceiptId."|".$totalCnt."|".$remainCnt."|".$sSate."|".$eDate."|".$name."|".$netAmount."|".$discount."|".$mode."|".$currencyType."|".$productPrice."|".$serviceId."|".$membershipType;
				$data .="\n";
				unset($totalCnt);
				unset($remainCnt);
				unset($usedCnt);
				unset($serveFor);
				unset($membershipType);	
				// csv ends
			}	
		}
	}
}
echo $data;

?>
