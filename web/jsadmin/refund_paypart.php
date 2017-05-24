<?php
include("connect.inc");

$data=authenticated($cid);

if(isset($data))
{
	if($CMDSubmit)
        {
		$is_error=0;
		if($mode=="")
		{
			$is_error++;
			$smarty->assign("CHECK_MODE","Y");
		}
		elseif($mode != '')
		{
			if($curtype == '')
			{
				$is_error++;
				$smarty->assign("CHECK_CURTYPE","Y");
			}
		}
		if(trim($amount)=='')
		{
			$is_error++;
			$smarty->assign("CHECK_AMOUNT","Y");
		}
		if ($mode=="CHEQUE" || $mode=="DD")
		{
			if(trim($cdnum)=='')
			{
				$is_error++;
				$smarty->assign("CHECK_CDNUM","Y");
			}
			if($cd_day=='' || $cd_month=='' || $cd_year=='')
			{
				$is_error++;
				$smarty->assign("CHECK_CDDATE","Y");
			}
			if(trim($cd_city)=='')
			{
				$is_error++;
				$smarty->assign("CHECK_CDCITY","Y");
			}
			if($Bank=='')
			{
				$is_error++;
				$smarty->assign("CHECK_BANK","Y");
			}
			elseif($Bank=="Other")
			{
				if(trim($obank)=='')
				{
					$is_error++;
					$smarty->assign("CHECK_OBANK","Y");
				}
			}
			$cd_date=$cd_year."-".$cd_month."-".$cd_day;
		}

		if($type==0)
		{
			$type='RS';
		}
		elseif($type==1)
		{
			$type='DOL';
		}

		if($val=="paypart")
		{

			if($amount>=$dueamount_new)
			{
				$dueamt=0;
			}
			elseif($amount<$dueamount_new)
			{
				$dueamt=$dueamount_new-$amount;
			}
			if($dueamt >0)
			{
				if($due_day=='' || $due_month=='' || $due_year=='')
				{
					$is_error++;
						$smarty->assign("CHECK_DUEDATE","Y");
					}
					$due_date=$due_year."-".$due_month."-".$due_day;
			       	}
			}

		if($is_error==0)
		{

			if($val=="paypart")
			{
				$sql_i="INSERT INTO billing.PAYMENT_DETAIL(PROFILEID,STATUS,MODE,TYPE,BILLID,AMOUNT,CD_NUM,CD_CITY,BANK,OBANK,ENTRY_DT,ENTRYBY) VALUES('$pid','DONE','$mode','$type','$billid','$amount','$cdnum','$cd_city','$Bank','$obank',now(),'$user')";
				$result_i=mysql_query_decide($sql_i) or die(mysql_error_js());
				
				$receiptid=mysql_insert_id_js();
				$sql="UPDATE billing.PURCHASES SET DUEAMOUNT='$dueamt',DUEDATE='$due_date' WHERE BILLID='$billid'";
				mysql_query_decide($sql) or die(mysql_error_js());

			}
			elseif($val=="refund")
			{
				$dueamt=0;
 				$sql_i="INSERT INTO billing.PAYMENT_DETAIL(PROFILEID,MODE,TYPE,BILLID,AMOUNT,CD_NUM,STATUS,CD_CITY,BANK,OBANK,ENTRY_DT,ENTRYBY) VALUES('$pid','$mode','$type','$billid','$amount','$cdnum','REFUND','$cd_city','$Bank','$obank',now(),'$user')";
				$result_i=mysql_query_decide($sql_i) or die(mysql_error_js());
				$receiptid=mysql_insert_id_js();
				$sql="UPDATE billing.PURCHASES SET ENTRY_DT='$entry_dt',DUEAMOUNT='$dueamt' WHERE BILLID='$billid'";
				mysql_query_decide($sql) or die(mysql_error_js());
			}
		$sql="SELECT PACKAGE,COMPID,PACKID from billing.SERVICES where SERVICEID='$serviceid'";
                $result=mysql_query_decide($sql);
                $myrow=mysql_fetch_array($result);
                if($myrow['PACKAGE']!="Y")
                {
                        $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
                        $myrow1=mysql_fetch_array(mysql_query_decide($sql));
                                                                                                 
                        $sql="INSERT into billing.SERVICE_STATUS (RECEIPTID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT) values ('$receiptid','$serviceid','$myrow[COMPID]','Y',now(),'$user',ADDDATE('$entrydt', INTERVAL $myrow1[DURATION] MONTH))";
                        mysql_query_decide($sql);
                }
		if($myrow['PACKAGE']=="Y")
                {
                        $packid=$myrow['PACKID'];
                        $sql="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";                        $result=mysql_query_decide($sql);
                        while($myrow1=mysql_fetch_array($result))
                        {
                                $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow1[COMPID]'";
                                $myrow2=mysql_fetch_array(mysql_query_decide($sql));
                                $sql="INSERT into billing.SERVICE_STATUS (RECEIPTID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT)values ('$receiptid','$serviceid','$myrow1[COMPID]','Y',now(),'$user',ADDDATE('$entrydt', INTERVAL $myrow2[DURATION] MONTH))";
                                mysql_query_decide($sql);
			}
		}
			$smarty->assign("cid",$cid);
			$smarty->assign("pid",$pid);
			$smarty->assign("val",$val);
			$smarty->assign("user",$user);
			$smarty->assign("username",$username);
			$smarty->assign("mtype",$mtype);
			$smarty->assign("duration",$duration);
			$smarty->assign("serviceid",$serviceid);
			$smarty->assign("COMMENT",$comment);
			$smarty->assign("MODE",$mode);
			$smarty->assign("CURTYPE",$curtype);
			$smarty->assign("AMOUNT",$amount);
			$smarty->assign("DUE_DAY",$due_day);	
			$smarty->assign("DUE_MONTH",$due_month);	
			$smarty->assign("DUE_YEAR",$due_year);	
			$smarty->assign("DUE_DATE",$due_date);
			$smarty->assign("CDNUM",$cdnum);
			$smarty->assign("CD_DAY",$cd_day);	
			$smarty->assign("CD_MONTH",$cd_month);	
			$smarty->assign("CD_YEAR",$cd_year);	
			$smarty->assign("CD_DATE",$cd_date);
			$smarty->assign("CD_CITY",$cd_city);
			$smarty->assign("OVERSEAS",$overseas);	
			$smarty->assign("SEPARATEDS",$separateds);	
			$smarty->assign("BANK",$Bank);	
			$smarty->assign("OBANK",$obank);
			$smarty->assign("billid",$billid);
			
			$msg = "Part Payment of $username has been updated<br><br>";
                        $msg .= "<a href=\"searchpage.php?$link_msg\">";
                        $msg .= "Continue &gt;&gt;</a>";
                        $smarty->assign("name",$user);
                        $smarty->assign("username",$username);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");

		}
		else
		{
			$smarty->assign("cid",$cid);
			$smarty->assign("pid",$pid);
			$smarty->assign("val",$val);
			$smarty->assign("user",$user);
			$smarty->assign("username",$username);
			$smarty->assign("mtype",$mtype);
			$smarty->assign("duration",$duration);
			$smarty->assign("serviceid",$serviceid);
			$smarty->assign("COMMENT",$comment);
			$smarty->assign("MODE",$mode);	
			$smarty->assign("CURTYPE",$curtype);	
			$smarty->assign("AMOUNT",$amount);	
			$smarty->assign("DUE_DAY",$due_day);	
			$smarty->assign("DUE_MONTH",$due_month);	
			$smarty->assign("DUE_YEAR",$due_year);	
			$smarty->assign("CDNUM",$cdnum);	
			$smarty->assign("CD_DAY",$cd_day);	
			$smarty->assign("CD_MONTH",$cd_month);	
			$smarty->assign("CD_YEAR",$cd_year);	
			$smarty->assign("CD_CITY",$cd_city);	
			$smarty->assign("OVERSEAS",$overseas);	
			$smarty->assign("SEPARATEDS",$separateds);	
			$smarty->assign("OBANK",$obank);
			$smarty->assign("billid",$billid);
			$smarty->assign("link_msg",$link_msg);
			$smarty->assign("PAIDAMOUNT",$paidamount);
                        $smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
                        $smarty->assign("PRICENEW",$pricenew);
                        $smarty->assign("REF_AMOUNT",$ref_amount);
                        $smarty->assign("ENTRYDT",$entrydt);

			$smarty->assign("bank",create_dd($Bank,"Bank"));
			$smarty->display("refund_paypart.htm");
		}	
	}
        else
        {
	
		$smarty->assign("REF_AMOUNT",$ref_amount);
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("val",$val);
		$smarty->assign("user",$user);
		$smarty->assign("username",$username);
		$smarty->assign("mtype",$mtype);
		$smarty->assign("duration",$duration);
		$smarty->assign("serviceid",$serviceid);
		$smarty->assign("billid",$billid);
		$smarty->assign("PAIDAMOUNT",$paidamount);
		$smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
		$smarty->assign("PRICENEW",$pricenew);
		$smarty->assign("ENTRYDT",$entrydt);
		$smarty->assign("bank",create_dd($Bank,"Bank"));
		$smarty->assign("link_msg",$link_msg);
		$smarty->display("refund_paypart.htm");
        }
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
