<?php

class appleAmountFixTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'appleAmountFixTask';
		$this->briefDescription = 'fix apple amount issue issue';
		$this->detailedDescription = <<<EOF
		The [appleAmountFixTask|INFO] task fixes the cases where amount is incorrect because of wrong entry in service table.
		Call it with:
		[php symfony CRM:appleAmountFixTask|INFO]
EOF;
	}

    public function updateAmountAndCommissionInPaymentDetail($amount,$appleCommission,$billIdArrStr,$db){
        $sql1 = "UPDATE billing.PAYMENT_DETAIL"
                . " SET AMOUNT ='$amount',"
                . " APPLE_COMMISSION ='$appleCommission'"
                . " where BILLID IN ($billIdArrStr)";
        //print_r("\n\n\n".$sql1."                 \n");
	$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
    }
    
    public function updateAmountAndCommissionInPaymentDetailNew($price,$billIdArrStr,$db){
        $sql2 = "UPDATE billing.PAYMENT_DETAIL_NEW"
                . " SET AMOUNT ='$price'"
                . " where BILLID IN ($billIdArrStr)";
        //print_r("\n".$sql2."                 \n");
	$res2=mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js($db));
    }
    
    public function updateAmountInPurchaseDetail($serviceId,$price,$billIdArrStr,$db){
        $sql3 = "UPDATE billing.PURCHASE_DETAIL"
                . " SET PRICE ='$price',"
                . " NET_AMOUNT = '$price'"
                . " where BILLID IN ($billIdArrStr)"
                . " and SERVICEID ='$serviceId'";
        //print_r("\n".$sql3."                 \n");
	$res3=mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js($db));
    }

	protected function execute($arguments = array(), $options = array())
	{
            print_r("\nExecusion Started\n");
            $startDate = '2017-01-25 00:00:00';
            $endDate = '2017-01-30 23:59:59';
            
            //get all transactions within date range where payment was done from apple 
            include_once(JsConstants::$docRoot."/profile/connect_db.php");
            $db = connect_db();                    
            $sql  = "SELECT BILLID FROM billing.PAYMENT_DETAIL"
                        . " WHERE APPLE_COMMISSION>0"
                        . " and ENTRY_DT BETWEEN '$startDate' and '$endDate'";
            $row = mysql_query_decide($sql, $db) or die("$sql Error" );
            while($row1 = mysql_fetch_array($row))
                    $billIdArr[] = $row1['BILLID'];
            $billIdArrStr = implode(",", $billIdArr);
            
            //Get the serviceid for all the billid's got from above
            $sql2 = "SELECT SERVICEID, BILLID FROM billing.PURCHASES WHERE BILLID IN ($billIdArrStr)";
            $billIdArr2 = mysql_query_decide($sql2, $db) or die("$sql2 Error");
            while($row1=mysql_fetch_array($billIdArr2)){
                $key=$row1['SERVICEID'];
                $val=$row1['BILLID'];
                //check if serviceID is that of PL,C3 or CL which had incorrect billing amount
                if(strstr($key,'PL'))
	            $billIdPLArr[] = $val;
                else if(strstr($key,'C3')){
                    $billIdC3Arr[] = $val;
                }
                else if(strstr($key,'CL')){
                    $billIdCLArr[] = $val;
                }
            }
            
            $billIdPLArrStr = implode(",", $billIdPLArr);
            //print_r("\n PL: ");
            //print_r($billIdPLArrStr);
            
            $billIdC3ArrStr = implode(",", $billIdC3Arr);
            //print_r("\n C3: ");
            //print_r($billIdC3ArrStr);
            
            $billIdCLArrStr = implode(",", $billIdCLArr);
            //print_r("\n CL: ");
            //print_r($billIdCLArrStr);
            
            /*For 'PL' Set correct price(goes in purchase_details),
             *amount & applecommission(goes in payment_detail and payment_detail_new);
             */
            print_r("\nGot all Data, Going to update for PL\n");
            $serviceIdPL='PL';
            $pricePL='8500';        //100 amount
            $amountPL='5950';       //70 percent 
            $appleCommissionPL='2550';  //30 percent
            $this->updateAmountAndCommissionInPaymentDetail($amountPL,$appleCommissionPL,$billIdPLArrStr,$db);
            $this->updateAmountAndCommissionInPaymentDetailNew($pricePL,$billIdPLArrStr,$db);
            $this->updateAmountInPurchaseDetail($serviceIdPL,$pricePL,$billIdPLArrStr,$db);

            /*For 'C3' Set correct price(goes in purchase_details),
            *amount & applecommission(goes in payment_detail and payment_detail_new);
            */
            print_r("\nGoing to update for C3\n");
            $serviceIdC3='C3';
            $priceC3='4000';
            $amountC3='2800';
            $appleCommissionC3='1200';
            $this->updateAmountAndCommissionInPaymentDetail($amountC3,$appleCommissionC3,$billIdC3ArrStr,$db);
            $this->updateAmountAndCommissionInPaymentDetailNew($priceC3,$billIdC3ArrStr,$db);
            $this->updateAmountInPurchaseDetail($serviceIdC3,$priceC3,$billIdC3ArrStr,$db);
            
            /*For 'CL' Set correct price(goes in purchase_details),
            *amount & applecommission(goes in payment_detail and payment_detail_new);
            */
            print_r("\nGoing to update for CL\n");
            $serviceIdCL='CL';
            $priceCL='9900';
            $amountCL='6930';
            $appleCommissionCL='2970';
            $this->updateAmountAndCommissionInPaymentDetail($amountCL,$appleCommissionCL,$billIdCLArrStr,$db);
            $this->updateAmountAndCommissionInPaymentDetailNew($priceCL,$billIdCLArrStr,$db);
            $this->updateAmountInPurchaseDetail($serviceIdCL,$priceCL,$billIdCLArrStr,$db);
            
            print_r("\nExecusion Finished\n");
	}
}
