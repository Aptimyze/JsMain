<?php
include("../jsadmin/connect.inc");
if($_SERVER["SERVER_ADDR"]=="192.168.2.220")
{
        $smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates";
        $smarty->compile_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates_c";
}
$ip=FetchClientIP();
if(authenticated($cid))
{
	$entryby=getuser($cid);
	if($status == "UPGRADE")
	{
		// Checking if user upgraded in previous week
		$curdate = date("Y-m-d",time());
		$sql = "Select * from billing.PAYMENT_DETAIL where STATUS = 'ADJUST' AND PROFILEID ='$pid' AND ENTRY_DT >= DATE_SUB('$curdate', INTERVAL 7 DAY) ORDER BY RECEIPTID desc";
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		if(mysql_num_rows($result) >= 1)	
		{
			$myrow = mysql_fetch_array($result);
			$dt = explode("-",$myrow["ENTRY_DT"]);
			$date_str = my_format_date($dt[2],$dt[1],$dt[0]);
			$msg = "This profile has already been upgraded on $date_str.";
			$msg .= "<br>Next upgradation allowed after a week from last date of upgradation";
			$msg .= "<br><a href=\"search_user.php?cid=$cid\">";
			$msg .= "Click here to go back</a>";	
		        $smarty->assign("MSG",$msg);
		        $smarty->display("jsadmin_msg.tpl");
			exit;
		}
	}	
	if($Done)
	{

		$error=0;
		$mtype=$service_type.$duration_sel;
		//$service_change = 1;
		// Check if user has changed services while upgrading
		if($status=="UPGRADE")
		
		{	
			$service_change = 1;
			$sql = "Select SERVICEID, ADDON_SERVICEID from billing.PURCHASES where PROFILEID = '$pid'";
			if($status=='UPGRADE')
                        	$sql.=" and BILLID='$billid' ";
	        	else
				$sql.=" ORDER BY BILLID desc";
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow = mysql_fetch_array($result);
			if($myrow['SERVICEID'] == $mtype)
			{
				if( $myrow['ADDON_SERVICEID'] == '' && (!isset($addon_services)))
					$service_change = 0;					
				if($myrow['ADDON_SERVICEID'] != '' && (isset($addon_services)))	
				{
					$sql = "Select c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$mtype'";
					$result_services = mysql_query_decide($sql) or die(mysql_error_js());
					$myrow_services = mysql_fetch_array($result_services);
					$duration = $myrow_services['DURATION'];
					
					for($i=0;$i<count($addon_services);$i++)
						$serviceid_new_ar[$i] = $addon_services[$i].$duration;
					$serviceid_old_ar = explode(",",$myrow['ADDON_SERVICEID']);
					sort($serviceid_old_ar);
					sort($serviceid_new_ar);	
					$serviceid_old_str = implode(",",$serviceid_old_ar);
					$serviceid_new_str = implode(",",$serviceid_new_ar);
					if($serviceid_old_str == $serviceid_new_str)
						$service_change = 0;
				}
			}
			
			if($service_change == 0)	
			{
				$error++;
				$msg = "No change in existing services";
			}
		}
		
	/*	if($mtype=="")
		{
			$error++;
			$smarty->assign("CHECK_MTYPE","Y");
		}*/
		
		if($service_type=="")
                {
                        $error++;
                        $smarty->assign("CHECK_SERVICE_TYPE","Y");
                }	
		if($duration_sel=="")
                {
                        $error++;
                        $smarty->assign("CHECK_DURATION_SEL","Y");
                }

		elseif($service_type!="" && $duration_sel!="")
		{
			$serviceid = $mtype;	
			$sql = "Select a.NAME as NAME, c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where SERVICEID = '$mtype' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID";
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow = mysql_fetch_array($result);
			$duration = $myrow["DURATION"];	
			$mname = $myrow["NAME"];
		}
		if($duration=="")
		{
			$error++;
			$smarty->assign("CHECK_DURATION","Y");
		}

                $tdate=date("Y-m-d");
                $expirydate= strftime("%Y-%m-%d",JSstrToTime("$tdate + $duration months"));

		if ($status=="UPGRADE")
		{
			if($exp=="")
			{
				$error++;
				$smarty->assign("CHECK_EXP","Y");
			}
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

	                /*
			Code commented by Aman Sharma for changing the selection process

			$sql = "Select NAME,SERVICEID from billing.SERVICES where ID > 6 AND PACKAGE = 'Y' AND ADDON = 'N'";
        	        $result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                	while($myrow = mysql_fetch_array($result))
                	{
                        	$main_services[] = array("NAME"=>$myrow["NAME"],
                                	                "SERVICEID"=>$myrow["SERVICEID"]);
                	}	
	                $smarty->assign("MAIN_SERVICES",$main_services);*/
			$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE!='N' and PRIVILAGE like '%BU%' ORDER BY USERNAME";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$employee[]=$row['USERNAME'];
			}
			$smarty->assign("walkin_arr",$employee);
			$smarty->assign("WALKIN",$walkin);
			$smarty->assign("link_msg",$link_msg);
			$smarty->assign("username",$username);
			$smarty->assign("EMAIL",$email);
			$smarty->assign("MEMBERSHIP",$membership);
			$smarty->assign("SERVICEID_OLD",$serviceid_old);
			$smarty->assign("EXP_DT",$exp_dt);
			$smarty->assign("mtype",$mtype);
			$smarty->assign("DURATION_SEL",$duration_sel);
                        $smarty->assign("SERVICE_TYPE",$service_type);
			$smarty->assign("exp",$exp);
			$smarty->assign("duration",$duration);
                	$smarty->assign("cid",$cid);
                	$smarty->assign("pid",$pid);
			$smarty->assign("BILLID",$billid);
	                $smarty->assign("DISCOUNT_NEW",$discount_new);
	                $smarty->assign("DISCOUNT_TYPE",$discount_type);
	                $smarty->assign("REASON_NEW",$reason_new);
	                $smarty->assign("user",$user);
	                $smarty->assign("SOURCE",$source);
	                $smarty->assign("STATUS",$status);
			$smarty->assign("ADDON_SERVICES_NAMES",$addon_services_names);
			$smarty->assign("ADDON_SERVICES_ID",$addon_services_id_old);
			$smarty->assign("MSG",$msg);
			$smarty->assign("DEGRADE",$degrade);
        	        $smarty->display("make_paid.htm");			

		}
		else
		{
	                $sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$mtype'";

	                $result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
        	        while($myrow = mysql_fetch_array($result))
			{
	                $subscription_ar[] = $myrow["RIGHTS"];
			}	
	                if(is_array($addon_services))
			{
	                        for($i=0;$i<count($addon_services);$i++)
				{
					$addon_serviceid_ar[$i] = $addon_services[$i].$duration;
        	                        $addon_services[$i] = "'".$addon_services[$i].$duration."'";
				}
				$addon_serviceid = implode(",",$addon_serviceid_ar);
                	        $addon_services_str = implode(",",$addon_services);
				
				$sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.COMPONENTS c where a.PACKAGE = 'N' AND a.ADDON = 'Y' AND a.COMPID = c.COMPID AND a.SERVICEID in ($addon_services_str)";
	                        $result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
        	                while($myrow = mysql_fetch_array($result))
                	                $subscription_ar[] = $myrow["RIGHTS"];
                	}
			if($degrade=='Y')
			{
				$old_ser_price=get_services_amount($serviceid_old,$addon_services_id_old);
				$new_ser_price=get_services_amount($mtype,$addon_services_str);
                                $price_diff=$new_ser_price-$old_ser_price;
		        	if($price_diff>0)
				{
					$err_msg="Sorry you can't upgrade this profile as it has some due amount";
					$smarty->assign("MSG",$err_msg);
				        $smarty->display("jsadmin_msg.tpl");
					die();
				}
			}

	                if(is_array($subscription_ar))
        	                $subscription = implode(",",$subscription_ar);
				
			if($status=="UPGRADE")
			{
                		$sql="UPDATE newjs.JPROFILE set SUBSCRIPTION='$subscription' where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			}

/*			$sql="SELECT SUM(PRICE_RS) as PRICE from billing.SERVICES where SERVICEID ='$mtype'";
			if(is_array($addon_services))
			{
				$sql .= " OR SERVICEID IN ($addon_services_str)";
			}
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			$pricenew=$myrow['PRICE'];
			$pricenew += round((($pricenew - $discount_new) * $TAX_RATE)/100);

*/

/*			if($membership !='')
			{
				$sql="SELECT PRICE_RS from billing.SERVICES where NAME ='$membership'";
				$myrow=mysql_fetch_array(mysql_query_decide($sql));
				$priceold=$myrow['PRICE_RS'];
			}
*/
			
			$sql= "SELECT BILLID,DISCOUNT,DUEAMOUNT from billing.PURCHASES where PROFILEID='$pid' ";
			if($status=='UPGRADE')
                    	    $sql.=" and BILLID='$billid' ";
	                else
				$sql.=" order by BILLID desc";
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			
			if(count($myrow)>0)
			{
				$billid=$myrow['BILLID'];
				$discount=$myrow['DISCOUNT'];
				$old_dew=$myrow['DUEAMOUNT'];
			}
			
			$sql="SELECT sum(AMOUNT) as AMOUNT, STATUS,TYPE from billing.PAYMENT_DETAIL where BILLID='$billid' group by STATUS";
			$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$totalpaid=0;
			$totalpaid_done=0;
			$totalrefund=0;
			$totalbounce=0;
			$totalpaid_adjust=0;
			while($myrow_1=mysql_fetch_array($result))
			{
				if($myrow_1['STATUS']=='DONE')
					$totalpaid_done += $myrow_1['AMOUNT'];
				elseif($myrow_1['STATUS']=='REFUND')
					$totalrefund += $myrow_1['AMOUNT'];
//				elseif($myrow_1['STATUS']=='BOUNCE')
//					$totalbounce += $myrow_1['AMOUNT'];
				elseif($myrow_1['STATUS']=='ADJUST' )
					$totalpaid_adjust += $myrow_1['AMOUNT']; 
				elseif($myrow_1['STATUS']=='WRITE_OFF' )
					$totalpaid_wrt_off += $myrow_1['AMOUNT']; 
				$cur_type= $myrow_1['TYPE'];
			}

			if($cur_type == "DOL")
				$sql = "Select SUM(PRICE_DOL)";
			else	
                                $sql = "Select SUM(PRICE_RS)";
			$sql .= " as PRICE from billing.SERVICES where SERVICEID ='$mtype'";
                        if(is_array($addon_services))
                        {
                                $sql .= " OR SERVICEID IN ($addon_services_str)";
                        }
                        $result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                        $myrow=mysql_fetch_array($result);
                        $pricenew=$myrow['PRICE'];
			$pricenew=$pricenew-$discount_new;
/*
	Commented for subtracting Discount directly from pricenew			
		
			if($cur_type == "RS")
	                        $pricenew = floor(($pricenew + round(((($pricenew - $discount_new) * $TAX_RATE)/100),2)));
			if($status=="RENEW")
			{
				$dueamount_new=($pricenew-$discount_new)+$old_dew;
				$paidamount=0;
				
			}
			else
			{
				$paidamount=$totalpaid_done+$totapaid_adjust-$totalrefund; 
				$dueamount_new=$pricenew- $paidamount-$discount_new;
			}*/

			if($cur_type == "RS")
                                $pricenew = floor($pricenew + round((($pricenew * $TAX_RATE)/100),2));
                        if($status=="RENEW")
                        {
                                $dueamount_new=$pricenew+$old_dew;
                                $paidamount=0;
                                                                                                    
                        }
                        else
                        {
                                $paidamount=$totalpaid_done+$totalpaid_adjust+$totalpaid_wrt_off-$totalrefund;
                                $dueamount_new=$pricenew- $paidamount;
                        }

			if($dueamount_new>=0)
				$val="paypart";
			else
			{
				$val="refund";
				$dueamount_new=0;
			}

			if($cur_type == "RS")
				$tax_rate_apply = $TAX_RATE;
			elseif($cur_type == "DOL")
				$tax_rate_apply = "";

			if($walkin=="OFFLINE" || $walkin=="ARAMEX" || $walkin=="ONLINE")
				$walkin_center="HO";
			else
				$walkin_center=getcenter_for_walkin($walkin);
			
			$center=getcenter_for_walkin($entryby);	
			$sql="INSERT into billing.PURCHASES(PROFILEID,SERVICEID,USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,WALKIN,CENTER,ENTRYBY,DISCOUNT,DISCOUNT_TYPE,DISCOUNT_REASON,DUEAMOUNT,DUEDATE,ENTRY_DT,STATUS,SERVEFOR,ADDON_SERVICEID,TAX_RATE,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD) SELECT PROFILEID,'$mtype',USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,'$walkin','$walkin_center','$entryby','$discount_new','$discount_type','$reason_new','$dueamount_new'";
			if($status=="UPGRADE")
			{
				$sql .= ",DUEDATE,now(),'DONE','$subscription','$addon_serviceid','$tax_rate_apply',now(),'$center','$ip' from billing.PURCHASES where BILLID=$billid";			
			}
			elseif($status=="RENEW")
			{
				$sql .= ",now(),now(),'DONE','$subscription','$addon_serviceid', '$tax_rate_apply',now(),'$center','$ip' from billing.PURCHASES where BILLID=$billid";			
			}
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$billid_new=mysql_insert_id_js();
			
			if($status=="UPGRADE")
			{
				if($exp=="prev")
				{
					$sql="SELECT ACTIVATED_ON,BILLID FROM billing.SERVICE_STATUS s WHERE PROFILEID = '$pid'  and BILLID='$billid' ORDER by BILLID desc";
					$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
					$myrow=mysql_fetch_array($result);	
					$exp_start_dt_new=$myrow["ACTIVATED_ON"];
				}
				elseif($exp=="today")
				{
					$exp_start_dt_new=date('Y-m-d');
				}
				
			}	
			elseif($status=="RENEW")
			{
				$today = date('Y-m-d');
				if((strcmp($today,$exp_dt)>0))
					$exp_start_dt_new = $today;
				else
					$exp_start_dt_new = $exp_dt;
			}
			
			
			if($status != "RENEW")	
			{
				$sql="INSERT into billing.PAYMENT_DETAIL (PROFILEID,BILLID,MODE,TYPE,AMOUNT,REASON,STATUS,ENTRY_DT,ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD) SELECT PROFILEID,'$billid_new','OTHER','$cur_type','$paidamount','Adjusted against Billid $billid','ADJUST',now(),'$entryby',DEPOSIT_DT,DEPOSIT_BRANCH,'$ip' from billing.PAYMENT_DETAIL where BILLID='$billid' limit 1 ";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
				$receiptid_new=mysql_insert_id_js();
			}
			
			$sql="SELECT PACKAGE,COMPID,PACKID from billing.SERVICES where SERVICEID='$serviceid'";
	                $result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
        	        $myrow=mysql_fetch_array($result);
		
	                if($myrow['PACKAGE']!="Y")
        	        {
                	        $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
				$result1 = mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        	$myrow1=mysql_fetch_array($result1);
                        
				if($status=="UPGRADE") 
				{
					$sql="INSERT into billing.SERVICE_STATUS (BILLID, PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, EXPIRY_DT,ACTIVATED_BY) values ('$billid__new','$pid','$serviceid','$myrow[COMPID]','Y',now(),ADDDATE('$exp_start_dt_new', INTERVAL $myrow1[DURATION] MONTH),'$entryby')";
				}
				elseif($status=="RENEW")
				{
					$sql="INSERT into billing.SERVICE_STATUS (BILLID, PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATE_ON, EXPIRY_DT,ACTIVATED_BY) values ('$billid__new','$pid','$serviceid','$myrow[COMPID]','N','$exp_start_dt_new',ADDDATE('$exp_start_dt_new', INTERVAL $myrow1[DURATION] MONTH),'$entryby')";
				}
        	                mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                	}
			elseif($myrow['PACKAGE']=="Y")
        	        {
                	        $packid=$myrow['PACKID'];
                        	$sql="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	                        while($myrow1=mysql_fetch_array($result))
        	                {
                	                $comp_arr[]=$myrow1["COMPID"];
				}
				if(is_array($comp_arr))
					$comp_str=implode(",",$comp_arr);
				else
					$comp_str=$comp_arr;				
				//	$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow1[COMPID]'";
					$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$comp_arr[0]'";
					$result2 = mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        	        $myrow2=mysql_fetch_array($result2);
					if($status=="UPGRADE")
					{
	                                	$sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, EXPIRY_DT, ACTIVATED_BY)values ('$billid_new','$pid','$serviceid','$comp_str','Y',now(),ADDDATE('$exp_start_dt_new', INTERVAL $myrow2[DURATION] MONTH), '$entryby')";
					}
					elseif($status=="RENEW")
					{
	        	                        $sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATE_ON, EXPIRY_DT, ACTIVATED_BY)values ('$billid_new','$pid','$serviceid','$comp_str','N','$exp_start_dt_new',ADDDATE('$exp_start_dt_new', INTERVAL $myrow2[DURATION] MONTH), '$entryby')";
					}
					mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                        	
                	}
			if($source=="A")
			{
				$sql="UPDATE incentive.PAYMENT_COLLECT set BILLING='Y' where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}

		
			$smarty->assign("cid",$cid);
			$smarty->assign("pid",$pid);
			$smarty->assign("val",$val);
			$smarty->assign("user",$entryby);
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
			$smarty->assign("CURRENCY_TYPE",$cur_type);
		
			$msg1="User $username has been changed to $mname";	
			if($val=="paypart")
			{
				$msg="Click to enter Part Payment details to complete the billing process.";
				if($dueamount_new<=0)
					 $msg.="Please make an entry of zero amount even if you have given full discount";
			}
			elseif($val=="refund")
					$msg="<a href=search_user.php?user=$user&cid=$cid&criteria=uname>Continue</a>";
			//	$msg="Click to enter Refund details to complete the billing process.";

			//mail("shiv.narayan@jeevansathi.com, alok@jeevansathi.com","Upgrade/Renew Link Click","PROFILEID : $pid\nUSERNAME : $username\nSTATUS : $status\nOperator : $entryby\nTime : ".date("Y-m-d H:i:s"));

	        	$smarty->assign("MSG1",$msg1);
		        $smarty->assign("MSG",$msg);
			$smarty->assign("SOURCE",$source);
			$smarty->display("makepaid_link.htm");
			
		}

	}
	else
	{       
		$link_msg="user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&username=$username&email=$email&pid=$pid&PAGE=$PAGE&grp_no=$grp_no";
		$sql="SELECT USERNAME, EMAIL from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$username=$myrow['USERNAME'];
		$email=$myrow['EMAIL'];

		$sql = "SELECT a.BILLID, a.SERVICEID, a.ADDON_SERVICEID, b.EXPIRY_DT FROM billing.PURCHASES a, billing.SERVICE_STATUS b WHERE a.PROFILEID = '$pid'AND a.BILLID = b.BILLID ";
		if($status=='UPGRADE')
			$sql.=" and a.BILLID='$billid' ";
		else
			$sql.=" ORDER BY a.BILLID DESC ";	
		
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$myrow=mysql_fetch_array($result);

			$sql = "Select NAME FROM billing.SERVICES where SERVICEID = '$myrow[SERVICEID]' ";
			$result_main_service = mysql_query_decide($sql) or die(mysql_error_js());
			$myrow_main_service = mysql_fetch_array($result_main_service);
			$main_service_name = $myrow_main_service['NAME'];

			$smarty->assign("MEMBERSHIP",$main_service_name);	
			$smarty->assign("SERVICEID_OLD",$myrow["SERVICEID"]);
			$smarty->assign("EXP_DT",$myrow['EXPIRY_DT']);	

			
			if($myrow['ADDON_SERVICEID'])
			{
                                $addon_service_ar = explode(",",$myrow['ADDON_SERVICEID']);
                                for($i=0;$i<count($addon_service_ar);$i++)
                                        $addon_service_ar[$i]="'".$addon_service_ar[$i]."'";
                                $addon_service_str = implode(",",$addon_service_ar);

				$sql ="Select NAME FROM billing.SERVICES where SERVICEID IN ($addon_service_str)";	
				$result_name = mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($myrow_result = mysql_fetch_array($result_name))
					$services_name_ar[] = $myrow_result['NAME'];
				$services_names = implode(",",$services_name_ar); 
				
				$smarty->assign("ADDON_SERVICES_NAMES",$services_names);
				$smarty->assign("ADDON_SERVICES_ID",$addon_service_str);
			}
		}
		else
		{
			$smarty->assign("MEMBERSHIP","None");	
			$smarty->assign("EXP_DT","None");	
		}

		$sql = "Select NAME,SERVICEID from billing.SERVICES where ID > 6 AND PACKAGE = 'Y' AND ADDON = 'N'";
		$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($myrow = mysql_fetch_array($result))
		{
			$main_services[] = array("NAME"=>$myrow["NAME"],
						"SERVICEID"=>$myrow["SERVICEID"]);	
		}
		$smarty->assign("MAIN_SERVICES",$main_services);
		$sql="SELECT DISCOUNT,BILLID,WALKIN from billing.PURCHASES where PROFILEID='$pid' ";
		if($status=='UPGRADE')
                        $sql.=" AND BILLID='$billid' ";
                else
			$sql.= " order by BILLID desc";			
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$discount_new=$myrow['DISCOUNT'];
		$walkin=$myrow['WALKIN'];
		$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE like '%BU%'  and ACTIVE!='N' ORDER BY USERNAME";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$employee[]=$row['USERNAME'];
		}
                $smarty->assign("walkin_arr",$employee);
		$smarty->assign("WALKIN",$walkin);
		$smarty->assign("DISCOUNT_NEW",$discount_new);
		$smarty->assign("link_msg",$link_msg);
		$smarty->assign("username",$username);
		$smarty->assign("EMAIL",$email);	
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("user",$user);
		$smarty->assign("BILLID",$billid);
		$smarty->assign("SOURCE",$source);
		$smarty->assign("STATUS",$status);
		$smarty->assign("DEGRADE",$degrade);
		$smarty->display("make_paid.htm");
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
function get_services_amount($serid,$addonid)
{
	$sql = "Select SUM(PRICE_RS) as PRICE from billing.SERVICES where SERVICEID ='$serid'";
	if($addonid!="")
	{
		$sql .= " OR SERVICEID IN (".stripslashes($addonid).")";
	}
	$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	$myrow=mysql_fetch_array($result);
	$price=$myrow['PRICE'];
	return $price;
}
?>
