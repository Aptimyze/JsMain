<?php

/***************************************************************************************************************************
FILE NAME       : orderonphone.php
CREATED BY      : Ankit Aggarwal
DATE            : 3rd December 2008.
***************************************************************************************************************************/
include_once ('../connect.inc');
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
include_once ('functions.php');
$serObj = new Services;
$service_main = $serObj->getTrueService($service_main);
$services = $service_main;
$db = connect_db();

$data = authenticated($checksum);
$profileid = $data['PROFILEID'];
if ($profileid) {
    $sql_order = "SELECT CITY_RES,COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID = $profileid and activatedKey=1 ";
    $result = mysql_query_decide($sql_order) or logError_sums($sql_order, 1);
    $row = mysql_fetch_assoc($result);
    if ($row[COUNTRY_RES] == '51') {
        $cur_type = 'RS';
        $ddd = 1;
        if ($row[CITY_RES] == 'KA02') $phone_std = '080-40439009, 080- 40439008';
        elseif ($row[CITY_RES] == 'HA02' || $row[CITY_RES] == 'UP12' || $row[CITY_RES] == 'HA03' || $row[CITY_RES] == 'UP25' || $row[CITY_RES] == 'DE00') $phone_std = '0120-4393500';
        elseif ($row[CITY_RES] == 'MH08') $phone_std = '020 - 41407101, 66022611';
        elseif ($row[CITY_RES] == 'MH04') $phone_std = '022 - 67029730 / 31 / 32';
        else $ddd = 2;
        $smarty->assign('ddd', $ddd);
        $smarty->assign('phone_std', $phone_std);
    } 
    else {
        $cur_type = 'DOL';
    }
}
$smarty->assign("bms_membership", 1);
$smarty->assign("con_chk", '4');
$smarty->assign('CURRENCY', $cur_type);
$smarty->assign('DISCOUNT_TYPE', $DISCOUNT_TYPE);
if ($avail_discount == 'Y') {
    $smarty->assign('voucher_code', $voucher_code);
    $smarty->assign('DISCOUNT_MSG', $DISCOUNT_MSG);
    $smarty->assign('DISCOUNT', $DISCOUNT);
    $smarty->assign('avail_discount', $avail_discount);
}
$check = 0;
global $renew_discount_rate;
$memObj = new Membership;
if ($profileid) {
    $memObj->setProfileid($profileid);
    $disc = $memObj->isRenewable($profileid);
    savehits_payment($profileid, "3");
    
    if ($from_source) {
        sourcetracking_payment($profileid, '3', $from_source);
        $smarty->assign('from_source', $from_source);
    }
    $smarty->assign('DISC', $disc);
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
            $cb3 = 1;
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
        $serv_name = $serv_name . "<span class=\"black\" style=\"margin:0; padding:0;\"> [ $call_dur Direct Calls Available ]</span><br><b class='mar_clr t14'>$off_str</b>";
        $serv_name = $serObj->getServiceName($main_service);
        $serv_name = $serv_name[$main_service][NAME];
        $serv_price = $serObj->getServicesAmount($main_service, $cur_type);
        $serv_price1 = $serv_price[$main_service][PRICE];
        $PRICE+= $serv_price1;
        $call_dur = $serObj->getServiceDirectCalls($main_service);
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
        $serv_name = $serv_name[$A_arr][NAME];
        $serv_price = $serObj->getServicesAmount($A_arr, $cur_type);
        $serv_price = $serv_price[$A_arr][PRICE];
        $PRICE = $PRICE + $serv_price;
        $smarty->assign('ANAME', $serv_name);
        $smarty->assign('APRICE', $serv_price);
        $check+= 1;
        $smarty->assign('FORA', ++$ii);
        $smarty->assign('ASTRO', $A_arr);
        $assem.= $A_arr . ",";
    }
    $PRICE = $memObj->forPage3($main_service, $PRICE, $serv_price1);
    if (!$disc && $avail_discount == "Y") {
        $returned_val = check_voucher_discount_code($voucher_code, $profileid);
        if ($returned_val['CODE_EXISTS'] > 0 || "Y" == $rem) {
            $vdr = $returned_val['PERCENT'];
            if ($vdr) $voucher_discount_rate = $vdr;
            $subtotal2 = round((($voucher_discount_rate / 100) * $serv_price1), 2);
            if ($cur_type == "DOL") $total = round($subtotal2);
            else $total = floor($subtotal2);
            $serv_price1 = $subtotal2;
            $discount = $serv_price1;
            $PRICE = ceil($PRICE - $serv_price1);
        }
    }
    
    $smarty->assign('CHECK', $check);
    $smarty->assign('IDS', $assem);
    $smarty->assign('CHECK1', $check);
    $smarty->assign('PRICE', $PRICE);
}

if ($data) {
    $smarty->assign("checksum", $checksum);
    $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));
    $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
    $smarty->display("pg/orderonphone.htm");
} 
else {
    Timedout();
}
?>                                                                                                                                                                                                                       
