<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/jsadmin/connect.inc");
include_once($_SERVER["DOCUMENT_ROOT"]."/profile/pg/functions.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/billing/comfunc_sums.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Membership.class.php");
$data=authenticated($cid);
if(isset($data))
{
	$serviceObj = new Services;
	$membershipObj = new Membership;
	$cri = populate_search_criteria();
	$smarty->assign("cri",$cri);
	$smarty->assign("offline_billing",$offline_billing);
	$user=getuser($cid);
	$privilage=getprivilage($cid);
	$priv=explode("+",$privilage);

	if(in_array('BA',$priv))
	{
		$smarty->assign("ADMIN","Y");
	}
	/*creation of query depending on critera starts*/
	if(trim($phrase)!="")
	{
		$flag=1;
		$sql="SELECT newjs.JPROFILE.PROFILEID,newjs.JPROFILE.USERNAME,newjs.JPROFILE.SOURCE,billing.PURCHASES.BILLID";
		if($criteria=="uname")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES";
			$where=" WHERE newjs.JPROFILE.USERNAME='$phrase' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
		elseif($criteria=="billid")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES";//,billing.PAYMENT_DETAIL";
			$where=" WHERE billing.PURCHASES.BILLID='$phrase' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
		elseif($criteria=="email")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES";
			$where=" WHERE newjs.JPROFILE.EMAIL='$phrase' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
		elseif($criteria=="cdnum")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES,billing.PAYMENT_DETAIL";
			$where=" WHERE billing.PAYMENT_DETAIL.CD_NUM='$phrase' AND billing.PURCHASES.PROFILEID=PAYMENT_DETAIL.PROFILEID and newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
		elseif($criteria=="reqid")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES,incentive.PAYMENT_COLLECT";
			$where=" WHERE incentive.PAYMENT_COLLECT.ID ='$phrase' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND newjs.JPROFILE.PROFILEID=incentive.PAYMENT_COLLECT.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
		elseif($criteria=="phone")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES";
			$where=" WHERE billing.PURCHASES.RPHONE='$phrase' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
		elseif($criteria=="mobile")
		{
			$from=" FROM newjs.JPROFILE,billing.PURCHASES";
			$where=" WHERE billing.PURCHASES.MPHONE='$phrase' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
		}
                elseif($criteria=="orderid")
                {
                        list($orderid,$id)=explode("-",$phrase);
                        $from=" FROM newjs.JPROFILE,billing.PURCHASES,billing.ORDERS";
                       $where=" WHERE billing.ORDERS.ORDERID='$orderid' AND billing.ORDERS.ID='$id' AND newjs.JPROFILE.PROFILEID=billing.PURCHASES.PROFILEID AND newjs.JPROFILE.PROFILEID=billing.ORDERS.PROFILEID AND billing.PURCHASES.STATUS in ('DONE','STOPPED','CANCEL')";
                }

		$sql.=$from.$where." order by BILLID desc";
		$res=mysql_query_decide($sql) or logError_sums($sql,0);
		/*creation of query depending on critera ends*/

		if($row=mysql_fetch_array($res))//if entry is found in PURCHASES table.
		{
			$pid=$row['PROFILEID'];
			$username = $row['USERNAME'];
			$source_for_offline_check = $row["SOURCE"];
			if($source_for_offline_check == "ofl_prof" && !$offline_billing)
				$smarty->assign("ONLINE_TRYING_OFFLINE",1);
			elseif($source_for_offline_check != "ofl_prof" && $offline_billing)
				$smarty->assign("OFFLINE_TRYING_ONLINE",1);
			$billid_arr[]=$row['BILLID'];

			check_special_discount($pid);
			$marked_for_deletion = check_marked_for_deletion($pid);

			$smarty->assign("found","1"); 
			$smarty->assign("userexists","1"); 
			$smarty->assign("USERNAME",$phrase); 
			$smarty->assign("BILLED_USERNAME",$username); 
			$smarty->assign("PID",$pid);
		}
		else//if no entry in PURCHASES table then search in JPROFILE.
		{
			if($criteria="uname")
			{
				$sql="SELECT PROFILEID,SOURCE from newjs.JPROFILE where USERNAME='$phrase'";
				$result=mysql_query_decide($sql) or logError_sums($sql,0);
				if($myrow=mysql_fetch_array($result))
				{
					$smarty->assign("userexists","1"); 
					$source_for_offline_check = $myrow["SOURCE"];
					if($source_for_offline_check == "ofl_prof" && !$offline_billing)
						$smarty->assign("ONLINE_TRYING_OFFLINE",1);
					elseif($source_for_offline_check != "ofl_prof" && $offline_billing)
						$smarty->assign("OFFLINE_TRYING_ONLINE",1);
				}
				$pid=$myrow['PROFILEID'];
			}

			//aded by sriram to show 40% discount for users with score > 300
                        $sql_score_discount = "SELECT PROFILEID FROM billing.SCORE_MAILER WHERE PROFILEID='$pid'";
                        $res_score_discount = mysql_query_decide($sql_score_discount) or logError_sums($sql_score_discount,0);
                        if(mysql_num_rows($res_score_discount) > 0)
                                $smarty->assign("SPECIAL_SCORE_DISCOUNT",1);
                        //end of - aded by sriram to show 40% discount for users with score > 300

			check_special_discount($pid);
			$marked_for_deletion = check_marked_for_deletion($pid);

			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("PID",$pid);
			$smarty->assign("flag","NO_RECORD");
			$smarty->assign("SOURCE",$source);
			$smarty->assign("CRM_ID",$crm_id);
			$smarty->assign("SHOWLINK","NEW");
			$smarty->assign("USERNAME",$phrase); 
			$smarty->assign("PID",$pid);
			if($marked_for_deletion)
				$smarty->assign("MARKED_FOR_DELETION",1);
			$smarty->display("search_user.htm");
			exit;
		}
		
		$today = date("Y-m-d");
		$from_source_arr = populate_from_source();
		
		$sql_purchases = "SELECT * FROM billing.PURCHASES WHERE PROFILEID='$pid' ORDER BY BILLID";
		$res_purchases = mysql_query_decide($sql_purchases) or logError_sums($sql_purchases);
		while($row_purchases = mysql_fetch_assoc($res_purchases))
		{
			unset($orderid);
			$billid = $row_purchases["BILLID"];
		
			$transObj = new billing_TRACK_TRANSACTION_DISCOUNT_APPROVAL('newjs_slave');
            $approvedByArr = $transObj->fetchApprovedBy($billid);
            $user_details[$billid]["APPROVED_BY"] = $approvedByArr[$billid];
            
			$user_details[$billid]["BILLID"] = $billid;
			
			$sql_order = "SELECT ID, ORDERID FROM billing.ORDERS WHERE ID = '$row_purchases[ORDERID]'";
			$res_order = mysql_query_decide($sql_order) or logError_sums($sql_order);
			$row_order = mysql_fetch_array($res_order);
			if($row_order)
				$orderid = $row_order['ORDERID']."-".$row_order['ID'];
				
			$user_details[$billid]["ORDERID"] = $orderid;
			
			$sql_check_edit = "SELECT COUNT(*) FROM billing.EDIT_DETAILS_LOG WHERE BILLID='$billid'";
			$res_check_edit = mysql_query_decide($sql_check_edit) or logError_sums($sql_check_edit);
			$row_check_edit = mysql_fetch_row($res_check_edit);
			if($row_check_edit[0]>0)
				$user_details[$billid]['EDITED'] = 1;
				
			if($row_purchases["STATUS"] == "CANCEL"){
				$user_details[$billid]["CANCELLED"] = 1;
                $memHandlerObject = new MembershipHandler();
                $user_details[$billid]["CANCELLED_ON"] = $memHandlerObject->getCancelledDate($billid);
                unset($memHandlerObject);
            }
			else
				$partpay_arr[] = $billid;
					
			$user_details[$billid]["DUEAMOUNT"] = $row_purchases['DUEAMOUNT'];
			$user_details[$billid]["DUEDATE"] = $row_purchases['DUEDATE'];
			if($user_details[$billid]["DUEAMOUNT"] > 0)
				$user_details[$billid]['SHOW_WRITEOFF']=1;
				
			$user_details[$billid]["COMMENT"]=$row_purchases['COMMENT'];
			$user_details[$billid]["TAX_RATE"] = $row_purchases["TAX_RATE"];
			
			$user_details[$billid]["DISCOUNT_TYPE"] = memDiscountTypes::$discountArr[$row_purchases["DISCOUNT_TYPE"]];
			if(strtotime($row_purchases['ENTRY_DT']) > strtotime(date("2015-05-10 00:00:00"))){
				$user_details[$billid]["DISCOUNT"] = round($row_purchases["DISCOUNT"],2);
			} else {
				$user_details[$billid]["DISCOUNT"] = round(($row_purchases["DISCOUNT"]*(100+ $user_details[$billid]["TAX_RATE"])/100),2);
			}
			$user_details[$billid]["CURTYPE"] = $row_purchases["CUR_TYPE"];
			
			$serviceid_str = $row_purchases["SERVICEID"];
			$convertCToNCP = false;
			if(strstr($serviceid_str, 'NCP')){
				$convertCToNCP = true;
			}
			$serviceid_arr = @explode(",",$serviceid_str);

			 unset($service_details);
			unset($price);
			unset($price_tax);
                        $sql_service = "SELECT * FROM billing.PURCHASE_DETAIL WHERE BILLID='$billid'";
                        $res_service = mysql_query_decide($sql_service) or logError_sums($sql_service);
        while($row_service = mysql_fetch_assoc($res_service))
        {
                $serviceid = $row_service["SERVICEID"];
                if(strpos($serviceid,"J")===false){
	                $sname = $serviceObj->getServiceName($serviceid);
	                if($convertCToNCP == true && strpos($sname[$serviceid]["NAME"], "e-Value Pack")!==false){
	                	$service_details[$serviceid]["SERVICE"] = str_replace("e-Value Pack", "eAdvantage", $sname[$serviceid]["NAME"]);
	                }
	                else{
	                	$service_details[$serviceid]["SERVICE"] = $sname[$serviceid]["NAME"];
	                }
					if(ddiff($row_service["START_DATE"])>0)
					{
	                                	$service_details[$serviceid]["ACTIVATE_ON"] = $row_service["START_DATE"];
	                                	$service_details[$serviceid]["ACTIVATED_ON"] = "0000-00-00";
					}
					else
					{
	                                	$service_details[$serviceid]["ACTIVATE_ON"] = "0000-00-00";
	                                	$service_details[$serviceid]["ACTIVATED_ON"] = $row_service["START_DATE"];
					}
	                $service_details[$serviceid]["EXPIRY_DT"] = $row_service["END_DATE"];
	                $service_details[$serviceid]["PRICE"]=$row_service["PRICE"];
	                $service_details[$serviceid]["EXPIRY_DT_COLOR"] = get_expiry_dt_color($row_service["END_DATE"]);

	// Current Time
	                list($yy,$mm,$dd) = @explode("-",$today);
	                $ts = mktime(0,0,0,$mm,$dd,$yy);
	// Expiry Time
	                list($eyy,$emm,$edd) = @explode("-",$service_details[$serviceid]["EXPIRY_DT"]);
	                $ets = mktime(0,0,0,$emm,$edd,$eyy);

	/*if($eyy > $yy)
	                        $refund_arr[] = $billid;
	                elseif($ets > $ts)*/
	                $refund_arr[] = $billid;
				$price_tax += $row_service["PRICE"];
			}
        }
            $user_details[$billid]["SERVICE_DETAILS"] = $service_details;
	
			$services_amount = $serviceObj->getServicesAmountWithoutTax($serviceid_str,$user_details[$billid]["CURTYPE"]);
			$services_amount_tax = $serviceObj->getServicesAmount($serviceid_str,$user_details[$billid]["CURTYPE"]);
			

			for($i=0;$i<count($serviceid_arr);$i++)
			{
				$price += $services_amount[$serviceid_arr[$i]]["PRICE"];
			//	$price_tax += $services_amount_tax[$serviceid_arr[$i]]["PRICE"];
			}
			
			$user_details[$billid]["PRICE_WITHOUT_TAX"] = $price;
			$amount_with_discount = $price - $user_details[$billid]["DISCOUNT"];
			if(strtotime($row_purchases['ENTRY_DT']) > strtotime(date("2015-05-10 00:00:00"))){
				$user_details[$billid]["TAX"] = round($amount_with_discount,2);
			} else {
				$user_details[$billid]["TAX"] = round(($amount_with_discount * ($user_details[$billid]["TAX_RATE"]/100)),2);
			}
			$user_details[$billid]["PRICE"] = $price_tax;
			//$user_details[$billid]["TOTAL_AMOUNT"] = floor($amount_with_discount + $user_details[$billid]["TAX"]);
			$user_details[$billid]["TOTAL_AMOUNT"] =$price_tax-$user_details[$billid]["DISCOUNT"];
			
			unset($receipt_details);
			$sql_payment = "SELECT * FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid' ORDER BY RECEIPTID";
			$res_payment = mysql_query_decide($sql_payment) or logError_sums($sql_payment);
			while($row_payment = mysql_fetch_assoc($res_payment))
			{
				$receiptid = $row_payment["RECEIPTID"];
				$receipt_details[$receiptid]['RECEIPTID'] = $receiptid;
				list($position,$value) = multi_array_search($row_payment["SOURCE"],$from_source_arr);
				$receipt_details[$receiptid]["SOURCE"] = $from_source_arr[$position]['name'];
				if($row_payment["SOURCE"] == "BANK_TRSFR_CASH")
				{
					$receipt_details[$receiptid]['TRANSFER_DATE'] = $row_payment['CD_DT'];
					$receipt_details[$receiptid]['TRANSFER_CITY'] = $row_payment['CD_CITY'];
					$receipt_details[$receiptid]['TRANSFER_BANK'] = $row_payment['BANK'];
				}
				else
					$receipt_details[$receiptid]['TRANSACTION_NUMBER'] = $row_payment['TRANS_NUM'];
					
				$receipt_details[$receiptid]["MODE"] = $row_payment['MODE'];
				$receipt_details[$receiptid]["TYPE"] = $row_payment['TYPE'];
				$receipt_details[$receiptid]["AMOUNT"] = $row_payment['AMOUNT'];
				$receipt_details[$receiptid]["CD_NUM"] = $row_payment['CD_NUM'];
				$receipt_details[$receiptid]["CD_DT"] = $row_payment['CD_DT'];
				$receipt_details[$receiptid]["CD_CITY"] = $row_payment['CD_CITY'];
				$receipt_details[$receiptid]["BANK"] = $row_payment['BANK'];
				$receipt_details[$receiptid]["OBANK"] = $row_payment['OBANK'];
				$receipt_details[$receiptid]["REASON"] = $row_payment['REASON'];
				$receipt_details[$receiptid]["STATUS"] = $row_payment['STATUS'];
				$receipt_details[$receiptid]["BOUNCE_DT"] = $row_payment['BOUNCE_DT'];
				$receipt_details[$receiptid]["ENTRY_DT"] = $row_payment['ENTRY_DT'];
				$receipt_details[$receiptid]["ENTRYBY"] = $row_payment['ENTRYBY'];
				$receipt_details[$receiptid]["INVOICE_NO"] = $row_payment['INVOICE_NO'];
				$receipt_details[$receiptid]["DEPOSIT_DT"] = $row_payment['DEPOSIT_DT'];
                                $receipt_details[$receiptid]["DEPOSIT_BRANCH"] = $row_payment['DEPOSIT_BRANCH'];
				$receipt_details[$receiptid]["DEL_REASON"] = $row_payment['DEL_REASON'];
				
				if($receipt_details[$receiptid]["STATUS"] == "BOUNCE")
				{
					$priv = getprivilage($cid);
					if(strstr($priv,"BCR"))
					{
						$sql_bounce = "SELECT DISPLAY FROM billing.BOUNCED_CHEQUE_HISTORY WHERE RECEIPTID='$receiptid'";
						$res_bounce = mysql_query_decide($sql_bounce) or logError_sums($sql_bounce);
						if($row_bounce = mysql_fetch_array($res_bounce))
						{
							if ($row_bounce['DISPLAY'] == 'Y')
								$receipt_details[$receiptid]["ACTION_BOUNCE_LINK"] = 1;
							else
								$receipt_details[$receiptid]["ACTION_BOUNCE_LINK"] = 1;
						}
					}
				}
			}
			
			$user_details[$billid]["RECEIPT_DETAILS"] = $receipt_details;

			/*unset($service_details);
			$sql_service = "SELECT * FROM billing.PURCHASE_DETAIL WHERE BILLID='$billid'";
			$res_service = mysql_query_decide($sql_service) or logError_sums($sql_service);
			while($row_service = mysql_fetch_assoc($res_service))
			{
				$serviceid = $row_service["SERVICEID"];
				$sname = $serviceObj->getServiceName($serviceid);
				$service_details[$serviceid]["SERVICE"] = $sname[$serviceid]["NAME"];
				$service_details[$serviceid]["ACTIVATED_ON"] = $row_service["START_DATE"];
				$service_details[$serviceid]["ACTIVATE_ON"] = $row_service["ACTIVATE_ON"];
				$service_details[$serviceid]["EXPIRY_DT"] = $row_service["END_DATE"];
				$service_details[$serviceid]["PRICE"]=$row_service["PRICE"];
				$service_details[$serviceid]["EXPIRY_DT_COLOR"] = get_expiry_dt_color($row_service["END_DATE"]);
				list($eyy,$emm,$edd) = @explode("-",$service_details[$serviceid]["EXPIRY_DT"]);
				$ets = mktime(0,0,0,$emm,$edd,$eyy);
				list($yy,$mm,$dd) = @explode("-",$today);
				$ts = mktime(0,0,0,$mm,$dd,$yy);
				if($ets > $ts)
					$refund_arr[] = $billid;
			}
			$user_details[$billid]["SERVICE_DETAILS"] = $service_details;*/
		}
		print_r($user_details);//die;
		
		$smarty->assign("user_details",$user_details);

		//To show last active service.
		$sql_last_service = "SELECT BILLID, SERVICEID, ACTIVATED_ON, EXPIRY_DT,SERVEFOR FROM billing.SERVICE_STATUS WHERE PROFILEID='$pid' AND ACTIVE='Y'";
		$res_last_service = mysql_query_decide($sql_last_service) or logError_sums($sql_last_service);
		while($row_last_service = mysql_fetch_assoc($res_last_service))
		{
			if(strpos($row_last_service["SERVICEID"], "J")===false){
				$last_active_billid = $row_last_service["BILLID"];
				$sid = $row_last_service["SERVICEID"];
				$sname = $serviceObj->getServiceName($sid);
				if(strpos($sname[$sid]["NAME"], 'e-Value')!==false && strpos($row_last_service["SERVEFOR"], 'N')!==false){
					$last_active_services[$sid]["SERVICE"] = str_replace("e-Value Pack", "eAdvantage", $sname[$sid]["NAME"]);
				}
				else{
					$last_active_services[$sid]["SERVICE"] = $sname[$sid]["NAME"];
				}
				$last_active_services[$sid]["ACTIVATED_ON"] = $row_last_service["ACTIVATED_ON"];
				$last_active_services[$sid]["EXPIRY_DT"] = $row_last_service["EXPIRY_DT"];
				$last_active_services[$sid]["EXPIRY_DT_COLOR"] = get_expiry_dt_color($row_last_service["EXPIRY_DT"]);
			}
		}
		
		$sql_purchases = "SELECT USERNAME, NAME, ADDRESS, CITY, EMAIL, RPHONE, OPHONE, MPHONE, PIN, DISCOUNT, DISCOUNT_TYPE, DISCOUNT_REASON, CUR_TYPE, STATUS, WALKIN FROM billing.PURCHASES WHERE BILLID='$last_active_billid'";
		$res_purchases = mysql_query_decide($sql_purchases) or logError_sums($sql_purchases);
		$row_purchases = mysql_fetch_assoc($res_purchases);
		
		$sql_c = "SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$row_purchases[CITY]'";
		$res_c = mysql_query_decide($sql_c) or logError_sums($sql_c);
		$row_c = mysql_fetch_array($res_c);
		
		$last_active["USERNAME"] = $row_purchases["USERNAME"];
		$last_active["CUSTNAME"] = $row_purchases["NAME"];
		$last_active["CITY"] = $row_c["LABEL"];
		$last_active["EMAIL"] = $row_purchases["EMAIL"];
		$last_active["RPHONE"] = $row_purchases["RPHONE"];
		$last_active["OPHONE"] = $row_purchases["OPHONE"];
		$last_active["MPHONE"] = $row_purchases["MPHONE"];
		$last_active["PIN"] = $row_purchases["PIN"];
		$last_active["STATUS"] = $row_purchases["STATUS"];
		$last_active["WALKIN"] = $row_purchases["WALKIN"];
		$last_active["DISCOUNT_TYPE"] = memDiscountTypes::$discountArr[$row_purchases["DISCOUNT_TYPE"]];
		$last_active["DISCOUNT"] = $row_purchases["DISCOUNT"];
		$last_active["DISCOUNT_REASON"] = ereg_replace("\r\n|\n","<br>",$row_purchases["DISCOUNT_REASON"]);
		$last_active["CURTYPE"] = $row_purchases["CUR_TYPE"];
		
		$sql_verify = "SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
		$res_verify = mysql_query_decide($sql_verify) or logError_sums($sql_verify);
		$row_verify = mysql_fetch_array($res_verify);
		$subscription_arr = @explode(",",$row_verify["SUBSCRIPTION"]);
		if(@in_array("S",$subscription_arr))
		{
			$checksum1 = md5($pid)."i".$pid;
			$smarty->assign("checksum1",$checksum1);
			$last_active["VERIFY_LINK"] = 1;
		}

		$smarty->assign("last_active_services",$last_active_services);
		$smarty->assign("last_active",$last_active);
		
		/*to show/hide the links at the bottom of search page*/
		$sql_exp_dt = "SELECT COUNT(PROFILEID) AS CNT FROM billing.SERVICE_STATUS WHERE PROFILEID = '$pid' AND EXPIRY_DT >= '$today' AND ACTIVE='Y' ORDER BY BILLID DESC LIMIT 1";
		$res_exp_dt = mysql_query_decide($sql_exp_dt) or logError_sums($sql_exp_dt,0);
		$row_exp_dt = mysql_fetch_array($res_exp_dt);
		if($row_exp_dt['CNT'] > 0)
		{
			$sql_status = "SELECT STATUS FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$pid' ORDER BY RECEIPTID DESC LIMIT 1";
			$res_status = mysql_query_decide($sql_status) or logError_sums($sql_status,0);
			$row_status = mysql_fetch_array($res_status);
			if($row_status['STATUS']=='BOUNCE' || $row_status['STATUS']=='CHARGE_BACK')
			{
				$smarty->assign("hide_bottom_links_buttons","Y");
			}
		}
		/*to show/hide the links at the bottom of search page*/

		$billid_upgd_arr = @array_unique($refund_arr);
		$billid_upgd_arr = @array_reverse($billid_upgd_arr);
		$smarty->assign("billid_sel",$billid_upgd_arr[0]);
		//@sort($billid_upgd_arr,SORT_DESC);
		$cnt_upgd=count($billid_upgd_arr);
		$smarty->assign("cnt_upgd",$cnt_upgd);
                $smarty->assign("billid_upgd_arr",$billid_upgd_arr);
		if(is_array($partpay_arr))
		{
			$partpay_arr = array_unique($partpay_arr);
			$partpay_arr = array_reverse($partpay_arr);
		}
                @sort($refund_arr,SORT_DESC);
                $smarty->assign("partpay_sel",$partpay_arr[0]);
		$cnt_partpay=count($partpay_arr);
		$smarty->assign("cnt_partpay",$cnt_partpay);
		$smarty->assign("partpay_arr",$partpay_arr);
	
		/*$sql = "SELECT ID FROM incentive.PAYMENT_COLLECT WHERE PROFILEID='$pid' ORDER BY ID DESC";
		$res = mysql_query_decide($sql) or logError_sums($sql,0);
		while($row=mysql_fetch_array($res))
		{
			$req_id_arr[] = $row['ID'];
			$req_id_sel = $row['ID'];
		}
		$smarty->assign("cnt_req",count($req_id_arr));
		$smarty->assign("req_id_arr",$req_id_arr);
		$smarty->assign("req_id_sel",$req_id_sel);*/
		
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("uname",$uname);
		$smarty->assign("SOURCE",$source);
		$smarty->assign("CRM_ID",$crm_id);
		if($marked_for_deletion)
			$smarty->assign("MARKED_FOR_DELETION",1);
		$smarty->display("search_user.htm");
	}
	else
	{
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->display("search_user.htm");
	}
}
else
{
	$smarty->assign("CID",$cid);
        $smarty->display("jsconnectError.tpl");
}

function ddiff($date)
{
	$today=date('Y-m-d');
	list($yy,$mm,$dd) = @explode("-",$date);
	list($yy1,$mm1,$dd1) = @explode("-",$today);
	$days=$yy*365+$mm*30+$dd;
	$days1=$yy1*365+$mm1*30+$dd1;
	return($days-$days1);
}
?>
