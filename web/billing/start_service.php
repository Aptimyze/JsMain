<?php
include_once("../jsadmin/connect.inc");
include_once("../profile/pg/functions.php");
include_once("comfunc_sums.php");
include_once "../crm/func_sky.php";
$db = connect_db();
$db_slave =connect_slave();
//print_r($_POST);
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
$memObj = new Membership;
global $DOL_CONV_RATE;
$data=authenticated($cid);
$ip=FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}


if(isset($data))
{
	$loginname=getuser($cid);
	$walkin="BANK_TSFR";
	$center="HO";
	$deposit_branch=$center;
	$mode="CHEQUE";
	$src="BANK_TRSFR_CHQ";
	if($sendmail)
	{
		$smarty->assign("REQ_ID",$req_id);
		if($DraftMail)
		{
			$sql=" SELECT * from billing.CHEQUE_REQ_DETAILS where REQUEST_ID='$req_id' ";
			$res=mysql_query_decide($sql,$db_slave) or die("$sql<br>".mysql_error_js());
                        while($row=mysql_fetch_array($res))
                        {
				$cd_num=$row["CD_NUM"];
				list($cd_yy,$cd_mm,$cd_dd)=explode("-",$row["CD_DT"]);
				$cd_dt=$cd_dd."-".$cd_mm."-".$cd_yy;
				$cd_branch=$row["BANK"].",".$row["CD_CITY"];
				$type=$row["TYPE"];
				$amount=$row["AMOUNT"]." ".$type;
			}
					
			if($detail==1)
			{
				$msg="Dear Member,\n\nThank you for registering with Jeevansathi.Com, one of the world&rsquo;s leading matrimonial sites and giving us an opportunity to help you find your match better. \n\nWe tried establishing contact with you but were unable to do so. To ensure that the contact details updated on the website are correct, please log on to \n\nwww.jeevansathi.com/P/viewprofile.php?checksum=$CHECKSUM&profilechecksum=$myprofilechecksum&EditWhatNew=ContactDetails\n\nWe wish to inform you that the payment details updated on the website for activation of your Jeevansathi membership are incorrect. Below stated is the payment detail updated by you on the website.\n\nCheque Number: $cd_num\nCheque Date: $cd_dt\nCheque Branch: $cd_branch\nPayment Amount: $amount\n\nRequest you to update the correct details on the website. Click on the link given below.\n\n www.jeevansathi.com/profile/pg/transecute/cheque_request.php\n\nYour membership would be activated post updation of accurate information on the website which is subject to clearance of the payment.\n\nFor any query please feel free to get in touch with customer services on +91-120-4393500\n\nBest Regards\nJeevansathi Team";
				$subject='Cheque Details Mismatch';
			}
			elseif($detail==2)
			{
				$msg="Dear Member,\n\nThank you for registering with Jeevansathi.Com, one of the world&rsquo;s leading matrimonial sites and giving us an opportunity to help you find your match better. We tried establishing contact with you but were unable to do so. To ensure that the contact details updated on the website are correct, please log on to \n\nwww.jeevansathi.com/P/viewprofile.php?checksum=$CHECKSUM&profilechecksum=$myprofilechecksum&EditWhatNew=ContactDetails\n\nWe wish to inform you that we have not received clearance status of the payment made for activation of your Jeevansathi membership. Below stated is the payment detail updated by you on the website.\n\nCheque Number: $cd_num\nCheque Date: $cd_dt\nCheque Branch: $cd_branch\nPayment Amount: $amount\n\nRequest you to send us the bank certificate stating that the above stated payment has been debited in your savings bank account. Below stated is the information required for us to process your request.\n\nBank Name\nBank Savings Account Number\nBank Branch\nDate of Debit\nBank Account number (where the money has been transferred).\nElse, send us a fresh payment in lieu of the above if it has not been debited from your savings bank account for activation of your paid membership.\n\nFor any query please feel free to get in touch with customer services on +91-120-4393500\n\nBest Regards\nJeevansathi Team";
				$subject='Clearance Status not received';
			}
			$smarty->assign("email",$user_email);
			$smarty->assign("user_mailsent",$user_mailsent);
			$smarty->assign("msg",$msg);
			$smarty->assign("subject",$subject);
			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("message_display",1);
			$smarty->display("start_service_mailsent.htm");
		}
		elseif($SendMail)
		{
			$user= getname($cid);
			$from=get_email($user,$db_slave);
			$smarty->assign("mailsent",1);
			$smarty->assign("user_mailsent",$user_mailsent);
			$attach="";
			send_mail_custom($email,$Cc,$Bcc,$msg,$subject,$from,"text/plain");

			//added by sriram to track mail sent status.
			$sql_upd = "UPDATE billing.CHEQUE_REQ_DETAILS SET MAIL_SENT = MAIL_SENT + 1, MAIL_SENT_DATE = now() WHERE REQUEST_ID='$req_id'";
			mysql_query_decide($sql_upd,$db) or die("$sql_upd".mysql_error_js());

			$sql_upd = "UPDATE incentive.PAYMENT_COLLECT SET ACC_REJ_MAIL_BY = '$loginname' WHERE ID = '$req_id'";
			mysql_query_decide($sql_upd,$db) or die("$sql_upd".mysql_error_js());

			$smarty->display("start_service_mailsent.htm");
		}
		else
		{
			$smarty->assign("user_email",$user_email);
			$smarty->assign("user_mailsent",$user_mailsent);
			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->display("start_service_mailsent.htm");
		}

	}
	elseif($CMDSearch)
	{
		$smarty->assign("search","Y");
		
		if($login)
		{
			$sql_s="SELECT a.ENTRY_DT,a.REQ_DT,a.ID,a.SERVICE,a.ADDON_SERVICEID,a.PROFILEID,a.USERNAME,b.AMOUNT,b.TYPE,b.CD_NUM,b.CD_DT,b.CD_CITY,b.BANK,a.PICKUP_TYPE FROM incentive.PAYMENT_COLLECT as a,billing.CHEQUE_REQ_DETAILS as b WHERE b.STATUS='PENDING' AND a.ID=b.REQUEST_ID";
		}
		else
		{
			$sql_s="SELECT a.ID,a.ENTRY_DT,a.REQ_DT,a.SERVICE,a.ADDON_SERVICEID,a.PROFILEID,a.USERNAME,b.AMOUNT,b.TYPE,b.CD_NUM,b.CD_DT,b.CD_CITY,b.BANK,a.PICKUP_TYPE FROM incentive.PAYMENT_COLLECT as a,billing.CHEQUE_REQ_DETAILS as b where a.ID=b.REQUEST_ID and ";
			if($username!="")
				$where="  a.USERNAME='$username' AND b.STATUS IN ('PENDING')";
			elseif($orderid!="")
				$where = " a.ID = '$orderid' ";//Allow to check the exact status
			elseif($cd_num!="")
				$where = " b.CD_NUM = '$cd_num' ";//Allow to check the exact status
			else
			{
				$sdate=$syear."-".$smonth."-".$sday." 00:00:00";
				$edate=$eyear."-".$emonth."-".$eday." 23:59:59";
				$where=" a.REQ_DT between '$sdate' AND '$edate' AND b.STATUS IN ('PENDING')";
			}

			$sql_s.=$where;
		}
		$res_s=mysql_query_decide($sql_s,$db_slave) or die(mysql_error_js());
		$today=date("Y-m-d");
		$i=0;
		while($row_s=mysql_fetch_array($res_s))
		{
				$serve_for=$row_s['SERVICE'];
				if($row_s['ADDON_SERVICEID'])
					$serve_for.=",".$row_s['ADDON_SERVICEID'];
				$orderarr[$i]["servefor"]=$serve_for;
				$orderarr[$i]["username"]=$row_s['USERNAME'];
				$username_sel=$row_s['USERNAME'];
				$sql_ph="select PHONE_MOB,PHONE_RES,EMAIL from newjs.JPROFILE where USERNAME='$username_sel'";
				$res_ph=mysql_query_decide($sql_ph,$db_slave) or die(mysql_error_js());
				$row_ph=mysql_fetch_array($res_ph);
				$orderarr[$i]["user_email"]=$row_ph['EMAIL'];
				$orderarr[$i]["phone_mob"]=$row_ph['PHONE_MOB'];
				$orderarr[$i]["phone_res"]=$row_ph['PHONE_RES'];
				$orderarr[$i]["orderid"]=$row_s['ID'];
				$orderarr[$i]["req_dt"]=$row_s['REQ_DT'];
				$orderarr[$i]["amount"]=$row_s['AMOUNT'];
				$orderarr[$i]["cd_num"]=$row_s['CD_NUM'];
				$orderarr[$i]["cd_dt"]=$row_s['CD_DT'];
				$orderarr[$i]["pt"]=$row_s['PICKUP_TYPE'];
				$orderarr[$i]["cd_city"]=$row_s['CD_CITY'];
				$orderarr[$i]["bank"]=$row_s['BANK'];
				if(DayDiff($row_s['REQ_DT'],$today)>=2 && DayDiff($row_s['REQ_DT'],$today)<7)
					$orderarr[$i]['twodays']=1;
				elseif(DayDiff($row_s['REQ_DT'],$today)>=7)
					$orderarr[$i]['sevendays']=1;
				$min=$orderarr[0]['req_dt'];
				if($min>$orderarr[$i]['req_dt'])
					$min=$orderarr[$i]['req_dt'];
				$i++;
		}
		if($login)
		{
			$today=date("Y-m-d");
			list($yy,$mm,$dd)=explode("-",$today);
			list($syear,$smonth,$sday)=explode("-",substr($min,0,10));
			$smarty->assign("sday",$sday);
			$smarty->assign("smonth",$smonth);
			$smarty->assign("syear",$syear);
			$smarty->assign("eday",$dd);
			$smarty->assign("emonth",$mm);
			$smarty->assign("eyear",$yy);
		}
		else
		{
			$smarty->assign("sday",$sday);
			$smarty->assign("smonth",$smonth);
			$smarty->assign("syear",$syear);
			$smarty->assign("eday",$eday);
			$smarty->assign("emonth",$emonth);
			$smarty->assign("eyear",$eyear);
			$smarty->assign("username",$username);
			$smarty->assign("orderid",$orderid);
			$smarty->assign("cd_num",$cd_num);
		}

		$smarty->assign("orderarr",$orderarr);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);

		$smarty->display("start_service.htm");
	}
	elseif($CMDGo)
	{

		 $sql="SELECT a.ID,a.SERVICE,a.ADDON_SERVICEID,a.DISCOUNT,a.PROFILEID,a.USERNAME,a.REQ_DT,b.AMOUNT,b.TYPE,b.CD_NUM,b.CD_DT,b.CD_CITY,b.BANK,b.OBANK FROM incentive.PAYMENT_COLLECT as a,billing.CHEQUE_REQ_DETAILS as b where a.ID=b.REQUEST_ID and a.ID ='$accarr'";
		$res=mysql_query_decide($sql,$db_slave) or die("$sql<br>".mysql_error_js());
		$row=mysql_fetch_array($res);
		$profileid=$row["PROFILEID"];
		$cdnum=$row["CD_NUM"];
		$sql_chk="SELECT count(*) as CNT from billing.PAYMENT_DETAIL where PROFILEID='$profileid' and CD_NUM='$cdnum' ";
		$result_chk = mysql_query_decide($sql_chk,$db_slave) or die("$sql_chk<br>".mysql_error_js());
		$myrow_chk = mysql_fetch_array($result_chk);
		$cnt_paid=$myrow_chk["CNT"];
		if($cnt_paid<1)
		{
			$orderid==$row["ID"];
			$service_selected=rtrim($row["SERVICE"],",");
			$addon_services_str=$row["ADDON_SERVICEID"];
			$username=$row["USERNAME"];
			$custname=$row["USERNAME"];
			$cd_dt=$row["CD_DT"];
			$cd_city=$row["CD_CITY"];
			$bankfeed=$row["BANK"];
			$obank=$row["OBANK"];
			$curtype=$row["TYPE"];
			$amount=$row["AMOUNT"];
			$discount=round(($row["DISCOUNT"]*100)/(100+$memObj->getTaxRate()),2);
			$dep_date=$row["REQ_DT"];
			$tax_value = $TAX_RATE;
	//		$dep_branch=$row["PICKUP_TYPE"];
			if($curtype == "DOL") { //to be checked
				$dol_conv_rate = $DOL_CONV_RATE;
			}
			$sql="SELECT NAME, GENDER, ADDRESS, CITY, PIN, EMAIL, RPHONE, OPHONE, MPHONE FROM billing.PURCHASES WHERE PROFILEID='$profileid' order by ENTRY_DT desc limit 1";
			$res=mysql_query_decide($sql,$db_slave) or logError_sums($sql,0);
			if(mysql_num_rows($res)>0)
			{
				$row = mysql_fetch_array($res);
				$custname = addslashes($row['NAME']);
				$gender = $row['GENDER'];
				$address = $row['ADDRESS'];
				$city = $row['CITY'];
				$pin = $row['PIN'];
				$email = $row['EMAIL'];
				$rphone = $row['RPHONE'];
				$ophone = $row['OPHONE'];
				$mphone = $row['MPHONE'];
				$address = addslashes($address);
				$tracking_variable = "R";
				$discount_type=1;
			}
			else
			{
				$tracking_variable = "N";
				$sql_order = "SELECT PHONE_RES,PHONE_MOB,GENDER,COUNTRY_RES,PINCODE,EMAIL,CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = $profileid ";
				$result = mysql_query_decide($sql_order,$db_slave) or logError_sums($sql_order,1);
				$row = mysql_fetch_assoc($result);
				$city = $row['CITY_RES'];
				$gender = $row['GENDER'];
				$pin = $row['PINCODE'];
				$email = $row['EMAIL'];
				$rphone = $row[' PHONE_RES'];
				$mphone = $row['PHONE_MOB'];
				if ($row[COUNTRY_RES] == '51')
					$overseas='N';
				else
					$overseas='Y';
			}
			$address = addslashes(stripslashes($address));
			$membership_details["serviceid"] = $service_selected;
			$membership_details["profileid"] = $profileid;
			$membership_details["username"] = $username;
			$membership_details["custname"] = $custname;
			$membership_details["address"] = $address;
			$membership_details["gender"] = $gender;
			$membership_details["city"] = $city;
			$membership_details["pin"] = $pin;
			$membership_details["email"] = $email;
			$membership_details["rphone"] = $rphone;
			$membership_details["ophone"] = $ophone;
			$membership_details["mphone"] = $mphone;
			$membership_details["comment"] = $comment;
			$membership_details["curtype"] = $curtype;
			$membership_details["overseas"] = $overseas;
			$membership_details["discount"] = $discount;
			$membership_details["discount_type"] = $discount_type;
			$membership_details["discount_reason"] = $reason;
			$membership_details["walkin"] = $walkin;
			$membership_details["center"] = $center;
			$membership_details["entryby"] = $loginname;
			$membership_details["dueamount"] = $part_payment;
			$membership_details["due_date"] = $due_date;
			$membership_details["status"] = "DONE";
			$membership_details["verify_service"] = $verify_service;
			$membership_details["deposit_date"] = $dep_date;
			$membership_details["deposit_branch"] =$deposit_branch; 
			$membership_details["ip"] = $ip;
			$membership_details["entry_from"] = $tracking_variable;
			$membership_details["mode"] = $mode;
			$membership_details["amount"] = $amount;
			$membership_details["cheque_number"] = $cdnum;
			$membership_details["cheque_date"] = $cd_dt;
			$membership_details["cheque_city"] = $cd_city;
			$membership_details["bank"] = $bankfeed;
			$membership_details["obank"] = $obank;
			$membership_details["source"] = $src;
			$membership_details["transaction_number"] = $transaction_number;
			$membership_details["dol_conv_bill"]='N';

			$memObj->startServiceBackend($membership_details);
			$memObj->makePaid();
			$sql_executive="SELECT VOUCHER_CODE FROM billing.VOUCHER_MARKING WHERE PROFILEID='$profileid'";
			$res_executive = mysql_query_decide($sql_executive,$db_slave) or logError_sums($sql_executive);
			if(mysql_num_rows($res_executive)>0)
			{
				$row_executive = mysql_fetch_array($res_executive);
				mark_voucher_code($profileid,$row_executive["VOUCHER_CODE"],"OVER","SUCCESSFUL",$orderid);
			}
			//incorporate changes thrugh Airex Module
			$memObj->updatePaymentCollectForAirex();
			
			$subject = "Bill for your subscription";
			$msg = $memObj->membership_mail();
                        //$timeNow = date("Y-m-d h:m:s");
                        $bill = $memObj->printbill($memObj->getReceiptid(),$memObj->getBillid());

			$sql="SELECT EMAIL from newjs.JPROFILE where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql,$db_slave) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			$email= $myrow['EMAIL'];

			//send_email("$email",$msg,$subject,'webmaster@jeevansathi.com','',"payments@jeevansathi.com",$bill);
			//different mail function called to send html mail along with rtf attachment.
			$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$email,"EMAIL_TYPE"=>"29"),$profileid);
                        $canSend = $canSendObj->canSendIt();
                        if($canSend)
                        {
				send_email("$email",$msg,$subject,'webmaster@jeevansathi.com','',"payments@jeevansathi.com,aman.sharma@jeevansathi.com",$bill);
			}

			$sql_u="UPDATE billing.CHEQUE_REQ_DETAILS SET  STATUS='DONE' WHERE REQUEST_ID = $accarr";
			mysql_query_decide($sql_u,$db) or die("$sql_u<br>".mysql_error_js());

			$sql_upd = "UPDATE incentive.PAYMENT_COLLECT SET ACC_REJ_MAIL_BY = '$loginname' WHERE ID = $accarr";
			mysql_query_decide($sql_upd,$db) or die("$sql_upd<br>".mysql_error_js());
			$msg="Records Have been succesfully updated<br>";

			$msg.="<a href=\"start_service.php?user=$user&cid=$cid&CMDSearch=1&login=1\">Continue</a>";
		}
		else
		{
			$msg="Billing already done for this cheque<br><a href=\"start_service.php?user=$user&cid=$cid&CMDSearch=1&login=1\">Continue</a>";
		}
		$smarty->assign("MSG",$msg);
		$smarty->display("start_service.htm");
	}
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->assign("username","$username");
        $smarty->display("jsconnectError.tpl");
}
function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day    //
   return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
}

?>
