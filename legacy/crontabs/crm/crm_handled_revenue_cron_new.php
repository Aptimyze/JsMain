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


	$sql ="select SOURCE_ID,DATE from incentive.LAST_HANDLED_DATE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res)){
                $sourceId 		=$row['SOURCE_ID'];          
               	$handledArr[$sourceId] 	=$row['DATE'];;	 
        }
	$lastReleasedDate =$handledArr[1];
	$lastManualEntryDt=$handledArr[2];

	
	// Delete manual released profile from MIS.CRM_DAILY_ALLOT_MONTH
	if($lastReleasedDate){
		$sqlDel1 ="SELECT PROFILEID,ALLOTED_TO,DEALLOCATION_DT FROM incentive.DEALLOCATION_TRACK WHERE DEALLOCATION_DT>'$lastReleasedDate' AND PROCESS_NAME IN('RELEASE_PROFILE','NO_LONGER_WORKING')";
		$resDel1=mysql_query_decide($sqlDel1,$db) or die("$sqlDel1".mysql_error_js());
		$setDate =false;	
		while($rowDel1=mysql_fetch_array($resDel1))
		{
			$profileid_1  	=$rowDel1['PROFILEID'];
			$alloted_to_1 	=$rowDel1['ALLOTED_TO'];
			$deAllocationDt =$rowDel1['DEALLOCATION_DT'];

			$sql1 ="DELETE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where PROFILEID='$profileid_1' AND ALLOTED_TO='$alloted_to_1' AND ENTRY_DT>='$startDate'";
			mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			$setDate =true;
		}
		if($setDate){
			$sqlDeall ="update incentive.LAST_HANDLED_DATE SET DATE='$deAllocationDt' where SOURCE_ID='1'";
			mysql_query_decide($sqlDeall,$db) or die("$sqlDeall".mysql_error_js());	
		}
	}


        // Handle fresh payments profiles based on last receiptid
        $sql1 ="select MAX(RECEIPTID) RECEIPTID from incentive.MONTHLY_INCENTIVE_ELIGIBILITY";
        $res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
        $row1=mysql_fetch_array($res1);
        if($row1['RECEIPTID']>0){
                $receiptId =$row1['RECEIPTID'];
                checkProfileValidation($receiptId);
        }


	// Handle manually back date allocated profiles 
	if($lastManualEntryDt){
		$sqlMan ="select PROFILEID, ENTRY_DT from incentive.MANUAL_ALLOT WHERE ALLOTED_BY!='jstech' AND ENTRY_DT>'$lastManualEntryDt' ORDER BY ENTRY_DT ASC";
	        $resMan=mysql_query_decide($sqlMan,$db) or die("$sqlMan".mysql_error_js());
		$setDate =false;
		while($rowMan=mysql_fetch_array($resMan)){
			$profileid 	=$rowMan['PROFILEID'];
			$manualEntryDt 	=$rowMan['ENTRY_DT'];		
	                checkProfileValidation('',$profileid,$manualEntryDt);
			$setDate =true;
	        }
		if($setDate){
		        $sqlMan1 ="update incentive.LAST_HANDLED_DATE SET DATE='$manualEntryDt' where SOURCE_ID='2'";
		        mysql_query_decide($sqlMan1,$db) or die("$sqlMan1".mysql_error_js());        
		}
	}	

	// Delete records of refund/cancel status from MIS.CRM_DAILY_ALLOT_MONTH
	$sqlDel="SELECT RECEIPTID,BILLID FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND ENTRY_DT<=CURDATE() AND STATUS<>'DONE'";
        $resDel=mysql_query_decide($sqlDel,$db) or die("$sqlDel".mysql_error_js());
        while($rowDel=mysql_fetch_array($resDel))
        {
        	$billId         =$rowDel['BILLID'];
        	$receiptId      =$rowDel['RECEIPTID'];
                $sql ="DELETE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where RECEIPTID='$receiptId' AND BILLID='$billId'";
                mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        }


function checkProfileValidation($receiptId='',$profileid='',$manualEntryDt='')
{
	global $db;
	$sql="SELECT PROFILEID,RECEIPTID,BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,MODE FROM billing.PAYMENT_DETAIL WHERE STATUS='DONE' AND AMOUNT>0";
	if($receiptId)
		$sql .=" AND RECEIPTID>'$receiptId'";
	elseif($profileid)
		$sql .=" AND PROFILEID='$profileid'";			

	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());		
	while($row=mysql_fetch_array($res))
	{
		$receiptId      =$row['RECEIPTID'];
		$billId         =$row['BILLID'];
		$mode		=$row['MODE'];	

		$profileid      =$row['PROFILEID'];
		$amount		=$row['AMOUNT'];
		$entryDate	=$row['ENTRY_DT'];
		$entryDateDD    =date("Y-m-d",JSstrToTime("$entryDate"));
	
		$sql_crm ="select ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' AND ALLOT_TIME<='$entryDate' AND DE_ALLOCATION_DT>='$entryDateDD' ORDER BY ID DESC LIMIT 1";
		$res_crm=mysql_query_decide($sql_crm,$db) or die("$sql_crm".mysql_error_js());
		if($row_crm=mysql_fetch_array($res_crm))
		{
			$alloted_to     =$row_crm['ALLOTED_TO'];
			$allot_time     =$row_crm['ALLOT_TIME'];
			$deAllocationDt =$row_crm['DE_ALLOCATION_DT'];

			// Actual de-allocation date check
			if($manualEntryDt)
				$include ='false';
			else
				$include=true;

			$sqlCheck ="select ID from incentive.DEALLOCATION_TRACK where PROFILEID='$profileid' AND ALLOTED_TO='$alloted_to' ORDER BY ID DESC LIMIT 1";	
			$resCheck    =mysql_query_decide($sqlCheck,$db) or die("$sqlCheck".mysql_error_js());
			if($rowCheck=mysql_fetch_array($resCheck)){
				$idSet =$rowCheck['ID'];
				if($manualEntryDt)
					$sqlTrack ="select PROFILEID from incentive.DEALLOCATION_TRACK where ID='$idSet' AND DATE(DEALLOCATION_DT)<='$deAllocationDt' AND DEALLOCATION_DT<='$manualEntryDt'";		
				else
					$sqlTrack ="select PROFILEID from incentive.DEALLOCATION_TRACK where ID='$idSet' AND DEALLOCATION_DT>'$allot_time' AND DATE(DEALLOCATION_DT)<='$deAllocationDt'";	
				$resTrack    =mysql_query_decide($sqlTrack,$db) or die("$sqlTrack".mysql_error_js());
				if($rowTrack=mysql_fetch_array($resTrack)){
					if($manualEntryDt)
						$include=true;
					else
						$include =false;	
				}
			}
			if($include){
				$sql_jsadmin	="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
				$res_jsadmin	=mysql_query_decide($sql_jsadmin,$db) or die("$sql_jsadmin".mysql_error_js());				
				$row_jsadmin	=mysql_fetch_array($res_jsadmin);
				$center		=strtoupper($row_jsadmin['CENTER']);

				$valid_id =0;
				$valid_id =check_validity_followup_new($billId,$alloted_to,$db);
				if($valid_id){
					$sqlIns	="insert ignore into incentive.MONTHLY_INCENTIVE_ELIGIBILITY(`RECEIPTID`,`BILLID`,`MODE`,`PROFILEID`,`ALLOTED_TO`,`ALLOT_TIME`,`CENTER`,`AMOUNT`,`ENTRY_DT`) VALUES('$receiptId','$billId','$mode','$profileid','$alloted_to','$allot_time','$center','$amount','$entryDate')";
					mysql_query_decide($sqlIns,$db) or die("$sqlIns".mysql_error_js());
				}
			}		
		}
	}
}


?>
