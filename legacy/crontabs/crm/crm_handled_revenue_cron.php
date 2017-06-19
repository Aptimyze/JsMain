<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
ini_set("max_execution_time","0");
include("$docRoot/web/mis/connect.inc");
$db =connect_master();

$todayNew=date("Y-m");
$startDate =$todayNew."-01 00:00:00";
//$startDate ="2013-03-01 00:00:00";

                // delete records of refund status from MIS.CRM_DAILY_ALLOT_MONTH
                $sqlDel1="SELECT PROFILEID,ALLOTED_TO FROM incentive.DEALLOCATION_TRACK WHERE DEALLOCATION_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND PROCESS_NAME IN('RELEASE_PROFILE','NO_LONGER_WORKING')";
                $resDel1=mysql_query_decide($sqlDel1,$db) or die("$sqlDel1".mysql_error_js());
                while($rowDel1=mysql_fetch_array($resDel1))
                {
                        $profileid_1  =$rowDel1['PROFILEID'];
                        $alloted_to_1 =$rowDel1['ALLOTED_TO'];
                        $sql1 ="DELETE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where PROFILEID='$profileid_1' AND ALLOTED_TO='$alloted_to_1' AND ENTRY_DT>='$startDate'";
                        mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());

                }

$sql1 ="select RECEIPTID from billing.PAYMENT_DETAIL WHERE ENTRY_DT >='$startDate' AND STATUS='DONE' ORDER BY RECEIPTID ASC LIMIT 1";
$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
$row1=mysql_fetch_array($res1);
if($row1['RECEIPTID']>0)
	$receiptId =$row1['RECEIPTID'];

if($receiptId>0){		
	$sql="SELECT PROFILEID,RECEIPTID,BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE RECEIPTID>'$receiptId' AND STATUS='DONE'";

		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$receiptId      =$row['RECEIPTID'];
			$billId         =$row['BILLID'];

			$pidCheck 	=0;
			$sqlCheck ="select PROFILEID from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where RECEIPTID ='$receiptId' AND BILLID='$billId'";
			$resCheck =mysql_query_decide($sqlCheck,$db) or die("$sqlCheck".mysql_error_js());
			$rowCheck =mysql_fetch_array($resCheck);
			if($rowCheck['PROFILEID'])
				$pidCheck =$rowCheck['PROFILEID'];
			
			if($pidCheck==0){			
				$profileid      =$row['PROFILEID'];
				$amount		=$row['AMOUNT'];
				$entryDate	=$row['ENTRY_DT'];
				$entryDateDD    =date("Y-m-d",strtotime("$entryDate"));
			
				$sql_crm ="select ALLOTED_TO from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' AND ALLOT_TIME<='$entryDate' AND DE_ALLOCATION_DT>='$entryDateDD'";
				$res_crm=mysql_query_decide($sql_crm,$db) or die("$sql_crm".mysql_error_js());
				while($row_crm=mysql_fetch_array($res_crm))
				{
					$alloted_to	=$row_crm['ALLOTED_TO'];	
					$sql_jsadmin	="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
					$res_jsadmin	=mysql_query_decide($sql_jsadmin,$db) or die("$sql_jsadmin".mysql_error_js());				
                                	$row_jsadmin	=mysql_fetch_array($res_jsadmin);
                               		$center		=strtoupper($row_jsadmin['CENTER']);

					$valid_id =0;
					$valid_id =check_validity_followup_new($billId,$alloted_to,$db);
					if($valid_id && $amount>0)
					{
						$sqlIns	="insert ignore into incentive.MONTHLY_INCENTIVE_ELIGIBILITY(`RECEIPTID`,`BILLID`,`PROFILEID`,`ALLOTED_TO`,`CENTER`,`AMOUNT`,`ENTRY_DT`) VALUES('$receiptId','$billId','$profileid','$alloted_to','$center','$amount','$entryDate')";
						//echo "\n";
						mysql_query_decide($sqlIns,$db) or die("$sqlIns".mysql_error_js());
					}
				}
			}
		}

		// delete records of refund status from MIS.CRM_DAILY_ALLOT_MONTH
                $sqlDel="SELECT RECEIPTID,BILLID FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND ENTRY_DT<=CURDATE() AND STATUS<>'DONE'";
                $resDel=mysql_query_decide($sqlDel,$db) or die("$sqlDel".mysql_error_js());
                while($rowDel=mysql_fetch_array($resDel))
		{
                        $billId         =$rowDel['BILLID'];
                        $receiptId      =$rowDel['RECEIPTID'];
		
			$sql ="DELETE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where RECEIPTID='$receiptId' AND BILLID='$billId'";
			mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());	
		}

	}

?>
