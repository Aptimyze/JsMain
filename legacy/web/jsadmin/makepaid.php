<?php
include("connect.inc");
if(authenticated($cid))
{
	if($Done)
	{
		$tdate=date("Y-m-d");
		if($duration==0)
			$duration=0;
		$expirydate= strftime("%Y-%m-%d",JSstrToTime("$tdate + $duration months"));
		if($mtype=='F')
		{
			$mname="Full Membership";
		}
		if($mtype=='V')
		{
			$mname="Value Added Membership";
		}
		$error=0;
		if($mtype=="")
		{
			$error++;
			$smarty->assign("CHECK_MTYPE","Y");
		}
		if($duration=="")
		{
			$error++;
			$smarty->assign("CHECK_DURATION","Y");
		}
	
		if(trim($discount_new) == '' || floor($discount_new)==0)
                {
                        $reason_new='';
                        $discount_type='';
                }
                elseif(trim($discount_new) != '' && floor($discount_new)!=0)
                {
                        if($reason_new=='')
                        {
                                $is_error++;
                                $smarty->assign("CHECK_REASON","Y");
                        }
                        if($discount_type=='')
                        {
                                $is_error++;
                                $smarty->assign("CHECK_DISCOUNT_TYPE","Y");
                        }
                                                                                                 
                }
                                                                                                 
	
		if($error>0)
		{
//			echo $reason."kush".$discount_new;
			$smarty->assign("link_msg",$link_msg);
	                $smarty->assign("username",$username);
        	        $smarty->assign("EMAIL",$email);
			$smarty->assign("MEMBERSHIP",$membership);
			$smarty->assign("EXP_DT",$exp_dt);
			$smarty->assign("mtype",$mtype);
			$smarty->assign("duration",$duration);
                	$smarty->assign("cid",$cid);
                	$smarty->assign("pid",$pid);
	                $smarty->assign("DISCOUNT_NEW",$discount_new);
	                $smarty->assign("DISCOUNT_TYPE",$discount_type);
	                $smarty->assign("REASON_NEW",$reason_new);
	                $smarty->assign("user",$user);
        	        $smarty->display("make_paid.tpl");			

		}
		else
		{
	                if ($mtype=="F")
	                        $mtype_insert="F";
                        elseif($mtype=="V")
                                $mtype_insert="B,F";
	
			$sql="UPDATE newjs.JPROFILE set SUBSCRIPTION='$mtype_insert' where PROFILEID='$pid'";
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());

			if ($mtype=='F' && $duration=='3')
				$serviceid="S1";	
			if ($mtype=='F' && $duration=='6')
				$serviceid="S2";	
			if ($mtype=='F' && $duration=='12')
				$serviceid="S3";	
			if ($mtype=='V' && $duration=='3')
				$serviceid="S4";	
			if ($mtype=='V' && $duration=='6')
				$serviceid="S5";	
			if ($mtype=='V' && $duration=='12')
				$serviceid="S6";
			$sql="SELECT PRICE_RS from billing.SERVICES where SERVICEID ='$serviceid'";
			$myrow=mysql_fetch_array(mysql_query_decide($sql));
			$pricenew=$myrow['PRICE_RS'];
			if($membership !='')
			{
				$sql="SELECT PRICE_RS from billing.SERVICES where NAME ='$membership'";
				$myrow=mysql_fetch_array(mysql_query_decide($sql));
				$priceold=$myrow['PRICE_RS'];
			}
			
			$sql= "SELECT BILLID,DISCOUNT,DUEAMOUNT from billing.PURCHASES where PROFILEID='$pid' order by BILLID desc";
			$myrow=mysql_fetch_array(mysql_query_decide($sql));
			
			if(count($myrow)>0)
			{
				$billid=$myrow['BILLID'];
				$discount=$myrow['DISCOUNT'];
				$old_dew=$myrow['DUEAMOUNT'];
			}
			
			$sql="SELECT sum(AMOUNT) as AMOUNT, STATUS from billing.PAYMENT_DETAIL where BILLID='$billid' group by STATUS";
			$result=mysql_query_decide($sql);
			$totalpaid=0;
			$totalrefund=0;
			$totalbounce=0;
			while($myrow_1=mysql_fetch_array($result))
			{
				if($myrow_1['STATUS']=='DONE')
					$totalpaid += $myrow_1['AMOUNT'];
				elseif($myrow_1['STATUS']=='REFUND')
					$totalrefund += $myrow_1['AMOUNT'];
				elseif($myrow_1['STATUS']=='BOUNCE')
					$totalbounce += $myrow_1['AMOUNT'];
			}
			$paidamount=$totalpaid-$totalrefund-$totalbounce; 
			$dueamount_new=$pricenew- $paidamount-$discount_new;
			if($dueamount_new>=0)
				$val="paypart";
			else
			{
				$val="refund";
				$dueamount_new=0;
			}

			
				
			
			$sql="UPDATE billing.PURCHASES set STATUS='CANCEL' where BILLID='$billid'";	
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			
			$sql="INSERT into billing.PURCHASES(PROFILEID,SERVICEID,USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,WALKIN,CENTER,DISCOUNT,DISCOUNT_TYPE,DISCOUNT_REASON,ENTRYBY,DUEAMOUNT,DUEDATE,ENTRY_DT,STATUS) SELECT PROFILEID,'$serviceid',USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,WALKIN,CENTER,'$discount_new','$discount_type','$reason_new','$user','$dueamount_new',DUEDATE,now(),'DONE' from billing.PURCHASES where BILLID=$billid";			
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$billid_new=mysql_insert_id_js();
		
			$sql="UPDATE billing.PAYMENT_DETAIL set STATUS='CANCEL' where BILLID='$billid'";
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		
			$sql="DELETE billing.SERVICE_STATUS.* from billing.SERVICE_STATUS,billing.PAYMENT_DETAIL where BILLID='$billid' and SERVICE_STATUS.BILLID=PAYMENT_DETAIL.BILLID";	
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	
			$sql="INSERT into billing.PAYMENT_DETAIL (PROFILEID,BILLID,MODE,TYPE,AMOUNT,REASON,STATUS,ENTRY_DT,ENTRYBY) SELECT PROFILEID,'$billid_new','CASH','RS','$paidamount','Adjusted against Billid $billid','DONE',now(),'$user' from billing.PAYMENT_DETAIL where BILLID='$billid' limit 1 ";
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$receiptid_new=mysql_insert_id_js();
			
			$sql="SELECT PACKAGE,COMPID,PACKID from billing.SERVICES where SERVICEID='$serviceid'";
                $result=mysql_query_decide($sql);
                $myrow=mysql_fetch_array($result);
                if($myrow['PACKAGE']!="Y")
                {
                        $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
                        $myrow1=mysql_fetch_array(mysql_query_decide($sql));
                                                                                                 
                        $sql="INSERT into billing.SERVICE_STATUS (BILLID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT) values ('$receiptid_new','$serviceid','$myrow[COMPID]','Y',now(),'$user',ADDDATE(now(), INTERVAL $myrow1[DURATION] MONTH))";
                        mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                }
 if($myrow['PACKAGE']=="Y")
                {
                        $packid=$myrow['PACKID'];
                        $sql="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";                        $result=mysql_query_decide($sql);
                        while($myrow1=mysql_fetch_array($result))
                        {
                                $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow1[COMPID]'";
                                $myrow2=mysql_fetch_array(mysql_query_decide($sql));
                                $sql="INSERT into billing.SERVICE_STATUS (BILLID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT)values ('$receiptid_new','$serviceid','$myrow1[COMPID]','Y',now(),'$user',ADDDATE(now(), INTERVAL $myrow2[DURATION] MONTH))";
                                mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
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
			$smarty->assign("billid",$billid_new);
			$smarty->assign("link_msg",$link_msg);
			$smarty->assign("PRICENEW",$pricenew);
			$smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
			$smarty->assign("PAIDAMOUNT",$paidamount);
			$smarty->assign("ENTRYDT",$entrydt);
		
			$msg1="User $username has been changed to $mname for $duration months";	
			if($val=="paypart")
				$msg="Click to enter Part Payment details";
			elseif($val=="refund")
				$msg="Click to enter Refund details";
		        $smarty->assign("MSG1",$msg1);
		        $smarty->assign("MSG",$msg);

			$smarty->display("makepaid_link.htm");
			
		}

	}
	else
	{       
		$link_msg="user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&username=$username&email=$email&pid=$pid&PAGE=$PAGE&grp_no=$grp_no";
		$sql="SELECT USERNAME, EMAIL from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result);
		$username=$myrow['USERNAME'];
		$email=$myrow['EMAIL'];
		$sql="SELECT SERVICES.NAME as SERVICE, SERVICE_STATUS.EXPIRY_DT as EXP_DT, SERVICE_STATUS.BILLID as RECEIPTID FROM billing.SERVICES, billing.PURCHASES, billing.SERVICE_STATUS, billing.PAYMENT_DETAIL WHERE PURCHASES.BILLID = PAYMENT_DETAIL.BILLID AND PAYMENT_DETAIL.BILLID = SERVICE_STATUS.BILLID AND PURCHASES.SERVICEID = SERVICES.SERVICEID AND PURCHASES.PROFILEID = '$pid' order by RECEIPTID desc";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$myrow=mysql_fetch_array($result);
			$smarty->assign("MEMBERSHIP",$myrow['SERVICE']);	
			$smarty->assign("EXP_DT",$myrow['EXP_DT']);	
		}
		else
		{
			$smarty->assign("MEMBERSHIP","None");	
			$smarty->assign("EXP_DT","None");	
		}
		
		$sql="SELECT DISCOUNT,BILLID from billing.PURCHASES where PROFILEID='$pid' order by BILLID desc";			
		$myrow=mysql_fetch_array(mysql_query_decide($sql));
		$discount_new=$myrow['DISCOUNT'];
		$smarty->assign("DISCOUNT_NEW",$discount_new);
		$smarty->assign("link_msg",$link_msg);
		$smarty->assign("username",$username);
		$smarty->assign("EMAIL",$email);	
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("user",$user);
		$smarty->display("make_paid.tpl");
	}
}
else
{
	$msg="Your session has been timed out";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}                                                                                                 

?>
