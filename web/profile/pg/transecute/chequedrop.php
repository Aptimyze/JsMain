<?php
$zipIt = 0;

include_once ("../../connect.inc");
include_once ("../functions.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
include_once (JsConstants::$docRoot . "/commonFiles/comfunc.inc");
require ("functions_transecute.php");
connect_db();
if (isset($payment_source)) {
    $smarty->assign("payment_source", $payment_source);
  
}
$smarty->assign("con_chk", '4');
$serObj = new Services;
$service_main = $serObj->getTrueService($service_main);
$services = $service_main;
$smarty->assign("data", $data["PROFILEID"]);
$smarty->assign("bms_topright", 18);
$smarty->assign("bms_right", 28);
$smarty->assign("bms_bottom", 19);
$smarty->assign("bms_left", 24);
$smarty->assign("bms_new_win", 32);
$smarty->assign("bms_membership", 1);
$ip = FetchClientIP();
if (strstr($ip, ",")) {
    $ip_new = explode(",", $ip);
    $ip = $ip_new[1];
}

$smarty->assign("head_tab", "memberships");
if (!$checksum) {
    die('<font color="red"><b>Please login again and fill the details.</b><font>');
}
$smarty->assign("ICICI", 'N');
$smarty->assign('DISCOUNT_TYPE', $DISCOUNT_TYPE);
if ($avail_discount == 'Y') {
    $smarty->assign('voucher_code', $voucher_code);
}

if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
    $memObj = new Membership;
    $memObj->setProfileid($profileid);
    $payment = $memObj->forOnline($service_main, $type, $service, $discSel, $device, $couponCodeVal);
    $PRICE = $payment['total'];
    if ($submitType == "Submit Cheque") {
        $sql1 = "insert into incentive.PAYMENT_COLLECT (PROFILEID, USERNAME, SERVICE, BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,STATUS,COURIER_TYPE,DISPLAY,ADDON_SERVICEID,DISCOUNT,AMOUNT,CUR_TYPE,PHONE_RES,EMAIL,ADDRESS,REQ_DT) values ('$profileid', '" . addslashes($data[USERNAME]) . "','$service_main','Y','N','',NOW(),'','','','$addon_serviceid','$discount','$PRICE','$type','$data[PHONE]','$data[EMAIL]','" . addslashes(stripslashes($data[CONTACT])) . "',NOW())";
        
        $res1 = mysql_query_decide($sql1) or die($sql . mysql_error_js());
        
        $insert_id = mysql_insert_id_js();
        $data["REQUESTID"] = $insert_id;
        $REQUESTID = $data["REQUESTID"];
        $cd_dt = $cd_year . "-" . $cd_month . "-" . $cd_day;
        if ($OBANK && ($Bank == '' || $Bank == 'Other' || $Bank == '-1')) {
            $Bank = $OBANK;
            $OBANK = 'Y';
        } 
        else $OBANK = 'N';
        $sql_order = "select AMOUNT,CUR_TYPE from incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
        $result = mysql_query_decide($sql_order) or logError_sums($sql_order, 1);
        $row = mysql_fetch_array($result);
        $amount = $row[AMOUNT];
        $curtype = $row[CUR_TYPE];
        if ($depositType == "transfer") {
            $mode = "FUND_TRANSFER";
            $pickupType = "FUND_TRANSFER";
        } 
        else {
            $mode = "CHEQUE";
            $pickupType = "ICICI_CHEQUE";
        }
        
        $sql = "INSERT INTO billing.CHEQUE_REQ_DETAILS (PROFILEID,REQUEST_ID,MODE,TYPE,AMOUNT,CD_DT,CD_NUM,CD_CITY,BANK,OBANK,STATUS,ENTRY_DT,ENTRYBY,IPADD) VALUES('$profileid','$REQUESTID','$mode','$curtype','$amount','$cd_dt','$cdnum','$cd_city','$Bank','$OBANK','PENDING',NOW(),'USER','$ip')";
        mysql_query_decide($sql) or die(mysql_error_js());
         //logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
        
        $sql1 = "UPDATE incentive.PAYMENT_COLLECT SET PICKUP_TYPE='$pickupType',COMMENTS='" . addslashes(stripslashes($COMMENTS)) . "' , PHONE_MOB ='" . addslashes(stripslashes($MOB_NO)) . "' WHERE ID='$REQUESTID'";
        mysql_query_decide($sql1) or die(mysql_error_js());
         //logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
        $msg = "Thank you for showing interest in our paid services.";
        if ($depositType == "transfer") {
            $msg.= "<br>A sales executive will contact you shortly to confirm your fund transfer.";
            $msg.= "<br><br>Details submitted by you are:";
            $msg.= "<br><br><div style='padding-left:20px;'>";
            $msg.= "<div style='padding:0px 0px 4px 0px;'>";
            $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Transaction Number&nbsp;:</div><div class='fl'>$cdnum</div><div style='clear:both'></div>";
        } 
        else if ($depositType == "drop") {
            $msg.= "<br>A sales executive will contact you shortly.";
            $msg.= "<br><br>Details submitted by you are:";
            $msg.= "<br><br><div style='padding-left:20px;'>";
            $msg.= "<div style='padding:0px 0px 4px 0px;'>";
            $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Cheque Number&nbsp;:</div><div class='fl'>$cdnum</div><div style='clear:both'></div>";
        }
        $msg.= "</div>";
        $msg.= "<div style='padding:0px 0px 4px 0px;'>";
        $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Date&nbsp;:</div><div class='fl'>$cd_dt</div><div style='clear:both'></div>";
        $msg.= "</div>";
        $msg.= "<div style='padding:0px 0px 4px 0px;'>";
        $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Bank Name&nbsp;:</div><div class='fl'>$Bank</div><div style='clear:both'></div>";
        $msg.= "</div>";
        $msg.= "<div style='padding:0px 0px 4px 0px;'>";
        if ($depositType == "transfer") {
            $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>City&nbsp;:</div><div class='fl'>$cd_city</div><div style='clear:both'></div>";
        } 
        else {
            $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Cheque City&nbsp;:</div><div class='fl'>$cd_city</div><div style='clear:both'></div>";
        }
        $msg.= "</div>";
        $msg.= "<div style='padding:0px 0px 4px 0px;'>";
        $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Mobile number&nbsp;:</div><div class='fl'>$MOB_NO</div><div style='clear:both'></div>";
        $msg.= "</div>";
        $msg.= "<div style='padding:0px 0px 4px 0px;'>";
        $msg.= "<div style='text-align:right;width:100px;padding-right:5px;' class='fl'>Amount&nbsp;:</div><div class='fl'>$amount</div><div style='clear:both'></div></div>";
        $msg.= "</div>";
        $msg.= "<div style='padding:8px 0px 4px;font-weight:bold'>";
        if ($depositType == "transfer") {
            $msg.= "Paid services will be activated within 48 hours of confirmation of your payment.";
        } 
        else $msg.= "Paid services will be activated within 48 hours of receipt of your payment.";
        $msg.= "</div>";
        
        echo $msg;
        die();
    }
    if ($submitType == "Submit Request") {
        if ($EMAIL == '') {
            $sql = "select PINCODE,EMAIL from newjs.JPROFILE where PROFILEID='$profileid'";
        } 
        else {
            $sql = "select PINCODE from newjs.JPROFILE where PROFILEID='$profileid'";
        }
        $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
        $myrow = mysql_fetch_array($result);
        $pin = $myrow["PINCODE"];
        if ($EMAIL == '') $EMAIL = $myrow["EMAIL"];
        $sql1 = "insert into incentive.PAYMENT_COLLECT (PROFILEID, USERNAME, SERVICE, BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,STATUS,COURIER_TYPE,DISPLAY,ADDON_SERVICEID,DISCOUNT,AMOUNT,CUR_TYPE,PHONE_RES,EMAIL,ADDRESS,REQ_DT) values ('$profileid', '" . addslashes($data[USERNAME]) . "','$service_main','Y','N','',NOW(),'','','','$addon_serviceid','$discount','$PRICE','$type','$data[PHONE]','$data[EMAIL]','" . addslashes(stripslashes($data[CONTACT])) . "',NOW())";
        
        $res1 = mysql_query_decide($sql1) or die($sql . mysql_error_js());
        
        $insert_id = mysql_insert_id_js();
        $data["REQUESTID"] = $insert_id;
        $REQUESTID = $data["REQUESTID"];
        
        $pref_time = $pref_year . "-" . $pref_month . "-" . $pref_day;
        $sql2 = "UPDATE incentive.PAYMENT_COLLECT SET NAME='" . addslashes(stripslashes($NAME1)) . "' , EMAIL='$EMAIL',PHONE_RES='$PHONE_RES',PHONE_MOB = '$PHONE_MOB' , SERVICE ='$SERVICE' , ADDRESS='" . addslashes(stripslashes($ADDRESS)) . "',CITY='$city',PIN='$pin' , BYUSER='Y' , CONFIRM='' , COMMENTS='" . addslashes(stripslashes($COMMENTS)) . "', PREF_TIME ='$pref_time', COURIER_TYPE='GHARPAY' , ADDON_SERVICEID='$addon_services_str' ,PICKUP_TYPE='CHEQ_REQ_USER' WHERE ID='$REQUESTID'";
        
        $result = mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql2, "ShowErrTemplate");
        $msg = "<div style='margin-top:10px'><div style='padding-bottom:4px;'>Thank you for showing interest in our paid services.</div>";
        $msg.= "<div style='padding-bottom:4px;'>A service executive will contact you shortly to get your cheque picked-up for FREE.</div>";
        $msg.= "<div style='padding-bottom:4px;'>Please quote request ID <span style='font-weight:bold'>" . $REQUESTID . "</span> in your future communications.</div>";
        $msg.= "</div>";
        echo $msg;
        die();
    } 
    else if ($submitType == "courier") {
        echo $sql1 = "insert into incentive.PAYMENT_COLLECT (PROFILEID, USERNAME, SERVICE, BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,STATUS,COURIER_TYPE,DISPLAY,ADDON_SERVICEID,DISCOUNT,AMOUNT,CUR_TYPE,PHONE_RES,EMAIL,ADDRESS,REQ_DT) values ('$profileid', '" . addslashes($data[USERNAME]) . "','$service_main','Y','N','',NOW(),'','','','$addon_serviceid','$discount','$PRICE','$type','$data[PHONE]','$data[EMAIL]','" . addslashes(stripslashes($data[CONTACT])) . "',NOW())";
        
        $res1 = mysql_query_decide($sql1) or die($sql . mysql_error_js());
        $insert_id = mysql_insert_id_js();
        $data["REQUESTID"] = $insert_id;
        die();
    } 
    else if ($submit == "change city") {
        
        $sql_address = " SELECT * FROM newjs.CONTACT_US WHERE STATE='$city'";
        $res_address = mysql_query_decide($sql_address) or die("$sql_address" . mysql_error_js());
        $i = 0;
        while ($row_address = mysql_fetch_array($res_address)) {
            $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
            $near_branches[$i]['ADDRESS'] = nl2br($row_address['ADDRESS']);
            $near_branches[$i]['PHONE'] = $row_address['PHONE'];
            $near_branches[$i]['MOBILE'] = $row_address['MOBILE'];
            $near_branches[$i]['NAME'] = $row_address['NAME'];
            $near_branches[$i]['STATE'] = $row_address['STATE'];
            
            $i++;
        }
        $near_branches['i'] = $i;
        die(json_encode($near_branches));
    }
    
    $sql_order = "SELECT COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID = $profileid ";
    $result = mysql_query_decide($sql_order) or logError_sums($sql_order, 1);
    $row = mysql_fetch_assoc($result);
    if ($row[COUNTRY_RES] == '51') {
        $cur_type = 'RS';
        $indian = 'Y';
    } 
    else {
        $cur_type = 'DOL';
        $indian = 'N';
    }
    $smarty->assign('CURRENCY', $cur_type);
    $check = 0;
    global $renew_discount_rate;
    $memObj = new Membership;
    $memObj->setProfileid($profileid);
    
    $smarty->assign('USERNAME', $data[USERNAME]);
    $smarty->assign('DISCOUNT_MSG', $DISCOUNT_MSG);
    $smarty->assign('avail_discount', $avail_discount);
    $smarty->assign('DISCOUNT', $DISCOUNT);
    
    $PRICE = 0;
    $ii = 0;
    $assem = '';
    
    $arr = explode(",", $services);
    for ($i = 0; $i < count($arr); $i++) if ($arr[$i]) {
        $var = $arr[$i];
        if (strstr($var, 'P')) {
            $main_service = $var;
        }
        if (strstr($var, 'C')) {
            $main_service = $var;
        }
        if (strstr($var, 'B')) {
            $bold = $var;
        }
        if (strstr($var, 'T')) {
            $T_arr = $var;
        }
        if (strstr($var, 'A')) {
            $A_arr = $var;
        }
        if (strstr($var, 'M')) {
            $cb5 = $var;
        }
    }
    if ($main_service) {
        $serv_dura = $serObj->getDuration($main_service, 'M');
        $serv_name = $serObj->getServiceName($main_service);
        $serv_name = $serv_name[$main_service][NAME];
        $serv_price = $serObj->getServicesAmount($main_service, $cur_type);
        $serv_price1 = $serv_price[$main_service][PRICE];
        $PRICE+= $serv_price1;
        $disc_str = $serObj->getDiscountStr($main_service);
        if ($disc_str && $main_service != 'PL' && $main_service != 'CL') $off_str = $disc_str;
        if ($off_str) $serv_name = $serv_name . "<br><b class='mar_clr t14'>$off_str</b>";
        $smarty->assign('MNAME', $serv_name);
        $smarty->assign('MPRICE', $serv_price1);
        $check+= 1;
        $ii = 1;
        $assem.= $main_service . ",";
    }
    $check*= 10;
    if ($bold) {
        $serv_name = $serObj->getServiceName($bold);
         //,$bold,$A_arr,$cb5);
        $serv_name = $serv_name[$bold][NAME];
        $serv_price = $serObj->getServicesAmount($bold, $cur_type);
        $serv_price = $serv_price[$bold][PRICE];
        $PRICE = $PRICE + $serv_price;
        $smarty->assign('BNAME', $serv_name);
        $smarty->assign('BPRICE', $serv_price);
        $check+= 1;
        $smarty->assign('FORB', ++$ii);
        $smarty->assign('BOLD', $bold);
        $assem.= $bold . ",";
    }
    $check*= 10;
    if ($T_arr) {
        $serv_name = $serObj->getServiceName($T_arr);
         //,$bold,$T_arr,$cb5);
        $serv_name = $serv_name[$T_arr][NAME];
        $serv_price = $serObj->getServicesAmount($T_arr, $cur_type);
        $serv_price = $serv_price[$T_arr][PRICE];
        $PRICE = $PRICE + $serv_price;
        $smarty->assign('TNAME', $serv_name);
        $smarty->assign('TPRICE', $serv_price);
        $check+= 1;
        $smarty->assign('FORT', ++$ii);
        $smarty->assign('RB', $T_arr);
        $assem.= $T_arr . ",";
    }
    $check*= 10;
    if ($cb5) {
        $serv_name = $serObj->getServiceName($cb5);
         //,$bold,$A_arr,$cb5);
        $serv_name = $serv_name[$cb5][NAME];
        $serv_price = $serObj->getServicesAmount($cb5, $cur_type);
        $serv_price = $serv_price[$cb5][PRICE];
        $PRICE = $PRICE + $serv_price;
        $smarty->assign('MANAME', $serv_name);
        $smarty->assign('MAPRICE', $serv_price);
        $check+= 1;
        $smarty->assign('FORM', ++$ii);
        $smarty->assign('MATRO', $cb5);
        $assem.= $cb5 . ",";
    }
    
    $check*= 10;
    if ($A_arr) {
        $serv_name = $serObj->getServiceName($A_arr);
         //,$bold,$A_arr,$cb5);
        $serv_name = $serv_name[$A_arr][NAME];
        $serv_price = $serObj->getServicesAmount($A_arr, $cur_type);
        $serv_price = $serv_price[$A_arr][PRICE];
        $PRICE = $PRICE + $serv_price;
        $smarty->assign('ANAME', $serv_name);
        $smarty->assign('APRICE', $serv_price);
        $check+= 1;
        $smarty->assign('FORA', ++$ii);
        $smarty->assign('ASTRO', $A_arr);
        $assem.= $A_arr;
    }
    $PRICE = $memObj->forPage3($main_service, $PRICE, $serv_price1);
    $smarty->assign('CHECK', $check);
    $smarty->assign('CHECK1', $check);
    $smarty->assign('IDS', $assem);
    $smarty->assign('PRICE', $PRICE);
    
    $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));
    $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
     //Added for revamp
    $smarty->assign("COURIER", $COURIER);
    $smarty->assign("indian", $indian);
    if ($checkout = 1) {
        $paymode = "cheque";
        if (strstr($paymode, "cheque")) {
            global $error_msg, $pay_arrayfull, $pay_arrayfull, $announce_to_email, $ip, $DOL_CONV_RATE;
            
            unset($insert_id);
            
            $sql1 = "insert into incentive.PAYMENT_COLLECT (PROFILEID, USERNAME, SERVICE, BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,STATUS,COURIER_TYPE,DISPLAY,ADDON_SERVICEID,DISCOUNT,AMOUNT,CUR_TYPE,PHONE_RES,EMAIL,ADDRESS,REQ_DT) values ('$profileid', '" . addslashes($data[USERNAME]) . "','$service_main','Y','N','',NOW(),'','','','$addon_serviceid','$discount','$PRICE','$type','$data[PHONE]','$data[EMAIL]','" . addslashes(stripslashes($data[CONTACT])) . "',NOW())";
            
            $res1 = mysql_query_decide($sql1) or die($sql . mysql_error_js());
            
            $insert_id = mysql_insert_id_js();
            $data["REQUESTID"] = $insert_id;
            
            if (!$insert_id) {
                $smarty->assign("CHECKSUM", $checksum);
                $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
                $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
                $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
                $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
                $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
                $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
                $smarty->display("pg/ordererror.htm");
                die;
            } 
            else $ORDER = $data;
            
            $orderdate = date("Y-m-d", time());
            list($year, $month, $day) = explode("-", $orderdate);
            $orderdate = my_format_date($day, $month, $year);
            
            if ($type == "DOL") {
                $paytype = "US $";
                $smarty->assign("PAYTYPE_WORDS", "Dollar");
                $smarty->assign("AMOUNT", $ORDER["AMOUNT"] / $DOL_CONV_RATE);
            } 
            else {
                $paytype = "Rs. ";
                $smarty->assign("PAYTYPE_WORDS", "Rupees");
                $smarty->assign("AMOUNT", $ORDER["AMOUNT"]);
            }
            
            chequepickup($profileid, $ORDER["REQUESTID"]);
            depositcheque($ORDER["REQUESTID"]);
            get_nearest_branches($profileid);
            savehits_payment($profileid, "3");
            
            //if(isset($payment_source))
            if ($from_source) {
                sourcetracking_payment($profileid, '3', $from_source);
                $smarty->assign('from_source', $from_source);
            }
            
            $smarty->assign("PERIOD", $service_main_details["DURATION"]);
            $smarty->assign("ORDERID", $ORDER["REQUESTID"]);
            $smarty->assign("ORDERDATE", $orderdate);
            $smarty->assign("BILL_NAME", $ORDER["USERNAME"]);
            $smarty->assign("BILL_ADD", $ORDER["CONTACT"]);
            $smarty->assign("BILL_COUNTRY", $ORDER["COUNTRY"]);
            $smarty->assign("BILL_PHONE", $ORDER["PHONE"]);
            $smarty->assign("BILL_EMAIL", $ORDER["EMAIL"]);
            $smarty->assign("PAYTYPE", $paytype);
            $smarty->assign("SER_MAIN", $ser_main);
            
            $smarty->assign("CHECKSUM", $checksum);
            for ($i = 0; $i < 31; $i++) {
                $ddarr[$i] = $i + 1;
            }
            for ($i = 0; $i < 12; $i++) {
                $mmarr[$i] = $i + 1;
            }
            for ($i = 0; $i < 10; $i++) {
                $yyarr[$i] = $i + 2005;
            }
            $smarty->assign("ddarr", $ddarr);
            $smarty->assign("mmarr", $mmarr);
            $smarty->assign("yyarr", $yyarr);
            
            list($cur_year, $cur_month, $cur_day) = explode("-", date('Y-m-d'));
            $smarty->assign("cur_year", $cur_year);
            $smarty->assign("cur_month", ltrim($cur_month, "0"));
            $smarty->assign("cur_day", ltrim($cur_day, "0"));
            
            $after_two_days = mktime(0, 0, 0, date('m'), date('d') + 2, date('Y'));
            list($after2_year, $after2_month, $after2_date) = explode("-", date('Y-m-d', $after_two_days));
            $smarty->assign("after2_date", $after2_date);
            $smarty->assign("after2_month", $after2_month);
            $smarty->assign("after2_year", $after2_year);
            $smarty->display("pg/chequedrop.htm");
        }
    } 
    else {
        $smarty->assign("CHECKSUM", $checksum);
        $smarty->assign("SER_MAIN", $ser_main);
        $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        
        $smarty->display("pg/ordererror.htm");
    }
} 
else {
    die('<font color="red"><b>Please login again and fill the details.</b><font>');
}
function chequepickup($pid, $REQUESTID) {
    global $smarty, $dec_ag;
    $sql = "SELECT SERVICE , ADDON_SERVICEID , AMOUNT , CUR_TYPE FROM incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
    $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    $row = mysql_fetch_array($result);
    $service_main = $row['SERVICE'];
    $addon = $row['ADDON_SERVICEID'];
    
    $username = $data["USERNAME"];
    $main_ser = $service_main;
    $addon_ser = $addon;
    $amt = $row['AMOUNT'];
    $amt_words = convert($amt);
    $amt_words.= " Only";
    $amt_words = str_replace("     ", "    ", $amt_words);
    $amt_words = str_replace("    ", "   ", $amt_words);
    $amt_words = str_replace("   ", "  ", $amt_words);
    $amt_words = str_replace("  ", " ", $amt_words);
    $amt_words;
    $oot = strlen($amt_words);
     //to be tested
    if ($oot > 48) {
        $str = explode(' ', $amt_words);
        $str[count($str) - 3].= "<br>";
        $amt_words = implode(' ', $str);
        $smarty->assign("NOL", '2');
    } 
    else $smarty->assign("NOL", '1');
    $amt_words;
    $smarty->assign("AMOUNT_WORDS", $amt_words);
    
    $sql = "select EMAIL,PHONE_MOB,CITY_RES,PHONE_RES,CONTACT,PINCODE from newjs.JPROFILE where PROFILEID='$pid'";
    $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    $myrow = mysql_fetch_array($result);
    $email = $myrow["EMAIL"];
    $city_res = $myrow["CITY_RES"];
    $PINCODE = $myrow["PINCODE"];
    $main_ser_name = service_name($main_ser);
    
    if ($row["ADDON_SERVICEID"]) {
        $addon_serviceid = $row["ADDON_SERVICEID"];
        $addon_serviceid_ar = explode(",", $addon_serviceid);
        for ($j = 0; $j < count($addon_serviceid_ar); $j++) $addon_serviceid_ar[$j] = "'" . $addon_serviceid_ar[$j] . "'";
        $addon_serviceid_str = implode(",", $addon_serviceid_ar);
        
        $sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
        $result_services = mysql_query_decide($sql) or die(mysql_error_js());
        while ($myrow_result_services = mysql_fetch_array($result_services)) {
            $add_on_services[] = "<br>" . $myrow_result_services["NAME"];
        }
    }
    
    $sql_near = "SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y'";
    $result_near = mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    $i = 0;
    while ($row_near = mysql_fetch_array($result_near)) {
        if ($row_near["VALUE"] != "GU") {
            $near_ar[$i]['LABEL'] = $row_near["LABEL"];
            $near_ar[$i]['VALUE'] = $row_near["VALUE"];
            $i++;
        }
    }
    $smarty->assign("PINCODE", $PINCODE);
    $smarty->assign("STP", $stp);
    $smarty->assign("REQUESTID", $REQUESTID);
    $smarty->assign("CHECKSUM", $data["CHECKSUM"]);
    $smarty->assign("EMAIL", $email);
    $smarty->assign("USERNAME", $username);
    $smarty->assign("PHONE_MOB", $myrow["PHONE_MOB"]);
    $smarty->assign("PHONE_RES", $myrow["PHONE_RES"]);
    $smarty->assign("ADDRESS", $myrow["CONTACT"]);
    $smarty->assign("SERVICE", $main_ser);
    $smarty->assign("ADDON_SER", $addon_ser);
    $smarty->assign("MAIN_SER_NAME", $main_ser_name);
    $smarty->assign("ADDON_ARR", $addon_arr);
    $smarty->assign("ADDON_SERVICES", $addon_service_names);
    $smarty->assign("CUR_TYPE", $row['CUR_TYPE']);
    $smarty->assign("AMOUNT", $amt);
    $smarty->assign("NEAR_ARR", $near_ar);
    $smarty->assign("CITY_RES", $city_res);
    unset($add_on_services);
}
function service_name($id) {
    $sql = "select NAME from billing.SERVICES where SERVICEID='$id'";
    $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    $myrow = mysql_fetch_array($result);
    $name = $myrow["NAME"];
    return $name;
}
function depositcheque($REQUESTID) {
    global $smarty;
    $sql = "SELECT PROFILEID , USERNAME , EMAIL , SERVICE , ADDON_SERVICEID , AMOUNT , CUR_TYPE FROM incentive.PAYMENT_COLLECT WHERE ID='$REQUESTID'";
    $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    $row = mysql_fetch_array($result);
    $main_ser_name = service_name($row['SERVICE']);
    if ($row["ADDON_SERVICEID"]) {
        $addon_serviceid = $row["ADDON_SERVICEID"];
        $addon_serviceid_ar = explode(",", $addon_serviceid);
        for ($j = 0; $j < count($addon_serviceid_ar); $j++) $addon_serviceid_ar[$j] = "'" . $addon_serviceid_ar[$j] . "'";
        $addon_serviceid_str = implode(",", $addon_serviceid_ar);
        
        $sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
        $result_services = mysql_query_decide($sql) or die(mysql_error_js());
        while ($myrow_result_services = mysql_fetch_array($result_services)) {
            $add_on_services[] = "<br>" . $myrow_result_services["NAME"];
        }
        $addon_service_names = implode(",", $add_on_services);
    }
    
    $smarty->assign("EMAIL", $row['EMAIL']);
    $smarty->assign("USERNAME", $row['USERNAME']);
    $smarty->assign("REQUESTID", $REQUESTID);
    $smarty->assign("MAIN_SER_NAME", $main_ser_name);
    $smarty->assign("ADDON_SERVICES", $addon_service_names);
    $smarty->assign("CURTYPE", $row['CUR_TYPE']);
    $smarty->assign("AMOUNT", $row['AMOUNT']);
    $smarty->assign("PROFILEID", $row['PROFILEID']);
    
    $sql = "SELECT NAME FROM billing.BANK";
    $res = mysql_query_decide($sql) or die(mysql_error_js());
    $i = 0;
    while ($row = mysql_fetch_array($res)) {
        $bank[$i] = $row['NAME'];
        $i++;
    }
    $sql = "SELECT NAME FROM incentive.BRANCHES order by NAME";
    $res = mysql_query_decide($sql) or die(mysql_error_js());
    $i = 0;
    while ($row = mysql_fetch_array($res)) {
        $dep_branch_arr[$i] = $row['NAME'];
        $i++;
    }
    $dd_arr = explode("-", Date('Y-m-d'));
    $smarty->assign("DEP_DAY", $dd_arr[2]);
    $smarty->assign("DEP_MONTH", $dd_arr[1]);
    $smarty->assign("DEP_YEAR", $dd_arr[0]);
    $smarty->assign("dep_branch", $center);
    $smarty->assign("dep_branch_arr", $dep_branch_arr);
    
    $smarty->assign("USER", $user);
    $smarty->assign("val", $val);
    $smarty->assign("uname", $uname);
    $smarty->assign("phrase", $phrase);
    $smarty->assign("criteria", $criteria);
    $smarty->assign("billid", $billid);
    $smarty->assign("subs", $subs);
    $smarty->assign("bank", $bank);
    
    $smarty->assign("MODE", "CHEQUE");
    $smarty->assign("CHECKSUM", $data["CHECKSUM"]);
}
function get_nearest_branches($profileid) {
    global $smarty;
    $sql = "SELECT CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
    $res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
    $row = mysql_fetch_array($res);
    
    $sql_address = " SELECT newjs.CONTACT_US.* FROM incentive.BRANCH_CITY, newjs.BRANCHES,newjs.CONTACT_US WHERE incentive.BRANCH_CITY.NEAR_BRANCH=newjs.BRANCHES.VALUE and newjs.BRANCHES.NAME = newjs.CONTACT_US.STATE and incentive.BRANCH_CITY.VALUE='$row[CITY_RES]'";
    $res_address = mysql_query_decide($sql_address) or die("$sql_address" . mysql_error_js());
    $i = 0;
    while ($row_address = mysql_fetch_array($res_address)) {
        $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
        $near_branches[$i]['ADDRESS'] = nl2br($row_address['ADDRESS']);
        $near_branches[$i]['PHONE'] = $row_address['PHONE'];
        $near_branches[$i]['MOBILE'] = $row_address['MOBILE'];
        $near_branches[$i]['NAME'] = $row_address['NAME'];
        $near_branches[$i]['STATE'] = $row_address['STATE'];
        
        $i++;
    }
    $SQL = " SELECT DISTINCT STATE FROM newjs.CONTACT_US ORDER BY STATE";
    $RESULT = mysql_query_decide($SQL) or die("$SQL" . mysql_error_js());
    $i = 0;
    while ($ROW = mysql_fetch_array($RESULT)) {
        $STATES[$i] = $ROW['STATE'];
        $i++;
    }
    $smarty->assign("STATES", $STATES);
    $smarty->assign("near_branches", $near_branches);
}

if ($zipIt && !$dont_zip_now) ob_end_flush();
?>
