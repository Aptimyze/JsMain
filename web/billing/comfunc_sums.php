<?php
include_once JsConstants::$docRoot . "/commonFiles/flag.php";
include_once JsConstants::$docRoot . "/crm/func_sky.php";
include_once JsConstants::$docRoot . "/profile/contacts_functions.php";
include_once JsConstants::$docRoot . "/commonFiles/comfunc.inc";
include_once JsConstants::$docRoot . "/classes/JProfileUpdateLib.php";
include_once JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php";

/**
 * @param $serid
 * @param $addonid
 * @return mixed
 */
function get_services_amount($serid, $addonid)
{
    $sql = "Select SUM(desktop_RS) as PRICE from billing.SERVICES where SERVICEID ='$serid'";

    if ($addonid != "") {
        $sql .= " OR SERVICEID IN (" . stripslashes($addonid) . ")";
    }

    $result = mysql_query_decide($sql) or logError_sums($sql, 0);
    $myrow  = mysql_fetch_array($result);
    $price  = $myrow['PRICE'] * (1 - (billingVariables::TAX_RATE / 100));
    return $price;
}

/**
 * @return mixed
 */
function get_dep_branches()
{
    $sql = "SELECT NAME FROM incentive.BRANCHES order by NAME";
    $res = mysql_query_decide($sql) or logError_sums($sql, 0);
    $i   = 0;

    while ($row = mysql_fetch_array($res)) {
        $dep_branch_arr[$i] = $row['NAME'];
        $i++;
    }

    return $dep_branch_arr;
}

/**
 * @param $profileid
 * @return mixed
 */
function get_subscription($profileid)
{
    $sql  = "SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
    $res  = mysql_query_decide($sql) or logError_sums($sql, 0);
    $row  = mysql_fetch_array($res);
    $subs = $row['SUBSCRIPTION'];
    return $subs;
}

/**
 * @return mixed
 */
function get_banks()
{
    $sql = "SELECT NAME FROM billing.BANK";
    $res = mysql_query_decide($sql) or logError_sums($sql, 0);
    $i   = 0;

    while ($row = mysql_fetch_array($res)) {
        $bank_arr[$i] = $row['NAME'];
        $i++;
    }

    return $bank_arr;
}

/**
 * @param $billid
 * @return mixed
 */
function get_due_amount($billid)
{
    $sql = "SELECT PROFILEID,DUEAMOUNT,CUR_TYPE FROM billing.PURCHASES WHERE BILLID='$billid'";
    $res = mysql_query_decide($sql) or logError_sums($sql, 0);

    if ($row = mysql_fetch_array($res)) {
        $dueamt = $row['DUEAMOUNT'];
    }

    return $dueamt;
}

/**
 * @return mixed
 */
function get_days()
{
    for ($i = 0; $i < 31; $i++) {
        $ddarr[$i] = $i + 1;
    }

    return $ddarr;
}

/**
 * @return mixed
 */
function get_months()
{
    for ($i = 0; $i < 12; $i++) {
        $mmarr[$i] = $i + 1;
    }

    return $mmarr;
}

/**
 * @return mixed
 */
function get_years()
{
    $yy =date("Y");
    $j=0;
    for ($i = 2006; $i <= $yy; $i++) {
        $yyarr[$j] =$i;
        $j++;
    }
    return $yyarr;
}

/**
 * @param $profileid
 * @return mixed
 */
function get_jprofile_details($profileid)
{
    $sql = "SELECT `USERNAME`, `GENDER`, `CONTACT`, `COUNTRY_RES`, `CITY_RES`, `EMAIL`, `MOD_DT`, `PINCODE` ,`INCOMPLETE`, `SUBSCRIPTION`, `ACTIVATED`, `PHONE_RES`, `PHONE_MOB` from newjs.JPROFILE where PROFILEID='$profileid' ";
    $res = mysql_query_decide($sql) or logError_sums($sql, 0);
    $row = mysql_fetch_array($res);
    return $row;
}

/**
 * @return mixed
 */
function populate_search_criteria()
{
    $cri = array(
        array('name' => 'By Username', 'value' => 'uname'),
        array('name' => 'By Email', 'value' => 'email'),
        array('name' => 'By Cheque/DD No.', 'value' => 'cdnum'),
        array('name' => 'By BillID', 'value' => 'billid'),
        array('name' => 'By RequestID', 'value' => 'reqid'),
        array('name' => 'By Phone Number', 'value' => 'phone'),
        array('name' => 'By Mobile Number', 'value' => 'mobile'),
        array('name' => 'By Order-ID', 'value' => 'orderid'),
    );
    return $cri;
}

/**
 * @return mixed
 */
function populate_rev_search_criteria()
{
    $rev_cri = array(
        array('name' => 'By Client-name', 'value' => 'uname'),
        array('name' => 'By Cheque/DD No.', 'value' => 'cdnum'),
        array('name' => 'By BillID', 'value' => 'billid'),
    );
    return $rev_cri;
}

/**
 * @return mixed
 */
function populate_service_type()
{
    global $offline_billing;

    if ($offline_billing) {
        $service_main = array(
            array('name' => 'Offline', 'value' => 'O'),
            array('name' => '101 Membership', 'value' => 'HDO'),
        );
    } else {
        $service_main = array(
            array('name' => 'e-Ristha', 'value' => 'P'),
            array('name' => 'e-Value Pack', 'value' => 'C'),
            array('name' => 'e-Advantage Pack', 'value' => 'NCP'),
            array('name' => 'Matri Profile', 'value' => 'M'),
            array('name' => 'Super Saver with e-Rishta', 'value' => 'SC'),
            array('name' => '101 Membership', 'value' => 'HDO'),
            array('name' => 'e-Sathi Confidential', 'value' => 'ES'),
            array('name' => 'e-Sathi Classified', 'value' => 'ESP'),
            array('name' => 'JS Assisted', 'value' => 'ESJA'),
        );
    }

    return $service_main;
}

/**
 * @return mixed
 */
function populate_service_duration()
{
    global $offline_billing;
    $exclude_arr = array("0");

    $sql_dur = "SELECT NAME,DURATION FROM billing.COMPONENTS";
    $res_dur = mysql_query_decide($sql_dur) or logError_sums($sql_dur, 0);

    while ($row_dur = mysql_fetch_array($res_dur)) {
        if (!@in_array($row_dur['DURATION'], $val_arr)) {
            $dur           = $row_dur['DURATION'];
            $exp           = explode("-", $row_dur['NAME']);
            $val_arr[]     = $dur;
            $dur_arr[$dur] = "For " . trim($exp[1]);
        }
    }

    $fin_arr = array_diff($val_arr, $exclude_arr);
    @sort($fin_arr);

    if ($offline_billing) {
        $service_duration = array(
            array("value" => "1", "duration" => "For One Month"),
            array("value" => "3", "duration" => "For Three Month"),
            array("value" => "6", "duration" => "For Six Month"),
            array("value" => "12", "duration" => "For Twelve Month"),
        );
    } else {
        for ($i = 0; $i < count($fin_arr); $i++) {
            $service_duration[$i]['value']    = $fin_arr[$i];
            $service_duration[$i]['duration'] = $dur_arr[$fin_arr[$i]];
        }
    }

    return $service_duration;
}

/**
 * @return mixed
 */
function populate_discount_type()
{
    $discount_type = array(
        array("value" => "1", "dtype" => "Renewal Discount"),
        array("value" => "2", "dtype" => "General Discount"),
        array("value" => "3", "dtype" => "Complementary Discount"),
        array("value" => "4", "dtype" => "Referral Discount"),
        array("value" => "5", "dtype" => "Variable Discount"),
        array("value" => "6", "dtype" => "Festive Discount"),
        array("value" => "7", "dtype" => "Renewal + Festive Discount"),
        array("value" => "8", "dtype" => "Voucher Code Discount"),
        array("value" => "9", "dtype" => "Variable + Festive Discount"),
    );

    return $discount_type;
}

/**
 * @return mixed
 */
function populate_bounce_reason()
{
    $bounce_reason = array(
        array("value" => "PS", "reason" => "Payment Stopped by User"),
        array("value" => "IF", "reason" => "Insufficient Funds"),
        array("value" => "ENC", "reason" => "Effects not cleared, present again"),
        array("value" => "NAF", "reason" => "Not arrange for"),
        array("value" => "RTD", "reason" => "Refer to Drawer"),
        array("value" => "DSI", "reason" => "Drawers Signature incomplete / differs / required"),
        array("value" => "AFS", "reason" => "Alteration requires full signature"),
        array("value" => "PDC", "reason" => "Post dated cheque"),
        array("value" => "OD", "reason" => "Out of date"),
        array("value" => "AWD", "reason" => "Amount in words and figures differ"),
        array("value" => "EA", "reason" => "Exceeds arrangement"),
        array("value" => "NDU", "reason" => "Not drawn on us"),
        array("value" => "PNR", "reason" => "Payee&quot;s name required / differs / mismatch"),
        array("value" => "AC", "reason" => "Account Closed"),
        array("value" => "", "reason" => "Other"),
    );

    return $bounce_reason;
}

//function for upgradation of Offline Product.
/**
 * @return mixed
 */
function populate_service_count()
{
    $service_count = array(
        array("value" => "10", "count" => "10 Acceptances"),
    );

    return $service_count;
}

/**
 * @param $service_type
 * @return mixed
 */
function get_service_name($service_type)
{
    if ($service_type == "P") {
        $service_type_name = "e-Rishta";
    } elseif ($service_type == "D") {
        $service_type_name = "e-Classifieds";
    } elseif ($service_type == "C") {
        $service_type_name = "e-Value Pack";
    } elseif ($service_type == "NCP") {
        $service_type_name = "e-Advantage Pack";
    } elseif ($service_type == "M") {
        $service_type_name = "Matri Profile";
    } elseif ($service_type == "O") {
        $service_type_name = "Offline Product";
    } elseif ($service_type == "SC") {
        $service_type_name = "Super Saver with e-Rishta";
    } elseif ($service_type == "HDO") {
        $service_type_name = "101 Membership";
    } elseif ($service_type == "ES") {
        $service_type_name = "e-Sathi Confidential";
    } elseif ($service_type == "ESP") {
        $service_type_name = "e-Sathi Classified";
    }

    return $service_type_name;
}

/**
 * @param $addon_services
 * @return mixed
 */
function get_addon_services($addon_services)
{
    $addons = "";

    if (count($addon_services) > 0) {
        $comma = ", ";
    }

    for ($i = 0; $i < count($addon_services); $i++) {
        if ($addon_services[$i] == "B") {
            $addons .= "Profile Highlighting" . $comma;
        }

        if ($addon_services[$i] == "H") {
            $addons .= "Horoscope" . $comma;
        }

        if ($addon_services[$i] == "K") {
            $addons .= "Kundali" . $comma;
        }

        if ($addon_services[$i] == "A") {
            $addons .= "Astro Compatibility" . $comma;
        }

        if ($addon_services[$i] == "M") {
            $addons .= "Matri Profile";
        }
    }

    return $addons;
}

/**
 * @param $discount_type
 * @return mixed
 */
function get_discount_type($discount_type)
{
    if ($discount_type == "1") {
        $disc_type = "Renewal Discount";
    } elseif ($discount_type == "2") {
        $disc_type = "General Discount";
    } elseif ($discount_type == "3") {
        $disc_type = "Complementary Discount";
    } elseif ($discount_type == "4") {
        $disc_type = "Referral Discount";
    } elseif ($discount_type == "5") {
        $disc_type = "Special Discount";
    } elseif ($discount_type == "6") {
        $disc_type = "Festive Discount";
    } elseif ($discount_type == "7") {
        $disc_type = "Renewal and Festive Discount";
    } elseif ($discount_type == "8") {
        $disc_type = "Voucher Code Discount";
    }

    return $disc_type;
}

/**
 * @return mixed
 */
function mode_of_payment()
{
    global $offline_billing;
/*
if($offline_billing)
{
$pay_mode = array(
array('name'=>'Cash','value'=>'CASH'),
array('name'=>'Cheque','value'=>'CHEQUE')
);
}
else
{
 */
    $pay_mode = array(
        array('name' => 'Cash', 'value' => 'CASH'),
        array('name' => 'Cash without hardcopy receipt', 'value' => 'CASH_WITHOUT_RECEIPT'),
        array('name' => 'Cheque', 'value' => 'CHEQUE'),
        array('name' => 'Cheque without hardcopy receipt', 'value' => 'CHEQUE_WITHOUT_RECEIPT'),
        array('name' => 'Demand Draft', 'value' => 'DD'),
        array('name' => 'Online', 'value' => 'ONLINE'),
        array('name' => 'Bank-Transfer-Online', 'value' => 'BANK_TRSFR_ONLINE'),
        array('name' => 'TT', 'value' => 'TT'),
        array('name' => 'CCOFFILINE', 'value' => 'CCOFFLINE'),
        array('name' => 'IVR', 'value' => 'IVR'),
        array('name' => 'Ghar Pay Cheque', 'value' => 'GHAR_PAY_CHEQUE'),
        array('name' => 'Ghar Pay Cash', 'value' => 'GHAR_PAY_CASH'),
        array('name' => 'PayTM on delivery','value'=>'PayTM_ON_DELIVERY')
    );
//    }
    return $pay_mode;
}

/**
 * @return mixed
 */
function get_deposit_branches()
{
    //$sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
    $sql = "SELECT NAME FROM billing.BRANCHES order by NAME";
    $res = mysql_query_decide($sql) or logError_sums($sql, 0);
    $i   = 0;

    while ($row = mysql_fetch_array($res)) {
        $branch_arr[$i] = htmlentities($row['NAME']);
        $i++;
    }

    return $branch_arr;
}

/**
 * @param $email
 * @param $Cc
 * @param $Bcc
 * @param $msg
 * @param $subject
 * @param $from
 * @return mixed
 */
function send_log_mail($email, $Cc, $Bcc, $msg, $subject, $from)
{
    $boundry       = "b" . md5(uniqid(time()));
    $MP            = "/usr/sbin/sendmail -t  ";
    $spec_envelope = 1;

    if ($spec_envelope) {
        $MP .= " -N never -R hdrs -f $from";
    }

    $fd = popen($MP, "w");
    fputs($fd, "X-Mailer: PHP3\n");
    fputs($fd, "MIME-Version:1.0 \n");
    fputs($fd, "To: $email\n");
    fputs($fd, "Cc: $Cc\n");
    fputs($fd, "Bcc: $Bcc\n");
    fputs($fd, "From: $from \n");
    fputs($fd, "Subject: $subject \n");
    fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
    fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
    fputs($fd, "$msg\r\n");
    fputs($fd, "\r\n . \r\n");
    $p = pclose($fd);
    return $p;
}

/**
 * @param $query
 * @param $sendmail
 */
function logError_sums($query, $sendmail = "0")
{
    global $smarty;
    $error_msg = "Date : " . date('Y-m-d G-i-s', time() + 37800) . "\n";
    $error_msg .= "Sql query : " . $query . "\n";
    $error_msg .= "MySql Error Message : " . addslashes(mysql_error_js()) . "\n";
    $error_msg .= "MySql Error Number : " . mysql_errno_js() . "\n";
    $error_msg .= "Script : " . $_SERVER['REQUEST_URI'] . "\n";

    $error_msg = "echo \"" . $error_msg;
    $msg       = $error_msg . "\" >> /var/www/html/web/billing/logError_sums.txt";

    passthru($msg);
    $smarty->display("logError_sums.htm");

    if ($sendmail) {
        $to      = "vibhor.garg@jeevansathi.com, aman.sharma@jeevansathi.com";
        $subject = "Error in Jeevansathi Sums";
        $from    = "js-sums@jeevansathi.com";
        mail($to, $subject, $msg, $from);
    }

    die;
}

/**
 * @param $action
 * @return null
 */
function maStripVARS_sums($action)
{
    global $_GET, $_POST;

    if (get_magic_quotes_gpc() == 0) {
        if ($action == "stripslashes") {
            if (is_array($_GET)) {
                while (list($k, $v) = each($_GET)) {
                    if (!is_array($v)) {
                        $_GET[$k]    = strip_tags(str_replace("\"", "'", $v));
                        $GLOBALS[$k] = strip_tags(str_replace("\"", "'", $GLOBALS[$k]));
                    }
                }

                reset($_GET);
            }

            if (is_array($_POST)) {
                while (list($k, $v) = each($_POST)) {
                    if (!is_array($v)) {
                        $_POST[$k]   = strip_tags(str_replace("\"", "'", $v));
                        $GLOBALS[$k] = strip_tags(str_replace("\"", "'", $GLOBALS[$k]));
                    }
                }

                reset($_POST);
            }

            return;
        }
    } else {
        if ($action == "addslashes") {
            if (is_array($_GET)) {
                while (list($k, $v) = each($_GET)) {
                    if (!is_array($v)) {
                        $_GET[$k]    = strip_tags(str_replace("\"", "'", $v));
                        $GLOBALS[$k] = strip_tags(str_replace("\"", "'", $GLOBALS[$k]));
                    }
                }

                reset($_GET);
            }

            if (is_array($_POST)) {
                while (list($k, $v) = each($_POST)) {
                    if (!is_array($v)) {
                        $_POST[$k]   = strip_tags(str_replace("\"", "'", $v));
                        $GLOBALS[$k] = strip_tags(str_replace("\"", "'", $GLOBALS[$k]));
                    }
                }

                reset($_POST);
            }

            return;
        }
    }

    if (is_array($_GET)) {
        while (list($k, $v) = each($_GET)) {
            if (!is_array($v)) {
                if ($action == "stripslashes") {
                    $_GET[$k]    = strip_tags(str_replace("\"", "'", stripslashes($v)));
                    $GLOBALS[$k] = strip_tags(str_replace("\"", "'", stripslashes($GLOBALS[$k])));
                    $GLOBALS[$k] = strip_tags(str_replace("\"", "'", stripslashes($GLOBALS[$k])));
                }

                if ($action == "addslashes") {
                    $_GET[$k]    = strip_tags(str_replace("\"", "'", addslashes($v)));
                    $GLOBALS[$k] = strip_tags(str_replace("\"", "'", addslashes($GLOBALS[$k])));
                }
            }
        }

        reset($_GET);
    }

    if (is_array($_POST)) {
        while (list($k, $v) = each($_POST)) {
            if (!is_array($v)) {
                if ($action == "stripslashes") {
                    $_POST[$k]   = strip_tags(str_replace("\"", "'", stripslashes($v)));
                    $GLOBALS[$k] = strip_tags(str_replace("\"", "'", stripslashes($GLOBALS[$k])));
                }

                if ($action == "addslashes") {
                    $_POST[$k]   = strip_tags(str_replace("\"", "'", addslashes($v)));
                    $GLOBALS[$k] = strip_tags(str_replace("\"", "'", addslashes($GLOBALS[$k])));
                }
            }
        }

        reset($_POST);
    }
}

/**
 * @param $id
 * @param $message
 * @param $flag
 */
function change_notify_mail($id, $message, $flag)
{
    if ($flag == "C") {
        $sql_wlk = "SELECT PROFILEID,WALKIN FROM billing.PURCHASES WHERE BILLID = '$id'";
        $res_wlk = mysql_query_decide($sql_wlk) or logError_sums($sql_wlk, 0);
        $row_wlk = mysql_fetch_array($res_wlk);

        $msg = "PROFILEID = " . $row_wlk['PROFILEID'] . "\n";
        $msg .= "BILLID = " . $id . "\n";
        $msg .= "Details : \n" . $message;
        $subject = "Bill ID : " . $id . " has been cancelled";
    } elseif ($flag == "E") {
        $sql = "SELECT PROFILEID, BILLID FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$id'";
        $res = mysql_query_decide($sql) or logError_sums($sql, 0);
        $row = mysql_fetch_array($res);

        $sql_wlk = "SELECT WALKIN FROM billing.PURCHASES WHERE BILLID = '$row[BILLID]'";
        $res_wlk = mysql_query_decide($sql_wlk) or logError_sums($sql_wlk, 0);
        $row_wlk = mysql_fetch_array($res_wlk);

        $msg = "PROFILEID = " . $row['PROFILEID'] . "\n";
        $msg .= "BILLID = " . $row['BILLID'] . "\n";
        $msg .= "RECEIPTID = " . $id . "\n";
        $msg .= "Details : \n" . $message;
        $subject = "Receipt ID : " . $id . " has been modified";
    }

    $db_slave   = connect_slave();
    $email      = get_email($row_wlk['WALKIN'], $db_slave);
    $boss_email = get_boss_email($row_wlk['WALKIN'], $db_slave);
    unset($db_slave);
    $db = connect_db();

    $from      = "info_sums@jeevansathi.com";
    $emailHead = "anamika.singh@jeevansathi.com";
    $to        = $emailHead . "," . $boss_email;
    $cc        = $email . "," . "shyam.kumar@jeevansathi.com, bodhsatv@naukri.com";

    mail($to, $subject, $msg, "From: $from\r\n" . "Cc: $cc\r\n" . "X-Mailer: PHP/" . phpversion());
}

/**
 * @return mixed
 */
function populate_from_source()
{
    global $offline_billing;
/*
if($offline_billing)
{
$from_source = array(
array('name'=>'Cash','value'=>'CASH'),
array('name'=>'Cheque','value'=>'CHEQUE')
);
}
else
{
 */
    $from_source = array(
        array('name' => 'Cash', 'value' => 'CASH'),
        array('name' => 'Cheque', 'value' => 'CHEQUE'),
        array('name' => 'Demand Draft', 'value' => 'DD'),
        array('name' => 'Easy Bill Cash', 'value' => 'EB_CASH'),
        array('name' => 'Easy Bill Cheque', 'value' => 'EB_CHEQUE'),
        array('name' => 'Bank Transfer Cash', 'value' => 'BANK_TRSFR_CASH'),
        array('name' => 'Bank Deposit Cheque', 'value' => 'BANK_TRSFR_CHQ'),
        array('name' => 'Online', 'value' => 'ONLINE'),
        array('name' => 'Bank Transfer Online', 'value' => 'BANK_TRSFR_ONLINE'),
        array('name' => 'TT', 'value' => 'TT'),
        array('name' => 'CCOFFILINE', 'value' => 'CCOFFLINE'),
        array('name' => 'IVR', 'value' => 'IVR'),
        array('name' => 'BLUEDART COD', 'value' => 'BLUEDART_COD'),
        array('name' => 'Ghar Pay Cheque', 'value' => 'GHAR_PAY_CHEQUE'),
        array('name' => 'Ghar Pay Cash', 'value' => 'GHAR_PAY_CASH'),
        array('name'=>'PayTM on delivery','value'=>'PayTM_ON_DELIVERY')
    );
//    }
    return $from_source;
}

/**
 * @return mixed
 */
function array_for_trans_num()
{
    $arr_trans = array("EB_CASH", "EB_CHEQUE", "ONLINE", "BANK_TRSFR_ONLINE", "TT", "CCOFFLINE", "IVR");
    return $arr_trans;
}

/**
 * @return mixed
 */
function populate_misc_category()
{
    $misc_category = array(
        array('name' => 'Banners', 'value' => 'banners'),
        array('name' => 'Mailers', 'value' => 'mailers'),
        array('name' => 'Marriage Bureau', 'value' => 'marriage_bureau'),
        array('name' => 'Others', 'value' => 'others'),
    );
    return $misc_category;
}

/**
 * @return mixed
 */
function populate_misc_saletype()
{
    $misc_saletype = array(
        array('name' => 'Credit', 'value' => 'Credit'),
        array('name' => 'Part Payment', 'value' => 'Part Payment'),
        array('name' => 'Full Payment', 'value' => 'Full Payment'),
        array('name' => 'Trial', 'value' => 'Trial'),
    );
    return $misc_saletype;
}

/**
 * @return mixed
 */
function populate_misc_saleby()
{
    $sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE!='N' and PRIVILAGE REGEXP 'BA|BU|MR|MBU' ORDER BY USERNAME";
    $res = mysql_query_decide($sql) or die(mysql_error_js());
    $i   = 0;

    while ($row = mysql_fetch_array($res)) {
        $employee[$i] = $row['USERNAME'];
        $i++;
    }

    return $employee;
}

/**
 * @param $profileid
 * @param $receiptid
 * @param $noChargeLog
 * @return mixed
 */
function charge_back_stats_log($profileid, $receiptid, $noChargeLog = '')
{
    $path = JsConstants::$docRoot;

    include_once "$path/classes/globalVariables.Class.php";
    include_once "$path/classes/Mysql.class.php";
    include_once "$path/classes/Memcache.class.php";

//    $mysql = new Mysql;
    //    $myDbName = getProfileDatabaseConnectionName($profileid, '', $mysql);
    //    $myDb = $mysql->connect("$myDbName");
    $sql_user = " SELECT ENTRY_DT,PHONE_RES,STD,PHONE_MOB,CONTACT,IPADD from newjs.JPROFILE where PROFILEID='$profileid' ";
    $res_user = mysql_query_decide($sql_user) or logError_sums($sql_user, 1);
    $row_user = mysql_fetch_array($res_user);
    $reg_dt   = $row_user["ENTRY_DT"];
    $phone    = $row_user["STD"] . "-" . $row_user["PHONE_RES"];
    $mob      = $row_user["PHONE_MOB"];
    $address  = addslashes(stripslashes($row_user["CONTACT"]));
    $ip_reg   = $row_user["IPADD"];

    $sql_payment = "SELECT a.BILLID,b.RECEIPTID,b.ENTRY_DT,a.SERVICEID,a.ADDON_SERVICEID,a.ORDERID,b.MODE,b.AMOUNT, b.TYPE, b.IPADD, b.TRANS_NUM from billing.PURCHASES as a,billing.PAYMENT_DETAIL as b where b.RECEIPTID='$receiptid' and a.BILLID=b.BILLID ";
    $res_payment = mysql_query_decide($sql_payment) or logError_sums($sql_payment, 1);
    $row_payment = mysql_fetch_array($res_payment);
    $billid      = $row_payment["BILLID"];
    $receiptid   = $row_payment["RECEIPTID"];
    $payment_dt  = $row_payment["ENTRY_DT"];
    $service     = $row_payment["SERVICEID"];
    $addon       = $row_payment["ADDON_SERVICEID"];
    $mode        = $row_payment["MODE"];
    $amount      = $row_payment["AMOUNT"];
    $type        = $row_payment["TYPE"];
    $ip_pay      = $row_payment["IPADD"];
    $ref_id      = $row_payment["TRANS_NUM"];

    if ($mode != "IVR") {
        $ord_id = $row_payment["ORDERID"];

        $sql_ord = " SELECT GATEWAY,ORDERID from billing.ORDERS where ID='$ord_id' ";
        $res_ord = mysql_query_decide($sql_ord) or logError_sums($sql_ord, 1);
        $row_ord = mysql_fetch_array($res_ord);
        $gateway = $row_ord["GATEWAY"];
        $orderid = $row_ord["ORDERID"];
        unset($ft);
        $orderid .= "-" . $ord_id;

        if ($gateway == 'PAYSEAL') {
            $sql_ft = " SELECT TXNREFNO from billing.PAYSEAL where ORDERID='$orderid' ";
            $res_ft = mysql_query_decide($sql_ft) or logError_sums($sql_ft, 1);
            $row_ft = mysql_fetch_array($res_ft);
            $ft     = $row_ft["TXNREFNO"];
        }
    }

/*
while($row_sender=mysql_fetch_array($res_sender))
{
if($row_sender["TYPE"]=='A')
$acc_me=$row_sender["cnt"];
elseif($row_sender["TYPE"]=='D')
$dec_me=$row_sender["cnt"];
elseif($row_sender["TYPE"]=='I')
$i_wtng=$row_sender["cnt"];
}
 */

    $contactResult = getResultSet("COUNT(*) AS CNT, TYPE", $profileid, "", "", "", "", "", "", "TYPE");

    for ($i = 0; $i < count($contactResult); $i++) {
        $typeCon = $contactResult[$i]["TYPE"];

        if ($typeCon == "A") {
            $acc_me = $contactResult[$i]['CNT'];
        } elseif ($typeCon == "D") {
            $dec_me = $contactResult[$i]['CNT'];
        } elseif ($typeCon == "I") {
            $i_wtng = $contactResult[$i]['CNT'];
        }
    }

/*
$sql_rec=" SELECT COUNT(*) as cnt,TYPE from newjs.CONTACTS where RECEIVER='$profileid' group by TYPE" ;
$res_rec=mysql_query_decide($sql_rec) or logError_sums($sql_rec,1);
while($row_rec=mysql_fetch_array($res_rec))
{
if($row_rec["TYPE"]=='A')
$i_acc=$row_rec["cnt"];
elseif($row_rec["TYPE"]=='D')
$i_dec=$row_rec["cnt"];
elseif($row_rec["TYPE"]=='I')
$wtng_me=$row_rec["cnt"];
}
 */

    $contactResult = getResultSet("COUNT(*) AS CNT, TYPE", "", "", $profileid, "", "", "", "", "TYPE");

    for ($i = 0; $i < count($contactResult); $i++) {
        $typeCon = $contactResult[$i]["TYPE"];

        if ($typeCon == "A") {
            $i_acc = $contactResult[$i]['CNT'];
        } elseif ($typeCon == "D") {
            $i_dec = $contactResult[$i]['CNT'];
        } elseif ($typeCon == "I") {
            $wtng_me = $contactResult[$i]['CNT'];
        }
    }
    $pidShard        = JsDbSharding::getShardNo($profileid, 'slave');
    $dbMessageLogObj = new NEWJS_MESSAGE_LOG($pidShard);
    $res             = $dbMessageLogObj->getMessageLogBilling($profileid, 'SENDER', '');

//    $sql_con_made = " SELECT RECEIVER,DATE,IP from newjs.MESSAGE_LOG where SENDER='$profileid' order by ID desc limit 20";
    //    $res_con_made = $mysql->executeQuery($sql_con_made, $myDb) or logError_sums($sql_con_made, 1);

//    while ($row_con_made = $mysql->fetchArray($res_con_made)) {
    foreach ($res as $key => $row_con_made) {
        $sql = "select USERNAME from newjs.JPROFILE where PROFILEID=$row_con_made[RECEIVER] ";
        $res = mysql_query_decide($sql) or logError_sums($sql, 1);
        $row = mysql_fetch_array($res);

        $con_made .= $row["USERNAME"] . "           " . $row_con_made["DATE"] . " EST           " . $row_con_made["IP"] . "\n";
    }

    addslashes(stripslashes($con_made));

    $res = $dbMessageLogObj->getMessageLogBilling($profileid, 'RECEIVER', 'A');

//    $sql_con_acc = " SELECT SENDER,DATE,IP from newjs.MESSAGE_LOG where RECEIVER='$profileid' and TYPE='A' order by ID desc limit 20";
    //    $res_con_acc = $mysql->executeQuery($sql_con_acc, $myDb) or logError_sums($sql_con_acc, 1);

//    while ($row_con_acc = $mysql->fetchArray($res_con_acc)) {
    foreach ($res as $key => $row_con_acc) {
        $sql = "select USERNAME from newjs.JPROFILE where PROFILEID=$row_con_acc[SENDER] ";
        $res = mysql_query_decide($sql) or logError_sums($sql, 1);
        $row = mysql_fetch_array($res);

        $con_acc .= $row["USERNAME"] . "            " . $row_con_acc["DATE"] . " EST             " . $row_con_acc["IP"] . "\n";
    }

    addslashes(stripslashes($con_acc));

    if ($noChargeLog) {
        if ($billid == '') {
            $billid = 0;
        }

        if ($receiptid == '') {
            $receiptid = 0;
        }

        if ($i_acc == '') {
            $i_acc = 0;
        }

        if ($i_dec == '') {
            $i_dec = 0;
        }

        if ($wtng_me == '') {
            $wtng_me = 0;
        }

        if ($acc_me == '') {
            $acc_me = 0;
        }

        if ($dec_me == '') {
            $dec_me = 0;
        }

        if ($i_wtng == '') {
            $i_wtng = 0;
        }

        $resultSetArr                    = array();
        $resultSetArr['PROFILEID']       = $profileid;
        $resultSetArr['REGISTRATION_DT'] = $reg_dt;
        $resultSetArr['PHONE']           = $phone;
        $resultSetArr['MOBILE']          = $mob;
        $resultSetArr['CONTACT_ADDRESS'] = $address;
        $resultSetArr['IP_REG']          = $ip_reg;
        $resultSetArr['BILLID']          = $billid;
        $resultSetArr['RECEIPTID']       = $receiptid;
        $resultSetArr['PAYMENT_DT']      = $payment_dt;
        $resultSetArr['SERVICEID']       = $service;
        $resultSetArr['ADDON']           = $addon;
        $resultSetArr['MODE']            = $mode;
        $resultSetArr['AMOUNT_PAID']     = $amount;
        $resultSetArr['TYPE']            = $type;
        $resultSetArr['IP_PAYMENT']      = $ip_pay;
        $resultSetArr['REF_NO']          = $ref_id;
        $resultSetArr['FT_NO']           = $ft;
        $resultSetArr['ORDERID']         = $orderid;
        $resultSetArr['I_ACC']           = $i_acc;
        $resultSetArr['I_DEC']           = $i_dec;
        $resultSetArr['WTNG_ME']         = $wtng_me;
        $resultSetArr['ACC_ME']          = $acc_me;
        $resultSetArr['DEC_ME']          = $dec_me;
        $resultSetArr['I_WTNG']          = $i_wtng;
        $resultSetArr['CONTACTS_MADE']   = $con_made;
        $resultSetArr['CONTACTS_ACC']    = $con_acc;

        return $resultSetArr;
    } else {
        $sql_insert = " INSERT INTO billing.CHARGE_BACK_LOG(PROFILEID, REGISTRATION_DT, PHONE, MOBILE, CONTACT_ADDRESS, IP_REG, BILLID, RECEIPTID, PAYMENT_DT, SERVICEID, ADDON, MODE, AMOUNT_PAID,TYPE, IP_PAYMENT, REF_NO, FT_NO, ORDERID, I_ACC, I_DEC, WTNG_ME,ACC_ME, DEC_ME, I_WTNG, CONTACTS_MADE, CONTACTS_ACC,ENTRY_DT) VALUES('$profileid','$reg_dt','$phone','$mob','$address','$ip_reg','$billid','$receiptid','$payment_dt','$service','$addon','$mode','$amount','$type','$ip_pay','$ref_id','$ft','$orderid','$i_acc','$i_dec','$wtng_me','$acc_me','$dec_me','$i_wtng','$con_made','$con_acc',now()) ";
        mysql_query_decide($sql_insert) or logError_sums($sql_insert, 1);
    }
}

/**
 * @param $from
 * @return mixed
 */
function populate_reject_reason($from)
{
//The array has been populated conditionally on April 26th 2007 as the values for populating the reject reason drop down was completely changed after the module was made live on April 18th 2007. Here we cannot go forward with only new values for dropdown as old reject reason exist in the database and removing old drop down values results in blank rows being displayed in MIS. Also, drop down should show few different Reject reason's in case of Bank Transfer Record than in Confirm Client Module. We require all the values to display the MIS properly.
    if ($from == "BANK_TRANSFER_RECORD") {
        $reject_reason_arr = array(
            array('name' => 'Testing', 'value' => 'TST'),
            array('name' => 'Not Interested', 'value' => 'NI'),
            array('name' => 'Revert not received within 3 buisiness days of mail sent', 'value' => 'RNR'),
            array('name' => 'Customer does not recognise the transaction', 'value' => 'DRT'),
            array('name' => 'Customer will update correct details', 'value' => 'UCD'),
            array('name' => 'Customer will make fresh payment', 'value' => 'MFP'),
            array('name' => 'Customer will send Bank Certificate', 'value' => 'SBC'),
            array('name' => 'Already Married', 'value' => 'AM'),
            array('name' => 'Incorrect Contact Number', 'value' => 'ICN'),
            array('name' => 'Incorrect Email ID', 'value' => 'IEI'),
            array('name' => 'Deceased', 'value' => 'DES'),
            array('name' => 'Paid at another Matrimonial Site', 'value' => 'PMS'),
            array('name' => 'Profile Deleted', 'value' => 'PD'),
        );
    } elseif ($from == "CONFIRM_CLIENT") {
        $reject_reason_arr = array(
            array('name' => 'Testing', 'value' => 'TST'),
            array('name' => 'Not Interested', 'value' => 'NI'),
            array('name' => 'Will avail the Services later', 'value' => 'ASL'),
            array('name' => 'Incorrect Contact Number', 'value' => 'ICN'),
            array('name' => 'Incorrect Email ID', 'value' => 'IEI'),
            array('name' => 'Non Serious Profile', 'value' => 'NSP'),
            array('name' => 'Customer not Available', 'value' => 'CNA'),
            array('name' => 'Already Married', 'value' => 'AM'),
            array('name' => 'Already Paid', 'value' => 'AP'),
            array('name' => 'Proposal in Pipeline', 'value' => 'PIP'),
            array('name' => 'Deceased', 'value' => 'DES'),
            array('name' => 'Paid at another Matrimonial Site', 'value' => 'PMS'),
            array('name' => 'Never Raised the Request', 'value' => 'NRR'),
            array('name' => 'Profile Deleted', 'value' => 'PD'),
            array('name' => 'Wants to Pay on another Profile', 'value' => 'POAP'),
        );
    } elseif ($from == "MIS") {
        $reject_reason_arr = array(
            array('name' => 'Testing', 'value' => 'TST'),
            array('name' => 'Not Interested', 'value' => 'NI'),
            array('name' => 'Will avail the Services later', 'value' => 'ASL'),
            array('name' => 'Incorrect Contact Number', 'value' => 'ICN'),
            array('name' => 'Incorrect Email ID', 'value' => 'IEI'),
            array('name' => 'Non Serious Profile', 'value' => 'NSP'),
            array('name' => 'Customer not Available', 'value' => 'CNA'),
            array('name' => 'Already Married', 'value' => 'AM'),
            array('name' => 'Already Paid', 'value' => 'AP'),
            array('name' => 'Proposal in Pipeline', 'value' => 'PIP'),
            array('name' => 'Deceased', 'value' => 'DES'),
            array('name' => 'Paid at another Matrimonial Site', 'value' => 'PMS'),
            array('name' => 'Never Raised the Request', 'value' => 'NRR'),
            array('name' => 'Profile Deleted', 'value' => 'PD'),
            array('name' => 'Wants to Pay on another Profile', 'value' => 'POAP'),
            array('name' => 'Revert not received within 3 buisiness days of mail sent', 'value' => 'RNR'),
            array('name' => 'Customer does not recognise the transaction', 'value' => 'DRT'),
            array('name' => 'Customer will update correct details', 'value' => 'UCD'),
            array('name' => 'Customer will make fresh payment', 'value' => 'MFP'),
            array('name' => 'Customer will send Bank Certificate', 'value' => 'SBC'),
        );
    }

    return $reject_reason_arr;
}

/**
 * @param $order_id
 * @param $entry_by
 * @param $rej_reason
 * @param $from
 */
function reject_reason($order_id, $entry_by, $rej_reason, $from)
{
    $sql_ins = "INSERT INTO billing.REJECTED_RECORDS(ORDER_ID,REJECTED_BY,REJECT_REASON,ENTRY_DT) VALUES('$order_id','$entry_by','$rej_reason',now())";
    mysql_query_decide($sql_ins) or die("$sql_ins" . mysql_error_js());
    if ($from == "BANK_TRANSFER_RECORD") {
        $sql_upd = "UPDATE billing.CHEQUE_REQ_DETAILS SET  STATUS='CANCEL' WHERE REQUEST_ID = '$order_id'";
        mysql_query_decide($sql_upd) or die("$sql_upd" . mysql_error_js());

        $sql_upd = "UPDATE incentive.PAYMENT_COLLECT SET ACC_REJ_MAIL_BY='$entry_by' WHERE ID = '$order_id'";
        mysql_query_decide($sql_upd) or die("$sql_upd" . mysql_error_js());
    } elseif ($from == "CONFIRM_CLIENT") {
        $sql_ins = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,PREF_TIME,COURIER_TYPE,REF_ID) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,PREF_TIME,COURIER_TYPE,'$order_id' FROM incentive.PAYMENT_COLLECT where ID='$order_id'";
        mysql_query_decide($sql_ins) or die("$sql_ins" . mysql_error_js());
        $sql_upd = "UPDATE incentive.PAYMENT_COLLECT SET DISPLAY = 'N',CONFIRM='N', ENTRYBY='$entry_by',ENTRY_DT=now(),COMMENTS = '$rej_reason', ACC_REJ_MAIL_BY ='$entry_by' WHERE ID ='$order_id'";
        mysql_query_decide($sql_upd) or die("$sql_upd" . mysql_error_js());
    }
}

if (!function_exists("multi_array_search")) {
    /**
     * @param $search_value
     * @param $the_array
     * @return mixed
     */
    function multi_array_search($search_value, $the_array)
    {
        if (is_array($the_array)) {
            foreach ($the_array as $key => $value) {
                $result = multi_array_search($search_value, $value);
                if (is_array($result)) {
                    $return = $result;
                    array_unshift($return, $key);
                    return $return;
                } elseif ($result == true) {
                    $return[] = $key;
                    return $return;
                }
            }

            return false;
        } else {
            if ($search_value == $the_array) {
                return true;
            } else {
                return false;
            }
        }
    }
}

/**
 * @param $service_expiry_dt
 * @return mixed
 */
function get_expiry_dt_color($service_expiry_dt)
{
    $date_difference = getTimeDiff(date('Y-m-d'), $service_expiry_dt);
    if ($date_difference > 10) {
        $expiry_date_color = "green";
    } elseif ($date_difference > 0 && $date_difference <= 10) {
        $expiry_date_color = "#FF9933";
    } elseif ($date_difference == 0) {
        $expiry_date_color = "red";
    }

    return $expiry_date_color;
}

/**
 * @param $to
 * @param $msg
 * @param $subject
 * @param $from
 * @param $cc
 * @param $bcc
 * @param $attach
 * @param $from_name
 */
function send_rtf_email($to, $msg = "", $subject = "", $from = "", $cc = "", $bcc = "", $attach = "", $from_name = "Jeevansathi.com")
{
    if (trim(strtolower($to)) != "abc@mail.com" && !stristr($to, "@jsxyz.com")) {
        $boundry = "b" . md5(uniqid(time()));
        if ($subject == "") {
            $announce_subject = "Info from jeevansathi.com";
        } else {
            $announce_subject = $subject;
        }

        if ($from == "") {
            $announce_from_email = "webmaster@jeevansathi.com";
        } else {
            $announce_from_email = $from;
        }

        $announce_to_email = $to;

        //$from_name="Jeevansathi.com";

        $MP = "/usr/sbin/sendmail -t";
        $MP .= " -N never -R hdrs -f $announce_from_email";

        $fd = popen($MP, "w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $announce_to_email\n");

        if ($cc != "") {
            fputs($fd, "Cc: $cc\n");
        }

        if ($bcc != "") {
            fputs($fd, "Bcc: $bcc\n");
        }

        fputs($fd, "From: $from_name <$announce_from_email> \n");
        fputs($fd, "Subject: $announce_subject \n");

        fputs($fd, "Content-Type: multipart/mixed; boundary=\"$boundry\"");
        fputs($fd, "This is a multi-part message in MIME format\n\n");
        fputs($fd, "--{$boundry}\n");
        fputs($fd, "Content-Type: text/html; charset=\"iso-8859-1\"\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \n\n");
        fputs($fd, "$msg \n\n");
        fputs($fd, "--{$boundry}\n");
        fputs($fd, "Content-Type: text/rtf\r\n");
        fputs($fd, "Content-Disposition: attachment; filename=\"BILL.rtf\"\r\n\r\n");
        fputs($fd, $attach);
        $p = pclose($fd);
        return $p;
    }
}

/**
 * @param $code
 * @param $profileid
 * @return mixed
 */
function check_voucher_discount_code($code, $profileid = '')
{
    $sql_vc = "SELECT PERCENT,MESSAGE FROM newjs.DISCOUNT_CODE_MULTIPLE WHERE CODE='" . strtoupper($code) . "' AND ACTIVE='Y'";
    $res_vc = mysql_query_decide($sql_vc) or logError_sums($sql_vc, 0);

    if ($row_vc = mysql_fetch_array($res_vc)) {
        $return["CODE_EXISTS"] = 1;
        $return["PERCENT"]     = $row_vc['PERCENT'];
        $return["MESSAGE"]     = $row_vc['MESSAGE'];
    } else {
        $sql_vc = "SELECT DISCOUNT_PERCENT, DISCOUNT_MESSAGE,NAME_OF_CODE FROM newjs.DISCOUNT_CODE WHERE CODE='$code' AND USED='N' AND ACTIVE='Y'";
        $res_vc = mysql_query_decide($sql_vc) or logError_sums($sql_vc, 0);

        if ($row_vc = mysql_fetch_array($res_vc)) {
            if (strstr($row_vc['NAME_OF_CODE'], '40% discount') && $profileid > 0) {
                $sql_p = "SELECT CODE from mailer.DISCOUNT_MAILER where PROFILE_ID='$profileid' AND CODE='$code' ";
                $res_p = mysql_query_decide($sql_p) or logError_sums($sql_p, 0);

                if ($row_p = mysql_fetch_array($res_p)) {
                    $return["CODE_EXISTS"] = 1;
                    $return["PERCENT"]     = $row_vc['DISCOUNT_PERCENT'];
                    $return["MESSAGE"]     = $row_vc['DISCOUNT_MESSAGE'];
                } else {
                    $return["CODE_EXISTS"] = 0;
                    $return["PERCENT"]     = 0;
                    $return["MESSAGE"]     = 0;
                }
            } else {
                $return["CODE_EXISTS"] = 1;
                $return["PERCENT"]     = $row_vc['DISCOUNT_PERCENT'];
                $return["MESSAGE"]     = $row_vc['DISCOUNT_MESSAGE'];
            }
        } else {
            $return["CODE_EXISTS"] = 0;
            $return["PERCENT"]     = 0;
            $return["MESSAGE"]     = 0;
        }
    }

    return $return;
}

/**
 * @param $profileid
 * @param $code
 * @param $cancel
 * @param $payment
 * @param $billid
 */
function mark_voucher_code($profileid, $code, $cancel = "", $payment = "", $billid = "")
{
    if ("BOOK" == $cancel) {
        $sql_upd = "UPDATE newjs.DISCOUNT_CODE SET USED_BY='$profileid', USED_DT=NOW() WHERE CODE='$code'";
        mysql_query_decide($sql_upd) or logError_sums($sql_upd, 1);
        $sql = "INSERT IGNORE INTO billing.VOUCHER_MARKING(VOUCHER_CODE,PROFILEID,ENTRY_DT) VALUES('$code','$profileid',NOW())";
        mysql_query_decide($sql) or logError_sums($sql, 1);
/*                if(@mysql_affected_rows_js() == 0)
{
$sql_del = "DELETE FROM newjs.DISCOUNT_CODE_USED WHERE PROFILEID='$profileid' AND CODE='".strtoupper($code)."'";
mysql_query_decide($sql_del) or logError_sums($sql_del,1);
}
 */} else {
        $sql_upd = "UPDATE newjs.DISCOUNT_CODE SET USED='Y', USED_BY='$profileid', USED_DT=NOW()";

        if ("SUCCESSFUL" == $payment) {
            $sql_upd .= ", PAYMENT_SUCCESSFUL = 'Y', BILLID='$billid'";
        }

        $sql_upd .= " WHERE CODE='$code'";
        mysql_query_decide($sql_upd) or logError_sums($sql_upd, 1);

        if (@mysql_affected_rows_js() == 0) {
            $sql = "SELECT COUNT(*) AS COUNT FROM newjs.DISCOUNT_CODE_USED WHERE PROFILEID='$profileid' && CODE='" . strtoupper($code) . "'";
            $res = mysql_query_decide($sql) or logError_sums($sql_upd, 1);
            $row = mysql_fetch_array($res);

            if ($row['COUNT'] > 0) {
                $sql_upd = "UPDATE newjs.DISCOUNT_CODE_USED SET USED_DT=NOW()";

                if ("SUCCESSFUL" == $payment) {
                    $sql_upd .= ", PAYMENT_SUCCESSFUL = 'Y', BILLID='$billid'";
                }

                $sql_upd .= " WHERE PROFILEID='$profileid'";
                mysql_query_decide($sql_upd) or logError_sums($sql_upd, 1);
            } else {
                if ("SUCCESSFUL" == $payment) {
                    $sql_ins = "INSERT INTO newjs.DISCOUNT_CODE_USED(PROFILEID,CODE,USED_DT,BILLID,PAYMENT_SUCCESSFUL) VALUES('$profileid','$code',NOW(),'$billid','Y')";
                } else {
                    $sql_ins = "INSERT INTO newjs.DISCOUNT_CODE_USED(PROFILEID,CODE) VALUES('$profileid','$code')";
                }

                mysql_query_decide($sql_ins) or logError_sums($sql_ins, 1);
            }
        }
    }
}

/**
 * @param $profileid
 */
function stop_offline_service($profileid)
{
    if (strstr($profileid, ",")) {
        $sql_off = "SELECT PROFILEID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID IN ('$profileid')";
        $res_off = mysql_query_decide($sql_off) or logError_sums($sql_off, 0);

        while ($row_off = mysql_fetch_array($res_off)) {
            $profileid_arr[] = $row_off['PROFILEID'];
        }

        $profileid_str = @implode("','", $profileid_arr);

        if ($profileid_str) {
            /*$sql_jp_upd = "UPDATE newjs.JPROFILE SET PREACTIVATED=IF(ACTIVATED<>'D',ACTIVATED,PREACTIVATED), ACTIVATED='D',activatedKey=0 WHERE PROFILEID IN ('$profileid_str')";
        mysql_query_decide($sql_jp_upd) or logError_sums($sql_jp_upd,1);*/
        }

        $sql_upd = "UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE='N' WHERE PROFILEID IN ('$profileid')";
    } else {
        $sql_upd = "UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE='N' WHERE PROFILEID='$profileid'";
    }

    mysql_query_decide($sql_upd) or logError_sums($sql_upd, 1);

    $sql_jp_upd = "UPDATE jsadmin.OFFLINE_MATCHES SET SHOW_ONLINE='N' WHERE PROFILEID IN ('$profileid')";
    mysql_query_decide($sql_jp_upd) or logError_sums($sql_jp_upd, 1);
}

/**
 * @param $serviceid
 * @return mixed
 */
function get_service_type($serviceid)
{
    $serviceid = str_replace("'", "", $serviceid);

    $string_length = strlen($serviceid);
    $string        = substr($serviceid, 0, $string_length);

    if ($string) {
        while (!ctype_alpha($string)) {
            $string_length--;
            $string = substr($string, 0, $string_length);
        }
    }

    return $string;
}

/**
 * @param $profileid
 * @return null
 */
function check_special_discount($profileid)
{
    return;
    global $smarty;
    $mtongue_arr = array("10", "33", "27", "7", "28", "13", "14", "15", "30", "20", "12", "19", "11", "34", "8", "9");
    $city_arr    = array("JK", "HP", "PU", "DE00", "HA", "WB", "UP", "UT", "BI", "JH", "CH", "MH", "GU", "RA");

    $sql      = "SELECT MTONGUE,CITY_RES FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
    $res      = mysql_query_decide($sql) or logError_sums($sql, 0);
    $row      = mysql_fetch_array($res);
    $mtongue  = $row['MTONGUE'];
    $city_res = $row['CITY_RES'];

    for ($i = 0; $i < count($city_arr); $i++) {
        if (strstr($city_res, $city_arr[$i])) {
            $city_for_holi = 1;
            break;
        } else {
            $city_for_holi = 0;
        }
    }

    if (@in_array($mtongue, $mtongue_arr) || $city_for_holi) {
        $smarty->assign("SPECIAL_DISCOUNT", 1);
        $smarty->assign("SPECIAL_DISCOUNT_MSG", "This user can avail 10% discount for holi");
    }
}

/**
 * @param $profileid
 * @return int
 */
function check_marked_for_deletion($profileid)
{
    $sql = "SELECT COUNT(*) AS COUNT FROM jsadmin.MARK_DELETE WHERE PROFILEID='$profileid' AND STATUS='M'";
    $res = mysql_query_decide($sql) or logError_sums($sql, 0);
    $row = mysql_fetch_array($res);

    if ($row['COUNT'] > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**********************************************************************************************************
Function   :    evalue_privacy
Issue      :    2847 : contact details for evalue members to be revealed
Created By :    Sadaf Alam
Created On :    26 March 2008

Parameters :    $profileid : profileid of user buying evalue service

Return     :    1 if confirm contact details mailer not be sent, 0 otherwise

Purpose    :    This function checks the validity of contact details of the user buying the evalue service
and if all are valid, then confirm contact details mailer is not sent, Instead all contact
details are unhidden (if any hidden). If any contact detail is invalid then, the profile is
marked to send the confirm contact details mailer
 ************************************************************************************************************/
/**
 * @param $profileid
 * @param $subscription
 */
function evalue_privacy($profileid, $subscription = '')
{
    global $db;
    $sql = "SELECT USERNAME,EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,SHOWMESSENGER FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
    $res = mysql_query_decide($sql) or logError_sums($sql, 1);

    if ($row = mysql_fetch_assoc($res)) {
        if ($row["SHOWPHONE_RES"] == "N" || $row["SHOWPHONE_MOB"] == "N" || $row["SHOWMESSENGER"] == "N") {
            $jprofileObj = JProfileUpdateLib::getInstance();
            $updateStr   = "SHOWPHONE_RES='Y',SHOWPHONE_MOB='Y',SHOWMESSENGER='Y'";
            $paramArr    = $jprofileObj->convertUpdateStrToArray($updateStr);

            if ($subscription) {
                if (strstr($subscription, "S")) {
                    $sub1 = substr($subscription, 0, strlen($subscription) - 2);
                }
                $paramArr['SUBSCRIPTION'] = $sub1;
            }

            $updateStatus = $jprofileObj->editJPROFILE($paramArr, $profileid, 'PROFILEID');
            if (!$updateStatus) {
                include_once JsConstants::$cronDocRoot . "/lib/model/lib/SendMail.class.php";
                SendMail::send_email("avneet.bindra@jeevansathi", "jProfile Update failed in comfunc_sums.php", "comfunc_sums.php error", "js-sums@jeevansathi.com");
            }

            $from     = "info@jeevansathi.com";
            $subject  = "Your contact privacy settings have been changed";
            $to       = $row["EMAIL"];
            $checksum = md5($profileid) . "i" . $profileid;
            $msg      = "Dear " . $row["USERNAME"];
            $msg .= "\n\n";
            $msg .= "We noticed that you had chosen to hide your contact details from others. Now that you have taken an eValue Pack Membership on jeevansathi.com, to get full value of your subscription, we thought we would change your privacy settings to allow people to see your contact details.";
            $msg .= "\n\n";
            $msg .= "In case you still want to hide your contact details, you can do so by clicking <a href=\"http://www.jeevansathi.com/profile/edit_profile.php?checksum=$checksum&EditWhat=ContactDetails\">here</a> and changing your privacy settings.";
            $msg .= "\n\n";
            $msg .= "Warm Regards,";
            $msg .= "\n\n";
            $msg .= "The Jeevansathi.com Team";
            $msg = nl2br($msg);
            send_mail($to, '', '', $msg, $subject, $from);
        } else {
            if ($subscription) {
                if (strstr($subscription, "S")) {
                    $sub1 = substr($subscription, 0, strlen($subscription) - 2);
                }

                /*$sqlup="UPDATE newjs.JPROFILE SET SUBSCRIPTION='$sub1' WHERE PROFILEID='$profileid'";
                mysql_query_decide($sqlup) or logError_sums($sqlup,1);*/
                $jprofileObj = JProfileUpdateLib::getInstance();
                $paramArr    = array("SUBSCRIPTION" => $sub1);
                $jprofileObj->editJPROFILE($paramArr, $profileid, 'PROFILEID');
            }
        }
    }
}

/**
 * @param $profileid
 * @return int
 */
function is_profile_offline($profileid)
{
    $sql = "SELECT COUNT(*) AS COUNT FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND SOURCE='ofl_prof'";
    $res = mysql_query_decide($sql) or logError_sums($sql, 1);
    $row = mysql_fetch_array($res);

    if ($row["COUNT"] > 0) {
        return 1;
    } else {
        return 0;
    }
}
