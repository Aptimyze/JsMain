<?php
include("../jsadmin/connect.inc");
include("comfunc_sums.php");
$data=authenticated($cid);
$flag=0;

if(isset($data))
{
	maStripVARS_sums('stripslashes');
	//populate miscellaneous category.
	$misc_category = populate_misc_category();
	$smarty->assign("misc_category",$misc_category);

	//populate days, months and years.
	$ddarr = get_days();
	$mmarr = get_months();
	$yyarr = get_years();
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);

	//to set current day month and year preselected.
	list($cur_year,$cur_month,$cur_day)=explode("-",date('Y-m-d'));
	$smarty->assign("cur_year",$cur_year);
	$smarty->assign("cur_month",$cur_month);
	$smarty->assign("cur_day",$cur_day);

	//populate sale by.
	$employee = populate_misc_saleby();
	$smarty->assign("employee",$employee);

        //populate sale type.
        $sale_type = populate_misc_saletype();
        $smarty->assign("sale_type",$sale_type);

	$reasondisc=populate_discount_type();
	$smarty->assign("reasondisc",$reasondisc);
	$user=getname($cid);

	$smarty->assign("user",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("TAX_RATE",$TAX_RATE);

	//when submit button is clicked.
	if($CMDSubmit)
        {
		//server side validation.
		$is_error=0;
		if(trim($comp_name)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_COMPANY","Y");
                }
		if(trim($shipAddress)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_ADDRESS","Y");
                }
		if(trim($shipCountry)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_COUNTRY","Y");
                }	
		if(trim($shipPIN)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_PIN","Y");
                }
		if(trim($shipPhone)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_PHONE","Y");
                }
		if(trim($shipEmail)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_EMAIL","Y");
                }
		if(trim($sale_by)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_SALE_BY","Y");
                }
		if(trim($sale_des)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_SALE_DES","Y");
                }
		if($curtype == '')
		{
			$is_error++;
			$smarty->assign("CHECK_CURTYPE","Y");
		}
		if(trim($sale_amt)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_SALE_AMT","Y");
                }
		if(trim($discount)!='' && trim($discreason) == "" && trim($discount) != 0)
                {
                        $is_error++;
                        $smarty->assign("CHECK_DISC_REASON","Y");
                }
		if(trim($tot_amount)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_TOTAL","Y");
                }
		if(trim($no_tax_res)=='' && trim($service_tax1)=='0' && $curtype=='0')
                {
                        $is_error++;
                        $smarty->assign("CHECK_TAX_RES","Y");
                }
		if(trim($start_day) == "" || trim($start_month) == "" || trim($start_month) == "")
		{
			$is_error++;
			$smarty->assign("CHECK_END_DATE","Y");
		}
		if(trim($end_day) == "" || trim($end_month) == "" || trim($end_month) == "")
		{
			$is_error++;
			$smarty->assign("CHECK_END_DATE","Y");
		}
		//if no error is found.
		if($is_error==0)
                {

			$flag=1;

			if($curtype==0)
			{
				$curtype='RS';
			}
			elseif($curtype==1)
			{
				$curtype='DOL';
			}
			$due_dt=$yy."-".$mm."-".$dt;
			$comp_name=addslashes(stripslashes($comp_name));
			$sale_des=addslashes(stripslashes($sale_des));
			$sale_by=addslashes(stripslashes($sale_by));
			$no_tax_res=addslashes(stripslashes($no_tax_res));
			$discreason = addslashes(stripslashes($discreason));

			$due_amount = $tot_amount;

			if($due_amount < 0)
				$due_amount = 0;

			$start_date = $start_year."-".$start_month."-".$start_day;
			$end_date = $end_year."-".$end_month."-".$end_day;
			if($curtype=='RS' && $service_tax1>0)
				$service_tax_content ="(Inclusive of Swachh Bharat Cess and Krishi Kalyan Cess)";
			elseif($curtype=='DOL')
				$service_tax_content ="(Inclusive of Swachh Bharat Cess and Krishi Kalyan Cess)";
			//insert sale details.
			$sql_i = "INSERT INTO billing.REV_MASTER(COMP_NAME,SALE_DES,CUR_TYPE,SALE_AMT,SERVICE_TAX,DISCOUNT,DISCOUNT_REASON,TOTAL_AMT,SALE_BY,ENTRY_DT,ENTRY_BY,TAX_RATE,NO_TAX_RES,DUEAMOUNT,DUE_DT,CATEGORY,BUREAU_PID,START_DATE,END_DATE,SALE_TYPE,SHIP_TO_ADDRESS,SHIP_TO_PIN,SHIP_TO_COUNTRY,SHIP_TO_PHONE,SHIP_TO_EMAIL,SERVICE_TAX_CONTENT) VALUES('$comp_name','$sale_des','$curtype','$sale_amt','$service_tax1','$discount','$discreason','$tot_amount','$sale_by',now(),'$user','$TAX_RATE','$no_tax_res','$due_amount','$due_dt','$category','$bureauprofileid','$start_date','$end_date','$sale_type_sel','$shipAddress','$shipPIN','$shipCountry','$shipPhone','$shipEmail','$service_tax_content')";
			$res=mysql_query_decide($sql_i) or logError_sums($sql_i,1);
			$id=mysql_insert_id_js();
			$ref_id="JR-".$id;
			if($category=='banners')
			{
				$BMS_URL = JsConstants::$bmsUrl;
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$BMS_URL/bmsjs/bms_booking.php?id=$cid&ref_id=$ref_id&js_flag=Y\"></body></html>";
				exit;
	                }
			else
			{
				$smarty->assign("phrase",$ref_id);
				$smarty->assign("criteria","billid");
				$smarty->assign("successful_entry",1);
				$smarty->display("misc_resources_entry.htm");
			}

		}
		else
		{
			$smarty->assign("dt",$dt);
			$smarty->assign("mm",$mm);
			$smarty->assign("yy",$yy);
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);
			$smarty->assign("comp_name",$comp_name);
			$smarty->assign("sale_by",$sale_by);
			$smarty->assign("sale_des",$sale_des);
			$smarty->assign("CURTYPE",$curtype);
			$smarty->assign("sale_amt",$sale_amt);
			$smarty->assign("service_tax",$service_tax1);
			$smarty->assign("discount",$discount);
			$smarty->assign("discreason",$discreason);
			$smarty->assign("tot_amount",$tot_amount);
			$smarty->assign("no_tax_res",$no_tax_res);
			$smarty->assign("bureauprofileid",$bureauprofileid);
			$smarty->assign("category","$category");
			$smarty->assign("TAX_RATE","$TAX_RATE");
			$smarty->display("misc_resources_entry.htm");	
		}
	}
	else
        {
		if($bureauprofileid>0)
		{
			$sql="SELECT USERNAME FROM marriage_bureau.BUREAU_PROFILE where PROFILEID='$bureauprofileid' ";
			$res = mysql_query_decide($sql) or logError_sums($sql,0);
			while($row=mysql_fetch_array($res))
			{
				$comp_name=$row['USERNAME'];
			}
			$smarty->assign("bureauprofileid",$bureauprofileid);
			$smarty->assign("category","marriage_bureau");
		}
		$smarty->assign("shipEmail",$shipEmail);
		$smarty->assign("shipPIN",$shipPIN);
		$smarty->assign("shipCountry",$shipCountry);
		$smarty->assign("shipPhone",$shipPhone);	
		$smarty->assign("comp_name",$comp_name);
		$smarty->assign("shipAddress",$shipAddress);
	        $smarty->display("misc_resources_entry.htm");
        }
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->display("jsconnectError.tpl");
}
?>
