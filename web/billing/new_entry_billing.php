<?php

/**************************************************************************************************************************
FILE            : new_entry_billing.php
DESCRIPTION     : This file takes the details required for a new entry/renew of a user subcription.
FILES INCLUDED  : connect.inc,comfunc_sums.php
MODIFIED BY     : Vibhor Garg
DATE            : 22nd Nov 2011
**************************************************************************************************************************/
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/billing/comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

if(authenticated($cid))
{
	$serviceObj = new Services;
	$membershipObj = new Membership;
	
	$user=getname($cid);
	maStripVARS_sums('stripslashes');
	if($offline_billing)
		$offline_billing=1;
	/*Smarty assigned for use in most of the templates*/
	list($cur_year,$cur_month,$cur_day) = explode("-",date('Y-m-d'));
	$smarty->assign("cur_year",$cur_year);
	$smarty->assign("cur_month",$cur_month);
	$smarty->assign("cur_day",$cur_day);
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->assign("crm_id",$crm_id);
	$smarty->assign("source",$source);
	$smarty->assign("criteria",$criteria);
	$smarty->assign("phrase",$phrase);
	$smarty->assign("walkin_arr",create_dd($walkin,"Walkin"));
	$smarty->assign("city_india_arr",create_dd($City_India,"City_India"));
	$smarty->assign("myArray",($array1));
	$smarty->assign("myArray_dol",($array2));
	$smarty->assign("TAX_RATE",$TAX_RATE);//smarty assigned to be used in javascript while calculating net pay amount.
	/*End of - Smarty assigned for use in most of the templates*/

	/*To populate date/month/year selection dropdown boxes*/
	$ddarr = get_days();
	$mmarr = get_months();
	$yyarr = get_years();
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
	/*End of - To populate date/month/year selection dropdown boxes*/
	
	/*If renewing service*/
	if($renew)
	{
		$smarty->assign("renew",$renew);
		$smarty->assign("email",$email);
		$smarty->assign("sname",$sname);
		$smarty->assign("addon_services_lastbill",$addon_services_lastbill);
		$smarty->assign("expiry_dt",$expiry_dt);
		$smarty->assign("BOLD_LISTING_SELECTED",$BOLD_LISTING_SELECTED);
		$smarty->assign("HOROSCOPE_SELECTED",$HOROSCOPE_SELECTED);
		$smarty->assign("KUNDALI_SELECTED",$KUNDALI_SELECTED);
		$smarty->assign("MATRI_PROFILE_SELECTED",$MATRI_PROFILE_SELECTED);
		$smarty->assign("ASTRO_COMPATIBILITY_SELECTED",$ASTRO_COMPATIBILITY_SELECTED);
	}
	/*End of - if renewing service*/

	/*Smarty variable assignment, to be used in various scripts based on button clicked, all smarty variables are not used for single template*/

	$smarty->assign("username",$username);
	$smarty->assign("custname",$custname);

	/*To show the gender field selected. Two variables used because gender field is disabled*/
	$smarty->assign("gender",$gender);
	$smarty->assign("gender",$genderval);
	/*End of - To show the gender field selected. Two variables used because gender field is disabled*/

	$smarty->assign("gender_disp",$gender_disp);//To display Male/Female dependeng on M/F values.

	$smarty->assign("address",$address);
	$smarty->assign("email",$email);
	$smarty->assign("pin",$pin);
	$smarty->assign("rphone",$rphone);
	$smarty->assign("ophone",$ophone);
	$smarty->assign("mphone",$mphone);


	$service_type_arr = populate_service_type();
	get_service_price();
	$smarty->assign("service_type_arr",$service_type_arr);

	$service_duration = populate_service_duration();
	$smarty->assign("service_duration",$service_duration);
	$duration_arr=$serviceObj->populate_service_duration();
	$smarty->assign("duration_arr",$duration_arr);
	$count_arr=$serviceObj->populate_service_count();
	$smarty->assign("count_arr",$count_arr);
	$smarty->assign("curtype",$curtype);
	$smarty->assign("duration_sel",$duration_sel);
	$smarty->assign("discount",$discount);
	$smarty->assign("discount_type",$discount_type1);
	$smarty->assign("reason",$reason);

	$discount_type = populate_discount_type();
	$smarty->assign("discount_type",$discount_type);

	$from_source_arr = populate_from_source();
	$smarty->assign("from_source_arr",$from_source_arr);

	$bank_arr = get_banks();
	$smarty->assign("bank_arr",$bank_arr);

	$dep_branch_arr = get_deposit_branches();
	$smarty->assign("dep_branch_arr",$dep_branch_arr);

	$disc_type = get_discount_type($discount_type1);
	$smarty->assign("disc_type",$disc_type);
	$smarty->assign("disc_type1",$disc_type1);

	$pay_mode = mode_of_payment();
	$smarty->assign("pay_mode",$pay_mode);

	$smarty->assign("service_type",$service_type);

	$smarty->assign("main_service_id",$main_service_id);
	$smarty->assign("addons",$addons);
	$smarty->assign("addonid",$addonid);
	$smarty->assign("offline_billing",$offline_billing);

	$smarty->assign("walkin",$walkin);


	$smarty->assign("discount",$discount);
	$smarty->assign("voucher_discount_code",$voucher_discount_code);
	$smarty->assign("discount_type1",$discount_type1);
	$smarty->assign("reason",$reason);
	$smarty->assign("price",$price);
	$smarty->assign("tax_rate",$tax_rate);
	$smarty->assign("tax",$tax);
	$smarty->assign("total_pay",$total_pay);
	$smarty->assign("services",$services);

	if($curtype=="RS")
		$smarty->assign("curtype_disp","Rupees");
	else
		$smarty->assign("curtype_disp","US($)");

	if($City_India != "Other")
	{
		$city_ind = label_select("CITY_INDIA",$City_India,"newjs");
		$smarty->assign("city_ind",$city_ind[0]);
	}
	else
		$smarty->assign("ocity",$ocity);
	$smarty->assign("city",$city);
	$smarty->assign("comment",$comment);
	$smarty->assign("mode",$mode);
	$smarty->assign("from_source",$from_source);
	$smarty->assign("transaction_number",$transaction_number);
	$smarty->assign("amount",$amount);
	$smarty->assign("cdnum",$cdnum);
	$cd_date = $cd_year."-".$cd_month."-".$cd_day;
	$smarty->assign("cd_date",$cd_date);
	$smarty->assign("cd_city",$cd_city);

	$smarty->assign("bank",$Bank);
	$smarty->assign("obank",$obank);

	$smarty->assign("overseas",$overseas);
	$smarty->assign("separateds",$separateds);
	$due_date = $due_year."-".$due_month."-".$due_day;
	$smarty->assign("due_date",$due_date);
	$dep_date = $dep_year."-".$dep_month."-".$dep_day;
	$smarty->assign("dep_date",$dep_date);
	$smarty->assign("cd_day",$cd_day);
	$smarty->assign("cd_month",$cd_month);
	$smarty->assign("cd_year",$cd_year);
	$smarty->assign("cd_city",$cd_city);
	$smarty->assign("overseas",$overseas);
	$smarty->assign("separateds",$separateds);
	$smarty->assign("due_day",$due_day);
	$smarty->assign("due_month",$due_month);
	$smarty->assign("due_year",$due_year);
	$smarty->assign("dep_day",$dep_day);
	$smarty->assign("dep_month",$dep_month);
	$smarty->assign("dep_year",$dep_year);
	$smarty->assign("dep_branch",$dep_branch);
	/*End of - Smarty variable assignment, to be used in various scripts based on button clicked, all smarty variables are not used for single templates*/

	/* When button is clicked from first page*/
	if($pg1_submit)
	{
		$is_error=0;
		/*Server side checks for User details*/
		if($membership_service == 'ES' || $membership_service == 'ESP')
		{
			if($chk_boldlisting_service || $chk_astro_service || $chk_matriprofile_service || $chk_display_service || $chk_assistance_service || $chk_introcalls_service || $chk_featuredprofile_service || $chk_JSExclusive_service)
			{
				$is_error++;
				$smarty->assign("msg","No other service can be selected with eSathi");
			}
		}
		if($membership_service == 'NCP')
		{
			if($chk_assistance_service || $chk_featuredprofile_service || $chk_JSExclusive_service)
                        {
                                $is_error++;
                                $smarty->assign("msg","Auto-apply, Featured Profile & JS Exclusive cannot be selected with eAdvantage");
                        }

		}
		if(!$renew)//if renewing, no need to check user details
		{
			if(trim($custname)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_CUSTNAME","Y");
			}
			if(trim($genderval)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_GENDER","Y");
			}
			if($City_India=="Other")
				$city=$ocity;
			else
				$city=$City_India;
			
		}
		/*End of - Server side checks for User details*/
		/*Server side checks for Service and Sale details*/
		if(trim($curtype)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_CURRTYPE","Y");
		}
		if(trim($walkin)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_WALKIN","Y");
		}
		if(trim($discount) == '' || ($discount)==0)
		{
			$reason='';
			$discount_type1='';
		}
		elseif(trim($discount) != '' && ($discount)!=0)
		{
			if($reason=='')
			{
				$is_error++;
				$smarty->assign("CHECK_REASON","Y");
			}
			if($discount_type1=='')
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT_TYPE","Y");
			}
			if(!$renew && trim($voucher_discount_code))
			{
				$returned_val = check_voucher_discount_code(trim($voucher_discount_code));
				if($returned_val['CODE_EXISTS'] == 0)
				{
					$is_error++;
					$smarty->assign("CHECK_VOUCHER_DISCOUNT","Y");
				}
			}
			
			$services_arr = array("membership_service",/*"boldlisting_service",*/"astro_service",/*"matriprofile_service","display_service","assistance_service","introcalls_service","featuredprofile_service",*/"JSExclusive_service");
                        for($i=0;$i<count($services_arr);$i++)
                        {
                                $to_check = "chk_".$services_arr[$i];
                                if($$to_check == "Y")
                                {
                                        $check_duration = $services_arr[$i]."_duration";
                                        $dur = $$check_duration;
                                        if($dur=='1188')
                                                $dur='L';
                                        if($dur=='0.07')
                                                $dur='1W';
                                        if($dur=='0.5')
                                                $dur='2W';
                                        if($dur=='1.5')
                                                $dur='6W';
                                        $check_service_id_arr[] = $$services_arr[$i].$dur;
                                }
                        }
			$check_service_id_str = implode(",",$check_service_id_arr);
			$total_price = $serviceObj->getTotalPrice($check_service_id_str, $curtype);
				
			if($discount > $total_price || $discount < 0)
			{
				$is_error++;
				$smarty->assign("CHECK_DISCOUNT","Y");
			}
		}
		/*End of - Server side checks for Service and Sale details*/
		if($is_error==0)
		{
			$no_err=0;
			$services_arr = array("membership_service",/*"boldlisting_service",*/"astro_service",/*"matriprofile_service","display_service","assistance_service","introcalls_service","featuredprofile_service",*/"JSExclusive_service");
			for($i=0;$i<count($services_arr);$i++)
			{
				$to_check = "chk_".$services_arr[$i];
				if($$to_check == "Y")
				{
					$no_err=1;
					if($services_arr[$i]=='assistance_service' || $services_arr[$i]=='introcalls_service')
						$main_req=1;
					$temp_duration = $services_arr[$i]."_duration";
					$duration = $$temp_duration;
					if($services_arr[$i]!='membership_service' && $services_arr[$i]!='introcalls_service')
						$dur_arr[$$services_arr[$i]]=$duration;
					if($duration=='1188')
						$duration='L';
					if($duration=='0.07')
						$duration='1W';
					if($duration=='0.5')
						$duration='2W';
					if($duration=='1.5')
						$duration='6W';
					$temp_service_id_arr[] = $$services_arr[$i].$duration;
				}
			}
			if($main_req)
			{
				$msg=$membershipObj->checkRange($profileid,$temp_service_id_arr);
				if($msg)
					$no_err=0;
			}
			foreach($temp_service_id_arr as $k=>$v)
                        {
                                if($serviceObj->getServiceName($v)=='')
                                {
                                        $msg="Please select the valid duration for the selected service(s)";
                                        if($msg)
                                                $no_err=0;
                                }
                        }
			if(!$no_err)
			{
				$is_error++;
				$smarty->assign("msg",$msg);
				$smarty->assign("CHECK_SERVICE",1);
				$smarty->display("new_entry_billing.htm");
				exit;
			}
			
			
			$serviceid = @implode(",",$temp_service_id_arr);
			
			$temp_service_name_array = $serviceObj->getServiceName($serviceid);
		
			for($i=0;$i<count($temp_service_id_arr);$i++)
			{
				$temp_service_id = $temp_service_id_arr[$i];
				$service_amount = $serviceObj->getServicesAmountWithoutTax($temp_service_id,$curtype);
				$service_amount_tax = $serviceObj->getServicesAmount($temp_service_id,$curtype);
				
				$service_names[$i]["NAME"] = $temp_service_name_array[$temp_service_id]["NAME"];
				$service_names[$i]["PRICE"] = $service_amount_tax[$temp_service_id]["PRICE"];
				
				$price += $service_amount[$temp_service_id]["PRICE"];
				$price_tax += $service_amount_tax[$temp_service_id]["PRICE"];
			}

			$smarty->assign("price",$price);	//to display service_price excluding tax.
			$smarty->assign("tax_rate",$membershipObj->getTaxRate());

			$tax = $price * ($membershipObj->getTaxRate()/100);
			$tax = round($tax,2);
			$smarty->assign("tax",$tax);
		
			$total_pay = $price_tax - $discount;
			
			$smarty->assign("total_pay",$total_pay);
			$smarty->assign("main_service_id",$serviceid);
			$smarty->assign("service_names",$service_names);
			$smarty->assign("dep_branch",strtoupper(getcenter_for_walkin($user)));

			$smarty->display("new_entry_paydet_billing.htm");
		}
		else
		{
			$smarty->assign("renew",$renew);
			$smarty->display("new_entry_billing.htm");
		}
		
	}
	/* End of - When button is clicked from first page*/

	/* When button is clicked from second page */
	elseif($pg2_submit)
	{
		/*Server side checks for Payment details*/
		$is_error = 0;
		$arr_trans = array_for_trans_num();
		if($convert_curr=='CONV_DOL')
                        $convert_to='Converted to Dollars';

                $smarty->assign('convert_curr',$convert_curr);
                $smarty->assign('convert_to',$convert_to);

		if(trim($mode)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_MODE","Y");
		}
		if(trim($from_source)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_FROM_SOURCE","Y");
		}
		if(in_array($from_source,$arr_trans))
		{
			if(trim($transaction_number)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_FROM_SOURCE","Y");
			}
		}
		if(trim($amount)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_AMOUNT","Y");
		}
		if($mode == "CHEQUE" || $mode == "DD")
		{
			if(trim($cdnum)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_CDNUM","Y");
			}
			if(trim($cd_day)=="" || trim($cd_month)=="" || trim($cd_year)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_CDDATE","Y");
			}
			if(trim($cd_city)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_CDCITY","Y");
			}
			if(trim($Bank)=="" && trim($obank)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_BANK","Y");
			}
			if(trim($address) == "") {
				$is_error++;
				$smarty->assign("CHECK_ADDRESS","Y");	
			}

			/*check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
                        $entered_timestamp = mktime(0,0,0,$cd_month,$cd_day,$cd_year);
                        $arr1 = explode("-",date('Y-m-d'));
                        list($y,$m,$d) = $arr1;
                        $current_timestamp = mktime(0,0,0,$m,$d,$y);
                        $f=4;
                        $r = $m-$f;
                        if($r<=0)
                        {
                                $r=$r+12;
                                $y--;
                        }
                        $checking_timestamp = mktime(0,0,0,$r,$d,$y);
                        if(($current_timestamp-$entered_timestamp)>($current_timestamp-$checking_timestamp))
                        {
                                $is_error++;
                                $smarty->assign("CHECK_CDDATE","Y");
                        }
			if($entered_timestamp > $current_timestamp)
			{
                                $is_error++;
                                $smarty->assign("CHECK_CDDATE","Y");
			}
			/*end of - check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
		}
		/*if there is due amount, then check for due date -- due date cannot be blank and it cannot exeed 1 month duration from current date */
		if($amount<$total_pay)
		{
			if(trim($due_day)=="" || trim($due_month)=="" || trim($due_year)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_DUEDATE","Y");
			}
                        $entered_timestamp = mktime(0,0,0,$due_month,$due_day,$due_year);
                        $arr1 = explode("-",date('Y-m-d'));
                        list($y,$m,$d) = $arr1;
                        $current_timestamp = mktime(0,0,0,$m,$d,$y);

			if($m==12)
			{
				$new_m=1;
				$y++;
			}
			else
				$new_m = $m+1;
			$timestamp_tocheck = mktime(0,0,0,$new_m,$d,$y);
			if($enterd_timestamp > $timestamp_tocheck)
			{
				$is_error++;
				$smarty->assign("CHECK_DUEDATE","Y");
			}
		}
		/*end of - if there is due amount, then check for due date -- due date cannot be blank and it cannot exeed 1 month duration from current date */
		if(trim($dep_day)=="" || trim($dep_month)=="" || trim($dep_year)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_DEPDATE","Y");
		}
		if(trim($dep_branch)=="")
		{
			$is_error++;
			$smarty->assign("CHECK_BRANCH","Y");
		}
		/*End of - Server side checks for Payment details*/

		if($is_error==0)
		{
			$temp_service_id_arr = @explode(",",$main_service_id);
			$temp_service_name_array = $serviceObj->getServiceName($main_service_id);
			for($i=0;$i<count($temp_service_id_arr);$i++)
			{
				$temp_service_id = $temp_service_id_arr[$i];
				$service_amount_tax = $serviceObj->getServicesAmount($temp_service_id,$curtype);
				$service_names[$i]["NAME"] = $temp_service_name_array[$temp_service_id]["NAME"];
				$service_names[$i]["PRICE"] = $service_amount_tax[$temp_service_id]["PRICE"];
			}
			$smarty->assign("service_names",$service_names);
			$smarty->display("new_entry_summary_billing.htm");
		}
		else
		{
			$smarty->assign("city_ind",$city);
			$smarty->assign("disc_type",$disc_type1);
			$smarty->display("new_entry_paydet_billing.htm");

		}
	}
	/* End of - When button is clicked from second page */
	/*When no button is pressed (or this php is called by clicking on New Entry hyperlink)*/
	else
	{
		if(!$renew)
		{
			$service_type = populate_service_type();
			$smarty->assign("service_type",$service_type);
			$service_duration = populate_service_duration();
			$smarty->assign("service_duration",$service_duration);
			$discount_type = populate_discount_type();
			$smarty->assign("discount_type",$discount_type);

			$sql = "Select INCOMPLETE from newjs.JPROFILE where PROFILEID = '$pid'";
			$result = mysql_query_decide($sql) or logError_sums($sql,0);
			$myrow = mysql_fetch_array($result);
			if($myrow['INCOMPLETE'] == 'Y')
			{
				$msg = "Billing entry not allowed for incomplete profile.";
				$msg .= "<br><a href=\"search_user.php?cid=$cid\">Click here to go back</a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("billing_msg.tpl");
				exit;
			}
			if($req_idsub)
			{
				$crm_id = $reqid;
				$source = "A";
			}
			if($source=="A")//if came from AIREX module
			{
				$smarty->assign("source",$source);

				$sql="SELECT * from incentive.PAYMENT_COLLECT where ID='$crm_id'";
				$result=mysql_query_decide($sql) or logError_sums($sql,0);
				$myrow=mysql_fetch_array($result);

				$newjs_details = get_jprofile_details($myrow['PROFILEID']);

				$smarty->assign("username",$newjs_details['USERNAME']);
				$smarty->assign("custname",$myrow['NAME']);
				$smarty->assign("gender",$newjs_details['GENDER']);
				$smarty->assign("address",$newjs_details['CONTACT']);
				$smarty->assign("email",$newjs_details['EMAIL']);
				$smarty->assign("city_india_arr",create_dd($newjs_details['CITY_RES'],"City_India"));
				$smarty->assign("pin",$newjs_details['PIN']);
				$smarty->assign("rphone",$newjs_details['PHONE_RES']);
				$smarty->assign("mphone",$newjs_details['PHONE_MOB']);
				$smarty->assign("discount",$myrow['DISCOUNT']);

				$smarty->assign("curtype",$myrow['TYPE']);

				$sid=$myrow['SERVICE'];
				substr($sid,1);
				$smarty->assign("service_type",substr($sid,0,1));
				$smarty->assign("duration_sel",substr($sid,1));

				if(strstr($myrow['ADDON_SERVICEID'],'B'))
					$smarty->assign("BOLD_LISTING_SELECTED","Y");
				if(strstr($myrow['ADDON_SERVICEID'],'V'))
					$smarty->assign("VOICEMAIL_SELECTED","Y");
				if(strstr($myrow['ADDON_SERVICEID'],'K'))
					$smarty->assign("KUNDLI_SELECTED","Y");
				if(strstr($myrow['ADDON_SERVICEID'],'H'))
					$smarty->assign("HOROSCOPE_SELECTED","Y");
				if(strstr($myrow['ADDON_SERVICEID'],'M'))
					$smarty->assign("MATRI_PROFILE_SELECTED","Y");
				if(strstr($myrow['ADDON_SERVICEID'],'A'))
					$smarty->assign("ASTRO_COMPATIBILITY_SELECTED","Y");
			}
			else
			{
				$myrow = get_jprofile_details($pid);
				$smarty->assign("username",$myrow['USERNAME']);
				$smarty->assign("gender",$myrow['GENDER']);
			}
		}
		else
		{
			$row = get_jprofile_details($pid);
			$smarty->assign("username",$row['USERNAME']);
			$smarty->assign("email",$row['EMAIL']);

			$sql_ids = "SELECT p.BILLID, p.SERVICEID, p.ADDON_SERVICEID, sst.EXPIRY_DT FROM billing.PURCHASES p, billing.SERVICE_STATUS sst WHERE p.PROFILEID = '$pid' AND p.BILLID = sst.BILLID ORDER BY p.BILLID DESC";
			$res_ids = mysql_query_decide($sql_ids) or logError_sums($sql_ids,0);
			$row_ids = mysql_fetch_array($res_ids);

			$smarty->assign("service_type1",substr($row_ids['SERVICEID'],0,1));
			$smarty->assign("duration_sel",substr($row_ids['SERVICEID'],1));
			$smarty->assign("expiry_dt",$row_ids['EXPIRY_DT']);

			$sql_sname = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID = '$row_ids[SERVICEID]'";
			$res_sname = mysql_query_decide($sql_sname) or logError_sums($sql_sname,0);
			$row_sname = mysql_fetch_array($res_sname);


			$smarty->assign("sname",$row_sname['NAME']);

			if($row_ids['ADDON_SERVICEID'])
			{
				if(strstr($row_ids['ADDON_SERVICEID'],'B'))
                                        $smarty->assign("BOLD_LISTING_SELECTED","Y");
                                if(strstr($row_ids['ADDON_SERVICEID'],'V'))
                                        $smarty->assign("VOICEMAIL_SELECTED","Y");
                                if(strstr($row_ids['ADDON_SERVICEID'],'K'))
                                        $smarty->assign("KUNDLI_SELECTED","Y");
                                if(strstr($row_ids['ADDON_SERVICEID'],'H'))
                                        $smarty->assign("HOROSCOPE_SELECTED","Y");
                                if(strstr($row_ids['ADDON_SERVICEID'],'M'))
                                        $smarty->assign("MATRI_PROFILE_SELECTED","Y");
                                if(strstr($row_ids['ADDON_SERVICEID'],'A'))
                                        $smarty->assign("ASTRO_COMPATIBILITY_SELECTED","Y");

				$addon_ser_ar = explode(",",$row_ids['ADDON_SERVICEID']);
				$addon_ser_str = "'".implode("','",$addon_ser_ar)."'";
			

				$sql_adname = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID IN ($addon_ser_str)";
				$res_adname = mysql_query_decide($sql_adname) or logError_sums($sql_adname,0);
				while($row_adname = mysql_fetch_array($res_adname))
				{
					$addon_ser_name[] = $row_adname['NAME'];
					$addon_services_lastbill = implode(",",$addon_ser_name);
				}
				$smarty->assign("addon_services_lastbill",$addon_services_lastbill);
			}

		}
		/*code to display service prices through javascript*/
		$array1 = 'var myArray = new Array();';
		$sql_pr = "SELECT SERVICEID, desktop_RS from billing.SERVICES where ID > 6";
		$result_pr = mysql_query_decide($sql_pr) or logError_sums($sql_pr,0);
		$i=0;
		while($myrow_pr=mysql_fetch_array($result_pr))
		{
														     
														     
			$array1 .= "myArray['".$myrow_pr["SERVICEID"]."'] =".$myrow_pr["desktop_RS"].";";
			$ser_arr[$i]=$myrow_pr["SERVICEID"];
			$i++;
														     
														     
		}
		$smarty->assign("myArray",$array1);
														     
		$array2 = 'var myArray_dol = new Array();';
		$sql_pr = "SELECT SERVICEID, desktop_DOL from billing.SERVICES where ID > 6";
		$result_pr = mysql_query_decide($sql_pr) or logError_sums($sql_pr,0);
		$i=0;
		while($myrow_pr=mysql_fetch_array($result_pr))
		{
														     
														     
			$array2 .= "myArray_dol['".$myrow_pr["SERVICEID"]."'] =".$myrow_pr["desktop_DOL"].";";
			$ser_arr[$i]=$myrow_pr["SERVICEID"];
			$i++;
														     
														     
		}
		$smarty->assign("myArray_dol",$array2);
		/*end of code to display service prices through javascript*/
		$smarty->assign("pid",$pid);
		$smarty->display("new_entry_billing.htm");
	}
	/*End of - When no button is pressed (or this php is called by clicking on New Entry hyperlink)*/
}
else
{
        $msg="Your session is timed out";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("billing_msg.tpl");
                                                                                                                             
}

function get_service_price()
{
	global $smarty;
	$sql="SELECT SERVICEID, NAME, desktop_RS, desktop_DOL FROM billing.SERVICES WHERE ACTIVE='Y'";
	$res=mysql_query_decide($sql) or logError_sums($sql);
	while($row= mysql_fetch_array($res))
	{
		$ind1=substr($row['SERVICEID'],0,1);
		if(strstr($row['SERVICEID'],'120'))
			$ind2='120';
		elseif(strstr($row['SERVICEID'],'12'))
			$ind2='12';
		elseif(strstr($row['SERVICEID'],'40'))
			$ind2='40';
		elseif(strstr($row['SERVICEID'],'50'))
			$ind2='50';
		elseif(strstr($row['SERVICEID'],'30'))
			$ind2='30';
		elseif(strstr($row['SERVICEID'],'60'))
			$ind2='60';
		elseif(strstr($row['SERVICEID'],'20'))
			$ind2='20';
		elseif(strstr($row['SERVICEID'],'70'))
			$ind2='70';
		elseif(strstr($row['SERVICEID'],'100'))
			$ind2='100';
                elseif(strstr($row['SERVICEID'],'110'))
                        $ind2='110';
		elseif(strstr($row['SERVICEID'],'10'))
			$ind2='10';
		elseif(strstr($row['SERVICEID'],'80'))
			$ind2='80';
		elseif(strstr($row['SERVICEID'],'90'))
			$ind2='90';
		elseif(strstr($row['SERVICEID'],'11'))
			$ind2='11';
		elseif(strstr($row['SERVICEID'],'1W'))
			$ind2="0.07";
		elseif(strstr($row['SERVICEID'],'1'))
			$ind2='1';
		elseif(strstr($row['SERVICEID'],'2W'))
			$ind2="0.5";
		elseif(strstr($row['SERVICEID'],'6W'))
			$ind2="1.5";
		elseif(strstr($row['SERVICEID'],'2'))
			$ind2='2';
		elseif(strstr($row['SERVICEID'],'3'))
			$ind2='3';
		elseif(strstr($row['SERVICEID'],'4'))
			$ind2='4';
		elseif(strstr($row['SERVICEID'],'5'))
			$ind2='5';
		elseif(strstr($row['SERVICEID'],'6'))
			$ind2='6';
		elseif(strstr($row['SERVICEID'],'7'))
			$ind2='7';
		elseif(strstr($row['SERVICEID'],'9'))
			$ind2='9';
		elseif(strstr($row['SERVICEID'],'8'))
			$ind2='8';
		elseif(strstr($row['SERVICEID'],'L'))
			$ind2="1188";

		/*if($ind2=='L')
			$ind2=1188;*/
		if($ind1=='S')
			$ind1='SC';
		elseif($ind1=='H')
			$ind1='HDO';
		elseif(strstr($row['SERVICEID'],'ESP'))
                        $ind1='ESP';
		elseif(strstr($row['SERVICEID'],'ESJA'))
			$ind1='ESJA';
		elseif(strstr($row['SERVICEID'],'ES'))
                        $ind1='ES';
        elseif(strstr($row['SERVICEID'],'NCP'))
                        $ind1='NCP';
		$ser_rupee_arr[$ind1][$ind2]=$row['desktop_RS'];
		$ser_dollar_arr[$ind1][$ind2]=$row['desktop_DOL'];
	}

	$smarty->assign("rupee_arr",$ser_rupee_arr);
	$smarty->assign("dollar_arr",$ser_dollar_arr);
}
?>
