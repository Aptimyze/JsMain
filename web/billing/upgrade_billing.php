<?php
include_once("../jsadmin/connect.inc");
include_once("comfunc_sums.php");

if(authenticated($cid))
{
	/*Smarty variables assigned to be used in various templates.(all smarty variables may not be used in single template)*/
	maStripVARS_sums('stripslashes');
	$service_type_arr = populate_service_type();
	$smarty->assign("service_type_arr",$service_type_arr);

	$service_duration = populate_service_duration();
	$smarty->assign("service_duration",$service_duration);
	
	$smarty->assign("walkin_arr",create_dd($walkin,"Walkin"));
	
	$discount_type = populate_discount_type();
	$smarty->assign("discount_type",$discount_type);

	$from_source_arr = populate_from_source();
	$smarty->assign("from_source_arr",$from_source_arr);
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

	list($cur_year,$cur_month,$cur_day) = explode("-",date('Y-m-d'));
        $smarty->assign("cur_year",$cur_year);
        $smarty->assign("cur_month",$cur_month);
        $smarty->assign("cur_day",$cur_day);
        $smarty->assign("cid",$cid);
        $smarty->assign("pid",$pid);
        $smarty->assign("user",$user);
        $smarty->assign("crm_id",$crm_id);
        $smarty->assign("source",$source);
        $smarty->assign("criteria",$criteria);
        $smarty->assign("phrase",$phrase);
        $smarty->assign("walkin_arr",create_dd($walkin,"Walkin"));
	$ddarr = get_days();
        $mmarr = get_months();
        $yyarr = get_years();
        $smarty->assign("ddarr",$ddarr);
        $smarty->assign("mmarr",$mmarr);
        $smarty->assign("yyarr",$yyarr);

	$smarty->assign("status",$status);
	$smarty->assign("username",$username);
	$smarty->assign("email",$email);
	$smarty->assign("sname",$sname);
	$smarty->assign("addon_services_lastbill",$addon_services_lastbill);
	$smarty->assign("expiry_dt",$expiry_dt);
	$smarty->assign("billid",$billid);
	$smarty->assign("curtype",$curtype);
	if($curtype=="0")
		$smarty->assign("curtype_disp","Rupees");
	else
		$smarty->assign("curtype_disp","US($)");

	$smarty->assign("stdate",$stdate);
	$smarty->assign("service_type",$service_type);
	$smarty->assign("duration_sel",$duration_sel);
	$smarty->assign("discount",$discount);
        $smarty->assign("reason",$reason);

	$bank_arr = get_banks();
        $smarty->assign("bank_arr",$bank_arr);
                                                                                                                             
        $dep_branch_arr = get_deposit_branches();
        $smarty->assign("dep_branch_arr",$dep_branch_arr);
	$smarty->assign("dep_branch",strtoupper(getcenter_for_walkin($user)));
                                                                                                                             
        $disc_type = get_discount_type($discount_type1);
        $smarty->assign("disc_type",$disc_type);
        $smarty->assign("disc_type1",$disc_type1);

	$pay_mode = mode_of_payment();
        $smarty->assign("pay_mode",$pay_mode);

	$service_name = get_service_name($service_type);
        $smarty->assign("service_name",$service_name);
        $smarty->assign("service_name1",$service_name1);
        $smarty->assign("main_service_id",$main_service_id);
        $smarty->assign("addon_services",$addon_services);
        $smarty->assign("addons",$addons);
	$smarty->assign("addonid",$addonid);
        $smarty->assign("walkin",$walkin);

        $smarty->assign("discount_type1",$discount_type1);
        $smarty->assign("reason",$reason);
        $smarty->assign("price",$price);
        $smarty->assign("tax_rate",$tax_rate);
        $smarty->assign("tax",$tax);
        $smarty->assign("total_pay",$total_pay);
        $smarty->assign("services",$services);
	
	$smarty->assign("comment",$comment);
        $smarty->assign("mode",$mode);
        $smarty->assign("amount",$amount);
        $smarty->assign("cdnum",$cdnum);
        $cd_date = $cd_year."-".$cd_month."-".$cd_day;
        $smarty->assign("cd_date",$cd_date);
        $smarty->assign("cd_city",$cd_city);
        if($Bank)
                $smarty->assign("bank",$Bank);
        else
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
	/*End of - Smarty variables assigned to be used in various templates.(all smarty variables may not be used in single template)*/
	
	/*When button is clicked from page 1*/
	if($pg1_submit)
	{
		/*Server side checks for service details.*/
		if(trim($curtype)=="")
                {
                        $is_error++;
                        $smarty->assign("CHECK_CURRTYPE","Y");
                }
                if(trim($service_type)=="")
                {
                        $is_error++;
                        $smarty->assign("CHECK_SERVICE_TYPE","Y");
                }
                if(trim($duration_sel)=="")
                {
                        $is_error++;
                        $smarty->assign("CHECK_DURATION","Y");
                }
		/*End of - Server side checks for service details.*/
		/*Server side check for sale details*/
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
                                                                                                                             
                }
		/*End of - Server side check for sale details*/
		if($is_error==0)
                {
			if($stdate=="previous_dt")//when to start the service from 
			{
				$sql = "SELECT ACTIVATED_ON, ACTIVATE_ON FROM billing.SERVICE_STATUS WHERE BILLID='$billid'";
				$res = mysql_query_decide($sql) or logError_sums($sql,0);
				$row = mysql_fetch_array($res);
				if($row['ACTIVATED_ON'] == "0000-00-00")
					$stdate = $row['ACTIVATE_ON'];
				else
					$stdate = $row['ACTIVATED_ON'];
			}
			else
				$stdate = date('Y-m-d');

			$smarty->assign("stdate",$stdate);

			/*If service is Matri Profile then serviceid is M , else service_id is concatenation of Main service and its duration*/
                        if($service_type=='M')
                        {
                                 $service_id = "'".$service_type."'";
                                 $main_service_id = $service_id;
                        }
                        else
                        {
                                $service_id = "'".$service_type.$duration_sel."'";
                                $main_service_id = $service_id;
                                if($blist)
                                {
                                        $addonid[] .= "'".$blist.$duration_sel."'";
                                        $addon_services[] = $blist;
                                }
                                if($horoscope)
                                {
                                        $addonid[] .= "'".$horoscope.$duration_sel."'";
                                        $addon_services[] = $horoscope;
                                }
                                if($kundali)
                                {
                                        $addonid[] .= "'".$kundali.$duration_sel."'";
                                        $addon_services[] = $kundali;
                                }
                                if($matrip)
                                {
                                        $addonid[] .= "'".$matrip.$duration_sel."'";
                                        $addon_services[] = $matrip;
                                }
                                if($astro_compatibility)
                                {
                                        $addonid[] .= "'".$astro_compatibility.$duration_sel."'";
                                        $addon_services[] = $astro_compatibility;
                                }
				if($addonid)
				{
                                        $addonid = implode(",",$addonid);
                                        $service_id .= ",".$addonid;
				}
                                                                                                                             
                        }
			/*End of - If service is Matri Profile then serviceid is M , else service_id is concatenation of Main service and its duration*/

			/*Calculating price for selected service(s)*/
			$sql="SELECT desktop_RS, desktop_DOL FROM billing.SERVICES WHERE SERVICEID IN($service_id)";
                        $res = mysql_query_decide($sql) or logError_sums($sql,0);
                        while($row = mysql_fetch_array($res))
                        {
                                if($curtype=="0")
                                {
                                        $price += $row['desktop_RS']*(1-($TAX_RATE/100));
                                        $price_tax += $row['desktop_RS'];
                                }
                                else
                                {
                                        $price += $row['desktop_DOL']*(1-($TAX_RATE/100));
                                        $price_tax += $row['desktop_DOL'];
                                }
                        }
                        $addons = get_addon_services($addon_services);
                        $smarty->assign("addons",$addons);
                                                                                                                             
                        $smarty->assign("price",$price);//to display price without tax.
                        $smarty->assign("tax_rate",$TAX_RATE);
                                                                                                                             
                        $tax = $price * ($TAX_RATE/100);
                        $tax = round($tax,2);
                        $smarty->assign("tax",$tax);
                                                                                                                             
                        $total_pay = $price_tax - $discount;
                        $smarty->assign("total_pay",$total_pay);
                        $smarty->assign("main_service_id",$main_service_id);
			$smarty->assign("addonid",$addonid);
                        $addon_services = @implode(",",$addon_services);
                        $smarty->assign("addon_services",$addon_services);
			/*End of - Calculating price for selected service(s)*/

			/*Code to fetch the price for previous service.*/
			$sql_prev_id = "SELECT SERVICEID, ADDON_SERVICEID FROM billing.PURCHASES WHERE BILLID = '$billid'";
			$res_prev_id = mysql_query_decide($sql_prev_id) or logError_sums($sql_prev_id,0);
			$row_prev_id = mysql_fetch_array($res_prev_id);

			$prev_ids =  $row_prev_id['SERVICEID'];
			if($row_prev_id['ADDON_SERVICEID'])
				$prev_ids .= ",".$row_prev_id['ADDON_SERVICEID'];

			$prev_ids_ar = explode(",",$prev_ids);
			$prev_ids_str = "'".implode("','",$prev_ids_ar)."'";
			
			if($curtype=="0")
				$cur_to_select = "desktop_RS";
			else
				$cur_to_select = "desktop_DOL";

			$sql_prev_cost = "SELECT SUM($cur_to_select) AS TCOST FROM billing.SERVICES WHERE SERVICEID IN ($prev_ids_str)";
			$res_prev_cost = mysql_query_decide($sql_prev_cost) or logError_sums($sql_prev_cost,0);
			$row_prev_cost = mysql_fetch_array($res_prev_cost);
			$prev_cost = $row_prev_cost['TCOST'];
			/*End of - Code to fetch the price for previous service.*/

			/*Calculating total amount paid till date*/
			$sql = "SELECT SUM(AMOUNT) as AMOUNT,TYPE FROM billing.PAYMENT_DETAIL WHERE BILLID = '$billid' GROUP BY TYPE";
			$res = mysql_query_decide($sql) or logError_sums($sql,0);
			$j=0;
			while($row = mysql_fetch_array($res))
			{
				$hist[$j]['TYPE']=$row['TYPE'];
				$hist[$j]['AMOUNT']=$row['AMOUNT'];
				$j++;
			}
			$smarty->assign("hist",$hist);
			/*End of - Calculating total amount paid till date*/

			/*Check for degrade*/
			if($price_tax < $prev_cost)
				$smarty->assign("degrade","1");
			/*End of - Check for degrade*/

                        $smarty->display("upgrade_paydet_billing.htm");
                }
                else
                {
                        $smarty->display("upgrade_billing.htm");
                }
	}
	/*End of - When button is clicked from page 1*/
	/*When no button is clicked (or this php is called by clicking on Upgrade/degrade button form search page)*/
	else
	{
		$row = get_jprofile_details($pid);
		$smarty->assign("username",$row['USERNAME']);
		$smarty->assign("email",$row['EMAIL']);
														     
		$sql_ids = "SELECT p.BILLID, p.CUR_TYPE, p.SERVICEID, p.ADDON_SERVICEID, sst.EXPIRY_DT FROM billing.PURCHASES p, billing.SERVICE_STATUS sst WHERE p.PROFILEID = '$pid' AND p.BILLID = sst.BILLID AND p.BILLID='$billid'";
		$res_ids = mysql_query_decide($sql_ids) or logError_sums($sql_ids,0);
		$row_ids = mysql_fetch_array($res_ids);

		if($row_ids['CUR_TYPE'] == 'RS')
			$curtype = 0;
		else
			$curtype = 1;

		$smarty->assign("service_type",substr($row_ids['SERVICEID'],0,1));
		$smarty->assign("duration_sel",substr($row_ids['SERVICEID'],1));
		$smarty->assign("expiry_dt",$row_ids['EXPIRY_DT']);
		$smarty->assign("curtype",$curtype);
														     
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
			for($i=0;$i<count($addon_ser_ar);$i++)
				$addon_ser_ar[$i] = "'".$addon_ser_ar[$i]."'";
			$addon_ser_str = implode(",",$addon_ser_ar);

			$sql_adname = "SELECT NAME FROM billing.SERVICES WHERE SERVICEID IN ($addon_ser_str)";
			$res_adname = mysql_query_decide($sql_adname) or logError_sums($sql_adname,0);
			while($row_adname = mysql_fetch_array($res_adname))
			{
				$addon_ser_name[] = $row_adname['NAME'];
				$addon_services_lastbill = implode(",",$addon_ser_name);
			}
			$smarty->assign("addon_services_lastbill",$addon_services_lastbill);
		}

		$sql = "SELECT SUM(AMOUNT) as AMOUNT,TYPE FROM billing.PAYMENT_DETAIL WHERE BILLID = '$billid' GROUP BY TYPE";
		$res = mysql_query_decide($sql) or logError_sums($sql,0);
		$j=0;
		while($row = mysql_fetch_array($res))
		{
			$hist[$j]['TYPE']=$row['TYPE'];
			$hist[$j]['AMOUNT']=$row['AMOUNT'];
			$j++;
		}
		$smarty->assign("hist",$hist);

		$curdate = date("Y-m-d",time());
                $sql = "Select * from billing.PAYMENT_DETAIL where STATUS = 'ADJUST' AND PROFILEID ='$pid' AND ENTRY_DT >= DATE_SUB('$curdate', INTERVAL 7 DAY) ORDER BY RECEIPTID desc";
                $result = mysql_query_decide($sql) or logError_sums($sql,0);
                if(mysql_num_rows($result) >= 1)
                {
			$smarty->assign("RECENTLY_UPDATED","1");

                        $myrow = mysql_fetch_array($result);
                        $dt = explode("-",$myrow["ENTRY_DT"]);
                        $date_str = my_format_date($dt[2],$dt[1],$dt[0]);
			$smarty->assign("date_str",$date_str);
			$smarty->assign("cid",$cid);
                        $smarty->display("upgrade_billing.htm");
                }
		else
			$smarty->display('upgrade_billing.htm');
	}
	/*End of - When no button is clicked (or this php is called by clicking on Upgrade/degrade button form search page)*/
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
