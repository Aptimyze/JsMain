<?php
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/billing/comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");


$ip=FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

$flag=0;

if(authenticated($cid))
{
	list($cur_year,$cur_month,$cur_day) = explode("-",date('Y-m-d'));
        $smarty->assign("cur_year",$cur_year);
        $smarty->assign("cur_month",$cur_month);
        $smarty->assign("cur_day",$cur_day);

	$pay_mode = mode_of_payment();
	$smarty->assign("pay_mode",$pay_mode);

	$from_source_arr = populate_from_source();
	$smarty->assign("from_source_arr",$from_source_arr);

	$ddarr = get_days();
	$smarty->assign("ddarr",$ddarr);

	$mmarr = get_months();
	$smarty->assign("mmarr",$mmarr);

	$yyarr = get_years();
	$smarty->assign("yyarr",$yyarr);

	$bank_arr = get_banks();
	$smarty->assign("bank_arr",$bank_arr);

	$dep_branch_arr = get_deposit_branches();
	$smarty->assign("dep_branch_arr",$dep_branch_arr);
	$smarty->assign("dep_branch",strtoupper(getcenter_for_walkin($user)));

	$smarty->assign("billid",$billid);
	$smarty->assign("val",$val);
	$smarty->assign("username",$username);
	$smarty->assign("dueamt",$dueamt);
	$smarty->assign("curtype",$curtype);
	$smarty->assign("comment",$comment);
	$smarty->assign("mode",$mode);
	$smarty->assign("amount",$amount);
	$smarty->assign("due_day",$due_day);	
	$smarty->assign("due_month",$due_month);	
	$smarty->assign("due_year",$due_year);	
	$smarty->assign("cdnum",$cdnum);
	$smarty->assign("cd_day",$cd_day);	
	$smarty->assign("cd_month",$cd_month);	
	$smarty->assign("cd_year",$cd_year);	
	$smarty->assign("cd_year",$cd_city);
	$smarty->assign("overseas",$overseas);	
	$smarty->assign("separates",$separateds);	
	$smarty->assign("Bank",$Bank);	
	$smarty->assign("obank",$obank);
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->assign("flag",$flag);
	$smarty->assign("uname",$uname);
	$smarty->assign("phrase",$phrase);
	$smarty->assign("criteria",$criteria);
	$smarty->assign("subs",$subs);
	$smarty->assign("bank",$bank);	

	$user=getname($cid);
	/*When submit button is clicked*/
	if($submit)
        {
		/*Server side checks for Payment details*/
		$is_error=0;
		$arr_trans = array_for_trans_num();

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
			/*check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
			$entered_timestamp = mktime(0,0,0,$cd_month,$cd_day,$cd_year);
			$arr = explode("-",date('Y-m-d'));
			list($y,$m,$d) = $arr;
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
			/*End of - check for cheque date -- post dated cheques and cheque dates older than 4 months not accepted*/
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
		/*End of - Server side checks for Payment details*/

		if($is_error==0)
		{
			$membershipObj = new Membership;
			$smarty->assign("flag","1");

			$center=strtoupper(getcenter_for_walkin($user));
			$sql="SELECT PROFILEID,DUEAMOUNT,SERVEFOR FROM billing.PURCHASES WHERE BILLID='$billid'";
			$res=mysql_query_decide($sql) or logError_sums($sql,0);
			if($row=mysql_fetch_array($res))
			{
				$profileid=$row['PROFILEID'];
				$dueamt=$row['DUEAMOUNT'];
				$servefor = $row["SERVEFOR"];
			}
			
			$cd_dt=$cd_year."-".$cd_month."-".$cd_day;
			$dueamt -= $amount;
			if($dueamt<0)
				$dueamt=0;
			$deposit_date=$dep_year."-".$dep_month."-".$dep_day;

			if($Bank=="" || $Bank=="Other")
			{
				$Bank = $obank;
				$obank = "Y";
			}
			else
				$obank = "N";

			$membershipObj->setBillid($billid);
			$membership_details["profileid"] = $profileid;
			$membership_details["mode"] = $mode;
			$membership_details["curtype"] = $curtype;
			$membership_details["amount"] = $amount;
			$membership_details["cheque_date"] = $cd_dt;
			$membership_details["cheque_number"] = $cdnum;
			$membership_details["cheque_city"] = $cd_city;
			$membership_details["bank"] = $Bank;
			$membership_details["obank"] = $obank;
			$membership_details["reason"] = $comment;
			$membership_details["entryby"] = $user;
			$membership_details["deposit_date"] = $deposit_date;
			$membership_details["deposit_branch"] = $dep_branch;
			$membership_details["ip"] = $ip;
			$membership_details["source"] = $from_source;
			$membership_details["transaction_number"] = $transaction_number;
			$jprofileObj =JProfileUpdateLib::getInstance();
			$dateNew =date("Y-m-d");
			if($val=="paypart")
			{
				//new receipt for part payment
				$membership_details["status"] = "DONE";

				if($revoke=="yes")
				{
					//added by sriram to prevent the query on CONTACTS table being run several times on page reload.
					$sql="SELECT ACTIVATED,PREACTIVATED FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
					$res=mysql_query_decide($sql) or logError_sums($sql,0);
					$row=mysql_fetch_array($res);
                                        if($row['ACTIVATED']=='D')
                                        {
                                                //$path = $_SERVER['DOCUMENT_ROOT']."/jsadmin/retrieveprofile_bg.php $profileid > /dev/null &";
						$path = $_SERVER['DOCUMENT_ROOT']."/profile/retrieveprofile_bg.php $profileid > /dev/null &";
                                                $cmd = JsConstants::$php5path." -q ".$path;
                                                passthru($cmd);
                                                                                                                             
                                        }
                                        //end of - added by sriram to prevent the query on CONTACTS table being run several times on page reload.
					/*$sql="UPDATE newjs.JPROFILE SET ACTIVATED=IF(PREACTIVATED<>'D',PREACTIVATED,ACTIVATED), PREACTIVATED='', SUBSCRIPTION='$servefor', ACTIVATE_ON=now(),activatedKey=1 where PROFILEID='$profileid'";
				        mysql_query_decide($sql) or logError_sums($sql,1);*/
		                        if($row['ACTIVATED']!='D')
		                                $preActivated =$row['ACTIVATED'];
		                        else
		                                $preActivated =$row['PREACTIVATED'];

                                        $paramArr =array('PREACTIVATED'=>$preActivated,'SUBSCRIPTION'=>$servefor,'ACTIVATE_ON'=>$dateNew,'activatedKey'=>1);
                                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');

					
					$sql="INSERT INTO jsadmin.DELETED_PROFILES(PROFILEID,RETRIEVED_BY,TIME,REASON) values ('$profileid','$user','".date('Y-M-d')."','Service Revoked through billing')";
				        mysql_query_decide($sql) or logError_sums($sql,1);

				}
                                $sql_ss = "UPDATE billing.PURCHASE_DETAIL SET STATUS = 'DONE' WHERE BILLID='$billid'";
				mysql_query_decide($sql_ss) or logError_sums($sql_ss,1);
                                
				$sql_ss = "UPDATE billing.SERVICE_STATUS SET ACTIVE = 'Y' WHERE BILLID='$billid'";
				mysql_query_decide($sql_ss) or logError_sums($sql_ss,1);

				$DT=date('Y-m-d');
				$sql_ss = "SELECT SERVEFOR FROM billing.SERVICE_STATUS WHERE PROFILEID= '$profileid' AND ACTIVE='Y' AND ACTIVATED='Y' AND EXPIRY_DT>='$DT'";
				$res_ss = mysql_query_decide($sql_ss) or logError_sums($sql_ss);
				while($row_ss = mysql_fetch_array($res_ss))
				{
					$servefor_arr[] = $row_ss["SERVEFOR"];
				}
				$servefor=@implode(",",$servefor_arr);
				/*$sql_upd = "UPDATE newjs.JPROFILE SET SUBSCRIPTION = '$servefor' WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql_upd) or logError_sums($sql_upd,1);*/
                                $paramArr =array("SUBSCRIPTION"=>$servefor);
                                $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');
	
			}
			elseif($val=="refund")
			{
				//new recipt for refund
				$membership_details["status"] = "REFUND";

				$marked_for_deletion = check_marked_for_deletion($profileid);
				if($marked_for_deletion)
				{
					$sql_act = "SELECT ACTIVATED,PREACTIVATED FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
					$res_act = mysql_query_decide($sql_act) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql_act));
					$row_act = mysql_fetch_array($res_act);
					if($row_act['ACTIVATED']!='D' && !$offline_billing)
					{
						$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
						$cmd = JsConstants::$php5path." -q ".$path;
						passthru($cmd);
					}
					/*$sql="UPDATE newjs.JPROFILE SET PREACTIVATED=IF(ACTIVATED<>'D',ACTIVATED,PREACTIVATED), ACTIVATED='D',SUBSCRIPTION='', ACTIVATE_ON=now(),activatedKey=0 where PROFILEID='$profileid'";
					mysql_query_decide($sql) or die(mysql_error_js());*/
		                        if($row_act['ACTIVATED']!='D')
        		                        $preActivated =$row_act['ACTIVATED'];
                		        else
                		                $preActivated =$row_act['PREACTIVATED'];

                                        $updateStr ="PREACTIVATED='$preActivated', ACTIVATED='D',SUBSCRIPTION='', ACTIVATE_ON='$dateNew',activatedKey=0";
                                        $paramArr =$jprofileObj->convertUpdateStrToArray($updateStr);
                                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');
				}
				
			}
			
			$sql="UPDATE billing.PURCHASES SET DUEAMOUNT='$dueamt',STATUS='DONE' WHERE BILLID='$billid'";
			mysql_query_decide($sql) or logError_sums($sql,1);
			
			$membershipObj->startServiceBackend($membership_details);
			$membershipObj->generateReceipt();
            
            //**START - Entry for negative transactions
            if($val=="refund"){
                $memHandlerObject = new MembershipHandler();
                $memHandlerObject->handleNegativeTransaction(array('RECEIPTIDS'=>array($membershipObj->getReceiptid())),'REFUND');
                unset($memHandlerObject);
            }
            //**END - Entry for negative transactions
            
			$smarty->display("refund_paypart.htm");
		}
		else
		{
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
				$smarty->display("refund_paypart.htm");
		}	
	}
	/*End of - When submit button is clicked*/
	/*When Refund/Part Payment button is pressed form the search page.*/
        else
        {
		$sql_s="SELECT USERNAME, DUEAMOUNT, CUR_TYPE as TYPE  FROM billing.PURCHASES WHERE BILLID='$billid'";
		$res_s=mysql_query_decide($sql_s) or logError_sums($sql_s,0);
		$row=mysql_fetch_array($res_s);
		$dueamt=$row['DUEAMOUNT'];
		if($row['TYPE'] == "RS")
			$smarty->assign("type","Rs");
		else
			$smarty->assign("type","US($)");
		$smarty->assign("dueamt",$dueamt);
		$smarty->assign("username",$row['USERNAME']);
		$smarty->assign("curtype",$row['TYPE']);

		if($val=="refund")
		{
			if($dueamt>0)
			{
				$smarty->assign("DUEG0","Y");
				$smarty->display("refund_paypart.htm");
				die;
			}
		}

		$sql="SELECT PROFILEID FROM billing.PURCHASES WHERE BILLID='$billid'";
		$res=mysql_query_decide($sql) or logError_sums($sql,0);
		$row=mysql_fetch_array($res);
		$profileid=$row['PROFILEID'];

		$sql="SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or logError_sums($sql,0);
		$row=mysql_fetch_array($res);
		$activated=$row['ACTIVATED'];

		if($activated=="D")
		{
			$smarty->assign("profile_deleted",1);
		}

		if($val=="paypart")
		{
			$smarty->assign("PAY","Y");
		}

		$center=strtoupper(getcenter_for_walkin($user));

		if($show=="makepaid")
	                $smarty->display("makepaid_refund_paypart.htm");
		else
	                $smarty->display("refund_paypart.htm");
        }
	/*End of - When Refund/Part Payment button is pressed form the search page.*/
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->display("jsconnectError.tpl");
}
?>
