<?php
include_once("../jsadmin/connect.inc");
include_once("../profile/pg/functions.php");
include_once("comfunc_sums.php");
$data=authenticated($cid);
$flag=0;
global $DOL_CONV_RATE;

if(isset($data))
{
	maStripVARS_sums('stripslashes');

	//populate mode of payment.
	$pay_mode = mode_of_payment();
	$smarty->assign("pay_mode",$pay_mode);

	//populate mode of payment.
	$from_source_arr = populate_from_source();
	$smarty->assign("from_source_arr",$from_source_arr);

	//populate deposit branches.
	$dep_branch_arr = get_deposit_branches();
	$smarty->assign("dep_branch_arr",$dep_branch_arr);

	//populate banks.
	$bank = get_banks();
	$smarty->assign("bank",$bank);

	//populate days, months and years.
        $ddarr = get_days();
        $mmarr = get_months();
        $yyarr = get_years();
        $smarty->assign("ddarr",$ddarr);
        $smarty->assign("mmarr",$mmarr);
        $smarty->assign("yyarr",$yyarr);

	//to set current day month and year preselected.
        list($cur_year,$cur_month,$cur_day)=explode("-",date('Y-m-d'));
	$smarty->assign("cur_day",$cur_day);
	$smarty->assign("cur_month",$cur_month);
	$smarty->assign("cur_year",$cur_year);

	//to show logged in user's center preselected.
	$center=getcenter_for_walkin($user);
	$smarty->assign("dep_branch",$center);
	$smarty->assign("TAX_RATE",$TAX_RATE);

	$user=getname($cid);
	//when submit button is clicked.
	if($CMDSubmit)
        {
		//server side validations.
		$is_error=0;
		$arr_trans = array_for_trans_num();
		if($mode=="")
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
		if($mode != '')
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
		if($mode=="CCOFFLINE")
		{
			if(trim($cdnum)=='')
                        {
                                $is_error++;
                                $smarty->assign("CHECK_CDNUM","Y");
                        }
		}
		if(trim($tds)=='')
                {
                        $is_error++;
                        $smarty->assign("CHECK_TDS","Y");
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
		$due_amt_new=$due_amt-($amount+$tds);
		if($due_amt_new>0)
		{
			if($due_day=='' || $due_month=='' || $due_year=='' )
			{
				$is_error++;
                                $smarty->assign("CHECK_DUEDATE","Y");
			}
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
				$dol_conv_rate = $DOL_CONV_RATE;
			}

			$cd_dt=$cd_year."-".$cd_month."-".$cd_day;
			$dep_dt=$dep_year."-".$dep_month."-".$dep_day;

			if($due_amt_new < 0)
				$due_amt_new = 0;

			if($due_amt_new > 1)
				$due_dt_new=$due_year."-".$due_month."-".$due_day;

			$transaction_number = addslashes(stripslashes($transaction_number));

			if($Bank == "Other")
			{
				$Bank = addslashes(stripslashes($obank));
				$obank = "Y";
			}
			if($shipPIN=="")
				$shipPIN=0;
			if($billPIN=="")
                                $billPIN=0;
			if($billName=='' && $billPIN=='' && $billAddress=='' && $billEmail=='' && $billPhone=='' && $billCountry=='')
                        {
                                $billName=$comp_name;
                                $billPIN=$billPIN1;
                                $billAddress=$billAddress1;
                                $billEmail=$billEmail1;
                                $billPhone=$billPhone1;
                                $billCountry=$billCountry1;
                        }

			//insert payment details
			$sql_i="INSERT INTO billing.REV_PAYMENT(STATUS,MODE,TYPE,SALEID,AMOUNT,TDS,CD_DT,CD_NUM,CD_CITY,BANK,OBANK,REASON,ENTRY_DT,ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,SOURCE,TRANS_NUM,DOL_CONV_RATE,BILL_TO_NAME,BILL_TO_ADDRESS,BILL_TO_PIN,BILL_TO_COUNTRY,BILL_TO_PHONE,BILL_TO_EMAIL,SERVICE_TAX) VALUES('DONE','$mode','$curtype','$billid','$amount','$tds','$cd_dt','$cdnum','$cd_city','$Bank','$obank','".addslashes(stripslashes($comment))."',now(),'$user','$dep_dt','$dep_branch','$from_source','$transaction_number','$dol_conv_rate','$billName','$billAddress','$billPIN','$billCountry','$billPhone','$billEmail','$service_tax1')";
			$res=mysql_query_decide($sql_i) or logError_sums($sql_i,1);
				
			$sql="UPDATE billing.REV_MASTER SET DUEAMOUNT='$due_amt_new',DUE_DT='$due_dt_new' WHERE SALEID='$billid'";
			mysql_query_decide($sql) or logError_sums($sql,1);
				
			if( $category=='marriage_bureau' && $bureauprofileid>0 )
			{
				$money_add=ceil($amount*($sale_amt/$total_amt));

				$sql="UPDATE marriage_bureau.BUREAU_PROFILE set MONEY=MONEY+'$money_add' WHERE PROFILEID='$bureauprofileid' ";
				mysql_query_decide($sql) or logError_sums($sql,1);
			}

			$smarty->assign("cid",$cid);
			$smarty->assign("criteria","billid");
			$smarty->assign("phrase",get_rev_saleid($billid));
			$smarty->assign("successful_entry",1);
			$smarty->display("misc_resources_billing.htm");
		}
		else
		{
			$smarty->assign("comp_name",$comp_name);
			$smarty->assign("sale_des",$sale_des);
			$smarty->assign("sale_amt",$sale_amt);
			$smarty->assign("total_amt",$total_amt);
			$smarty->assign("service_tax",$service_tax);
			$smarty->assign("tds",$tds);

			$smarty->assign("dep_branch",$dep_branch);
			$smarty->assign("dep_day_sel",$dep_day);
			$smarty->assign("dep_month_sel",$dep_month);
			$smarty->assign("dep_year_sel",$dep_year);
			$smarty->assign("COMMENT",$comment);
			$smarty->assign("MODE",$mode);	
			$smarty->assign("from_source",$from_source);
			$smarty->assign("transaction_number",$transaction_number);
			$smarty->assign("CURTYPE",$curtype);	
			$smarty->assign("AMOUNT",$amount);	
			$smarty->assign("due_amt",$due_amt);
			$smarty->assign("due_day_sel",$due_day);	
			$smarty->assign("due_month_sel",$due_month);	
			$smarty->assign("due_year_sel",$due_year);	
			$smarty->assign("CDNUM",$cdnum);	
			$smarty->assign("CD_DAY",$cd_day);	
			$smarty->assign("CD_MONTH",$cd_month);	
			$smarty->assign("CD_YEAR",$cd_year);	
			$smarty->assign("CD_CITY",$cd_city);	
			$smarty->assign("OVERSEAS",$overseas);	
			$smarty->assign("SEPARATEDS",$separateds);	
			$smarty->assign("BANK",$Bank);	
			$smarty->assign("OBANK",$obank);
			$smarty->assign("CID",$cid);
			$smarty->assign("USER",$user);
			$smarty->assign("val",$val);
			$smarty->assign("flag",$flag);
			$smarty->assign("uname",$uname);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("category",$category);
			$smarty->assign("bureauprofileid",$bureauprofileid);
			$smarty->assign("billid",$billid);
			$smarty->assign("subs",$subs);
			$smarty->display("misc_resources_billing.htm");
		}	
	}
        else
        {
		//query to find find bill details	
		$sql = "SELECT * from billing.REV_MASTER where SALEID='$saleid'";
		$res = mysql_query_decide($sql) or logError_sums($sql,0);
		while($row = mysql_fetch_array($res))
                {
			$comp_name=$row["COMP_NAME"];
			$sale_des=$row["SALE_DES"];
			if($row["CUR_TYPE"]=='RS')
				$curtype='0';
			else
				$curtype='1';
			$sale_amt=$row["SALE_AMT"];
			$service_tax=$row["SERVICE_TAX"];
			$total_amt=$row["TOTAL_AMT"];
			$due_amt = $row["DUEAMOUNT"];
			$category=$row["CATEGORY"];
			$bureauprofileid=$row["BUREAU_PID"];
			$shipAddress=$row["SHIP_TO_ADDRESS"];
			$shipCountry=$row["SHIP_TO_COUNTRY"];
			$shipEmail=$row["SHIP_TO_EMAIL"];
			$shipPIN=$row["SHIP_TO_PIN"];
			$shipPhone=$row["SHIP_TO_PHONE"];
		}		

                $smarty->assign("billid",$saleid);	
		$smarty->assign("USER",$user);
                $smarty->assign("CID",$cid);
		$smarty->assign("comp_name",$comp_name);
		$smarty->assign("sale_des",$sale_des);
		$smarty->assign("sale_amt",$sale_amt);
		$smarty->assign("total_amt",$total_amt);
		$smarty->assign("service_tax",$service_tax);
		$smarty->assign("due_amt",$due_amt);
		$smarty->assign("CURTYPE",$curtype);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("category",$category);
		$smarty->assign("bureauprofileid",$bureauprofileid);
		$smarty->assign("shipAddress",$shipAddress);
		$smarty->assign("shipCountry",$shipCountry);
		$smarty->assign("shipEmail",$shipEmail);
		$smarty->assign("shipPIN",$shipPIN);
		$smarty->assign("shipPhone",$shipPhone);
	        $smarty->display("misc_resources_billing.htm");
        }
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
