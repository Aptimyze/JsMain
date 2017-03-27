<?php
include_once("../jsadmin/connect.inc");
include_once("../profile/pg/functions.php");
include_once("comfunc_sums.php");
global $DOL_CONV_RATE;
$ip=FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

if(authenticated($cid))
{
	maStripVARS_sums('stripslashes');
	$entryby=getuser($cid);
	/*When button is pressed from page 2 (don't get confused due to name of button)*/
	if($pg1_submit)
	{
		if($walkin=="OFFLINE" || $walkin=="ARAMEX")
                {
                        $walkin_center="HO";
                        $email_walkin="mahesh@jeevansathi.com";
                }
                else
                {
                        $sql="SELECT EMAIL,CENTER from jsadmin.PSWRDS where USERNAME='$walkin'";
                        $result = mysql_query_decide($sql) or logError_sums($sql,0);
                        $myrow=mysql_fetch_array($result);
                        $walkin_center=$myrow['CENTER'];
                        $email_walkin=$myrow['EMAIL'];
                }

		$is_error = 0;
		$arr_trans = array_for_trans_num();

		if(!$degrade)
		{
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
			if(trim($mode)=="")
			{
				$is_error++;
				$smarty->assign("CHECK_MODE","Y");
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
				if(trim($Bank)=="")
				{
					$is_error++;
					$smarty->assign("CHECK_BANK","Y");
				}
				elseif($Bank=="Other")
				{
					if(trim($obank)=="")
					{
						$is_error++;
						$smarty->assign("CHECK_OBANK","Y");
					}
				}
				/*check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
				$arr1 = explode("-",date('Y-m-d'));
				list($y,$m,$d) = $arr1;
				$current_timestamp = mktime(0,0,0,$m,$d,$y);
				$entered_timestamp= mktime(0,0,0,$cd_month,$cd_day,$cd_year);
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
				/*End of - check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
			}
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
		}
		if($is_error==0)
		{
			$dep_date = $dep_year."-".$dep_month."-".$dep_day;
			$cd_date = $cd_year."-".$cd_month."-".$cd_day;
			$due_date = $due_year."-".$due_month."-".$due_day;

			$service_change = 1;
			$mtype = $main_service_id;
			$mtype = str_replace("'","",$mtype);

			$sql = "Select c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$mtype'";
			$result_services = mysql_query_decide($sql) or logError_sums($sql,0);
			$myrow_services = mysql_fetch_array($result_services);
			$duration = $myrow_services['DURATION'];

			if(count($addon_services))
				$addon_services = explode(",",$addon_services);

			if(!$degrade)
			{
				$sql = "Select SERVICEID, ADDON_SERVICEID from billing.PURCHASES where PROFILEID = '$pid' and BILLID='$billid'";
				$result = mysql_query_decide($sql) or logError_sums($sql,0);
				$myrow = mysql_fetch_array($result);
				
				if($myrow['SERVICEID'] == $mtype)
				{
					if( $myrow['ADDON_SERVICEID'] == '' && (!isset($addon_services)))
						$service_change = 0;					
					if($myrow['ADDON_SERVICEID'] != '' && (isset($addon_services)))	
					{

						$sql = "Select c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$mtype'";
						$result_services = mysql_query_decide($sql) or logError_sums($sql,0);
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
				if($service_change == "0")
				{
					$smarty->assign("SERVICE_NOT_CHANGED","1");
					$smarty->display("upgrade_insert.htm");
				}
			}
			if($degrade)
			{
				$smarty->assign("degrade","1");
			}
			
			$sql_rect_id = "SELECT MAX(RECEIPTID) as  RECEIPTID FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid'";
			$res_rect_id = mysql_query_decide($sql_rect_id) or logError_sums($sql_rect_id,0);
			$row_rect_id = mysql_fetch_array($res_rect_id);
			$rect_id = $row_rect_id['RECEIPTID'];


			$sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$mtype'";
			$result = mysql_query_decide($sql) or logError_sums($sql,0);
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
				$result = mysql_query_decide($sql) or logError_sums($sql,0);
				while($myrow = mysql_fetch_array($result))
					$subscription_ar[] = $myrow["RIGHTS"];
			}
			if(is_array($subscription_ar))
					$subscription = implode(",",$subscription_ar);
			/*$sql_update = "UPDATE newjs.JPROFILE SET SUBSCRIPTION = '$subscription' WHERE PROFILEID = '$pid'";
			mysql_query_decide($sql_update) or logError_sums($sql_update,1);*/
			$jprofileObj =JProfileUpdateLib::getInstance();
			$paramArr    =array("SUBSCRIPTION"=>$subscription);
			$jprofileObj->editJPROFILE($paramArr,$pid,'PROFILEID');

			if($curtype==0)
				$curtype="RS";
			else
			{
				$curtype="DOL";
				$dol_conv_rate = $DOL_CONV_RATE;
			}
			$discount=round(($discount*100)/(100+$tax_rate),2);

			$transaction_number = addslashes(stripslashes($transaction_number));

			if($dueamt<0)
				$dueamt=0;
			$sql = "INSERT into billing.PURCHASES(PROFILEID,SERVICEID,USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE, CUR_TYPE,WALKIN,CENTER,ENTRYBY,DISCOUNT,DISCOUNT_TYPE,DISCOUNT_REASON,DUEAMOUNT,DUEDATE,ENTRY_DT,STATUS,SERVEFOR,ADDON_SERVICEID,TAX_RATE,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD) SELECT PROFILEID,'$mtype',USERNAME,NAME,ADDRESS,GENDER,CITY,PIN,EMAIL,RPHONE,OPHONE,MPHONE,'$curtype', '$walkin', '$walkin_center', '$entryby','$discount','$discount_type','".addslashes(stripslashes($reason))."','$dueamt','$due_date',now(),'DONE','$subscription','$addon_serviceid','$tax_rate','$dep_date','$dep_branch','$ip' FROM billing.PURCHASES WHERE BILLID = '$billid'";
			$res = mysql_query_decide($sql) or logError_sums($sql,1);

			$billid_new = mysql_insert_id_js();
			$serviceid = $mtype;

			$sql="SELECT PACKAGE,COMPID,PACKID from billing.SERVICES where SERVICEID='$serviceid'";
			$result=mysql_query_decide($sql) or logError_sums($sql,0);
			$myrow=mysql_fetch_array($result);
			if($myrow['PACKAGE']!="Y")
			{
				$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
				$result1 = mysql_query_decide($sql) or logError_sums($sql,0);
				$myrow1=mysql_fetch_array($result1);
															     
				$sql="INSERT into billing.SERVICE_STATUS (BILLID, PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, EXPIRY_DT,ACTIVATED_BY) values ('$billid__new','$pid','$serviceid','$myrow[COMPID]','Y',now(),ADDDATE('$stdate', INTERVAL $myrow1[DURATION] MONTH),'$entryby')";
				mysql_query_decide($sql) or logError_sums($sql,1);
			}
			elseif($myrow['PACKAGE']=="Y")
			{
				$packid=$myrow['PACKID'];
				$sql="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";
				$result=mysql_query_decide($sql) or logError_sums($sql,0);
				while($myrow1=mysql_fetch_array($result))
				{
					$comp_arr[]=$myrow1["COMPID"];
				}
				if(is_array($comp_arr))
					$comp_str=implode(",",$comp_arr);
				else
					$comp_str=$comp_arr;
				$sql="SELECT DURATION from billing.COMPONENTS where COMPID='$comp_arr[0]'";
				$result2 = mysql_query_decide($sql) or logError_sums($sql,0);
				$myrow2=mysql_fetch_array($result2);

				$sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, EXPIRY_DT, ACTIVATED_BY)values ('$billid_new','$pid','$serviceid','$comp_str','Y',now(),ADDDATE('$stdate', INTERVAL $myrow2[DURATION] MONTH), '$entryby')";
				mysql_query_decide($sql) or logError_sums($sql,1);
															     
			}
			$sql="SELECT SUM(AMOUNT) AS AMOUNT FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid'";
			$res = mysql_query_decide($sql) or logError_sums($sql,0);
			$row = mysql_fetch_array($res);

 			$sql = "INSERT INTO billing.PAYMENT_DETAIL (PROFILEID, BILLID, MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, BANK, OBANK, REASON, STATUS, BOUNCE_DT, ENTRY_DT, ENTRYBY, DEL_REASON, MAIL_TYPE, DEPOSIT_DT, DEPOSIT_BRANCH, IPADD, COLLECTED, COLLECTED_BY, COLLECTION_DATE,SOURCE,TRANS_NUM,DOL_CONV_RATE) SELECT PROFILEID, '$billid_new', 'OTHER', TYPE, '$row[AMOUNT]',CD_NUM, CD_DT, CD_CITY, BANK, OBANK, 'Adjusted against billid $billid', 'ADJUST', BOUNCE_DT, now(), '$entryby',DEL_REASON, MAIL_TYPE, DEPOSIT_DT, DEPOSIT_BRANCH, '$ip', COLLECTED, COLLECTED_BY, COLLECTION_DATE, SOURCE, TRANS_NUM,'$dol_conv_rate' FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid' LIMIT 1";
			$res = mysql_query_decide($sql) or logError_sums($sql,1);

			if(!$degrade)
			{
				if($Bank=="" || $Bank=="Other")
				{
					$bankfeed=$obank;
					$obank="Y";
				}
				else
				{
					$bankfeed=$Bank;
					$obank="N";
				}
				$sql_again = "INSERT INTO billing.PAYMENT_DETAIL (PROFILEID, BILLID, MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, BANK, OBANK, REASON, STATUS, ENTRY_DT, ENTRYBY, DEPOSIT_DT, DEPOSIT_BRANCH, IPADD,SOURCE,TRANS_NUM,DOL_CONV_RATE) VALUES ('$pid','$billid_new','$mode','$curtype','$amount','$cdnum','$cd_date','$cd_city','".addslashes(stripslashes($bankfeed))."','$obank','".addslashes(stripslashes($comment))."','DONE',now(), '$entryby','$dep_date','$dep_branch','$ip','$from_source','$transaction_number','$dol_conv_rate')";
				$res_again = mysql_query_decide($sql_again) or logError_sums($sql_again,1);
			}

			$sql_cont="DELETE from newjs.CONTACTS_STATUS where PROFILEID ='$pid'";
	                mysql_query_decide($sql_cont) or logError_sums($sql_cont,0);

			$smarty->assign("cid",$cid);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("phrase",$phrase);
			$smarty->display("upgrade_insert.htm");
		}
		else
		{	
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
			$ddarr = get_days();
			$mmarr = get_months();
			$yyarr = get_years();
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);

			$bank_arr = get_banks();
			$smarty->assign("bank_arr",$bank_arr);

			$dep_branch_arr = get_deposit_branches();
			$smarty->assign("dep_branch_arr",$dep_branch_arr);

			$pay_mode = mode_of_payment();
			$smarty->assign("pay_mode",$pay_mode);

			$smarty->assign("username",$username);
			$smarty->assign("status",$status);
			$smarty->assign("email",$email);
			$smarty->assign("sname",$sname);
			$smarty->assign("addon_services_lastbill",$addon_services_lastbill);
			$smarty->assign("expiry_dt",$expirty_dt);
			$smarty->assign("billid",$billid);
			$smarty->assign("curtype",$curtype);
			$smarty->assign("curtype_disp",$curtype_disp);
			$smarty->assign("stdate",$stdate);
			$smarty->assign("service_name",$service_name1);
			$smarty->assign("addons",$addons);
			$smarty->assign("duration_sel",$duration_sel);
			$smarty->assign("walkin",$walkin);
			$smarty->assign("discount",$discount);
			$smarty->assign("disc_type",$disc_type1);
			$smarty->assign("discount_type1",$discount_type1);
			$smarty->assign("reason",$reason);
			$smarty->assign("price",$price);
			$smarty->assign("tax_rate",$tax_rate);
			$smarty->assign("tax",$tax);
			$smarty->assign("total_pay",$total_pay);
			$smarty->assign("main_service_id",$main_service_id);
			$smarty->assign("addon_services",$cid);
			$smarty->assign("cid",$cid);
			$smarty->assign("pid",$pid);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("user",$user);
			$smarty->assign("source",$source);
			$smarty->assign("crm_id",$crm_id);
			$smarty->assign("degrade",$degrade);

			$smarty->assign("comment",$comment);
			$smarty->assign("mode",$mode);
			$smarty->assign("amount",$amount);
			$smarty->assign("cd_day",$cd_day);
			$smarty->assign("cd_month",$cd_month);
			$smarty->assign("cd_year",$cd_year);
			$smarty->assign("cd_city",$cd_city);
			$smarty->assign("Bank",$Bank);
			$smarty->assign("obank",$obank);
			$smarty->assign("overseas",$overseas);
			$smarty->assign("separateds",$separateds);
			$smarty->assign("due_day",$due_day);
			$smarty->assign("due_month",$due_month);
			$smarty->assign("due_year",$due_year);
			$smarty->assign("dep_day",$due_day);
			$smarty->assign("dep_month",$due_month);
			$smarty->assign("dep_year",$due_year);
			$smarty->assign("dep_branch",$dep_branch);
			
			$smarty->display("upgrade_paydet_billing.htm");
		}	
	}
	/*End of - When button is pressed from page 2 (don't get confused due to name of button)*/
}
else
{
	$smarty->display("jsconnectError.tpl");
}
