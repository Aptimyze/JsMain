<?php

/*********************************************************************************************
* FILE NAME     : add_display.php
* DESCRIPTION   : Script is used to give e-classified service additionally means 		  		   it converts member to e-value pack by charging only for the left period.	
* CREATION DATE : 22 july, 2005
* CREATED BY    : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/




include("../jsadmin/connect.inc");
if(authenticated($cid))
{
	$entryby=getuser($cid);
	// Checking if user has more than 30 days of expiry 
	$curdate = date("Y-m-d",time());
	$sql = "Select * from billing.SERVICE_STATUS where PROFILEID ='$pid' AND EXPIRY_DT >= DATE_ADD('$curdate', INTERVAL 30 DAY) ORDER BY BILLID desc";
	$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	if(mysql_num_rows($result)==0)	
	{
		$myrow = mysql_fetch_array($result);
		$dt = explode("-",$myrow["EXPIRY_DT"]);
		$date_str = my_format_date($dt[2],$dt[1],$dt[0]);
		$msg .= "<br>This facility is allowed only if expiry date is more than one month later from current date";
		$msg .= "<br><a href=\"search_user.php?cid=$cid\">";
		$msg .= "Click here to go back</a>";	
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
		exit;
	}
 	// Checking if user has any due amount.
	$sql= "SELECT BILLID,DISCOUNT,DUEAMOUNT from billing.PURCHASES where PROFILEID='$pid' order by BILLID desc";
	$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	$myrow=mysql_fetch_array($result);
	if(count($myrow)>0)
	{
		$billid=$myrow['BILLID'];
		$discount=$myrow['DISCOUNT'];
		$old_dew=$myrow['DUEAMOUNT'];
	}
	if($old_dew>0)
	{	
		$msg .= "<br>Sorry,the user can't avail it because there is some due amount";
	$msg .= "<br><a href=\"search_user.php?cid=$cid\">";
	$msg .= "Click here to go back</a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
	exit;

	}
		



	
	if($Done)
	{	

		$error=0;

		// Check if user has changed services while upgrading
	
		$service_change = 1;
		$sql = "Select SERVICEID, ADDON_SERVICEID from billing.PURCHASES where PROFILEID = '$pid' ORDER BY BILLID desc";
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow = mysql_fetch_array($result);
		$dur=$myrow["SERVICEID"];
		$sql = "Select c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$dur'";
		$result_services = mysql_query_decide($sql) or die(mysql_error_js());
		$myrow_services = mysql_fetch_array($result_services);
		$duration = $myrow_services['DURATION'];
				
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
                                                                                                 
	
		if($is_error>0)
		{
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
			$smarty->assign("duration_new",$duration_new);
                	$smarty->assign("cid",$cid);
                	$smarty->assign("pid",$pid);
	                $smarty->assign("DISCOUNT_NEW",$discount_new);
	                $smarty->assign("DISCOUNT_TYPE",$discount_type);
	                $smarty->assign("REASON_NEW",$reason_new);
	                $smarty->assign("user",$user);
	                $smarty->assign("SOURCE",$source);
	                $smarty->assign("STATUS",$status);
			$smarty->assign("ADDON_SERVICES_NAMES",$addon_services_names);
			$smarty->assign("ADDON_SERVICES_ID",$addon_services_id_old);
			$smarty->assign("MSG",$msg);
        	        $smarty->display("add_display.htm");			

		
		}
		else
		{
			$serviceid_new='C'.$duration_new;
			$sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$serviceid_new'";
                                                                                                                             
                        $result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                        while($myrow = mysql_fetch_array($result))
                        {
                	        $subscription_ar[] = $myrow["RIGHTS"];
                        }
			if($addon_services_id_old!='')
			{
				$addon_services_id_old_in= str_replace(",","','",$addon_services_id_old);
				$sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.COMPONENTS c where a.PACKAGE = 'N' AND a.ADDON = 'Y' AND a.COMPID = c.COMPID AND a.SERVICEID in ('$addon_services_id_old_in')";
				$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
				while($myrow = mysql_fetch_array($result))
				       $subscription_ar[] = $myrow["RIGHTS"];
			}
			if(is_array($subscription_ar))
                                $subscription = implode(",",$subscription_ar);
                                                                                                                             
                        $sql="UPDATE newjs.JPROFILE set SUBSCRIPTION='$subscription' where PROFILEID='$pid'";
         	        mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$sql= "SELECT BILLID,DISCOUNT,DUEAMOUNT from billing.PURCHASES where PROFILEID='$pid' order by BILLID desc";
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
//                              elseif($myrow_1['STATUS']=='BOUNCE')
//                                      $totalbounce += $myrow_1['AMOUNT'];
                                elseif($myrow_1['STATUS']=='ADJUST' )
                                        $totalpaid_adjust += $myrow_1['AMOUNT'];
                                $cur_type= $myrow_1['TYPE'];
                        }
			 $paidamount=$totalpaid_done+$totalpaid_adjust-$totalrefund;                                                                                                                             
	
		        $dueamount_new=$price_new-$discount_new;
			if($cur_type == "RS")
                                $dueamount_new = floor($dueamount_new + round((($dueamount_new * $TAX_RATE)/100),2));

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
                                                                             
			 $sql="INSERT into billing.PURCHASES(PROFILEID,SERVICEID,USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,WALKIN,CENTER,ENTRYBY,DISCOUNT,DISCOUNT_TYPE,DISCOUNT_REASON,DUEAMOUNT,DUEDATE,ENTRY_DT,STATUS,SERVEFOR,ADDON_SERVICEID,TAX_RATE) SELECT PROFILEID,'$serviceid_new',USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,WALKIN,CENTER,'$entryby','$discount_new','$discount_type','$reason_new','$dueamount_new',DUEDATE,now(),'DONE','$subscription','$addon_services_id_old','$tax_rate_apply' from billing.PURCHASES where BILLID=$billid";
                        mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                        $billid_new=mysql_insert_id_js();
			$sql="INSERT into billing.PAYMENT_DETAIL (PROFILEID,BILLID,MODE,TYPE,AMOUNT,REASON,STATUS,ENTRY_DT,ENTRYBY) SELECT PROFILEID,'$billid_new','CASH','$cur_type','$paidamount','Adjusted against Billid $billid','ADJUST',now(),'$entryby' from billing.PAYMENT_DETAIL where BILLID='$billid' limit 1 ";
                        mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$receiptid_new=mysql_insert_id_js();
			
			$sql="SELECT PACKAGE,COMPID,PACKID from billing.SERVICES where SERVICEID='$serviceid_new'";
                        $result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                        $myrow=mysql_fetch_array($result);
                                                                                                                             
                        if($myrow['PACKAGE']!="Y")
                        {
                                $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
                                $result1 = mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $myrow1=mysql_fetch_array($result1);
                                                                                                                             
				$sql="INSERT into billing.SERVICE_STATUS (BILLID, PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, EXPIRY_DT,ACTIVATED_BY) values ('$billid__new','$pid','$serviceid_new','$myrow[COMPID]','Y',now(),'$exp_dt','$entryby')";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                        }
                        elseif($myrow['PACKAGE']=="Y")
                        {
                                $packid=$myrow['PACKID'];
                                $sql="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";     $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                while($myrow1=mysql_fetch_array($result))
                                {
                                        $comp_arr[]=$myrow1["COMPID"];

				}
                                if(is_array($comp_arr))
                                        $comp_str=implode(",",$comp_arr);
                                else
                                        $comp_str=$comp_arr;
                                //      $sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow1[COMPID]'";
				$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$comp_arr[0]'";
				$result2 = mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$myrow2=mysql_fetch_array($result2);
				$sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, EXPIRY_DT, ACTIVATED_BY) values ('$billid_new','$pid','$serviceid_new','$comp_str','Y',now(),'$exp_dt', '$entryby')";
				mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			}
			$smarty->assign("cid",$cid);
                        $smarty->assign("pid",$pid);
                        $smarty->assign("val",$val);
                        $smarty->assign("user",$entryby);
                        $smarty->assign("username",$username);
                        $smarty->assign("duration",$duration);
                        $smarty->assign("serviceid",$serviceid_new);
                        $smarty->assign("billid",$billid_new);
                        $smarty->assign("link_msg",$link_msg);
                        $smarty->assign("PRICENEW",$pricenew);
                        $smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
                        $smarty->assign("PAIDAMOUNT",$paidamount);
                        $smarty->assign("ENTRYDT",$entrydt);
                        $smarty->assign("CURRENCY_TYPE",$cur_type);
                                                                                                                             
                        $msg1="User $username has been given e-classified services also";
                        if($val=="paypart")
                                $msg="Click to enter Part Payment details to complete the billing process.";
                        elseif($val=="refund")
                                $msg="Click to enter Refund details to complete the billing process.";
                                                                                                                             
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
//		$sql="SELECT SERVICES.NAME as SERVICE, SERVICE_STATUS.EXPIRY_DT as EXP_DT, SERVICE_STATUS.BILLID as BILLID FROM billing.SERVICES, billing.PURCHASES, billing.SERVICE_STATUS, billing.PAYMENT_DETAIL WHERE PURCHASES.BILLID = SERVICE_STATUS.BILLID AND PURCHASES.SERVICEID = SERVICES.SERVICEID AND PURCHASES.PROFILEID = '$pid' order by BILLID desc";

		$sql = "SELECT a.BILLID, a.SERVICEID, a.ADDON_SERVICEID, b.EXPIRY_DT FROM billing.PURCHASES a, billing.SERVICE_STATUS b WHERE a.PROFILEID = '$pid'AND a.BILLID = b.BILLID ORDER BY a.BILLID DESC ";	
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
			$mainservice_duration= getServiceDetails($myrow["SERVICEID"]);
			$newservice_ID= "D".$mainservice_duration['DURATION'];	
			
			$newservice= getServiceDetails($newservice_ID);
			$total_duration=$mainservice_duration['DURATION']*30;
			$curdate=date('Y-m-d');
			$days_left=getTimeDiff($curdate,$myrow['EXPIRY_DT']);
			$sql_type="select TYPE from billing.PAYMENT_DETAIL where PROFILEID='$pid' ORDER BY BILLID DESC";
			$result_type=mysql_query_decide($sql_type) or die("$sql_type".mysql_error_js());
			$row_type=mysql_fetch_array($result_type);
			if($row_type["TYPE"]=='RS')
			{
			$newservice_price=floor($days_left/$total_duration * $newservice['PRICE_RS']);
		        }
                        elseif($row_type["TYPE"]=='DOL')
			{
                        $newservice_price=floor($days_left/$total_duration * $newservice['PRICE_DOL']);
                        }
			$smarty->assign("duration_new",$mainservice_duration['DURATION']);
 			$smarty->assign("PRICE_TYPE",$row_type["TYPE"]);
			$smarty->assign("DISPLAY_SERVICE_PRICE",$newservice_price);
			if($myrow['ADDON_SERVICEID'])
			{
                                $addon_service_ar = explode(",",$myrow['ADDON_SERVICEID']);
//                                for($i=0;$i<count($addon_service_ar);$i++)
  //                                      $addon_service_ar[$i]=$addon_service_ar[$i];
                                $addon_service_str = implode(",",$addon_service_ar);
				$addon_service_str_query=implode("','",$addon_service_ar);
				$sql ="Select NAME FROM billing.SERVICES where SERVICEID IN ('$addon_service_str_query')";	
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
/*
		$sql = "Select NAME,SERVICEID from billing.SERVICES where ID > 6 AND PACKAGE = 'Y' AND ADDON = 'N'";
		$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($myrow = mysql_fetch_array($result))
		{
			$main_services[] = array("NAME"=>$myrow["NAME"],
						"SERVICEID"=>$myrow["SERVICEID"]);	
		}
		$smarty->assign("MAIN_SERVICES",$main_services);
*/
		$sql="SELECT DISCOUNT,BILLID from billing.PURCHASES where PROFILEID='$pid' order by BILLID desc";			
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$smarty->assign("DISCOUNT_NEW",$discount_new);
		$smarty->assign("link_msg",$link_msg);
		$smarty->assign("username",$username);
		$smarty->assign("EMAIL",$email);	
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("user",$user);
		$smarty->assign("SOURCE",$source);
		$smarty->assign("STATUS",$status);
		$smarty->display("add_display.htm");
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
/***********************************************************************
*    DESCRIPTION   :    Find the days difference between two dates
*    RETURNS       :    number of days, date2 > date1 ;
                        0,if date1= date2
                        -1, if date1 > date2 ***********************************************************************/
function getTimeDiff($date1,$date2)
{
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
                $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
                $timestamp_diff= $date2_timestamp - $date1_timestamp;
                $days_diff= $timestamp_diff / (24*60*60);
                return $days_diff;
        }
        elseif($date2 == $date1)
                return 0;
        else
                return -1;
}

function getServiceDetails($serviceid)
{
        $sql="SELECT * from billing.SERVICES where SERVICEID = '$serviceid'";
        $result=mysql_query_decide($sql) or logError($error_msg,$sql,"ShowErrTemplate",$announce_to_email);        $myrow=mysql_fetch_array($result);
                                                                                                    
        if($myrow["PACKAGE"]=="Y")
        {
                $sql = "Select c.DURATION,c.RIGHTS from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$serviceid'";
                $result_duration = mysql_query_decide($sql) or logError($error_msg,$sql,"ShowErrTemplate",$announce_to_email);;
                $myrow_duration = mysql_fetch_array($result_duration);
                $myrow["DURATION"] = $myrow_duration["DURATION"];
                $myrow["RIGHTS"] = $myrow_duration["RIGHTS"];
        }
        elseif($myrow["PACKAGE"]=="N")
        {
                $sql = "Select c.DURATION,c.RIGHTS from billing.SERVICES a, billing.COMPONENTS c where c.COMPID = a.COMPID AND a.SERVICEID = '$serviceid'";
                $result_duration = mysql_query_decide($sql) or logError($error_msg,$sql,"ShowErrTemplate",$announce_to_email);;
                $myrow_duration = mysql_fetch_array($result_duration);
                $myrow["DURATION"] = $myrow_duration["DURATION"];
                $myrow["RIGHTS"] = $myrow_duration["RIGHTS"];
        }
        return $myrow;
                                                                                                    
}

?>
