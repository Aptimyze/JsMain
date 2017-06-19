<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
ini_set("max_execution_time","0");
include("$docRoot/web/mis/connect.inc");
$db =connect_master();


	$sql ="SELECT * FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
                $receiptId      =$row['RECEIPTID'];
                $billId         =$row['BILLID'];
                $profileid      =$row['PROFILEID'];
		$alloted_to	=$row['ALLOTED_TO'];
		
			
		$sql1="SELECT MODE,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptId' AND BILLID='$billId'";
		$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
		$row1=mysql_fetch_array($res1);
		$mode 		=$row1['MODE'];
		$entryDt	=date("Y-m-d",strtotime($row1['ENTRY_DT']));

		$sql2 ="select ALLOT_TIME from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' AND ALLOTED_TO='$alloted_to' AND ALLOT_TIME<='$entryDt' AND DE_ALLOCATION_DT>='$entryDt' ORDER BY ID DESC LIMIT 1";
                $res2=mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
                $row2=mysql_fetch_array($res2);
                $allot_time =$row2['ALLOT_TIME'];

	        $sql3 ="update incentive.MONTHLY_INCENTIVE_ELIGIBILITY SET MODE='$mode',ALLOT_TIME='$allot_time' WHERE RECEIPTID='$receiptId' AND BILLID='$billId'";
        	mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
	}
		

?>
