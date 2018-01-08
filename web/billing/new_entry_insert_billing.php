<?php

include_once("../jsadmin/connect.inc");
include_once("../profile/pg/functions.php"); 
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
global $DOL_CONV_RATE;
$ip=FetchClientIP();
if(strstr($ip, ","))
{
	$ip_new = explode(",",$ip);
	$ip = $ip_new[1];
}
if(authenticated($cid))
{
	$serviceObj = new Services;
	$membershipObj = new Membership;
	
	maStripVARS_sums('stripslashes');
	if($submit)
	{
		$loginname=getuser($cid);
		if($walkin=="OFFLINE" || $walkin=="ARAMEX")
		{
			$center="HO";
			$email_walkin="mahesh@jeevansathi.com";
		}
		else
		{
			$sql="SELECT EMAIL,CENTER from jsadmin.PSWRDS where USERNAME='$walkin'";
			$result = mysql_query_decide($sql) or logError_sums($sql,0);
			$myrow=mysql_fetch_array($result);
			$center=$myrow['CENTER'];
			$email_walkin=$myrow['EMAIL'];
		}
	
		$username = addslashes(stripslashes($username));
		$sql="SELECT PROFILEID,EMAIL,SCREENING,PHONE_RES,PHONE_MOB,CONTACT,PINCODE,CITY_RES from newjs.JPROFILE where USERNAME='$username'";
		$result = mysql_query_decide($sql) or logError_sums($sql,0);
		$myrow=mysql_fetch_array($result);
		$profileid=$myrow['PROFILEID'];
		$email_jprofile=$myrow['EMAIL'];
		$screening=$myrow['SCREENING'];
		$pin_jprofile=$myrow['PINCODE'];
		$rphone_jprofile=$myrow['PHONE_RES'];
		$mphone_jprofile=$myrow['PHONE_MOB'];
		$address_jprofile=$myrow['CONTACT'];
		$city_res_val=$myrow['CITY_RES'];

		if($city_res_val)
		{
			$sql_city="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res_val'";
			$result_city = mysql_query_decide($sql_city) or logError_sums($sql_city,0);
			$myrow_city=mysql_fetch_array($result_city);
			$city_res_jprofile=$myrow_city['LABEL'];
		}

		if($email=="")
			$email=$email_jprofile;	
		if($address=="")
		{
			$address=$address_jprofile;
			$city=$city_res_jprofile;
		}
		if($mphone=="")
			$mphone=$mphone_jprofile;
		if($rphone=="")
			$rphone=$rphone_jprofile;
		if($pin=="")
			$pin=$pin_jprofile;
		if($city=="")
			$city=$city_res_jprofile;

		if($bank=="Other" || $bank=="")
		{
			$bankfeed=$obank;
			$obank="Y";
		}
		else
		{
			$bankfeed=$bank;
			$obank="N";
		}

		if($renew)//if renewing then select user details from PURCHASES table.
		{
			$sql="SELECT NAME, GENDER, ADDRESS, CITY, PIN, EMAIL, RPHONE, OPHONE, MPHONE FROM billing.PURCHASES WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or logError_sums($sql,0);
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
		}
		else
		{
			$tracking_variable = "N";
			$address = addslashes(stripslashes($address));
		}

		$part_payment = $total_pay - $amount;
		if($part_payment<0)
			$part_payment=0;

		$custname = addslashes(stripslashes($custname));
		$comment = addslashes(stripslashes($comment));
		$reason = addslashes(stripslashes($reason));
		if($curtype!='DOL')
			$discount=round($discount,2);
        if(strpos($main_service_id,"Upgrade")!==false){
            $main_service_id = str_replace("Upgrade", "", $main_service_id);
            $discount_type = 15;
            $memUpgrade = "MAIN";
            $orderid = "backend";
        }
        else{
            $memUpgrade = "NA";
            $orderid = "";
        }
		$rights = $serviceObj->getRights($main_service_id);
		//Assigning value to VERIFY_SERVICE depending on whether contact details are invalid or not [2847[
		if(@in_array("D",$rights))
                {
                        if(!isFlagSet("PHONERES",$screening) || !isFlagSet("PHONEMOB",$screening) || !isFlagSet("CONTACT",$screening) || !isFlagSet("MESSENGER",$screening) || !isFlagSet("EMAIL",$screening) || !isFlagSet("PARENTS_CONTACT",$screening))
			        $verify_service = "N";
                        else
                                evalue_privacy($profileid);
                }
	        if(!$verify_service)
			unset($verify_service);
			
		$membership_details["serviceid"] = $main_service_id;
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
		$membership_details["deposit_branch"] = $dep_branch;
		$membership_details["ip"] = $ip;
		$membership_details["entry_from"] = $tracking_variable;
		$membership_details["mode"] = $mode;
		$membership_details["amount"] = $amount;
		$membership_details["cheque_number"] = $cdnum;
		$membership_details["cheque_date"] = $cd_date;
		$membership_details["cheque_city"] = $cd_city;
		$membership_details["bank"] = $bankfeed;
		$membership_details["obank"] = $obank;
		$membership_details["source"] = $from_source;
		$membership_details["transaction_number"] = $transaction_number;
		if($convert_curr=='CONV_DOL')
			$membership_details["dol_conv_bill"]='Y';
		else
			$membership_details["dol_conv_bill"]='N';
		$membershipObj->startServiceBackend($membership_details);
		$membershipObj->makePaid(false,$memUpgrade,$orderid);
		$membershipObj->updateEasyBill();
		$membershipObj->updateIvr();
		
		if($voucher_discount_code)
			mark_voucher_code($profileid,trim($voucher_discount_code),"","SUCCESSFUL",$billid);
		
		//incorporate changes thrugh Airex Module
		if($source=="A")
			$membershipObj->updatePaymentCollectForAirex();

		if($offline_billing)
		{
			$off_id_arr=explode(",",$main_service_id);
			for($i=0;$i<count($off_id_arr);$i++)
			{
				if(strstr($off_id_arr[$i],"O"))
					$off_id=$off_id_arr[$i];
			}
			$sql_off = "Select c.RIGHTS as RIGHTS, c.DURATION as DURATION, c.ACC_COUNT as ACC_COUNT from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID='$off_id' ";

			$result_off = mysql_query_decide($sql_off) or logError_sums($sql_off,0);
			while($myrow_off = mysql_fetch_array($result_off))
			{
				$acceptance_count = $myrow_off["ACC_COUNT"];
                	}

			$membershipObj->handleOfflineBilling($acceptance_count);
		}
	/*	
		if($string == "P")
			$subject = "Congrats! You are now an e-Rishta Member!";
		if($string == "D")
			$subject = "Congrats! You are now an e-Classified Member!";
		if($string == "C")
			$subject = "Congrats! You are now an e-Value Pack Member!";
		if($string == "SC")
			$subject = "Congrats! You are now an Super Saver Pack Member!";
	*/
			$subject = "Bill for your subscription";

		if($walkin=="OFFLINE" || $walkin=="ARAMEX")
			$walkinemail="mahesh@jeevansathi.com";
		else
		{
			$sql="SELECT EMAIL from jsadmin.PSWRDS where USERNAME='$walkin'";
			$result=mysql_query_decide($sql) or logError_sums($sql,0);
			$myrow=mysql_fetch_array($result);
			$walkinemail= $myrow['EMAIL'];
		}
		
		if($amount > 0)
		{
			$msg = $membershipObj->membership_mail();
			//$bill = $membershipObj->printbill($membershipObj->getReceiptid(),$membershipObj->getBillid());

			// New code added for pdf creation
                        $receiptid =$membershipObj->getReceiptid();
                        $billid =$membershipObj->getBillid();
			include_once("invoiceGenerate.php");
			$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$email,"EMAIL_TYPE"=>"29"),$profileid);
                        $canSend = $canSendObj->canSendIt();
                        if($canSend)
                        {
				send_email("$email",$msg,$subject,'membership@jeevansathi.com',$walkinemail,"payments@jeevansathi.com",$bill,'','','',"1",'','Jeevansathi Membership');
			}
		}
		if(strstr($main_service_id,'M'))
		{
			$billid=$membershipObj->getBillid();
			$sql="INSERT INTO billing.MATRI_PURCHASES(PROFILEID,BILLID,ENTRY_DT) VALUES ('$profileid','$billid',NOW())";
			$result=mysql_query_decide($sql) or logError_sums($sql,0);
			$membershipObj->matri_questionnaire_mail($username,$email,$walkinemail);
		}

		//if discount > 15% then send mail.
		$allowed_discount = ((41/100) * $price);
		if($discount > $allowed_discount)
		{
			$to = "anamika.singh@jeevansathi.com,rohan.mathur@jeevansathi.com,rajeev.joshi@jeevansathi.com";
			$header = "From: info_sums@jeevansathi.com\r\n";
			$subject = "Discount exceeding 40%";

			$discount_msg = "Details as follows\n";
			//$discount_msg .= "\r\nBill ID: ".$billid;
			$discount_msg .= "\r\nUsername: ".$username;

			if($addons)
				$discount_msg .= "\r\nAddon Service: ".$addons;

			//$discount_msg .= "\r\nDuration: ".$duration_sel." Month(s)";

			$price = floor($price + (($price * $tax_rate)/100));
			$discount_msg .= "\r\nTotal Amount: ".$price;

			$discount = round($discount + (($discount * $tax_rate)/100));
			$discount_msg .= "\r\nDiscount: ".$discount;

			$discount_percentage = round(($discount/$price) * 100);
			$discount_msg .= "\r\nDiscount Percentage: ".$discount_percentage."%";

			$discount_msg .= "\r\nDiscount Reason: ".$reason;

			$discount_msg .= "\r\n\r\nSale by: ".$walkin;
			$discount_msg .= "\r\nEntry by: ".$user;
			
			//mail($to,$subject,$discount_msg,$header);
		}

		if($source=="A")
			$msg_cnt = "../crm/billentry.php?user=".$loginname."&cid=".$cid;	
		elseif($source=="I")
			$msg_cnt = "../crm/mainpage.php?cid=".$cid;	
		else
			$msg_cnt = "search_user.php?user=".$loginname."&cid=".$cid."&criteria=".$criteria."&phrase=".$phrase."&offline_billing=".$offline_billing;
		$smarty->assign("name",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg_cnt);
		$smarty->assign("offline_billing",$offline_billing);
		$smarty->display("new_entry_insert_billing.htm");
	}	
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("billing_msg.tpl");
                                                                                                 
}
?>

