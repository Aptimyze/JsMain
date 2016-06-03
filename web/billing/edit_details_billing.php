<?php
/**************************************************************************************************************************
FILE            : edit_details_billing.php
DESCRIPTION     : This file updates the edited values in PURCHASES and PAYMENT_DETAIL tables , if Save button is clicked
		: or deletes the details of a particular billid if Delete button is clicked.
MODIFIED BY	: SRIRAM VISWANTHAN.
REASON		: Revamp of billing modules.
DATE            : 18th October 2006.
**************************************************************************************************************************/
include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("comfunc_sums.php");
$data=authenticated($cid);
$entryby = getuser($cid);
$privilage=explode('+',getprivilage($cid));
if(in_array('BA',$privilage))
	$billing_admin="Y";
$smarty->assign("DUP",stripslashes($dup));

if(isset($data))
{
	maStripVARS_sums('stripslashes');
	/*When save button is clicked from the edit page*/
	if($save)
	{
		$mod_str = "";
		$sql_old_details = "SELECT * FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res_old_details = mysql_query_decide($sql_old_details) or logError_sums($sql_old_details,0);
		$row_old_details = mysql_fetch_array($res_old_details);
		$amt_paid = $row_old_details['AMOUNT']; //used to calculate due amount
		
		//checking which fields are to be changed.
		if($chk_deposit_dt)
		{
			$mod_str .= "DEPOSIT_DT changed";

			if($row_old_details['DEPOSIT_DT'])
				$mod_str .= " from ".$row_old_details['DEPOSIT_DT'];

			$mod_str .= " to ".$deposit_dt.",\n";
		}
		if($chk_dep_branch)
		{
			$mod_str .= "DEPOSIT_BRANCH changed";
			if($row_old_details['DEPOSIT_BRANCH'])
				$mod_str .= " from ".$row_old_details['DEPOSIT_BRANCH'];
		
			$mod_str .= " to ".html_entity_decode($dep_branch).",\n";
		}
		if($chk_mode)
		{
			$mod_str .= "MODE changed from ".$row_old_details['MODE']." to ".$mode.",\n";
		}
		if($chk_from_source)
		{
			$mod_str .= "SOURCE changed from ".$row_old_details['SOURCE']." to ".$from_source.",\n";
		}
		if($chk_transaction_number)
		{
			$mod_str .= "TRANS_NUM changed from ".$row_old_details['TRANS_NUM']." to ".$transaction_number.",\n";
		}
		if($chk_amt)
		{
			$mod_str .= "AMOUNT changed from ".$row_old_details['AMOUNT']." to ".$amt.",\n";
		}
                if($chk_cd_num)
		{
			$mod_str .= "CD_NUM changed";
			if($row_old_details['CD_NUM'])
				$mod_str .= "from ".$row_old_details['CD_NUM'];

			$mod_str .= " to ".$cd_num.",\n";
		}
                if($chk_cd_dt)
		{
			$mod_str .= "CD_DATE changed";
			if($row_old_details['CD_DATE'])
				$mod_str .= "from ".$row_old_details['CD_DATE'];

			$mod_str .= " to ".$cd_dt.",\n";
		}
                if($chk_cd_city)
		{
			$mod_str .= "CD_CITY changed";
			if($row_old_details['CD_CITY'])
				$mod_str .= "from ".$row_old_details['CD_CITY'];

			$mod_str .= " to ".$cd_city.",\n";
		}
                if($chk_bank)
		{
			$mod_str .= "BANK changed";
			if($row_old_details['BANK'])
				$mod_str .= "from ".$row_old_details['BANK'];

			$mod_str .= " to ".$bank.",\n";
		}
                if($chk_reason)
		{
			$mod_str .= "REASON changed";
			if($row_old_details['REASON'])
				$mod_str .= " from ".addslashes(stripslashes($row_old_details['REASON']));

			$mod_str .= " to ".addslashes(stripslashes($reason)).",\n";
		}
                if($chk_walkinby)
		{
			$sql_walkin = "SELECT WALKIN FROM billing.PURCHASES WHERE BILLID = '$billid'";
			$res_walkin = mysql_query_decide($sql_walkin) or logError_sums($sql_walkin,0);
			$row_walkin = mysql_fetch_array($res_walkin);

			$mod_str .= "WALKIN changed from ".$row_walkin['WALKIN']." to ".$walkinby.",\n";
		}

		//locking the edited details
		if($mod_str)
		{
			$sql_log="INSERT into billing.EDIT_DETAILS_LOG(PROFILEID,BILLID,RECEIPTID,CHANGES,ENTRYBY,ENTRY_DT) values('$row_old_details[PROFILEID]','$row_old_details[BILLID]','$row_old_details[RECEIPTID]','$mod_str','$entryby',now())";
			mysql_query_decide($sql_log) or logError_sums($sql_log,1); 
			change_notify_mail($receiptid, $mod_str, "E");//passing receiptid, the modified string and "E" indicating that the details has been edited
		}

		$oldstatus=$row_old_details['STATUS'];

		$sql_due = "SELECT DUEAMOUNT FROM billing.PURCHASES WHERE BILLID = '$billid'";
		$res_due = mysql_query_decide($sql_due) or logError_sums($sql_due,0);
		$row_due = mysql_fetch_array($res_due);
		$dueamt = $row_due['DUEAMOUNT'];

		if($amt<$amt_paid)
			$dueamt += ($amt_paid-$amt);
		else
			$dueamt -= ($amt - $amt_paid);
		if($dueamt < 0)
			$dueamt = 0;

		//updation in PURCHASE table.
		$sql_u="UPDATE billing.PURCHASES SET";


		$sql_update[] = " DUEAMOUNT = '$dueamt'";

		if($chk_walkinby)
		{
			if($walkinby)
			{
				$sql_update[]= " WALKIN = '$walkinby', CENTER = '".getcenter_for_walkin($walkinby)."' ";
			}
		}
		if($chk_deposit_dt || $chk_dep_branch)
		{
			if($deposit_dt)
			{
				$sql_update[]= " DEPOSIT_DT='$deposit_dt', DEPOSIT_BRANCH='".html_entity_decode($dep_branch)."'" ;
			}
		}
		if(count($sql_update)>0)
		{
			$sql_update_str=implode(",",$sql_update);
			$sql_u=$sql_u.$sql_update_str."WHERE BILLID='$billid'";
			mysql_query_decide($sql_u) or logError_sums($sql_u,1);
		}
		//updation in PAYMENT_DETAIL table
		$reason=addslashes(stripslashes($reason));
		$sql="UPDATE billing.PAYMENT_DETAIL SET ";

		if($chk_mode)
			$sql_pd[]=" MODE='$mode' ";
		if($chk_from_source)
			$sql_pd[]=" SOURCE='$from_source' ";
		if($chk_transaction_number)
			$sql_pd[]=" TRANS_NUM='$transaction_number' ";
		if($chk_cd_num)
			$sql_pd[]=" CD_NUM='$cd_num' ";
		if($chk_cd_dt)
			$sql_pd[]=" CD_DT='$cd_dt' ";
		if($chk_cd_city)
			$sql_pd[]=" CD_CITY='$cd_city' ";
		if($chk_deposit_dt || $chk_dep_branch)
			$sql_pd[]=" DEPOSIT_DT='$deposit_dt', DEPOSIT_BRANCH='$dep_branch' ";
		if($chk_bank)
			$sql_pd[]=" BANK='$bank' ";
		if($chk_reason)
			$sql_pd[]=" REASON='$reason' ";
		if($chk_status)
			$sql_pd[]=" STATUS='$status' ";
		if($chk_amt)
			$sql_pd[]=" AMOUNT='$amt' ";
		if(count($sql_pd)>0)
		{
	                $sql_update_pd_str=implode(",",$sql_pd);
        	        $sql_u=$sql.$sql_update_pd_str."WHERE RECEIPTID='$receiptid'";
                	mysql_query_decide($sql_u) or logError_sums($sql_u,1);
		}

		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("flag","saved");
		//$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
		//$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));

		$smarty->display("edit_details_billing.htm");
	}
	/*End of - When save button is clicked from the edit page*/
	/*When delete button is clicked from edit page*/
	elseif($delete)
	{
		if(trim($delreason)!="")
		{
			$sql_old_details = "SELECT * FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
			$res_old_details = mysql_query_decide($sql_old_details) or logError_sums($sql_old_details,0);
			$row_old_details = mysql_fetch_array($res_old_details);

			$change_str = "STATUS changed from ".$row_old_details['STATUS']." to DELETE,\n ";
			$change_str .= "REASON :- ".addslashes(stripslashes($delreason))."\n ";

			//lock delete details
			$sql_log = "INSERT INTO billing.EDIT_DETAILS_LOG(PROFILEID,BILLID,RECEIPTID,CHANGES,ENTRYBY,ENTRY_DT) VALUES('$row_old_details[PROFILEID]','$row_old_details[BILLID]','$row_old_details[RECEIPTID]','$change_str','$entryby',now())";
			mysql_query_decide($sql_log) or logError_sums($sql_log,1);

			change_notify_mail($receiptid, $change_str,"E");//passing receiptid, the modified string and "E" indicating that the details has been edited
			$deldate=date("Y-m-d");

			$sql="SELECT AMOUNT,STATUS FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
			$res=mysql_query_decide($sql) or logError_sums($sql,0);
			$row=mysql_fetch_array($res);
			$oldstatus=$row['STATUS'];
			$amt=$row['AMOUNT'];
			if($oldstatus=='DONE')
			{
				if($add_back_amt)
				{
					$sql_s="SELECT DUEAMOUNT FROM billing.PURCHASES WHERE BILLID='$billid'";
					$res_s=mysql_query_decide($sql_s) or logError_sums($sql_s,0);
					$row_s=mysql_fetch_array($res_s);
					$dueamt=$row_s['DUEAMOUNT'];
					if($oldstatus=="DONE")
					{
						$dueamt+=$amt;
		
						$sql_u="UPDATE billing.PURCHASES SET DUEAMOUNT='$dueamt' WHERE BILLID='$billid'";
						mysql_query_decide($sql_u) or logError_sums($sql_u,1);
					}
				}


				$user = getuser($cid);
				$delreason = addslashes(stripslashes($delreason));
				$sql="UPDATE billing.PAYMENT_DETAIL SET STATUS='DELETE', ENTRYBY = '$user', DEL_REASON = '$delreason' WHERE RECEIPTID='$receiptid'";
				mysql_query_decide($sql) or logError_sums($sql,1);


				$msg=" Profileid : $profileid \n";
				$msg.=" Username : $uname \n";
				$msg.=" Bill id : $billid \n";
				$msg.=" Receipt id : $receiptid \n";
				$msg.=" Deleted by : $user \n";
				$msg.=" Date : $deldate \n";
				$msg.=" Reason : $delreason ";

				$subject = "Payment deleted for $uname by $user";
				$from = "info@jeevansathi.com";
				//$to = "vivek@jeevansathi.com";
				$to = "payments@jeevansathi.com";

				$msg = ereg_replace("\n","<br>",$msg);
				send_email($to,$msg,$subject,$from);

				$smarty->assign("USER",$user);
				$smarty->assign("CID",$cid);
				$smarty->assign("phrase",$phrase);
				$smarty->assign("criteria",$criteria);
				$smarty->assign("billid",$billid);
				$smarty->assign("flag","deleted");
				//$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
				//$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
				$smarty->display("edit_details_billing.htm");
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
	/*End of - When delete button is clicked from edit page*/
	/*When the edit link is clicked from the search page*/
	else
	{
		$pay_mode = mode_of_payment();
		$smarty->assign("pay_mode",$pay_mode);

		$from_source_arr = populate_from_source();
		$smarty->assign("from_source_arr",$from_source_arr);

		$sql="SELECT PROFILEID,RECEIPTID,BILLID,MODE,TYPE,AMOUNT,CD_NUM,CD_DT,CD_CITY,BANK,OBANK,REASON,STATUS,ENTRY_DT,ENTRYBY,DEPOSIT_BRANCH,DEPOSIT_DT,SOURCE,TRANS_NUM FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or logError_sums($sql,0);
		if($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
			$billid=$row['BILLID'];
			$receiptid=$row['RECEIPTID'];
			$mode=$row['MODE'];
			$from_source=$row['SOURCE'];
			$transaction_number=$row['TRANS_NUM'];
			$amt=$row['AMOUNT'];
			$cd_num=$row['CD_NUM'];
			$cd_dt=$row['CD_DT'];
			$cd_city=$row['CD_CITY'];
			$bank=$row['BANK'];
			$obank=$row['OBANK'];
			$reason=$row['REASON'];
			$status=$row['STATUS'];
			$entry_dt=$row['ENTRY_DT'];
			$entryby=$row['ENTRYBY'];
			if($status=='DONE' && $billing_admin=='Y')
			{
				$smarty->assign("change_dep","Y");
				$deposit_dt=$row['DEPOSIT_DT'];
				$deposit_branch=$row['DEPOSIT_BRANCH'];
			}		
		

			$sql1 = "select WALKIN from billing.PURCHASES where BILLID = $billid";
			$res1 = mysql_query_decide($sql1) or logError_sums($sql1,0);
			$row1 = mysql_fetch_array($res1);
			$walkinby = $row1['WALKIN'];
		}

		$sql_u="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%BU%'  and ACTIVE='Y' UNION SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE LIKE '%OB%' and ACTIVE='Y'";
		$res_u=mysql_query_decide($sql_u) or logError_sums($sql_u,0);
		$i=0;
		while($row_u=mysql_fetch_array($res_u))
		{
			$users[$i]=$row_u['USERNAME'];
			$i++;
		}

		$dep_branch_arr = get_deposit_branches();
		$smarty->assign("dep_branch_arr",$dep_branch_arr);
		
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("profileid",$profileid);
		$smarty->assign("billid",$billid);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("mode",$mode);
		$smarty->assign("transaction_number",$transaction_number);
		$smarty->assign("from_source",$from_source);
		$smarty->assign("amt",$amt);
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
		$smarty->assign("users",$users);
		$smarty->assign("walkinby",$walkinby);
		$smarty->assign("deposit_dt",$deposit_dt);
		$smarty->assign("dep_branch",$deposit_branch);

		$smarty->display("edit_details_billing.htm");
	}
	/*End of - When the edit link is clicked from the search page*/
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
