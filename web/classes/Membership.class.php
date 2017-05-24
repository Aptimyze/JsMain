<?php

if (JsConstants::$whichMachine != 'matchAlert') {
	include_once (JsConstants::$docRoot . "/billing/comfunc_sums.php");
	include_once (JsConstants::$docRoot . "/jsadmin/ap_common.php");
}

include_once (JsConstants::$docRoot . "/classes/Services.class.php");
include_once (JsConstants::$cronDocRoot . "/lib/model/lib/FieldMapLib.class.php");

class Membership
{

    private $error_msg = "Due to some temporary problem your request could not be processed. Please try after some time.";
    private $announce_to_email = "vibhor.garg@jeevansathi.com";
    
    private $DOL_CONV_RATE = 66;
    private $tax_rate = billingVariables::TAX_RATE;
    private $renew_discount_rate = 15;    
    private $subscription;
    private $payment_gateway;
    
    //purchase table insertion variables;
    private $serviceid;
    private $profileid;
    private $username;
    private $name;
    private $address;
    private $gender;
    private $city;
    private $pin;
    private $email;
    private $rphone;
    private $ophone;
    private $mphone;
    private $comment;
    private $overseas;
    private $discount;
    private $discount_type;
    private $discount_reason;
    private $walkin;
    private $center;
    private $entryby;
    private $dueamount;
    private $duedate;
    private $entry_dt;
    private $status;
    private $servefor;
    private $verify_service;
    private $orderid;
    private $deposit_dt;
    private $deposit_branch;
    private $ipadd;
    private $curtype;
    private $entry_from;
    private $membership;
    private $orderid_part1;
    private $device;
    private $checkCoupon;

    //payment detail insertion variables.
    private $billid;
    private $mode;
    private $type;
    private $amount;
    private $cheque_number;
    private $cheque_date;
    private $cheque_city;
    private $bank;
    private $obank;
    private $reason;
    private $bounced_date;
    private $transaction_number;
    private $source;
    
    private $receiptid;
    private $activated;
    private $activated_on;
    private $activate_on;
    private $activated_by;
    private $expiry_dt;
    
    private $set_activate;
    private $dol_conv_bill;
    private $assisted_arr = array();
    private $discount_percent;

    function setBillid($billid) {
        $this->billid = $billid;
    }
    
    function getBillid() {
        return $this->billid;
    }
    
    function setReceiptid($receiptid) {
        $this->receiptid = $receiptid;
    }
    
    function getReceiptid() {
        return $this->receiptid;
    }
    
    function getTaxRate() {
        return $this->tax_rate;
    }

    function get_DOL_CONV_RATE() {
        return $this->DOL_CONV_RATE;
    }

    function get_renew_discount_rate() {
        return $this->renew_discount_rate;
    }

    function setProfileid($profileid) {
        $this->profileid = $profileid;
    }
    
    function setCurtype($curtype) {
        $this->curtype = $curtype;
    }
    
    function getCurtype() {
        return $this->curtype;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    function generateOrder($profileid, $paymode, $curtype, $amount, $service_str, $service_main, $discount, $setactivate, $gateway = '', $discount_type = '') {
        if ($profileid > 0) {
            $ORDERID = sprintf("J%1.1s%09lX", 'Z', time(NULL));
            
            $data = getProfileDetails($profileid);
            $data["AMOUNT"] = $amount;
            
            // convert USD to RS in case of gateway is other than PAYPAL,
            if ($gateway != "PAYPAL") {
                if ($curtype == "DOL" && $gateway != "PAYSEAL") $data["AMOUNT"] = round(($data["AMOUNT"] * $this->DOL_CONV_RATE), 2);
            }
            
            $serviceObj = new Services;
            $servefor = @implode(",", $serviceObj->getRights($service_str));
            
            $data["SERVICE_MAIN"] = $service_main;
            
            if ($setactivate == "Y") {
                $renew_status = $this->getRenewStatus($profileid);
                $activate_on = $renew_status['EXPIRY_DT'];
            } 
            else $activate_on = date('Y-m-d');
            
            $insert_id = '';
            
            $billingOrderObj = new BILLING_ORDERS();

            $paramsStr = "PROFILEID, USERNAME, ORDERID, PAYMODE, SERVICEMAIN, CURTYPE,SERVEFOR, AMOUNT, BILL_ADDRESS, PINCODE, BILL_COUNTRY, BILL_PHONE, BILL_EMAIL, IPADD,DISCOUNT,SET_ACTIVATE,GATEWAY, DISCOUNT_TYPE";
            $valuesStr = "'$profileid', '" . addslashes($data[USERNAME]) . "', '$ORDERID', '$paymode', '$service_main','$curtype','$servefor', '$data[AMOUNT]','" . addslashes(stripslashes($data[CONTACT])) . "', '" . addslashes(stripslashes($data[PINCODE])) . "', '" . addslashes(stripslashes($data[COUNTRY])) . "', '$data[PHONE]', '$data[EMAIL]','$ip','$discount','$setactivate','$gateway','$discount_type'";
            $insert_id = $billingOrderObj->genericOrderInsert($paramsStr, $valuesStr);

            $data["ORDERID"] = $ORDERID . "-" . $insert_id;
            
            if ($insert_id) return $data;
            else return false;
        } 
        else return false;
    }
    
    function updtOrder($ORDERID, &$dup, $updateStatus = 'Y') {
        list($part1, $part2) = explode('-', $ORDERID);
        
        $billingOrderObj = new BILLING_ORDERS();
        $orderDetails = $billingOrderObj->getOrderDetailsForOrderID($part2, $part1);
        
        if (!is_array($orderDetails) || empty($orderDetails)) {
            $ret = false;
        } 
        else {
            $myrow = $orderDetails[0];
            if ($myrow["PMTRECVD"] == '0000-00-00' || $myrow["STATUS"] == 'N' || $myrow["STATUS"] == 'B') {
                $date = date("Y-m-d", time());
                $updateRowStatus = $billingOrderObj->updatePaymentReceivedStatus($date, $updateStatus, $part2, $part1);
                if ($updateRowStatus) {
                    $ret = true;
                }
                else {
                    $ret = false;
                }
                $dup = false;
                if($updateStatus == 'N' || $updateStatus == "N"){
                    //check whether user was eligible for membership upgrade or not
                    $memCacheObject = JsMemcache::getInstance();
                    $checkForMemUpgrade = $memCacheObject->get($myrow["PROFILEID"].'_MEM_UPGRADE_'.$ORDERID);
                    if($checkForMemUpgrade != null && in_array($checkForMemUpgrade,  VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                        $memHandlerObj = new MembershipHandler(false);
                        $memHandlerObj->updateMemUpgradeStatus($ORDERID,$myrow["PROFILEID"],array("UPGRADE_STATUS"=>"FAILED","DEACTIVATED_STATUS"=>"FAILED","REASON"=>"Gateway payment failed"),true);
                        unset($memHandlerObj);
                    }
                }
            } 
            else {
                $dup = true;
                $ret = true;
            }
        }
        return $ret;
    }
    
    function startServiceOrder($orderid, $skipBill = false,$doneUpto="") {
        global $smarty;
        
        list($part1, $part2) = explode('-', $orderid);

        $billingOrderObj = new BILLING_ORDERS();
        $orderDetails = $billingOrderObj->getOrderDetailsForOrderID($part2, $part1);
        $myrow = $orderDetails[0];
        $this->profileid = $myrow["PROFILEID"];
        $this->payment_gateway = $myrow['GATEWAY'];
        $this->serviceid = rtrim($myrow["SERVICEMAIN"], ",");
        $this->amount = $myrow["AMOUNT"];
        $this->DISCOUNT_TYPE = $myrow["DISCOUNT_TYPE"];
        $billingVouchMarkObj = new billing_VOUCHER_MARKING();
        if ($this->DISCOUNT_TYPE == '8') {
            $row_executive = $billingVouchMarkObj->getVoucherCodeForProfileid($this->profileid);
            if ($row_executive) {
                //User has used multiple times usable voucher code
            } 
            else {
                mark_voucher_code($this->profileid, $row_executive["VOUCHER_CODE"], "OVER", "SUCCESSFUL", $orderid);
            }
        }
        if ($myrow["CURTYPE"] == 'DOL') {
            if ($this->payment_gateway != "PAYPAL" && $this->payment_gateway != "PAYSEAL" && $this->payment_gateway != "CCAVENUE" && $this->payment_gateway != "APPLEPAY" && $this->payment_gateway != "PAYU") {
                $this->curtype = "DOL";
                $this->amount = $this->amount / $this->DOL_CONV_RATE;
            } 
            else {
                $this->curtype = "DOL";
                $this->type = "DOL ";
            }
        } 
        elseif ($myrow["CURTYPE"] == 'RS') {
            $this->curtype = "RS";
            $this->type = "RS ";
        } 
        else {
            mail($this->announce_to_email, "$this->type not found", "OrderID : $orderid\nType : $this->type");
            $this->curtype = "";
        }
        
        $this->subscription = $myrow["SERVEFOR"];
        $subscription_arr = @explode(",", $this->subscription);
        $jprofileObj = new JPROFILE();
        if (in_array("D", $subscription_arr)) {
            $rowcon = $jprofileObj->get($this->profileid,'PROFILEID','SCREENING');
            //If contact details invalid, send confirm contact details mailer, hence marking verify_service as N
            if (!isFlagSet("PHONERES", $rowcon["SCREENING"]) || !isFlagSet("PHONEMOB", $rowcon["SCREENING"]) || !isFlagSet("CONTACT", $rowcon["SCREENING"]) || !isFlagSet("MESSENGER", $rowcon["SCREENING"]) || !isFlagSet("EMAIL", $rowcon["SCREENING"]) || !isFlagSet("PARENTS_CONTACT", $rowcon["SCREENING"])) $this->verify_service = "N";
            else evalue_privacy($this->profileid);
        }
        
        list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
        $this->entry_dt = my_format_date($day, $month, $year);
        
        $this->discount_type = $myrow['DISCOUNT_TYPE'];
        $this->deposit_dt = date('Y-m-d');
        
        $this->ipadd = FetchClientIP();
        if (strstr($this->ipadd, ",")) {
            $ip_new = explode(",", $this->ipadd);
            $this->ipadd = $ip_new[1];
        }
        
        $this->username = addslashes(stripslashes($myrow["USERNAME"]));
        $this->name = $myrow["DLVR_CUST_NAME"];
        $this->address = addslashes(stripslashes($myrow["BILL_ADDRESS"]));
        $this->email = $myrow["BILL_EMAIL"];
        $this->rphone = $myrow["BILL_PHONE"];
        $this->comment = $myrow["DLVR_NOTES"];
        $this->discount = $myrow["DISCOUNT"];
        $this->walkin = "ONLINE";
        $this->center = "HO";
        $this->entryby = "ONLINE";
        $this->status = "DONE";
        $this->servefor = $myrow["SERVEFOR"];
        $this->orderid_part1 = $part1;
        $this->orderid = $part2;
        $this->deposit_branch = "HO";
        $this->mode = "ONLINE";
        $this->source = "ONLINE";
        $this->expiry_dt = $myrow["EXPIRY_DT"];
        $this->set_activate = $myrow["SET_ACTIVATE"];
        
        //check whether user is eligible for membership upgrade or not
        $memCacheObject = JsMemcache::getInstance();
        $checkForMemUpgrade = $memCacheObject->get($this->profileid.'_MEM_UPGRADE_'.$orderid);
        
        if($checkForMemUpgrade == null || $checkForMemUpgrade == false){
            $upgradeOrderObj = new billing_UPGRADE_ORDERS();
            $isUpgradeCaseEntry = $upgradeOrderObj->isUpgradeEntryExists($orderid,$this->profileid);
            if(is_array($isUpgradeCaseEntry)){
                $memUpgrade = $isUpgradeCaseEntry["MEMBERSHIP"];
            }
            else{
                $memUpgrade = "NA";
            }
        }
        else{
            if(in_array($checkForMemUpgrade, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                $memUpgrade = $checkForMemUpgrade;
            }
            else{
                $memUpgrade = "NA";
            }
        }
        $this->makePaid($skipBill,$memUpgrade,$orderid,$doneUpto);

        include_once (JsConstants::$docRoot . "/profile/suspected_ip.php");
        $suspected_check = doubtfull_ip("$ip");
        
        if ($suspected_check) send_email('vikas@jeevansathi.com', $this->profileid, "Payment Profileid of suspected email-id", "payment@jeevansathi.com");
        
        // $subject = "Bill for your online subscription";
        
        // $msg = $this->order_mail_content($this->username, $this->type, $this->amount, $this->entry_dt, $myrow['ORDERID'], $myrow['ID']);
        
        $receiptid = $this->receiptid;
        $billid = $this->billid;
        include_once (JsConstants::$docRoot . "/billing/invoiceGenerate.php");
        
        //different mail function called to send html mail along with pdf attachment.
		$canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$this->email,"EMAIL_TYPE"=>"29"),$myrow['PROFILEID']);
        $canSend = $canSendObj->canSendIt();

        if($canSend)
        {
        	$ordrDeviceObj = new billing_ORDERS_DEVICE();
            $device = $ordrDeviceObj->getOrderDeviceFromBillid($billid);
            if($device == 'iOS_app'){
            	// Disable sending bills to iOS Customers
            	unset($bill);
            }
            $membershipMailer = new MembershipMailer();
            $membershipMailer->sendWelcomeMailerToPaidUser(1835, $this->profileid, $bill, $this->serviceid);
	        //send_email($this->email, $msg, $subject, 'membership@jeevansathi.com', '', 'payments@jeevansathi.com', $bill, '', '', '', "1", '', 'Jeevansathi Membership');
		}
        
        if (strstr($this->serviceid, 'M')) {
            $jsadminMainAdminObj = new MAIN_ADMIN();
            $row_exec = $jsadminMainAdminObj->getAllotedTo($myrow['PROFILEID']);
            if ($row_exec["ALLOTED_TO"] != '') {
                $jsadminPswrdsObj = new jsadmin_PSWRDS();
                $alloted_to = $jsadminPswrdsObj->getEmail($row['ALLOTED_TO']);
            }
            
            $billingMartiPurObj = new billing_MATRI_PURCHASES();
            $billingMartiPurObj->insert($this->profileid, $this->billid);
            $this->matri_questionnaire_mail($myrow['USERNAME'], $myrow["BILL_EMAIL"], $alloted_to);
        }
        
    }
    
    function startServiceBackend($membership_details) {
        $this->serviceid = str_replace("'", "", $membership_details["serviceid"]);
        $this->profileid = $membership_details["profileid"];
        $this->username = addslashes($membership_details["username"]);
        $this->name = $membership_details["custname"];
        $this->address = $membership_details["address"];
        $this->gender = $membership_details["gender"];
        $this->city = $membership_details["city"];
        $this->pin = $membership_details["pin"];
        $this->email = $membership_details["email"];
        $this->rphone = $membership_details["rphone"];
        $this->mphone = $membership_details["mphone"];
        $this->ophone = $membership_details["ophone"];
        $this->comment = $membership_details["comment"];
        $this->curtype = $membership_details["curtype"];
        $this->overseas = $membership_details["overseas"];
        $this->discount = $membership_details["discount"];
        $this->discount_type = $membership_details["discount_type"];
        $this->discount_reason = $membership_details["discount_reason"];
        $this->walkin = $membership_details["walkin"];
        $this->center = $membership_details["center"];
        $this->entryby = $membership_details["entryby"];
        $this->dueamount = $membership_details["dueamount"];
        $this->duedate = $membership_details["due_date"];
        $this->entry_dt = date("Y-m-d G:i:s");
        $this->status = $membership_details["status"];
        $serviceObj = new Services;
        $this->servefor = @implode(",", $serviceObj->getRights($this->serviceid));
        $this->verify_service = $membership_details["verify_service"];
        $this->deposit_dt = $membership_details["deposit_date"];
        $this->deposit_branch = $membership_details["deposit_branch"];
        $this->ipadd = $membership_details["ip"];
        $this->entry_from = $membership_details["entry_from"];
        $this->dol_conv_bill = $membership_details["dol_conv_bill"];
        
        $this->reason = addslashes(stripslashes($membership_details["reason"]));
        $this->mode = $membership_details["mode"];
        $this->amount = $membership_details["amount"];
        $this->cheque_number = $membership_details["cheque_number"];
        $this->cheque_date = $membership_details["cheque_date"];
        $this->cheque_city = addslashes(stripslashes($membership_details["cheque_city"]));
        $this->bank = addslashes(stripslashes($membership_details["bank"]));
        $this->obank = $membership_details["obank"];
        $this->source = $membership_details["source"];
        $this->transaction_number = addslashes(stripslashes($membership_details["transaction_number"]));
        $this->device = 'desktop';
    }
    
    function order_mail_content_renew($USERNAME, $type, $amount, $entry_dt, $ORDERID, $ID, $DURATION, $EXPIRY_DT, $activate_on) {
        global $payment_gateway;

        list($yy, $mm, $dd) = explode("-", $entry_dt);
        $entry_dt = my_format_date($dd, $mm, $yy);
        
        list($yy, $mm, $dd) = explode("-", $EXPIRY_DT);
        $EXPIRY_DT = my_format_date($dd, $mm, $yy);

        list($yy, $mm, $dd) = explode("-", $activate_on);
        $activate_on = my_format_date($dd, $mm, $yy);

        $activate_on_timestamp = mktime(0, 0, 0, $mm, $dd, $yy);
        $last_expire_timestamp = $activate_on_timestamp + (24 * 60 * 60);
        $last_expire_dt = date('Y-m-d', $last_expire_timestamp);
        list($yy, $mm, $dd) = explode("-", $last_expire_dt);
        $activate_on_show = my_format_date($dd, $mm, $yy);
        
        $msg = "Dear $USERNAME,\n\nWe have received the payment for the renewal of your Jeevansathi Membership. Given below are your subscription registration details:\n\nYour order id    : $ORDERID-$ID\nDate    : $entry_dt\nFor an amount of   : $type $amount\n\nYour membership subscription is for the period of $DURATION months and is valid till $EXPIRY_DT. Your subscription will come into effect only on $activate_on_show as your earlier subscription comes to an end on $activate_on.";
        
        if ($payment_gateway == 'CCAVENUE') {
            $msg.= "\n\nPlease Note :- The charge will appear on your credit card as 'ccavenue.com/charge'";
        } 
        elseif ($payment_gateway == 'TRANSECUTE') {
            $msg.= "\n\nPlease Note :- The charge that will be appearing on your Credit Card Statement will display pay.transecute.com";
        } 
        elseif ($payment_gateway == 'PAYPAL') {
            $msg.= "\n\nPlease Note :- The credit card transaction will appear on your bill as \"PAYPAL *JEEVANSATHI\".";
        }
        
        return $msg;
    }

    function order_mail_content($USERNAME, $type, $amount, $entry_dt, $ORDERID, $ID) {
        $membershipMailer = new MembershipMailer();
        $receiptid = $this->receiptid;
        $billid = $this->billid;
        include_once (JsConstants::$docRoot . "/billing/invoiceGenerate.php");
        $membershipMailer->sendWelcomeMailerToPaidUser(1835, $this->profileid, $bill, $this->serviceid);

        // global $payment_gateway;
        // //header
        // $msg = "Please add membership@jeevansathi.com to your address book to ensure delivery of this mail into you inbox.<br><br>";
        
        // $msg.= "Dear $USERNAME,\n\nThank you for subscribing to Jeevansathi.com.\n\nWe have received your payment of $type $amount on $entry_dt. Please use your ORDER ID. No. - $ORDERID-$ID in all your future communication with us in order for us to serve you better.\n\nCopy of your bill (BILL.pdf) has been attached with this mail. Kindly revert back for any discrepancies in the bill.";
        
        // if ($payment_gateway == 'CCAVENUE') {
        //     $msg.= "\n\nPlease Note :- The charge will appear on your credit card as 'ccavenue.com/charge'";
        // } 
        // elseif ($payment_gateway == 'TRANSECUTE') {
        //     $msg.= "\n\nPlease Note :- The charge that will be appearing on your Credit Card Statement will display pay.transecute.com";
        // } 
        // elseif ($payment_gateway == 'PAYPAL') {
        //     $msg.= "\n\nPlease Note :- The credit card transaction will appear on your bill as \"PAYPAL *JEEVANSATHI\".";
        // }
        
        // $msg.= "\n\nRegards,\nJeevansathi.com Team";
        
        //Footer
        // $mailerServiceObj = new MailerService();
        // $mailerLinks = $mailerServiceObj->getLinks();
        // $unsubscribeLink = $mailerLinks['UNSUBSCRIBE'];        
        // $msg.="<br><br>You have received this mail because your e-mail ID is registered with Jeevansathi.com. This is a system-generated e-mail, please don't reply to this message. To stop receiving these mails <a href='$unsubscribeLink/0/0' target='_blank'>Unsubscribe</a>.";

        // return $msg;
    }

    function isRenewable($profileid) {
        $purchasesObj = new BILLING_PURCHASES();
        $serviceStatusObj = new BILLING_SERVICE_STATUS();
        
        $myrow = $purchasesObj->getPurchaseCount($profileid);
        if ($myrow['COUNT'] > 0) {
            $row = $serviceStatusObj->getLastActiveServiceDetails($profileid);
            if ($row['EXPIRY_DT']) {
                if ($row['SERVICEID'] == "PL" || $row['SERVICEID'] == "CL" || $row['SERVICEID'] == "DL" || $row['SERVICEID'] == "ESPL" || $row['SERVICEID'] == "NCPL") {
                    return 1;
                } 
                else {
                    if ($row['DIFF'] > - 11 && $row['DIFF'] < 30) {
                        list($yy, $mm, $dd) = explode('-', $row["EXPIRY_DT"]);
                        $ts = mktime(0, 0, 0, $mm, $dd + 10, $yy);
                        $expiry_date = date("j-M-Y", $ts);
                        return $expiry_date;
                    } 
                    else if ($row['DIFF'] > - 11) return 1;
                    else return 0;
                }
            } 
            else return 0;
        } 
        else return 0;
    }

    function isRenewableEver($profileid, $billing_dt)
    {
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $row = $billingServStatObj->getIsRenewableEverContent($profileid, $billing_dt);
        if ($row['EXPIRY_DT']) {
            if ($row['SERVICEID'] == "PL" || $row['SERVICEID'] == "CL" || $row['SERVICEID'] == "DL" || $row['SERVICEID'] == "ESPL" || $row['SERVICEID'] == "NCPL") {
                return 1;
            } 
            else {
                if ($row['DIFF'] > - 11 && $row['DIFF'] < 30) return 1;
                else return 0;
            }
        }
        return 0;
    }
    
    function getMemUserType($profileid,$fromBackend="") {
        $billingPurObj = new BILLING_PURCHASES();
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $count = $billingPurObj->getPaidStatus($profileid);
        if ($count) {
            $row = $billingServStatObj->getLastActiveServiceDetails($profileid);
            if ($row['EXPIRY_DT']) {
                $memUpgradeOffset = intval("-".VariableParams::$memUpgradeConfig["mainMemUpgradeLimit"]);
                $memID     = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $row['SERVICEID']);
                
                if(strpos($memID[0], 'L')!=false){
                    $memID[0] = substr($memID[0],0,-1);
                    $memID[1] = 'L';
                }
                if ($row['SERVICEID'] == "PL" || $row['SERVICEID'] == "CL" || $row['SERVICEID'] == "DL" || $row['SERVICEID'] == "ESPL" || $row['SERVICEID'] == "NCPL") {
                    if($fromBackend != "discount_link" && $row['ACTIVE_DIFF'] <=0 && $row['ACTIVE_DIFF'] >= $memUpgradeOffset && in_array($memID[0], VariableParams::$memUpgradeConfig["excludeMainMembershipUpgrade"])==false
                       ){
                        return array(memUserType::UPGRADE_ELIGIBLE, $row["EXPIRY_DT"], $row["SERVICEID"]);
                    }
                    else{
                        return array(5, $row["EXPIRY_DT"], $row['SERVICEID']);
                    }
                } 
                else {
                    if ($row['DIFF'] >= - 10 && $row['DIFF'] < 30) {
                        list($yy, $mm, $dd) = explode('-', $row["EXPIRY_DT"]);
                        $ts = mktime(0, 0, 0, $mm, $dd + 10, $yy);
                        $ts1 = mktime(0, 0, 0, $mm, $dd, $yy);
                        $expiry_date_plus_10 = date("j-M-Y", $ts);
                        $expiry_date = date("j-M-Y", $ts1);
                        
                        if ($row['DIFF'] < 0) return array(4, $expiry_date_plus_10, $row["SERVICEID"]);
                        else{
                            if($fromBackend != "discount_link" && $row['ACTIVE_DIFF'] <=0 && $row['ACTIVE_DIFF'] >= $memUpgradeOffset && in_array($memID[0], VariableParams::$memUpgradeConfig["excludeMainMembershipUpgrade"])==false){
                                return array(memUserType::UPGRADE_ELIGIBLE, $expiry_date, $row["SERVICEID"]);
                            }
                            else{
                                return array(6, $expiry_date, $row['SERVICEID']);
                            }
                        }
                    } 
                    else if ($row['DIFF'] < - 10) {
                        return array(3, 0, $row["SERVICEID"]);
                    } 
                    else {
                        if($fromBackend != "discount_link" && $row['ACTIVE_DIFF'] <=0 && $row['ACTIVE_DIFF'] >= $memUpgradeOffset && in_array($memID[0], VariableParams::$memUpgradeConfig["excludeMainMembershipUpgrade"])==false){
                            return array(memUserType::UPGRADE_ELIGIBLE, 0, $row["SERVICEID"]);
                        }
                        else{
                            return array(5, 0, $row["SERVICEID"]);
                        }
                    }
                }
            } 
            else {
                $row1 = $row = $billingServStatObj->getLastActiveServiceDetailsWithoutMainFlag($profileid);
                if ($row1['DIFF'] > 0) return array(7, 0, 0);
                else return array(2, 0, 0);
            }
        } 
        else return array(2, 0, 0);
    }
    function getSubscriptionStatus($profileid, $module = NULL) {
        $cur_dt = date('Y-m-d');
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $profiles = $billingServStatObj->fetchSubscriptionStatusDetails($profileid, $cur_dt);
        $qwe = 0;
        $sidArr = array();
        $serviceObj = new Services;
        $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        if(is_array($profiles)){
	        foreach ($profiles as $key=>$row_last_service) {
	            if ($row_last_service["ADDON_ID"] != '') {
	                $www = explode(',', $row_last_service["ADDON_ID"]);
	                for ($i = 0; $i < count($www); $i++) {
	                    $aid = $www[$i];
	                    $sidArr[] = $aid;
	                    $last_active_services[$qwe]["SERVICEID"] = $aid;
	                    $last_active_services[$qwe]["LINK"] = 'N';
	                    $last_active_services[$qwe]["ACTIVATED_ON"] = $row_last_service["ACTIVATED_ON"];
	                    list($yy, $mm, $dd) = explode('-', $row_last_service["EXPIRY_DT"]);
	                    $last_active_services[$qwe]["EXPIRY_DT"] = $dd = $dd . '-' . $months[$mm - 1] . '-' . $yy;
	                    $qwe++;
	                }
	            }
	            $sid = $row_last_service["SERVICEID"];
	            $sidArr[] = $sid;
	            $last_active_services[$qwe]["SERVICEID"] = $sid;
	            if ($this->isMainMembership($sid)) {
	                if ($last_main != 'Y') {
	                    $last_active_services[$qwe]["LINK"] = 'Y';
	                    $last_main = 'Y';
	                }
	                if (strstr($sid, 'L')) {
	                    $last_active_services[$qwe]["LINK"] = 'B';
	                }
	            } 
	            else {
	                $last_active_services[$qwe]["LINK"] = 'N';
	            }
	            $last_active_services[$qwe]["SERVICE"] = $sname[$sid]["NAME"];
	            $last_active_services[$qwe]["ACTIVATED_ON"] = $row_last_service["ACTIVATED_ON"];
	            list($yy, $mm, $dd) = explode('-', $row_last_service["EXPIRY_DT"]);
	            $last_active_services[$qwe]["EXPIRY_DT"] = $dd = $dd . '-' . $months[$mm - 1] . '-' . $yy;
	            $qwe++;
	        }
	    }
        if(empty($module)){
        	if(is_array($sidArr) && !empty($sidArr)){
	           	$snameArr = $serviceObj->getServiceName($sidArr);
	           	foreach($last_active_services as $key=>$val){
	           		$last_active_services[$key]["SERVICE"] = $snameArr[$val['SERVICEID']]["NAME"];
	           	}
           	}
        }
        if (is_array($last_active_services)) return $last_active_services;
        else return false;
    }
    
    function voucher_optin($profileid) {
        $billingVouchViewedObj = new billing_VOUCHER_VIEWED();
        $billingVouchViewedObj->updateVoucherOption($this->profileid);
    }
    
    function log_payment_status($orderid, $status, $gateway, $msg, $profileid="") {
        $msg = addslashes(stripslashes($msg));
        $billingPayStatLog = new billing_PAYMENT_STATUS_LOG();
        $billingPayStatLog->insertEntry($orderid,$status,$gateway,$msg,$profileid);
    }
    
    function makePaid($skipBill = false,$memUpgrade = "NA",$orderid="",$doneUpto="") {
        $userObjTemp = $this->getTempUserObj();

        //set MemApiResponseHandler temp obj based on requirement
        $apiTempObj = $this->setApiTempObj($memUpgrade,$userObjTemp);
        
        $this->setRedisForFreeToPaid($userObjTemp);
        if($skipBill == true || in_array($doneUpto, array("PAYMENT_DETAILS","MEM_DEACTIVATION"))){
            $this->setGenerateBillParams();
        } else {
            $this->generateBill($memUpgrade);
        }
        
        if(in_array($doneUpto, array("PAYMENT_DETAILS","MEM_DEACTIVATION"))){
            $this->setGenerateReceiptParams();
        }
        else{
            $this->getDeviceAndCheckCouponCodeAndDropoffTracking();
            $this->generateReceipt();
        }
        if($memUpgrade != "NA" && $doneUpto!="MEM_DEACTIVATION"){
            $this->deactivateMembership($memUpgrade,$orderid);
        }
        $this->setServiceActivation();
        $this->populatePurchaseDetail($memUpgrade);
        $this->updateJprofileSubscription();
        
        $this->checkIfDiscountExceeds($userObjTemp,$memUpgrade,$apiTempObj);
        if($memUpgrade != "NA"){
            $memHandlerObj = new MembershipHandler(false);
            $memHandlerObj->updateMemUpgradeStatus($orderid,$this->profileid,array("UPGRADE_STATUS"=>"DONE","BILLID"=>$this->billid));
            unset($memHandlerObj);
        }

        //flush myjs cache after success payment
        if($this->profileid && !empty($this->profileid)){
            MyJsMobileAppV1::deleteMyJsCache(array($this->profileid));
        }
    }

    function setApiTempObj($memUpgrade="NA",$userObjTemp=""){
        if(in_array($memUpgrade, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
            //error_log("ankita set apiTempObj");
            $apiTempObj = new MembershipAPIResponseHandler();
            $purchasesObj = new BILLING_PURCHASES();
            $purchaseDetails = $purchasesObj->getCurrentlyActiveService($this->profileid,"PU.DISCOUNT_PERCENT");
            unset($purchasesObj);
            if(is_array($purchaseDetails) && $purchaseDetails['SERVICEID']){
                $apiTempObj->memID = @strtoupper($purchaseDetails['SERVICEID']);
                $apiTempObj->lastPurchaseDiscount = $purchaseDetails['DISCOUNT_PERCENT'];
            }
            else{
                $apiTempObj->memID = @strtoupper($purchaseDetails);
                $apiTempObj->lastPurchaseDiscount = 0;
            }
            
            $memHandlerObj = new MembershipHandler(false);
            if($userObjTemp != ""){
                $apiTempObj->subStatus = $memHandlerObj->getSubscriptionStatusArray($userObjTemp,null,null,$apiTempObj->memID);
            }
            else{
                $apiTempObj = "";
            }
            unset($memHandlerObj);
        }
        else{
            $apiTempObj = "";
        }
        return $apiTempObj;
    }

    /*function - deactivateMembership
    * deactivates currently active membership of user
    * @inputs: $memUpgrade="NA",$orderid=""
    * @outputs: none
    */
    function deactivateMembership($memUpgrade="NA",$orderid=""){
        $urlToHit = JsConstants::$siteUrl."/api/v1/membership/deactivateCurrentMembership";
        $profileCheckSum = JsAuthentication::jsEncryptProfilechecksum($this->profileid);
        $postParams = array("PROFILECHECKSUM"=>$profileCheckSum,"USERNAME"=>$this->username,"MEMBERSHIP"=>$memUpgrade,"NEW_ORDERID"=>$orderid);
        $deactivationResponse = CommonUtility::sendCurlPostRequest($urlToHit,$postParams,VariableParams::$memUpgradeConfig["deactivationCurlTimeout"]);
        if($deactivationResponse){
            $finalOutput = json_decode($deactivationResponse,true);
            //error_log("end of deactivateMembership...".$finalOutput["responseStatusCode"]);
        }
        else{
            $finalOutput = array("responseStatusCode"=>"1");
        }
        //if not deactivated then send alert mail
        if($finalOutput["responseStatusCode"] == "1"){
            $subject = "Curl hit to deactivate main membership failed";
            $msg = "Details: new_orderid {$orderid} in deactivateMembership called by makepaid function for Username : {$this->username}";
            foreach ($finalOutput as $key => $value) {
              $msg .= "key:".$key."-".$value;
            }
            SendMail::send_email("ankita.g@jeevansathi.com,vibhor.garg@jeevansathi.com",$msg,$subject);
        }
        unset($finalOutput);
        unset($deactivationResponse);
    }

    function getTempUserObj() {
        $memHandlerObj = new MembershipHandler();
        $userObj = new memUser($this->profileid);
        list($ipAddress1, $currency1) = $memHandlerObj->getUserIPandCurrency($this->profileid);
        $userObj->setIpAddress($ipAddress1);
        $userObj->setCurrency($currency1);
        $userObj->setMemStatus();
        return $userObj;
    }

    function setGenerateReceiptParams(){
        $billingPaymentDetObj = new BILLING_PAYMENT_DETAIL();
        $paymentDetailArr = $billingPaymentDetObj->getDetails($this->billid);
        if(is_array($paymentDetailArr)){
            $this->receiptid = $paymentDetailArr["RECEIPTID"];
        }
    }

    function setGenerateBillParams(){
        if (strstr($this->serviceid, 'C') || strstr($this->serviceid, 'P') || strstr($this->serviceid, 'ES') || strstr($this->serviceid, 'X') || strstr($this->serviceid, 'NCP')) {
            $this->membership = 'Y';
            $dur_arr = array('1W', '2W', '6W', '11', '12', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'L', '10');
            if (strstr($this->serviceid, 'C')) {
                $app = 'C';
            }
            else {
                $app = 'P';
            }
        } 
        else {
            $this->membership = 'N';
        }
        
        if ($this->curtype == 'RS') {
            $this->service_tax_content = billingVariables::SERVICE_TAX_CONTENT;
        } else {
            $this->service_tax_content = '';
        }

        //Field for identifying the team to which sales belong
        $jprofileObj = new JPROFILE();
        $myrow_sales = $jprofileObj->get($this->profileid,'PROFILEID');
        $this->sales_type = $myrow_sales['CRM_TEAM'];
    }
    
    function generateBill($memUpgrade = "NA") {

        if(empty($this->discount_type) || $this->discount_type == 0){
            $this->discount_type = 12;
        }

        $this->setGenerateBillParams();

        $geoIpCountryName = $_SERVER['GEOIP_COUNTRY_NAME'];
        if(!$geoIpCountryName){
		$geoIpCountryId = $_SERVER['GEOIP_COUNTRY_CODE'];
		if($geoIpCountryId){
			if($geoIpCountryId=='IN')
				$geoIpCountryName ='India';
			else
                		$geoIpCountryName =$geoIpCountryId;
		}
		else{
			if($this->curtype == 'RS')
				$geoIpCountryName ='India';
			else
				$geoIpCountryName ='Foreign';
		}
        }
        
        unset($modifiedServeFor);
        if(strstr($this->servefor,'X') && !strstr($this->servefor,'J')){
            $modifiedServeFor = $this->servefor.',J';
        }
        else{
            $modifiedServeFor = $this->servefor;
        }
        
        $this->discount_percent = round((($this->discount)/($this->amount+$this->discount)) * 100,2);

        //Generating Bill ID.
        $billingPurObj = new BILLING_PURCHASES();
        $paramsStr = "SERVICEID, PROFILEID, USERNAME, NAME, ADDRESS, GENDER, CITY, PIN, EMAIL, RPHONE, OPHONE, MPHONE, COMMENT, OVERSEAS, DISCOUNT, DISCOUNT_TYPE, DISCOUNT_REASON, WALKIN, CENTER, ENTRYBY, DUEAMOUNT, DUEDATE, ENTRY_DT, STATUS, SERVEFOR, VERIFY_SERVICE, ORDERID, DEPOSIT_DT, DEPOSIT_BRANCH, IPADD, CUR_TYPE, ENTRY_FROM, MEMBERSHIP, DOL_CONV_BILL, SALES_TYPE, SERVICE_TAX_CONTENT, COUNTRY,DISCOUNT_PERCENT";
        $valuesStr = "'$this->serviceid','$this->profileid','" . addslashes($this->username) . "','$this->name','" . addslashes($this->address) . "','$this->gender','$this->city','$this->pin','$this->email','$this->rphone','$this->ophone','$this->mphone','$this->comment','$this->overseas','$this->discount','$this->discount_type','$this->discount_reason','$this->walkin','$this->center','$this->entryby','$this->dueamount','$this->duedate',now(),'$this->status','$modifiedServeFor','$this->verify_service','$this->orderid','$this->deposit_dt','$this->deposit_branch','$this->ipadd','$this->curtype','$this->entry_from','$this->membership','$this->dol_conv_bill','$this->sales_type','$this->service_tax_content','$geoIpCountryName','$this->discount_percent'";
        if($memUpgrade != 'NA'){
            $paramsStr .= ", MEM_UPGRADE";
            $valuesStr .= ",'$memUpgrade'";
        }
        // TAX FOR RS ONLY
        if ($this->curtype == 'RS') {
            $paramsStr .= ", TAX_RATE";
            $valuesStr .= ",'$this->tax_rate'";
        }

        $this->billid = $billingPurObj->genericPurchaseInsert($paramsStr, $valuesStr);
        
        /**
         * Code added for tracking discount given per transaction
         */
        $memHandlerObj = new MembershipHandler();
        list($execName, $supervisor) = $memHandlerObj->getAllotedExecSupervisor($this->profileid);
        if(empty($supervisor)){
            $supervisor = 'rohan.m';
        }
        $servicesObj = new Services();
        $transObj = new billing_TRACK_TRANSACTION_DISCOUNT_APPROVAL();
        $serArr = $servicesObj->getServiceName($this->serviceid);
        foreach($serArr as $key=>$val){
            $services_names[] = $val['NAME'];
        }
        $serName = implode(",", $services_names);
        $iniAmt = $servicesObj->getTotalPrice($this->serviceid);
        $finAmt = round($iniAmt - $this->discount, 2);
        $discPerc = round((($iniAmt - $finAmt)/$iniAmt) * 100, 2);
        $transObj->insert($this->billid, $this->profileid, $this->discount_type, $supervisor, $discPerc, $iniAmt, $finAmt, $this->serviceid);
        if ($supervisor != 'rohan.m') {
            $jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
            $execEmail = $jsadminPswrdsObj->getEmail($supervisor);
            $subject = "Bill with discount of {$discPerc}% offered by {$execName}; Final Bill Amount: {$finAmt}";
            $msg = "Bill Details ({$serName})";
            $msg .= "Username : {$this->username} \n";
            $msg .= "Billid : {$this->billid} \n";
            SendMail::send_email($execEmail,$msg,$subject,$from="js-sums@jeevansathi.com",$cc="avneet.bindra@jeevansathi.com");
        }
        /**
         * End Code
         */
    }

    function getDeviceAndCheckCouponCodeAndDropoffTracking() {
        try {
            $ordrDeviceObj = new billing_ORDERS_DEVICE();
            $ordrDeviceObj->updateBillingDetails($this->orderid,$this->orderid_part1,$this->billid);
            $this->checkCoupon = $ordrDeviceObj->checkAppliedCoupon($this->orderid,$this->orderid_part1);
            $this->device = $ordrDeviceObj->getOrderDevice($this->orderid,$this->orderid_part1);
            if(!empty($this->checkCoupon) && $this->checkCoupon != ''){
                $couponOffrObj = new billing_COUPON_OFFER();
                $couponOffrObj->updateCouponCount($this->checkCoupon);
                unset($couponOffrObj);
            }
        }
        catch(Exception $e) {
            $msgContent = "OrderID: ".$this->orderid.", BillID: ".$this->billid."Coupon Code error encountered in generateBill for ".$this->username;
            send_email('vibhor.garg@jeevansathi',$msgContent,"payments@jeevansathi","avneet.bindra@jeevansathi.com");
            throw new jsException($e);
        }
        
        if(empty($this->device) || $this->device == ''){
            $this->device = 'desktop';
        }

        $billingDropSrcObj = new billing_DROPOFF_SOURCE_TRACKING();
        $billingDropSrcObj->deleteSourceTracking($this->profileid, $this->device);
    }

    function generateInvoiceNo(){
        $billyear = (date('m', strtotime(date("Y-m-d H:i:s")))<'04') ? date('y',strtotime('-1 year')) : date('y');
        $billid_toassign = $billyear;
        $d = $billid_toassign + 1;
        if ($d < 10) {
            $d = "0" . $d;
        }
        $billid_toassign.= $d;
        $serviceid_arr = @explode(",", $this->serviceid);
        for ($i = 0; $i < count($serviceid_arr); $i++) {
            $service_type[] = get_service_type($serviceid_arr[$i]);
        }
        $sid = end($serviceid_arr);
        if (@in_array("P", $service_type)) $billid_toassign.= "-F";
        if (@in_array("D", $service_type)) $billid_toassign.= "-D";
        if (@in_array("C", $service_type)) $billid_toassign.= "-C";
        if (strlen($this->serviceid) == 2) {
            if (strstr($sid, '2')) $billid_toassign.= "02";
            if (strstr($sid, '3')) $billid_toassign.= "03";
            if (strstr($sid, '4')) $billid_toassign.= "04";
            if (strstr($sid, '5')) $billid_toassign.= "05";
            if (strstr($sid, '6')) $billid_toassign.= "06";
        } 
        else $billid_toassign.= "12";
        $no_zero = 6 - strlen($this->billid);
        for ($i = 0; $i < $no_zero; $i++) $billid_toassign.= "0";
        $billid_toassign.= $this->billid;
        $invNo = $billid_toassign;
        return $invNo;
    }

    function generateReceipt() {

        //New invoice generation logic 
        if(date('Y-m-d H:i:s') > '2017-03-31 23:59:59')
            $invNo = $this->generateNewInvoiceNo();
        else
            $invNo = $this->generateInvoiceNo();
        $billingPaymentDetObj = new BILLING_PAYMENT_DETAIL();
        $paramsStr = "PROFILEID, BILLID, MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, BANK, OBANK, REASON, STATUS, BOUNCE_DT, ENTRY_DT, ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD,SOURCE,TRANS_NUM,INVOICE_NO";
        $valuesStr = "'$this->profileid','$this->billid','$this->mode','$this->curtype','$this->amount','$this->cheque_number','$this->cheque_date','$this->cheque_city','$this->bank','$this->obank','$this->reason','$this->status','$this->bounced_date',now(),'$this->entryby','$this->deposit_dt','$this->deposit_branch','$this->ipadd','$this->source','$this->transaction_number','$invNo'";
        if ($this->curtype == "DOL") {
            $paramsStr .= ", DOL_CONV_RATE";
            $valuesStr .= ",'$this->DOL_CONV_RATE'";
        }
        $this->receiptid = $billingPaymentDetObj->genericPaymentInsert($paramsStr, $valuesStr);
        if($this->status != 'REFUND'){
            $paramsStr = "RECEIPTID, ".$paramsStr;
            $valuesStr = "'$this->receiptid', ".$valuesStr;
            $negTransactionObj = new billing_PAYMENT_DETAIL_NEW();
            $negTransactionObj->insertRecord($paramsStr,$valuesStr);
        }
    }
    
    function setServiceActivation() {
        $serviceObj = new Services;
        if (strstr($this->serviceid, 'ES') || strstr($this->serviceid, 'NCP')) {
            $serarr = explode(",", $this->serviceid);
            for ($s = 0; $s < count($serarr); $s++) {
                if (strstr($serarr[$s], 'ES') || strstr($serarr[$s], 'NCP')) {
                    $serviceid_arr = $serviceObj->getPackServices($serarr[$s]);
                }
                else {
                    $serviceid_arr1[] = $serarr[$s];
                }
            }
            if (count($serviceid_arr1)) {
                $serviceid_arr = array_merge($serviceid_arr, $serviceid_arr1);
            }
        }
        elseif(strstr($this->serviceid, 'X')){
            $mainServ = $this->serviceid;
            $mainServDur = preg_replace("/[^0-9]/","",$mainServ);
            $mainServArr = explode(",", $mainServ);
            if(is_array($mainServArr)){
                foreach ($mainServArr as $k1 => $v1) {
                    if($v1 == 'XL'){
                        $mainServDur = 'L';
                    }
                    else if(strstr($v1, 'X')){
                        $mainServDur = preg_replace("/[^0-9]/","",$v1);
                    }
                }
            }
            $serviceArr = array();
            foreach(VariableParams::$jsExclusiveComboAddon as $key => $val){
                $serviceArr[] = $val.$mainServDur;
            }
            $serviceid_arr = @explode(",", $this->serviceid);
            $serviceid_arr = array_merge($serviceid_arr, $serviceArr);
        }
        else $serviceid_arr = @explode(",", $this->serviceid);
        for ($i = 0; $i < count($serviceid_arr); $i++) {
            unset($insert_query_str);
            $duration = $serviceObj->getDuration($serviceid_arr[$i]);
            $rights_str = @implode(",", $serviceObj->getRights($serviceid_arr[$i]));
            if(strstr($this->serviceid, 'NCP') && strstr($serviceid_arr[$i], 'C')){
            	$rights_str .= ",N";
            }
            $previous_expiry_date = $serviceObj->getPreviousExpiryDate($this->profileid, $rights_str);
            if ($previous_expiry_date) {
                $this->set_activate = 'Y';
                $activate_on = $previous_expiry_date["EXPIRY_DATE"];
                list($yy, $mm, $dd) = @explode("-", $activate_on);
                $ts = mktime(0, 0, 0, $mm, $dd + $duration, $yy);
                if ($duration == '35640') {
                    $expiry_date = '2099-01-01';
                }
                else {
                    $expiry_date = date("Y-m-d", $ts);
                }
                if ($rights_str == 'I') {
                    $insert_query_str = "'$this->billid','$this->profileid','$serviceid_arr[$i]','$service_components','Y','','$activate_on','$this->entryby','$expiry_date','Y','$rights_str'";
                }
                else {
                    $insert_query_str = "'$this->billid','$this->profileid','$serviceid_arr[$i]','$service_components','N','','$activate_on','$this->entryby','$expiry_date','Y','$rights_str'";
                }
                if (strstr($rights_str, 'F')) {
                    $this->start_direct_call($serviceid_arr[$i], '1');
                }
            } 
            else {
                $activated_on = date("Y-m-d");
                $ts = time();
                $ts += $duration * 24 * 60 * 60;
                if ($duration == '35640') $expiry_date = '2099-01-01';
                else $expiry_date = date("Y-m-d", $ts);
                $insert_query_str = "'$this->billid','$this->profileid','$serviceid_arr[$i]','$service_components','Y','$activated_on','','$this->entryby','$expiry_date','Y','$rights_str'";
                if ($rights_str == 'T' || $rights_str == 'L') $this->assisted_arr[] = $rights_str;
                elseif (strstr($rights_str, 'F')) $this->start_direct_call($serviceid_arr[$i], '0');
            }
            if ($serviceObj->getServiceType($serviceid_arr[$i]) == 'C') {
                $total = $serviceObj->getCount($serviceid_arr[$i]);
                $used = 0;
                $insert_query_str.= ",'$total','$used'";
            } 
            else {
                $insert_query_str.= ",0,0";
            }

            unset($previous_expiry_date);
            $billingServStatObj = new BILLING_SERVICE_STATUS();
            $paramsStr = "BILLID, PROFILEID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATE_ON, ACTIVATED_BY, EXPIRY_DT, ACTIVE, SERVEFOR,TOTAL_COUNT,USED_COUNT";
            $billingServStatObj->genericServiceInsert($paramsStr, $insert_query_str);
        }
        //$mailerService = "I";       //For We Talk For You
        $this->sendServiceBasedMailer($serviceid_arr);
    }
    
    function populatePurchaseDetail($upgradeMem="NA") {
        $serviceObj = new Services;
        if (strstr($this->serviceid, 'ES') || strstr($this->serviceid, 'NCP')) {
            $serarr = explode(",", $this->serviceid);
            for ($s = 0; $s < count($serarr); $s++) {
                if (strstr($serarr[$s], 'ES') || strstr($serarr[$s], 'NCP')) {
                    $serviceid_arr = $serviceObj->getPackServices($serarr[$s]);
                }
                else {
                    $serviceid_arr1[] = $serarr[$s];
                }
            }

            if (count($serviceid_arr1)) {
                $serviceid_arr = array_merge($serviceid_arr, $serviceid_arr1);
            }

            $services = implode(",", $serviceid_arr);
            $total = $serviceObj->getTotalPrice($services, $this->curtype, $this->device);
            $services = "'" . implode("','", $serviceid_arr) . "'";
            $combo_price = $serviceObj->getTotalPrice($this->serviceid, $this->curtype, $this->device);
            $combo_disc = $total - $combo_price;

            if ($combo_disc > 0) {
                $this->discount = $this->discount + $combo_disc;
            }
        } 
        else {
            $service_arr = explode(",", $this->serviceid);
            $services = implode(",", $service_arr);
            $total = $serviceObj->getTotalPrice($services, $this->curtype, $this->device);
            $services = "'" . implode("','", $service_arr) . "'";
        }

        $billingServObj = new billing_SERVICES();
        $serviceDetailsArr = $billingServObj->fetchAllServiceDetails($services);
        foreach ($serviceDetailsArr as $key=>$row_price) {
            $discnt = 0;
            if ($this->discount) {
                if($this->curtype == "DOL"){
                    $disc_sale_value = $this->discount * ($row_price[$this->device.'_DOL'] / $total);    
                } else {
                    $disc_sale_value = $this->discount * ($row_price[$this->device.'_RS'] / $total); 
                }
                
                $discnt = round($disc_sale_value, 2);
            }
            if($this->curtype == "DOL"){
                $amount[$row_price['SERVICEID']] = $row_price[$this->device.'_DOL'];
                $amount_net[$row_price['SERVICEID']] = $row_price[$this->device.'_DOL'] - $discnt;
            } else {
                $amount[$row_price['SERVICEID']] = $row_price[$this->device.'_RS'];
                $amount_net[$row_price['SERVICEID']] = $row_price[$this->device.'_RS'] - $discnt;
            }
            $sum+= $amount_net[$row_price['SERVICEID']];
        }

        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $billingDetails = $billingServStatObj->fetchAllServiceDetailsForBillid($this->billid);
        $billingPurDetObj = new billing_PURCHASE_DETAIL();

        foreach ($billingDetails as $key=>$row) {
            list($actual_start_date, $actual_end_date) = $this->calculateASSD_ASEDLogic($row);
            $price = $amount[$row['SERVICEID']];
            $net_price = $amount_net[$row['SERVICEID']];
            $discount = $price - $net_price;
            
            if ($sum != 0) {
                $share = round(($net_price / $sum) * 100, 2);
            }
            else {
                $share = 0;
            }
            
            $start_date = $row['ACTIVATED_ON'];

            if ($start_date == '0000-00-00') {
                $start_date = $row['ACTIVATE_ON'];
            }

            $end_date = $row['EXPIRY_DT'];
            
            if ($row['SERVICEID'] == 'PL' || $row['SERVICEID'] == 'CL' || strstr($row_price['SERVICEID'], 'I')) {
                $deferrable = 'N';
            }
            else {
                $deferrable = 'Y';
            }

            $paramsPDStr = "BILLID,SERVICEID,CUR_TYPE,PRICE,DISCOUNT,NET_AMOUNT,START_DATE,END_DATE,SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE,SHARE,PROFILEID,STATUS,DEFERRABLE";

            //handling for main membership upgrade
            if($price != 0 && $upgradeMem == 'MAIN'){
                $actualAmount = $this->amount + $this->discount;
                $valuesPDStr = "$this->billid,'" . $row['SERVICEID'] . "','$this->curtype','$actualAmount','$this->discount','$this->amount','$start_date','$end_date','$actual_start_date','$actual_end_date','$share','" . $row['PROFILEID'] . "','$this->status','$deferrable'";
            }
            else{
                $valuesPDStr = "$this->billid,'" . $row['SERVICEID'] . "','$this->curtype','$price','$discount','$net_price','$start_date','$end_date','$actual_start_date','$actual_end_date','$share','" . $row['PROFILEID'] . "','$this->status','$deferrable'";
            }
            $billingPurDetObj->genericPurchaseDetailInsert($paramsPDStr, $valuesPDStr);
            unset($paramsPDStr);
            unset($valuesPDStr);
            unset($deferrable);
            $billingPurDetObj->updateDiscountForBillid($discount, $this->billid, $row['SERVICEID']);
        }
    }

    function calculateASSD_ASEDLogic($row) {
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $billingPurDetObj = new billing_PURCHASE_DETAIL();
        $billingServObj = new billing_SERVICES();
        $serviceObj = new Services;
        $constantYears = 1;
        // ASSD / ASED logic start
        $duration = $serviceObj->getDuration($row['SERVICEID']);
        $rights_str = @implode(",", $serviceObj->getRights($row['SERVICEID']));
        if(strstr($this->serviceid, 'NCP') && strstr($row['SERVICEID'], 'C')){
            $rights_str .= ",N";
        }
        if(strstr($row['SERVICEID'], 'C') || strstr($row['SERVICEID'], 'P')) { // Main Service Check
            $pExpiry = $billingServObj->getPreviousExpiryDetails($this->profileid, $rights_str, 'Y');
        } else {
            $pExpiry = $billingServObj->getPreviousExpiryDetails($this->profileid, $rights_str);
        }
        if ($pExpiry) {
            list($yy, $mm, $dd) = @explode("-", $pExpiry["EXPIRY_DT"]);
            if ($yy == '2099' && $mm == '01') { // previous expiry is 2099 i.e. already unlimited running
                if ($duration == '35640') { // current is also unlimited duration
                    $actual_start_date = date("Y-m-d", time());
                    $actual_end_date = date("Y-m-d", time()+($constantYears*(365*24*60*60)));
                } 
                else // current is not unlimited duration 
                {
                    $actual_start_date = date("Y-m-d", time()); 
                    $actual_end_date = date("Y-m-d", time()+($duration*(24*60*60))); 
                }
            } 
            else if ($yy >= '2099')  // Case when another membership is already bought after unlimited plan
            {
                $serviceDates = $billingPurDetObj->selectActualDates($pExpiry['BILLID'], $pExpiry['SERVICEID']);
                $actual_start_date = $serviceDates['SUBSCRIPTION_END_DATE'];
                if ($duration == '35640') { // current is unlimited duration
                    $actual_end_date = date("Y-m-d", strtotime($actual_start_date)+($constantYears*(365*24*60*60)));
                } 
                else // current is not unlimited duration 
                {
                    $actual_end_date = date("Y-m-d", strtotime($actual_start_date)+($duration*(24*60*60)));
                }
            }
            else // previous expiry is not unlimited plan
            {
                if ($duration == '35640') { // current is unlimited duration
                    $actual_start_date = $row['ACTIVATED_ON'];
                    if ($actual_start_date == '0000-00-00') {
                        $actual_start_date = $row['ACTIVATE_ON'];
                    }
                    $actual_end_date = date("Y-m-d", strtotime($actual_start_date)+($constantYears*(365*24*60*60)));
                } 
                else // current is not unlimited duration
                {    // Here we pick the SUBSCRIPTION_END_DATE of previous service with similar rights
                     // and use that as SUBSCRIPTION_START_DATE for next service
                     // instead of referring to SERVICE_STATUS TABLE
                    $serviceDates = $billingPurDetObj->selectActualDates($pExpiry['BILLID'], $pExpiry['SERVICEID']);
                    $actual_start_date = $serviceDates['SUBSCRIPTION_END_DATE'];
                    if ($actual_start_date == '0000-00-00') {
                        $actual_start_date = $row['ACTIVATE_ON'];
                    }
                    $actual_end_date = date("Y-m-d", strtotime($actual_start_date)+($duration*(24*60*60)));
                }
            }
        } 
        else // no previous membership with current subscription rights
        {
            $actual_start_date = $row['ACTIVATED_ON'];
            if ($actual_start_date == '0000-00-00') {
                $actual_start_date = $row['ACTIVATE_ON'];
            }
            $actual_end_date = $row['EXPIRY_DT'];
        }
        // ASSD / ASED logic end
        return array($actual_start_date, $actual_end_date);
    }
    
    function updateJprofileSubscription() {
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $subscription = $billingServStatObj->getActiveSuscriptionString($this->profileid);
        
        if ($this->verify_service && $subscription) {
            $subscription = $subscription . ",S";
        }

        $subscription_arr = explode(",", $subscription);
        $subscription_arr = array_unique($subscription_arr);
        $subscription = implode(",", $subscription_arr);
        
        $jprofileObj = new JPROFILE();
        if ($subscription) {

            $jprofileObj->edit(array('SUBSCRIPTION'=>$subscription), $this->profileid, 'PROFILEID');
            
            // CLEAR MEMCACHE FOR CURRENT USER
	    	$memCacheObject = JsMemcache::getInstance();
	    	if($memCacheObject){
		        $memCacheObject->remove($this->profileid . '_MEM_NAME');
		        $memCacheObject->remove($this->profileid . "_MEM_OCB_MESSAGE_API17");
		        $memCacheObject->remove($this->profileid . "_MEM_HAMB_MESSAGE");
		        $memCacheObject->remove($this->profileid . "_MEM_SUBSTATUS_ARRAY");
		    }
            
            if (isset($_COOKIE['JSLOGIN'])) {
                $checksum = $_COOKIE['JSLOGIN'];
                list($val, $id) = explode("i", $checksum);
                $newjsConnObj = new newjs_CONNECT();
                $newjsConnObj->updateSubscriptionForId($this->subscription, $id);
            }
        }
        foreach ($this->assisted_arr as $k => $v) {
            if ($v == 'T') {
                startAutoApply($this->profileid, $this->walkin);
		addAutoApplyLog($this->profileid,'MEMBERSHIP',$v);
            }
            if ($v == 'L') {
                if (!in_array('T', $this->assisted_arr)) startHomeDelivery($this->profileid, '');
            }
        }
        if(strpos($this->serviceid, 'X')!==false)
        {
            $subject = $this->username . " has paid for Exclusive services";
            $msg = "Date: " . date("Y-m-d", strtotime($this->entry_dt)) . ", Amount: " . $this->curtype . " " . $this->amount; 
            SendMail::send_email('smarth.katyal@jeevansathi.com, suruchi.kumar@jeevansathi.com,webmaster@jeevansathi.com,rishabh.gupta@jeevansathi.com,kanika.tanwar@jeevansathi.com,princy.gulati@jeevansathi.com', $msg, $subject, 'payments@jeevansathi.com', 'rajeev.kailkhura@naukri.com,sandhya.singh@jeevansathi.com,anjali.singh@jeevansathi.com,deepa.negi@naukri.com');

            //add entry in EXCLUSIVE_MEMBERS TABLE
            $this->addExclusiveMemberEntry();
        }
        
        $this->sendInstantSms();
        $this->changeFTOState();
        
        $memCacheObject = JsMemcache::getInstance();
        $memCacheObject->remove($this->profileid . '_MEM_NAME');
        $memCacheObject->remove($this->profileid . "_MEM_OCB_MESSAGE_API17");
        $memCacheObject->remove($this->profileid . "_MEM_HAMB_MESSAGE");
        $memCacheObject->remove($this->profileid . "_MEM_SUBSTATUS_ARRAY");
    }

    function checkIfDiscountExceeds($userObj,$upgradeMem="NA",$apiTempObj="") {
        $memHandlerObj = new MembershipHandler();
        $serviceObj  = new billing_SERVICES();
        $servObj = new Services();
        $mainMembership = array_shift(@explode(",", $this->serviceid));
        
        if (strstr($mainMembership, 'C') || strstr($mainMembership, 'P') || strstr($mainMembership, 'ES') || strstr($mainMembership, 'X') || strstr($mainMembership, 'NCP')) {
        } else {
            $mainMembership = null;
        }
        $allMemberships = $this->serviceid;
        $festCondition = false;
        $fest = $servObj->getFestive();
        // Fetch all variables regarding discount for current user
        $excludeMains = array('PL','P12','CL','C12','ESPL','ESP12','NCPL','NCP12','XL','X12');
        if ($fest == 1 && !in_array($mainMembership, $excludeMains) && !empty($mainMembership)) {
            $festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
            $actualServiceid = $festOffrLookup->fetchReverseOfferedServiceId($mainMembership);
            if ($actualServiceid != $mainMembership && !empty($actualServiceid)) {
                $festCondition = true;
            }
        }

        if((!empty($this->checkCoupon) && $this->checkCoupon != '') || $festCondition){
            // Dont handle coupon code and when extra duration is offered in festive extra duration case
        } else {
            list($total, $discount) = $memHandlerObj->setTrackingPriceAndDiscount($userObj, $this->profileid, $mainMembership, $allMemberships, $this->curtype, $this->device, $this->checkCoupon, null, null, null, true,$upgradeMem,$apiTempObj);
            
            if ($total > $this->amount) {
                $iniAmt = $servObj->getTotalPrice($this->serviceid, $this->curtype);
                $actDisc = $iniAmt - $this->amount;
                $siteDisc = $iniAmt - $total;
                $actDiscPerc = round($actDisc/$iniAmt, 2)*100;
                $siteDiscPerc = round($siteDisc/$iniAmt, 2)*100;
                $netOffTax = round($this->amount*(1-billingVariables::NET_OFF_TAX_RATE),2);
        		if($actDiscPerc>=$siteDiscPerc)
        			$netDiscPer =$actDiscPerc-$siteDiscPerc;
        		if($netDiscPer>=5){
                    $msg = "'{$this->username}' has been given a discount greater than visible on site <br>Actual Discount Given : {$this->curtype} {$actDisc}, {$actDiscPerc}%<br>Discount Offered on Site : {$this->curtype} {$siteDisc}, {$siteDiscPerc}%<br>Final Billing Amount : {$this->curtype} {$this->amount}/-<br>Net-off Tax : {$this->curtype} {$netOffTax}/-<br><br>Note : <br>Discounts are inclusive of previous day discounts if applicable for the username mentioned above<br>Max of current vs previous day discount is taken as final discount offered on site !";
                    //error_log("ankita msg-".$msg);
                    if (JsConstants::$whichMachine == 'prod') {
                        SendMail::send_email('rohan.mathur@jeevansathi.com',$msg,"Discount Exceeding Site Discount : {$this->username}",$from="js-sums@jeevansathi.com");
                    }
        		}
            }
        }
    }
    
    function addExclusiveMemberEntry()
    {
        $exclusiveObj = new billing_EXCLUSIVE_MEMBERS();
        $detailsArr = array("PROFILEID"=>$this->profileid,"ASSIGNED_TO"=>NULL,"ASSIGNED"=>'N',"BILLING_DT"=>date("Y-m-d H:i:s"),"BILL_ID"=>$this->billid,"ASSIGNED_DT"=>'0000-00-00');
        $exclusiveObj->addExclusiveMember($detailsArr);
        unset($exclusiveObj);
    }

    function membership_mail() {
    	$membershipMailer = new MembershipMailer();
    	$receiptid = $this->receiptid;
        $billid = $this->billid;
        include_once (JsConstants::$docRoot . "/billing/invoiceGenerate.php");
        $membershipMailer->sendWelcomeMailerToPaidUser(1835, $this->profileid, $bill, $this->serviceid);

        // $serviceid_arr = @explode(",", $this->serviceid);
        // for ($i = 0; $i < count($serviceid_arr); $i++) $service_type[] = get_service_type($this->serviceid);

        //     $msg = "Dear $this->username,\n\nThank you for subscribing to Jeevansathi.com.\n\nWe have received your payment of $this->curtype $this->amount . \n\nCopy of your bill (BILL.pdf) has been attached with this mail. Kindly revert back for any discrepancies in the bill.";

        // $msg.= "\n\n\nWarm Regards,\nThe Jeevansathi Team\n";
        
        // return $msg;
    }
    
    function membership_details($serviceid = "") {
        $serviceObj = new Services;
        if ($this->serviceid == '') $this->serviceid = $serviceid;
        $serviceid_arr = @explode(",", $this->serviceid);
        $call_dir = array("P", "S", "PL", "C", "CL");
        for ($i = 0; $i < count($serviceid_arr); $i++) {
            $service_t = get_service_type($serviceid_arr[$i]);
            if (in_array($service_t, $call_dir)) $count = $serviceObj->getServiceDirectCalls($serviceid_arr[$i]);
            $service_type[] = $service_t;
        }

        if (@in_array("P", $service_type) || @in_array("S", $service_type) || @in_array("PL", $service_type)) {
            $msg.= " As an e-Rishta Member you can:\n Send personalized messages along with contact details directly to all members.\n View contact details like address and telephone numbers of members who accept your contact.\n Initiate chat using the Jeevansathi Messenger.\n Contact people instantly with $count Direct Calls.";
        }
        if (@in_array("D", $service_type)) $msg.= " As an e-Classified Member :\n Your profile along with your contact details (your e-mail ID and Telephone no.) will be visible to ALL Jeevansathi members and visitors.\n Quick Contact- Potential matches will be able to contact you directly, quickly.\n Saves time- You don’t have to visit the site regularly. People can get in touch with you through the contact details provided on your profile.";

        if (@in_array("C", $service_type) || @in_array("CL", $service_type)) {
            $msg.= " As an e-Value Pack Member :\n Your profile along with your contact details (your e-mail ID and Telephone no.) will be visible to ALL Jeevansathi members and visitors.\n Quick Contact- Potential matches will be able to contact you directly, quickly.\n Send personalized messages along with contact details directly to all members.\n View contact details like address and telephone numbers of members who accept your contact.\n Initiate chat using the Jeevansathi Messenger.\n Contact people instantly with $count Direct Calls.";
        }
        if (@in_array("M", $service_type)) $msg.= " Our profile writing experts with the help of precise and easy to understand questions understands & expresses your personality, background and likes and dislikes. This then helps to present your profile in a crisp and attractive manner.\n\nYour Matri-profile is created in easy steps:\n\nStep 1 --> our counselor will help you fill the simple objective type questionnaire.\n\nStep 2 --> our expert content developer writes your very own Matri Profile.";

        if (@in_array("SC", $service_type)) $msg.= " As our special Super Saver pack member, you are entitled to the following privileges, all through your membership tenure:\n With our Profile Highlighting, your matrimonial profile will appear in a \"coloured\" band with the username in bold for more prominence in our search result list.\n With our Astro Compatibility Service, you are just clicks away from checking your compatibility with other users based on astrological calculations including Guna matching and other details. A comprehensive report on compatibility will be available for your perusal as well on each of the profiles you choose to have it on.\n With our Matri Profile, you can avail the expertise of our profile writing experts. With the help of precise and easy to understand questions we understand your personality, background, likes, and dislikes. We then word them in a crisp and attractive manner.\n Sit back and relax. We will help you create your profile in 2 easy steps:\n Step 1 --> We will mail you an objective easy-to-fill questionnaire. Just fill it and mail it back to us.\n Step 2 --> Our expert content developers word your profile from the answers you provided.\n What are you waiting for? Log in and explore our list of matches. We will be ready to help you in making your partner search a smooth, hassle-free, and enjoying experience.";
        
        if (@in_array("B", $service_type)) $msg.= "\n With our Profile Highlighting, your matrimonial profile will appear in a \"coloured\" band with the username in bold for more prominence in our search results.";
        
        if (@in_array("A", $service_type)) $msg.= "\n With our Astro Compatibility Service, you are just clicks away from checking your compatibility with other users based on astrological calculations including Guna matching and other details. A comprehensive report on compatibility will be available for your perusal as well on each of the profiles you choose to have it on.";
        if (@in_array("T", $service_type)) $msg.= "\n With  our Auto-apply Service, we will send an expression of Interest on your behalf to a select set of members matching your criteria based on your partner preferences.";
        
        if (@in_array("L", $service_type)) $msg.= "\n With  our Profile home delivery Service, you will receive a set of members matching your criteria couriered right to your doorstep every fortnight. The number of profiles in each courier may vary.";
        
        if (@in_array("I", $service_type)) $msg.= "\n With  our Intro call Service, We will get you introduced to a list of people chosen by you so that you do not have to go through those embarrassing ice-breaking moments. Moreover we will also collect extra information of those chosen profiles that may help you decide on them faster.";
        if (@in_array("X", $service_type)) $msg.= "Personalized matrimonial search. \n Appointment of a personal advisor for your search. \n Profile matches sent after careful manual research across several sources by our advisors. \n Client Profiles offered complete confidentiality. \n Personalized meeting arrangements at several cities across India. ";
        
        $msg = str_replace("\n", "\par", $msg);
        return $msg;
    }
    
    function printbill($receiptid = "", $billid = "") {
        global $smarty;

        $serviceObj = new Services;
        $billingPurObj = new BILLING_PURCHASES();
        $billingServObj = new billing_SERVICES();
        $jsadminPswrdsObj = new jsadmin_PSWRDS();
        $newjsContactUsObj = new NEWJS_CONTACT_US();
        $jProfileObj =new JPROFILE('newjs_slave');

        if ($this->billid) {
            $billid = $this->billid;
        }
        if ($this->receiptid) {
            $receiptid = $this->receiptid;
        }
        if (!$this->dol_conv_bill) {
            $myrow = $billingPurObj->fetchAllDataForBillid($billid);
            $this->dol_conv_bill = $myrow['DOL_CONV_BILL'];
        }

        $ordrDeviceObj = new billing_ORDERS_DEVICE();
        $this->device = $ordrDeviceObj->getOrderDeviceFromBillid($billid);
        if(empty($this->device) || $this->device == ''){
        	$this->device = 'desktop';
        }

        if ($this->dol_conv_bill == 'Y') {
            if (!$this->serviceid) $this->serviceid = $myrow['SERVICEID'];
            $serviceids = explode(',', $this->serviceid);
            $sids = "'".implode("','", $serviceids)."'";
            $serviceDetailsArr = $billingServObj->fetchAllServiceDetails($sids);
            foreach ($serviceDetailsArr as $key=>$myrow) {
                $dol_conv_price[$myrow['SERVICEID']] = round(($myrow[$this->device.'_DOL'] * $this->DOL_CONV_RATE), 2);
            }
        }
        
        $billingPaymentDetObj = new BILLING_PAYMENT_DETAIL();
        $paymentDetailArr = $billingPaymentDetObj->fetchAllDataForReceiptId($receiptid);
        $billdate = $paymentDetailArr['ENTRY_DT'];
        $invoiceNo = $paymentDetailArr['INVOICE_NO'];
        if (!$billid) {
            $billid = $paymentDetailArr['BILLID'];
        }

        $billyear = (date('m', strtotime($billdate))<'04') ? date('y',strtotime('-1 year')) : date('y');
        $inv_dt_arr = explode(" ", $billdate);
        list($inv_year, $inv_month, $inv_day) = explode("-", $inv_dt_arr[0]);
        $inv_date = my_format_date($inv_day, $inv_month, $inv_year);
        
        $billid_toassign = $billyear;
        $d = $billid_toassign + 1;
        if ($d < 10) $d = "0" . $d;
        
        $billid_toassign.= $d;
        $i = 0;

        $printBillDataArr = $billingPurObj->fetchPrintBillDataForBillid($billid);
	$this->profileid =$printBillDataArr[0]['PROFILEID'];
	$jProfileArr = $jProfileObj->get($this->profileid,'PROFILEID','COUNTRY_RES,CITY_RES');

        $purDetRow = $billingPurObj->fetchAllDataForBillid($billid);
        $smarty->assign("eAdvantageService", substr($purDetRow['SERVICEID'],0,3));
        $smarty->assign("memUpgrage",$purDetRow['MEM_UPGRADE']);
        //Start:JSC-2632Changed to display complete service name and duration of membership plan in invoice 
        //$smarty->assign("eAdvantageServiceName", VariableParams::$mainMembershipNamesArr[substr($purDetRow['SERVICEID'],0,3)]);
        $ser_name = $serviceObj->get_servicename(substr($purDetRow['SERVICEID'],0,4));
        $smarty->assign("eAdvantageServiceName", $ser_name);
        //End:JSC-2632Changed to display complete service name and duration of membership plan in invoice 
        $smarty->assign("excludeInPrintBill", VariableParams::$excludeInPrintBill);
        unset($purDetRow);

        foreach ($printBillDataArr as $key=>$myrow) {
            $saleBy = $myrow['WALKIN'];
            $overseas = $myrow['OVERSEAS'];
            $order_dt = $myrow['ENTRY_DT'];
            $start_dt = $myrow['START_DATE'];
            $exp_dt = $myrow['END_DATE'];
            $sid = $myrow['SERVICEID'];
            $pid = $myrow['PROFILEID'];
            $username = $myrow['USERNAME'];
            $address = $myrow['ADDRESS'];
            $city = $myrow['CITY'];
            $pin = $myrow['PIN'];
            $email = $myrow['EMAIL'];
            $tax_rate = $myrow['TAX_RATE'];
            $cur_type = $myrow['CUR_TYPE'];
	    $entryBy =$myrow['ENTRYBY'];
            $memUpgrage = $myrow['MEM_UPGRADE'];
	    if($entryBy=='ONLINE')
		$ipCountry =$myrow['COUNTRY'];
	    $resCountryVal =$jProfileArr['COUNTRY_RES'];
	    $resCity =$jProfileArr['CITY_RES'];
	    $resCountry =FieldMap::getFieldLabel('country',$resCountryVal);	
	    if($resCountryVal==51 && $resCity){	
		$stateRes =substr($resCity,0,2);
	    	$stateRes =FieldMap::getFieldLabel('state_india',$stateRes);
                $resCountry =$stateRes." , ".$resCountry;
	    }
	
            if(stristr($myrow['SERVICE_TAX_CONTENT'],'swachh') && stristr($myrow['SERVICE_TAX_CONTENT'],'krishi')){ // this will occur only for billings occurring with swachh tax applied or krishi kalyan tax is applied
                $otherTaxes = billingVariables::SWACHH_TAX_RATE + billingVariables::KRISHI_KALYAN_TAX_RATE;
                $service_tax_content ="Service Tax @ ".($tax_rate-$otherTaxes)."%";
				$swachh_content = "Swachh Bharat Cess @ ".billingVariables::SWACHH_TAX_RATE."%";
                $smarty->assign("swachh_content",$swachh_content);
                $krishi_content = "Krishi Kalyan Cess @ ".billingVariables::KRISHI_KALYAN_TAX_RATE."%";
                $smarty->assign("krishi_content",$krishi_content);
            } else if(stristr($myrow['SERVICE_TAX_CONTENT'],'swachh')){ // this will occur only for billings occurring with swachh tax applied
				$service_tax_content ="Service Tax @ ".($tax_rate-billingVariables::SWACHH_TAX_RATE)."%";
				$swachh_content = "Swachh Bharat Cess @ ".billingVariables::SWACHH_TAX_RATE."%";
                $smarty->assign("swachh_content",$swachh_content);
			} else {
				$service_tax_content ="Service Tax @ "."$tax_rate".$myrow['SERVICE_TAX_CONTENT']."%";
			}
			$name = $myrow['NAME'];
            $services[] = $sid;
            
            $order_date = date("d-M-Y", JSstrToTime($order_dt));
            $start_dt = date("d-M-Y", JSstrToTime($start_dt));
            $exp_dt = date("d-M-Y", JSstrToTime($exp_dt));
            
            $ser_name = $serviceObj->get_servicename($myrow['SERVICEID']);
            $cost = round(($myrow[$this->device.'_RS'] * 100 / ($tax_rate + 100)), 2);
            if ($this->dol_conv_bill == 'Y') {
                $cost = $dol_conv_price[$sid];
            }
            $discount = $myrow['DISCOUNT'];
            $discount_tp = $myrow['DISCOUNT_TYPE'];
            
            list($cost_rs, $cost_paise) = explode(".", $cost);
            if ($cost_paise == '') $cost_paise = '00';
            elseif (strlen($cost_paise) == 1) $cost_paise.= '0';
            
            $servicecost_total+= $cost;
            if ($serviceObj->getServiceType($myrow['SERVICEID']) == 'C') {
                $qty = $serviceObj->getCount($myrow['SERVICEID']);
                $cost = $cost / $qty;
                $start_dt = '';
                $exp_dt = '';
                $ser_name_dur = $ser_name;
                $ser_name_arr = explode('-', $ser_name);
                $ser_name = $ser_name_arr['0'];
            } 
            else {
                $qty = "1";
                $ser_name_dur = $ser_name;
            }
            $ser[] = array("NUM" => $i + 1, "NAME" => $ser_name, "MEM_UPGRADE" => $memUpgrage, "NAME_DUR" => $ser_name_dur, "QTY" => $qty, "COST" => $cost, "COST_RS" => $cost_rs, "COST_PAISE" => $cost_paise, "S_DATE" => $start_dt, "E_DATE" => $exp_dt);
            $i++;
        }
        unset($i);
        $service_str = implode(',', $services);
        $this->serviceid = $service_str;
        $services_count = count($ser);
        $smarty->assign("services_count", $services_count);
        
        $membership_details = $this->membership_details($myrow['SERVICEID']);
        $smarty->assign("membership_details", $membership_details);
        $smarty->assign("main_ser_id", $myrow['SERVICEID']);
        
        $serviceid_arr = @explode(",", $this->serviceid);
        for ($i = 0; $i < count($serviceid_arr); $i++) $service_type[] = get_service_type($serviceid_arr[$i]);

        if (@in_array("P", $service_type)) $billid_toassign.= "-F";
        if (@in_array("D", $service_type)) $billid_toassign.= "-D";
        if (@in_array("C", $service_type)) $billid_toassign.= "-C";
        if (strlen($this->serviceid) == 2) {
            if (strstr($sid, '2')) $billid_toassign.= "02";
            if (strstr($sid, '3')) $billid_toassign.= "03";
            if (strstr($sid, '4')) $billid_toassign.= "04";
            if (strstr($sid, '5')) $billid_toassign.= "05";
            if (strstr($sid, '6')) $billid_toassign.= "06";
        } 
        else $billid_toassign.= "12";
        
        $no_zero = 6 - strlen($billid);
        for ($i = 0; $i < $no_zero; $i++) $billid_toassign.= "0";
            $billid_toassign.= $billid;
        
        //$discount=$myrow['DISCOUNT'];
        list($discount_rs, $discount_paise) = explode(".", $discount);
        if ($discount_paise == '') $discount_paise = '00';
        elseif (strlen($discount_paise) == 1) $discount_paise.= '0';
        if ($discount_tp == 1) $discount_type = "Renewal";
        elseif ($discount_tp == 2) $discount_type = "General";
        elseif ($discount_tp == 3) $discount_type = "Complementary";
        elseif ($discount_tp == 4) $discount_type = "Referral";
        elseif ($discount_tp == 5) $discount_type = "Special";
        elseif ($discount_tp == 6) $discount_type = "Festive";
        elseif ($discount_tp == 7) $discount_type = "Renewal and Festive";
        elseif ($discount_tp == 8) $discount_type = "Voucher code";
        elseif ($discount_tp == 9) $discount_type = "Special and Festive";
        
        $smarty->assign("saleBy", $saleBy);
        $smarty->assign("overseas", $overseas);
        $smarty->assign("order_date", $order_date);
        $smarty->assign("name", $name);
        $smarty->assign("address", $address);
        $smarty->assign("pin", $pin);
        $smarty->assign("receiptid", $receiptid);
        $smarty->assign("custno", $pid);
        $smarty->assign("discount_rs", $discount_rs);
        $smarty->assign("discount_paise", $discount_paise);
        $smarty->assign("discount", $discount);
        $smarty->assign("discount_type", $discount_type);
        $smarty->assign("username", $username);
        $smarty->assign("date", $inv_date);
        $smarty->assign("service_tax_content", $service_tax_content);

        $smarty->assign("city", $city);

        $myrow1 = $billingPaymentDetObj->fetchPrintBillDataForReceiptId($receiptid);
        
        $bill_date = date("d-M-Y", JSstrToTime($myrow1['ENTRY_DT']));
        $smarty->assign("bill_date", $bill_date);
        
        list($rec_dt, $rec_time) = explode(" ", $myrow1['ENTRY_DT']);
        $r_dt = explode("-", $rec_dt);
        $r_dt1 = '';
        for ($i = count($r_dt), $k = 0; $i >= 0; $i--, $k++) $r_dt1.= $r_dt[$i] . "-";
            $rdt = substr($r_dt1, 1, (strlen($r_dt1) - 2));
        $cd_dt = explode("-", $myrow1['CD_DT']);
        $cd_dt1 = '';
        for ($i = count($cd_dt), $k = 0; $i >= 0; $i--, $k++) $cd_dt1.= $cd_dt[$i] . "-";
            $cddt = substr($cd_dt1, 1, (strlen($cd_dt1) - 2));
        $smarty->assign("mode", $myrow1['MODE']);
        $smarty->assign("type", $myrow1['TYPE']);
        $smarty->assign("cdnum", $myrow1['CD_NUM']);
        $smarty->assign("cddt", $cddt);
        $smarty->assign("receipt_date", $rdt);
        $smarty->assign("cdcity", $myrow1['CD_CITY']);
        
        $feevalue = $myrow1['AMOUNT'];
        
        //$branch=$myrow1['DEPOSIT_BRANCH'];
        if ($saleBy != 'ONLINE') {
            $saleBy = trim($saleBy);
            $center = $jsadminPswrdsObj->getSubCenter($saleBy);
            $row_add = $newjsContactUsObj->fetchPrintBillData($center);
            $address_br = $row_add['ADDRESS'];
            $phone_br = $row_add['PHONE'];
            $mobile_br = $row_add['MOBILE'];
        }
        if ($saleBy == 'ONLINE' || $address_br == '') {
            $address_br1 = 'Head office : B - 8, Sector - 132, Noida - 201301';
            $phone_br = '120-3082000';
        }
        if ($address_br != '') $address_br1 = "Branch office : $address_br";
        
        // Address breakup
        $cnt = 0;
        $j = 0;
        $address_words_cnt = @explode(",", $address_br1);
        $one_row_cnt = round(count($address_words_cnt) / 4) + 1;
        foreach ($address_words_cnt as $key => $val) {
            if ($cnt == $one_row_cnt) {
                $cnt = 0;
                $j++;
            }
            $addressArr[$j].= $val . " ";
            $cnt++;
        }
        $address_br1_1 = $addressArr[0];
        $address_br1_2 = $addressArr[1];
        $address_br1_3 = $addressArr[2];
        $address_br1_4 = $addressArr[3];
        $smarty->assign("address_br1_1", $address_br1_1);
        $smarty->assign("address_br1_2", $address_br1_2);
        $smarty->assign("address_br1_3", $address_br1_3);
        $smarty->assign("address_br1_4", $address_br1_4);
        
        $smarty->assign("address_br", $address_br);
        $smarty->assign("address_br1", $address_br1);
        $smarty->assign("phone_br", $phone_br);
        $smarty->assign("mobile_br", $mobile_br);
        $smarty->assign("feevalue", $feevalue);
	$smarty->assign("ipCountry", $ipCountry);
        $smarty->assign("resCountry", $resCountry);
        
        // Cost value from payment without tax
        $feevalue_exTax = round(($feevalue * 100 / ($tax_rate + 100)), 2);
        list($feevalue_exTax_rs, $feevalue_exTax_paise) = explode(".", $feevalue_exTax);
        if ($feevalue_exTax_paise == '') $feevalue_exTax_paise = '00';
        elseif (strlen($feevalue_exTax_paise) == 1) $feevalue_exTax_paise.= '0';
        $smarty->assign("costvalue_exTax", $feevalue_exTax);
        $smarty->assign("costvalue_exTax_rs", $feevalue_exTax_rs);
        $smarty->assign("costvalue_exTax_paise", $feevalue_exTax_paise);
        //$smarty->assign("costvalue", $feevalue_exTax);
        list($feevalue_incTax_rs, $feevalue_incTax_paise) = explode(".", $feevalue);
        if($feevalue_incTax_paise == '') $feevalue_incTax_paise = '00';
        elseif (strlen($feevalue_incTax_paise) == 1) $feevalue_incTax_paise.= '0';
        $smarty->assign("costvalue_rs", $feevalue_incTax_rs);
        $smarty->assign("costvalue_paise", $feevalue_incTax_paise);
        
        // Sale amount value without tax
        list($scost_rs, $scost_paise) = explode(".", $servicecost_total);
        if ($scost_paise == '') $scost_paise = '00';
        elseif (strlen($scost_paise) == 1) $scost_paise.= '0';
        $smarty->assign("SUBTOTAL1", $servicecost_total);
        $smarty->assign("SUBTOTAL1_RS", $scost_rs);
        $smarty->assign("SUBTOTAL1_PS", $scost_paise);
        $servicecostvalue1 = $servicecost_total - $discount;
        if ($discount > 0) {
            // Sale amount value after discount
            list($servicecostvalue1_rs, $servicecostvalue1_paise) = explode(".", $servicecostvalue1);
            if ($servicecostvalue1_paise == '') $servicecostvalue1_paise = '00';
            elseif (strlen($servicecostvalue1_paise) == 1) $servicecostvalue1_paise.= '0';
            
            $smarty->assign("SUBTOTAL2", $servicecostvalue1);
            $smarty->assign("SUBTOTAL2_rs", $servicecostvalue1_rs);
            $smarty->assign("SUBTOTAL2_paise", $servicecostvalue1_paise);
        }
        $smarty->assign("TAX", "Y");
        $smarty->assign("TAX_RATE", $tax_rate);
        if (($tax_rate != '' || $tax_rate != 0)) {
            // Sale amount tax calculation
            $tax_ratevalue = round((($tax_rate / 100) * $servicecostvalue1), 2);
            $servicecostvalue = round($servicecostvalue1 + $tax_ratevalue);
            list($servicecostvalue_rs, $servicecostvalue_paise) = explode(".", $servicecostvalue);
            if ($servicecostvalue_paise == '') $servicecostvalue_paise = '00';
            elseif (strlen($servicecostvalue_paise) == 1) $servicecostvalue_paise.= '0';
            
            // Sale amount without tax
            if (strtotime($myrow1['ENTRY_DT']) > strtotime(date("2015-05-10 00:00:00"))) {
                $servicecostvalue_exTax = round(($feevalue * 100 / ($tax_rate + 100)), 2);
            } 
            else {
                $servicecostvalue_exTax = round(($servicecostvalue * 100 / ($tax_rate + 100)), 2);
            }
            list($servicecostvalue_exTax_rs, $servicecostvalue_exTax_paise) = explode(".", $servicecostvalue_exTax);
            if ($servicecostvalue_exTax_paise == '') $servicecostvalue_exTax_paise = '00';
            elseif (strlen($servicecostvalue_exTax_paise) == 1) $servicecostvalue_exTax_paise.= '0';
            $smarty->assign("servicecostvalue_exTax", $servicecostvalue_exTax);
            $smarty->assign("servicecostvalue_exTax_rs", $servicecostvalue_exTax_rs);
            $smarty->assign("servicecostvalue_exTax_paise", $servicecostvalue_exTax_paise);
            
            // Sale amount tax
            list($tax_ratevalue_rs, $tax_ratevalue_paise) = explode(".", $tax_ratevalue);
            if ($tax_ratevalue_paise == '') $tax_ratevalue_paise = '00';
            elseif (strlen($tax_ratevalue_paise) == 1) $tax_ratevalue_paise.= '0';
            
            $smarty->assign("TAX_RATEVALUE_rs", $tax_ratevalue_rs);
            $smarty->assign("TAX_RATEVALUE_paise", $tax_ratevalue_paise);
        } 
        
        // Cost value tax calculation distribution
        if (($tax_rate != '' || $tax_rate != 0)) {
            if(isset($swachh_content) && isset($krishi_content)){
                $tempTaxRate = $tax_rate-billingVariables::SWACHH_TAX_RATE-billingVariables::KRISHI_KALYAN_TAX_RATE;
                $tax_ratevalue1=round((($tempTaxRate/100)*$feevalue_exTax),2);
	        	list($tax_ratevalue_rs1,$tax_ratevalue_paise1)=explode(".",$tax_ratevalue1);
                
                $tax_ratevalue_swachh1=round(((billingVariables::SWACHH_TAX_RATE/100)*$feevalue_exTax),2);
	        	list($tax_ratevalue_rs_swachh1,$tax_ratevalue_paise_swachh1)=explode(".",$tax_ratevalue_swachh1);
	        	if($tax_ratevalue_paise_swachh1 == '')
                    $tax_ratevalue_paise_swachh1= '00';
	            elseif(strlen($tax_ratevalue_paise_swachh1)== 1)
                    $tax_ratevalue_paise_swachh1 .= '0';
	        	$smarty->assign("TAX_RATEVALUE_SWACHH_rs1",$tax_ratevalue_rs_swachh1);
	        	$smarty->assign("TAX_RATEVALUE_SWACHH_paise1",$tax_ratevalue_paise_swachh1);
                
                $tax_ratevalue_krishi=round(((billingVariables::KRISHI_KALYAN_TAX_RATE/100)*$feevalue_exTax),2);
	        	list($tax_ratevalue_rs_krishi,$tax_ratevalue_paise_krishi)=explode(".",$tax_ratevalue_krishi);
	        	if($tax_ratevalue_paise_krishi == '')
                    $tax_ratevalue_paise_krishi= '00';
	            elseif(strlen($tax_ratevalue_paise_krishi)== 1)
                    $tax_ratevalue_paise_krishi .= '0';
	        	$smarty->assign("TAX_RATEVALUE_KRISHI_rs1",$tax_ratevalue_rs_krishi);
	        	$smarty->assign("TAX_RATEVALUE_KRISHI_paise1",$tax_ratevalue_paise_krishi);
            }
            else if(isset($swachh_content)){ // calculating swachh bharat tax seperately
				$tempTaxRate = $tax_rate-billingVariables::SWACHH_TAX_RATE;
	        	$tax_ratevalue1=round((($tempTaxRate/100)*$feevalue_exTax),2);
	        	list($tax_ratevalue_rs1,$tax_ratevalue_paise1)=explode(".",$tax_ratevalue1);
	        	$tax_ratevalue_swachh1=round(((billingVariables::SWACHH_TAX_RATE/100)*$feevalue_exTax),2);
	        	list($tax_ratevalue_rs_swachh1,$tax_ratevalue_paise_swachh1)=explode(".",$tax_ratevalue_swachh1);
	        	if($tax_ratevalue_paise_swachh1 == '')
	           	$tax_ratevalue_paise_swachh1= '00';
	            elseif(strlen($tax_ratevalue_paise_swachh1)== 1)
	            $tax_ratevalue_paise_swachh1 .= '0';
	        	$smarty->assign("TAX_RATEVALUE_SWACHH_rs1",$tax_ratevalue_rs_swachh1);
	        	$smarty->assign("TAX_RATEVALUE_SWACHH_paise1",$tax_ratevalue_paise_swachh1);
	    	} else {
	    		$tax_ratevalue1=round((($tax_rate/100)*$feevalue_exTax),2);
	        	list($tax_ratevalue_rs1,$tax_ratevalue_paise1)=explode(".",$tax_ratevalue1);
	    	}
	        if($tax_ratevalue_paise1 == '')
	       	$tax_ratevalue_paise1= '00';
	        elseif(strlen($tax_ratevalue_paise1)== 1)
	       	$tax_ratevalue_paise1 .= '0';
	        $smarty->assign("TAX_RATEVALUE_rs1",$tax_ratevalue_rs1);
	        $smarty->assign("TAX_RATEVALUE_paise1",$tax_ratevalue_paise1);
        }
        
        $servicecost_rs = convert($servicecostvalue_rs);
        if ($servicecost_paise != '' && $servicecost_paise != '00' && $servicecost_paise != '0') {
            if ($myrow1['TYPE'] == "RS") $servicecost_paise = convert($servicecostvalue_paise) . "paise";
            elseif ($myrow1['TYPE'] == "DOL") $servicecost_paise = convert($servicecostvalue_paise) . "cents";
            $servicecost = $servicecost_rs . "and " . $servicecost_paise . " only";
        } 
        else $servicecost = convert($servicecostvalue_rs) . " only";
        
        // Display nothing if start or end date is greater than 2099
        foreach ($ser as $key=>&$val) {
            list($dd,$mm,$yy) = explode("-", $val['S_DATE']);
            list($dd1,$mm1,$yy1) = explode("-", $val['E_DATE']);
            if ($yy >= '2099') {
                $val['S_DATE'] = "-";
            }
            if ($yy1 >= '2099') {
                $val['E_DATE'] = "-";
            }
        }

        // Service cost net with tax
        $smarty->assign("servicecostvalue", $servicecostvalue);
        $smarty->assign("servicecostvalue_rs", $servicecostvalue_rs);
        $smarty->assign("servicecostvalue_paise", $servicecostvalue_paise);
        $smarty->assign("servicecost", $servicecost);
        $smarty->assign("SERVICES", $ser);
        $smarty->assign("countServices", count($ser));
        $smarty->assign("accountid", $pid);
        $smarty->assign("billid", $invoiceNo);

        $cost_rs = convert($feevalue_incTax_rs);
        if ($feevalue_incTax_paise != '' && $feevalue_incTax_paise != '00' && $feevalue_incTax_paise != '0') {
            if ($myrow1['TYPE'] == "RS") $cost_paise = convert($feevalue_incTax_paise) . "paise";
            elseif ($myrow1['TYPE'] == "DOL") $cost_paise = convert($feevalue_incTax_paise) . "cents";
            $cost = $cost_rs . "and " . $cost_paise . " only";
        } 
        else $cost = convert($feevalue_incTax_rs) . " only";
        
        $smarty->assign("cost", $cost);
        $output = $smarty->fetch("../jsadmin/BILL3.htm");
        
        //echo $output;die;
        if ($output == "ERROR") {
            echo "ERROR";
            exit;
        }
        return $output;
    }
    
    function updateEasyBill() {
        if (strstr(substr($this->source, 0, 2), "EB")) {
            $billingEasyBillObj = new billing_EASY_BILL();
            $billingEasyBillObj->updateEasyBill($this->billid, $this->transaction_number);
        }
    }
    
    function updateIvr() {
        if ($this->source == "IVR") {
            $billingIVRObj = new billing_IVR_DETAILS();
            $billingIVRObj->updateIvrDetails($this->billid, $this->entryby, $this->transaction_number);
        }
    }

    function updatePaymentCollectForAirex() {
        $incPaymentCollectObj = new incentive_PAYMENT_COLLECT();
        $incPaymentCollectObj->updatePaymentCollectForAirex($this->profileid);
    }
    
    function handleOfflineBilling($acceptance_count) {
        $jsadminOfflineBillingObj = new JSADMIN_OFFLINE_BILLING();
        $jsadminOfflineMatchesObj = new jsadmin_OFFLINE_MATCHES();
        $jprofileObj = new JPROFILE();
        $accAllowed = $jsadminOfflineBillingObj->fetchAccAllowed($this->profileid);
        $acceptance_count = $accAllowed + $acceptance_count;
        
        $jsadminOfflineBillingObj->updateActiveStatus('N',$this->profileid);
        
        $jsadminOfflineBillingObj->insertOfflineBillEntry($this->profileid, $this->billid, $acceptance_count);

        $jsadminOfflineMatchesObj->updateOfflineBillingDetails($this->profileid);
        
        $jprofileObj->updateOfflineBillingDetails($this->profileid);
    }
    
    function matri_questionnaire_mail($username, $bill_email, $alloted_to) {
        $serviceObj = new Services;
        if (@in_array('M', $serviceObj->getRights($this->serviceid))) {
            $msg_ques = "Dear Member ($this->username),\n\nThank you for registering with Jeevansathi.Com and giving us an opportunity to help you find your match better. You have availed one of our premium add on services, which is creation of Matri Profile. Attached is the questionnaire which you need to fill up for us to process your request.\n\nOnce you have completed the questionnaire, request you to mail this back to matriprofile@jeevansathi.com .\n\nWish you all the best in finding you perfect match at Jeevansathi.Com. For any Query or Feedback on Jeevansathi, Please feel free to email us at feedback@jeevansathi.com or visit us at www.jeevansathi.com \n\n\nWith Regards\nThe JeevanSathi.com Team";
            
            //$fileatt = "/usr/local/matri_profiles/matri_questionnaire.doc"; // Path to the file
            $fileatt = "../smarty/templates/jsadmin/matri_questionnaire.doc";
             // Path to the file
            $fileatt_type = "application/msword";
             // File Type
            $fileatt_name = "questionnaire.doc";
             // Filename that will be used for the file as the attachment
            $file = fopen($fileatt, 'rb');
            $data = fread($file, filesize($fileatt));
            fclose($file);
            $data = chunk_split(base64_encode($data));
            $mime_boundary = "==Multipart_Boundary_x" . md5(mt_rand()) . "x";
            $subject = "Matri Profile Questionnaire";
            $from = "matriprofile@jeevansathi.com";
            $from_name = "Jeevansathi Membership";
            if ($alloted_to != "") $bcc = $alloted_to;
            
            // message headers
            if ($bcc != "") $headers = "From: $from_name <$from> \r\n" . "Cc: $cc1,$cc2\r\n" . "Bcc: $bcc\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: multipart/mixed;\r\n" . " boundary=\"{$mime_boundary}\"";
            else $headers = "From: $from_name <$from> \r\n" . "Cc: $cc1,$cc2\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: multipart/mixed;\r\n" . " boundary=\"{$mime_boundary}\"";
            
            // message body
            $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $msg_ques . "\n\n";

            // insert a boundary to indicate start of the attachment
            $message.= "--{$mime_boundary}\n" . "Content-Type: {$fileatt_type};\n" . " name=$fileatt_name\n" . "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" . "--{$mime_boundary}--\n";
	    $canSendObj= canSendFactory::initiateClass(CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$bill_email,"EMAIL_TYPE"=>"29"),$this->profileid);
            $canSend = $canSendObj->canSendIt();
            if($canSend)
            {
	    	mail($bill_email, $subject, $message, $headers);
	    }	
        }
    }
    
    /*function to update subscription field in JPROFILE
    */
    function stop_service($billid, $profileid) {
        $cancel_arr = array();
        $servefor_arr = array();
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $details = $billingServStatObj->fetchAllServiceDetailsForBillid($billid);
        foreach ($details as $key=>$row_sel) {
            $cancel_arr[] = $row_sel["SERVEFOR"];
        }
        $billingServStatObj->updateActiveStatus('N', $billid);
        $DT = date('Y-m-d');
        $servefor = $billingServStatObj->getActiveSuscriptionString($profileid);
        $end_arr = array_diff($cancel_arr, $servefor_arr);
        if (in_array("T", $end_arr)) endAutoApply($profileid);
        if (in_array("L", $end_arr)) endHomeDelivery($profileid);
        if (in_array("I", $end_arr) || (!strstr('F', $servefor))) endIntroCalls($profileid);
        foreach ($cancel_arr as $key => $value) {
            if(strpos($value, 'X') !== false)
            {
                //remove entry from billing.EXCLUSIVE_MEMBERS table
                $exMemObj = new billing_EXCLUSIVE_MEMBERS();
                $exMemObj->removeExclusiveMemberEntry($profileid);
                break;
            }   
        }
        
        $jprofileObj = new JPROFILE();
        $jprofileObj->updateSubscriptionStatus($servefor, $profileid);
        unset($cancel_arr);
        unset($servefor_arr);
    }
    
    /*Function to change STATUS
    */
    function change_status($billid, $status) {
        $billingPurObj = new BILLING_PURCHASES();
        $billingPurDetObj = new billing_PURCHASE_DETAIL();
        $billingPurObj->updateStatus($status, $billid);
        $billingPurDetObj->updateStatus($status, $billid);
    }
    
    /*Function to get last expiry date of main service
    */
    function lastMainExpiryDate($profileid) {
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $row = $billingServStatObj->getLastMainExpiryDate($profileid);
        if ($row['EXPIRY_DT']) {
            if ($row['SERVICEID'] == "PL" || $row['SERVICEID'] == "CL") {
                $ret = 'L';
                return $ret;
            } 
            else {
                list($yy, $mm, $dd) = explode("-", $row['EXPIRY_DT']);
                $timestamp = mktime(0, 0, 0, $mm, $dd, $yy);
                $timestamp_10 = mktime(0, 0, 0, $mm, $dd + 10, $yy);
                $ser_arr['SHOW_10'] = date('d M Y', mktime(0, 0, 0, $mm, $dd + 10, $yy));
                if ($row['DIFF'] > 15) $ser_arr['EXPIRY_IN_15'] = 1;
                else if ($row['DIFF'] <= 15 && $row['DIFF'] > - 1) $ser_arr['EXPIRY_IN_15'] = 2;
                else if ($row['DIFF'] <= 0 && $row['DIFF'] > - 11) $ser_arr['EXPIRY_IN_15'] = 3;
                else $ser_arr['EXPIRY_IN_15'] = 0;
                $ser_arr['EXPIRY_DT'] = date('d M Y', $timestamp);
                $ser_arr['RENEW_DT'] = date('d M Y', $timestamp_10);
                $ser_arr['SERVICEID'] = $row['SERVICEID'];
                return $ser_arr;
            }
        } 
        else return;
    }
    
    function availableCount($profileid) {
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $profilesDetail = $billingServStatObj->getActiveConsumeProfileDetails($profileid);
        foreach ($profilesDetail as $key=>$row) {
            $serve = $row['SERVEFOR'];
            $avail = $row['TOTAL_COUNT'] - $row['USED_COUNT'];
            $arr[$serve]+= $avail;
        }
        if ($arr) return ($arr['I']);
        else return 0;
    }
    
    function consumeCount($profileid, $count) {
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $profilesDetail = $billingServStatObj->getActiveConsumeProfileDetails($profileid);
        $flag = $avail = $to_consume = 0;
        if(!empty($profilesDetail)){
            foreach ($profilesDetail as $key=>$row) {
                $id = $row['ID'];
                $serve = $row['SERVEFOR'];
                $avail = $row['TOTAL_COUNT'] - $row['USED_COUNT'];
                $arr[$serve]+= $avail;
                if ($serve == 'I') $multiple_cnt[$id] = $avail;
                if ($count <= $arr['I']) {
                    $flag = 1;
                    $to_consume = $count;
                    foreach ($multiple_cnt as $k => $v) {
                        $sid = $k;
                        $used = $v;
                        if ($to_consume <= $used) {
                            if ($to_consume == $used) $activeStatus = 'E';
                            else $activeStatus = NULL;
                            $billingServStatObj->updateConsumeCount($sid, $to_consume, $activeStatus);
                            break;
                        } 
                        else {
                            $activeStatus = 'E';
                            $billingServStatObj->updateConsumeCount($sid, $used, $activeStatus);
                            $to_consume-= $used;
                        }
                    }
                }
            }
        }
        return $flag;
    }
    
    function checkRange($profileid, $service) {
        global $serviceObj;
        foreach ($service as $k => $v) {
            $k1 = substr($v, 0, 1);
            if (substr($v, 1) == '1W') $vv = '0.17';
            else if (substr($v, 1) == '2W') $vv = '0.5';
            else if (substr($v, 1) == '6W') $vv = '1.5';
            else $vv = substr($v, 1);
            if ($vv == 'L') $vv = '100';
            $service_arr[$k1] = $vv;
        }
        if (array_key_exists('P', $service_arr)) $main = 'P';
        elseif (array_key_exists('C', $service_arr)) $main = 'C';
        else $main = '';
        $expiry_date = $serviceObj->getPreviousExpiryDate($profileid, "F");
        $a_dd = $service_arr[$main] * 30;
        if ($expiry_date) {
            $previous_expiry_date = $expiry_date['EXPIRY_DATE'];
            list($yy, $mm, $dd) = @explode("-", $previous_expiry_date);
            $tsm = mktime(23, 59, 59, $mm, $dd + $a_dd, $yy);
            if ($previous_expiry_date == '2099-01-01') $tsm = '9999999999';
        } 
        else {
            if ($main == '') if (array_key_exists('L', $service_arr) || array_key_exists('T', $service_arr)) {
            }
            $tsm = time();
            $tsm = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $tsm+= $a_dd * 24 * 60 * 60;
        }
        $check_service = array('L' => "Profile home delivery", 'T' => "Auto-apply");
        foreach ($check_service as $k => $v) {
            if (array_key_exists($k, $service_arr)) {
                $ed = $serviceObj->getPreviousExpiryDate($profileid, $k);
                $ped = $ed['EXPIRY_DATE'];
                list($y, $m, $d) = @explode("-", $ped);
                $a_d = $service_arr[$k] * 30;
                if ($ped) $ts = mktime(0, 0, 0, $m, $d + $a_d, $y);
                else {
                    $ts = time();
                    $ts = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                    $ts+= $a_d * 24 * 60 * 60;
                }
                if ($k == 'L') {
                    $auto_ed = $serviceObj->getPreviousExpiryDate($profileid, 'T');
                    $auto_ped = $auto_ed['EXPIRY_DATE'];
                    list($ay, $am, $ad) = @explode("-", $auto_ped);
                    $auto_d = $service_arr['T'] * 30;
                    if ($auto_ped) $auto_ts = mktime(0, 0, 0, $am, $ad + $auto_d, $ay);
                    else {
                        $auto_ts = time();
                        $auto_ts = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                        $auto_ts+= $auto_d * 24 * 60 * 60;
                    }
                    if (($auto_ts < $ts) && in_array("T", $service_arr))
                        $msg1.= "$v service duration should lie within Auto-apply service duration. ";
                }
            }
        }
        $msg.= $msg1;
        return $msg;
    }

    function start_direct_call($serviceid, $renew = 0) {
        $serviceObj = new Services;
        $count = $serviceObj->getServiceDirectCalls($serviceid);
        $jsadminContactAllotObj = new jsadmin_CONTACTS_ALLOTED();
        if ($count) {
            $row = $jsadminContactAllotObj->getAllotedContacts($this->profileid); 
            if ($renew && $row) {
                $jsadminContactAllotObj->updateAllotedContacts($this->profileid, $count);
            } 
            else {
                $jsadminContactAllotObj->replaceAllotedContacts($this->profileid, $count);
            }
        }
    }

    function getSpecialDiscount($profile) {
        $today = date('Y-m-d');
        $billingVarDiscObj = new billing_VARIABLE_DISCOUNT('newjs_masterRep');
        $row = $billingVarDiscObj->getDiscountDetails($profile);
        if ($row['DISCOUNT']) {
            $data['DISCOUNT'] = $row['DISCOUNT'];
            $data['EDATE'] = $row['EDATE'];
            return $data;
        } 
        else return 0;
    }

    public function getDiscountDetailsForProfile($profileid, $memID)
    {
        $vd_exist = $this->getSpecialDiscount($profileid);
        if(is_array($vd_exist))
        {
            $mem_duration = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);
            if(strpos($mem_duration[0], "L")){
                $mainService = substr($mem_duration[0], 0, 1);
                $mem_duration = "L";
            } else {
                $mainService = $mem_duration[0];
                $mem_duration = $mem_duration[1];
            }
            $billVDODObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
            $rows = $billVDODObj->getDiscountDetailsForProfile($profileid);
            foreach($rows as $key=>$val){
                if($val['SERVICE'] == $mainService){
                    $row = $rows[$key];
                }
            }
            if(in_array($mem_duration,array_keys($row))){
                $output = $row[$mem_duration];
            } else {
                $output = 0;
            }
        }
        else
            $output = 0;
        return $output;
    }

    // Function to return other discount for a profile
    function getOtherDiscount($serviceId) {
        $today = date('Y-m-d');
        $billingDiscOffrLogObk = new billing_DISCOUNT_OFFER_LOG();
        $idCheck = $billingDiscOffrLogObk->checkDiscountOffer();
        if (!$idCheck) return;
        $billingDiscOffrObj = new billing_DISCOUNT_OFFER();
        $discount = $billingDiscOffrObj->getDiscountOffer($serviceId);
        if ($discount > 0) {
            return $discount;
        } 
        else return;
    }

    // Function to return other backend link discount for a profile
    function getLinkDiscount($linkIdSel, $profileid) {
        $incPaymentCollectObj = new incentive_PAYMENT_COLLECT();
        $discount = $incPaymentCollectObj->getLinkDiscount($linkIdSel, $profileid);
        if($discount){
            return $discount;
        } else {
            return 0;
        }
    }

    function forPage3($main_service, $PRICE, $main_price) {
        $serObj = new Services;
        $disc = $this->isRenewable($this->profileid);
        global $smarty;
        $Spec_arr = $this->getSpecialDiscount($this->profileid);
        $Spec = $Spec_arr['DISCOUNT'];
        $smarty->assign('Spec', $Spec);
        global $renew_discount_rate;
        if ($disc) {
            $disc = 'Y';
            $DISCOUNT_TYPE = 1;
        } 
        elseif ($Spec) {
            $disc = 'Y';
            $DISCOUNT_TYPE = '5';
            $renew_discount_rate = $Spec;
        } 
        else $disc = 'N';
        $smarty->assign('DISC', $disc);
        if ($serObj->getFestive()) $Fest = 1;
        else $Fest = 0;
        if ($disc == 'Y') {
            $main_price_disc = $serObj->getDiscountedPrice($DISCOUNT_TYPE, $main_price, '', $this->profileid);
            $main_price = $main_price - $main_price_disc;
            $msg = $serObj->getDiscountMsg($DISCOUNT_TYPE, $Spec);
            $smarty->assign('DISCOUNT_MSG', $msg);
        }

        //if($Fest && ($main_service=='PL' || $main_service=='CL'))
        $festiveOfferLookupObj = new billing_FESTIVE_OFFER_LOOKUP();
        $festiveDiscountPercent = $festiveOfferLookupObj->getPercDiscountOnService($main_service);
        if ($Fest && $festiveDiscountPercent > 0) {
            if ($DISCOUNT_TYPE != 1) {
                $mon_off = 1;
                $discount = $serObj->getDiscountedPrice('6', $main_price, $main_service, $this->profileid);
                $main_price = $serObj->getOfferPrice($main_price, $festiveDiscountPercent);
                $smarty->assign("DISCOUNT", $discount);
                $msg = $serObj->getDiscountMsg('6', $festiveDiscountPercent);
                $smarty->assign('DISCOUNT_FMSG', $msg);
                $smarty->assign('Fest', 1);
                if ($Spec) $DISCOUNT_TYPE = 9;
                else $DISCOUNT_TYPE = 6;
            }
        } 
        elseif ($Fest) {
            if ($DISCOUNT_TYPE != 1) $DISCOUNT_TYPE = 6;
            else $DISCOUNT_TYPE = 7;
        }
        $tot_disc = $main_price_disc + $discount;
        if ($disc == 'Y' || $mon_off) $smarty->assign("DISCOUNTED_PRICE", $main_price_disc);

        $smarty->assign('DISCOUNT_TYPE', $DISCOUNT_TYPE);
        $PRICE-= $tot_disc;
        return $PRICE;
    }

    public function forOnline($allMemberships, $type, $mainServiceId, $link_discount = NULL, $paymentOptionSel = NULL, $device = 'desktop', $couponCodeVal = NULL,$apiResHandlerObj="")
    {
        if($apiResHandlerObj == "" || !($apiResHandlerObj->upgradeMem)){
            $upgradeMem = "NA";
        }
        else{
            $upgradeMem = $apiResHandlerObj->upgradeMem;
        }
        
        $profileid = $this->profileid;
        $userObj = new memUser($profileid);
        $userObj->setMemStatus();
        $userObj->setCurrency($type);
        $memHandlerObj = new MembershipHandler();
        $memApiFuncs = new MembershipApiFunctions();
        $memApiRespHandlerObj = new MembershipAPIResponseHandler();
        $servObj = new Services();

        $profileObj = LoggedInProfile::getInstance('newjs_slave',$profileid);
        $screeningStatus = $profileObj->getACTIVATED();

        if ($device == "iOS_app") {
            $main_service = $mainServiceId;
            $allMembershipsNew = $mainServiceId;
            $service_str_off = $allMemberships;
            $discount = 0;
            $discount_type = 12;
            $total = $servObj->getTotalPrice($allMemberships, $type, $device);
        }else if ($screeningStatus == "N") {
            $main_service = $mainServiceId;
            $allMembershipsNew = $allMemberships;
            $service_str_off = $allMemberships;
            $discount = 0;
            $discount_type = 12;
            $total = $servObj->getTotalPrice($allMemberships, $type, $device);
        }else {
            list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code,$upgradePercentArr,$upgradeActive) = $memHandlerObj->getUserDiscountDetailsArray($userObj, "L",3,$apiResHandlerObj,$upgradeMem);
        
            // Existing codes for setting discount type in billing.ORDERS
            // 10 - Backend Discount Link
            // 1 - Renewal Discount
            // 7 - Renewal + Festive Discount
            // 6 - Festive Discount
            // 11 - Cash Discount
            // 5 - Special Discount
            // 9 - Special + Festive Discount
            // 12 - Others / Default
            // 14 - Coupon Code was applied 

            if ($link_discount) {
                $id_arr = explode("i", $link_discount);
                if (md5($id_arr[1]) == $id_arr[0]) {
                    $discount_type = 10;
                    $backendRedirect = 1;
                    $profileCheckSum = md5($profileid)."i".$profileid;
                    $reqid = $link_discount;
                }
            } else if ($renewalActive){
                $discount_type = 1;
                if ($fest) {
                    $discount_type = 7;
                }
            } else if ($fest) {
                $discount_type = 6;
            } else if ($discountActive) {
                $discount_type = 11;
            } else if ($specialActive) {
                $discount_type = 5;
                if ($fest) {
                    $discount_type = 9;
                }
            } else if($upgradeActive == "1"){
                $discount_type = 15;
            } else {
                $discount_type = 12;
            }

            $allMembershipsNew = rtrim($allMemberships, ",");

            if ($fest && $mainServiceId) { // Get reverse Offer Mapping in case of festive 
                $allMembershipsNew = $servObj->OfferMapping($allMembershipsNew);
            }

            if (!$mainServiceId) {
                $allMembershipsNew = $allMemberships;
            }

            $allMembershipsNew = rtrim($allMembershipsNew, ",");
            
            list($total, $discount) = $memHandlerObj->setTrackingPriceAndDiscount($userObj, $profileid, $mainServiceId, $allMemberships, $type, $device, $couponCode, $backendRedirect, $profileCheckSum, $reqid,false,$upgradeMem,$apiResHandlerObj);
        }

        if ($couponCodeVal && $mainServiceId) {
            $discountVal = $memHandlerObj->validateCouponCode($mainServiceId, $couponCodeVal);
            if (is_numeric($discountVal) && $discountVal > 0) {
                $additionalDisc = round(($total*$discountVal)/100, 2);
                $discount = $discount + $additionalDisc;
                $total = $total - $additionalDisc;
                $discount_type = 14;
            }
        } 

        $payment['total'] = $total;
        $payment['service_str'] = $allMembershipsNew;
        $payment['discount'] = $discount;
        $payment['discount_type'] = $discount_type;

        $service_str_off = rtrim($allMembershipsNew, ",");

        if ($device == "JSAA_mobile_website") {
            $device = 'Android_app';
        }

        if ($allMembershipsNew != $allMemberships && JsConstants::$whichMachine == 'prod') {
            $msg = "Mismatch in services sent to forOnline '{$allMemberships}' vs final sent to newOrder '{$allMembershipsNew}'<br>Profileid : '{$profileid}', Device : '{$device}'";
            SendMail::send_email('avneet.bindra@jeevansathi.com', $msg, 'Mismatch in forOnline function', $from = "js-sums@jeevansathi.com", $cc = "vibhor.garg@jeevansathi.com,vidushi@naukri.com");
        }

        $memHandlerObj->trackMembership($userObj, '', '', $service_str_off, '', $discount, $total, $paymentOptionSel, 'F', $device);
        return $payment;
    }

    function sendInstantSms() {
        include_once (JsConstants::$docRoot . "/profile/InstantSMS.php");
        $sms = new InstantSMS("PAYMENT_PHONE_VERIFY", $this->profileid);
        $sms->send();
        if ($this->amount > 0 && $this->curtype == "RS") {
            $sms = new InstantSMS("PAYMENT_ANY", $this->profileid, array("PAYMENT" => $this->amount));
            $sms->send();
        }
        if ($this->membership == 'Y') {
            $sms = new InstantSMS("PAYMENT_MEMBERSHIP", $this->profileid);
            $sms->send();
        }
    }

    function changeFTOState() {
        include_once (JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php");
        $ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($this->profileid);
        if (1)
         //$ftoStateArray['STATE']=='DUPLICATE')
        {
            include_once (JsConstants::$docRoot . "/../apps/operations/lib/JsCommonLib.class.php");
            $checked[0] = $this->profileid;
            JsOpsCommon::updateFtoStatus($checked, 1);
        } 
        else {
            $action = FTOStateUpdateReason::TAKE_MEMBERSHIP;
            SymfonyFTOFunctions::updateFTOState($this->profileid, $action);
        }
    }

    function isMainMembership($serviceid) {
        foreach (VariableParams::$mainMembershipsArr as $k => $v) {
            if (strpos($serviceid, $v) === 0) return true;
        }
    }

    function getMaxVdDiscount($discount) {
        include_once (JsConstants::$cronDocRoot . "/lib/model/enums/Membership.enum.class.php");
        $renew_discount_rate = userDiscounts::RENEWAL;
        if ($discount) $maxDiscount = max($renew_discount_rate, $discount);
        else $maxDiscount = $renew_discount_rate;
        return $maxDiscount;
    }

    /*send mail on activation of some services
    * @params: $serviceid_arr
    */
    public function sendServiceBasedMailer($serviceid_arr)
    {
        $mailToBeSend = false;
        $index = 0;
        foreach($serviceid_arr as $key=>$value)
        {
            if(strpos($value,"I") !== false || strpos($value,"T") !== false)
            {
                $mailToBeSend = true;
                $mailEligibleService[$index++] = $value;
            }
        }
        if($mailToBeSend==true)
        {
            $memHandlerObj = new MembershipHandler();
            $profileDetails = $memHandlerObj->getUserData($this->profileid);
            $mailerObj = new MembershipMailer();
            $profileDetails["PROFILEID"] = $this->profileid;
            foreach ($mailEligibleService as $key => $value) {
                          
                list($serviceID,$serviceDuration) = sscanf($value, "%[A-Z]%d");
                switch($serviceID)
                {
                    case "I": $mailId = 1803; //For We talk for you usage description mail.
                              break;
                    case "T": $mailId = 1807; //For RB activation mail.
                              $profileDetails["SERVICE_DURATION"] = $serviceDuration;
                              break;
                          
                }
                $mailerObj->sendServiceActivationMail($mailId, $profileDetails);
            }
        }
    }
    
    public function generateNewInvoiceNo(){
        $fullYr = date('Y');
        $yr = date('y');$mn = date('m');$dt = date('d');
        $autoIncReceiptidObj = new billing_AUTOINCREMENT_RECEIPTID('newjs_master');
        if($mn == "04" && $dt == "01"){
            //truncate table logic
            $result = $autoIncReceiptidObj->getLastInsertedRow();
            if($result["ENTRY_DT"]<$fullYr."-"."04"."-"."01 00:00:00"){
                $autoIncReceiptidObj->truncateAutoIncrementReceiptIdTable();
            }
        }
        $id = $autoIncReceiptidObj->insertNewAutoIncrementReceiptId();
        
        $id = ($id+1)/2; //To get continuation series. On live auto increment stores only odd number series
        $trailingZero = 7 - strlen($id);
        if($mn == "01" || $mn == "02" || $mn == "03" )
            $receiptId = ($yr-1).$yr."-";
        else
            $receiptId = $yr.($yr+1)."-";
        for($i = 0;$i<$trailingZero;$i++) $receiptId.="0";
        $receiptId.=$id;
        
        $finalReceiptid = "JS-".$receiptId;
        return $finalReceiptid;
    }
    
    public function setRedisForFreeToPaid($userObjTemp){
        if($userObjTemp->profileid && $userObjTemp->userType == memUserType::FREE)
        {
            JsMemcache::getInstance()->set("FreeToP_$userObjTemp->profileid",date("Y-m-d H:i:s"),604800);
            $this->sendMailForPaidUser("Redis Key Set for ".$userObjTemp->profileid." user type: ".$userObjTemp->userType,"Key set");
        }
        else{
            $this->sendMailForPaidUser("Redis Key Not Set for ".$userObjTemp->profileid." user type: ".$userObjTemp->userType,"Key not set");
        }
        
    }
    
    public function sendMailForPaidUser($msg,$subject){
        $to = "nitishpost@gmail.com";
        $from = "info@jeevansathi.com";
        $from_name = "Jeevansathi Info";
        SendMail::send_email($to,$msg, $subject, $from,"","","","","","","1","",$from_name);
    }
}
?>
