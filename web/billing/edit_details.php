<?php

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$data=authenticated($cid);
$privilage=explode('+',getprivilage($cid));
if(in_array('BA',$privilage))
	$billing_admin="Y";
$smarty->assign("DUP",stripslashes($dup));

if(isset($data))
{
	if($save)
	{
		if($chk_status)
		{
			$MOD_FIELDS[]="pd.STATUS";
			$INS_FIELDS[]="STATUS";
		}
		if($chk_deposit_dt)
		{
			$MOD_FIELDS[]="pd.DEPOSIT_DT";
			$INS_FIELDS[]="DEPOSIT_DT";
		}
		if($chk_dep_branch)
		{
			$MOD_FIELDS[]='pd.DEPOSIT_BRANCH';
			$INS_FIELDS[]="DEPOSIT_BRANCH";
		}
		if($chk_mode)
		{
                        $MOD_FIELDS[]="MODE";
			$INS_FIELDS[]="MODE";
		}
                if($chk_cd_num)
		{
                        $MOD_FIELDS[]="CD_NUM";
			$INS_FIELDS[]="CD_NUM";
		}
                if($chk_cd_dt)
		{
                        $MOD_FIELDS[]="CD_DT";
			$INS_FIELDS[]="CD_DT";
		}
                if($chk_bank)
		{
                        $MOD_FIELDS[]="BANK";
			$INS_FIELDS[]="BANK";
		}
                if($chk_reason)
		{
                        $MOD_FIELDS[]="REASON";
			$INS_FIELDS[]="REASON";
		}
                if($chk_walkinby)
		{
                        $MOD_FIELDS[]="pur.WALKIN";
			$INS_FIELDS[]="WALKIN";
		}

		if(count($MOD_FIELDS)>0)
		{
			$sql_mod_str=implode(",",$MOD_FIELDS);
			$sql_ins_str=implode(",",$INS_FIELDS);
			$sql_log="INSERT into billing.EDIT_LOG(BILLID,RECEIPTID,LOG_STATUS,MOD_BY,MOD_DT,".$sql_ins_str.") SELECT $billid,$receiptid,'P','$user',now(),$sql_mod_str ";
			$sql_log.= "from billing.PAYMENT_DETAIL as pd,billing.PURCHASES as pur WHERE pd.RECEIPTID='$receiptid' AND pd.BILLID=pur.BILLID";
			mysql_query_decide($sql_log) or die($sql_log.mysql_error_js());
		}
		    
		$sql="SELECT AMOUNT,STATUS FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$oldstatus=$row['STATUS'];
		$amt=$row['AMOUNT'];

		$sql_s="SELECT DUEAMOUNT FROM billing.PURCHASES WHERE BILLID='$billid'";
		$res_s=mysql_query_decide($sql_s) or die(mysql_error_js());
		$row_s=mysql_fetch_array($res_s);
		$dueamt=$row_s['DUEAMOUNT'];


		$sql_u="UPDATE billing.PURCHASES SET";
		if($chk_status)
		{
			if($status != $oldstatus)
			{
				if($status=="REFUND")
				{
					if($oldstatus=="DONE")
					{
						$dueamt+=$amt;
						$sql_update[]=" DUEAMOUNT='$dueamt' "; 
					}
				}
				elseif($status=="DONE")
				{
					if($oldstatus=="REFUND")
					{
						$dueamt-=$amt;
						if($dueamt<0)
						{
							$dueamt=0;
						}
					$sql_update[]=" DUEAMOUNT='$dueamt' "; 
					}
				}
			}
	 	}
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
				$sql_update[]= " DEPOSIT_DT='$deposit_dt', DEPOSIT_BRANCH='$dep_branch'  ";
			}
		}

if(count($sql_update)>0)
{
		$sql_update_str=implode(",",$sql_update);
		$sql_u=$sql_u.$sql_update_str."WHERE BILLID='$billid'";
		mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_query_decide());
}

		$sql="UPDATE billing.PAYMENT_DETAIL SET ";

		if($chk_mode)
			$sql_pd[]=" MODE='$mode' ";
		if($chk_cd_num)
			$sql_pd[]=" CD_NUM='$cd_num' ";
		if($chk_cd_dt)
			$sql_pd[]=" CD_DT='$cd_dt' ";
		if($chk_deposit_dt || $chk_dep_branch)
			$sql_pd[]=" DEPOSIT_DT='$deposit_dt', DEPOSIT_BRANCH='$dep_branch' ";
		if($chk_bank)
			$sql_pd[]=" BANK='$bank' ";
		if($chk_reason)
			$sql_pd[]=" REASON='$reason' ";
		if($chk_status)
			$sql_pd[]=" STATUS='$status' ";
if(count($sql_pd)>0)
{
                $sql_update_pd_str=implode(",",$sql_pd);
                $sql_u=$sql.$sql_update_pd_str."WHERE RECEIPTID='$receiptid'";
                mysql_query_decide($sql_u) or die("$sql_u<br>".mysql_query_decide());
}

		if(count($MOD_FIELDS)>0)
                {
                        $sql_mod_str=implode(",",$MOD_FIELDS);
                        $sql_ins_str=implode(",",$INS_FIELDS);
                        $sql_log="INSERT into billing.EDIT_LOG(BILLID,RECEIPTID,LOG_STATUS,MOD_BY,MOD_DT,".$sql_ins_str.") SELECT $billid,$receiptid,'M','$user',now(),$sql_mod_str ";
                        $sql_log.= "from billing.PAYMENT_DETAIL as pd,billing.PURCHASES as pur WHERE pd.RECEIPTID='$receiptid' AND pd.BILLID=pur.BILLID";
                        mysql_query_decide($sql_log) or die("$sql_log".mysql_error_js());
                }


		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("flag","saved");
		$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
		$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));

		$smarty->display("edit_details.htm");
	}
	elseif($delete)
	{
		if(trim($delreason)!="")
		{
			$deldate=date("Y-m-d");

			$sql="SELECT AMOUNT,STATUS FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($res);
			$oldstatus=$row['STATUS'];
			$amt=$row['AMOUNT'];
			if($oldstatus=='DONE')
			{
				if($add_back_amt)
				{
					$sql_s="SELECT DUEAMOUNT FROM billing.PURCHASES WHERE BILLID='$billid'";
					$res_s=mysql_query_decide($sql_s) or die(mysql_error_js());
					$row_s=mysql_fetch_array($res_s);
					$dueamt=$row_s['DUEAMOUNT'];
					if($oldstatus=="DONE")
					{
						$dueamt+=$amt;
		
						$sql_u="UPDATE billing.PURCHASES SET DUEAMOUNT='$dueamt' WHERE BILLID='$billid'";
						mysql_query_decide($sql_u) or die(mysql_error_js());
					}
				}

				$user = getuser($cid);
				$sql="UPDATE billing.PAYMENT_DETAIL SET STATUS='DELETE', ENTRYBY = '$user', DEL_REASON = '$delreason' WHERE RECEIPTID='$receiptid'";
				mysql_query_decide($sql) or die(mysql_error_js());

				$msg=" Profileid : $profileid \n";
				$msg.=" Username : $uname \n";
				$msg.=" Bill id : $billid \n";
				$msg.=" Receipt id : $receiptid \n";
				$msg.=" Deleted by : $user \n";
				$msg.=" Date : $deldate \n";
				$msg.=" Reason : $delreason ";

				$subject = "Payment deleted for $uname by $user";
				$from = "info@jeevansathi.com";
				$to = "vivek@jeevansathi.com";
				//$cc = "mahesh@naukri.com";
				$cc = "payments@jeevansathi.com";

				$msg = ereg_replace("\n","<br>",$msg);
				send_email($to,$msg,$subject,$from,$cc);

				$smarty->assign("USER",$user);
				$smarty->assign("CID",$cid);
				$smarty->assign("phrase",$phrase);
				$smarty->assign("criteria",$criteria);
				$smarty->assign("billid",$billid);
				$smarty->assign("flag","deleted");
				$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
				$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
				$smarty->display("edit_details.htm");
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
		$sql="SELECT PROFILEID,RECEIPTID,BILLID,MODE,TYPE,AMOUNT,CD_NUM,CD_DT,CD_CITY,BANK,OBANK,REASON,STATUS,ENTRY_DT,ENTRYBY,DEPOSIT_BRANCH,DEPOSIT_DT FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
			$billid=$row['BILLID'];
			$receiptid=$row['RECEIPTID'];
			$mode=$row['MODE'];
//			$type=$row['TYPE'];
//			$amt=$row['AMOUNT'];
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
			$res1 = mysql_query_decide($sql1) or die(mysql_error_js());
			$row1 = mysql_fetch_array($res1);
			$walkinby = $row1['WALKIN'];
		}

		$sql_u="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%BU%'";
		$res_u=mysql_query_decide($sql_u) or die(mysql_error_js());
		$i=0;
		while($row_u=mysql_fetch_array($res_u))
		{
			$users[$i]=$row_u['USERNAME'];
			$i++;
		}
		$sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $dep_branch_arr[$i]=$row['NAME'];
                        $i++;
                }
		
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("profileid",$profileid);
		$smarty->assign("billid",$billid);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("mode",$mode);
//		$smarty->assign("type",$type);
//		$smarty->assign("amt",$amt);
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
		$smarty->assign("dep_branch_arr",$dep_branch_arr);

//		$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
//		$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));

		$smarty->display("edit_details.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
//        $smarty->assign("username","$username");
        $smarty->display("jsconnectError.tpl");
}
?>
