<?php

class incorrectBillingAppleFixTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'incorrectBillingAppleFixTask';
		$this->briefDescription = 'fix apple false success issue';
		$this->detailedDescription = <<<EOF
		The [incorrectBillingAppleFixTask|INFO] task fixes incorrect billings.
		Call it with:
		[php symfony CRM:incorrectBillingAppleFixTask|INFO]
EOF;
	}

    public function updateInPaymentDetail($billId,$db){
        $sql1 = "UPDATE billing.PAYMENT_DETAIL"
                . " SET STATUS ='CANCEL'"
                . " where BILLID ='$billId'";
        print_r("\n\n\n".$sql1."                 \n");
	$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
    }
    
    public function updateInPurchaseDetail($billId,$db){
        $sql2 = "UPDATE billing.PURCHASE_DETAIL"
                . " SET STATUS ='CANCEL'"
                . " where BILLID ='$billId'";
        print_r("\n".$sql2."                 \n");
	$res2=mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js($db));
    }
    
    public function updateInOrders($orderIdArrStr,$db){
        $sql3 = "UPDATE billing.ORDERS"
                . " SET STATUS ='N'"
                . " where ORDERID IN ($orderIdArrStr)";
        print_r("\n".$sql3."                 \n");
	$res3=mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js($db));
    }
       
    public function updateServiceStatus($billId,$db){
        $sql4 = "UPDATE billing.SERVICE_STATUS"
                . " SET ACTIVE ='N'"
                . " where BILLID ='$billId'";
        print_r("\n".$sql4."                 \n");
	$res4=mysql_query_decide($sql4,$db) or die("$sql4".mysql_error_js($db));
    }
    
    public function updatePurchases($billId,$db){
        $sql5 = "UPDATE billing.PURCHASES"
                . " SET STATUS ='CANCEL'"
                . " where BILLID ='$billId'";
        print_r("\n".$sql5."                 \n");
	$res5=mysql_query_decide($sql5,$db) or die("$sql5".mysql_error_js($db));
    }
     
    public function updatePaymentDetailsNew($billId,$db){
        $sql6 = "UPDATE billing.PAYMENT_DETAIL_NEW"
                . " SET STATUS ='CANCEL'"
                . " where BILLID ='$billId'";
        print_r("\n".$sql6."                 \n");
	$res6=mysql_query_decide($sql6,$db) or die("$sql6".mysql_error_js($db));
    }

	protected function execute($arguments = array(), $options = array())
	{
            print_r("\nExecusion Started\n");
            $startDate = '2017-01-01 00:00:00';
            $endDate = '2017-01-31 23:59:59';
            include_once(JsConstants::$docRoot."/classes/Membership.class.php");
            include_once(JsConstants::$docRoot."/profile/connect_db.php");
            $membershipObj = new Membership;
            //get all transactions within date range where payment was done from apple and entry exists in payment_details but not in apple_orders 
            $db = connect_db();                    
            $sql = "select BILLID,PROFILEID"
                        . " from billing.PAYMENT_DETAIL"
                        . " where APPLE_COMMISSION>0"
                        . " and ENTRY_DT between '$startDate' and '$endDate'"
                        . " and PROFILEID not in"
                                . " (select PROFILEID"
                                . " from billing.APPLE_ORDERS"
                                . " where ENTRY_DT between '$startDate' and '$endDate')";
            print_r($sql);
            
            $row = mysql_query_decide($sql, $db) or die("$sql Error" );
            while($row1 = mysql_fetch_array($row)){
                    $billIdArr[] = $row1['BILLID'];
                    $profileidArr[] = $row1['PROFILEID'];
            }
            $n = count($billIdArr);
            print_r("\nGot all Data, Number of records to update: $n");

            
            for($i=0;$i<$n;$i++){
                print_r("\n-------\n Going to update for $billIdArr[$i] \n");
                
                $this->updateInPaymentDetail($billIdArr[$i],$db);
                $this->updatePaymentDetailsNew($billIdArr[$i],$db);
                $this->updatePurchases($billIdArr[$i],$db);
                $this->updateInPurchaseDetail($billIdArr[$i],$db);
                if($billIdArr[$i]!="" && $profileidArr[$i] !="")
                    $membershipObj->stop_service($billIdArr[$i],$profileidArr[$i]);
                //$this->updateServiceStatus($billIdArr[$i],$db);
                //$this->updateInOrders($orderIdArr[$i],$db);
               
            }
            
            print_r("\nExecusion Finished\n");
	}
}
