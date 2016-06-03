<?php
include("connect.inc");
include("pg/functions.php");

$ip=FetchClientIP();
if(strstr($ip, ","))    
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}
$db=connect_db();
$data=authenticated($checksum);
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
//$smarty->assign("HEAD",$smarty->fetch("headold_payment.htm"));
//$smarty->assign("CURRENTUSERNAME",$data['USERNAME']);
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelold_payment.htm"));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));


/*
$data=authenticated($cid);
$flag=0;
*/
$flag = 0;
if(isset($data))
{
	$user=getname($cid);
	if($CMDSubmit || $submit_on_enter)
        {
		$is_error=0;
		$checkmphone = checkmphone($MOB_NO);
		if($checkmphone==1 || trim($MOB_NO)=='' || strlen(trim($MOB_NO)) < 10)
		{
			$is_error++;
			$smarty->assign("CHECK_MOB","Y");
		}
		if(trim($amount)=='')
		{
			$is_error++;
			$smarty->assign("CHECK_AMOUNT","Y");
		}
		if(trim($cdnum)=='' || !is_numeric(trim($cdnum)))
		{
			$is_error++;
			$smarty->assign("CHECK_CDNUM","Y");
		}
		if($cd_day=='' || $cd_month=='' || $cd_year=='' || !checkdate($cd_month,$cd_day,$cd_year))
		{
			$is_error++;
			$smarty->assign("CHECK_CDDATE","Y");
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
		/*end of - check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
		if(trim($cd_city)=='')
		{
			$is_error++;
			$smarty->assign("CHECK_CDCITY","Y");
		}
		if(trim($Bank)=='')
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
			$Bank = $obank;
		}
		$cd_date=$cd_year."-".$cd_month."-".$cd_day;

		if($is_error==0)
		{
			$flag=1;
			$cd_dt=$cd_year."-".$cd_month."-".$cd_day;
			if ($obank)
				$OBANK = 'Y';
			else
				$OBANK = 'N';
			$sql = "INSERT INTO billing.CHEQUE_REQ_DETAILS (PROFILEID,REQUEST_ID,TYPE,AMOUNT,CD_DT,CD_NUM,CD_CITY,BANK,OBANK,STATUS,ENTRY_DT,ENTRYBY,IPADD) VALUES('$PROFILEID','$REQUESTID','$curtype','$amount','$cd_dt','$cdnum','$cd_city','$Bank','$OBANK','PENDING',NOW(),'USER','$ip')";
			mysql_query_decide($sql) or die(mysql_error_js()); //logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");

			$sql = "UPDATE incentive.PAYMENT_COLLECT SET PICKUP_TYPE='ICICI_CHEQUE',COMMENTS='".addslashes(stripslashes($COMMENTS))."' , PHONE_MOB ='".addslashes(stripslashes($MOB_NO))."' WHERE ID='$REQUESTID'";
			mysql_query_decide($sql) or die(mysql_error_js()); //logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");

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
			$smarty->assign("CID",$cid);
			$smarty->assign("USER",$user);
			$smarty->assign("val",$val);
			$smarty->assign("flag",$flag);
			$smarty->assign("uname",$uname);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);

			for($i=0;$i<31;$i++)
                        {
                                $ddarr[$i]=$i+1;
                        }
                        for($i=0;$i<12;$i++)
                        {
                                $mmarr[$i]=$i+1;
                        }
                        for($i=0;$i<10;$i++)
                        {
                                $yyarr[$i]=$i+2005;
                        }
                        $smarty->assign("ddarr",$ddarr);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("yyarr",$yyarr);

			//$smarty->display("refund_paypart.htm");
			$smarty->display("cheque_deposit.htm");
		}
		else
		{
			$sql="SELECT NAME FROM billing.BANK";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			$i=0;
			while($row=mysql_fetch_array($res))
			{
				$bank[$i]=$row['NAME'];
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

			$smarty->assign("dep_branch_arr",$dep_branch_arr);
                        $smarty->assign("dep_branch",$dep_branch);
                        $smarty->assign("DEP_DAY",$dep_day);
                        $smarty->assign("DEP_MONTH",$dep_month);
                        $smarty->assign("DEP_YEAR",$dep_year);
			

			
			$smarty->assign("EMAIL",$EMAIL);
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("REQUESTID",$REQUESTID);
			$smarty->assign("MAIN_SER_NAME",$MAIN_SER_NAME);
			$smarty->assign("ADDON_SERVICES",$ADDON_SERVICES);
			$smarty->assign("PROFILEID",$PROFILEID);
			//$smarty->assign("AMOUNT",$AMOUNT);

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
			$smarty->assign("BANK",$Bank);	
			$smarty->assign("OBANK",$obank);
			$smarty->assign("CID",$cid);
			$smarty->assign("USER",$user);
			$smarty->assign("val",$val);
			$smarty->assign("flag",$flag);
			$smarty->assign("uname",$uname);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);
			$smarty->assign("subs",$subs);
			$smarty->assign("bank",$bank);	
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("MOB_NO",$MOB_NO);
			for($i=0;$i<31;$i++)
                        {
                                $ddarr[$i]=$i+1;
                        }
                        for($i=0;$i<12;$i++)
                        {
                                $mmarr[$i]=$i+1;
                        }
                        for($i=0;$i<10;$i++)
                        {
                                $yyarr[$i]=$i+2005;
                        }
                        $smarty->assign("ddarr",$ddarr);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("yyarr",$yyarr);


			if($show=="makepaid")
			{
				$smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
				$smarty->assign("REF_AMOUNT",$ref_amount);
				$smarty->assign("UPGRADE_PRICE",$upgrade_price);
				$smarty->assign("PAIDAMOUNT",$paidamount);
				$smarty->assign("PRICENEW",$pricenew);
				$smarty->display("makepaid_refund_paypart.htm");
			}
			else
				$smarty->display("cheque_deposit.htm");
		}	
	}
        else
        {
		/*$sql_s="SELECT DUEAMOUNT FROM billing.PURCHASES WHERE BILLID='$billid'";
		$res_s=mysql_query_decide($sql_s) or die(mysql_error_js());
		$row=mysql_fetch_array($res_s);
		$dueamt=$row['DUEAMOUNT'];
		if($val=="refund")
		{
			if($dueamt>0)
			{
				$smarty->assign("phrase",$phrase);
				$smarty->assign("criteria",$criteria);
				$smarty->assign("billid",$billid);
				$smarty->assign("CID",$cid);
				$smarty->assign("USER",$user);
				$smarty->assign("DUEG0","Y");
				$smarty->display("refund_paypart.htm");
				die;
			}
		}
		$sql="SELECT PROFILEID FROM billing.PURCHASES WHERE BILLID='$billid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$profileid=$row['PROFILEID'];

		$sql="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$subs=$row['SUBSCRIPTION'];
		if($subs=="")
		{
			$subs="EMPTY";
		}*/

		$sql = "SELECT PROFILEID , USERNAME , EMAIL , SERVICE , ADDON_SERVICEID , AMOUNT , CUR_TYPE FROM incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row = mysql_fetch_array($result);
		
		$main_ser_name=service_name($row['SERVICE']);
		if($row["ADDON_SERVICEID"])
		{
			$addon_serviceid = $row["ADDON_SERVICEID"];
			$addon_serviceid_ar = explode(",",$addon_serviceid);
			for($j=0;$j<count($addon_serviceid_ar);$j++)
				$addon_serviceid_ar[$j]="'".$addon_serviceid_ar[$j]."'";
			$addon_serviceid_str = implode(",",$addon_serviceid_ar);
														    
			$sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
			$result_services = mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow_result_services = mysql_fetch_array($result_services))
			{
				$add_on_services[] = "<br>".$myrow_result_services["NAME"];
			}
		}

		if(count($add_on_services) > 0)
			$addon_service_names = implode(",",$add_on_services);

		$smarty->assign("EMAIL",$row['EMAIL']);
		$smarty->assign("USERNAME",$row['USERNAME']);
		$smarty->assign("REQUESTID",$REQUESTID);
		$smarty->assign("MAIN_SER_NAME",$main_ser_name);
		$smarty->assign("ADDON_SERVICES",$addon_service_names);
		$smarty->assign("CURTYPE",$row['CUR_TYPE']);
		$smarty->assign("AMOUNT",$row['AMOUNT']);
		$smarty->assign("PROFILEID",$row['PROFILEID']);

		$sql="SELECT NAME FROM billing.BANK";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$bank[$i]=$row['NAME'];
			$i++;
		}

		if($val=="paypart")
		{
			$smarty->assign("PAY","Y");
		}

		//$center=strtoupper(getcenter_for_walkin($user));
                $sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $dep_branch_arr[$i]=$row['NAME'];
                        $i++;
                }
                $dd_arr=explode("-",Date('Y-m-d'));
                $smarty->assign("DEP_DAY",$dd_arr[2]);
                $smarty->assign("DEP_MONTH",$dd_arr[1]);
                $smarty->assign("DEP_YEAR",$dd_arr[0]);
                $smarty->assign("dep_branch",$center);
                $smarty->assign("dep_branch_arr",$dep_branch_arr);

		$smarty->assign("USER",$user);
		$smarty->assign("val",$val);
		$smarty->assign("uname",$uname);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("subs",$subs);
		$smarty->assign("bank",$bank);

		$smarty->assign("MODE","CHEQUE");
		$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2005;
		}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);

		if($show=="makepaid")
	                $smarty->display("makepaid_refund_paypart.htm");
		else
	                $smarty->display("cheque_deposit.htm");
        }
}
else
{
	/*$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->display("jsconnectError.tpl");*/
	Timedout();
}
function service_name($id)
{
        $sql="select NAME from billing.SERVICES where SERVICEID='$id'";         
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $myrow=mysql_fetch_array($result);
        $name=$myrow["NAME"];
        return $name;
}

?>
