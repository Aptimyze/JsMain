<?php

/*********************************************************************************************
 * FILE NAME   : functions.php
 * DESCRIPTION : Contains functions to help Online payment gateway
 * Author      : Kush Asthana
 * Copyright  2005, InfoEdge India Pvt. Ltd.
 *********************************************************************************************/

include_once (JsConstants::$docRoot . "/commonFiles/comfunc.inc");
include_once (JsConstants::$docRoot . "/billing/comfunc_sums.php");
include_once (JsConstants::$docRoot . "/classes/globalVariables.Class.php");
include_once (JsConstants::$docRoot . "/classes/Mysql.class.php");
include_once (JsConstants::$docRoot . "/classes/Memcache.class.php");
include_once (JsConstants::$docRoot . "/classes/Jpartner.class.php");
include_once (JsConstants::$docRoot . "/classes/shardingRelated.php");
include_once (JsConstants::$docRoot . "/commonFiles/flag.php");
include_once (JsConstants::$docRoot . "/profile/contacts_functions.php");
include_once (JsConstants::$cronDocRoot . "/lib/model/enums/Membership.enum.class.php");
include_once (JsConstants::$cronDocRoot . "/lib/model/lib/SendMail.class.php");
include_once (JsConstants::$docRoot . "/classes/JProfileUpdateLib.php");

$error_msg = "Due to some temporary problem your request could not be processed. Please try after some time.";

//$announce_to_email = "alok@jeevansathi.com";
$announce_to_email = "vibhor.garg@jeevansathi.com";
$DOL_CONV_RATE = 60;
$tax_rate = billingVariables::TAX_RATE;
$net_off_tax_rate = billingVariables::NET_OFF_TAX_RATE;
$renew_discount_rate = 15;
$voucher_discount_rate = 15;
$payment_gateway = '';

/***********************************************************************
 *    DESCRIPTION   :	Update ORDERS table after returning from payment gateway
 *    RETURNS       :    Returns true if successful and false if not
 ***********************************************************************/
function updtOrder($ORDERID, &$dup, $updateStatus = 'Y') {
    
    //mail("alok@jeevansathi.com","Capture orderid","orderid - $ORDERID :: Authdesc - $updateStatus");
    global $error_msg, $announce_to_email;
    
    list($part1, $part2) = explode('-', $ORDERID);
    
    $sql = "select * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
    
    //$sql = "select * from ORDERS where ORDERID = '$ORDERID'";
    $res = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate");
    
    if (!mysql_num_rows($res)) {
        SendMail::send_mail('vibhor.garg@jeevansathi.com', "Record not found for $ORDERID", "Record not found for $ORDERID", 'js-sums@jeevansathi.com', 'avneet.bindra@jeevansathi.com');
        //mail($announce_to_email, "Record not found for $ORDERID", "In function updtOrder, script name functions.php\nmysql_num_rows is : " . mysql_num_rows($res));
        $ret = false;
    } 
    else {
        $myrow = mysql_fetch_array($res);
        
        /*condition $myrow["STATUS"] == 'N' added by Alok on 12th Aug 2005*/
        if ($myrow["PMTRECVD"] == '0000-00-00' || $myrow["STATUS"] == 'N') {
            $date = date("Y-m-d", time());
            $sql_updt = "update billing.ORDERS set PMTRECVD='$date', STATUS = '$updateStatus' where ID = '$part2' and ORDERID = '$part1'";
            $res_updt = mysql_query_decide($sql_updt) or logError($error_msg, $sql_updt, "ShowErrTemplate", $announce_to_email);
            if (mysql_affected_rows_js()) $ret = true;
            else $ret = false;
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
            SendMail::send_mail('vibhor.garg@jeevansathi.com', "PMTRECVD already populated for $ORDERID", "PMTRECVD already populated for $ORDERID", 'js-sums@jeevansathi.com', 'avneet.bindra@jeevansathi.com');
            //mail($announce_to_email, "PMTRECVD failed for $ORDERID", "in funcion updtOrder script name funcion.php\nPMTRECVD already populated for $ORDERID");
            $dup = true;
            $ret = true;
        }
    }
    return $ret;
}

/***********************************************************************
 *    DESCRIPTION   :	Find the data associated with user corresponding to PROFILEID
 *    RETURNS       :    Returns array of associated values against PROFILEID
 ***********************************************************************/
function getProfileDetails($profileid) {
    
    global $error_msg;
    $sql = "select count(*) as NUM from billing.ORDERS where PROFILEID = '$profileid' and EXPIRY_DT >= CURDATE()";
    $res = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate");
    $myrow = mysql_fetch_array($res);
    
    $numrows = $myrow["NUM"];
    
    $sql = "select PROFILEID, USERNAME, CONTACT, CITY_RES, COUNTRY_RES, PHONE_RES, PHONE_MOB, PINCODE, SUBSCRIPTION, EMAIL from newjs.JPROFILE where PROFILEID = '$profileid'";
    $res = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate");
    $myrow = mysql_fetch_array($res);
    
    if (strstr($myrow["SUBSCRIPTION"], "F") && ($numrows > 0)) $data["ACTIVE"] = "YES";
    else $data["ACTIVE"] = "NO";
    
    if ($phone = $myrow["PHONE_RES"]);
    else if ($phone = $myrow["PHONE_MOB"]);
    else $phone = "";
    
    $country_res = label_select("COUNTRY", $myrow["COUNTRY_RES"]);
    
    if ($myrow["COUNTRY_RES"] == "51") $city_res = label_select("CITY_INDIA", $myrow["CITY_RES"]);
    elseif ($myrow["COUNTRY_RES"] == "128") $city_res = label_select("CITY_USA", $myrow["CITY_RES"]);
    else $city_res[0] = "";
    
    //
    $fblObj = new FieldMap();
    $state_code = substr($myrow["CITY_RES"], 0, 2);
    $data["STATE"] = $fblObj->getFieldLabel("state_india", "$state_code", "");
    
    //
    $data["USERNAME"] = $myrow["USERNAME"];
    $data["CONTACT"] = $myrow["CONTACT"];
    $data["COUNTRY"] = ($city_res[0] ? "$city_res[0]," : "") . $country_res[0];
    $data["PHONE"] = $phone;
    $data["EMAIL"] = $myrow["EMAIL"];
    $data["CONTACT"] = $myrow["CONTACT"];
    $data["PINCODE"] = $myrow["PINCODE"];
    
    return $data;
}

/***********************************************************************
 *    DESCRIPTION   :	Find Rights for Selected services (main + addons)
 *    RETURNS       :    Returns string of comma seperated rights for services
 ***********************************************************************/
function serve_for($service_main, $service_str) {
    if (!empty($service_main)) {
        $service_main_details = getServiceDetails($service_main);
        $serve_for[] = $service_main_details["RIGHTS"];
    }
    if (strlen($service_str) > 0) {
        $serve_paid = explode(",", $service_str);
        for ($i = 0; $i < count($serve_paid); $i++) {
            $service_detail = getServiceDetails($serve_paid[$i]);
            $serve_for[] = $service_detail["RIGHTS"];
            $addon_serviceid[] = $serve_paid[$i];
        }
    }
    if (count($serve_for) > 0) $serve_for_str[] = implode(",", $serve_for);
    $serve_for_str = implode(",", $serve_for_str);
    $addon_serviceid = implode(",", $addon_serviceid);
    return array($serve_for_str, $addon_serviceid);
}

/***********************************************************************
 *    DESCRIPTION   :	Find the properties associated with service like RIGHTS, DURATION, ID etc
 *    RETURNS       :    Returns associative array indexing keys as properties to their values
 ***********************************************************************/
function getServiceDetails($serviceid) {
	$billServObj = new billing_SERVICES();
	$serviceid = implode("','",explode(",", $serviceid));
	$myrow = $billServObj->fetchAllServiceDetails($serviceid);
    $myrow = $myrow[0];
    
    if ($myrow["PACKAGE"] == "Y") {
    	$billCompObj = new billing_COMPONENTS();
        $row = $billCompObj->getDurationRightsForServiceDetails($serviceid, $myrow['PACKAGE']);
        foreach ($row as $key=>$myrow_duration) {
            $myrow["DURATION"] = $myrow_duration["DURATION"];
            $rights[] = $myrow_duration["RIGHTS"];
        }
        if (count($rights) > 0) {
            $rights_str = implode(",", $rights);
        }
        $myrow['RIGHTS'] = $rights_str;
    } 
    elseif ($myrow["PACKAGE"] == "N") {
        $billCompObj = new billing_COMPONENTS();
        $myrow_duration = $billCompObj->getDurationRightsForServiceDetails($serviceid, $myrow['PACKAGE']);
        $myrow["DURATION"] = $myrow_duration["DURATION"];
        $myrow["RIGHTS"] = $myrow_duration["RIGHTS"];
    }
    
    return $myrow;
}

function getTotalPriceAll($serviceid, $curtype, $device = 'desktop') {
    if ($curtype == "DOL") $price_string = $device."_DOL";
    else $price_string = $device."_RS";
    $serviceid = @explode(",", $serviceid);
    $billServObj = new billing_SERVICES();
    if($curtype == "DOL"){
    	$row = $billServObj->fetchServiceDetailForDollarTrxn($serviceid, $device);
    } else {
    	$row = $billServObj->fetchServiceDetailForRupeesTrxn($serviceid, $device);
    }
    foreach ($row as $key=>$val) {
        $price+= $val["PRICE"];
    }
    return $price;
}

/***********************************************************************
 *    DESCRIPTION   :	Generate new OrderID and inserting new record for each payment try on site for both online as well as cheque
 *    RETURNS       :    Returns true if record successfully entered
 ***********************************************************************/

function newOrder($profileid, $paymode, $curtype, $amount, $service_str, $service_main, $discount, $setactivate, $gateway = '', $discount_type = '', $device = 'desktop', $couponCodeVal = '',$memUpgrade="NA") {
    
    if(!$memUpgrade || $memUpgrade == ""){
        $memUpgrade = "NA";
    }
    //	echo $profileid."-".$paymode."-".$curtype."-".$amount."-".$service_str."-".$service_main."-".$discount."-".$setactivate;
    global $error_msg, $pay_arrayfull, $pay_arrayfull, $announce_to_email, $ip, $DOL_CONV_RATE, $tax_rate;
    
    $ip = FetchClientIP();
     //Gets ipaddress of user
    if (strstr($ip, ",")) {
        $ip_new = explode(",", $ip);
        $ip = $ip_new[1];
    }
    
    if ($profileid > 0) {
        if (strstr($service_main, 'P')) $ORDERID = sprintf("J%1.1s%09lX", 'F', time(NULL));
        elseif (strstr($service_main, 'D')) $ORDERID = sprintf("J%1.1s%09lX", 'D', time(NULL));
        elseif (strstr($service_main, 'C')) $ORDERID = sprintf("J%1.1s%09lX", 'C', time(NULL));
        else $ORDERID = sprintf("J%1.1s%09lX", 'A', time(NULL));
         //For add-ons
        
        $data = getProfileDetails($profileid);
        $data["AMOUNT"] = $amount;
        
        // convert USD to RS in case of gateway is other than PAYPAL,
        if ($gateway != "PAYPAL" && $gateway != "CCAVENUE" && $gateway != "PAYSEAL" && $gateway != "PAYU" && $gateway != "APPLEPAY" && $gateway != "PAYTM") {
            if ($curtype == "DOL" && $gateway != "PAYSEAL") $data["AMOUNT"] = round(($data["AMOUNT"] * $DOL_CONV_RATE), 2);
             //convert USD value into INR value
        }
        
        $servMain = explode(",", $service_main);
        
        if (strstr($service_main, 'P') || strstr($service_main, 'C') || strstr($service_main, 'D') || strstr($service_main, 'X')) {
            // Check for main membership in billed services
            $service_main = array_shift($servMain);
            $service_str = implode(",", $servMain);
        } else {
            // Only addons case
            $service_str = $service_main;
            $service_main = null;
        }
        
        list($servefor, $addon_serviceid) = serve_for($service_main, $service_str);
        
        $data["SERVICE_MAIN"] = $service_main;
        $data["ADDON_SERVICE"] = $addon_serviceid;
        
        $service_all = $service_main;

        if ($addon_serviceid) {
            $service_all = $service_main . "," . $addon_serviceid;
        }
        
        $price_tot = getTotalPriceAll($service_all, $curtype, $device);
        //confirm the check for upgrade amount too less
        if(!in_array($memUpgrade, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
            if ($amount / $price_tot < 0.20) {
                die("Some error has occured during request generation. Try again");
            }
        }
        else{
            if ($amount / $price_tot < 0.01) {
                die("Some error has occured during request generation. Try again");
            }
        }
        
        if ($setactivate == "Y") {
            $renew_status = getRenewStatus($profileid);
            $activate_on = $renew_status['EXPIRY_DT'];
        } else {
            $activate_on = date('Y-m-d');
        }

        $insert_id = '';
        
        if (136580 == $profileid) {
            $data[AMOUNT] = 1;
        }
        
        $discount = round($discount, 2);
        $service_insert = ltrim(rtrim($service_all, ","),",");
        if(strstr($service_insert,'X')){
            $servefor = $servefor.',J';
        }
        $billingOrderObj = new BILLING_ORDERS();
        $paramsStr = "PROFILEID, USERNAME, ORDERID, PAYMODE, SERVICEMAIN, CURTYPE,SERVEFOR, AMOUNT, ENTRY_DT, EXPIRY_DT, BILL_ADDRESS, PINCODE, BILL_COUNTRY, BILL_PHONE, BILL_EMAIL, IPADD,ADDON_SERVICEID,DISCOUNT,SET_ACTIVATE,GATEWAY, DISCOUNT_TYPE";
        $valuesStr = "'$profileid', '" . addslashes($data[USERNAME]) . "', '$ORDERID', '$paymode', '$service_insert','$curtype','$servefor', '$data[AMOUNT]', NOW(), '', '" . addslashes(stripslashes($data[CONTACT])) . "', '" . addslashes(stripslashes($data[PINCODE])) . "', '" . addslashes(stripslashes($data[COUNTRY])) . "', '$data[PHONE]', '$data[EMAIL]','$ip','$addon_serviceid','$discount','$setactivate','$gateway','$discount_type'";
        $insert_id = $billingOrderObj->genericOrderInsert($paramsStr, $valuesStr);
        
        $data["ORDERID"] = $ORDERID . "-" . $insert_id;
        if ($device == NULL) {
        	$device = 'desktop';
        }
        $ordrDeviceObj = new billing_ORDERS_DEVICE();
        $ordrDeviceObj->insertOrderDetails($insert_id, $ORDERID, $device, $profileid, $couponCodeVal);
        unset($ordrDeviceObj);
        if($memUpgrade == ""){
            $memUpgrade = "NA";
        }
        if ($insert_id) {
            //set upgrade entry record for such user
            if($memUpgrade != "NA" && in_array($memUpgrade, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
              
                //set entry in upgrade_orders for membership upgrade for current user
                $upgradeOrdersObj = new billing_UPGRADE_ORDERS();
                $insertedRowId = $upgradeOrdersObj->addOrderUpgradeEntry(array("PROFILEID"=>$profileid,"ORDERID"=>$data["ORDERID"],"ENTRY_DT"=>date("Y-m-d H:i:s"),"MEMBERSHIP"=>$memUpgrade));
                unset($upgradeOrdersObj);
                //set upgrade case in memcache for 1 hr for this user 
                if($insertedRowId){
                    $memCacheObject = JsMemcache::getInstance();
                    $memCacheObject->set($profileid.'_MEM_UPGRADE_'.$data["ORDERID"],$memUpgrade,10800);
                }
            }
            else{
                $memCacheObject = JsMemcache::getInstance();
                $memCacheObject->set($profileid.'_MEM_UPGRADE_'.$data["ORDERID"],"NA",10800);
            }
        	return $data;
        }
        else {
        	return false;
        }
    } 
    else return false;
}

/***********************************************************************
 *    DESCRIPTION   :	Inserting successful payments into PURCHASES, PAYMENT_DETAIL and SERVICE_STATUS tables and also keeping track of Online renewals. Functions also calls other functions for sending mail to user
 *    RETURNS       :    nothing
 ***********************************************************************/
function start_service($orderid) {
    global $error_msg, $announce_to_email, $DOL_CONV_RATE, $tax_rate, $payment_gateway, $path, $smarty;
    
    list($part1, $part2) = explode('-', $orderid);
    
    $sql = "select * from billing.ORDERS where ID = '$part2'";
    $res = mysql_query_decide($sql) or logError("$error_msg", $sql, "ShowErrTemplate");
    $myrow = mysql_fetch_array($res);
    
    $payment_gateway = $myrow['GATEWAY'];
    $serviceid = $myrow["SERVICEMAIN"];
    $amount = $myrow["AMOUNT"];
    if ($myrow["CURTYPE"] == 'DOL') {
        if ($payment_gateway != "PAYPAL" && $payment_gateway != "PAYSEAL") {
            $type1 = "DOL";
             //this is allowed value in payment_details table
            $amount = $amount / $DOL_CONV_RATE;
        } 
        else {
            $type1 = "DOL";
            $type = "DOL ";
        }
        $dol_conv_rate = $DOL_CONV_RATE;
    } 
    elseif ($myrow["CURTYPE"] == 'RS') {
        $type1 = "RS";
        $type = "RS ";
         //adding space
        
    } 
    else {
        mail($announce_to_email, "$type not found", "OrderID : $orderid\nType : $type");
        $type1 = "";
    }
    
    $subscription = $myrow["SERVEFOR"];
    if (strstr($subscription, 'D') || strstr($subscription, 'H') || strstr($subscription, 'K')) {
        $sqlcon = "SELECT SCREENING FROM newjs.JPROFILE WHERE PROFILEID='$myrow[PROFILEID]'";
        $rescon = mysql_query_decide($sqlcon) or logError_sums($sqlcon, 1);
        $rowcon = mysql_fetch_assoc($rescon);
        
        //If contact details invalid, send confirm contact details mailer, hence marking verify_service as N
        if (strstr($subscription, 'D')) {
            if (!isFlagSet("PHONERES", $rowcon["SCREENING"]) || !isFlagSet("PHONEMOB", $rowcon["SCREENING"]) || !isFlagSet("CONTACT", $rowcon["SCREENING"]) || !isFlagSet("MESSENGER", $rowcon["SCREENING"]) || !isFlagSet("EMAIL", $rowcon["SCREENING"]) || !isFlagSet("PARENTS_CONTACT", $rowcon["SCREENING"])) {
                $verify_service = "N";
                $subscription.= ",S";
            } 
            else evalue_privacy($myrow["PROFILEID"]);
        } 
        else {
            if (!isFlagSet("CITYBIRTH", $rowcon["SCREENING"]) || !isFlagSet("NAKSHATRA", $rowcon["SCREENING"])) $verify_service = "N";
        }
    }
    list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
    $entry_dt = my_format_date($day, $month, $year);

    $discount_type = $myrow['DISCOUNT_TYPE'];
    $cur_date = date('Y-m-d');
    
    $ip = FetchClientIP();
    if (strstr($ip, ",")) {
        $ip_new = explode(",", $ip);
        $ip = $ip_new[1];
    }
    
    $sql = "INSERT into billing.PURCHASES (SERVICEID, PROFILEID, USERNAME, NAME, ADDRESS, GENDER, CITY, PIN, EMAIL, RPHONE, OPHONE, MPHONE, COMMENT, OVERSEAS, DISCOUNT, DISCOUNT_TYPE, WALKIN, CENTER, ENTRYBY, DUEAMOUNT, DUEDATE, ENTRY_DT, STATUS,SERVEFOR,ADDON_SERVICEID,TAX_RATE,VERIFY_SERVICE,ORDERID,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD,CUR_TYPE) values ('$serviceid','$myrow[PROFILEID]','" . addslashes($myrow['USERNAME']) . "','$myrow[DLVR_CUST_NAME]','" . addslashes($myrow[BILL_ADDRESS]) . "','','','','$myrow[BILL_EMAIL]','$myrow[BILL_PHONE]','','','$myrow[DLVR_NOTES]','','$myrow[DISCOUNT]','$discount_type','ONLINE','HO','ONLINE','','',now(),'DONE','$myrow[SERVEFOR]','$myrow[ADDON_SERVICEID]','$tax_rate','$verify_service','$part2','$cur_date','HO','$ip','$type1')";
    mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
    $billid = mysql_insert_id_js();
    
    $sql = "INSERT into billing.PAYMENT_DETAIL (PROFILEID, BILLID, MODE, TYPE, AMOUNT, STATUS, ENTRY_DT, ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD,SOURCE,DOL_CONV_RATE) values ('$myrow[PROFILEID]','$billid','ONLINE','$type1','$amount','DONE',now(),'ONLINE','$cur_date','HO','$ip','ONLINE','$dol_conv_rate')";
    mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
    
    $receiptid = mysql_insert_id_js();
    
    $service_main_values = getServiceDetails($serviceid);
    $service_main_duration = $service_main_values['DURATION'];
    $service_main_components = getServiceComponents($serviceid);
    if ($myrow['SET_ACTIVATE'] != 'Y') $insert_query[] = "('$billid','$myrow[PROFILEID]','$serviceid','$service_main_components','Y',now(),'','ONLINE',ADDDATE(now(), INTERVAL $service_main_duration MONTH))";
    else {
        $renew_status = getRenewStatus($myrow['PROFILEID']);
        $activate_on = $renew_status['EXPIRY_DT'];
        $insert_query[] = "('$billid','$myrow[PROFILEID]','$serviceid','$service_main_components','N','','$activate_on','ONLINE',ADDDATE('$activate_on', INTERVAL $service_main_duration MONTH))";
    }
    
    $sql = "INSERT into billing.SERVICE_STATUS (BILLID, PROFILEID, SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATE_ON, ACTIVATED_BY, EXPIRY_DT) values " . implode(",", $insert_query);
    mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
   
    if ($myrow['SET_ACTIVATE'] != 'Y') {

	$jprofileObj    =JProfileUpdateLib::getInstance();
	$paramArr =array("SUBSCRIPTION"=>$subscription,"SUBSCRIPTION_EXPIRY_DT"=>$myrow['EXPIRY_DT']);
	$jprofileObj->editJPROFILE($paramArr,$myrow['PROFILEID'],'PROFILEID');
        
        if (isset($_COOKIE['JSLOGIN'])) {
            $checksum = $_COOKIE['JSLOGIN'];
            list($val, $id) = explode("i", $checksum);
            $sql = "UPDATE newjs.CONNECT SET SUBSCRIPTION='$subscription' WHERE ID='$id'";
            mysql_query_decide($sql);
        }
    }
    
    /************************Voucher Code Discount*************************/
    $sql_vc = "SELECT CODE FROM newjs.DISCOUNT_CODE_USED WHERE PROFILEID = '$myrow[PROFILEID]' AND PAYMENT_SUCCESSFUL = 'N' ORDER BY ID DESC LIMIT 1";
    $res_vc = mysql_query_decide($sql_vc) or logError($error_msg, $sql_vc, "ShowErrTemplate", $announce_to_email);
    if ($row_vc = mysql_fetch_array($res_vc)) $v_code = $row_vc['CODE'];
    else {
        $sql_vc = "SELECT CODE FROM newjs.DISCOUNT_CODE WHERE USED_BY='$myrow[PROFILEID]' AND PAYMENT_SUCCESSFUL = 'N' ORDER BY ID DESC LIMIT 1";
        $res_vc = mysql_query_decide($sql_vc) or logError($error_msg, $sql_vc, "ShowErrTemplate", $announce_to_email);
        if ($row_vc = mysql_fetch_array($res_vc)) $v_code = $row_vc['CODE'];
    }
    if ($v_code) mark_voucher_code($myrow['PROFILEID'], $row_vc['CODE'], "", "SUCCESSFUL", $billid);
    
    /************************End of - Voucher Code Discount*************************/
    
    user_start_paying($myrow['PROFILEID']);
    
    // added by shiv on 15th feb for voucher optin
    //voucher_optin($myrow['PROFILEID']);
    
    //added by sadaf on 2 aug for voucher delivery revamp
    $smarty->assign("USERNAME", $myrow["USERNAME"]);
    
    //Added by lavesh
    //promo_mailer($myrow['PROFILEID']);
    
    //Added By lavesh to check suspected ip address.
    //include_once("../suspected_ip.php");
    include_once ($path . "/profile/suspected_ip.php");
    $suspected_check = doubtfull_ip("$ip");
    if ($suspected_check) send_email('vikas@jeevansathi.com', $myrow['PROFILEID'], "Payment Profileid of suspected email-id", "payment@jeevansathi.com");
    
    $subject = "Bill for your online subscription";
    
    if ($myrow['SET_ACTIVATE'] != 'Y') {
        $msg = order_mail_content($myrow['USERNAME'], $type, $amount, $entry_dt, $myrow['ORDERID'], $myrow['ID']);
    } 
    else {
        $msg = order_mail_content_renew($myrow['USERNAME'], $type, $amount, $entry_dt, $myrow['ORDERID'], $myrow['ID'], $myrow_comp['DURATION'], $myrow['EXPIRY_DT'], $activate_on);
    }
    $bill = printbill($receiptid, $billid);
    
    //send_email($myrow["BILL_EMAIL"],$msg,$subject,'','','vibhor.garg@jeevansathi.com,vikas.jayna@jeevansathi.com,alok@jeevansathi.com',$bill,$myrow_comp['DURATION']);
    //send_email($myrow["BILL_EMAIL"],$msg,$subject,'','','payments@jeevansathi.com',$bill,$myrow_comp['DURATION']);
    
    //different mail function called to send html mail along with rtf attachment.
    send_rtf_email($myrow["BILL_EMAIL"], $msg, $subject, 'membership@jeevansathi.com', '', 'payments@jeevansathi.com', $bill, "Jeevansathi Membership");
    
    if (strstr($myrow["SERVEFOR"], "H") || strstr($myrow["SERVEFOR"], "K")) {
        astro_mail($myrow['USERNAME'], $myrow["SERVEFOR"], $myrow["BILL_EMAIL"]);
    }
    
    /***********************************************code added for matri profile questionnaire********************************/
    if (strstr($myrow["SERVICEMAIN"], "M") || strstr($myrow["ADDON_SERVICEID"], "M")) {
        $sql_exec = "select ALLOTED_TO from jsadmin.MAIN_ADMIN where PROFILEID=$myrow[PROFILEID] ";
        $res_exec = mysql_query_decide($sql_exec);
        $row_exec = mysql_fetch_array($res_exec);
        if ($row_exec["ALLOTED_TO"] != '') {
            $sql_alloted = "select EMAIL from jsadmin.PSWRDS where USERNAME='$row[ALLOTED_TO]'";
            $res_alloted = mysql_query_decide($sql_alloted);
            $row_alloted = mysql_fetch_array($res_alloted);
            $alloted_to = $row_alloted["EMAIL"];
        }
        $service_name = getServiceName($myrow["SERVICEMAIN"]);
        matri_questionnaire_mail($myrow['USERNAME'], $service_name, $myrow["BILL_EMAIL"], $alloted_to);
    }
    
    /*********************************************code ends here**************************************************************/
    
    //code added for removing entry from newjs.CONTACTS_STATUS for new insertion of EXPIRY_DT
    $sql_exp = "DELETE from newjs.CONTACTS_STATUS where PROFILEID=$myrow[PROFILEID] ";
    mysql_query_decide($sql_exp);
}

/***********************************************************************
 *    DESCRIPTION   :	Generating mail content for online renewals
 *    RETURNS       :    returns mail content
 ***********************************************************************/
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
    
    $msg = "Dear $USERNAME,\n\nWe have received the payment for the renewal of your Jeevansathi Membership. Given below are your subscription registration details:\n\nYour order id 	: $ORDERID-$ID\nDate	: $entry_dt\nFor an amount of 	: $type $amount\n\nYour membership subscription is for the period of $DURATION months and is valid till $EXPIRY_DT. Your subscription will come into effect only on $activate_on_show as your earlier subscription comes to an end on $activate_on.";
    
    if ($payment_gateway == 'CCAVENUE') {
        $msg.= "\n\nPlease Note :- The charge will appear on your credit card as 'ccavenue.com/charge'";
    } 
    elseif ($payment_gateway == 'TRANSECUTE') {
        $msg.= "\n\nPlease Note :- The charge that will be appearing on your Credit Card Statement will display
 pay.transecute.com";
    } 
    elseif ($payment_gateway == 'PAYPAL') {
        $msg.= "\n\nPlease Note :- The credit card transaction will appear on your bill as \"PAYPAL *JEEVANSATHI\".";
    }
    
    return $msg;
}

/***********************************************************************
 *    DESCRIPTION   :	Generating mail content for all the users except renewal case
 *    RETURNS       :    Returns mail content
 ***********************************************************************/
function order_mail_content($USERNAME, $type, $amount, $entry_dt, $ORDERID, $ID) {
    global $payment_gateway;
    
    $msg = "Dear $USERNAME,\n\nThank you for subscribing to Jeevansathi.com.\n\nWe have received your payment of $type $amount on $entry_dt. Please use your ORDER ID. No. - $ORDERID-$ID in all your future communication with us in order for us to serve you better.\n\nCopy of your bill (Bill.rtf) has been attached with this mail. Kindly revert back for any discrepancies in the bill.";
    
    if ($payment_gateway == 'CCAVENUE') {
        $msg.= "\n\nPlease Note :- The charge will appear on your credit card as 'ccavenue.com/charge'";
    } 
    elseif ($payment_gateway == 'TRANSECUTE') {
        $msg.= "\n\nPlease Note :- The charge that will be appearing on your Credit Card Statement will display
 pay.transecute.com";
    } 
    elseif ($payment_gateway == 'PAYPAL') {
        $msg.= "\n\nPlease Note :- The credit card transaction will appear on your bill as \"PAYPAL *JEEVANSATHI\".";
    }
    
    $msg.= "\n\nRegards,\nJeevansathi.com Team";
    
    return $msg;
}

/***********************************************************************
 *    DESCRIPTION   :	Checking Previous subscription status against PROFILEID
 *    RETURNS       :    Returns true if previous subscription exists else false
 ***********************************************************************/
function getSubscriptionStatus($profileid) {
    global $error_msg;
    $sql = "SELECT count(*) as cnt from billing.PURCHASES where PROFILEID='$profileid' and STATUS='DONE'";
    $result = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
    $myrow = mysql_fetch_array($result);
    if ($myrow['cnt'] > 0) return true;
    else {
        $sql = "SELECT count(*) as cnt from billing.SUBSCRIPTION_EXPIRE where PROFILEID='$profileid'";
        $result = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
        $myrow_1 = mysql_fetch_array($result);
        if ($myrow_1['cnt'] > 0) return true;
    }
    return false;
}

/***********************************************************************
 *    DESCRIPTION   :	find latest subscription against PROFILEID, if any
 *    RETURNS       :    Returns associative array with required values
 ***********************************************************************/
function getRenewStatus($profileid) {
    $sql = "SELECT * from billing.SERVICE_STATUS where PROFILEID='$profileid' and ACTIVATED<>'N' order by ID desc";
    $result = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
    if (mysql_num_rows($result) > 0) {
        $myrow = mysql_fetch_array($result);
        return $myrow;
    } 
    else {
        $sql = "SELECT * from billing.SUBSCRIPTION_EXPIRE where PROFILEID='$profileid'";
        $result = mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
        if (mysql_num_rows($result) > 0) {
            $myrow = mysql_fetch_array($result);
            return $myrow;
        }
    }
    return false;
}

/***********************************************************************
*    DESCRIPTION   :	Find the days difference between two dates
*    RETURNS       :    number of days, date2 > date1 ;
			0,if date1= date2
			-1, if date1 > date2  
***********************************************************************/
if (!function_exists('getTimeDiff')) {
    function getTimeDiff($date1, $date2) {
        if ($date2 > $date1) {
            list($yy1, $mm1, $dd1) = explode("-", $date1);
            list($yy2, $mm2, $dd2) = explode("-", $date2);
            $date1_timestamp = mktime(0, 0, 0, $mm1, $dd1, $yy1);
            $date2_timestamp = mktime(0, 0, 0, $mm2, $dd2, $yy2);
            $timestamp_diff = $date2_timestamp - $date1_timestamp;
            $days_diff = $timestamp_diff / (24 * 60 * 60);
            return $days_diff;
        } 
        elseif ($date2 == $date1) return 0;
        else return -1;
    }
}

/***********************************************************************
 *    DESCRIPTION   :    find component of a service
 *    RETURNS       :    Returns component string
 ***********************************************************************/

function getServiceComponents($serviceid) {
    $sql = "SELECT c.COMPID as COMPID, c.DURATION as DURATION from billing.SERVICES a,
billing.PACK_COMPONENTS b, billing.COMPONENTS c  where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$serviceid'";
    $result_pkg = mysql_query_decide($sql) or die("$sql<br>" . mysql_error_js());
    while ($myrow_pkg = mysql_fetch_array($result_pkg)) {
        $comp_ar[] = $myrow_pkg['COMPID'];
    }
    if (is_array($comp_ar)) $components = implode(",", $comp_ar);
    else $components = $comp_ar;
    return $components;
}

/***********************************************************************
 *    DESCRIPTION   :    find names of services
 *    RETURNS       :    Returns names string
 ***********************************************************************/
function getServiceName($service) {
    $service = str_replace(",", "','", $service);
    
    $sql = "SELECT PACKAGE,NAME FROM billing.SERVICES WHERE SERVICEID IN ('$service') ORDER BY PACKAGE DESC";
    $res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
     //logError();
    while ($row = mysql_fetch_array($res)) {
        $data.= $row['NAME'] . ", ";
    }
    
    $data = substr($data, 0, strlen($data) - 2);
    
    return $data;
}

/*function added by Puneet Makkar on 23 Dec 2005 to understand the critical mass (at which the user starts paying) we need to  start capturing the status - at the time of payment
*/
function user_start_paying($pid) {
    $ts = time();
    $ts-= 30 * 24 * 60 * 60;
    $date = date("Y-m-d", $ts);
    $sql_pid = "SELECT RELATION,HAVEPHOTO,AGE,ENTRY_DT,MSTATUS,GENDER FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
    $res = mysql_query_decide($sql_pid) or logError($error_msg, $sql_pid, "ShowErrTemplate", $announce_to_email);
    $row = mysql_fetch_array($res);
    $PHOTO = $row['HAVEPHOTO'];
    $REG_DATE = $row['ENTRY_DT'];
    $GENDER = $row['GENDER'];
    $RELATION = $row['RELATION'];
    $MSTATUS = $row['MSTATUS'];
    $AGE = $row['AGE'];
    
    $mysql = new Mysql;
    $myDbName = getProfileDatabaseConnectionName($pid, '', $mysql);
    $myDb = $mysql->connect("$myDbName");
    $sql1 = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$date'";
    $res1 = $mysql->executeQuery($sql1, $myDb) or logError($error_msg, $sql1, "ShowErrTemplate", $announce_to_email);
    $row1 = $mysql->fetchArray($res1);
    $LOGIN_CNT = $row1['CNT'];
    
    $sql1_1 = "SELECT TOTAL_COUNT FROM newjs.LOGIN_HISTORY_COUNT WHERE PROFILEID='$pid'";
    $res1_1 = $mysql->executeQuery($sql1_1, $myDb) or logError($error_msg, $sql1_1, "ShowErrTemplate", $announce_to_email);
    if ($row1_1 = $mysql->fetchArray($res1_1)) $LOGIN_CNT = $LOGIN_CNT + $row1_1['TOTAL_COUNT'];
    
    //Restablish the connection
    connect_db();
    
    //Sharding of CONTACTS done by Sadaf
    $sendersIn = $pid;
    $contactResult = getResultSet("COUNT(*) AS CNT2", $sendersIn);
    $INITIATE_CNT = $contactResult[0]["CNT2"];
    unset($contactResult);
    
    $receiversIn = $pid;
    $contactResult = getResultSet("COUNT(*) AS CNT3", '', '', $receiversIn);
    $RECEIVE_CNT = $contactResult[0]["CNT3"];
    unset($contactResult);
    
    $receiversIn = $pid;
    $typeIn = "'A'";
    $contactResult = getResultSet("COUNT(*) AS CNT", '', '', $receiversIn, '', $typeIn);
    $recd_acc = $contactResult[0]["CNT"];
    unset($contactResult);
    
    $sendersIn = $pid;
    $typeIn = "'A'";
    $contactResult = getResultSet("COUNT(*) AS CNT", $sendersIn, '', '', '', $typeIn);
    $made_acc = $contactResult[0]["CNT"];
    unset($contactResult);
    
    $ACCEPTANCE_CNT = $recd_acc + $made_acc;
    
    $receiversIn = $pid;
    $typeIn = "'I'";
    $contactResult = getResultSet("SENDER", '', '', $receiversIn, '', $typeIn);
    if (is_array($contactResult)) {
        unset($pid_str);
        foreach ($contactResult as $key => $value) {
            $pid_str.= $contactResult[$key]["SENDER"] . ",";
        }
        $pid_str = substr($pid_str, 0, strlen($pid_str) - 1);
        $sql6 = "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE PROFILEID IN($pid_str) AND SUBSCRIPTION <> ''";
        $res6 = mysql_query_decide($sql6) or logError($error_msg, $sql6, "ShowErrTemplate", $announce_to_email);
        $row6 = mysql_fetch_array($res6);
        $PAID_INITIATE_CNT = $row6['CNT'];
    }
    unset($contactResult);
    
    $receiversIn = $pid;
    $typeIn = "'A'";
    $contactResult = getResultSet("SENDER", '', '', $receiversIn, '', $typeIn);
    if (is_array($contactResult)) {
        unset($pid_str);
        foreach ($contactResult as $key => $value) {
            $pid_str.= $contactResult[$key]["SENDER"] . ",";
        }
        $pid_str = substr($pid_str, 0, strlen($pid_str) - 1);
        $sql7 = "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE PROFILEID IN($pid_str) AND SUBSCRIPTION <> ''";
        $res7 = mysql_query_decide($sql7) or logError($error_msg, $sql7, "ShowErrTemplate", $announce_to_email);
        $row7 = mysql_fetch_array($res7);
        $PAID_RECEIVED_ACCPT_CNT = $row7["CNT"];
    }
    unset($contactResult);
    
    $sendersIn = $pid;
    $typeIn = "'A'";
    $contactResult = getResultSet("RECEIVER", $sendersIn, '', '', '', $typeIn);
    if (is_array($contactResult)) {
        unset($pid_str);
        foreach ($contactResult as $key => $value) {
            $pid_str.= $contactResult[$key]["RECEIVER"] . ",";
        }
        $pid_str = substr($pid_str, 0, strlen($pid_str) - 1);
        $sql8 = "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE PROFILEID IN ($pid_str) AND SUBSCRIPTION <> ''";
        $res8 = mysql_query_decide($sql8) or logError($error_msg, $sql8, "ShowErrTemplate", $announce_to_email);
        $row8 = mysql_fetch_array($res8);
        $PAID_SENT_ACCPT_CNT = $row8["CNT"];
    }
    unset($contactResult);
    
    $sql10 = "select TOTAL_COUNT from newjs.PAGE_VIEWS where PROFILEID='$pid'";
    $res10 = mysql_query_decide($sql10) or logError($error_msg, $sql6, "ShowErrTemplate", $announce_to_email);
    if ($row10 = mysql_fetch_row($res10)) $PAGE_VIEWS = $row10[0];
    else $PAGE_VIEWS = 0;
    
    $sql = "INSERT IGNORE into newjs.USER_STARTS_PAYING (PROFILEID,GENDER,RELATION,AGE,MSTATUS,INITIATED,RECEIVED,ACCEPTANCE,REG_DATE,PAY_DATE,PHOTO,LOGIN_CNT,PAID_INITIATED,PAID_RECEIVED_ACCEPTANCE,PAID_SENT_ACCEPTANCE,SEARCHES,PAGE_VIEWS) values ('$pid','$GENDER','$RELATION','$AGE','$MSTATUS','$INITIATE_CNT','$RECEIVE_CNT','$ACCEPTANCE_CNT','$REG_DATE',now(),'$PHOTO','$LOGIN_CNT','$PAID_INITITATE_CNT','$PAID_RECEIVED_ACCPT_CNT','$PAID_SENT_ACCPT_CNT','$SEARCHES','$PAGE_VIEWS')";
    
    mysql_query_decide($sql) or logError($error_msg, $sql, "ShowErrTemplate", $announce_to_email);
}

function payment_thanks_things_to_do($profileid, $set_activate) {
    global $smarty;
    $sql_jp = "SELECT HAVEPHOTO, SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
    $res_jp = mysql_query_decide($sql_jp) or logError($error_msg, $sql_jp, "ShowErrTemplate", $announce_to_email);
    $row_jp = mysql_fetch_array($res_jp);
    
    if ($row_jp['HAVEPHOTO'] == 'Y') $smarty->assign("HAVEPHOTO", "1");
    
    if (strstr($row_jp['SUBSCRIPTION'], "F")) {
        $smarty->assign("CHAT", "1");
        $smarty->assign("SENDCUSTOMIZED", "1");
    }
    
    //Sharding Concept added by Vibhor Garg on table JPARTNER
    
    $mysqlObj = new Mysql;
    $jpartnerObj = new Jpartner;
    $myDbName = getProfileDatabaseConnectionName($profileid, '', $mysqlObj);
    $myDb = $mysqlObj->connect("$myDbName");
    if ($jpartnerObj->isPartnerProfileExist($myDb, $mysqlObj, $profileid)) $smarty->assign("HAVEPARTNER", "1");

    $contactResult_A = getResultSet("COUNT(*) AS CNT", "", "", "$profileid", "", "'A'");
    if ($contactResult_A[0]['CNT'] > 0) $smarty->assign("RECEIVED_A", $contactResult_A[0]['CNT']);
    
    $sql_sst = "SELECT";
    if ($set_activate != 'Y') $sql_sst.= " ACTIVATED_ON as ST_DATE";
    else $sql_sst.= " ACTIVATE_ON as ST_DATE";
    
    $sql_sst.= " FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileid' ORDER BY BILLID DESC LIMIT 1";
    $res_sst = mysql_query_decide($sql_sst) or logError($error_msg, $sql_sst, "ShowErrTemplate", $announce_to_email);
    $row_sst = mysql_fetch_array($res_sst);
    list($y, $m, $d) = explode("-", $row_sst['ST_DATE']);
    $st_date = my_format_date($d, $m, $y);
    $smarty->assign("ST_DATE", $st_date);
}

function voucher_optin($profileid) {
    $sql = "INSERT INTO billing.VOUCHER_VIEWED(ID,PROFILEID,VIEWED,ENTRY_DATE) VALUES ('','$profileid','',CURDATE())";
    mysql_query_decide($sql) or logError("", $sql, "ShowErrTemplate", $announce_to_email);
}

/* Added By sriram for Easy Bill.*/
function populate_locality_city() {
    global $smarty;
    
    /*Code to populate City and locality using javascript*/
    $sql = "SELECT DISTINCT CITY_LABEL FROM billing.EASY_BILL_LOCATIONS WHERE ACTIVE='Y' ORDER BY SORTBY";
    $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    while ($row = mysql_fetch_array($res)) {
        $city_label[] = $row['CITY_LABEL'];
        
        $sql_loc = "SELECT DISTINCT LOCALITY FROM billing.EASY_BILL_LOCATIONS WHERE CITY_LABEL='$row[CITY_LABEL]' AND ACTIVE='Y' ORDER BY LOCALITY";
        $res_loc = mysql_query_decide($sql_loc) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_loc, "ShowErrTemplate");
        while ($row_loc = mysql_fetch_array($res_loc)) {
            $loc.= $row_loc['LOCALITY'] . "|#|";
        }
        $locality_arr[] = $row['CITY_LABEL'] . "|X|" . rtrim($loc, "|#|");
        unset($loc);
    }
    $smarty->assign("city_label", $city_label);
    $smarty->assign("locality_arr", $locality_arr);
    
    /*End of - Code to populate City and locality using javascript*/
}

/*Function to generate 5 digit reference id for Easy Bill*/
function generate_ref_id($id) {
    $min = 10000;
    $max = 99999;
    $prefix = "0004";
    if ($id < $max) {
        return $prefix . $id;
    } 
    else {
        
        //$new_ref_id = ($id - $max) + $min;
        $new_ref_id = $id - 89999;
        if ($new_ref_id > $max) return generate_ref_id($new_ref_id);
        else return $prefix . $new_ref_id;
    }
}

//Created by lavesh on 20 april 2007 to send promotional voucher mail to user when they take subscription when they take subscription..
function promo_mailer($pid) {
    global $smarty;
    
    $sql1 = "SELECT CLIENTID,SLABS,GENDER,AVAILABLE_IN FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND CLIENTID!='VLCC01'";
    $res1 = mysql_query_decide($sql1) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes", $sql1, "ShowErrTemplate");
    $i = mysql_num_rows($res1);
    $j = $i;
    if ($j % 3 != 0) $j = $j - ($i % 3) + 3;
    while ($j > 0) {
        $row1 = mysql_fetch_assoc($res1);
        if ($row1["GENDER"] || $row1["SLABS"] || $row1["AVAILABLE_IN"]) $client1 = array("clientid" => $row1["CLIENTID"], "conditions" => 1);
        else $client1 = array("clientid" => $row1["CLIENTID"], "conditions" => 0);
        $row1 = mysql_fetch_assoc($res1);
        if ($row1["GENDER"] || $row1["AVAILABLE_IN"] || $row1["SLABS"]) $client2 = array("clientid" => $row1["CLIENTID"], "conditions" => 1);
        else $client2 = array("clientid" => $row1["CLIENTID"], "conditions" => 0);
        $row1 = mysql_fetch_assoc($res1);
        if ($row1["GENDER"] || $row1["SLABS"] || $row1["AVAILABLE_IN"]) $client3 = array("clientid" => $row1["CLIENTID"], "conditions" => 1);
        else $client3 = array("clientid" => $row1["CLIENTID"], "conditions" => 0);
        $client[] = array($client1, $client2, $client3);
        $j-= 3;
    }
    $smarty->assign("client", $client);
    $sql1 = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
    $res1 = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql1, "ShowErrTemplate");
    $row1 = mysql_fetch_array($res1);
    $msg = $smarty->fetch("../jeevansathi/promotion_mailer.htm");
    $subject = "Your free gifts from Jeevansathi.com";
    $email = $row1["EMAIL"];
    send_email($email, $msg, $subject, "promotions@jeevansathi.com");
    
    //Priority of invalid email / Promo option is set.
    $sql1 = "UPDATE newjs.INTERMEDIATE_PAGE SET MYOPTION='2',COUNT='1' WHERE PROFILEID='$pid'";
    mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql1, "ShowErrTemplate");
}

function log_payment_status($orderid, $status, $gateway, $msg) {
    $msg = addslashes(stripslashes($msg));
    $sql = " INSERT into billing.PAYMENT_STATUS_LOG(ORDERID,STATUS,GATEWAY,MSG,ENTRY_DT) values('$orderid','$status','$gateway','$msg',now()) ";
    mysql_query_decide($sql);
}

function set_subscription_cookie($profileid) {
    global $protect_obj, $data;
    $sql_jp = "SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
    $res_jp = mysql_query_decide($sql_jp) or logError($error_msg, $sql_jp, "ShowErrTemplate", $announce_to_email);
    $row_jp = mysql_fetch_array($res_jp);
    $data["SUBSCRIPTION"] = $row_jp['SUBSCRIPTION'];
    $protect_obj->setcookies($data);
}
function savehits_payment($profileid, $pg_no) {
    $sql_hit = "INSERT into billing.PAYMENT_HITS(PROFILEID,PAGE,ENTRY_DT) values('$profileid','$pg_no',now())";
    mysql_query_decide($sql_hit);
}
function sourcetracking_payment($profileid, $pg_no, $from_source) {
    $sql_hit = "INSERT into billing.PAYMENT_SOURCE_TRACKING(PROFILEID,PAGE,ENTRY_DT,SOURCE) values('$profileid','$pg_no',now(),'$from_source')";
    mysql_query_decide($sql_hit);
}
function get_score($pid) {
    $sqls = "SELECT ATTRIBUTE_SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$pid'";
    $ress = mysql_query_decide($sqls) or die("$sqls" . mysql_error_js());
    if ($rows = mysql_fetch_array($ress)) return $rows["ATTRIBUTE_SCORE"];
    else return 0;
}
function get_no($pid) {
    $sqln = "SELECT COUNT(*) AS CNT FROM billing.PURCHASES as p JOIN billing.PAYMENT_DETAIL as pd ON p.BILLID=pd.BILLID WHERE pd.PROFILEID='$pid' AND pd.STATUS = 'DONE' AND pd.AMOUNT>0";
    $resn = mysql_query_decide($sqln) or die("$sqln" . mysql_error_js());
    if ($rown = mysql_fetch_array($resn)) return $rown["CNT"];
    else return 0;
}
?>
