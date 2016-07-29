<?php

include_once("../jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("../profile/pg/functions.php");
include_once("comfunc_sums.php");
global $DOL_CONV_RATE;

$data = authenticated($cid);
$entryby = getuser($cid);

$smarty->assign("DUP",stripslashes($dup));
$smarty->assign("TAX_RATE",$TAX_RATE);
if(isset($data))
{
	maStripVARS_sums('stripslashes');
	//if save button is clicked.
	if($save)
	{
		//get previous rev master details
		$sql_old_purch = "SELECT START_DATE,END_DATE,DUEAMOUNT FROM billing.REV_MASTER WHERE SALEID='$saleid'";
		$res_old_purch = mysql_query_decide($sql_old_purch) or logError_sums($sqk_old_purch);
		$row_old_purch = mysql_fetch_array($res_old_purch);

		//get previous rev payment details.
		$sql_old_details = "SELECT MODE,TYPE,AMOUNT,TDS,SERVICE_TAX,CD_NUM,CD_DT,CD_CITY,BANK,REASON,SOURCE,TRANS_NUM FROM billing.REV_PAYMENT WHERE RECEIPTID = '$receiptid'";
		$res_old_details = mysql_query_decide($sql_old_details) or logError_sums($sql_old_details,0);
		$row_old_details = mysql_fetch_array($res_old_details); 

		if($chk_start_date)
		{
			$mod_str .= " START DATE CHANGED FROM ".$row_old_purch['START_DATE']." to ".$start_date.",\n";
			$sql_upd_purch[] = " START_DATE = '$start_date' ";
		}
		if($chk_end_date)
		{
			$mod_str .= " END DATE CHANGED FROM ".$row_old_purch['END_DATE']." to ".$end_date.",\n";
			$sql_upd_purch[] = " END_DATE = '$end_date' ";
		}
		if($chk_mode)
		{
			$mod_str .= " MODE CHANGED FROM ".$row_old_details['MODE']." to ".$mode.",\n";
			$sql_upd_arr[] = " MODE = '$mode' ";
		}if($chk_service_tax)
		{
			$mod_str .= " SERVICE_TAX CHANGED FROM ".$row_old_details['SERVICE_TAX']." to ".$service_tax1.",\n";
			$sql_upd_arr[] = " SERVICE_TAX = '$service_tax1' ";
		}else{
			$mod_str .= " SERVICE_TAX CHANGED FROM ".$row_old_details['SERVICE_TAX']." to 0 \n";
			$sql_upd_arr[] = " SERVICE_TAX = 0 ";
		} 
		if($chk_from_source)
		{
			$mod_str .= " SOURCE CHANGED FROM ".$row_old_details['SOURCE']." to ".$from_source.",\n";
			$sql_upd_arr[] = " SOURCE = '$from_source' ";
		}
		if($chk_transaction_number)
		{
			$mod_str .= " TRANSACTION NUMBER CHANGED";
			if($row_old_details['TRANS_NUM'])
				$mod_str .= " FROM ".$row_old_details['TRANS_NUM'];

			$mod_str .= " to ".$transaction_number.",\n";
			$sql_upd_arr[] = " TRANS_NUM = '$transaction_number' ";
		}
		if($chk_type)
		{
			$mod_str .= " TYPE CHANGED FROM ".$row_old_details['TYPE']." to ".$type.",\n";
			$sql_upd_arr[] = " TYPE = '$type' ";

			if(trim($type)=="DOL")
			{
				$dol_conv_rate = $DOL_CONV_RATE;
				$sql_upd_arr[] = " DOL_CONV_RATE='$dol_conv_rate' ";
			}
		}
		if($chk_amt)
		{
			$mod_str .= " AMOUNT CHANGED FROM ".$row_old_details['AMOUNT']." to ".$amt.",\n";
			$sql_upd_arr[] = " AMOUNT = '".addslashes(stripslashes($amt))."' ";

			$due_amount = $row_old_purch['DUEAMOUNT'];
			$old_amount = $row_old_details['AMOUNT'];

			$due_amount_new = ($due_amount + $old_amount) - $amt;
			if($due_amount_new < 0)
				$due_amount_new = 0;

		}//for tds
		if($chk_tds)
                {
                        $mod_str .= " TDS CHANGED FROM ".$row_old_details['TDS']." to ".$tds.",\n";
                        //$old_tds_amount = $row_old_details['TDS'];
                        if(isset($due_amount_new)){
                        	$due_amount_new = $due_amount_new - ($tds - $row_old_details['TDS']);
                        } else {
                        	$due_amount = $row_old_purch['DUEAMOUNT'];
                        	$old_amount = $row_old_details['TDS'];
                        	$due_amount_new = ($due_amount + $old_amount) - $tds;
                        }
                        if($due_amount_new < 0){
							$due_amount_new = 0;
                        }
                        $due_tds = $tds;
                        if($due_tds < 0)
                                $due_tds = 0;
			$sql_upd_arr[]=" TDS = '$due_tds' ";
                }


		if($chk_cdnum)
		{
			$mod_str .= " CD_NUM CHANGED";
			if($row_old_details['CD_NUM'])
				$mod_str .= " FROM ".$row_old_details['CD_NUM'];

			$mod_str .= " to ".$cd_num.",\n";
			$sql_upd_arr[] = " CD_NUM = '".addslashes(stripslashes($cd_num))."' ";
		}
		if($chk_cd_dt)
		{
			$mod_str .= " CD_DT CHANGED";
			if($row_old_details['CD_DT'])
				$mod_str .= " FROM ".$row_old_details['CD_DT'];

			$mod_str .= " to ".$cd_dt.",\n";
			$sql_upd_arr[] = " CD_DT = '".addslashes(stripslashes($cd_dt))."' ";
		}
		if($chk_bank)
		{
			$mod_str .= " BANK CHANGED";
			if($row_old_details['BANK'])
				$mod_str .= " FROM ".$row_old_details['BANK'];

			$mod_str .= " to ".$bank.",\n";
			$sql_upd_arr[] = " BANK = '".addslashes(stripslashes($bank))."' ";
		}
		if($chk_reason)
		{
			$mod_str .= " REASON CHANGED";
			if($row_old_details['REASON'])
				$mod_str .= " FROM ".$row_old_details['REASON'];

			$mod_str .= " to ".$reason.",\n";
			$sql_upd_arr[] = " REASON = '".addslashes(stripslashes($reason))."' ";
		}
		if($mod_str)
		{
			//log old details.
			$sql_log = "INSERT INTO billing.REV_EDIT_DETAILS_LOG(SALEID,RECEIPTID,CHANGES,ENTRYBY,ENTRY_DT) VALUES('$saleid','$receiptid','$mod_str','$entryby',now())";
			mysql_query_decide($sql_log) or logError_sums($sql_log,1);
		}

		if(isset($due_amount_new)){
			$sql_upd_purch[] = " DUEAMOUNT = '$due_amount_new' ";
		}
		//query to update the checked fields
		if(count($sql_upd_purch) > 0)
		{
			$sql_upd = "UPDATE billing.REV_MASTER SET";
			$sql_upd_str = implode(",",$sql_upd_purch);
			$sql_upd .= $sql_upd_str."WHERE SALEID='$saleid'";
			mysql_query_decide($sql_upd) or logError_sums($sql_upd,1);
		}
		//query to update the checked fields
		if(count($sql_upd_arr) > 0)
		{
			$sql_upd = "UPDATE billing.REV_PAYMENT SET";
			$sql_upd_str = implode(",",$sql_upd_arr);
			$sql_upd .= $sql_upd_str."WHERE RECEIPTID='$receiptid'";
			mysql_query_decide($sql_upd) or logError_sums($sql_upd,1);
		}

		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("flag","saved");

		$smarty->display("edit_rev_payment_details.htm");
	}
	//if delete button is clicked.
	elseif($delete)
	{
		if($chk_delreason && trim($delreason)!="")
		{
			$deldate=date("Y-m-d");

			$sql_old_details = "SELECT STATUS FROM billing.REV_PAYMENT WHERE RECEIPTID='$receiptid'";
			$res_old_details = mysql_query_decide($sql_old_details) or logError_sums($sql_old_details,0);
			$row_old_details = mysql_fetch_array($res_old_details);

			$changes = "STATUS CHANGED FROM ".$row_old_details['STATUS']." to DELETE";

			//log before deletion.
			$sql_log = "INSERT INTO billing.REV_EDIT_DETAILS_LOG(SALEID,RECEIPTID,CHANGES,ENTRYBY,ENTRY_DT) VALUES('$saleid','$receiptid','$changes','$entryby',now())";
			mysql_query_decide($sql_log) or logError_sums($sql_log,1);

			$sql="SELECT AMOUNT,STATUS FROM billing.REV_PAYMENT WHERE RECEIPTID='$receiptid'";
			$res=mysql_query_decide($sql) or logError_sums($sql,0);
			$row=mysql_fetch_array($res);
			$oldstatus=$row['STATUS'];
			$amt=$row['AMOUNT'];
			if($oldstatus=='DONE')
			{
				$user = getuser($cid);
				//query to delete the record.
				$sql="UPDATE billing.REV_PAYMENT SET STATUS='DELETE', ENTRYBY = '$user', DEL_REASON = '$delreason' WHERE RECEIPTID='$receiptid'";
				mysql_query_decide($sql) or die(mysql_error_js());

				//query to update due amount by reversing the existing transaction
				$sql2 = "SELECT SALEID, AMOUNT, TDS FROM billing.REV_PAYMENT WHERE RECEIPTID='$receiptid'";
				$res2=mysql_query_decide($sql2) or logError_sums($sql2,0);
				$row2=mysql_fetch_array($res2);
				$trans_rev_amount = $row2['AMOUNT']+$row2['TDS'];
				$trans_rev_saleid = $row2['SALEID'];

				$sql3 = "UPDATE billing.REV_MASTER SET DUEAMOUNT=DUEAMOUNT+'$trans_rev_amount' WHERE SALEID='$trans_rev_saleid'";
				$res3=mysql_query_decide($sql3) or logError_sums($sql3,0);
				$row3=mysql_fetch_array($res3);
				
				$msg.=" Username : $uname \n";
				$msg.=" Bill id : $billid \n";
				$msg.=" Receipt id : $receiptid \n";
				$msg.=" Deleted by : $user \n";
				$msg.=" Date : $deldate \n";
				$msg.=" Reason : $delreason ";

				$subject = "Payment deleted in Misc-Revenue billing for $uname by $user";
				$from = "info@jeevansathi.com";
				$to = "vivek@jeevansathi.com";
				//$cc = "mahesh@naukri.com";
				$msg = ereg_replace("\n","<br>",$msg);
				//send_email($to,$msg,$subject,$from,$cc);

				$smarty->assign("USER",$user);
				$smarty->assign("CID",$cid);
				$smarty->assign("phrase",$phrase);
				$smarty->assign("criteria",$criteria);
				$smarty->assign("billid",$billid);
				$smarty->assign("flag","deleted");
				$smarty->display("edit_rev_payment_details.htm");
			}
			else
			{
				$msg="This entry can not be deleted";
				$smarty->assign("name",$user);
				$smarty->assign("cid",$cid);
				$smarty->assign("MSG",$msg);
				$smarty->display("billing_msg.tpl");
			}
		}
	}
	else
	{
		//query to find payment details.
		$sql="SELECT RECEIPTID,SALEID,MODE,TYPE,AMOUNT,TDS,CD_NUM,CD_DT,CD_CITY,BANK,OBANK,REASON,STATUS,ENTRY_DT,ENTRYBY,SOURCE,TRANS_NUM,SERVICE_TAX FROM billing.REV_PAYMENT WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$saleid = $row['SALEID'];
			$billid=get_rev_saleid($row['SALEID']);
			$receiptid=$row['RECEIPTID'];
			$mode=$row['MODE'];
			$from_source = $row['SOURCE'];
			$transaction_number = $row['TRANS_NUM'];
			$type=$row['TYPE'];
			$amt=$row['AMOUNT'];
			$tds=$row['TDS'];
			$cd_num=$row['CD_NUM'];
			$cd_dt=$row['CD_DT'];
			$cd_city=$row['CD_CITY'];
			$bank=$row['BANK'];
			$obank=$row['OBANK'];
			$reason=$row['REASON'];
			$status=$row['STATUS'];
			$entry_dt=$row['ENTRY_DT'];
			$entryby=$row['ENTRYBY'];
			$service_tax = $row['SERVICE_TAX'];
		}

		$sql_master = "SELECT START_DATE,END_DATE FROM billing.REV_MASTER WHERE SALEID='$saleid'";
		$res_master = mysql_query_decide($sql_master) or logError_sums($sql_master,0);
		if($row_master = mysql_fetch_array($res_master))
		{
			$start_date = $row_master['START_DATE'];
			$end_date = $row_master['END_DATE'];
		}

		//populate mode of payment.
		$pay_mode = mode_of_payment();
		$smarty->assign("pay_mode",$pay_mode);

		//populate source of payment.
		$from_source_arr = populate_from_source();
		$smarty->assign("from_source_arr",$from_source_arr);

		$smarty->assign("saleid",$saleid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("billid",$billid);
		$smarty->assign("start_date",$start_date);
		$smarty->assign("end_date",$end_date);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("mode",$mode);
		$smarty->assign("from_source",$from_source);
		$smarty->assign("transaction_number",$transaction_number);
		$smarty->assign("type",$type);
		$smarty->assign("amt",$amt);
		$smarty->assign("tds",$tds);
		$smarty->assign("cd_num",$cd_num);
		$smarty->assign("cd_dt",$cd_dt);
		$smarty->assign("cd_city",$cd_city);
		$smarty->assign("bank",$bank);
		$smarty->assign("reason",$reason);
		$smarty->assign("status",$status);
		$smarty->assign("entry_dt",$entry_dt);
		$smarty->assign("entryby",$entryby);
		$smarty->assign("uname",$uname);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("service_tax",$service_tax);

		$smarty->display("edit_rev_payment_details.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
